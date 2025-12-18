<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Liste des champs qu'on a le droit de remplir via un formulaire
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message'
    ];
}