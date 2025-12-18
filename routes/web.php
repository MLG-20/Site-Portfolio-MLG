<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

// Si ce fichier est lu, Laravel DOIT passer par le contrôleur
Route::get('/', [PortfolioController::class, 'index'])->name('home');

Route::post('/contact', [PortfolioController::class, 'storeMessage'])->name('contact.store');

