@extends('layouts.app')

@section('title', 'FAQ - Pondok Pesantren Bani Syahid')

@section('styles')
<style>
    .faq-accordion {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .faq-item {
        border-bottom: 1px solid #e5e7eb;
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        padding: 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .faq-question:hover {
        background-color: #f8fafc;
    }

    .faq-question.active {
        background-color: #f0f9ff;
        border-left: 4px solid #057572;
    }

    .faq-number {
        background: #057572;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .faq-text {
        flex: 1;
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }

    .faq-icon {
        transition: transform 0.3s ease;
        color: #057572;
    }

    .faq-question.active .faq-icon {
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0 1.5rem;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #fafafa;
    }

    .faq-answer.active {
        padding: 1.5rem;
        max-height: 500px;
    }

    .faq-answer-content {
        color: #4b5563;
        line-height: 1.6;
        border-left: 3px solid #d1d5db;
        padding-left: 1rem;
    }

    .search-box {
        position: relative;
        margin-bottom: 2rem;
    }

    .search-box input {
        padding-left: 3rem;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #057572;
        box-shadow: 0 0 0 3px rgba(5, 117, 114, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .no-results {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .no-results i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }

    .faq-count {
        background: #057572;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .faq-question {
            padding: 1rem;
        }

        .faq-text {
            font-size: 0.9rem;
        }

        .faq-answer.active {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
     <nav class="bg-white shadow-md py-2 px-4 md:py-3 md:px-6 rounded-full mx-2 md:mx-4 mt-2 md:mt-4 sticky top-2 md:top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg md:text-xl font-bold text-primary">Ponpes Al Bani</div>

            <div class="md:flex space-x-6 items-center desktop-menu">
                <a href="{{ url('/') }}" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="#profile" class="text-primary hover:text-secondary font-medium">Profil</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary hover:text-secondary font-medium">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary hover:text-secondary font-medium">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary hover:text-secondary font-medium">Pembayaran</a>
                <a href="{{ route('santri.faq.index') }}" class="text-primary hover:text-secondary font-medium">FAQ</a>
                <a href="{{ route('santri.kegiatan.index') }}" class="text-primary hover:text-secondary font-medium">Kegiatan</a>
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
                <a href="#profile" class="text-primary">Profil</a>
                <a href="{{ route('santri.biodata.index') }}" class="text-primary">Pendaftaran</a>
                <a href="{{ route('santri.documents.index') }}" class="text-primary">Dokumen</a>
                <a href="{{ route('santri.payments.index') }}" class="text-primary">Pembayaran</a>
                <a href="{{ route('santri.faq.index') }}" class="text-primary">FAQ</a>
                <a href="{{ route('santri.kegiatan.index') }}" class="text-primary">Kegiatan</a>
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
            <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-2">FAQ</h1>
            <p class="text-secondary text-lg">Pertanyaan yang Sering Diajukan</p>

            <div class="mt-4 flex items-center justify-center gap-4">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-question-circle"></i>
                    <span>Total Pertanyaan: </span>
                    <span class="faq-count">{{ count($faqs) }}</span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-6 px-4">
        <!-- Search Box -->
        <div class="search-box">
            <div class="relative">
                <i class="fas fa-search search-icon"></i>
                <input type="text"
                       id="faqSearch"
                       placeholder="Cari pertanyaan atau jawaban..."
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
        </div>

        <!-- FAQ Accordion -->
        <div class="faq-accordion" id="faqAccordion">
            @if(count($faqs) > 0)
                @foreach($faqs as $index => $faq)
                <div class="faq-item" data-faq-index="{{ $index }}">
                    <div class="faq-question" onclick="toggleFAQ({{ $index }})">
                        <div class="faq-number">{{ $index + 1 }}</div>
                        <div class="faq-text">{{ $faq['pertanyaan'] ?? 'Pertanyaan tidak tersedia' }}</div>
                        <div class="faq-icon">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="faq-answer" id="answer-{{ $index }}">
                        <div class="faq-answer-content">
                            {!! nl2br(e($faq['jawaban'] ?? 'Jawaban tidak tersedia')) !!}
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-results">
                    <i class="fas fa-inbox"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada FAQ</h3>
                    <p class="text-gray-500">Belum ada pertanyaan yang tersedia saat ini.</p>
                </div>
            @endif
        </div>

        <!-- No Results Message (Hidden by default) -->
        <div id="noResults" class="no-results hidden">
            <i class="fas fa-search"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ditemukan</h3>
            <p class="text-gray-500">Tidak ada pertanyaan yang sesuai dengan pencarian Anda.</p>
        </div>

        <!-- Help Section -->

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

    // FAQ Accordion Functionality
    function toggleFAQ(index) {
        const answer = document.getElementById(`answer-${index}`);
        const question = document.querySelector(`[data-faq-index="${index}"] .faq-question`);

        // Toggle active class
        question.classList.toggle('active');
        answer.classList.toggle('active');

        // Close other FAQs (optional - remove if you want multiple open)
        document.querySelectorAll('.faq-item').forEach((item, i) => {
            if (i !== index) {
                const otherAnswer = document.getElementById(`answer-${i}`);
                const otherQuestion = item.querySelector('.faq-question');
                otherQuestion.classList.remove('active');
                otherAnswer.classList.remove('active');
            }
        });
    }

    // Search Functionality
    document.getElementById('faqSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        const faqItems = document.querySelectorAll('.faq-item');
        const noResults = document.getElementById('noResults');
        const faqAccordion = document.getElementById('faqAccordion');

        let visibleCount = 0;

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-text').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer-content').textContent.toLowerCase();

            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0 && searchTerm !== '') {
            noResults.classList.remove('hidden');
            faqAccordion.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            faqAccordion.classList.remove('hidden');
        }
    });

    // Auto-open first FAQ on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(count($faqs) > 0)
            // Open first FAQ by default
            toggleFAQ(0);
        @endif
    });

    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close all FAQs when ESC is pressed
            document.querySelectorAll('.faq-question.active').forEach(question => {
                question.classList.remove('active');
            });
            document.querySelectorAll('.faq-answer.active').forEach(answer => {
                answer.classList.remove('active');
            });
        }
    });
</script>
@endsection
