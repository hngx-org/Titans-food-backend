<?php

namespace App\Traits;

trait MessageTrait
{
    
    public function success($message, $code = 200, $data = [])
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error($message, $code)
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message
        ], $code);
    }
}
