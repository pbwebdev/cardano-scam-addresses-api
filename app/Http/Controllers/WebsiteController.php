<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteRequest;
use App\Http\Resources\WebsiteCollection;
use App\Http\Resources\WebsiteResource;
use App\Models\Website;
use Illuminate\Http\JsonResponse;

class WebsiteController extends Controller
{
    /**
     * Create a new Website controller instance
     */
    public function __construct()
    {
        $this->middleware('can:administrate')->only(['update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = new WebsiteCollection(Website::all());

        return $this->sendSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WebsiteRequest  $request
     *
     * @return JsonResponse
     */
    public function store(WebsiteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $website = Website::create($data);

        return $this->show($website->address);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $website
     *
     * @return JsonResponse
     */
    public function show(string $website): JsonResponse
    {
        $data = Website::where('address', $website)->first();

        if (is_null($data)) {
            return $this->sendFail('Not found');
        }

        $data = new WebsiteResource($data);

        return $this->sendSuccess($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WebsiteRequest  $request
     * @param  Website         $website
     *
     * @return JsonResponse
     */
    public function update(WebsiteRequest $request, Website $website): JsonResponse
    {
        $data = $request->validated();

        $website->update($data);

        return $this->show($website->address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Website  $website
     *
     * @return JsonResponse
     */
    public function destroy(Website $website): JsonResponse
    {
        $website->delete();

        return $this->index();
    }
}
