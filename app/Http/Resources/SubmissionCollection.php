<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SubmissionCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
