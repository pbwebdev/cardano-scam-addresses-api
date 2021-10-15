<?php

namespace App\Http\Resources\Json;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BaseResponse;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class PaginatedResourceResponse extends BaseResponse
{
    /**
     * Add the pagination information to the response.
     *
     * @param  Request  $request
     *
     * @return array
     */
    protected function paginationInformation($request): array
    {
        $paginated = $this->resource->resource->toArray();

        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            return ['pagination' => $paginated['total'] > $paginated['per_page'] ? $paginated['links'] : []];
        }

        return [
            'links' => $this->paginationLinks($paginated),
            'meta'  => $this->meta($paginated),
        ];
    }

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
        $keys = array_fill_keys([
            'path',
            'current_page',
            'last_page',
            'per_page',
            'total',
        ], '');

        return array_replace($keys, array_intersect_key($paginated, $keys));
    }
}
