# 🔌 PANDUAN INTEGRASI - Program Pendidikan Components

Dokumen ini menjelaskan cara mengintegrasikan components program pendidikan ke dalam halaman utama (welcome.blade.php)

---

## 📍 STEP 1: Identifikasi Lokasi Integrasi

### Current Structure di welcome.blade.php

Halaman welcome.blade.php saat ini memiliki section:
1. Navbar (Navigation)
2. Hero Section (Landing)
3. Visi & Misi Section (Lines 300+)
4. Program Pendidikan Section (Lines 343-450)
5. Program Unggulan Section
6. FAQ Section
7. Footer

---

## 🔄 STEP 2: Ganti Section yang Ada

### OPTION A: Replace Existing Sections (Recommended)

#### A1. Ganti Visi & Misi Section
**Lokasi**: Cari di welcome.blade.php bagian dengan ID `visi-misi`

**Sebelum**:
```blade
<!-- Visi Misi Section (Original) -->
<section id="visi-misi">
    ... (existing code)
</section>
```

**Sesudah**:
```blade
<!-- Visi Misi Section (NEW) -->
@include('layouts.components.visi-misi-section')
```

---

#### A2. Ganti Program Pendidikan Section
**Lokasi**: Cari di welcome.blade.php bagian dengan ID `program-pendidikan` (Lines 343-450)

**Sebelum**:
```blade
<!-- Program Pendidikan Section -->
<section id="program-pendidikan" class="py-16 px-4 bg-gradient-to-b from-white to-blue-50">
    <div class="container mx-auto">
        <!-- ... existing program cards code ... -->
    </div>
</section>
```

**Sesudah**:
```blade
<!-- Program Pendidikan Section (NEW) -->
@include('layouts.components.program-pendidikan-section')
```

---

### OPTION B: Tambah Hero Section Baru (Optional)

Jika ingin menambahkan hero section khusus program di awal halaman:

**Lokasi**: Sesudah navbar, sebelum section pertama

```blade
<!-- Hero Program Section (OPTIONAL) -->
@include('layouts.components.hero-program-section')

<!-- Existing Sections -->
<section id="visi-misi">
    ...
</section>
```

---

## 🎯 STEP 3: Perbarui Navigation Links

Jika menggunakan hero section baru, update navbar links ke:

```blade
<!-- Desktop menu -->
<a href="#hero-program" class="text-primary hover:text-secondary font-medium transition duration-300">Program Pendidikan</a>
<a href="#visi-misi" class="text-primary hover:text-secondary font-medium transition duration-300">Visi & Misi</a>
```

---

## ✅ STEP 4: Verify Data Flow

### Check WelcomeController
Pastikan controller sudah update dengan:

```php
// Ambil visi misi dari database
$landingContent = LandingContent::all()->pluck('payload', 'key');
$visiMisi = $landingContent['visi_misi'] ?? [];

// Ambil program pendidikan dari database
$dbPrograms = $landingContent['programs'] ?? [];

// Return ke view dengan compact:
return view('welcome', compact(
    'packages',
    'contentSettings',
    'isLoggedIn',
    'userRole',
    'educationStats',
    'programPendidikan',
    'visiMisi',      // ← NEW
    'programs'       // ← NEW
));
```

---

## 🔐 STEP 5: Setup Storage Link

Untuk gambar program bisa diakses publik:

```bash
# Terminal/PowerShell
php artisan storage:link
```

Output diharapkan:
```
The [public/storage] directory has been successfully linked to [storage/app/public].
```

---

## 📝 STEP 6: Update Admin Form (Optional)

Jika belum, pastikan admin form sudah punya Tab "Visi & Misi" dan "Program Pendidikan"

### Cek File
`resources/views/dashboard/admin/landing/index.blade.php`

Sudah ada tabs:
- ✅ Hero Section
- ✅ Visi & Misi
- ✅ Program Pendidikan

---

## 🧪 STEP 7: Testing

### Test di Admin Dashboard
1. Buka: `http://localhost:8000/admin/content`
2. Pergi ke tab "Visi & Misi"
3. Input visi dan misi
4. Pergi ke tab "Program Pendidikan"
5. Tambah program dengan keterangan dan keunggulan
6. Upload gambar (opsional)
7. Klik "Simpan Perubahan"

### Test di Frontend
1. Buka halaman utama: `http://localhost:8000/`
2. Scroll ke section Visi & Misi
3. Verifikasi visi dan misi tampil dari database
4. Scroll ke section Program Pendidikan
5. Verifikasi program tampil dengan:
   - Judul program
   - Deskripsi
   - Keunggulan (dari database)
   - Gambar (jika ada)

---

## 🎨 STEP 8: Customization (Optional)

### A. Ubah Warna Primary
File: `resources/views/layouts/components/visi-misi-section.blade.php`

Cari dan ganti:
```blade
<!-- FROM -->
<div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-primary to-teal-600 ...">

<!-- TO (Custom color) -->
<div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-700 ...">
```

### B. Ubah WhatsApp Link
File: `resources/views/layouts/components/program-pendidikan-section.blade.php`

Cari:
```blade
https://wa.me/628123456789?text=Saya%20ingin%20bertanya%20tentang%20program%20pendidikan
```

Ganti dengan nomor WhatsApp Anda:
```blade
https://wa.me/62YOUR_PHONE_NUMBER?text=Saya%20ingin%20bertanya%20tentang%20program%20pendidikan
```

### C. Customize Register Route
Jika route register berbeda, update:

File: `resources/views/layouts/components/program-pendidikan-section.blade.php`

```blade
<!-- FROM -->
href="{{ route('register') }}"

<!-- TO -->
href="{{ route('your_register_route') }}"
```

---

## 📋 INTEGRATION CHECKLIST

### Pre-Integration
- [ ] Download/buat 3 component files
- [ ] Update WelcomeController dengan data LandingContent
- [ ] Pastikan database sudah running
- [ ] Run `php artisan storage:link`

### Integration
- [ ] Include visi-misi-section.blade.php di welcome
- [ ] Include program-pendidikan-section.blade.php di welcome
- [ ] (Optional) Include hero-program-section.blade.php
- [ ] Update navbar links jika diperlukan
- [ ] Test data flow dari controller

### Admin Setup
- [ ] Buka admin panel
- [ ] Input visi dan misi
- [ ] Tambah minimal 1 program dengan keunggulan
- [ ] Upload gambar untuk program (optional)
- [ ] Simpan perubahan

### Frontend Testing
- [ ] Verifikasi visi & misi tampil
- [ ] Verifikasi program tampil
- [ ] Verifikasi gambar program tampil
- [ ] Test responsive design (mobile, tablet, desktop)
- [ ] Test CTA buttons berfungsi
- [ ] Test link WhatsApp berfungsi

### Performance Check
- [ ] Cek loading time
- [ ] Verifikasi no console errors
- [ ] Test di berbagai browser

---

## 🚀 DEPLOY TO PRODUCTION

### Pre-Deploy
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimize
php artisan optimize
```

### Deploy
```bash
# Push ke production server
git add .
git commit -m "Add program pendidikan with hero and visi-misi sections"
git push origin main
```

### Post-Deploy
```bash
# SSH ke production server
php artisan storage:link
php artisan migrate (jika ada migration baru)
```

---

## 🔧 TROUBLESHOOTING

### Issue 1: Visi & Misi Tidak Muncul
**Solusi**:
1. Cek di admin: apakah sudah input visi & misi?
2. Cek database: `LandingContent` table ada data dengan key `visi_misi`?
3. Verifikasi: `WelcomeController` sudah ambil `$landingContent`?

### Issue 2: Program Tidak Muncul
**Solusi**:
1. Cek di admin: apakah sudah add program?
2. Cek database: ada data dengan key `programs`?
3. Verifikasi: component sudah diinclude di welcome?

### Issue 3: Gambar Program Tidak Muncul
**Solusi**:
1. Verifikasi: `php artisan storage:link` sudah jalan?
2. Cek folder: `storage/app/public/programs/` ada gambar?
3. Cek access: `public/storage/` bisa diakses browser?

### Issue 4: Style/CSS Tidak Bekerja
**Solusi**:
1. Verifikasi: Tailwind CSS sudah include di welcome.blade.php?
2. Compile Tailwind: `npm run dev` atau `npm run build`
3. Bersihkan cache browser: Hard refresh (Ctrl+Shift+Del)

### Issue 5: Form Submit Error
**Solusi**:
1. Verifikasi: Route `admin.content.update` ada?
2. Cek error log: `storage/logs/laravel.log`
3. Verifikasi: LandingContentController::update() sudah update?

---

## 📞 QUICK REFERENCE

### Files Created/Updated
```
✅ resources/views/layouts/components/hero-program-section.blade.php    [NEW]
✅ resources/views/layouts/components/visi-misi-section.blade.php       [NEW]
✅ resources/views/layouts/components/program-pendidikan-section.blade.php [NEW]
✅ resources/views/dashboard/admin/landing/index.blade.php             [UPDATED]
✅ app/Http/Controllers/Admin/LandingContentController.php             [UPDATED]
✅ app/Http/Controllers/WelcomeController.php                          [UPDATED]
```

### Routes Used
```
GET  /                                    → WelcomeController@index
POST /admin/content/update                → LandingContentController@update
GET  /admin/content                       → LandingContentController@index
```

### Database Tables
```
landing_contents (key, payload)
  - key: 'hero'
  - key: 'visi_misi'
  - key: 'programs'
```

---

**Last Updated**: Desember 9, 2025
**Status**: Ready for Integration ✅
