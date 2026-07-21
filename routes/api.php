<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactMailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/contact-mail', [ContactMailControllerr::class, 'mail']);
Route::post('/contact-mail', [ContactMailController::class, 'mail']);
