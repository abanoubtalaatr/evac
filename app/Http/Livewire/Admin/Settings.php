<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Arr;
use App\Http\Livewire\Traits\ValidationTrait;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use ValidationTrait;
    use WithFileUploads;

    public $form, $page_title, $settings, $logo;
    public $progress = 0, $latitude, $longitude;

    public function mount()
    {
        $this->page_title = __('messages.settings');
        $this->settings = Setting::first();
        $this->form = Arr::except($this->settings->toArray(), ['id', 'created_at', 'updated_at']);
       
        if($this->settings->logo){
            $this->form['logo'] = url('uploads/pics/'. $this->settings->logo);
        }

    }
    public function update()
    {
        $this->validate();

        $this->form['logo'] =
            $this->logo?
                $this->logo->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->logo->extension(),'public') : $this->settings->logo;

        $this->settings->update($this->form);

        return redirect()->to(route('admin.settings'))->with('success_message', __('site.saved'));
    }

    public function getRules()
    {

        return [
            'form.office_id' => 'nullable',
            'form.office_name' => 'nullable|string',
            'form.registration_no' => 'nullable|string',
            'form.mobile' => 'nullable',
            'form.vat_no' => 'nullable',
            'form.no_of_days_to_check_visa' => 'nullable',
            'form.passport_expiry_days' => 'nullable',
            'form.vat_rate' => 'nullable|string',
            'form.address' => 'nullable|string',
        ];
    }
    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}
