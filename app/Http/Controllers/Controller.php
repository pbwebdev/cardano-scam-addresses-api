<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

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
     * @param  string  $error
     * @param  int     $code
     *
     * @return JsonResponse
     */
    protected function sendFail(string $error, int $code = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error'   => $error,
        ], $code);
    }
}
