<?php

namespace App\Http\Controllers;

use App\Http\Resources\Wallet as WalletResource;
use App\Http\Resources\WalletCollection;
use App\Models\Wallet;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
