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
        return [
            'id'      => $this->resource->getAttributeValue('id'),
            'address' => $this->resource->getAttributeValue('address'),
        ];
    }
}
