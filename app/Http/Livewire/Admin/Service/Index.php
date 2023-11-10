<?php

namespace App\Http\Livewire\Admin\Service;

use App\Models\CarModule;
use App\Models\Service;
use App\Models\SubService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $status;
    public $brand;
    public $subServices;
    public $name_ar;
    public $name_en;
    public $subService;
    public $perPage =10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.services');
        $this->subServices = SubService::query()->where('is_active',1 )->get();
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return Service::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->where('is_active', 1) : $query->where('is_active', 0);
            })
            ->when(!empty($this->subService), function ($query) {
                return $query->where('sub_service_id', $this->subService);
            })
            ->when(!empty($this->name), function ($query) {
                return $query->where(function ($query) {
                    $query->where('name_ar', 'LIKE', '%' . $this->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $this->name . '%');
                });
            })
            ->latest()
            ->paginate();
    }

    public function resetData()
    {
        $this->reset(['name', 'status', 'subService']);
    }

    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.services.index', compact('records'))->layout('layouts.admin');
    }
}
