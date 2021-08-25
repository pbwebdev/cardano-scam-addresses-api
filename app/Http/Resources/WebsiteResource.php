<?php

namespace App\Http\Resources;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Website
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
