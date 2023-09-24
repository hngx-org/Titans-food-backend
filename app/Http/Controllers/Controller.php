<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="Team Titans - Free Lunch API", version="0.1"),
    * @OA\SecurityScheme(
    *    securityScheme="bearerAuth",
    *    in="header",
    *    name="bearerAuth",
    *    type="http",
    *    scheme="bearer",
    *    bearerFormat="JWT",
    * ),
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse(
        bool $error_status = true,
        string $message = 'Bad Request',
        $data = null,
        int $status_code = 400
    ): JsonResponse {
        return response()->json([
            'error' => $error_status,
            'message' => ucfirst($message),
            'data' => $data,
        ], $status_code);
    }
}
