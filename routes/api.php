<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Agent;

Route::group(['middleware' => 'acceptLocale'], function () {
    Route::get('oil-brands/{oilBrand}', function ($oilBrand){
        return response(['data' => \App\Models\Oil::query()->where('oil_brand_id', $oilBrand)->active()->get()]);
    });

    Route::get('/agents/search', function(Request $request){
        $query = $request->input('query');
        $searchResults = Agent::where('name', 'like', '%' . $query . '%')->get();

        return response()->json($searchResults);
    });
});
