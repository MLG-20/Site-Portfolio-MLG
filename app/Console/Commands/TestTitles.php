<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PersonalInfo;

class TestTitles extends Command
{
    // Dans app/Console/Commands/TestTitles.php
    protected $hidden = true; // Masque la commande de la liste
    protected $signature = 'test:titles';
    protected $description = 'Teste le fonctionnement des titres';

    public function handle()
    {
        $info = PersonalInfo::first();
        
        if (!$info) {
            $this->error('❌ Aucune information personnelle trouvée !');
            $this->line('Vous devriez en créer une via Filament ou en base de données.');
            return 1;
        }
        
        $this->info("=== 🔍 Test des titres ===");
        $this->line("Nom: {$info->name}");
        
        // Récupère la valeur brute de la base de données
        $rawTitles = $info->getRawOriginal('titles');
        $this->line("Titres (raw DB): " . ($rawTitles ?: '(vide)'));
        
        // Récupère via l'accessor (getTitlesAttribute)
        $processedTitles = $info->titles;
        $this->line("Titres (via getter): " . json_encode($processedTitles));
        $this->line("Type: " . gettype($processedTitles));
        $this->line("Nombre: " . (is_array($processedTitles) ? count($processedTitles) : 'N/A'));
        
        $this->newLine();
        $this->info("📝 Test de modification...");
        
        // Test modification
        $testTitles = ['Développeur PHP', 'Analyste BI', 'Consultant IT'];
        $info->titles = $testTitles;
        $info->save();
        
        // Recharger depuis la base
        $info->refresh();
        
        $this->line("✅ Après modification:");
        $this->line("Titres: " . json_encode($info->titles));
        $this->line("Raw DB: " . $info->getRawOriginal('titles'));
        
        $this->newLine();
        $this->info("🎯 Test Typed.js:");
        $this->line("Pour utiliser dans la vue:");
        $this->line('const titles = @json($personalInfo->titles);');
        $this->line("// Résultat: " . json_encode($info->titles));
        
        return 0;
    }
}