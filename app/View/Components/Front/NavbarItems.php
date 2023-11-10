<?php

namespace App\View\Components\Front;

use App\Models\Page;
use Illuminate\View\Component;

class NavbarItems extends Component{
    public $pages;
    public function __construct(){
        $this->pages = Page::whereType('navbar')->get();
    }


    public function render(){
        return view('components.front.navbar-items');
    }
}
