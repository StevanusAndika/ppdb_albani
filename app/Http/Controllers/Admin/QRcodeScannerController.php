<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QRcodeScannerController extends Controller
{
    public function index()
    {
        // tampilan kamera
        return view('dashboard.admin.qrcode-scanner.index'); // sesuaikan path view
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
