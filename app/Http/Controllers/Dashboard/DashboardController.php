<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('santri.dashboard');
        }
    }

    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    /**
     * Santri Dashboard
     */
    public function santriDashboard()
    {
        return view('dashboard.santri');
    }
}
