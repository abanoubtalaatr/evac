<?php

namespace App\View\Components;

use App\Models\Setting;
use Illuminate\View\Component;

class Logo extends Component
{
    public $src;

    public function __construct($src = 'default-logo.png')
    {
        $settings = Setting::query()->first();
        if($settings){
            $src = asset('uploads/pics/'. $settings->logo);
        }else{
            $src = asset('assets_'.app()->getLocale())."/imgs/logo/logo.svg";
        }

        $this->src = $src ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.logo');
    }
}
