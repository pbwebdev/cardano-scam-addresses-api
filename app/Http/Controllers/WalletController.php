<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Http\Resources\WalletResource;
use App\Http\Resources\WalletCollection;
use App\Models\Wallet;

class WalletController extends Controller
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
        $data = new WalletCollection(Wallet::all());

        return $this->sendSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WalletRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WalletRequest $request)
    {
        $data = $request->validated();
        $wallet = Wallet::create($data);

        return $this->show($wallet->address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($wallet)
    {
        $data = Wallet::where('address', $wallet)->first();

        if (is_null($data)) {
            return $this->sendFail('Not found');
        }

        $data = new WalletResource($data);

        return $this->sendSuccess($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WalletRequest  $request
     * @param  \App\Models\Wallet  $wallet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(WalletRequest $request, Wallet $wallet)
    {
        $data = $request->validated();

        $wallet->update($data);

        return $this->show($wallet->address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return $this->index();
    }
}
