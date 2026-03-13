<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class CaptchaController extends Controller
{
    #[OA\Get(
        path: "/api/captcha",
        operationId: "generateCaptcha",
        summary: "Generate a new CAPTCHA",
        description: "Returns a base64 encoded image and a unique token for verification",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", example: "uuid-token"),
                        new OA\Property(property: "image", type: "string", example: "data:image/png;base64,...")
                    ]
                )
            )
        ]
    )]
    public function generate()
    {
        // Generate a random 6-character alphanumeric code
        $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));

        // Create image using GD
        $width  = 200;
        $height = 60;
        $image  = imagecreatetruecolor($width, $height);

        // Colors
        $bg    = imagecolorallocate($image, 245, 245, 245);
        $text  = imagecolorallocate($image, 30, 30, 30);
        $noise = imagecolorallocate($image, 180, 180, 180);

        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);

        // Add noise lines
        for ($i = 0; $i < 6; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $noise);
        }

        // Add noise dots
        for ($i = 0; $i < 100; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $height), $noise);
        }

        // Draw each character with slight rotation offset
        $fontPath = base_path('resources/fonts/captcha.ttf');
        $fontSize = 22;
        $x = 15;

        for ($i = 0; $i < strlen($code); $i++) {
            $angle = rand(-15, 15);
            $y     = rand(38, 48);
            imagettftext($image, $fontSize, $angle, $x, $y, $text, $fontPath, $code[$i]);
            $x += rand(26, 32);
        }

        // Capture output as base64
        ob_start();
        imagepng($image);
        $imageData = base64_encode(ob_get_clean());
        imagedestroy($image);

        // Store in cache for 5 minutes with a unique token
        $token = Str::uuid()->toString();
        Cache::put('captcha_' . $token, $code, now()->addMinutes(5));

        return response()->json([
            'token' => $token,
            'image' => 'data:image/png;base64,' . $imageData,
        ]);
    }
}
