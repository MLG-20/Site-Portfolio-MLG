<?php

namespace App\Models;

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

    /**
     * Titles : cast manuel robuste pour gérer tous les états de la DB
     * (correctement encodé, double-encodé, null, string brute).
     * Le cast natif 'array' seul ne suffit pas car la DB peut contenir
     * du JSON double-encodé issu des versions précédentes.
     */
    public function getTitlesAttribute(mixed $value): array
    {
        if (is_array($value)) return $value;
        if (empty($value))    return [];

        $decoded = json_decode($value, true);
        if (is_array($decoded)) return $decoded;

        // Double-encodé : decoder une deuxième fois
        if (is_string($decoded)) {
            $decoded2 = json_decode($decoded, true);
            if (is_array($decoded2)) return $decoded2;
        }

        return [];
    }

    public function setTitlesAttribute(mixed $value): void
    {
        $this->attributes['titles'] = is_array($value)
            ? json_encode($value, JSON_UNESCAPED_UNICODE)
            : $value;
    }
}
