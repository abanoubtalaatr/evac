<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;

class HomeController extends Controller{

    public function index()
    {
        $sliders = Slider::query()->active()->get();
        $settings = Setting::query()->first();
        $about = Page::query()->find(10);
        $service = Page::query()->find(11);
        $services = Service::query()->active()->get();
        $testmoinals = Opinion::query()->active()->get();
        return view('welcome', compact('sliders', 'settings', 'about', 'service', 'services','testmoinals'));
    }
}
