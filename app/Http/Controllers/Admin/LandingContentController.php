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
            $path = $request->file('hero_image')->store('public/hero');
            $heroData['image'] = str_replace('public/', 'storage/', $path);
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
                $path = $request->file("programs.$key.image")->store('public/programs');
                $imageUrl = str_replace('public/', 'storage/', $path);
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

        return redirect()->back()->with('success', 'Konten berhasil diperbarui!');
    }
}
