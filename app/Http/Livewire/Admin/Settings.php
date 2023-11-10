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

    public $form, $page_title, $settings, $pay_cash, $pay_online, $mission_image, $objective_image, $vision_image, $video_url;
    public $progress = 0, $latitude, $longitude;

    public function mount()
    {

        $this->page_title = __('messages.settings');
        $this->settings = Setting::first();
        $this->form = Arr::except($this->settings->toArray(), ['id', 'created_at', 'updated_at']);
        $this->form['mission_image'] = url('uploads/pics/'. $this->settings->mission_image);
        $this->form['vision_image'] = url('uploads/pics/'. $this->settings->vision_image);
        $this->form['objective_image'] = url('uploads/pics/'. $this->settings->objective_image);
        $this->form['vision_image'] = url('uploads/pics/'. $this->settings->vision_image);
        $this->form['video_url'] = url('uploads/pics/'. $this->settings->video_url);
        $this->latitude = $this->settings->lat ?? env('DEFAULT_LATITUDE');
        $this->longitude = $this->settings->lng ?? env('DEFAULT_LONGITUDE');

    }

    public function updatePayCash()
    {
        if($this->form['pay_cash']) {
            $this->settings->update(['pay_cash' =>1]);
        }else{
            $this->settings->update(['pay_cash' =>0]);

        }
    }

    public function updateCoordinates($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function updatePayOnline()
    {
        if($this->form['pay_online']) {
            $this->settings->update(['pay_online' =>1]);
        }else{
            $this->settings->update(['pay_online' =>0]);

        }
    }

    public function update()
    {
        $this->validate();

        $this->form['mission_image'] =
            $this->mission_image?
                $this->mission_image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->mission_image->extension(),'public') : $this->settings->mission_image;

        $this->form['vision_image'] =
            $this->vision_image?
                $this->vision_image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->vision_image->extension(),'public') : $this->settings->vision_image;

        $this->form['objective_image'] =
            $this->objective_image?
                $this->objective_image->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->objective_image->extension(),'public') : $this->settings->objective_image;

        $this->form['video_url'] =
            $this->video_url?
                $this->video_url->storeAs(date('Y/m/d'),Str::random(50).'.'.$this->video_url->extension(),'public') : $this->settings->video_url;

        $this->form['lat'] = $this->latitude;
        $this->form['lng'] = $this->longitude;

        $this->settings->update($this->form);

        return redirect()->to(route('admin.settings'))->with('success_message', __('site.saved'));
    }

    public function getRules()
    {

        return [
            'form.email' => 'email',
            'form.address' => 'string|min:10|max:200',
            'form.address_ar' => 'string|min:10|max:200',
            'form.mobile' => 'numeric',
            'form.lat' => 'numeric',
            'form.lng' => 'numeric',
            'form.facebook' => 'string|min:10|max:1000',
            'form.instagram' => 'string|min:10|max:1000',
            'form.twitter' => 'string|min:10|max:1000',
            'form.customer_service_number' => 'required|string|max:20',
            'form.brief_ar' => 'required|string:min:3|max:1000',
            'form.brief_en' => 'required|string:min:3|max:1000',
            'form.vision_ar' => 'nullable|string|min:3|max:5000',
            'form.vision_en' => 'nullable|string|min:3|max:5000',
            'form.mission_ar' => 'nullable|string|min:3|max:5000',
            'form.mission_en' => 'nullable|string|min:3|max:5000',
            'form.objective_ar' => 'nullable|string|min:3|max:5000',
            'form.objective_en' => 'nullable|string|min:3|max:5000',
            'form.number_of_experience' => 'nullable|integer',
            'form.video_title_ar' => 'nullable|string|min:3|max:100',
            'form.video_title_en' => 'nullable|string|min:3|max:100',
            'form.video_description_ar' => 'nullable|string|min:3|max:300',
            'form.video_description_en' => 'nullable|string|min:3|max:300',
        ];
    }
    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}
