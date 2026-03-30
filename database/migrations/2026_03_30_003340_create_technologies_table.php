<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('name');                              // ex: "Laravel"
            $table->string('devicon_slug');                      // ex: "laravel" → <i class="devicon-laravel-plain colored">
            $table->string('category');                          // "Frontend", "Backend", "Outils"
            $table->unsignedInteger('order')->default(0);        // ordre d'affichage
            $table->boolean('is_active')->default(true);         // afficher ou masquer sans supprimer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technologies');
    }
};
