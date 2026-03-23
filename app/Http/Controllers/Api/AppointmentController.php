<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use App\Models\Appointment;
use App\Models\Patient;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentConfirmedDoctor;

class AppointmentController extends Controller
{
    #[OA\Get(
        path: "/api/slots",
        operationId: "getAvailableSlots",
        summary: "Get available slots for a date",
        description: "Returns a list of available time slots",
        parameters: [
            new OA\Parameter(
                name: "date",
                in: "query",
                required: true,
                description: "Date in YYYY-MM-DD format",
                schema: new OA\Schema(type: "string", format: "date")
            ),
            new OA\Parameter(
                name: "clinic_id",
                in: "query",
                required: false,
                description: "Clinic ID (Location ID)",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "date", type: "string", format: "date"),
                        new OA\Property(property: "slots", type: "array", items: new OA\Items(type: "string"))
                    ]
                )
            )
        ]
    )]
    public function slots(Request $request, BookingService $bookingService)
    {
        $request->validate([
            'date' => 'required|date',
            'clinic_id' => 'nullable|exists:locations,id',
        ]);

        $slots = $bookingService->getAvailableSlots($request->date, $request->clinic_id);

        return response()->json([
            'date' => $request->date,
            'slots' => $slots,
        ]);
    }

    #[OA\Post(
        path: "/api/appointments",
        operationId: "createAppointment",
        summary: "Create a new appointment",
        description: "Creates an appointment and links/creates a patient record",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["date", "time", "name", "email", "phone", "captcha_token", "captcha_answer"],
                properties: [
                    new OA\Property(property: "date", type: "string", format: "date", example: "2025-10-10"),
                    new OA\Property(property: "time", type: "string", example: "10:00"),
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "1234567890"),
                    new OA\Property(property: "reason", type: "string", example: "Checkup"),
                    new OA\Property(property: "doctor_id", type: "integer", example: 1, description: "Optional doctor ID"),
                    new OA\Property(property: "clinic_id", type: "integer", example: 1, description: "Clinic ID (from locations)"),
                    new OA\Property(property: "captcha_token", type: "string", example: "uuid-token"),
                    new OA\Property(property: "captcha_answer", type: "string", example: "ABCD12")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Appointment created successfully"
            )
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'date'           => 'required|date',
            'time'           => 'required',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'reason'         => 'nullable|string|max:500',
            'doctor_id'      => 'nullable|exists:users,id',
            'clinic_id'      => 'required|exists:locations,id',
            'captcha_token'  => 'required|string',
            'captcha_answer' => 'required|string',
        ]);

        // Verify CAPTCHA
        $cacheKey = 'captcha_' . $request->captcha_token;
        $expected = \Illuminate\Support\Facades\Cache::get($cacheKey);

        if (!$expected || strtoupper($request->captcha_answer) !== strtoupper($expected)) {
            return response()->json(['message' => 'Invalid or expired CAPTCHA. Please try again.'], 422);
        }

        // Delete CAPTCHA from cache (single-use)
        \Illuminate\Support\Facades\Cache::forget($cacheKey);

        // Find or Create Patient
        $patient = Patient::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'phone' => $request->phone,
            ]
        );

        // Always update phone if it changed? Maybe just keep it simple
        if ($patient->phone !== $request->phone || $patient->name !== $request->name) {
            $patient->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
        }

        $appointment = Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => $request->doctor_id,
            'location_id'      => $request->clinic_id,
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'reason'           => $request->reason,
            'appointment_date' => $request->date,
            'appointment_time' => $request->time,
            'status'           => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment request submitted successfully. Our team will confirm your appointment shortly.',
            'appointment' => $appointment->load('patient'),
        ], 201);
    }

    public function confirm(Request $request, Appointment $appointment)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature.');
        }

        if ($appointment->status !== 'pending') {
             return view('appointment.status', [
                'title' => 'Appointment Status',
                'status' => 'error',
                'heading' => 'Already Processed',
                'message' => 'This appointment has already been ' . $appointment->status . '.'
            ]);
        }

        $appointment->update(['status' => 'confirmed']);

        return view('appointment.status', [
            'title' => 'Appointment Confirmed',
            'status' => 'success',
            'heading' => 'Appointment Confirmed!',
            'message' => 'The appointment for ' . $appointment->name . ' has been successfully confirmed. A notification email has been sent to the patient.'
        ]);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature.');
        }

        if ($appointment->status !== 'pending') {
             return view('appointment.status', [
                'title' => 'Appointment Status',
                'status' => 'error',
                'heading' => 'Already Processed',
                'message' => 'This appointment has already been ' . $appointment->status . '.'
            ]);
        }

        $appointment->update(['status' => 'cancelled']);

        return view('appointment.status', [
            'title' => 'Appointment Cancelled',
            'status' => 'success',
            'heading' => 'Appointment Cancelled',
            'message' => 'The appointment for ' . $appointment->name . ' has been cancelled. A cancellation email has been sent to the patient.'
        ]);
    }
}
