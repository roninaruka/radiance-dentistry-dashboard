<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use OpenApi\Attributes as OA;

class DoctorController extends Controller
{
    #[OA\Get(
        path: "/api/doctors",
        operationId: "getDoctors",
        summary: "Get list of doctors",
        description: "Returns a list of all users with the doctor role",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "Dr. Smith"),
                            new OA\Property(property: "email", type: "string", format: "email", example: "doctor@example.com")
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            User::role('doctor')->get(['id', 'name', 'email'])
        );
    }
}
