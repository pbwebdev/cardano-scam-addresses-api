<?php

namespace App\Traits;

use App\Exceptions\TransactionNotFoundException;

trait Transactional
{
    use Serviceable;

    protected function getTransactionDetails(string $hash): array
    {
        $transactionDetails = $this->serviceProvider->getTransactionDetails($hash);

        if (! $transactionDetails) {
            throw new TransactionNotFoundException();
        }

        return $transactionDetails;
    }

    protected function getStakeAddresses(array $data): array
    {
        $senders = $this->getStakeAddress('inputs', $data);
        $receivers = $this->getStakeAddress('outputs', $data);

        return compact('senders', 'receivers');
    }

    protected function getStakeAddress(string $type, $data): array
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
