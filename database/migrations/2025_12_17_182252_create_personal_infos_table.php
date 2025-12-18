<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('personal_infos', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->json('titles'); // Stockera ["Data Analyst", "Dev PHP"]
        $table->string('email');
        $table->string('phone');
        $table->string('linkedin')->nullable();
        $table->string('github')->nullable();
        $table->string('cv_path')->nullable();
        $table->string('profile_image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
