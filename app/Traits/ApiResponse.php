<?php 
namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data,string $message = null, $code=200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse(string $message, int $code = 400, $data = [])
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null,
        ], $code);
    }
}