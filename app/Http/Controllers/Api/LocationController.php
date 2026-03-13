<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LocationController extends Controller
{
    #[OA\Get(
        path: "/api/locations",
        operationId: "getLocations",
        summary: "Get list of clinic locations",
        description: "Returns a list of clinic branches",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "address", type: "string"),
                            new OA\Property(property: "phone", type: "string"),
                            new OA\Property(property: "email", type: "string"),
                            new OA\Property(property: "working_hours", type: "string")
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        $locations = Location::paginate(10);

        return response()->json($locations);
    }
}
