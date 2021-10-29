<?php

namespace App\Observers;

use App\Models\Submission;
use App\Models\Wallet;
use App\Traits\Transactional;
use Illuminate\Contracts\Container\BindingResolutionException;

class SubmissionObserver
{
    use Transactional;

    /**
     * Handle the Submission "updated" event.
     *
     * @param  Submission  $submission
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function updated(Submission $submission): void
    {
        if ('accepted' !== $submission->getAttribute('status')) {
            return;
        }

        $this->setServiceProvider();

        $transactionDetails = $this->getTransactionDetails($submission->getAttribute('transaction'));
        $stakeAddresses = $this->getStakeAddresses($transactionDetails);

        foreach ($stakeAddresses['suspects'] as $address) {
            Wallet::updateOrCreate(compact('address'));
        }
    }
}
