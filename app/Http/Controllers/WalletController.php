<?php

namespace App\Http\Controllers;

use App\Blockfrost;
use App\Exceptions\InvalidAddressException;
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
     * @var CardanoAddress
     */
    private $cardanoAddress;

    /**
     * Create a new Wallet controller instance
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->middleware('can:administrate')->only(['update', 'destroy']);

        $this->blockfrost = app()->make(Blockfrost::class);
        $this->cardanoAddress = new CardanoAddress();
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
        $validator = Validator::make($request->validated(), ['address' => $this->cardanoAddress]);
        $data = $validator->validated();

        $this->maybeInvalidAddress($data['address']);

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
        $validator = Validator::make(['address' => $key], ['address' => [new Bech32Address(), $this->cardanoAddress]]);
        $data = $validator->validated();

        $this->maybeInvalidAddress($data['address']);

        $wallet = Wallet::where('address', $data['address'])->firstOrFail();

        $resource = new WalletResource($wallet);

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


    /**
     * @throws InvalidAddressException
     */
    protected function maybeInvalidAddress(string &$data): void
    {
        if (! $this->cardanoAddress->isStake($data)) {
            $data = $this->blockfrost->getStakeAddress($data);

            if (! $data) {
                throw new InvalidAddressException();
            }
        }
    }
}
