<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PersonalInfo;

return new class extends Migration
{
    public function up()
    {
        $infos = PersonalInfo::all();
        
        foreach ($infos as $info) {
            $rawTitles = $info->getRawOriginal('titles');
            
            if (!empty($rawTitles) && !$this->isValidJson($rawTitles)) {
                // Convertir la chaîne en tableau
                $titlesArray = array_map('trim', explode(',', $rawTitles));
                $titlesArray = array_filter($titlesArray);
                
                // Mettre à jour avec du JSON
                $info->titles = $titlesArray;
                $info->saveQuietly(); // saveQuietly pour éviter les observers
                
                echo "Corrigé pour: " . $info->name . "\n";
            }
        }
    }
    
    private function isValidJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
};