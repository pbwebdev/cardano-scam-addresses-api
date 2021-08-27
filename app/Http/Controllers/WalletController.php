<?php

namespace App\Http\Controllers;

use App\Blockfrost;
use App\Http\Requests\WalletRequest;
use App\Http\Resources\WalletCollection;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * @var Blockfrost
     */
    private $blockfrost;

    /**
     * Create a new Wallet controller instance
     */
    public function __construct()
    {
        $this->middleware('can:administrate')->only(['update', 'destroy']);

        $this->blockfrost = app()->make(Blockfrost::class);
    }

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
        $data['address'] = $this->blockfrost->getStakeAddress($data['address']);

        if (! $data['address']) {
            return $this->sendFail('Invalid address');
        }

        $validator = Validator::make($data, $request->rules());

        $wallet = Wallet::create($validator->validated());

        $resource = new WalletResource($wallet);

        return $this->sendSuccess($resource);
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
        $stake_address = $this->blockfrost->getStakeAddress($wallet);

        if (! $stake_address) {
            return $this->sendFail('Invalid address');
        }

        $data = Wallet::where('address', $stake_address)->first();

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

        $resource = new WalletResource($wallet);

        return $this->sendSuccess($resource);
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
