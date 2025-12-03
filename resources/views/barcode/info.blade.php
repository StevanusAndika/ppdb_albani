<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Calon Santri - {{ $registration->id_pendaftaran }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: #057572;
            min-height: 100vh;
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Pondok Pesantren Al-Quran Bani Syahid</h1>
            <p class="text-white/90">Informasi Calon Santri</p>
        </div>

        <!-- Main Card -->
        <div class="card-glass rounded-2xl shadow-2xl p-6 mb-6">
            <!-- ID Pendaftaran & Status -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $registration->nama_lengkap }}</h2>
                    <p class="text-gray-600 font-bold">ID: {{ $registration->id_pendaftaran }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        {{ $registration->status_pendaftaran == 'diterima' ? 'bg-green-100 text-green-800' :
                           ($registration->status_pendaftaran == 'ditolak' ? 'bg-red-100 text-red-800' :
                           'bg-yellow-100 text-yellow-800') }}">
                        {{ $registration->status_label }}
                    </span>
                    <p class="text-sm text-gray-500 mt-1">{{ $registration->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Barcode Section -->
            <div class="text-center border-2 border-dashed border-gray-300 rounded-xl p-6 mb-6 bg-white">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Barcode Pendaftaran</h3>
                <div class="flex justify-center mb-4">
                    <img src="{{ route('barcode.image', $registration->id_pendaftaran) }}"
                         alt="Barcode {{ $registration->id_pendaftaran }}"
                         class="h-32 mx-auto">
                </div>
                <p class="text-sm text-gray-600 mb-4">Scan barcode untuk melihat informasi ini</p>

                <!-- Download Button -->
                <a href="{{ route('barcode.download', $registration->id_pendaftaran) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-download mr-2"></i>
                    Download Barcode
                </a>
            </div>

            <!-- Information Grid -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Data Pribadi -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Data Pribadi
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Nama Lengkap</label>
                            <p class="font-medium">{{ $registration->nama_lengkap }}</p>
                        </div>

                        <div>
                            <label class="text-sm text-gray-500">Jenis Kelamin</label>
                            <p class="font-medium">{{ ucfirst($registration->jenis_kelamin) }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Agama</label>
                            <p class="font-medium">{{ ucfirst($registration->agama) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pendidikan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-graduation-cap mr-2 text-orange-600"></i>Pendidikan
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-500">Jenjang Pendidikan</label>
                            <p class="font-medium">{{ $registration->jenjang_pendidikan_terakhir }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500">Sekolah Terakhir</label>
                            <p class="font-medium">{{ $registration->nama_sekolah_terakhir }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Information -->
            @if($registration->package)
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">
                    <i class="fas fa-cube mr-2"></i>Paket Pendaftaran
                </h3>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-blue-900">{{ $registration->package->name }}</p>
                        <p class="text-blue-700">{{ $registration->formatted_total_biaya }}</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ $registration->program_unggulan_id }}
                    </span>
                </div>
            </div>
            @endif

            <!-- Payment Information -->
            @if($registration->payments && $registration->payments->count() > 0)
            @php
                $latestPayment = $registration->payments->first();
            @endphp
            <div class="mt-6 p-4 rounded-lg border
                {{ $latestPayment->status == 'success' || $latestPayment->status == 'lunas' ? 'bg-green-50 border-green-200' :
                   ($latestPayment->status == 'pending' || $latestPayment->status == 'waiting_payment' ? 'bg-yellow-50 border-yellow-200' :
                   ($latestPayment->status == 'failed' ? 'bg-red-50 border-red-200' :
                   ($latestPayment->status == 'expired' ? 'bg-gray-50 border-gray-200' : 'bg-gray-50 border-gray-200'))) }}">
                <h3 class="text-lg font-semibold mb-2
                    {{ $latestPayment->status == 'success' || $latestPayment->status == 'lunas' ? 'text-green-800' :
                       ($latestPayment->status == 'pending' || $latestPayment->status == 'waiting_payment' ? 'text-yellow-800' :
                       ($latestPayment->status == 'failed' ? 'text-red-800' :
                       ($latestPayment->status == 'expired' ? 'text-gray-800' : 'text-gray-800'))) }}">
                    <i class="fas fa-credit-card mr-2"></i>Informasi Pembayaran
                </h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Kode Pembayaran</label>
                        <p class="font-medium">{{ $latestPayment->payment_code }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Jumlah</label>
                        <p class="font-medium">{{ $latestPayment->formatted_amount }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Metode</label>
                        <p class="font-medium capitalize">{{ $latestPayment->payment_method ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                            {{ $latestPayment->status == 'success' || $latestPayment->status == 'lunas' ? 'bg-green-100 text-green-800' :
                               ($latestPayment->status == 'pending' || $latestPayment->status == 'waiting_payment' ? 'bg-yellow-100 text-yellow-800' :
                               ($latestPayment->status == 'failed' ? 'bg-red-100 text-red-800' :
                               ($latestPayment->status == 'expired' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800'))) }}">
                            <i class="fas {{ $latestPayment->status_icon }} mr-1"></i>
                            {{ $latestPayment->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Additional Payment Details -->
                @if($latestPayment->paid_at)
                <div class="mt-3 pt-3 border-t border-opacity-30">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Tanggal Pembayaran</label>
                            <p class="font-medium">{{ $latestPayment->paid_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($latestPayment->payment_method == 'xendit')
                        <div>
                            <label class="text-sm text-gray-600">Channel Pembayaran</label>
                            <p class="font-medium capitalize">{{ $latestPayment->xendit_response['channel_category'] ?? '-' }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @else
            <!-- No Payment Information -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    <i class="fas fa-credit-card mr-2"></i>Informasi Pembayaran
                </h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Kode Pembayaran</label>
                        <p class="font-medium">-</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Jumlah</label>
                        <p class="font-medium">-</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Metode</label>
                        <p class="font-medium">-</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                            <i class="fas fa-clock mr-1"></i>
                            Belum Ada Pembayaran
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Catatan Admin -->
            @if($registration->admin_notes)
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                    <i class="fas fa-sticky-note mr-2"></i>Catatan Admin
                </h3>
                <p class="text-yellow-700">{{ $registration->admin_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center text-white/80">
            <p>© 2025 Pondok Pesantren Al-Quran Bani Syahid.</p>
            <p class="text-sm mt-1">Informasi ini hanya untuk keperluan administrasi</p>
        </div>
    </div>
</body>
</html>
