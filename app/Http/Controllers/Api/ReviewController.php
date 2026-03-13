<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Get(
        path: "/api/reviews",
        operationId: "getReviews",
        summary: "Get list of published reviews",
        description: "Returns a list of published patient reviews",
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
                            new OA\Property(property: "rating", type: "integer"),
                            new OA\Property(property: "comment", type: "string"),
                            new OA\Property(property: "avatar", type: "string", nullable: true)
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        $reviews = Review::where('is_published', true)
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }
}
