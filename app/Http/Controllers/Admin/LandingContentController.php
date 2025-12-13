<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingContentController extends Controller
{
    public function index()
    {
        // Ambil semua data dan jadikan key sebagai index array agar mudah dipanggil di view
        // Contoh hasil: $contents['hero']['title']
        $contents = LandingContent::all()->pluck('payload', 'key');

        return view('dashboard.admin.landing.index', compact('contents'));
    }

    public function update(Request $request)
    {
        // 1. Update Hero Section
        $heroData = $request->input('hero');

        // Handle Upload Gambar Hero (Jika ada)
        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('hero', 'public');
            $heroData['image'] = 'storage/' . $path;
        } else {
            // Pertahankan gambar lama jika tidak ada upload baru
            $oldHero = LandingContent::where('key', 'hero')->first();
            $heroData['image'] = $oldHero->payload['image'] ?? null;
        }

        LandingContent::updateOrCreate(['key' => 'hero'], ['payload' => $heroData]);

        // 2. Update Visi Misi
        // Misi dikirim dalam bentuk array dari view
        $visiMisiData = [
            'visi' => $request->input('visi'),
            'misi' => array_filter($request->input('misi', [])), // Hapus input kosong
        ];
        LandingContent::updateOrCreate(['key' => 'visi_misi'], ['payload' => $visiMisiData]);

        // 3. Update Programs (JSON Complex dengan gambar)
        // Data program dikirim sebagai array of objects
        $programsInput = $request->input('programs', []);
        $oldPrograms = LandingContent::where('key', 'programs')->first();
        $oldProgramsData = $oldPrograms ? $oldPrograms->payload : [];

        $programsData = [];
        $fileIndex = 0;

        foreach($programsInput as $key => $p) {
            // Simpan atau update gambar program
            $imageUrl = null;

            // Cek apakah ada file yang diupload
            if ($request->hasFile("programs.$key.image")) {
                $path = $request->file("programs.$key.image")->store('programs', 'public');
                $imageUrl = 'storage/' . $path;
            } else {
                // Pertahankan gambar lama jika tidak ada upload baru
                if (isset($oldProgramsData[$fileIndex]['image'])) {
                    $imageUrl = $oldProgramsData[$fileIndex]['image'];
                }
            }

            $programsData[] = [
                'title' => $p['title'],
                'description' => $p['description'],
                // Explode string koma menjadi array
                'advantages' => isset($p['advantages']) ? array_map('trim', explode(',', $p['advantages'])) : [],
                'image' => $imageUrl,
            ];

            $fileIndex++;
        }

        LandingContent::updateOrCreate(['key' => 'programs'], ['payload' => $programsData]);

        // 4. Update Program Unggulan
        $programUnggulanInput = $request->input('program_unggulan', []);
        $programUnggulanData = [];
        foreach($programUnggulanInput as $p) {
            $programUnggulanData[] = [
                'nama' => $p['nama'] ?? ($p['title'] ?? null),
                'deskripsi' => $p['deskripsi'] ?? ($p['description'] ?? null),
                'target' => $p['target'] ?? null,
                'metode' => $p['metode'] ?? null,
                'evaluasi' => $p['evaluasi'] ?? null,
            ];
        }
        if(!empty($programUnggulanData)) {
            LandingContent::updateOrCreate(['key' => 'program_unggulan'], ['payload' => $programUnggulanData]);
        }

        // 5. Update Kegiatan Pesantren
        $kegiatanInput = $request->input('kegiatan', []);
        $kegiatanData = [];
        foreach($kegiatanInput as $item) {
            $kegiatanList = [];
            if (!empty($item['kegiatan'])) {
                // accept either comma separated string or array
                if (is_array($item['kegiatan'])) {
                    $kegiatanList = array_filter(array_map('trim', $item['kegiatan']));
                } else {
                    $kegiatanList = array_filter(array_map('trim', explode(',', $item['kegiatan'])));
                }
            }
            $kegiatanData[] = [
                'waktu' => $item['waktu'] ?? null,
                'kegiatan' => $kegiatanList,
            ];
        }
        if(!empty($kegiatanData)) {
            LandingContent::updateOrCreate(['key' => 'kegiatan_pesantren'], ['payload' => $kegiatanData]);
        }

        // 6. Update FAQ
        $faqInput = $request->input('faq', []);
        $faqData = [];
        foreach($faqInput as $f) {
            if (!empty($f['pertanyaan']) || !empty($f['jawaban'])) {
                $faqData[] = [
                    'pertanyaan' => $f['pertanyaan'] ?? null,
                    'jawaban' => $f['jawaban'] ?? null,
                ];
            }
        }
        if(!empty($faqData)) {
            LandingContent::updateOrCreate(['key' => 'faq'], ['payload' => $faqData]);
        }

        // 7. Update Alur Pendaftaran
        $alurInput = $request->input('alur', []);
        $alurData = [];
        foreach($alurInput as $a) {
            if (!empty($a['judul']) || !empty($a['deskripsi'])) {
                $alurData[] = [
                    'judul' => $a['judul'] ?? null,
                    'deskripsi' => $a['deskripsi'] ?? null,
                ];
            }
        }
        if(!empty($alurData)) {
            LandingContent::updateOrCreate(['key' => 'alur_pendaftaran'], ['payload' => $alurData]);
        }

        // 8. Update Biaya (simple text or structured if provided)
        $biayaText = $request->input('biaya_text');
        if (!is_null($biayaText)) {
            LandingContent::updateOrCreate(['key' => 'biaya'], ['payload' => ['text' => $biayaText]]);
        }

        // 9. Update Persyaratan Dokumen (with optional images)
        $persyaratanInput = $request->input('persyaratan', []);
        $oldPersyaratan = LandingContent::where('key', 'persyaratan_dokumen')->first();
        $oldPersyaratanData = $oldPersyaratan ? $oldPersyaratan->payload : [];
        $persyaratanData = [];
        foreach($persyaratanInput as $idx => $item) {
            $imgUrl = null;
            if ($request->hasFile("persyaratan.$idx.image")) {
                $path = $request->file("persyaratan.$idx.image")->store('persyaratan', 'public');
                $imgUrl = 'storage/' . $path;
            } else {
                // preserve old image if exists at same index
                if (isset($oldPersyaratanData[$idx]['img'])) {
                    $imgUrl = $oldPersyaratanData[$idx]['img'];
                }
            }
            $persyaratanData[] = [
                'title' => $item['title'] ?? null,
                'img' => $imgUrl,
                'note' => $item['note'] ?? null,
            ];
        }
        if(!empty($persyaratanData)) {
            LandingContent::updateOrCreate(['key' => 'persyaratan_dokumen'], ['payload' => $persyaratanData]);
        }

        // 10. Update Brosur
        $brosurData = [];
        if ($request->hasFile('brosur_file')) {
            $path = $request->file('brosur_file')->store('brosur', 'public');
            $brosurData['file'] = 'storage/' . $path;
            $brosurData['filename'] = $request->file('brosur_file')->getClientOriginalName();
        } else {
            // Pertahankan file lama jika tidak ada upload baru
            $oldBrosur = LandingContent::where('key', 'brosur')->first();
            if ($oldBrosur && isset($oldBrosur->payload['file'])) {
                $brosurData['file'] = $oldBrosur->payload['file'];
                $brosurData['filename'] = $oldBrosur->payload['filename'] ?? 'brosur.pdf';
            }
        }
        if (!empty($brosurData)) {
            LandingContent::updateOrCreate(['key' => 'brosur'], ['payload' => $brosurData]);
        }

        // 11. Update General Settings
        $generalInput = $request->input('general', []);
        if (!empty($generalInput)) {
            // Process navbar menu (comma separated to array)
            if (isset($generalInput['navbar_menu']) && is_string($generalInput['navbar_menu'])) {
                $generalInput['navbar_menu'] = array_map('trim', explode(',', $generalInput['navbar_menu']));
            }
            
            // Process footer links (comma separated to array)
            foreach (['footer_menu1_links', 'footer_menu2_links', 'footer_menu3_links', 'footer_menu4_links'] as $key) {
                if (isset($generalInput[$key]) && is_string($generalInput[$key])) {
                    $generalInput[$key] = array_map('trim', explode(',', $generalInput[$key]));
                }
            }
            
            LandingContent::updateOrCreate(['key' => 'general'], ['payload' => $generalInput]);
        }

        return redirect()->back()->with('success', 'Konten berhasil diperbarui!');
    }
}
