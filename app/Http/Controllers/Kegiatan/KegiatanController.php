<?php

namespace App\Http\Controllers\Kegiatan;

use App\Http\Controllers\Controller;
use App\Models\ContentSetting;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Display kegiatan pesantren page for calon santri
     */
    public function index()
    {
        $settings = ContentSetting::getSettings();
        $kegiatan = $settings->kegiatan_pesantren ?? [];

        return view('dashboard.calon_santri.kegiatan.index', compact('kegiatan'));
    }
}
