<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Radiance Dentistry API",
    description: "API documentation for Radiance Dentistry Dashboard",
    contact: new OA\Contact(email: "roninaruka@gmail.com")
)]
#[OA\Server(
    url: "https://my.radiancedentistryclinic.com/",
    description: "Live Server"
)]
#[OA\Server(
    url: "http://localhost:8080",
    description: "Local Development Server"
)]
class Swagger
{
    #[OA\Get(
        path: "/api/health",
        tags: ["Health"],
        summary: "Health check",
        responses: [
            new OA\Response(response: 200, description: "OK")
        ]
    )]
    public function health()
    {
        return response()->json(['status' => 'ok']);
    }
}
