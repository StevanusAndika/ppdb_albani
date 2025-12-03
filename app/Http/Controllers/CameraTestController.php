<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CameraTestController extends Controller
{
    public function index()
    {
        // tampilan kamera
        return view('camera-test'); // sesuaikan path view
    }

    public function store(Request $request)
    {
        $url = $request->input('field_input'); // isi QR

        // ... simpan log / proses lain di sini ...

        return back()
            ->with('success', 'Data terkirim: '.$url)
            ->with('scanned_url', $url);
    }

}
