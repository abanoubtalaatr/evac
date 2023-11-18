<?php

namespace App\Http\Livewire\Admin\Application;

use App\Http\Livewire\Traits\Admin\Application\DeleteTrait;
use App\Models\VisaType;
use Livewire\Component;

class DeletedApplication extends Component
{
    public $passport, $agent, $from, $to,$application;
    protected $listeners = [ 'showApplicationInvoice'];

    public function mount()
    {
        $this->page_title = __('admin.deleted_applications');
    }

    public function showApplicationModal($id)
    {
        $this->emit("showApplicationModal", $id);
    }

    public function getRecords()
    {
        return \App\Models\DeletedApplication::query()
            ->when(!empty($this->passport), function ($query) {
                $query->where(function ($query) {
                    $query->where('passport_no', 'like', '%'.$this->passport.'%');
                });
            })->when(!empty($this->agent), function ($query) {
                $query->whereHas('agent', function ($query){
                    $query->where('name', 'like', '%'.$this->agent.'%');
                });
            })->when(!empty($this->from) && !empty($this->to), function ($query) {
                $query->whereBetween('created_at', [$this->from, $this->to]);
            })
            ->latest()
            ->paginate();
    }

    public function resetData()
    {
        $this->reset(['passport', 'agent', 'from', 'to']);
    }


    public function render()
    {
        $records = $this->getRecords();
        return view('livewire.admin.application.deleted-application', compact('records'))->layout('layouts.admin');
    }
}
