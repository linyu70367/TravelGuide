<?php

use App\Http\Controllers\Api\TravelFoodController;
use Illuminate\Support\Facades\Route;

// 取得全部美食
Route::get(
    '/travelfoods',
    [TravelFoodController::class, 'index']
);

// 取得單筆美食
Route::get('/travelfoods/{id}', [TravelFoodController::class, 'show']);
