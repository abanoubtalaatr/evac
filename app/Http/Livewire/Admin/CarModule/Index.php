<?php

namespace App\Http\Livewire\Admin\CarModule;

use App\Models\CarBrand;
use App\Models\CarModule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $status;
    public $brand;
    public $carBrands;
    public $name_ar;
    public $name_en;
    public $perPage =10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.car_modules');
        $this->carBrands = CarBrand::query()->where('is_active',1 )->get();
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return CarModule::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->where('is_active', 1) : $query->where('is_active', 0);
            })
            ->when(!empty($this->brand), function ($query) {
                return $query->where('car_brand_id', $this->brand);
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
        $this->reset(['name', 'status', 'brand']);
    }

    public function toggleStatus(CarModule $carModule)
    {
        $carModule->update(['is_active' => !$carModule->is_active]);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.car-modules.index', compact('records'))->layout('layouts.admin');
    }
}
