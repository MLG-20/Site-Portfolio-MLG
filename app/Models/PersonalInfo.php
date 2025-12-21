<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $guarded = [];

    // SUPPRIMEZ ou COMMENTEZ le cast qui ne fonctionne pas
    // protected $casts = [
    //     'titles' => 'array',
    // ];

    // CORRECTION : Mutator qui gère les chaînes séparées par des virgules
    public function setTitlesAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['titles'] = json_encode([]);
        } elseif (is_array($value)) {
            // Si c'est déjà un tableau (venant de Filament TagsInput)
            $this->attributes['titles'] = json_encode(array_values($value));
        } elseif (is_string($value)) {
            // Vérifier si c'est déjà du JSON
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // C'est déjà du JSON valide
                $this->attributes['titles'] = $value;
            } else {
                // C'est une chaîne séparée par des virgules
                $titlesArray = array_map('trim', explode(',', $value));
                $titlesArray = array_filter($titlesArray); // Enlève les vides
                $this->attributes['titles'] = json_encode(array_values($titlesArray));
            }
        } else {
            $this->attributes['titles'] = json_encode([]);
        }
    }

    // CORRECTION : Accessor qui décode correctement
    public function getTitlesAttribute($value)
    {
        if (empty($value) || $value === 'null' || $value === '""') {
            return [];
        }
        
        // Vérifier si c'est du JSON
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // C'est du JSON valide
                return $decoded;
            }
            
            // Sinon, c'est probablement une chaîne séparée par des virgules
            $titlesArray = array_map('trim', explode(',', $value));
            return array_filter($titlesArray); // Enlève les vides
        }
        
        return (array)$value;
    }
}