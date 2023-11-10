<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyContactRequest;

class ContactController extends Controller{
    public function index(){
        return view('admin.contact.index');
    }

    public function show(Contact $contact){
        $data = [
            'page_title'=>($contact->status=='unreplied')? __('site.reply') : __('site.details'),
            'record'=>$contact
        ];
        return view('admin.contact.show',$data);
    }

    public function update(ReplyContactRequest $request, Contact $contact){
        $data = ['admin_id'=>auth('admin')->id(),'status'=>'replied'];
        $contact->update(array_merge($request->validated(),$data));
        return redirect()->to(route('admin.contact_us'))->withSuccessMessage(__('site.saved'));
    }
}
