<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    #[OA\Get(
        path: "/api/blogs",
        operationId: "getBlogs",
        tags: ["Blogs"],
        summary: "Get list of blogs",
        description: "Returns paginated published blogs",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation"
            )
        ]
    )]
    public function index()
    {
        $blogs = Blog::with('category', 'author')
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(10);

        return response()->json($blogs);
    }

    #[OA\Get(
        path: "/api/blogs/{slug}",
        operationId: "getBlog",
        tags: ["Blogs"],
        summary: "Get single blog",
        description: "Returns a single blog by slug",
        parameters: [
            new OA\Parameter(
                name: "slug",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation"
            ),
            new OA\Response(
                response: 404,
                description: "Blog not found"
            )
        ]
    )]
    public function show($slug)
    {
        $blog = Blog::with('category', 'author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($blog);
    }
}
