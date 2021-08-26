<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasJsonResponse
{

    /**
     * Send a JSON response back to an Ajax request, indicating success.
     *
     * @param  JsonResource  $data
     * @param  int           $code
     *
     * @return JsonResponse
     */
    protected function sendSuccess(JsonResource $data, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
        ], $code);
    }

    /**
     * Send a JSON response back to an Ajax request, indicating failure.
     *
     * @param  string  $message
     * @param  int     $code
     * @param  array   $errors
     *
     * @return JsonResponse
     */
    protected function sendFail(string $message, int $code = 404, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
