<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionNotFoundException;
use App\Http\Requests\TransactionRequest;
use App\Traits\Serviceable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    use Serviceable;

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
        $details = $this->getDetails($data['hash']);

        return [
            'data' => $details,
            'meta' => $this->getStakes($details),
        ];
    }

    protected function getDetails(string $hash): array
    {
        $transactionDetails = $this->serviceProvider->getTransactionDetails($hash);

        if (! $transactionDetails) {
            throw new TransactionNotFoundException();
        }

        return $transactionDetails;
    }

    protected function getStakes(array $data): array
    {
        $senders = $this->getStake('inputs', $data);
        $receivers = $this->getStake('outputs', $data);

        return compact('senders', 'receivers');
    }

    protected function getStake(string $type, $data): array
    {
        $addresses = [];

        foreach ($data[$type] as $transaction) {
            $stake = $this->serviceProvider->getStakeAddress($transaction['address']);

            if ($stake) {
                $addresses[$stake][] = $transaction['address'];
            }
        }

        return array_filter($addresses);
    }
}
