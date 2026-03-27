<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonalInfo extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'skills'    => 'array',
        'languages' => 'array',
    ];

    protected function titles(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_array($value)) return $value;
                if (empty($value)) return [];
                $decoded = json_decode($value, true);
                if (is_array($decoded)) return $decoded;
                // Double-encoded: decode once more
                $decoded2 = json_decode($decoded, true);
                return is_array($decoded2) ? $decoded2 : [];
            },
            set: fn($value) => is_array($value) ? json_encode($value) : $value,
        );
    }
}
