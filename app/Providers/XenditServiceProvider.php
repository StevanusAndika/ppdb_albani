<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('xendit', function ($app) {
            return new class {
                protected $secretKey;
                protected $baseUrl;

                public function __construct()
                {
                    $this->secretKey = config('xendit.secret_key');
                    $this->baseUrl = config('xendit.base_url', 'https://api.xendit.co');
                }

                /**
                 * Create invoice payment - PERBAIKAN: Fix undefined key
                 */
                public function createInvoice(array $data): array
                {
                    try {
                        // Validasi data yang diperlukan
                        if (!isset($data['external_id']) || !isset($data['amount']) || !isset($data['description'])) {
                            return [
                                'success' => false,
                                'message' => 'Data yang diperlukan tidak lengkap'
                            ];
                        }

                        // Siapkan payload untuk Xendit
                        $payload = [
                            'external_id' => $data['external_id'],
                            'amount' => $data['amount'],
                            'description' => $data['description'],
                            'currency' => 'IDR',
                            'success_redirect_url' => route('santri.payments.success'),
                            'failure_redirect_url' => route('santri.payments.failed'),
                        ];

                        // Tambahkan customer data jika ada
                        if (isset($data['payer_email'])) {
                            $payload['payer_email'] = $data['payer_email'];

                            $customerData = [
                                'given_names' => $data['customer']['given_names'] ?? 'Customer',
                                'email' => $data['payer_email']
                            ];

                            // Tambahkan mobile number jika ada
                            if (isset($data['customer']['mobile_number'])) {
                                $customerData['mobile_number'] = $data['customer']['mobile_number'];
                            }

                            $payload['customer'] = $customerData;
                        }

                        // Tambahkan items jika ada
                        if (isset($data['items']) && is_array($data['items'])) {
                            $payload['items'] = $data['items'];
                        }

                        // Tambahkan customer notification preference
                        $payload['customer_notification_preference'] = [
                            'invoice_created' => ['whatsapp', 'email', 'sms'],
                            'invoice_reminder' => ['whatsapp', 'email', 'sms'],
                            'invoice_expired' => ['whatsapp', 'email', 'sms'],
                            'invoice_paid' => ['whatsapp', 'email', 'sms']
                        ];

                        Log::info('Xendit Payload:', $payload);

                        $response = Http::withHeaders([
                            'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                            'Content-Type' => 'application/json'
                        ])->post($this->baseUrl . '/v2/invoices', $payload);

                        $result = $response->json();

                        if ($response->successful()) {
                            Log::info('Xendit invoice created successfully', [
                                'external_id' => $data['external_id'],
                                'response' => $result
                            ]);
                            return ['success' => true, 'data' => $result];
                        } else {
                            Log::error('Xendit API error', [
                                'external_id' => $data['external_id'],
                                'status' => $response->status(),
                                'response' => $result
                            ]);
                            return [
                                'success' => false,
                                'message' => $result['message'] ?? 'Gagal membuat invoice. Status: ' . $response->status()
                            ];
                        }
                    } catch (\Exception $e) {
                        Log::error('Xendit service exception', [
                            'external_id' => $data['external_id'] ?? 'unknown',
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return [
                            'success' => false,
                            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                        ];
                    }
                }

                /**
                 * Get invoice by ID
                 */
                public function getInvoice(string $invoiceId): array
                {
                    try {
                        $response = Http::withHeaders([
                            'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
                        ])->get($this->baseUrl . '/v2/invoices/' . $invoiceId);

                        $result = $response->json();

                        if ($response->successful()) {
                            return ['success' => true, 'data' => $result];
                        } else {
                            Log::error('Xendit get invoice error', [
                                'invoice_id' => $invoiceId,
                                'response' => $result
                            ]);
                            return ['success' => false, 'message' => $result['message'] ?? 'Gagal mengambil data invoice'];
                        }
                    } catch (\Exception $e) {
                        Log::error('Xendit get invoice exception', [
                            'invoice_id' => $invoiceId,
                            'error' => $e->getMessage()
                        ]);
                        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
                    }
                }

                /**
                 * Expire invoice
                 */
                public function expireInvoice(string $invoiceId): array
                {
                    try {
                        $response = Http::withHeaders([
                            'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
                        ])->post($this->baseUrl . '/invoices/' . $invoiceId . '/expire');

                        $result = $response->json();

                        if ($response->successful()) {
                            return ['success' => true, 'data' => $result];
                        } else {
                            Log::error('Xendit expire invoice error', [
                                'invoice_id' => $invoiceId,
                                'response' => $result
                            ]);
                            return ['success' => false, 'message' => $result['message'] ?? 'Gagal mengekspire invoice'];
                        }
                    } catch (\Exception $e) {
                        Log::error('Xendit expire invoice exception', [
                            'invoice_id' => $invoiceId,
                            'error' => $e->getMessage()
                        ]);
                        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
                    }
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__.'/../../config/xendit.php' => config_path('xendit.php'),
        ], 'xendit-config');
    }
}
