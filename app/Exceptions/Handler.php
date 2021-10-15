<?php

namespace App\Exceptions;

use App\Traits\HasJsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use HasJsonResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(static function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request    $request
     * @param  Throwable  $e
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        $response = parent::render($request, $e);

        if ($request->route() && 'api' === $request->route()->getPrefix()) {
            if ($e instanceof ModelNotFoundException) {
                $response->setData(['message' => 'Requested resource was not found.']);
            } elseif ($e instanceof InvalidAddressException) {
                $response->setData(['message' => 'Requested address was not valid to the network.']);
            }

            $response = new JsonResponse(
                $response->getData(true),
                $response->getStatusCode()
            );
        }

        return $response;
    }
}
