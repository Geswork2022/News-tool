<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsViewController;

// Page d'accueil
Route::get('/', function () {
    return response()->json(['message' => 'Bienvenue sur l\'API News!']);
});

// Routes CRUD pour la gestion des News
Route::middleware(['web'])->group(function () {
    Route::get('/news', [NewsViewController::class, 'index'])->name('news.index');
    Route::get('/news/create', [NewsViewController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsViewController::class, 'store'])->name('news.store');
    Route::get('/news/{id}', [NewsViewController::class, 'show'])->name('news.show');
    Route::get('/news/{id}/edit', [NewsViewController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [NewsViewController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [NewsViewController::class, 'destroy'])->name('news.destroy');

    // Route pour l'upload de fichiers via Trix Editor
    Route::post('/upload-attachment', [NewsViewController::class, 'uploadTrixFile'])
        ->name('upload.attachment');
});
