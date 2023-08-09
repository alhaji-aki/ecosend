<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\LazyCollection;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var array<int, array> */
        $data = json_decode(file_get_contents(storage_path('app/countries+states+cities.json')), true);

        LazyCollection::make($data)->each(function (array $countryData) {
            $countryId = Country::query()->firstOrCreate(Arr::only($countryData, 'name'))->id;

            LazyCollection::make($countryData['states'])->each(function (array $stateData) use ($countryId) {
                $stateId = State::query()->firstOrCreate([...Arr::only($stateData, 'name'), 'country_id' => $countryId])->id;

                $cities = LazyCollection::make($stateData['cities'])
                    ->map(fn (string $cityName) => ['uuid' => Str::orderedUuid(), 'name' => $cityName, 'state_id' => $stateId, 'country_id' => $countryId])
                    ->toArray();

                City::query()->insertOrIgnore($cities);
            });
        });
    }
}
