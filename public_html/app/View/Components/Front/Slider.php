<?php

namespace App\View\Components\Front;

use App\Models\Slider as SliderModel;
use Illuminate\View\Component;

class Slider extends Component
{
    public $slides;

    public function __construct()
    {
        $this->slides = SliderModel::latest()->where('is_active', 1)->get();
    }


    public function render()
    {
        return view('components.front.slider');
    }
}
