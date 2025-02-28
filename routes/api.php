<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/news', [NewsViewController::class, 'index']); // Ajout du GET pour lister les news
Route::get('/news/{id}', [NewsViewController::class, 'show']);
Route::post('/news', [NewsViewController::class, 'store']);
Route::put('/news/{id}', [NewsViewController::class, 'update']);
Route::delete('/news/{id}', [NewsViewController::class, 'destroy']);
