<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BeforeAfter;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BeforeAfterController extends Controller
{
    #[OA\Get(
        path: "/api/before-afters",
        operationId: "getBeforeAfters",
        summary: "Get list of active Before & After records",
        description: "Returns a list of active Before & After records sorted by sort order.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "title", type: "string"),
                            new OA\Property(property: "treatment", type: "string", nullable: true),
                            new OA\Property(property: "problem", type: "string", nullable: true),
                            new OA\Property(property: "before_image", type: "string"),
                            new OA\Property(property: "after_image", type: "string"),
                            new OA\Property(property: "sort_order", type: "integer")
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        $records = BeforeAfter::where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(15);

        // Update image URLs to full URLs
        $records->getCollection()->transform(function ($record) {
            $record->before_image = filter_var($record->before_image, FILTER_VALIDATE_URL) ? $record->before_image : asset('storage/' . $record->before_image);
            $record->after_image = filter_var($record->after_image, FILTER_VALIDATE_URL) ? $record->after_image : asset('storage/' . $record->after_image);
            return $record;
        });

        return response()->json($records);
    }
}
