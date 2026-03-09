<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FaqController extends Controller
{
    #[OA\Get(
        path: "/api/faqs",
        operationId: "getFaqs",
        tags: ["FAQs"],
        summary: "Get list of active FAQs",
        description: "Returns a list of active FAQs sorted by sort order. Optional tag filter.",
        parameters: [
            new OA\Parameter(
                name: "tag",
                in: "query",
                required: false,
                description: "Filter FAQs by a specific tag",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "question", type: "string"),
                            new OA\Property(property: "answer", type: "string"),
                            new OA\Property(property: "tag", type: "string", nullable: true),
                            new OA\Property(property: "sort_order", type: "integer")
                        ]
                    )
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $query = Faq::where('is_active', true);

        if ($request->has('tag') && $request->tag) {
            $query->where('tag', $request->tag);
        }

        $faqs = $query->orderBy('sort_order')->paginate(15);

        return response()->json($faqs);
    }
}
