<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Routes with global rate limiting
Route::middleware('throttle:api')->group(function () {
    // Blog Routes
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{slug}', [BlogController::class, 'show']);

    // Appointment Routes
    Route::get('/slots', [AppointmentController::class, 'slots']);
    Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('throttle:5,1'); // Strict throttle for booking

    // Location Routes
    Route::get('/locations', [LocationController::class, 'index']);

    // Content Routes
    Route::get('/faqs', [FaqController::class, 'index']);
    Route::get('/reviews', [ReviewController::class, 'index']);
});
