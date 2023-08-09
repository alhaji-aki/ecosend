<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $countries = Country::query()
            ->when($request->filled('term'), function ($query) use ($request) {
                $term = $request->string('term')->trim()->pipe('htmlspecialchars')->append('%')->toString();

                $query->where('name', 'LIKE', $term);
            })->get(['name', 'uuid']);

        return response()->json([
            'data' => $countries,
        ]);
    }
}
