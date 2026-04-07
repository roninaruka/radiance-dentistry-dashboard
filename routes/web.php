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

// Utility: Clear all caches (for shared hosting without SSH)
Route::get('/clear-cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('event:clear');

    return response()->json([
        'status' => 'success',
        'message' => 'All caches cleared successfully.',
    ]);
});

// Utility: Test email sending
Route::get('/test-mail', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email from Radiance Dentistry.', function ($message) {
            $message->to('no-reply@radiancedentistryclinic.com')
                    ->subject('Test Email - Radiance Dentistry');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Test email sent successfully.',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});
// Utility: Run migrations (for shared hosting without SSH)
Route::get('/run-migrations', function () {
    if (request('token') !== 'RadianceMigrate2026') {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized. Invalid token.',
        ], 403);
    }

    try {
        \Artisan::call('migrate', ['--force' => true]);
        return response()->json([
            'status' => 'success',
            'message' => 'Migrations executed successfully.',
            'output' => \Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});
