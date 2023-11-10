<?php

namespace App\Http\Controllers\Admin;
use App\Models\Task;
use App\Http\Controllers\Controller;

class TaskController extends Controller{

    public function index(){
        return view('admin.tasks.index');
    }


    public function create(){
        return view('admin.tasks.create');
    }



    public function edit(Task $task){
        return view('admin.tasks.edit',['record'=>$task]);
    }

}
