<?php

namespace App\Http\Resources\Json;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BaseResponse;
use Illuminate\Support\Arr;

class PaginatedResourceResponse extends BaseResponse
{
    /**
     * Get the pagination links for the response.
     *
     * @param  array  $paginated
     *
     * @return array
     */
    protected function paginationLinks($paginated): array
    {
        return [
            'first' => $paginated['first_page_url'] ?? null,
            'last'  => $paginated['last_page_url'] ?? null,
            'prev'  => $paginated['prev_page_url'] ?? null,
            'next'  => $paginated['next_page_url'] ?? null,
        ];
    }

    /**
     * Gather the meta data for the response.
     *
     * @param  array  $paginated
     *
     * @return array
     */
    protected function meta($paginated): array
    {
        return Arr::except($paginated, [
            'data',
            'links',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url',
        ]);
    }
}
