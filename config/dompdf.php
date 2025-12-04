<?php

return [

    'show_warnings' => false,
    'public_path' => null,
    'convert_entities' => true,

    'options' => [
        // Font configuration - FIXED
        'font_dir' => storage_path('fonts/'),  // Tambahkan slash di akhir
        'font_cache' => storage_path('fonts/'),

        // Temporary directory
        'temp_dir' => sys_get_temp_dir(),

        // Security
        'chroot' => realpath(base_path()),

        // Protocols
        'allowed_protocols' => [
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        'artifactPathValidation' => null,
        'log_output_file' => null,

        // Font subsetting - ENABLE THIS
        'enable_font_subsetting' => true,

        // PDF backend
        'pdf_backend' => 'CPDF',

        // Media and paper
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',

        // DEFAULT FONT - CHANGE TO AVAILABLE FONT
        'default_font' => 'dejavu sans',  // Ganti dari 'serif'

        // DPI
        'dpi' => 96,

        // Security settings
        'enable_php' => false,
        'enable_javascript' => true,

        // ENABLE REMOTE FOR FONTS - IMPORTANT!
        'enable_remote' => true,  // Ganti dari false ke true

        'allowed_remote_hosts' => null,
        'font_height_ratio' => 1.1,

        // HTML5 parser
        'enable_html5_parser' => true,
    ],

];
