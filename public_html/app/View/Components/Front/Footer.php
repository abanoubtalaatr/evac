<?php

namespace App\View\Components\Front;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\View\Component;

class Footer extends Component{
    public $settings,$services;
    public function __construct(){
        $this->settings = Setting::first();
        $this->services = Page::whereType('services')->get();
    }

    public function render(){
        return view('components.front.footer');
    }
}
