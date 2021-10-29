<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Traits\Transactional;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    use Transactional;

    /**
     * Handle the incoming request.
     *
     * @param  TransactionRequest  $request
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function __invoke(TransactionRequest $request): JsonResponse
    {
        $this->setServiceProvider();

        $data = $request->validated();

        return new JsonResponse($this->getResponse($data));
    }

    protected function getResponse(array $data): array
    {
        $details = $this->getTransactionDetails($data['hash']);

        return [
            'data' => $details,
            'meta' => $this->getStakeAddresses($details),
        ];
    }
}
