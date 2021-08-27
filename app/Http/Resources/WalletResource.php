<?php

namespace App\Http\Resources;

use App\Models\Wallet;
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
        $key = 'index' === $method ? 'address' : 'stake_address';
        $values = [
            'id' => $this->resource->getAttributeValue('id'),
            $key => $this->resource->getAttributeValue('address'),
        ];

        if ('show' === $method) {
            $address = $request->route('address');

            if (0 === strpos($address, 'addr1')) {
                $values['address'] = $address;
            }
        }

        return $values;
    }
}
