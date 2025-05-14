<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Get cities for a specific country
     *
     * @param Country $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Country $country)
    {
        return response()->json($country->cities);
    }
} 