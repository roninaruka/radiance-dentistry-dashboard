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
use App\Mail\AppointmentConfirmed;

class AppointmentController extends Controller
{
    #[OA\Get(
        path: "/api/slots",
        operationId: "getAvailableSlots",
        tags: ["Appointments"],
        summary: "Get available slots for a date",
        description: "Returns a list of available time slots",
        parameters: [
            new OA\Parameter(
                name: "date",
                in: "query",
                required: true,
                description: "Date in YYYY-MM-DD format",
                schema: new OA\Schema(type: "string", format: "date")
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
        ]);

        $slots = $bookingService->getAvailableSlots($request->date);

        return response()->json([
            'date' => $request->date,
            'slots' => $slots,
        ]);
    }

    #[OA\Post(
        path: "/api/appointments",
        operationId: "createAppointment",
        tags: ["Appointments"],
        summary: "Create a new appointment",
        description: "Creates an appointment and links/creates a patient record",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["date", "time", "name", "email", "phone"],
                properties: [
                    new OA\Property(property: "date", type: "string", format: "date", example: "2025-10-10"),
                    new OA\Property(property: "time", type: "string", example: "10:00"),
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "1234567890"),
                    new OA\Property(property: "reason", type: "string", example: "Checkup")
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
            'date' => 'required|date',
            'time' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'reason' => 'nullable|string|max:500',
        ]);

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
            'patient_id' => $patient->id,
            'name' => $request->name, // Keep name for historical record in appointment
            'email' => $request->email,
            'phone' => $request->phone,
            'reason' => $request->reason,
            'appointment_date' => $request->date,
            'appointment_time' => $request->time,
            'status' => 'pending',
        ]);

        // Send confirmation email
        try {
             Mail::to($appointment->email)->send(new AppointmentConfirmed($appointment));
             Mail::to('admin@radiance.com')->send(new AppointmentConfirmed($appointment));
        } catch (\Exception $e) {
            \Log::error('Mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Appointment created successfully',
            'appointment' => $appointment->load('patient'),
        ], 201);
    }
}
