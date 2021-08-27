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
        $method = $request->route()->getActionMethod();
        $key = 'index' === $method ? 'address' : 'stake_address';
        $values = [
            'id' => $this->resource->getAttributeValue('id'),
            $key => $this->resource->getAttributeValue('address'),
        ];

        if ('show' === $method) {
            $wallet = $request->route('wallet');

            if (0 === strpos($wallet, 'addr1')) {
                $values['address'] = $request->route('wallet');
            }
        } elseif ('store' === $method) {
            $values['address'] = $request->input('address');
        }

        return $values;
    }
}
