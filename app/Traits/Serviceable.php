<?php

namespace App\Traits;

use App\Blockfrost;
use App\TangoCrypto;
use Illuminate\Contracts\Container\BindingResolutionException;

trait Serviceable
{
    private $serviceProvider;

    /**
     * @throws BindingResolutionException
     */
    protected function setServiceProvider(): void
    {
        $providers = [
            'blockfrost'  => Blockfrost::class,
            'tangocrypto' => TangoCrypto::class,
        ];

        $service = config('services.cardano.service_provider');

        if (! array_key_exists($service, $providers)) {
            $service = 'blockfrost';
        }

        $this->serviceProvider = app()->make($providers[$service]);
    }
}
