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
        $suspects = $this->getSuspectStakes($senders, $receivers);

        return compact('senders', 'receivers', 'suspects');
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

    protected function getSuspectStakes(array $senders, array $receivers): array
    {
        if ($senders === $receivers) {
            return [];
        }

        if (1 === count($receivers)) {
            return [array_key_first($receivers)];
        }

        return array_diff(array_keys($receivers), array_keys($senders));
    }
}
