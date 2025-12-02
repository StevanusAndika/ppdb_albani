@extends('layouts.app')

@section('title', 'Kegiatan Pesantren - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .schedule-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
    }

    .schedule-table thead {
        background: linear-gradient(135deg, #057572, #0a948f);
        color: white;
    }

    .schedule-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .schedule-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .schedule-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .schedule-table tbody tr:last-child {
        border-bottom: none;
    }

    .schedule-table td {
        padding: 1.25rem 1.5rem;
        vertical-align: top;
    }

    .time-cell {
        width: 15%;
        font-weight: 600;
        color: #057572;
        background-color: #f0f9ff;
        border-right: 1px solid #e5e7eb;
    }

    .activity-cell {
        width: 85%;
        color: #4b5563;
        line-height: 1.6;
    }

    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .activity-list-item {
        padding: 0.5rem 0;
        display: flex;
        align-items: flex-start;
    }

    .activity-list-item:not(:last-child) {
        border-bottom: 1px dashed #e5e7eb;
    }

    .activity-bullet {
        color: #057572;
        margin-right: 0.75rem;
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .activity-text {
        flex: 1;
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

    .no-data {
        text-align: center;
        padding: 3rem 1rem;
    }

    .no-data-icon {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .schedule-table-container {
            overflow-x: auto;
        }

        .schedule-table {
            min-width: 600px;
        }

        .schedule-table th,
        .schedule-table td {
            padding: 0.75rem 1rem;
        }

        .time-cell {
            width: 20%;
        }

        .activity-cell {
            width: 80%;
        }

        .daily-schedule {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.calon_santri.navbar')

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

        <!-- Kegiatan Table -->
        <div class="schedule-table-container">
            @if(count($kegiatan) > 0)
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kegiatan as $item)
                        <tr>
                            <td class="time-cell">
                                <div class="font-semibold text-lg">{{ $item['waktu'] ?? 'Waktu tidak tersedia' }}</div>
                                @if(isset($item['icon']))
                                    <div class="mt-2 text-primary">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="activity-cell">
                                <ul class="activity-list">
                                    @if(is_array($item['kegiatan']))
                                        @foreach($item['kegiatan'] as $kegiatanItem)
                                        <li class="activity-list-item">
                                            <div class="activity-bullet">
                                                <i class="fas fa-circle text-xs"></i>
                                            </div>
                                            <div class="activity-text">{{ $kegiatanItem }}</div>
                                        </li>
                                        @endforeach
                                    @else
                                        <li class="activity-list-item">
                                            <div class="activity-bullet">
                                                <i class="fas fa-circle text-xs"></i>
                                            </div>
                                            <div class="activity-text">{{ $item['kegiatan'] }}</div>
                                        </li>
                                    @endif
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <div class="no-data-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
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
    @include('layouts.components.calon_santri.footer')
</div>
@endsection

@section('scripts')
<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.toggle('hidden');
    });

    // Add striping effect to table rows
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('.schedule-table tbody tr');
        tableRows.forEach((row, index) => {
            if (index % 2 === 0) {
                row.style.backgroundColor = '#fafafa';
            }
        });
    });
</script>
@endsection
