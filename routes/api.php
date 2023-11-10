<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'acceptLocale'], function () {
    Route::get('oil-brands/{oilBrand}', function ($oilBrand){
        return response(['data' => \App\Models\Oil::query()->where('oil_brand_id', $oilBrand)->active()->get()]);
    });
});
