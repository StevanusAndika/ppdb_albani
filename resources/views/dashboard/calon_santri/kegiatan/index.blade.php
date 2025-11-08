@extends('layouts.app')

@section('title', 'Kegiatan Pesantren - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .kegiatan-accordion {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .kegiatan-item {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .kegiatan-item:last-child {
        border-bottom: none;
    }

    .kegiatan-header {
        padding: 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .kegiatan-header:hover {
        background-color: #f8fafc;
    }

    .kegiatan-header.active {
        background-color: #f0f9ff;
        border-left: 4px solid #057572;
    }

    .kegiatan-time {
        background: #057572;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .kegiatan-title {
        flex: 1;
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }

    .kegiatan-icon {
        transition: transform 0.3s ease;
        color: #057572;
    }

    .kegiatan-header.active .kegiatan-icon {
        transform: rotate(180deg);
    }

    .kegiatan-content {
        padding: 0 1.5rem;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #fafafa;
    }

    .kegiatan-content.active {
        padding: 1.5rem;
        max-height: 500px;
    }

    .kegiatan-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .kegiatan-list-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: flex-start;
        color: #4b5563;
    }

    .kegiatan-list-item:last-child {
        border-bottom: none;
    }

    .kegiatan-bullet {
        color: #057572;
        margin-right: 0.75rem;
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .kegiatan-text {
        flex: 1;
        line-height: 1.6;
    }

    .daily-schedule {
        background: linear-gradient(135deg, #057572, #0a948f);
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .schedule-title {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .schedule-subtitle {
        text-align: center;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .kegiatan-header {
            padding: 1rem;
        }

        .kegiatan-time {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .kegiatan-title {
            font-size: 0.9rem;
        }

        .kegiatan-content.active {
            padding: 1rem;
        }

        .daily-schedule {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>

            <div class="hidden md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="{{ route('santri.dashboard') }}#profile" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary hover:text-secondary font-medium">Pembayaran</a>
                <a href="{{ route('santri.faq.index') }}" class="text-primary hover:text-secondary font-medium">FAQ</a>
                <a href="{{ route('santri.kegiatan.index') }}" class="text-primary hover:text-secondary font-medium font-bold border-b-2 border-primary">Kegiatan</a>
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-full transition duration-300">Logout</button>
                </form>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-primary focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-2 bg-white p-4 rounded-xl shadow-lg">
            <div class="flex flex-col space-y-2">
                <a href="{{ url('/') }}" class="text-primary">Beranda</a>
                <a href="{{ route('santri.dashboard') }}#profile" class="text-primary">Profil</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary">Pembayaran</a>
                <a href="{{ route('santri.faq.index') }}" class="text-primary">FAQ</a>
                <a href="{{ route('santri.kegiatan.index') }}" class="text-primary font-bold">Kegiatan</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary text-white py-2 rounded-full mt-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-8 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-2">Kegiatan Harian Pesantren</h1>
            <p class="text-secondary text-lg">Jadwal Rutin Harian Pondok Pesantren Al-Qur'an Bani Syahid</p>

            <div class="mt-4 flex items-center justify-center gap-4">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-clock"></i>
                    <span>Total Sesi: </span>
                    <span class="faq-count">{{ count($kegiatan) }}</span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-6 px-4">
        <!-- Daily Schedule Overview -->
        <div class="daily-schedule">
            <h2 class="schedule-title">📅 Jadwal Harian Santri</h2>
            <p class="schedule-subtitle">Disiplin waktu untuk membentuk karakter Qur'ani yang istiqomah</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold">{{ count($kegiatan) }}</div>
                    <div class="text-sm opacity-90">Sesi Kegiatan</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">24</div>
                    <div class="text-sm opacity-90">Jam Penuh</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">5</div>
                    <div class="text-sm opacity-90">Waktu Shalat</div>
                </div>
                <div>
                    <div class="text-2xl font-bold">7</div>
                    <div class="text-sm opacity-90">Hari/Minggu</div>
                </div>
            </div>
        </div>

        <!-- Kegiatan Accordion -->
        <div class="kegiatan-accordion" id="kegiatanAccordion">
            @if(count($kegiatan) > 0)
                @foreach($kegiatan as $index => $item)
                <div class="kegiatan-item" data-kegiatan-index="{{ $index }}">
                    <div class="kegiatan-header" onclick="toggleKegiatan({{ $index }})">
                        <div class="kegiatan-time">{{ $item['waktu'] ?? 'Waktu tidak tersedia' }}</div>
                        <div class="kegiatan-title">Sesi Kegiatan {{ $index + 1 }}</div>
                        <div class="kegiatan-icon">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="kegiatan-content" id="kegiatan-content-{{ $index }}">
                        <ul class="kegiatan-list">
                            @foreach($item['kegiatan'] as $kegiatanItem)
                            <li class="kegiatan-list-item">
                                <div class="kegiatan-bullet">
                                    <i class="fas fa-circle text-xs"></i>
                                </div>
                                <div class="kegiatan-text">{{ $kegiatanItem }}</div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Jadwal Kegiatan</h3>
                    <p class="text-gray-500">Jadwal kegiatan harian pesantren sedang dalam proses persiapan.</p>
                </div>
            @endif
        </div>

        <!-- Information Section -->
        <div class="mt-8 bg-green-50 border border-green-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-green-500 text-xl mt-1 mr-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Informasi Penting</h3>
                    <ul class="text-green-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-sm"></i>
                            <span>Seluruh kegiatan diwajibkan untuk diikuti oleh semua santri</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-sm"></i>
                            <span>Kedisiplinan waktu adalah bagian dari pendidikan karakter</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-sm"></i>
                            <span>Jadwal dapat berubah sesuai dengan kondisi dan kebutuhan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-sm"></i>
                            <span>Koordinasi dengan pengurus asrama untuk informasi terbaru</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 px-4 mt-12">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2025 PPDB Pesantren Al-Qur'an Bani Syahid</p>
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Kegiatan Accordion Functionality
    function toggleKegiatan(index) {
        const content = document.getElementById(`kegiatan-content-${index}`);
        const header = document.querySelector(`[data-kegiatan-index="${index}"] .kegiatan-header`);

        // Toggle active class
        header.classList.toggle('active');
        content.classList.toggle('active');

        // Close other kegiatan (optional - remove if you want multiple open)
        document.querySelectorAll('.kegiatan-item').forEach((item, i) => {
            if (i !== index) {
                const otherContent = document.getElementById(`kegiatan-content-${i}`);
                const otherHeader = item.querySelector('.kegiatan-header');
                otherHeader.classList.remove('active');
                otherContent.classList.remove('active');
            }
        });
    }

    // Auto-open first kegiatan on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(count($kegiatan) > 0)
            // Open first kegiatan by default
            toggleKegiatan(0);
        @endif
    });

    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close all kegiatan when ESC is pressed
            document.querySelectorAll('.kegiatan-header.active').forEach(header => {
                header.classList.remove('active');
            });
            document.querySelectorAll('.kegiatan-content.active').forEach(content => {
                content.classList.remove('active');
            });
        }
    });
</script>
@endsection
