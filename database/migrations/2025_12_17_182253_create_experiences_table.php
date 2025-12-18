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
    Schema::create('experiences', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Ex: Data Analyst
        $table->string('company')->nullable();
        $table->string('duration'); // Ex: 2022 - 2024
        $table->text('description');
        $table->string('icon')->default('fa-solid fa-briefcase');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
