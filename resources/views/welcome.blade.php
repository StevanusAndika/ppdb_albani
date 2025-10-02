@extends('layouts.app')

@section('title', 'PPDB PESANTREN AL-GURAN BANI SYAHID 2025')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Hero Section -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                PPDB PESANTREN AL-GURAN BANI SYAHID 2025
            </h1>
            <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed">
                Sistem Presentation: Person Login, Buy login money, manual, also experience<br>
                panda gama google phone apps and call calls
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button class="bg-green-500 text-white px-8 py-4 rounded-xl font-semibold hover:bg-green-600 transition-colors flex items-center gap-3">
                <i class="fas fa-mobile-alt"></i>
                Save Smartphone
            </button>
            <button class="bg-blue-500 text-white px-8 py-4 rounded-xl font-semibold hover:bg-blue-600 transition-colors flex items-center gap-3">
                <i class="fas fa-key"></i>
                Create Password
            </button>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Statistik PPDB 2025</h2>
                <p class="text-gray-600">In the final presentation format click icon</p>
            </div>
            <div class="flex gap-3 mt-4 lg:mt-0">
                <button class="bg-blue-100 text-blue-600 p-3 rounded-xl hover:bg-blue-200 transition-colors">
                    <i class="fas fa-chart-bar text-lg"></i>
                </button>
                <button class="bg-green-100 text-green-600 p-3 rounded-xl hover:bg-green-200 transition-colors">
                    <i class="fas fa-download text-lg"></i>
                </button>
                <button class="bg-purple-100 text-purple-600 p-3 rounded-xl hover:bg-purple-200 transition-colors">
                    <i class="fas fa-share-alt text-lg"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Statistic 1 -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-2xl text-center">
                <div class="text-4xl font-bold mb-2">150+</div>
                <div class="text-blue-100 font-medium">Santri Terdaftar</div>
                <div class="mt-3 text-blue-200 text-sm">Select Textiles</div>
            </div>

            <!-- Statistic 2 -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-2xl text-center">
                <div class="text-4xl font-bold mb-2">1200</div>
                <div class="text-green-100 font-medium">Santri Texts</div>
                <div class="mt-3 text-green-200 text-sm">Sorts Textiles</div>
            </div>

            <!-- Statistic 3 -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-2xl text-center">
                <div class="text-4xl font-bold mb-2">95.5%</div>
                <div class="text-purple-100 font-medium">Tingkat Retensi</div>
                <div class="mt-3 text-purple-200 text-sm">Type of retention</div>
            </div>

            <!-- Statistic 4 -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-2xl text-center">
                <div class="text-4xl font-bold mb-2">99.9%</div>
                <div class="text-orange-100 font-medium">Permintaan</div>
                <div class="mt-3 text-orange-200 text-sm">Requested request</div>
            </div>
        </div>
    </div>

    <!-- Why Choose Section -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                MENGAPA MEMILIH PPDB PESANTREN AL-GURAN BANI SYAHID?
            </h2>
            <p class="text-gray-600 text-lg">BETEXT VARI DERAPEARS UNTIL YESTERDAY (DAT TERRIFYERS)</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Person Count</h3>
                <p class="text-gray-600 leading-relaxed">
                    Investigations, not only are new systems implemented but also comprehensive analysis.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-exchange-alt text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Transition</h3>
                <p class="text-gray-600 leading-relaxed">
                    Show each user's income progression and financial growth tracking with detailed analytics.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-utensils text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Restaurant</h3>
                <p class="text-gray-600 leading-relaxed">
                    Get a personal account with customized meal plans and nutritional guidance for optimal health.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-clipboard-list text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Plans/Instructions</h3>
                <p class="text-gray-600 leading-relaxed">
                    Comprehensive educational plans and detailed instructions for academic and personal development.
                </p>
            </div>
        </div>

        <!-- Additional CTA -->
        <div class="text-center mt-8 pt-8 border-t border-gray-200">
            <button class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105">
                <i class="fas fa-file-alt mr-3"></i>
                Daftar Sekarang
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi untuk statistik
        const stats = document.querySelectorAll('.bg-gradient-to-br');
        stats.forEach((stat, index) => {
            stat.style.opacity = '0';
            stat.style.transform = 'translateY(20px)';

            setTimeout(() => {
                stat.style.transition = 'all 0.6s ease';
                stat.style.opacity = '1';
                stat.style.transform = 'translateY(0)';
            }, index * 200);
        });

        // Hover effect untuk feature cards
        const featureCards = document.querySelectorAll('.bg-gradient-to-br.from-gray-50');
        featureCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection
