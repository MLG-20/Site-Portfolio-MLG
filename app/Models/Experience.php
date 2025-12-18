<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    // Cette ligne dit à Laravel : "Laisse passer tous les champs, je sais ce que je fais"
    protected $guarded = [];
}