<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Pesantren AI-Our'an Bani Syahid 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#057572',
                        'secondary': '#5B5B5B',
                        'accent': '#9D9D9D',
                        'white': '#FFFFFF'
                    }
                }
            }
        }
    </script>
    <style>
        .icon-bg {
            background-color: rgba(5, 117, 114, 0.1);
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #057572;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-6 rounded-full mx-4 mt-4 sticky top-4 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold text-primary">PPDB Ponpes Al Bani</div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-primary focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex space-x-8">
                <a href="#" class="text-primary hover:text-secondary font-medium">Beranda</a>
                <a href="#visi-misi" class="text-primary hover:text-secondary font-medium">Visi & Misi</a>
                <a href="#program" class="text-primary hover:text-secondary font-medium">Program</a>
                <a href="#alur-pendaftaran" class="text-primary hover:text-secondary font-medium">Alur Pendaftaran</a>
                <a href="#biaya" class="text-primary hover:text-secondary font-medium">Biaya</a>
                <a href="#persyaratan" class="text-primary hover:text-secondary font-medium">Persyaratan</a>
                <a href="#" class="text-primary hover:text-secondary font-medium">Kontak</a>
            </div>

            <div class="hidden md:block">
               <a href="{{ route('login') }}">
            <button class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition duration-300">
                Login
            </button>
            </a>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4">
            <a href="#" class="block py-2 text-primary hover:text-secondary">Beranda</a>
            <a href="#visi-misi" class="block py-2 text-primary hover:text-secondary">Visi & Misi</a>
            <a href="#program" class="block py-2 text-primary hover:text-secondary">Program</a>
            <a href="#alur-pendaftaran" class="block py-2 text-primary hover:text-secondary">Alur Pendaftaran</a>
            <a href="#biaya" class="block py-2 text-primary hover:text-secondary">Biaya</a>
            <a href="#persyaratan" class="block py-2 text-primary hover:text-secondary">Persyaratan</a>
            <a href="#" class="block py-2 text-primary hover:text-secondary">Kontak</a>
            <a href="{{ route('login') }}">
            <button class="bg-primary text-white px-6 py-2 rounded-full hover:bg-secondary transition duration-300">
                Login
            </button>
            </a>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="py-12 px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-primary mb-4">PPDB</h1>
        <h2 class="text-2xl md:text-3xl font-semibold text-primary mb-8">Pesantren AI-Our'an Bani Syahid 2025</h2>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md mb-8">
            <p class="text-secondary text-lg mb-4">
               Sistem Penerimaan Peserta Didik Baru yang modern, mudah, dan terpercaya.
                <br>Untuk Masa Depan Yang Lebih Baik
            </p>
            <p class="text-accent italic"></p>
                <a href="{{ route('register') }}">
            <button class="bg-primary text-white px-4 py-1.5 rounded-full hover:bg-secondary transition duration-300 max-w-xs mx-auto my-3">
                Daftar Sekarang
            </button>
        </a>
        </div>
    </header>

    <!-- Visi & Misi Section -->
    <section id="visi-misi" class="py-16 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-12">Visi & Misi</h2>

            <div class="max-w-4xl mx-auto">
                <!-- Visi -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-8 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-6">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-eye text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-primary">Visi</h3>
                    </div>
                    <p class="text-lg text-secondary leading-relaxed">
                        Menjadi pusat pendidikan Al-Qur'an yang unggul dalam pembacaan, penghafalan, dan pemahaman Al-Qur'an,
                        dengan tetap istiqamah di atas manhaj dan warisan kelimuan para guru kami.
                    </p>
                </div>

                <!-- Misi -->
                <div class="bg-white rounded-xl shadow-lg p-8 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-6">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bullseye text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-primary">Misi</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <i class="fas fa-check text-primary mt-1 mr-3"></i>
                            <p class="text-secondary">Menanamkan keointaan terhadap Al-Qur'an sejak dini melalui pembelajaran yang menyeturuh: tilawah, tahtida, dan tafsir.</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-primary mt-1 mr-3"></i>
                            <p class="text-secondary">Membina santri menjadi haftar dan haftarah yang mutqin (kokoh hafalamya) dan berakhlak Qur'ani.</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-primary mt-1 mr-3"></i>
                            <p class="text-secondary">Mengajarkan metode pembacaan Al-Qur'an yang sesuai dengan tapivid dan qira'at yang mu'tabarah.</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-primary mt-1 mr-3"></i>
                            <p class="text-secondary">Mengembangkan sistem pendidikan yang berlandaskan pada nilai-nilai warisan guru dan ulama terdanulu.</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-primary mt-1 mr-3"></i>
                            <p class="text-secondary">Menumbuhkan semangat dakwah dan pengabdian di tengah masyarakat melalui nilai-nilai Al-Qur'an.</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check text-primary mt-1 mr-3"></i>
                            <p class="text-secondary">Menjaga dan melestarikan sanad kelimuan dalam pembelajaran Al-Qur'an dan ilmu-ilmu keislaman lainnya.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Unggulan Section -->
    <section id="program" class="py-16 px-4">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Program Unggulan</h2>
            <p class="text-center text-secondary mb-12">Program-program unggulan yang ditawarkan oleh Pesantren AI-Our'an Bani Syahid</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Program 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-quran text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary">Tahfıdzul Qur'an</h3>
                    </div>
                    <p class="text-secondary mb-4">Program Hafalan Al-Qur'an</p>
                    <ul class="text-secondary space-y-2">
                        <li><span class="font-semibold">Target:</span> Hafal 30 Juz dalam waktu 3-5 tahun</li>
                        <li><span class="font-semibold">Metode:</span> Talagd dan murajash harian bersama muayil/ah</li>
                        <li><span class="font-semibold">Sistem evaluasi:</span> Setora harian, tasmî mingguan, dan ujian tahunan</li>
                    </ul>
                </div>

                <!-- Program 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-book-open text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary">Qiraat Sab'ah</h3>
                    </div>
                    <p class="text-secondary mb-4">Program Qiraat Sab'ah </p>
                    <ul class="text-secondary space-y-2">
                        <li><span class="font-semibold">Target:</span> Menguasai tujuh dira'at mutawati sesuai rivayat yang sahih</li>
                        <li><span class="font-semibold">Materi:</span> Teori dan praktik dira'at berdasarkan matan "Aay-Syatibiyyah"</li>
                        <li><span class="font-semibold">Output:</span> Santri memahami perbedaan qiraat dan mampu membacanya dengan tepat</li>
                    </ul>
                </div>

                <!-- Program 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-music text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary">Nagham</h3>
                    </div>
                    <p class="text-secondary mb-4">Program Irama Tilawah Al-Qur'an</p>
                    <ul class="text-secondary space-y-2">
                        <li><span class="font-semibold">Tujuan:</span> Meninşkatkan kualltas bacaan santri dengan irama yang sesuai kaidah tajwid dan nagham</li>
                        <li><span class="font-semibold">Jenis Nagham:</span> Bayati, Shoba, Hijaz, Mahawan, Bast, Sika, Jiranka</li>
                        <li><span class="font-semibold">Keglator:</span> Lathan rutin, lomba internal, dan pembinaan untuk Musabaqah Tilawati! Qur'an (MTQ)</li>
                    </ul>
                </div>

                <!-- Program 4 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <div class="icon-bg w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-graduation-cap text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold text-primary">Kajlan Kitab Ulama Klasik (Turats)</h3>
                    </div>
                    <p class="text-secondary mb-4">Pengajian Kitab Kuning</p>
                    <ul class="text-secondary space-y-2">
                        <li><span class="font-semibold">Sistem:</span> Talaqqi (pengajian langsung) dan diskusi kitab kuning</li>
                        <li><span class="font-semibold">Target:</span> Santri memahami dasar-dasar limu Islam dari sumber klasik</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran Section -->
   <section id="alur-pendaftaran" class="py-20 px-6 bg-gradient-to-b from-primary/10 via-white to-primary/5">
  <div class="container mx-auto">
    <h2 class="text-4xl font-extrabold text-center text-primary mb-4 tracking-wide">
      Alur Pendaftaran
    </h2>
    <p class="text-center text-gray-600 mb-16">
      Tahapan pendaftaran PPDB Pesantren Al-Qur'an Bani Syahid
    </p>

    <div class="relative max-w-5xl mx-auto">
      <!-- Garis penghubung vertikal -->
      <div class="absolute left-8 top-0 h-full w-1 bg-gradient-to-b from-primary/50 to-secondary/50 rounded-full"></div>

      <!-- Step Template -->
      <div class="space-y-12">
        <!-- Step 1 -->
        <div class="relative flex items-start gap-6 group">
          <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
            1
          </div>
          <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
            <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">Membuat Akun</h3>
            <p class="text-gray-600 leading-relaxed">
              Membuat akun pada website PPDB Pondok Pesantren Al Bani Syahid.
            </p>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="relative flex items-start gap-6 group">
          <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
            2
          </div>
          <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
            <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">Isi Biodata</h3>
            <p class="text-gray-600 leading-relaxed">
              Login kembali ke website PPDB Al Bani Syahid, lengkapi biodata dan kirim berkas.
            </p>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="relative flex items-start gap-6 group">
          <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
            3
          </div>
          <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
            <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">Pembayaran</h3>
            <p class="text-gray-600 leading-relaxed">
              Lakukan pembayaran melalui metode yang disediakan atau langsung ke pesantren.
            </p>
          </div>
        </div>

        <!-- Step 4 -->
        <div class="relative flex items-start gap-6 group">
          <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
            4
          </div>
          <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
            <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">Cetak Kartu Peserta</h3>
            <p class="text-gray-600 leading-relaxed">
              Cetak kartu peserta yang berisi barcode dan informasi peserta.
            </p>
          </div>
        </div>

        <!-- Step 5 -->
        <div class="relative flex items-start gap-6 group">
          <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
            5
          </div>
          <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
            <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">Tes dan Wawancara</h3>
            <p class="text-gray-600 leading-relaxed">
              Calon santri akan dipanggil oleh pihak pesantren untuk tes dan wawancara.
            </p>
          </div>
        </div>

        <!-- Step 6 -->
        <div class="relative flex items-start gap-6 group">
          <div class="z-10 flex items-center justify-center w-14 h-14 bg-primary text-white font-bold rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
            6
          </div>
          <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex-1 border border-primary/10">
            <h3 class="text-2xl font-semibold text-primary mb-2 group-hover:text-primary/90">Pengumuman Kelulusan</h3>
            <p class="text-gray-600 leading-relaxed">
              Calon santri dapat melihat hasil kelulusan di website PPDB Pondok Pesantren Al Bani Syahid.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


    <!-- Informasi Biaya Section -->
    <section id="biaya" class="py-16 px-4">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Informasi Biaya Takhossus Pesantren</h2>
            <p class="text-center text-secondary mb-12">Informasi Biaya Pondok Pesantren Al Bani</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-gradient-to-br from-primary to-primary/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-money-bill-wave text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Pendaftaran</div>
                    </div>
                    <div class="text-lg font-medium">Rp.300.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">💰 Biaya awal pendaftaran</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-gradient-to-br from-secondary to-secondary/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-graduation-cap text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Bangunan</div>
                    </div>
                    <div class="text-lg font-medium">Rp.300.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">🏫 Untuk fasilitas pesantren</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-gradient-to-br from-accent to-accent/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-percentage text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Kitab</div>
                    </div>
                    <div class="text-lg font-medium">Rp.400.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">📚 Buku pelajaran dan kitab</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-gradient-to-br from-primary to-accent rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-chart-line text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Perlengkapan</div>
                    </div>
                    <div class="text-lg font-medium">Rp.1.000.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">🛏️ Untuk Lemari, Kasur Dan Bantal</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi Biaya Takhossus Pesantren Plus Sekolah Section -->
    <section class="py-16 px-4 bg-gradient-to-r from-primary/10 to-primary/20">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold text-center text-primary mb-4">Informasi Biaya Takhossus Pesantren Plus Sekolah</h2>
            <p class="text-center text-secondary mb-12">Informasi Biaya Pondok Pesantren Al Bani</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-gradient-to-br from-primary to-primary/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-money-bill-wave text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Pendaftaran</div>
                    </div>
                    <div class="text-lg font-medium">Rp.500.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">💰 Biaya awal pendaftaran</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-gradient-to-br from-secondary to-secondary/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-graduation-cap text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Bangunan</div>
                    </div>
                    <div class="text-lg font-medium">Rp.300.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">🏫 Untuk fasilitas pesantren</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-gradient-to-br from-accent to-accent/80 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-percentage text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Kitab</div>
                    </div>
                    <div class="text-lg font-medium">Rp.400.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">📚 Buku pelajaran dan kitab</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-gradient-to-br from-primary to-accent rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-chart-line text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Perlengkapan</div>
                    </div>
                    <div class="text-lg font-medium">Rp.1.000.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">🛏️ Untuk Lemari, Kasur Dan Bantal</p>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="bg-gradient-to-br from-green-500 to-green-400 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-book text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Syahriyah</div>
                    </div>
                    <div class="text-lg font-medium">Rp.400.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm"></p>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-400 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-105">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-tshirt text-2xl mr-3"></i>
                        <div class="text-2xl font-bold">Sekolah</div>
                    </div>
                    <div class="text-lg font-medium">Rp.100.000</div>
                    <div class="mt-4 pt-4 border-t border-white/30">
                        <p class="text-sm">🏫 Biaya sekolah per bulan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Persyaratan Dokumen Section -->
  <section id="persyaratan" class="py-20 bg-gradient-to-b from-[#f7fafc] to-[#e6f0ea]">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-4xl font-extrabold text-emerald-800 mb-3 tracking-wide">
      Persyaratan Dokumen
    </h2>
    <p class="text-gray-600 mb-16">
      Dokumen-dokumen yang diperlukan untuk pendaftaran santri baru
    </p>

    <div class="flex flex-col md:flex-row items-stretch justify-center gap-6 md:gap-10 flex-wrap">

      <!-- TEMPLATE CARD -->
      <div class="group bg-white rounded-2xl shadow-md border border-emerald-200 hover:border-emerald-400 hover:shadow-xl transition-all duration-300 w-48 h-[240px] flex flex-col items-center justify-start p-6">
        <div class="bg-emerald-100 rounded-full w-20 h-20 flex items-center justify-center overflow-hidden mb-4">
          <img src="{{ asset('image/formulir.png') }}" alt="Formulir" class="w-50 h-50 object-contain">
        </div>
        <h3 class="text-lg font-semibold text-emerald-800 mb-1">Formulir </h3>
        <p class="text-gray-600 text-sm leading-snug">Bisa melalui offline atau website.</p>
      </div>

      <div class="hidden md:flex text-3xl text-emerald-600 items-center">➜</div>

      <div class="group bg-white rounded-2xl shadow-md border border-emerald-200 hover:border-emerald-400 hover:shadow-xl transition-all duration-300 w-48 h-[240px] flex flex-col items-center justify-start p-6">
        <div class="bg-emerald-100 rounded-full w-20 h-20 flex items-center justify-center overflow-hidden mb-4">
          <img src="{{ asset('image/pasfoto.png') }}" alt="Pas Foto" class="w-50 h-50 object-contain">
        </div>
        <h3 class="text-lg font-semibold text-emerald-800 mb-1">Pas Foto 3x4</h3>
        <p class="text-gray-600 text-sm leading-snug">Sebanyak 4 lembar.</p>
      </div>

      <div class="hidden md:flex text-3xl text-emerald-600 items-center">➜</div>

      <div class="group bg-white rounded-2xl shadow-md border border-emerald-200 hover:border-emerald-400 hover:shadow-xl transition-all duration-300 w-48 h-[240px] flex flex-col items-center justify-start p-6">
        <div class="bg-emerald-100 rounded-full w-20 h-20 flex items-center justify-center overflow-hidden mb-4">
          <img src="{{ asset('image/akte.png') }}" alt="Akte Kelahiran" class="w-50 h-50 object-contain">
        </div>
        <h3 class="text-lg font-semibold text-emerald-800 mb-1">Akte Kelahiran</h3>
        <p class="text-gray-600 text-sm leading-snug">Dalam bentuk fotokopi.</p>
      </div>

      <div class="hidden md:flex text-3xl text-emerald-600 items-center">➜</div>

      <div class="group bg-white rounded-2xl shadow-md border border-emerald-200 hover:border-emerald-400 hover:shadow-xl transition-all duration-300 w-48 h-[240px] flex flex-col items-center justify-start p-6">
        <div class="bg-emerald-100 rounded-full w-20 h-20 flex items-center justify-center overflow-hidden mb-4">
          <img src="{{ asset('image/kk.png') }}" alt="Kartu Keluarga" class="w-50 h-50 object-contain">
        </div>
        <h3 class="text-lg font-semibold text-emerald-800 mb-1">Kartu Keluarga</h3>
        <p class="text-gray-600 text-sm leading-snug">Fotokopi Kartu Keluarga.</p>
      </div>

      <div class="hidden md:flex text-3xl text-emerald-600 items-center">➜</div>

      <div class="group bg-white rounded-2xl shadow-md border border-emerald-200 hover:border-emerald-400 hover:shadow-xl transition-all duration-300 w-48 h-[240px] flex flex-col items-center justify-start p-6">
        <div class="bg-emerald-100 rounded-full w-20 h-20 flex items-center justify-center overflow-hidden mb-4">
          <img src="{{ asset('image/ijazah.png') }}" alt="Ijazah" class="w-50 h-50 object-contain">
        </div>
        <h3 class="text-lg font-semibold text-emerald-800 mb-1">SKL atau Ijazah</h3>
        <p class="text-gray-600 text-sm leading-snug">SKL/Ijazah SD,SMP dan SMA, atau rapor terakhir bagi yang belum lulus.</p>
      </div>
    </div>
  </div>
</section>





    <!-- Footer -->
    <footer class="bg-primary text-white py-12 px-4">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Menu 1 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Tentang Kami</h3>
                    <ul class="space-y-2">
                        <li><a href="#visi-misi" class="hover:text-accent transition duration-300">Visi & Misi</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Sejarah</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Struktur Organisasi</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Fasilitas</a></li>
                    </ul>
                </div>

                <!-- Menu 2 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Program</h3>
                    <ul class="space-y-2">
                        <li><a href="#program" class="hover:text-accent transition duration-300">Tahfidz AI-Our'an</a></li>
                        <li><a href="#program" class="hover:text-accent transition duration-300">Pendidikan Formal</a></li>
                        <li><a href="#program" class="hover:text-accent transition duration-300">Keterampilan Digital</a></li>
                        <li><a href="#program" class="hover:text-accent transition duration-300">Ekstrakurikuler</a></li>
                    </ul>
                </div>

                <!-- Menu 3 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Informasi</h3>
                    <ul class="space-y-2">
                        <li><a href="#alur-pendaftaran" class="hover:text-accent transition duration-300">Alur Pendaftaran</a></li>
                        <li><a href="#biaya" class="hover:text-accent transition duration-300">Biaya Pendidikan</a></li>
                        <li><a href="#persyaratan" class="hover:text-accent transition duration-300">Persyaratan</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Pengumuman</a></li>
                    </ul>
                </div>

                <!-- Menu 4 -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <ul class="space-y-2">
                      <a href="https://www.google.com/maps/place/Pondok+Pesantren+Al-Qur'an+Bani+Syahid/@-6.3676771,106.8696904,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69ed654ce6786b:0x1019880ca4f9403b!8m2!3d-6.3676824!4d106.8722707!16s%2Fg%2F11f6m9qmmr?hl=id" 
   target="_blank" 
   rel="noopener noreferrer" 
   class="hover:text-accent transition duration-300">
   Alamat
</a>


                        <li><a href="#" class="hover:text-accent transition duration-300">Telepon</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Email</a></li>
                        <li><a href="#" class="hover:text-accent transition duration-300">Sosial Media</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/20 mt-8 pt-8 text-center">
                <p>&copy; 2025 PPDB Pesantren AI-Our'an Bani Syahid. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
