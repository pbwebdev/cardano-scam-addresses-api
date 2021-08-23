<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Http\Resources\WalletResource;
use App\Http\Resources\WalletCollection;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = new WalletCollection(Wallet::all());

        return $this->sendSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WalletRequest  $request
     *
     * @return JsonResponse
     */
    public function store(WalletRequest $request): JsonResponse
    {
        $data = $request->validated();
        $wallet = Wallet::create($data);

        return $this->show($wallet->address);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $wallet
     *
     * @return JsonResponse
     */
    public function show(string $wallet): JsonResponse
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
     * @param  Wallet         $wallet
     *
     * @return JsonResponse
     */
    public function update(WalletRequest $request, Wallet $wallet): JsonResponse
    {
        $data = $request->validated();

        $wallet->update($data);

        return $this->show($wallet->address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Wallet  $wallet
     *
     * @return JsonResponse
     */
    public function destroy(Wallet $wallet): JsonResponse
    {
        $wallet->delete();

        return $this->index();
    }
}
