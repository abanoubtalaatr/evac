<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Slider;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller{
    public function index(){
       return view('welcome');
    }

    public function showPage(Page $page){

        $page_templates = [
            3=>'about',
            4=>'privacy',
            8=>'privacy',
            5=>'advertiser',
            6=>'benefits',
            7=>'advertiser',
            9=>'advertiser'
        ];


        $view_name = isset($page_templates[$page->id])? $page_templates[$page->id] : 'about';

        $settings =Setting::first();
        $data = [
            'mission'=>$settings->{"mission_".app()->getLocale()},
            'vision'=>$settings->{"vision_".app()->getLocale()},
            'page'=>$page
        ];

        return view('new_front.front.'.$view_name,$data);
    }
}
