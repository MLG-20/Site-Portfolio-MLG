<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PersonalInfo;

return new class extends Migration
{
    public function up(): void
    {
        $info = PersonalInfo::latest()->first();

        if (!$info) return;

        // Force la remise en place des titres, quelle que soit la valeur actuelle en DB.
        // Nécessaire car la migration précédente (020000) a pu tourner avant que
        // les données soient corrompues, et ne re-tournera pas.
        $current = $info->getRawOriginal('titles');

        $needsUpdate = empty($current)
            || $current === 'Array'
            || $current === 'null'
            || $current === '[]'
            || !is_array(json_decode($current, true));

        if ($needsUpdate) {
            $info->titles = ['Développeur Full-Stack', 'Data Analyst', 'Expert Power BI'];
            $info->saveQuietly();
        }
    }

    public function down(): void {}
};
