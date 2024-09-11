<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Send any success response.
     *
     * @param string $message
     * @param object|array $data
     * @param string $key
     * @param int $statusCode
     * @return JsonResponse
     */
    public function success(string $message = 'success', object|array $data = [], string $key = 'item', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'code' => $statusCode,
            'status' => true,
            'message' => $message,
            $key => $data,
        ], $statusCode);
    }

    /**
     * Send any error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param int|string $customErrorCode
     * @return JsonResponse
     */
    public function error(string $message, int $statusCode = 500, int|string $customErrorCode = 0): JsonResponse
    {
        return response()->json([
            'code' => $statusCode,
            'status' => false,
            'message' => $message,
            'item' => [],
            'error_code' => $customErrorCode,
        ], $statusCode);
    }

    /**
     * Send any validation errors response.
     *
     * @param array $errors
     * @param string $message
     * @return JsonResponse
     */
    public function validationErrors(array $errors, string $message = 'Validation Error'): JsonResponse
    {
        return response()->json([
            'code' => 422,
            'status' => false,
            'message' => $message,
            'item' => [],
            'errors' => $errors,
        ], 422);
    }
}
