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
        if ($user->isAdmin()) {
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
        $user = Auth::user();
        return view('dashboard.admin.index', compact('user')); // Sesuaikan path view
    }

    /**
     * Santri Dashboard
     */
    public function santriDashboard()
    {
        $user = Auth::user();
        return view('dashboard.calon_santri.index', compact('user')); // Sesuaikan path view
    }
}
