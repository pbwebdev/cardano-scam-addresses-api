<?php

namespace App\Http\Resources;

use App\Models\Wallet;
use App\Rules\CardanoAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Wallet
     */
    public $resource;

    /**
     * @var CardanoAddress
     */
    protected $validator;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     *
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->validator = new CardanoAddress();
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        /** @noinspection NullPointerExceptionInspection */
        $method = $request->route()->getActionMethod();
        $values = [
            'id'      => $this->resource->getAttributeValue('id'),
            'address' => $this->resource->getAttributeValue('address'),
        ];

        if ('show' === $method) {
            $address = $request->route('address');

            if (! $this->validator->isStake($address)) {
                $values['key'] = $address;
            }
        }

        return $values;
    }
}
