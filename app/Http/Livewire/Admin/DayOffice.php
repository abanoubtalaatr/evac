<?php

namespace App\Http\Livewire\Admin;

use App\Http\Controllers\Admin\SendEmailController;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;
use function App\Helpers\canCloseDay;
use function App\Helpers\checkDayClosed;
use function App\Helpers\checkDayRestart;
use function App\Helpers\checkDayStart;
use function App\Helpers\currentDayForOffice;
use function App\Helpers\LastDayInExistDatabase;


class DayOffice extends Component
{
    public $page_title;
    public $disabledButtonDayStart = false;
    public $disabledButtonDayEnd = false;
    public $disabledButtonDayRestartDay = false;
    public $message = '';
public  $email,
$showSendEmail = false,
$showSendEmailButton= false, $agent;

    public function mount()
    {
        $this->page_title = __('admin.day_office');

        if(LastDayInExistDatabase()->day_status == '2') {//restart
            $this->disabledButtonDayStart = true;
            $this->disabledButtonDayEnd = false;
            $this->disabledButtonDayRestartDay  = true;
        }elseif (LastDayInExistDatabase()->day_status== '1'){ //start
            $this->disabledButtonDayStart = true;
            $this->disabledButtonDayRestartDay = false;
            $this->disabledButtonDayEnd = false;
        } elseif (LastDayInExistDatabase()->day_status== '0'){ //closed
            $this->disabledButtonDayStart = false;
            $this->disabledButtonDayRestartDay = false;
            $this->disabledButtonDayEnd = true;
        }


    }

    public function toggleShowModal()
    {
        $this->email = null;
        $this->showSendEmail = !$this->showSendEmail;
        $this->message = null;
    }

    public function startDay()
    {
        \App\Models\DayOffice::query()->create([
            'admin_id' => auth('admin')->id(),
            'office_id' => 1,
            'day_start' => Carbon::today(),
            'start_time' => Carbon::now()->format('H:i:s'),
            'end_time' => null,
            'day_status' => '1',
        ]);

       return redirect()->to(route('admin.day_office'));
    }

    public function sendEmail(Request $request)
    {

        $request->merge([
            'email' => $settings->email??"hala@ddilb.com",
            'className' => "App\\Http\\Controllers\\Admin\\Reports\\DailyReport\\PrintController",
        ]);
        (new SendEmailController())->send($request);
        return redirect()->to(route('admin.day_office'));

    }

    public function endDay(Request $request)
    {
        $officeDay = currentDayForOffice(1);

        if(!checkDayStart(1)){
            $this->message = 'Please start your day first';
            return ;
        }

        if(canCloseDay(1)){
            $settings = Setting::query()->first();

            $emails = explode( ',',$settings->email);
            foreach($emails as $email){
                $request->merge([
                    'email' => $email,
                    'className' => "App\\Http\\Controllers\\Admin\\Reports\\DailyReport\\PrintController",
                ]);
                (new SendEmailController())->send($request);
            }


            $officeDay->update([
                'end_time' => Carbon::now()->format('H:i:s'),
                'day_status' => "0",
                'end_admin_id' => auth('admin')->id()
            ]);
            return redirect()->to(route('admin.day_office'));
        }
        $this->message = "Please appraise applications";
    }

    public function restartDay()
    {
        $officeDay = currentDayForOffice(1);

        $officeDay->update([
            'restart_at' => Carbon::now()->format('H:i:s'),
            'day_status' => "2",
            'restart_admin_id' => auth('admin')->id(),
        ]);
        return redirect()->to(route('admin.day_office'));
    }

    public function render()
    {
        return view('livewire.admin.day-office')->layout('layouts.admin');
    }
}
