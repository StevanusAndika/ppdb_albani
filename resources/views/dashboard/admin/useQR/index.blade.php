@extends('layouts.app')

@section('title', 'Scan QR Calon Santri - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .scan-container {
        @apply bg-white rounded-xl shadow-md p-6 max-w-4xl mx-auto;
    }
    .scanner-section {
        @apply mb-6;
    }
    .scanner-placeholder {
        @apply w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300;
    }
    .scanner-active {
        @apply w-full h-64 bg-black rounded-lg overflow-hidden relative;
    }
    .result-section {
        @apply mt-6 p-4 rounded-lg hidden;
    }
    .result-success {
        @apply bg-green-50 border border-green-200 text-green-800;
    }
    .result-error {
        @apply bg-red-50 border border-red-200 text-red-800;
    }
    .manual-lookup {
        @apply mt-6 p-4 bg-gray-50 rounded-lg;
    }
    .image-upload {
        @apply mt-4 p-4 bg-blue-50 rounded-lg;
    }
    .action-btn {
        @apply px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center space-x-2;
    }
    .btn-primary {
        @apply bg-primary text-white hover:bg-secondary;
    }
    .btn-success {
        @apply bg-green-500 text-white hover:bg-green-600;
    }
    .btn-secondary {
        @apply bg-gray-500 text-white hover:bg-gray-600;
    }
    .btn-warning {
        @apply bg-yellow-500 text-white hover:bg-yellow-600;
    }
    .info-box {
        @apply bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4;
    }
    .scanner-overlay {
        @apply absolute inset-0 flex items-center justify-center pointer-events-none;
    }
    .scanner-frame {
        @apply border-2 border-green-400 rounded-lg w-64 h-64;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-3 px-4 md:px-6 rounded-xl mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-2 md:space-y-0">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>
            <div class="flex flex-wrap gap-2 md:gap-4 items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-primary hover:text-secondary font-medium text-sm">Dashboard</a>
                <a href="{{ route('admin.registrations.index') }}" class="text-primary hover:text-secondary font-medium text-sm">Pendaftaran</a>
                <a href="{{ route('admin.qr-scan.index') }}" class="bg-primary text-white px-3 py-1 rounded-full font-medium text-sm">Scan QR</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full transition duration-300 text-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-6 px-3 md:px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-primary mb-2">Scan QR Calon Santri</h1>
                <p class="text-gray-600">Scan QR code dari calon santri untuk langsung mengakses detail data pendaftaran</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="action-btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden md:inline">Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <div class="flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div>
                    <h3 class="font-bold text-blue-800">Scanner QR Code Aktif</h3>
                    <p class="text-blue-700 text-sm mt-1">
                        Sistem menggunakan teknologi QR code scanner modern. Pastikan mengizinkan akses kamera ketika diminta.
                    </p>
                </div>
            </div>
        </div>

        <!-- Scan Container -->
        <div class="scan-container">
            <!-- Scanner Section -->
            <div class="scanner-section">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Scanner QR Code</h2>

                <div class="scanner-placeholder" id="scannerPlaceholder">
                    <div class="text-center">
                        <i class="fas fa-qrcode text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 font-medium">Kamera siap digunakan</p>
                        <p class="text-gray-400 text-sm mt-2">Klik "Mulai Scan" untuk mengaktifkan kamera dan scanner</p>
                    </div>
                </div>

                <div id="scannerContainer" class="scanner-active hidden">
                    <div id="qr-reader" style="width: 100%; height: 100%;"></div>
                    <div class="scanner-overlay">
                        <div class="scanner-frame"></div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 mt-4">
                    <button id="startScanner" class="action-btn btn-primary">
                        <i class="fas fa-camera"></i>
                        <span>Mulai Scan</span>
                    </button>
                    <button id="stopScanner" class="action-btn btn-secondary hidden">
                        <i class="fas fa-stop"></i>
                        <span>Stop Scan</span>
                    </button>
                    <button id="switchCamera" class="action-btn btn-warning hidden">
                        <i class="fas fa-sync-alt"></i>
                        <span>Ganti Kamera</span>
                    </button>
                </div>
            </div>

            <!-- Result Section -->
            <div id="resultSection" class="result-section">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i id="resultIcon" class="fas text-xl"></i>
                        <div>
                            <h3 id="resultTitle" class="font-bold"></h3>
                            <p id="resultMessage" class="text-sm"></p>
                        </div>
                    </div>
                    <button id="redirectBtn" class="action-btn btn-success hidden">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Lihat Detail</span>
                    </button>
                </div>
            </div>

            <!-- Upload Gambar QR Code -->
            <div class="image-upload">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Upload Gambar QR Code</h3>
                <p class="text-gray-600 text-sm mb-4">Alternatif: Upload gambar QR code untuk dipindai secara otomatis</p>

                <form id="uploadQrForm" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col md:flex-row gap-3">
                        <div class="flex-1">
                            <input type="file"
                                   id="qrImage"
                                   name="qr_image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                        </div>
                        <button type="submit" class="action-btn btn-primary whitespace-nowrap">
                            <i class="fas fa-upload"></i>
                            <span>Scan Gambar</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Manual Lookup Section -->
            <div class="manual-lookup">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Pencarian Manual</h3>
                <p class="text-gray-600 text-sm mb-4">Cari langsung menggunakan ID Pendaftaran calon santri</p>

                <form action="{{ route('admin.qr-scan.manual-lookup') }}" method="POST" class="flex flex-col md:flex-row gap-3">
                    @csrf
                    <div class="flex-1">
                        <input type="text"
                               name="id_pendaftaran"
                               placeholder="Masukkan ID Pendaftaran (contoh: PPDB-2024-001)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required
                               pattern="PPDB-\d{4}-\d+"
                               title="Format: PPDB-YYYY-NNN (contoh: PPDB-2024-001)">
                    </div>
                    <button type="submit" class="action-btn btn-primary whitespace-nowrap">
                        <i class="fas fa-search"></i>
                        <span>Cari Data</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Petunjuk Penggunaan -->
        <div class="mt-6 bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Petunjuk Penggunaan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-camera text-primary mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-800">Scan Langsung</p>
                        <p>Arahkan kamera ke QR code calon santri</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-upload text-primary mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-800">Upload Gambar</p>
                        <p>Upload foto QR code untuk dipindai</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-id-card text-primary mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-800">ID Pendaftaran</p>
                        <p>QR code berisi ID pendaftaran santri</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-bolt text-primary mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-800">Auto Redirect</p>
                        <p>Langusng ke halaman detail setelah scan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test QR Codes Section -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-yellow-800 mb-4">QR Code untuk Testing</h3>
            <p class="text-yellow-700 text-sm mb-4">Gunakan QR code berikut untuk testing scanner:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $testIds = ['PPDB-2024-001', 'PPDB-2024-002', 'PPDB-2024-003'];
                @endphp
                @foreach($testIds as $testId)
                <div class="text-center">
                    <div class="bg-white p-3 rounded-lg inline-block">
                        <img src="{{ route('admin.qr-scan.generate-test', $testId) }}"
                             alt="QR Code {{ $testId }}"
                             class="w-32 h-32 mx-auto">
                    </div>
                    <p class="text-sm font-mono mt-2 text-gray-600">{{ $testId }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<!-- Include jsQR Library for QR Code Scanning -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
    class AdvancedQRScanner {
        constructor() {
            this.isScanning = false;
            this.stream = null;
            this.videoElement = null;
            this.canvasElement = null;
            this.canvasContext = null;
            this.animationFrame = null;
            this.currentCamera = 'environment';
            this.cameras = [];
            this.currentCameraIndex = 0;

            this.initializeElements();
            this.attachEventListeners();
        }

        initializeElements() {
            this.startScannerBtn = document.getElementById('startScanner');
            this.stopScannerBtn = document.getElementById('stopScanner');
            this.switchCameraBtn = document.getElementById('switchCamera');
            this.scannerPlaceholder = document.getElementById('scannerPlaceholder');
            this.scannerContainer = document.getElementById('scannerContainer');
            this.resultSection = document.getElementById('resultSection');
            this.resultIcon = document.getElementById('resultIcon');
            this.resultTitle = document.getElementById('resultTitle');
            this.resultMessage = document.getElementById('resultMessage');
            this.redirectBtn = document.getElementById('redirectBtn');
        }

        attachEventListeners() {
            this.startScannerBtn.addEventListener('click', () => this.startScanner());
            this.stopScannerBtn.addEventListener('click', () => this.stopScanner());
            this.switchCameraBtn.addEventListener('click', () => this.switchCamera());
            this.redirectBtn.addEventListener('click', () => this.redirectToDetail());

            // Handle image upload form
            document.getElementById('uploadQrForm').addEventListener('submit', (e) => this.handleImageUpload(e));
        }

        async startScanner() {
            try {
                this.showLoading('Mengakses kamera...');

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('Browser tidak mendukung akses kamera');
                }

                // Stop existing stream
                if (this.stream) {
                    this.stopScanner();
                }

                // Get available cameras
                await this.getCameras();

                // Create video element
                if (!this.videoElement) {
                    this.videoElement = document.createElement('video');
                    this.videoElement.setAttribute('autoplay', '');
                    this.videoElement.setAttribute('playsinline', '');
                    this.videoElement.style.width = '100%';
                    this.videoElement.style.height = '100%';
                    this.videoElement.style.objectFit = 'cover';
                }

                // Create canvas for QR processing
                if (!this.canvasElement) {
                    this.canvasElement = document.createElement('canvas');
                    this.canvasContext = this.canvasElement.getContext('2d', { willReadFrequently: true });
                }

                // Get camera constraints based on available cameras
                const constraints = this.getCameraConstraints();

                // Get camera stream
                this.stream = await navigator.mediaDevices.getUserMedia(constraints);

                // Setup video element
                this.videoElement.srcObject = this.stream;

                // Clear and setup scanner container
                this.scannerContainer.innerHTML = '';
                this.scannerContainer.appendChild(this.videoElement);

                // Add overlay
                const overlay = document.createElement('div');
                overlay.className = 'scanner-overlay';
                overlay.innerHTML = '<div class="scanner-frame"></div>';
                this.scannerContainer.appendChild(overlay);

                // Show scanner UI
                this.scannerPlaceholder.classList.add('hidden');
                this.scannerContainer.classList.remove('hidden');
                this.startScannerBtn.classList.add('hidden');
                this.stopScannerBtn.classList.remove('hidden');
                this.switchCameraBtn.classList.remove('hidden');

                this.isScanning = true;

                // Wait for video to load and start QR detection
                this.videoElement.onloadedmetadata = () => {
                    this.videoElement.play();
                    this.showSuccess('Scanner Aktif', 'Arahkan kamera ke QR code');
                    this.startQRDetection();
                };

            } catch (error) {
                console.error('Error starting scanner:', error);
                this.showError('Gagal mengakses kamera', error.message);
                this.resetScanner();
            }
        }

        async getCameras() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                this.cameras = devices.filter(device => device.kind === 'videoinput');
            } catch (error) {
                console.warn('Cannot enumerate cameras:', error);
                this.cameras = [];
            }
        }

        getCameraConstraints() {
            if (this.cameras.length > 0) {
                return {
                    video: {
                        deviceId: this.cameras[this.currentCameraIndex].deviceId,
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: this.currentCamera
                    },
                    audio: false
                };
            }

            return {
                video: {
                    facingMode: this.currentCamera,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            };
        }

        switchCamera() {
            if (this.cameras.length > 1) {
                this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length;
                this.currentCamera = this.currentCameraIndex === 0 ? 'environment' : 'user';
                this.stopScanner();
                setTimeout(() => this.startScanner(), 500);
            } else {
                this.currentCamera = this.currentCamera === 'environment' ? 'user' : 'environment';
                this.stopScanner();
                setTimeout(() => this.startScanner(), 500);
            }
        }

        stopScanner() {
            this.isScanning = false;

            // Stop animation frame
            if (this.animationFrame) {
                cancelAnimationFrame(this.animationFrame);
                this.animationFrame = null;
            }

            // Stop stream
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }

            // Stop video
            if (this.videoElement) {
                this.videoElement.pause();
                this.videoElement.srcObject = null;
            }

            this.resetScanner();
        }

        resetScanner() {
            this.scannerPlaceholder.classList.remove('hidden');
            this.scannerContainer.classList.add('hidden');
            this.startScannerBtn.classList.remove('hidden');
            this.stopScannerBtn.classList.add('hidden');
            this.switchCameraBtn.classList.add('hidden');

            this.scannerContainer.innerHTML = '';
        }

        startQRDetection() {
            const detectQR = () => {
                if (!this.isScanning || !this.videoElement || this.videoElement.readyState !== this.videoElement.HAVE_ENOUGH_DATA) {
                    this.animationFrame = requestAnimationFrame(detectQR);
                    return;
                }

                try {
                    // Setup canvas dimensions
                    this.canvasElement.width = this.videoElement.videoWidth;
                    this.canvasElement.height = this.videoElement.videoHeight;

                    // Draw video frame to canvas
                    this.canvasContext.drawImage(this.videoElement, 0, 0, this.canvasElement.width, this.canvasElement.height);

                    // Get image data from canvas
                    const imageData = this.canvasContext.getImageData(0, 0, this.canvasElement.width, this.canvasElement.height);

                    // Decode QR code using jsQR
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: 'dontInvert',
                    });

                    if (code) {
                        // QR code found!
                        console.log('QR Code detected:', code.data);
                        this.stopScanner();
                        this.processQRData(code.data);
                        return;
                    }

                } catch (error) {
                    console.error('QR detection error:', error);
                }

                // Continue detection
                if (this.isScanning) {
                    this.animationFrame = requestAnimationFrame(detectQR);
                }
            };

            this.animationFrame = requestAnimationFrame(detectQR);
        }

        async handleImageUpload(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const fileInput = document.getElementById('qrImage');

            if (!fileInput.files[0]) {
                this.showError('Error', 'Pilih file gambar terlebih dahulu');
                return;
            }

            try {
                this.showLoading('Memproses gambar QR code...');

                const response = await fetch('{{ route("admin.qr-scan.process-image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(
                        'Data Ditemukan!',
                        `Calon Santri: ${data.registration_data.nama_lengkap} - Status: ${data.registration_data.status_label}`,
                        data.redirect_url
                    );
                    e.target.reset();
                } else {
                    this.showError('Data Tidak Ditemukan', data.message);
                }

            } catch (error) {
                console.error('Error uploading QR image:', error);
                this.showError('Terjadi kesalahan saat memproses gambar: ' + error.message);
            }
        }

        async processQRData(qrData) {
            try {
                this.showLoading('Memproses QR code...');

                const response = await fetch('{{ route("admin.qr-scan.process-scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ qr_data: qrData })
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(
                        'Data Ditemukan!',
                        `Calon Santri: ${data.registration_data.nama_lengkap} - Status: ${data.registration_data.status_label}`,
                        data.redirect_url
                    );
                } else {
                    this.showError('Data Tidak Ditemukan', data.message);
                // Restart scanner after error
                setTimeout(() => this.startScanner(), 2000);
                }
            } catch (error) {
                console.error('Error processing QR data:', error);
                this.showError('Terjadi kesalahan saat memproses data: ' + error.message);
                // Restart scanner after error
                setTimeout(() => this.startScanner(), 2000);
            }
        }

        showLoading(message) {
            this.resultSection.className = 'result-section result-success';
            this.resultIcon.className = 'fas fa-spinner fa-spin text-blue-500';
            this.resultTitle.textContent = 'Memproses...';
            this.resultMessage.textContent = message;
            this.redirectBtn.classList.add('hidden');
            this.resultSection.classList.remove('hidden');
        }

        showSuccess(title, message, redirectUrl = null) {
            this.resultSection.className = 'result-section result-success';
            this.resultIcon.className = 'fas fa-check-circle text-green-500';
            this.resultTitle.textContent = title;
            this.resultMessage.textContent = message;

            if (redirectUrl) {
                this.redirectBtn.classList.remove('hidden');
                this.redirectBtn.onclick = () => {
                    window.location.href = redirectUrl;
                };

                // Auto-redirect after 3 seconds
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 3000);
            } else {
                this.redirectBtn.classList.add('hidden');
            }

            this.resultSection.classList.remove('hidden');
        }

        showError(title, message = '') {
            this.resultSection.className = 'result-section result-error';
            this.resultIcon.className = 'fas fa-times-circle text-red-500';
            this.resultTitle.textContent = title;
            this.resultMessage.textContent = message || 'Terjadi kesalahan';
            this.redirectBtn.classList.add('hidden');
            this.resultSection.classList.remove('hidden');
        }

        redirectToDetail() {
            if (this.redirectUrl) {
                window.location.href = this.redirectUrl;
            }
        }
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        window.qrScanner = new AdvancedQRScanner();

        // Add input validation for manual lookup
        const manualInput = document.querySelector('input[name="id_pendaftaran"]');
        if (manualInput) {
            manualInput.addEventListener('input', function(e) {
                const value = e.target.value;
                if (value && !value.match(/^PPDB-\d{4}-\d+$/)) {
                    e.target.setCustomValidity('Format harus: PPDB-YYYY-NNN (contoh: PPDB-2024-001)');
                } else {
                    e.target.setCustomValidity('');
                }
            });
        }
    });
</script>

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
@endsection
