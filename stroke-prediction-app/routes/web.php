<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PredictionController;

Route::get('/', function () {
    return view('welcome');  // Or your main view
});
Route::post('/predict', [PredictionController::class, 'predict'])->name('predict');
