# 📚 PANDUAN IMPLEMENTASI - SISTEM DOKUMEN DINAMIS FLEKSIBEL

Dokumentasi lengkap perubahan sistem untuk mendukung dokumen persyaratan yang fleksibel berdasarkan program pendidikan dan program unggulan.

---

## 🎯 Ringkasan Perubahan

Sistem telah diubah dari dokumentasi **statis 4 dokumen** menjadi **fleksibel & dinamis** dengan fitur:

1. **Dokumen per Program Pendidikan** - Setiap package dapat menentukan dokumen yang diperlukan
2. **Dokumen Tambahan per Program Unggulan** - Program unggulan dapat menambahkan dokumen spesifik
3. **Tabel Terpisah untuk Dokumen** - `registration_documents` table untuk scalability
4. **Admin Dashboard** - Kelola program unggulan dengan mudah
5. **Dynamic UI** - Form dokumen otomatis menyesuaikan dengan requirements

---

## 📁 Struktur File Baru & Perubahan

### Database & Migrations

**Diperbaharui:**
- [database/migrations/2025_11_04_035418_packages.php](database/migrations/2025_11_04_035418_packages.php)
  - ✅ Tambah kolom `required_documents` (JSON)

- [database/migrations/2025_11_05_053926_registrations.php](database/migrations/2025_11_05_053926_registrations.php)
  - ✅ Tambah FK `program_unggulan_id`
  - ✅ Buat tabel `programs_unggulan` dengan kolom:
    - `id` (PK)
    - `nama_program` (string, unique)
    - `potongan` (decimal, 0-100)
    - `perlu_verifikasi` (enum: yes/no)
    - `dokumen_tambahan` (JSON array)
    - timestamps

### Models

**Baru:**
- [app/Models/ProgramUnggulan.php](app/Models/ProgramUnggulan.php)
  - Relasi `hasMany` dengan `Registration`
  - Method `getAllRequiredDocuments($package)` untuk menggabung dokumen

- [app/Models/RegistrationDocument.php](app/Models/RegistrationDocument.php)
  - Menyimpan dokumen individual per tipe
  - Scopes: `byRegistration()`, `byDocumentType()`
  - Methods: `fileExists()`, `deleteFile()`, `getFileUrlAttribute()`

**Diperbaharui:**
- [app/Models/Package.php](app/Models/Package.php)
  - Tambah `required_documents` ke `$fillable` dan `$casts`

- [app/Models/Registration.php](app/Models/Registration.php)
  - Tambah relasi `programUnggulan()` dan `documents()`

### Services

**Baru:**
- [app/Services/DocumentRequirementService.php](app/Services/DocumentRequirementService.php)
  - Logika untuk mengambil dokumen requirement dinamis
  - Methods utama:
    - `getRequiredDocuments($registration)` - gabungkan dari package + program
    - `areAllDocumentsComplete($registration)` - cek kelengkapan
    - `getMissingDocuments($registration)` - dokumen yang belum diunggah
    - `saveDocument($idPendaftaran, $tipeDocumen, $filePath)` - simpan ke DB
    - `deleteDocument($idPendaftaran, $tipeDocumen)` - hapus dari DB

### Controllers

**Baru:**
- [app/Http/Controllers/Admin/ProgramUnggulanController.php](app/Http/Controllers/Admin/ProgramUnggulanController.php)
  - CRUD untuk Program Unggulan
  - Methods: `index()`, `create()`, `store()`, `edit()`, `update()`, `destroy()`
  - `getJson()` untuk AJAX API

**Traits (Baru):**
- [app/Http/Controllers/Traits/DocumentUploadTrait.php](app/Http/Controllers/Traits/DocumentUploadTrait.php)
  - Helper methods untuk DocumentController
  - Integrasi dengan DocumentRequirementService

**Diperbaharui:**
- [app/Http/Controllers/Document/DocumentController.php](app/Http/Controllers/Document/DocumentController.php)
  - Update `index()` - tampilkan dokumen dinamis
  - Update `upload()` - simpan ke `registration_documents`
  - Update `checkAllDocumentsCompleteApi()` - hitung dynamis
  - Update `delete()` - hapus dari tabel terpisah
  - Update `getFile()`, `download()` - dari tabel baru
  - Update `completeRegistration()` - ubah status
  - Update `getProgress()` - hitung dynamis
  - Update `downloadAll()` - download semua dokumen
  - Gunakan trait `DocumentUploadTrait`

- [app/Http/Controllers/Biodata/BiodataController.php](app/Http/Controllers/Biodata/BiodataController.php)
  - Injeksi `DocumentRequirementService`
  - Update `getPackagePrices()` - return `required_documents_labels`
  - Tambah method `getDocumentLabel()`

### Views

**Baru:**
- [resources/views/dashboard/admin/program-unggulan/index.blade.php](resources/views/dashboard/admin/program-unggulan/index.blade.php)
  - Daftar program unggulan dengan pagination
  - Tampilkan diskon, status verifikasi, dokumen tambahan
  - Tombol edit/delete dengan konfirmasi

- [resources/views/dashboard/admin/program-unggulan/create.blade.php](resources/views/dashboard/admin/program-unggulan/create.blade.php)
  - Form tambah program unggulan
  - Input dinamis untuk dokumen tambahan
  - Validasi real-time

- [resources/views/dashboard/admin/program-unggulan/edit.blade.php](resources/views/dashboard/admin/program-unggulan/edit.blade.php)
  - Form edit program unggulan
  - Pre-load data existing
  - Manipulasi dokumen tambahan

- [resources/views/dashboard/calon_santri/dokumen/dokumen-new.blade.php](resources/views/dashboard/calon_santri/dokumen/dokumen-new.blade.php)
  - Upload dokumen dengan drag & drop
  - Tampilkan dokumen yang diperlukan (dinamis dari DB)
  - Progress bar real-time
  - Download individual atau semua dokumen

### Routes

**Diperbaharui:**
- [routes/web.php](routes/web.php)
  - Import `ProgramUnggulanController`
  - Tambah route group untuk program-unggulan CRUD

### Seeders

**Baru:**
- [database/seeders/ProgramUnggulanSeeder.php](database/seeders/ProgramUnggulanSeeder.php)
  - Seed data default:
    - Program Regular (0% diskon, no verifikasi)
    - Program Tahfidz (10% diskon, + sertifikat_hafiz, surat_rekomendasi)
    - Program Intensif (5% diskon, + SKU)
    - Program Beasiswa (50% diskon, + dokumen_kesehatan, surat_rekomendasi)

**Diperbaharui:**
- [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)
  - Tambah call ke `ProgramUnggulanSeeder::class`

---

## 🚀 Langkah-Langkah Setup & Implementasi

### 1. Persiapan Database

```bash
# Backup database jika sudah ada data
php artisan migrate:rollback  # (optional, jika perlu clear)

# Jalankan migration
php artisan migrate:fresh

# Jalankan seed untuk default programs
php artisan db:seed --class=ProgramUnggulanSeeder
```

### 2. Cek Models & Services

Pastikan semua model dan service sudah tersedia:
```bash
# Check models
ls app/Models/ProgramUnggulan.php
ls app/Models/RegistrationDocument.php

# Check services
ls app/Services/DocumentRequirementService.php

# Check controllers
ls app/Http/Controllers/Admin/ProgramUnggulanController.php
```

### 3. Testing Routes

Akses admin dashboard:
```
http://localhost/admin/program-unggulan
http://localhost/admin/program-unggulan/create
```

### 4. Testing Upload Dokumen

Calon santri upload dokumen:
```
http://localhost/santri/dokumen (gunakan view dokumen-new.blade.php)
```

---

## 📋 Daftar Dokumen Standar

Sistem mendukung dokumen berikut (dapat diperluas):

| Type | Label | Catatan |
|------|-------|---------|
| `kartu_keluarga` | Kartu Keluarga | Dokumentasi standar |
| `ijazah` | Ijazah | Dokumentasi standar |
| `akta_kelahiran` | Akta Kelahiran | Dokumentasi standar |
| `pas_foto` | Pas Foto | Dokumentasi standar |
| `sku` | SKU (Surat Keterangan Ujian) | Untuk program spesifik |
| `sertifikat_hafiz` | Sertifikat Hafiz | Untuk program tahfidz |
| `surat_rekomendasi` | Surat Rekomendasi | Untuk program khusus |
| `dokumen_kesehatan` | Dokumen Kesehatan | Untuk program beasiswa |

### Menambah Dokumen Baru

1. **Di Admin**: Form otomatis di create/edit program-unggulan
2. **Di Service**: Update `getDocumentLabel()` di `DocumentRequirementService.php`
3. **Di View**: Tambah label di view dokumen-new.blade.php

---

## 🔧 Konfigurasi

### File Konfigurasi yang Perlu Dicek

1. **config/filesystems.php**
   - Pastikan disk `public` sudah dikonfigurasi
   - Storage path untuk dokumen: `storage/app/public/documents`

2. **config/app.php**
   - Timezone dan locale sesuai kebutuhan

### Update .env (jika ada)

```env
APP_URL=http://ppdb.local
FILESYSTEM_DISK=public
```

---

## 📊 Diagram Relasi Database

```
┌─────────────────────┐
│      packages       │
├─────────────────────┤
│ id (PK)             │
│ name                │
│ required_documents  │ ← JSON: ['kartu_keluarga', 'ijazah', ...]
│ is_active           │
└─────────────────────┘
         ↓ (1:N)
┌─────────────────────┐
│   registrations     │
├─────────────────────┤
│ id (PK)             │
│ id_pendaftaran (UK) │
│ user_id (FK)        │
│ package_id (FK)     │
│ program_unggulan_id │ ← FK baru
│ ... biodata ...     │
└─────────────────────┘
         ↓ (1:N)
┌────────────────────────────────┐
│  registration_documents        │
├────────────────────────────────┤
│ id (PK)                        │
│ id_pendaftaran (FK)            │
│ tipe_dokumen (string)          │
│ file_path (string)             │
│ created_at, updated_at         │
└────────────────────────────────┘

┌────────────────────────────────┐
│   programs_unggulan            │
├────────────────────────────────┤
│ id (PK)                        │
│ nama_program (string, unique)  │
│ potongan (decimal 0-100)       │
│ perlu_verifikasi (yes/no)      │
│ dokumen_tambahan (JSON array)  │
│ created_at, updated_at         │
└────────────────────────────────┘
         ↑ (1:N)
    program_unggulan_id di registrations
```

---

## 🧪 Testing Checklist

- [ ] Migration berhasil tanpa error
- [ ] Seeder berjalan: `php artisan db:seed --class=ProgramUnggulanSeeder`
- [ ] Admin dapat CRUD program unggulan
- [ ] Calon santri dapat upload dokumen
- [ ] Progress bar menghitung jumlah dokumen dinamis
- [ ] Download dokumen individual berfungsi
- [ ] Download semua dokumen (ZIP) berfungsi
- [ ] Delete dokumen bekerja
- [ ] Status pendaftaran berubah ke "telah_mengisi" saat semua dokumen lengkap
- [ ] API endpoint `/santri/documents/check-all` mengembalikan count yang benar

---

## 🐛 Troubleshooting

### Error: "Target class does not exist"
```bash
composer dump-autoload
php artisan cache:clear
```

### Error: "Table does not exist"
```bash
php artisan migrate:fresh
php artisan db:seed
```

### File tidak ter-upload
- Cek permissions folder `storage/app/public`
- Jalankan: `php artisan storage:link`

### Progress tidak update
- Cek browser console untuk error JavaScript
- Pastikan route `/santri/documents/check-all` accessible

---

## 📞 API Endpoints

### Admin
```
GET   /admin/program-unggulan              → List programs
GET   /admin/program-unggulan/create       → Create form
POST  /admin/program-unggulan              → Store program
GET   /admin/program-unggulan/{id}/edit    → Edit form
PUT   /admin/program-unggulan/{id}         → Update program
DELETE /admin/program-unggulan/{id}        → Delete program
GET   /admin/program-unggulan/json         → Get JSON for AJAX
```

### Calon Santri
```
GET    /santri/dokumen                     → Upload form
POST   /santri/documents/upload/{type}     → Upload dokumen
DELETE /santri/documents/{type}            → Delete dokumen
GET    /santri/documents/download/{type}   → Download dokumen
GET    /santri/documents/download-all      → Download semua (ZIP)
POST   /santri/documents/complete          → Complete registration
GET    /santri/documents/check-all         → Check progress
GET    /santri/documents/get-progress      → Get progress %
```

---

## 💡 Tips & Best Practices

1. **Backup Data**: Sebelum `migrate:fresh`, backup database existing
2. **Test Upload**: Gunakan file kecil (< 5MB) untuk test awal
3. **Monitor Logs**: Cek `storage/logs/laravel.log` untuk error
4. **Cache Clear**: Setelah deploy, jalankan `php artisan cache:clear`
5. **View File Path**: Gunakan dokumentasi standard dokumentasi Laravel Storage

---

## 📝 Dokumentasi Tambahan

Untuk informasi lebih detail:
- Laravel Documentation: https://laravel.com/docs
- Laravel Storage: https://laravel.com/docs/filesystem
- Query Builder: https://laravel.com/docs/queries

---

**Last Updated**: December 12, 2025
**Version**: 1.0
**Status**: ✅ Implementation Complete
