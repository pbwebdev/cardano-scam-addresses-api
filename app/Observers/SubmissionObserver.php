<?php

namespace App\Observers;

use App\Models\Submission;
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
        $this->setServiceProvider();

        $transactionDetails = $this->getTransactionDetails($submission->getAttribute('transaction'));
        $stakeAddresses = $this->getStakeAddresses($transactionDetails);

        logger($stakeAddresses);
    }
}
