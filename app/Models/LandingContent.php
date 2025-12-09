<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    protected $fillable = ['key', 'payload'];

    protected $casts = [
        'payload' => 'array', // Konversi otomatis JSON ke Array
    ];
}