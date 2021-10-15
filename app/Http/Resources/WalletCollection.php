<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class WalletCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
