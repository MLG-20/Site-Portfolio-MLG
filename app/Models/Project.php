<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    // C'EST ICI LA SOLUTION MAGIQUE :
    // On dit à Laravel : "Transforme automatiquement la colonne 'tags' en tableau PHP"
    protected $casts = [
        'tags'           => 'array',
        'views_count'    => 'integer',
        'gallery_images' => 'array',
    ];
}