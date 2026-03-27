<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PersonalInfo;

return new class extends Migration
{
    public function up(): void
    {
        $info = PersonalInfo::latest()->first();

        if (!$info) return;

        $titles = $info->titles;

        if (empty($titles)) {
            $info->titles = ['Développeur Full-Stack', 'Data Analyst', 'Expert Power BI'];
            $info->saveQuietly();
        }
    }

    public function down(): void {}
};
