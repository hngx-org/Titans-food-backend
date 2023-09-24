<?php

namespace App\Traits;

trait MessageTrait
{
    
    public function success($message, $code = 200, $data = [])
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error($message, $code, $data = [])
    {
        return response()->json([
            'statusCode' => $code,
            'message' => $message
        ], $code);
    }
}
