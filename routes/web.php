<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\MessageNotificationController;

// Si ce fichier est lu, Laravel DOIT passer par le contrôleur
Route::get('/', [PortfolioController::class, 'index'])->name('home');

// API pour les notifications PWA
Route::get('/api/check-messages', [MessageNotificationController::class, 'checkNewMessages'])->name('api.check-messages');

Route::post('/contact', [PortfolioController::class, 'storeMessage'])
    ->name('contact.store')
    ->middleware('throttle:3,10'); // max 3 envois par 10 minutes par IP

Route::get('/projets/{project}', [PortfolioController::class, 'showProject'])->name('projects.show');

Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (! file_exists($path)) {
        abort(404);
    }
    return Response::file($path, ['Content-Type' => 'application/xml']);
})->name('sitemap');
