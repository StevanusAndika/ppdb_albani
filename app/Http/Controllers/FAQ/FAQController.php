<?php

namespace App\Http\Controllers\FAQ;

use App\Http\Controllers\Controller;
use App\Models\ContentSetting;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display FAQ page for calon santri
     */
    public function index()
    {
        $settings = ContentSetting::getSettings();
        $faqs = $settings->faq ?? [];

        return view('dashboard.calon_santri.faq.index', compact('faqs'));
    }
}
