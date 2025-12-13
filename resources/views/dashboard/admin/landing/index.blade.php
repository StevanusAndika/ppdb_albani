@extends('layouts.app')

@section('title', 'Kelola Konten - Pondok Pesantren Bani Syahid')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans full-width-page w-full">
    <!-- Navbar -->
    @include('layouts.components.admin.navbar')
    <main class="max-w-7xl mx-auto py-6 px-4 flex-1">
        <div class="container mt-4">
            <header class="py-8 text-center">
                <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-1 text-center">Manajemen Konten Landing Page</h1>
                <p class="text-secondary text-center">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span> — Kelola konten landing page pondok pesantren.</p>
            </header>

            @if(session('success'))
                <div class="alert alert-success hidden">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="border-b border-gray-200 bg-gray-100 rounded-t-lg">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 justify-center" id="content-tab" data-tabs-toggle="#tab-content" role="tablist">
                        <li class="me-2" role="presentation">
                            <button id="hero-tab" data-tabs-target="#hero" type="button" role="tab" aria-controls="hero" aria-selected="true" class="inline-block p-4 border-b-2 rounded-t-lg border-primary text-primary">Hero Section</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="visimisi-tab" data-tabs-target="#visimisi" type="button" role="tab" aria-controls="visimisi" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Visi & Misi</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="programs-tab" data-tabs-target="#programs" type="button" role="tab" aria-controls="programs" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Program Pendidikan</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="programunggulan-tab" data-tabs-target="#programunggulan" type="button" role="tab" aria-controls="programunggulan" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Program Unggulan</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="kegiatan-tab" data-tabs-target="#kegiatan-admin" type="button" role="tab" aria-controls="kegiatan-admin" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Kegiatan</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="faq-tab" data-tabs-target="#faq-admin" type="button" role="tab" aria-controls="faq-admin" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">FAQ</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="alur-tab" data-tabs-target="#alur-admin" type="button" role="tab" aria-controls="alur-admin" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Alur Pendaftaran</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="biaya-tab" data-tabs-target="#biaya-admin" type="button" role="tab" aria-controls="biaya-admin" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Biaya</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="persyaratan-tab" data-tabs-target="#persyaratan-admin" type="button" role="tab" aria-controls="persyaratan-admin" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Persyaratan</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="brosur-tab" data-tabs-target="#brosur-admin" type="button" role="tab" aria-controls="brosur-admin" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Brosur</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button id="general-tab" data-tabs-target="#general-admin" type="button" role="tab" aria-controls="general-admin" aria-selected="false" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Pengaturan Umum</button>
                        </li>
                    </ul>
                </div>

                <div id="tab-content" class="p-4 bg-white border border-t-0 rounded-b-lg">
                    <div id="hero" role="tabpanel" aria-labelledby="hero-tab" class="tab-pane max-w-3xl mx-auto mt-8">

                    <!-- judul utama -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-judul-utama">
                                Judul Utama
                            </label>
                            </div>
                            <div class="md:w-2/3">
                            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-judul-utama" type="text" name="hero[title]" value="{{ $contents['hero']['title'] ?? '' }}">
                            </div>
                        </div>

                        <!-- tagline -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-tagline">
                                Tagline
                            </label>
                            </div>
                            <div class="md:w-2/3">
                            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-tagline" type="text" name="hero[tagline]" value="{{ $contents['hero']['tagline'] ?? '' }}">
                            </div>
                        </div>

                        <!-- deskripsi -->
                        <div class="md:flex md:items-start mb-6">
                            <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-deskripsi">
                                Deskripsi
                            </label>
                            </div>
                            <div class="md:w-2/3">
                            <textarea class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-deskripsi" name="hero[description]" rows="4">{{ $contents['hero']['description'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- whatsapp admin -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-whatsapp">
                                WhatsApp Admin
                            </label>
                            </div>
                            <div class="md:w-2/3">
                            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-whatsapp" type="text" name="hero[whatsapp]" value="{{ $contents['hero']['whatsapp'] ?? '' }}">
                            </div>
                        </div>

                        <!-- gambar hero -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-hero-image">
                                Gambar Hero
                            </label>
                            </div>
                            <div class="md:w-2/3">
                                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-hero-image" type="file" name="hero_image">
                                @if(!empty($contents['hero']['image']))
                                    <img src="{{ asset($contents['hero']['image']) }}" width="100" class="mt-2" onerror="this.src='{{ asset('images/default/image-placeholder.png') }}';">
                                @endif
                            </div>
                        </div>
                    </div>


                    <!-- Visi Misi -->
                    <div id="visimisi" role="tabpanel" aria-labelledby="visimisi-tab" class="hidden tab-pane max-w-3xl mx-auto mt-8">

                        <!-- visi -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-visi">
                                Visi
                            </label>
                            </div>
                            <div class="md:w-2/3">
                                <textarea name="visi" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-visi" rows="5">{{ $contents['visi_misi']['visi'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Misi -->
                        <div class="md:flex md:items-start mb-6">
                            <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-misi">
                                Misi
                            </label>
                            </div>
                            <div class="md:w-2/3">
                                <div id="misi-wrapper">
                                    @php $misis = $contents['visi_misi']['misi'] ?? ['']; @endphp
                                    @foreach($misis as $misi)
                                        <div class="input-group mb-2 flex">
                                            <input type="text" name="misi[]" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" value="{{ $misi }}">
                                            <button type="button" class="btn bg-red-500 ml-2 remove-row rounded-full text-white px-3 font-bold ">X</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm p-2 border border-primary mt-2 rounded-full hover:text-white hover:bg-primary w-full" onclick="addMisi()">+ Tambah Misi</button>
                            </div>
                        </div>
                    </div>


                    <!-- Programs Pendidikan -->
                    <div id="programs" role="tabpanel" aria-labelledby="programs-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">📚 Kelola Program Pendidikan</h3>
                            <p class="text-gray-700 text-sm">Tambahkan dan kelola berbagai program pendidikan dengan deskripsi lengkap dan keunggulan masing-masing program.</p>
                        </div>

                        <div id="program-wrapper">
                            @php $programs = $contents['programs'] ?? []; @endphp

                            @foreach($programs as $index => $program)
                                <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white hover:border-primary hover:shadow-lg transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-lg font-bold text-primary">Program #{{ $index + 1 }}: {{ $program['title'] ?? 'Baru' }}</h4>
                                        </div>
                                        <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                                    </div>

                                    <!-- Nama Program -->
                                    <div class="mb-4">
                                        <label class="block text-gray-700 font-semibold mb-2" for="program-title-{{$index}}">Nama Program <span class="text-red-500">*</span></label>
                                        <input type="text" id="program-title-{{$index}}" name="programs[{{$index}}][title]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" value="{{ $program['title'] ?? '' }}" placeholder="Contoh: Tahfidz Al-Qur'an, Bahasa Inggris, dll" required>
                                    </div>

                                    <!-- Deskripsi Program -->
                                    <div class="mb-4">
                                        <label class="block text-gray-700 font-semibold mb-2" for="program-desc-{{$index}}">Deskripsi Singkat <span class="text-red-500">*</span></label>
                                        <textarea id="program-desc-{{$index}}" name="programs[{{$index}}][description]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" rows="3" placeholder="Jelaskan secara singkat apa yang menjadi fokus program ini" required>{{ $program['description'] ?? '' }}</textarea>
                                    </div>

                                    <!-- Keunggulan Program -->
                                    <div class="mb-4">
                                        <label class="block text-gray-700 font-semibold mb-2" for="program-advantages-{{$index}}">Keunggulan Program (Pisahkan dengan koma) <span class="text-red-500">*</span></label>
                                        <textarea id="program-advantages-{{$index}}" name="programs[{{$index}}][advantages]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" rows="3" placeholder="Contoh: Full AC, Guru Senior, Ekstrakurikuler, Fasilitas Lengkap">{{ implode(', ', $program['advantages'] ?? []) }}</textarea>
                                        <small class="text-gray-500 mt-1 block">💡 Tip: Pisahkan setiap keunggulan dengan koma (,) untuk hasil terbaik</small>
                                    </div>

                                    <!-- Gambar Program -->
                                    <div class="mb-4">
                                        <label class="block text-gray-700 font-semibold mb-2" for="program-image-{{$index}}">Gambar Program (Opsional)</label>
                                        <input type="file" id="program-image-{{$index}}" name="programs[{{$index}}][image]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" accept="image/*">
                                        @if(!empty($program['image']))
                                            <div class="mt-2">
                                                <img src="{{ asset($program['image']) }}" width="150" class="rounded-lg border-2 border-gray-300" onerror="this.src='{{ asset('images/default/image-placeholder.png') }}';">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tombol Tambah Program -->
                        <div class="text-center">
                            <button type="button" class="btn border-2 border-primary bg-white text-primary hover:bg-primary hover:text-white px-6 py-2 rounded-full font-semibold transition" onclick="addProgram()">+ Tambah Program Baru</button>
                        </div>
                    </div>

                    <!-- Program Unggulan Admin -->
                    <div id="programunggulan" role="tabpanel" aria-labelledby="programunggulan-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">📌 Program Unggulan</h3>
                            <p class="text-gray-700 text-sm">Kelola daftar program unggulan yang tampil di halaman utama.</p>
                        </div>

                        <div id="programunggulan-wrapper">
                            @php $pgs = $contents['program_unggulan'] ?? []; @endphp
                            @foreach($pgs as $i => $pg)
                                <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white hover:border-primary hover:shadow-lg transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div><h4 class="text-lg font-bold text-primary">Program Unggulan #{{ $i+1 }}</h4></div>
                                        <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                                    </div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Nama</label><input type="text" name="program_unggulan[{{ $i }}][nama]" class="w-full px-3 py-2 border rounded" value="{{ $pg['nama'] ?? '' }}"></div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Deskripsi</label><textarea name="program_unggulan[{{ $i }}][deskripsi]" class="w-full px-3 py-2 border rounded">{{ $pg['deskripsi'] ?? '' }}</textarea></div>
                                    <div class="grid grid-cols-3 gap-4"> 
                                        <div><label class="block text-sm font-semibold mb-1">Target</label><input type="text" name="program_unggulan[{{ $i }}][target]" class="w-full px-3 py-2 border rounded" value="{{ $pg['target'] ?? '' }}"></div>
                                        <div><label class="block text-sm font-semibold mb-1">Metode</label><input type="text" name="program_unggulan[{{ $i }}][metode]" class="w-full px-3 py-2 border rounded" value="{{ $pg['metode'] ?? '' }}"></div>
                                        <div><label class="block text-sm font-semibold mb-1">Evaluasi</label><input type="text" name="program_unggulan[{{ $i }}][evaluasi]" class="w-full px-3 py-2 border rounded" value="{{ $pg['evaluasi'] ?? '' }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <button type="button" onclick="addProgramUnggulan()" class="btn border-2 border-primary bg-white text-primary hover:bg-primary hover:text-white px-6 py-2 rounded-full">+ Tambah Program Unggulan</button>
                        </div>
                    </div>

                    <!-- Kegiatan Admin -->
                    <div id="kegiatan-admin" role="tabpanel" aria-labelledby="kegiatan-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">🗓️ Kegiatan Pesantren</h3>
                            <p class="text-gray-700 text-sm">Kelola jadwal kegiatan harian.</p>
                        </div>
                        <div id="kegiatan-wrapper">
                            @php $kegs = $contents['kegiatan_pesantren'] ?? []; @endphp
                            @foreach($kegs as $i => $k)
                                <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                                    <div class="flex justify-between items-start mb-4">
                                        <div><h4 class="text-lg font-bold text-primary">Kegiatan #{{ $i+1 }}</h4></div>
                                        <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                                    </div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Waktu</label><input type="text" name="kegiatan[{{ $i }}][waktu]" class="w-full px-3 py-2 border rounded" value="{{ $k['waktu'] ?? '' }}"></div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Kegiatan (pisahkan dengan koma)</label><textarea name="kegiatan[{{ $i }}][kegiatan]" class="w-full px-3 py-2 border rounded">{{ is_array($k['kegiatan'] ?? null) ? implode(', ', $k['kegiatan']) : ($k['kegiatan'] ?? '') }}</textarea></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center"><button type="button" onclick="addKegiatan()" class="btn border-2 border-primary bg-white text-primary hover:bg-primary hover:text-white px-6 py-2 rounded-full">+ Tambah Kegiatan</button></div>
                    </div>

                    <!-- FAQ Admin -->
                    <div id="faq-admin" role="tabpanel" aria-labelledby="faq-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">❓ FAQ</h3>
                            <p class="text-gray-700 text-sm">Kelola daftar pertanyaan dan jawaban.</p>
                        </div>
                        <div id="faq-wrapper">
                            @php $faqsAdmin = $contents['faq'] ?? []; @endphp
                            @foreach($faqsAdmin as $i => $fq)
                                <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                                    <div class="flex justify-between items-start mb-4">
                                        <div><h4 class="text-lg font-bold text-primary">FAQ #{{ $i+1 }}</h4></div>
                                        <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                                    </div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Pertanyaan</label><input type="text" name="faq[{{ $i }}][pertanyaan]" class="w-full px-3 py-2 border rounded" value="{{ $fq['pertanyaan'] ?? '' }}"></div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Jawaban</label><textarea name="faq[{{ $i }}][jawaban]" class="w-full px-3 py-2 border rounded">{{ $fq['jawaban'] ?? '' }}</textarea></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center"><button type="button" onclick="addFAQ()" class="btn border-2 border-primary bg-white text-primary hover:bg-primary hover:text-white px-6 py-2 rounded-full">+ Tambah FAQ</button></div>
                    </div>

                    <!-- Alur Admin -->
                    <div id="alur-admin" role="tabpanel" aria-labelledby="alur-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">🛤️ Alur Pendaftaran</h3>
                            <p class="text-gray-700 text-sm">Kelola langkah-langkah alur pendaftaran.</p>
                        </div>
                        <div id="alur-wrapper">
                            @php $alurs = $contents['alur_pendaftaran'] ?? []; @endphp
                            @foreach($alurs as $i => $a)
                                <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                                    <div class="flex justify-between items-start mb-4">
                                        <div><h4 class="text-lg font-bold text-primary">Langkah #{{ $i+1 }}</h4></div>
                                        <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                                    </div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Judul</label><input type="text" name="alur[{{ $i }}][judul]" class="w-full px-3 py-2 border rounded" value="{{ $a['judul'] ?? '' }}"></div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Deskripsi</label><textarea name="alur[{{ $i }}][deskripsi]" class="w-full px-3 py-2 border rounded">{{ $a['deskripsi'] ?? '' }}</textarea></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center"><button type="button" onclick="addAlur()" class="btn border-2 border-primary bg-white text-primary hover:bg-primary hover:text-white px-6 py-2 rounded-full">+ Tambah Langkah</button></div>
                    </div>

                    <!-- Biaya Admin -->
                    <div id="biaya-admin" role="tabpanel" aria-labelledby="biaya-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">💰 Biaya (Info singkat)</h3>
                            <p class="text-gray-700 text-sm">Teks singkat untuk menampilkan informasi biaya. Untuk harga terperinci gunakan menu Paket/Prices.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Informasi Biaya (teks)</label>
                            <textarea name="biaya_text" class="w-full px-3 py-2 border rounded" rows="4">{{ $contents['biaya']['text'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Persyaratan Admin -->
                    <div id="persyaratan-admin" role="tabpanel" aria-labelledby="persyaratan-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">🗃️ Persyaratan Dokumen</h3>
                            <p class="text-gray-700 text-sm">Kelola daftar persyaratan beserta gambar (opsional).</p>
                        </div>
                        <div id="persyaratan-wrapper">
                            @php $pers = $contents['persyaratan_dokumen'] ?? []; @endphp
                            @foreach($pers as $i => $it)
                                <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                                    <div class="flex justify-between items-start mb-4">
                                        <div><h4 class="text-lg font-bold text-primary">Persyaratan #{{ $i+1 }}</h4></div>
                                        <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                                    </div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Judul</label><input type="text" name="persyaratan[{{ $i }}][title]" class="w-full px-3 py-2 border rounded" value="{{ $it['title'] ?? '' }}"></div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Catatan (opsional)</label><input type="text" name="persyaratan[{{ $i }}][note]" class="w-full px-3 py-2 border rounded" value="{{ $it['note'] ?? '' }}"></div>
                                    <div class="mb-4"><label class="block text-sm font-semibold mb-1">Gambar (opsional)</label><input type="file" name="persyaratan[{{ $i }}][image]" class="w-full px-3 py-2 border rounded"></div>
                                    @if(!empty($it['img']))<div class="mt-2"><img src="{{ asset($it['img']) }}" width="150" class="rounded-lg border" onerror="this.src='{{ asset('images/default/image-placeholder.png') }}';"></div>@endif
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center"><button type="button" onclick="addPersyaratan()" class="btn border-2 border-primary bg-white text-primary hover:bg-primary hover:text-white px-6 py-2 rounded-full">+ Tambah Persyaratan</button></div>
                    </div>

                    <!-- Brosur Admin -->
                    <div id="brosur-admin" role="tabpanel" aria-labelledby="brosur-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">📄 Upload Brosur</h3>
                            <p class="text-gray-700 text-sm">Upload file brosur dalam format PDF yang akan ditampilkan di halaman utama untuk diunduh oleh pengunjung.</p>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2" for="brosur-file">
                                File Brosur (PDF) <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="brosur-file" name="brosur_file" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" accept=".pdf,application/pdf">
                            <small class="text-gray-500 mt-1 block">💡 Format yang didukung: PDF (Maksimal 10MB)</small>
                        </div>

                        @php
                            $brosur = $contents['brosur'] ?? null;
                        @endphp
                        
                        @if(!empty($brosur['file']))
                            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-800 font-semibold mb-1">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            File Brosur Saat Ini
                                        </p>
                                        <p class="text-gray-600 text-sm">
                                            {{ $brosur['filename'] ?? 'brosur.pdf' }}
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1">
                                            Path: {{ $brosur['file'] ?? '' }}
                                        </p>
                                    </div>
                                    <div>
                                        <a href="{{ asset($brosur['file']) }}" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 inline-flex items-center">
                                            <i class="fas fa-eye mr-2"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Belum ada file brosur yang diupload.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- General Settings Admin -->
                    <div id="general-admin" role="tabpanel" aria-labelledby="general-tab" class="hidden tab-pane max-w-4xl mx-auto mt-8">
                        <div class="mb-6 p-6 bg-blue-50 border-l-4 border-primary rounded">
                            <h3 class="text-lg font-bold text-primary mb-2">⚙️ Pengaturan Umum</h3>
                            <p class="text-gray-700 text-sm">Kelola teks navbar, footer, dan tombol yang tampil di halaman utama.</p>
                        </div>

                        @php
                            $general = $contents['general'] ?? [];
                        @endphp

                        <!-- Navbar Settings -->
                        <div class="mb-8 p-6 bg-white border-2 border-gray-200 rounded-lg">
                            <h4 class="text-xl font-bold text-primary mb-4">📱 Navbar</h4>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Logo Text</label>
                                <input type="text" name="general[navbar_logo]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['navbar_logo'] ?? '|| Ponpes Bani Sahid' }}" placeholder="|| Ponpes Bani Sahid">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Menu Items (Pisahkan dengan koma)</label>
                                <input type="text" name="general[navbar_menu]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ isset($general['navbar_menu']) ? implode(', ', $general['navbar_menu']) : 'Beranda, Visi & Misi, Program, Kegiatan, Alur Pendaftaran, Biaya, Persyaratan, FAQ, Kontak' }}" placeholder="Beranda, Visi & Misi, Program, ...">
                                <small class="text-gray-500 mt-1 block">💡 Pisahkan setiap menu dengan koma (,)</small>
                            </div>
                        </div>

                        <!-- Button Settings -->
                        <div class="mb-8 p-6 bg-white border-2 border-gray-200 rounded-lg">
                            <h4 class="text-xl font-bold text-primary mb-4">🔘 Tombol</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Download Brosur</label>
                                    <input type="text" name="general[btn_download_brosur]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['btn_download_brosur'] ?? 'Download Brosur' }}" placeholder="Download Brosur">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Tanya via WhatsApp</label>
                                    <input type="text" name="general[btn_whatsapp]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['btn_whatsapp'] ?? 'Tanya via WhatsApp' }}" placeholder="Tanya via WhatsApp">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Dashboard Admin</label>
                                    <input type="text" name="general[btn_dashboard_admin]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['btn_dashboard_admin'] ?? 'Dashboard Admin' }}" placeholder="Dashboard Admin">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Dashboard Santri</label>
                                    <input type="text" name="general[btn_dashboard_santri]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['btn_dashboard_santri'] ?? 'Dashboard Santri' }}" placeholder="Dashboard Santri">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Login</label>
                                    <input type="text" name="general[btn_login]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['btn_login'] ?? 'Login' }}" placeholder="Login">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Logout</label>
                                    <input type="text" name="general[btn_logout]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['btn_logout'] ?? 'Logout' }}" placeholder="Logout">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Settings -->
                        <div class="mb-8 p-6 bg-white border-2 border-gray-200 rounded-lg">
                            <h4 class="text-xl font-bold text-primary mb-4">📄 Footer</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Menu 1 - Judul</label>
                                    <input type="text" name="general[footer_menu1_title]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['footer_menu1_title'] ?? 'Tentang Kami' }}" placeholder="Tentang Kami">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Menu 2 - Judul</label>
                                    <input type="text" name="general[footer_menu2_title]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['footer_menu2_title'] ?? 'Program' }}" placeholder="Program">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Menu 3 - Judul</label>
                                    <input type="text" name="general[footer_menu3_title]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['footer_menu3_title'] ?? 'Informasi' }}" placeholder="Informasi">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Menu 4 - Judul</label>
                                    <input type="text" name="general[footer_menu4_title]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['footer_menu4_title'] ?? 'Kontak' }}" placeholder="Kontak">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Menu 1 - Links (Pisahkan dengan koma)</label>
                                <input type="text" name="general[footer_menu1_links]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ isset($general['footer_menu1_links']) ? implode(', ', $general['footer_menu1_links']) : 'Visi & Misi, info Beasiswa' }}" placeholder="Visi & Misi, info Beasiswa">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Menu 2 - Links (Pisahkan dengan koma)</label>
                                <input type="text" name="general[footer_menu2_links]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ isset($general['footer_menu2_links']) ? implode(', ', $general['footer_menu2_links']) : 'Tahfidz Al-Qur\'an' }}" placeholder="Tahfidz Al-Qur'an">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Menu 3 - Links (Pisahkan dengan koma)</label>
                                <input type="text" name="general[footer_menu3_links]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ isset($general['footer_menu3_links']) ? implode(', ', $general['footer_menu3_links']) : 'Alur Pendaftaran, Biaya Pendidikan, Persyaratan, FAQ' }}" placeholder="Alur Pendaftaran, Biaya Pendidikan, Persyaratan, FAQ">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Menu 4 - Contact Items (Pisahkan dengan koma)</label>
                                <input type="text" name="general[footer_menu4_links]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ isset($general['footer_menu4_links']) ? implode(', ', $general['footer_menu4_links']) : 'Alamat, WhatsApp Developers, WhatsApp Admin PPDB Putra, WhatsApp Admin PPDB Putri, Sosial Media' }}" placeholder="Alamat, WhatsApp Developers, ...">
                            </div>
                        </div>

                        <!-- Contact Info Settings -->
                        <div class="mb-8 p-6 bg-white border-2 border-gray-200 rounded-lg">
                            <h4 class="text-xl font-bold text-primary mb-4">📞 Informasi Kontak</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">WhatsApp Number (Developer)</label>
                                    <input type="text" name="general[wa_developer]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['wa_developer'] ?? '6287748115931' }}" placeholder="6287748115931">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">WhatsApp Number (Admin Putra)</label>
                                    <input type="text" name="general[wa_admin_putra]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['wa_admin_putra'] ?? '6289510279293' }}" placeholder="6289510279293">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">WhatsApp Number (Admin Putri)</label>
                                    <input type="text" name="general[wa_admin_putri]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['wa_admin_putri'] ?? '6282183953533' }}" placeholder="6282183953533">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Google Maps URL</label>
                                    <input type="text" name="general[google_maps_url]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['google_maps_url'] ?? 'https://www.google.com/maps/place/Pondok+Pesantren+Al-Qur\'an+Bani+Syahid/@-6.3676771,106.8696904,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69ed654ce6786b:0x1019880ca4f9403b!8m2!3d-6.3676824!4d106.8722707!16s%2Fg%2F11f6m9qmmr?hl=id' }}" placeholder="https://maps.google.com/...">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Social Media Link</label>
                                    <input type="text" name="general[social_media_url]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['social_media_url'] ?? 'https://banisyahid.bio.link/' }}" placeholder="https://banisyahid.bio.link/">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Instagram Link</label>
                                    <input type="text" name="general[instagram_url]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="{{ $general['instagram_url'] ?? 'https://www.instagram.com/ponpesalquranbanisyahid_/' }}" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 justify-center text-center">
                    <button type="submit" class="btn bg-primary text-white hover:bg-gray-800 btn-lg px-4 py-2 rounded-full">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Tab Switching Functionality
    class TabManager {
        constructor(containerSelector, options = {}) {
            this.container = document.querySelector(containerSelector);
            this.tabsContainer = this.container.querySelector('[data-tabs-toggle]');
            this.contentContainer = document.querySelector(this.tabsContainer.getAttribute('data-tabs-toggle'));
            this.tabButtons = this.tabsContainer.querySelectorAll('[role="tab"]');
            this.tabPanes = this.contentContainer.querySelectorAll('[role="tabpanel"]');
            
            this.activeClasses = options.activeClasses || 'text-primary border-primary';
            this.inactiveClasses = options.inactiveClasses || 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300';
            
            this.init();
        }

        init() {
            this.tabButtons.forEach(button => {
                button.addEventListener('click', () => this.showTab(button));
            });
        }

        showTab(button) {
            const targetId = button.getAttribute('data-tabs-target');
            const target = document.querySelector(targetId);

            // Hide all tabs
            this.tabPanes.forEach(pane => {
                pane.classList.add('hidden');
            });

            // Remove active state from all buttons
            this.tabButtons.forEach(btn => {
                btn.setAttribute('aria-selected', 'false');
                btn.classList.remove(...this.activeClasses.split(' '));
                btn.classList.add(...this.inactiveClasses.split(' '));
            });

            // Show selected tab
            if (target) {
                target.classList.remove('hidden');
            }

            // Set active state on clicked button
            button.setAttribute('aria-selected', 'true');
            button.classList.remove(...this.inactiveClasses.split(' '));
            button.classList.add(...this.activeClasses.split(' '));
        }
    }

    // Initialize tabs when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        new TabManager('form', {
            activeClasses: 'border-primary text-primary',
            inactiveClasses: 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300'
        });
    });

    // Script Tambah Misi
    function addMisi() {
        let html = `
            <div class="input-group mb-2 flex gap-2">
                <input type="text" name="misi[]" class="flex-1 bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" placeholder="Tulis misi baru...">
                <button type="button" class="btn bg-red-500 hover:bg-red-600 text-white remove-row rounded px-3 font-bold">✕</button>
            </div>`;
        document.getElementById('misi-wrapper').insertAdjacentHTML('beforeend', html);
        attachRemoveListener();
    }

    // Script Tambah Program (Menggunakan Index Time agar unik)
    function addProgram() {
        let index = Date.now(); // Unique ID
        let html = `
            <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white hover:border-primary hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-primary">Program Baru</h4>
                    </div>
                    <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                </div>

                <!-- Nama Program -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Program <span class="text-red-500">*</span></label>
                    <input type="text" name="programs[${index}][title]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" placeholder="Contoh: Tahfidz Al-Qur'an" required>
                </div>

                <!-- Deskripsi Program -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi Singkat <span class="text-red-500">*</span></label>
                    <textarea name="programs[${index}][description]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" rows="3" placeholder="Jelaskan secara singkat apa yang menjadi fokus program ini" required></textarea>
                </div>

                <!-- Keunggulan Program -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Keunggulan Program (Pisahkan dengan koma) <span class="text-red-500">*</span></label>
                    <textarea name="programs[${index}][advantages]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" rows="3" placeholder="Contoh: Full AC, Guru Senior, Ekstrakurikuler" required></textarea>
                    <small class="text-gray-500 mt-1 block">💡 Tip: Pisahkan setiap keunggulan dengan koma (,)</small>
                </div>

                <!-- Gambar Program -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Program (Opsional)</label>
                    <input type="file" name="programs[${index}][image]" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary" accept="image/*">
                </div>
            </div>`;
        document.getElementById('program-wrapper').insertAdjacentHTML('beforeend', html);
        attachRemoveListener();
    }

    // Attach remove listeners
    function attachRemoveListener() {
        document.addEventListener('click', function(e) {
            if(e.target && e.target.classList.contains('remove-row')) {
                e.target.parentElement.remove();
            }
            if(e.target && e.target.classList.contains('remove-program')) {
                e.target.closest('.program-item').remove();
            }
        });
    }

    // Tambah Program Unggulan
    function addProgramUnggulan() {
        let index = Date.now();
        let html = `
            <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white hover:border-primary hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-primary">Program Unggulan Baru</h4>
                    </div>
                    <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                </div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Nama</label><input type="text" name="program_unggulan[${index}][nama]" class="w-full px-3 py-2 border rounded"></div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Deskripsi</label><textarea name="program_unggulan[${index}][deskripsi]" class="w-full px-3 py-2 border rounded"></textarea></div>
                <div class="grid grid-cols-3 gap-4"> 
                    <div><label class="block text-sm font-semibold mb-1">Target</label><input type="text" name="program_unggulan[${index}][target]" class="w-full px-3 py-2 border rounded"></div>
                    <div><label class="block text-sm font-semibold mb-1">Metode</label><input type="text" name="program_unggulan[${index}][metode]" class="w-full px-3 py-2 border rounded"></div>
                    <div><label class="block text-sm font-semibold mb-1">Evaluasi</label><input type="text" name="program_unggulan[${index}][evaluasi]" class="w-full px-3 py-2 border rounded"></div>
                </div>
            </div>`;
        document.getElementById('programunggulan-wrapper').insertAdjacentHTML('beforeend', html);
        attachRemoveListener();
    }

    // Tambah Kegiatan
    function addKegiatan() {
        let index = Date.now();
        let html = `
            <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-primary">Kegiatan Baru</h4>
                    </div>
                    <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                </div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Waktu</label><input type="text" name="kegiatan[${index}][waktu]" class="w-full px-3 py-2 border rounded"></div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Kegiatan (pisahkan dengan koma)</label><textarea name="kegiatan[${index}][kegiatan]" class="w-full px-3 py-2 border rounded"></textarea></div>
            </div>`;
        document.getElementById('kegiatan-wrapper').insertAdjacentHTML('beforeend', html);
        attachRemoveListener();
    }

    // Tambah FAQ
    function addFAQ() {
        let index = Date.now();
        let html = `
            <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-primary">FAQ Baru</h4>
                    </div>
                    <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                </div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Pertanyaan</label><input type="text" name="faq[${index}][pertanyaan]" class="w-full px-3 py-2 border rounded"></div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Jawaban</label><textarea name="faq[${index}][jawaban]" class="w-full px-3 py-2 border rounded"></textarea></div>
            </div>`;
        document.getElementById('faq-wrapper').insertAdjacentHTML('beforeend', html);
        attachRemoveListener();
    }

    // Tambah Alur
    function addAlur() {
        let index = Date.now();
        let html = `
            <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-primary">Langkah Baru</h4>
                    </div>
                    <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                </div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Judul</label><input type="text" name="alur[${index}][judul]" class="w-full px-3 py-2 border rounded"></div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Deskripsi</label><textarea name="alur[${index}][deskripsi]" class="w-full px-3 py-2 border rounded"></textarea></div>
            </div>`;
        document.getElementById('alur-wrapper').insertAdjacentHTML('beforeend', html);
        attachRemoveListener();
    }

    // Tambah Persyaratan
    function addPersyaratan() {
        let index = Date.now();
        let html = `
            <div class="program-item border-2 border-gray-200 rounded-lg p-6 mb-6 bg-white">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-primary">Persyaratan Baru</h4>
                    </div>
                    <button type="button" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white remove-program px-3 py-1 rounded">✕ Hapus</button>
                </div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Judul</label><input type="text" name="persyaratan[${index}][title]" class="w-full px-3 py-2 border rounded"></div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Catatan (opsional)</label><input type="text" name="persyaratan[${index}][note]" class="w-full px-3 py-2 border rounded"></div>
                <div class="mb-4"><label class="block text-sm font-semibold mb-1">Gambar (opsional)</label><input type="file" name="persyaratan[${index}][image]" class="w-full px-3 py-2 border rounded"></div>
            </div>`;
        document.getElementById('persyaratan-wrapper').insertAdjacentHTML('beforeend', html);
        attachRemoveListener();
    }

    // Initialize remove listeners on page load
    document.addEventListener('DOMContentLoaded', attachRemoveListener);
</script>
@endsection