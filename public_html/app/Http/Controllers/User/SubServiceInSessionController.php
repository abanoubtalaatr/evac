<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubService;
use Illuminate\Http\Request;

class SubServiceInSessionController extends Controller
{
    public $subServicesIds;

    public function __invoke(Request $request)
    {
        $price = 0;
// Retrieve the session data
        $subServicesIds= session()->get('subServicesIds')?? [];

// Add or delete items from the array
        $subServiceId = $request->input('subServiceId');
        $subService = SubService::find($subServiceId);

        if($request->isChecked == 'true'){
            $subServicesIds[$subServiceId] = $subServiceId;
        }
//        if ($subService) {
//            if (in_array($subServiceId, $subServicesIds)) {
//                dd('delete');
//                // SubService is already selected, remove it and subtract its price
//                $key = array_search($subServiceId, $subServicesIds);
//                unset($subServicesIds[$key]);
//                $price -= $subService->price;
//            } else {
//                // SubService is not selected, add it and add its price
//
//                $price += $subService->price;
//            }
//        }

// Save the session data
        dd($subServicesIds);
        session()->put('subServicesIds', $subServicesIds);
        dd(session()->get('subServicesIds'));
    }
}
