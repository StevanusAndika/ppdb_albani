<!-- Informasi Biaya Section -->
<section id="biaya"  class="py-16 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center text-primary mb-4">Informasi Biaya Pondok Pesantren Al Bani</h2>
        <p class="text-center text-secondary mb-12">Informasi biaya lengkap untuk program Takhossus Pesantren dan Plus Sekolah</p>

        @forelse($packages as $package)
        <div class="mb-12">
            <h3 class="text-2xl font-bold text-center text-primary mb-8">{{ $package->name }} - {{ $package->type_label }}</h3>

            @if($package->description)
            <p class="text-center text-secondary mb-6 max-w-2xl mx-auto">{{ $package->description }}</p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $colorClasses = [
                        'bg-gradient-to-br from-primary to-primary/80',
                        'bg-gradient-to-br from-secondary to-secondary/80',
                        'bg-gradient-to-br from-accent to-accent/80',
                        'bg-gradient-to-br from-primary to-accent',
                        'bg-gradient-to-br from-green-500 to-green-400',
                        'bg-gradient-to-br from-purple-500 to-purple-400',
                        'bg-gradient-to-br from-blue-500 to-blue-400',
                        'bg-gradient-to-br from-red-500 to-red-400',
                        'bg-gradient-to-br from-yellow-500 to-yellow-400',
                        'bg-gradient-to-br from-indigo-500 to-indigo-400'
                    ];
                @endphp

                @foreach($package->prices as $index => $price)
                <div class="{{ $colorClasses[$index % count($colorClasses)] }} rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        @php
                            $icons = [
                                'fas fa-money-bill-wave',
                                'fas fa-graduation-cap',
                                'fas fa-percentage',
                                'fas fa-chart-line',
                                'fas fa-book',
                                'fas fa-tshirt',
                                'fas fa-home',
                                'fas fa-utensils',
                                'fas fa-bus',
                                'fas fa-user-graduate'
                            ];
                        @endphp
                        <i class="{{ $icons[$index % count($icons)] }} text-2xl mr-3"></i>
                        <div class="text-xl font-bold">{{ $price->item_name }}</div>
                    </div>
                    <div class="text-2xl font-bold mb-2">{{ $price->formatted_amount }}</div>
                    @if($price->description)
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm opacity-90">{{ $price->description }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        @if(!$loop->last)
        <div class="border-t border-gray-300 my-12"></div>
        @endif

        @empty
        <div class="text-center py-12">
            <i class="fas fa-info-circle text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-500 mb-2">Belum Ada Informasi Biaya</h3>
            <p class="text-gray-400">Informasi biaya sedang dalam proses persiapan</p>
        </div>
        @endforelse
    </div>
</section>
