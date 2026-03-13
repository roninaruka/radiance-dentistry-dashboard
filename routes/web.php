<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;

// All frontend routes are redundant as Filament handles the root path.
// API routes are defined in api.php

// Signed routes for Appointment Actions
Route::get('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])
    ->name('appointment.confirm')
    ->middleware('signed');

Route::get('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
    ->name('appointment.cancel')
    ->middleware('signed');
