<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalInfo extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'titles'    => 'array',
        'skills'    => 'array',
        'languages' => 'array',
    ];
}
