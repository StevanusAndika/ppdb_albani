@extends('layouts.app')

@section('title', 'Dashboard Santri - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    @include('layouts.components.calon_santri.navbar')

    <!-- Header Hero -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Dashboard Santri</h1>
        <p class="text-secondary">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> — Selamat datang di panel pendaftaran.</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Card & Menu -->
            <div class="space-y-6">
                <!-- Profile Card -->
                @include('layouts.components.calon_santri.dashboard.profile-card')

                <!-- Quick Menu Card -->
                @include('layouts.components.calon_santri.dashboard.quick-menu')

                <!-- Progress Summary -->
                @include('layouts.components.calon_santri.dashboard.progress-summary')
            </div>

            <!-- Right: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Summary Cards -->
                @include('layouts.components.calon_santri.dashboard.status-summary-cards')

                <!-- Status Pendaftaran Detail -->
                @include('layouts.components.calon_santri.dashboard.registration-status')
                
                <!-- Progress Pendaftaran -->
                @include('layouts.components.calon_santri.dashboard.registration-progress')

                <!-- Informasi Kuota Pendaftaran -->
                @include('layouts.components.calon_santri.dashboard.quota-info')

                <!-- Barcode Section -->
                @include('layouts.components.calon_santri.dashboard.barcode-section')

                <!-- Dokumen Section -->
                @include('layouts.components.calon_santri.dashboard.documents-section')

                <!-- Status Pembayaran -->
                @include('layouts.components.calon_santri.dashboard.payment-status')

                <!-- Quick Actions -->
                @include('layouts.components.calon_santri.dashboard.quick-actions')
            </div>
        </div>
    </main>

    <!-- Barcode Modal -->
    <div id="barcodeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-primary">QR Code Pendaftaran</h3>
                <button onclick="closeBarcodeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            @if($registration && $barcodeUrl)
            <div class="text-center">
                <!-- QR Code Image -->
                <div class="bg-white p-4 rounded-lg border-2 border-gray-200 mb-4">
                    <img src="{{ $barcodeUrl }}"
                         alt="QR Code Pendaftaran"
                         class="w-64 h-64 mx-auto qr-fade-in"
                         id="barcodeImage">
                </div>

                <!-- ID Pendaftaran -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600">ID Pendaftaran:</p>
                    <p class="font-mono font-bold text-lg text-primary">{{ $registration->id_pendaftaran }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ $barcodeDownloadUrl }}"
                       class="bg-primary text-white px-4 py-2 rounded-full hover:bg-secondary transition duration-300 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i> Download QR
                    </a>
                    <a href="{{ $barcodeInfoUrl }}"
                       target="_blank"
                       class="bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-external-link-alt mr-2"></i> Info Lengkap
                    </a>
                    <button onclick="refreshBarcode()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh
                    </button>
                </div>

                <!-- Information -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        QR Code ini dapat digunakan untuk verifikasi pendaftaran Anda
                    </p>
                </div>
            </div>
            @else
            <div class="text-center py-6">
                <i class="fas fa-qrcode text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">QR Code akan tersedia setelah Anda menyelesaikan pendaftaran</p>
                @if(!$registration)
                <a href="{{ route('santri.biodata.index') }}" class="inline-block mt-3 bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                    Mulai Pendaftaran
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.components.calon_santri.footer')
</div>

<style>
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .qr-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    // Download all documents as ZIP
    function downloadAllDocuments() {
        Swal.fire({
            title: 'Mempersiapkan File...',
            text: 'Sedang membuat file ZIP dari semua dokumen',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const link = document.createElement('a');
        link.href = `/santri/documents/download-all`;
        link.target = '_blank';

        fetch(`/santri/documents/download-all`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            Swal.close();
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Download gagal');
                });
            }

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: 'Download Berhasil',
                text: 'Semua dokumen berhasil didownload dalam format ZIP',
                confirmButtonText: 'OK'
            });
        })
        .catch(error => {
            Swal.close();
            console.error('Download all error:', error);

            Swal.fire({
                icon: 'error',
                title: 'Download Gagal',
                text: error.message || 'Terjadi kesalahan saat mendownload file ZIP',
                confirmButtonText: 'Mengerti'
            });
        });
    }

    // Barcode Modal Functions
    function showBarcodeModal() {
        const modal = document.getElementById('barcodeModal');
        if (modal) {
            modal.classList.remove('hidden');
            // Refresh barcode image to ensure it's current
            refreshBarcode();
        }
    }

    function closeBarcodeModal() {
        const modal = document.getElementById('barcodeModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function refreshBarcode() {
        const barcodeImage = document.getElementById('barcodeImage');
        const barcodePreview = document.getElementById('barcodePreview');

        if (barcodeImage && '{{ $barcodeUrl }}') {
            // Add timestamp to prevent caching
            const timestamp = new Date().getTime();
            barcodeImage.src = '{{ $barcodeUrl }}' + '?t=' + timestamp;

            // Show loading effect
            barcodeImage.classList.remove('qr-fade-in');
            setTimeout(() => {
                barcodeImage.classList.add('qr-fade-in');
            }, 100);
        }

        if (barcodePreview && '{{ $barcodeUrl }}') {
            const timestamp = new Date().getTime();
            barcodePreview.src = '{{ $barcodeUrl }}' + '?t=' + timestamp;

            barcodePreview.classList.remove('qr-fade-in');
            setTimeout(() => {
                barcodePreview.classList.add('qr-fade-in');
            }, 100);
        }

        Swal.fire({
            icon: 'success',
            title: 'QR Code Diperbarui',
            text: 'QR Code berhasil diperbarui',
            timer: 1500,
            showConfirmButton: false
        });
    }

    // Check quota availability via AJAX
    function checkQuotaAvailability() {
        Swal.fire({
            title: 'Memeriksa Kuota...',
            text: 'Sedang memeriksa ketersediaan kuota terbaru',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("santri.payments.check-quota") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            if (data.success) {
                let icon = data.available ? 'success' : 'warning';
                let title = data.available ? 'Kuota Tersedia' : 'Kuota Penuh';
                let text = data.available ?
                    `Masih tersedia ${data.quota.remaining} kuota dari ${data.quota.total} total kuota.` :
                    'Maaf, kuota pendaftaran sudah penuh. Silakan menunggu periode berikutnya.';

                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    confirmButtonText: 'Mengerti'
                }).then(() => {
                    // Reload page to update quota information
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memeriksa',
                    text: data.message || 'Terjadi kesalahan saat memeriksa kuota',
                    confirmButtonText: 'Mengerti'
                });
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Check quota error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memeriksa',
                text: 'Terjadi kesalahan saat memeriksa kuota',
                confirmButtonText: 'Mengerti'
            });
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-check quota every 5 minutes
    setInterval(() => {
        console.log('Auto-checking quota availability...');
    }, 300000); // 5 minutes
</script>
@endsection
