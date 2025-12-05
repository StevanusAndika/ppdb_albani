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
            background: linear-gradient(135deg, #057572 0%, #0a958f 100%);
            min-height: 100vh;
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .program-badge {
            position: relative;
            overflow: hidden;
        }
        .program-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        }
        .takhassus-badge {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            font-weight: bold;
        }
        .mts-badge {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        .ma-badge {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }
    </style>
</head>
<body class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-4 mb-4">
                <h1 class="text-3xl font-bold text-white mb-2">Pondok Pesantren Al-Quran Bani Syahid</h1>
                <p class="text-white/90">Informasi Calon Santri</p>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card-glass rounded-2xl shadow-2xl p-6 mb-6 program-badge">
            <!-- ID Pendaftaran & Status -->
            <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $registration->nama_lengkap }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-gray-600 font-bold">ID: {{ $registration->id_pendaftaran }}</span>


                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        {{ $registration->status_pendaftaran == 'diterima' ? 'bg-green-100 text-green-800' :
                           ($registration->status_pendaftaran == 'ditolak' ? 'bg-red-100 text-red-800' :
                           'bg-yellow-100 text-yellow-800') }}">
                        {{ $registration->status_label }}
                    </span>
                    <!-- Badge Program Pendidikan -->
                    @if($registration->program_pendidikan)
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                        {{ $registration->program_pendidikan == 'Takhassus Al-Quran' ? 'takhassus-badge' :
                           ($registration->program_pendidikan == 'MTS Bani Syahid' ? 'mts-badge' : 'ma-badge') }}">
                        <i class="fas {{ $registration->program_pendidikan == 'Takhassus Al-Quran' ? 'fa-book-quran' :
                                          ($registration->program_pendidikan == 'MTS Bani Syahid' ? 'fa-school' : 'fa-graduation-cap') }} mr-1"></i>
                        {{ $registration->program_pendidikan }}
                    </span>
                    @endif
                    <p class="text-sm text-gray-500">Terdaftar: {{ $registration->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Barcode Section -->
            <div class="text-center border-2 border-dashed border-gray-300 rounded-xl p-6 mb-6 bg-white">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Barcode Pendaftaran</h3>
                <div class="flex justify-center mb-4">
                    <img src="{{ route('barcode.image', $registration->id_pendaftaran) }}"
                         alt="Barcode {{ $registration->id_pendaftaran }}"
                         class="h-40 w-40 mx-auto">
                </div>
                <p class="text-sm text-gray-600 mb-4">Scan barcode untuk melihat informasi lengkap</p>

                <!-- Download Button -->
                <div class="flex justify-center gap-3">
                    <a href="{{ route('barcode.download', $registration->id_pendaftaran) }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-download mr-2"></i>
                        Download Barcode
                    </a>
                    @if($registration->program_pendidikan === 'Takhassus Al-Quran')
                    <div class="inline-flex items-center px-4 py-2 rounded-lg
                        {{ $registration->is_eligible_for_takhassus ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $registration->is_eligible_for_takhassus ? 'fa-check-circle' : 'fa-exclamation-triangle' }} mr-2"></i>
                        {{ $registration->is_eligible_for_takhassus ? 'Memenuhi Syarat' : 'Perlu Validasi' }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Information Grid -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <!-- Data Pribadi -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Data Pribadi
                    </h3>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-4">
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
                </div>

                <!-- Pendidikan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">
                        <i class="fas fa-graduation-cap mr-2 text-orange-600"></i>Pendidikan
                    </h3>

                    <div class="space-y-3">
                        <!-- Program Pendidikan -->
                        <div>
                            <label class="text-sm text-gray-500">Program Pendidikan Yang Dipilihw</label>
                            <p class="font-medium text-lg
                                {{ $registration->program_pendidikan == 'Takhassus Al-Quran' ? 'text-amber-600 font-bold' : 'text-gray-800' }}">
                                {{ $registration->program_pendidikan }}
                            </p>
                            @if($registration->program_pendidikan === 'Takhassus Al-Quran')
                            <div class="mt-2 p-3 rounded-lg {{ $registration->is_eligible_for_takhassus ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <div class="flex items-start">
                                    <i class="fas {{ $registration->is_eligible_for_takhassus ? 'fa-check-circle text-green-500' : 'fa-exclamation-triangle text-red-500' }} mt-0.5 mr-2"></i>
                                    <div>
                                        <p class="text-sm {{ $registration->is_eligible_for_takhassus ? 'text-green-700' : 'text-red-700' }}">
                                            Usia calon santri atas nama <strong>{{ $registration->nama_lengkap }}</strong>
                                            {{ $registration->is_eligible_for_takhassus ? 'memenuhi' : 'belum memenuhi' }}
                                            untuk program Pendidikan Takhassus Al-Quran.
                                        </p>
                                        <p class="text-xs {{ $registration->is_eligible_for_takhassus ? 'text-green-600' : 'text-red-600' }} mt-1">
                                            Usia saat ini: <strong>{{ $registration->usia }} tahun</strong> |
                                            Minimal: <strong>17 tahun</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-500">Jenjang Pendidikan</label>
                                <p class="font-medium">{{ $registration->jenjang_pendidikan_terakhir }}</p>
                            </div>

                        </div>

                        <div>
                            <label class="text-sm text-gray-500">Sekolah Terakhir</label>
                            <p class="font-medium">{{ $registration->nama_sekolah_terakhir }}</p>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Program Unggulan -->
            <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200">
                <h3 class="text-lg font-semibold text-indigo-800 mb-2">
                    <i class="fas fa-star mr-2"></i>Program Unggulan
                </h3>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-indigo-900">{{ $registration->program_unggulan_name ?: 'Belum memilih program' }}</p>
                        <p class="text-indigo-700 text-sm">Program pilihan calon santri</p>
                    </div>
                    @if($registration->program_unggulan_id)
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                        {{ $registration->program_unggulan_id }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Package Information -->
            @if($registration->package)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">
                    <i class="fas fa-cube mr-2"></i>Paket Pendaftaran
                </h3>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <p class="font-medium text-blue-900">{{ $registration->package->name }}</p>
                        <p class="text-blue-700 font-bold text-lg">{{ $registration->formatted_total_biaya }}</p>
                    </div>
                    <div class="text-sm text-blue-600">
                        <p><i class="fas fa-calendar-alt mr-1"></i> {{ $registration->package->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Seleksi -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    <i class="fas fa-clipboard-check mr-2"></i>Status Seleksi
                </h3>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <p class="font-medium text-gray-700">{{ $registration->status_seleksi_label }}</p>
                        <p class="text-gray-600 text-sm mt-1">Informasi keikutsertaan seleksi</p>
                    </div>
                    <div class="text-right">
                        @if($registration->status_seleksi === 'sudah_mengikuti_seleksi')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Sudah Seleksi
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Belum Seleksi
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($registration->payments && $registration->payments->count() > 0)
            @php
                $latestPayment = $registration->payments->first();
            @endphp
            <div class="mb-6 p-4 rounded-lg border
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
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                <div class="mt-4 pt-4 border-t border-opacity-30">
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
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
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
            @if($registration->catatan_admin)
            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                    <i class="fas fa-sticky-note mr-2"></i>Catatan Admin
                </h3>
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 mt-0.5 mr-2"></i>
                    <p class="text-yellow-700">{{ $registration->catatan_admin }}</p>
                </div>
                @if($registration->ditolak_pada)
                <p class="text-xs text-yellow-600 mt-2">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Ditolak pada: {{ $registration->ditolak_pada->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
            @endif

            <!-- Informasi Tambahan -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="grid md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div>
                        <p><i class="fas fa-sync-alt mr-2"></i>Terakhir diperbarui: {{ $registration->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p><i class="fas fa-eye mr-2"></i>Dilihat: {{ $registration->dilihat_pada ? $registration->dilihat_pada->format('d/m/Y H:i') : 'Belum' }}</p>
                    </div>
                    <div>
                        <p><i class="fas fa-id-badge mr-2"></i>Status: {{ $registration->status_label }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-white/80">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl px-6 py-4 inline-block">
                <p class="font-medium text-white">© 2025 Pondok Pesantren Al-Quran Bani Syahid.</p>
                <p class="text-sm mt-1 text-white/80">Informasi ini hanya untuk keperluan administrasi</p>

            </div>
        </div>
    </div>

    <script>
        // Menambahkan efek hover pada kartu
        document.addEventListener('DOMContentLoaded', function() {
            const mainCard = document.querySelector('.card-glass');
            if (mainCard) {
                mainCard.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                    this.style.transition = 'transform 0.3s ease';
                });

                mainCard.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }

            // Animasi untuk badge program pendidikan
            const programBadge = document.querySelector('.program-badge');
            if (programBadge) {
                programBadge.style.animation = 'fadeInUp 0.6s ease-out';
            }

            // Print functionality
            const printBtn = document.createElement('button');
            printBtn.className = 'fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition';
            printBtn.innerHTML = '<i class="fas fa-print"></i>';
            printBtn.onclick = () => window.print();
            document.body.appendChild(printBtn);
        });

        // CSS Animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media print {
                body {
                    background: white !important;
                }
                .card-glass {
                    box-shadow: none !important;
                    border: 1px solid #ddd !important;
                }
                button {
                    display: none !important;
                }
                .program-badge::before {
                    height: 2px !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
