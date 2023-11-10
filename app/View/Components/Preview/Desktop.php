<?php

namespace App\View\Components\Preview;

use Illuminate\View\Component;

class Desktop extends Component{
    public $record;
    public function __construct($record){
        $this->record = $record;
    }

    public function render(){
        return view('components.preview.desktop');
    }
}
