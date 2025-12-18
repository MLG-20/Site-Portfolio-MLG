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
    Schema::create('messages', function (Blueprint $table) {
        $table->id(); // Crée l'ID auto-incrémenté
        $table->string('name');
        $table->string('email');
        $table->string('phone')->nullable(); // nullable = optionnel
        $table->string('subject');
        $table->text('message');
        $table->timestamps(); // Crée automatiquement created_at et updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
