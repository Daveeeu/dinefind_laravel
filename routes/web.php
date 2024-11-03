<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
    Route::get('/swagger', function () {
        return view('swagger');
    });

Route::get('api/restaurants', [\App\Http\Controllers\Api\RestaurantController::class, 'index']);
