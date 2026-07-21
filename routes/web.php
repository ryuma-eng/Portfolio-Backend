<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactMailController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/contact-mail', [ContactMailController::class, 'mail']);
