<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Order;
use App\Models\State;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('welcome');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = (array) $request->validated();

        // validate and set ids of locations on data object
        $data = $this->validateLocations($data);

        try {
            DB::transaction(function () use ($data) {
                $orderProducts = collect($data['products'] ?? [])->map(fn (array $product) => [...$product, 'price' => $product['price'] * 100]);

                // create the order
                $order = Order::query()->create([
                    ...Arr::except($data, ['products']),
                    'quantity' => $orderProducts->sum('quantity'),
                    'amount' => $orderProducts->sum('price'),
                ]);

                // save order products
                $order->orderProducts()->createMany($orderProducts);
            });

            return response()->json([
                'message' => 'Thank you for your purchase, We will deliver your product to the given Address.',
            ]);
        } catch (Exception $exception) {
            report($exception);

            abort(500, 'Sorry we are unable to process your order at the moment.');
        }
    }

    private function validateLocations(array $data): array
    {
        /** @var \App\Models\Country|null */
        $country = Country::query()->where('uuid', $data['country'])->first();
        if (! $country) {
            throw ValidationException::withMessages(['country' => 'The selected country is invalid']);
        }

        /** @var \App\Models\State|null */
        $state = State::query()->where('uuid', $data['state'])->where('country_id', $country->id)->first();

        if (! $state) {
            throw ValidationException::withMessages(['state' => 'The selected state is invalid']);
        }
        /** @var \App\Models\City|null */
        $city = City::query()->where('uuid', $data['city'])->where('state_id', $state->id)->where('country_id', $country->id)->first();

        if (! $city) {
            throw ValidationException::withMessages(['city' => 'The selected city is invalid']);
        }

        $data['country_id'] = $country->id;
        $data['state_id'] = $state->id;
        $data['city_id'] = $city->id;

        return Arr::except($data, ['country', 'state', 'city']);
    }
}
