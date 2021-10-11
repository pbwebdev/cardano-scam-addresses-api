<?php

namespace App\Http\Controllers;

use App\Blockfrost;
use App\Http\Requests\WalletRequest;
use App\Http\Resources\WalletCollection;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use App\Rules\Bech32Address;
use App\Rules\CardanoAddress;
use Illuminate\Contracts\Container\BindingResolutionException;
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
     * @throws BindingResolutionException
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

        return $data->response();
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
        $address = new CardanoAddress();
        $validator = Validator::make($data, compact('address'));
        $data = $validator->validated();

        if (! $address->isStake($data['address'])) {
            $data['address'] = $this->blockfrost->getStakeAddress($data['address']);

            if (! $data['address']) {
                return $this->sendFail('Invalid address');
            }
        }

        $validator = Validator::make($data, $request->rules());

        $wallet = Wallet::create($validator->validated());

        $resource = new WalletResource($wallet);

        return $resource->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $key
     *
     * @return JsonResponse
     */
    public function show(string $key): JsonResponse
    {
        $address = new CardanoAddress();
        $validator = Validator::make(['address' => $key], ['address' => [new Bech32Address(), $address]]);
        $validated = $validator->validated();

        if (! $address->isStake($validated['address'])) {
            $validated['address'] = $this->blockfrost->getStakeAddress($validated['address']);

            if (! $validated['address']) {
                return $this->sendFail('Invalid address');
            }
        }

        $data = Wallet::where('address', $validated['address'])->firstOrFail();
        $resource = new WalletResource($data);

        return $resource->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WalletRequest  $request
     * @param  Wallet         $address
     *
     * @return JsonResponse
     */
    public function update(WalletRequest $request, Wallet $address): JsonResponse
    {
        $data = $request->validated();

        $address->update($data);

        $resource = new WalletResource($address);

        return $resource->response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Wallet  $address
     *
     * @return JsonResponse
     */
    public function destroy(Wallet $address): JsonResponse
    {
        $address->delete();

        return new JsonResponse([], 204);
    }
}
