<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect berdasarkan rolex
        if ($user->role === 'admin') {
            return view('dashboard.admin');
        } else {
            return view('dashboard.santri');
        }
    }
}
