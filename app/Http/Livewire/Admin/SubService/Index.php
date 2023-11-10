<?php

namespace App\Http\Livewire\Admin\SubService;

use App\Models\CarBrand;
use App\Models\CarCylinder;
use App\Models\CarModule;
use App\Models\SubService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $status;
    public $name_ar;
    public $name_en;
    public $perPage =10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.sub_services');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return SubService::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->whereIsActive(1) : $query->whereIsActive(0);
            })->when(!empty($this->name), function ($query) {
                return $query->where(function($query){
                    $query->where('name_ar', 'LIKE', '%' . $this->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $this->name . '%');
                });
            })->latest()->paginate();
    }

    public function resetData()
    {
        $this->reset(['status', 'name']);
    }

    public function toggleStatus(SubService $subService)
    {
        $subService->update(['is_active' => !$subService->is_active]);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.sub-services.index', compact('records'))->layout('layouts.admin');
    }
}
