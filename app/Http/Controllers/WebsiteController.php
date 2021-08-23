<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteRequest;
use App\Http\Resources\WebsiteCollection;
use App\Http\Resources\WebsiteResource;
use App\Models\Website;

class WebsiteController extends Controller
{
    /**
     * Send a JSON response back to an Ajax request, indicating success.
     *
     * @param $data
     * @param  int  $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendSuccess($data, $code = 200)
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
        ], $code);
    }

    /**
     * Send a JSON response back to an Ajax request, indicating failure.
     *
     * @param $error
     * @param  int  $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendFail($error, $code = 404)
    {
        return response()->json([
            'success' => false,
            'error'   => $error,
        ], $code);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = new WebsiteCollection(Website::all());

        return $this->sendSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WebsiteRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WebsiteRequest $request)
    {
        $data = $request->validated();
        $website = Website::create($data);

        return $this->show($website->address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($website)
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
     * @param  \App\Models\Website         $website
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(WebsiteRequest $request, Website $website)
    {
        $data = $request->validated();

        $website->update($data);

        return $this->show($website->address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Website $website)
    {
        $website->delete();

        return $this->index();
    }
}
