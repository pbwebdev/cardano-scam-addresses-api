<?php

namespace App\Http\Resources;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Submission
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
            'id'          => $this->resource->getAttributeValue('id'),
            'transaction' => $this->resource->getAttributeValue('transaction'),
            'description' => $this->resource->getAttributeValue('description'),
            'status'      => $this->resource->getAttributeValue('status'),
        ];
    }
}
