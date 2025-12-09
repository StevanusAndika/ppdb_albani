@extends('layouts.app')

@section('title', 'Dashboard Admin - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')

    <!-- Header Hero -->
    <header class="py-8 px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1">Dashboard Admin</h1>
        <p class="text-secondary">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> — Panel pengelolaan sistem PPDB.</p>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Profile Card -->
            <div>
                @include('layouts.components.admin.dashboard.profile-card')
            </div>

            <!-- Right: Main admin content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Welcome Message -->
                @include('layouts.components.admin.dashboard.welcome-message')

                <!-- Stats Cards -->
                @include('layouts.components.admin.dashboard.stats-cards')

                <!-- Recent Registrations -->
                @include('layouts.components.admin.dashboard.recent-registrations')

                <!-- Recent Announcements -->
                @include('layouts.components.admin.dashboard.recent-announcements')

                <!-- Content Management -->
                @include('layouts.components.admin.dashboard.content-management')

                <!-- Quick Actions -->
                @include('layouts.components.admin.dashboard.quick-actions')

                <!-- Support Menu -->
                @include('layouts.components.admin.dashboard.support-menu')
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('layouts.components.admin.footer')
</div>
@endsection
