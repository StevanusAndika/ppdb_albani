<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\ContentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil paket yang aktif beserta biaya yang aktif
        $packages = Package::with(['prices' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
        ->where('is_active', true)
        ->get();

        // Ambil pengaturan konten
        $contentSettings = ContentSetting::getSettings();

        // Cek apakah user sudah login
        $user = Auth::user();
        $isLoggedIn = Auth::check();
        $userRole = $isLoggedIn ? $user->role : null;

        return view('welcome', compact('packages', 'contentSettings', 'isLoggedIn', 'userRole'));
    }
}
