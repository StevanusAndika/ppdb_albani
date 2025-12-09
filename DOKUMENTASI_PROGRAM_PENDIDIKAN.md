# 📚 Dokumentasi: Input Program Pendidikan dengan Hero Section dan Visi Misi

## 📋 Ringkasan Fitur
Anda telah berhasil membuat sistem manajemen program pendidikan yang komprehensif dengan beberapa komponen utama:

---

## 1️⃣ **Form Admin untuk Input Program Pendidikan**

### Lokasi File
- **Admin Form**: `resources/views/dashboard/admin/landing/index.blade.php`
- **Controller**: `app/Http/Controllers/Admin/LandingContentController.php`

### Fitur Form
Tab **"Program Pendidikan"** di halaman admin memungkinkan untuk:

#### ✅ Menambah Program
- Klik tombol **"+ Tambah Program Baru"**
- Form otomatis muncul dengan input:
  - **Nama Program** (Wajib) - Contoh: "Tahfidz Al-Qur'an"
  - **Deskripsi Singkat** (Wajib) - Penjelasan program
  - **Keunggulan Program** (Wajib) - Pisahkan dengan koma
  - **Gambar Program** (Opsional) - Upload thumbnail program

#### 🗑️ Menghapus Program
- Klik tombol **"✕ Hapus"** di setiap kartu program

#### 💾 Menyimpan Data
- Klik **"Simpan Perubahan"** untuk menyimpan semua perubahan

### Validasi Data
- Field yang wajib diisi akan ditandai dengan `*`
- Keunggulan program otomatis dipisah berdasarkan koma saat disimpan
- Gambar program disimpan di folder `storage/app/public/programs/`

---

## 2️⃣ **Form Input Visi & Misi**

### Lokasi File
- **Admin Form**: `resources/views/dashboard/admin/landing/index.blade.php` (Tab "Visi & Misi")
- **Frontend Component**: `resources/views/layouts/components/visi-misi-section.blade.php`

### Fitur Form
#### 📝 Input Visi
- Text area untuk menginput visi pesantren
- Mendukung teks panjang dan multi-line
- Data ditampilkan di halaman publik

#### 📋 Input Misi
- Sistem dinamis dengan tombol **"+ Tambah Misi"**
- Setiap misi dapat dihapus dengan klik **"✕"**
- Misi ditampilkan dengan nomor urutan otomatis di frontend

### Tampilan Frontend
Component `visi-misi-section.blade.php` menampilkan:
- Card Visi dengan icon teropong
- Card Misi dengan list bernomor
- Section Nilai-Nilai dengan 4 pilar utama:
  1. 🕌 **Qur'ani** - Al-Qur'an sebagai pusat pembelajaran
  2. 💚 **Amanah** - Penuh tanggung jawab dan integritas
  3. 🧠 **Cerdas** - Kecerdasan akademik dan spiritual
  4. 🤝 **Bermartabat** - Menjunjung tinggi harkat individu

---

## 3️⃣ **Hero Section Program Pendidikan**

### Lokasi File
- **Component**: `resources/views/layouts/components/hero-program-section.blade.php`

### Fitur
Sebuah hero section yang elegan dengan:

#### 🎨 Desain
- Gradient background dari primary (#057572) ke blue
- Dekoratif floating elements
- Responsive design (mobile-friendly)

#### 📝 Konten
- **Judul**: "Raih Masa Depan Cemerlang Bersama Kami"
- **Deskripsi**: Penjelasan singkat program pendidikan
- **Key Features**: 4 bullet points utama
- **Call-to-Action Buttons**:
  - "Daftar Sekarang" (Primary CTA)
  - "Lihat Program" (Secondary CTA)
- **Trust Badge**: Statistik santri dan lama berdiri

#### 🎴 Floating Cards
Menampilkan 3 program utama dalam bentuk cards yang floating:
1. **MTS Bani Syahid** - 12-15 tahun (3 Tahun)
2. **MA Bani Syahid** - 15-18 tahun (3 Tahun)
3. **Takhassus Al-Qur'an** - 17+ tahun (3-5 Tahun)

---

## 4️⃣ **Section Tampilan Program Pendidikan**

### Lokasi File
- **Component**: `resources/views/layouts/components/program-pendidikan-section.blade.php`

### Fitur
Menampilkan program pendidikan dalam format grid 3 kolom:

#### 📊 Layout
- **Desktop**: 3 kolom responsive
- **Mobile**: 1 kolom full-width
- **Hover Effect**: Scale up dan shadow enhancement

#### 🎫 Program Card Content
Setiap card menampilkan:
- **Gambar/Header** dengan gradient color
- **Judul Program**
- **Deskripsi** singkat
- **Info Grid**:
  - Jenjang Pendidikan
  - Durasi Program
- **Keunggulan Program**: List dengan icon check
- **CTA Button**: "Daftar Sekarang"

#### 💡 Info Box
Box informasi di bawah dengan:
- Icon lightbulb
- Deskripsi "Memilih Program yang Tepat"
- Button "Konsultasi Gratis" (WhatsApp link)

#### ⚡ Fallback System
Jika tidak ada data dari database, sistem otomatis menggunakan data default:
- MTS Bani Syahid
- MA Bani Syahid
- Takhassus Al-Qur'an

---

## 📊 **Data Flow**

### Admin Input
```
Admin Input Form (admin/landing/index.blade.php)
    ↓
POST /admin/content/update (LandingContentController)
    ↓
LandingContent Model (Database)
    ↓
Storage: storage/app/public/programs/
```

### Display di Frontend
```
WelcomeController::index()
    ↓
Ambil dari LandingContent::all()->pluck('payload', 'key')
    ↓
Pass ke view ($programs, $visiMisi, $landingContent)
    ↓
Component Display:
  - hero-program-section.blade.php
  - visi-misi-section.blade.php
  - program-pendidikan-section.blade.php
```

---

## 🔧 **Update Controller**

File: `app/Http/Controllers/Admin/LandingContentController.php`

### Method: `update()`
Menangani:
1. **Hero Section Update**
   - Update text fields (title, tagline, whatsapp)
   - Handle upload gambar hero

2. **Visi Misi Update**
   - Update visi text
   - Update array misi (filter empty values)

3. **Programs Update** ✨ **NEW**
   - Handle multiple programs
   - Process keunggulan (explode by comma)
   - Handle program image upload
   - Maintain old images if no new upload

### File Upload Path
```
Programs: /storage/programs/
Heroes: /storage/hero/
```

---

## 🎯 **Integration Points**

### 1. Hubungkan ke Welcome View
Pastikan di `resources/views/welcome.blade.php` Anda include components:

```blade
<!-- Hero Section Program -->
@include('layouts.components.hero-program-section')

<!-- Visi & Misi Section -->
@include('layouts.components.visi-misi-section')

<!-- Program Pendidikan Section -->
@include('layouts.components.program-pendidikan-section')
```

### 2. Update Routes (Jika Belum Ada)
Pastikan routes sudah terdefinisi:
```php
Route::post('/admin/content/update', [LandingContentController::class, 'update'])->name('admin.content.update');
```

### 3. Storage Link
Pastikan symlink storage sudah dibuat:
```bash
php artisan storage:link
```

---

## 📱 **Features Responsiveness**

### Mobile (< 768px)
- ✅ Form input full-width
- ✅ Program cards stack vertically
- ✅ Hero section optimized
- ✅ Text size adjusted

### Tablet (768px - 1024px)
- ✅ 2-column grid untuk programs
- ✅ Optimized spacing

### Desktop (> 1024px)
- ✅ 3-column grid untuk programs
- ✅ Full horizontal layout
- ✅ Hover effects enabled

---

## 🎨 **Styling & Colors**

### Primary Colors (dari Tailwind config)
- **Primary**: `#057572` (Teal)
- **Secondary**: `#5B5B5B` (Gray)
- **Accent**: `#9D9D9D` (Light Gray)

### Component Colors
- **Visi**: Blue theme with checkmark icons
- **Misi**: Numbered circles with gradient
- **Programs**: Individual gradient per program
- **Buttons**: Primary color with hover effects

---

## 🚀 **Next Steps (Optional Enhancements)**

1. **Preview Gambar**
   - ✅ Sudah implemented di form
   - Gambar preview muncul setelah upload

2. **Search & Filter**
   - Tambahkan search input untuk mencari program
   - Filter berdasarkan duration, level, etc.

3. **Rating/Review**
   - Tambahkan rating system dari calon santri
   - Review teks untuk setiap program

4. **Video Integration**
   - Tambahkan video intro per program
   - YouTube embed support

5. **Analytics**
   - Track program popularity
   - Dashboard statistik per program

---

## ✅ **Checklist Implementasi**

- [x] Form input program pendidikan di admin
- [x] Update controller untuk handle image
- [x] Visi & Misi input form
- [x] Hero section component
- [x] Program display component
- [x] Responsive design
- [x] Database integration
- [x] Fallback default data
- [ ] Include components di welcome.blade.php (TODO)
- [ ] Customize WhatsApp link (TODO)

---

## 📞 **Support & Customization**

### Customize WhatsApp Link
Di `program-pendidikan-section.blade.php`, ubah:
```blade
https://wa.me/628123456789?text=...
```

### Customize Default Programs
Di `WelcomeController.php`, ubah array `$programPendidikan`:
```php
'Program Baru' => [
    'icon' => 'fas fa-icon-name',
    'color' => 'bg-color-500',
    'description' => 'Deskripsi program',
    'usia' => 'Rentang usia',
    'duration' => 'Durasi',
    'features' => ['Feature 1', 'Feature 2']
]
```

---

**Dibuat: Desember 9, 2025**
**Status: ✅ Siap Implementasi**
