<?php

namespace App\Http\Livewire\Admin\CarCylinder;

use App\Models\CarBrand;
use App\Models\CarCylinder;
use App\Models\CarModule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $status;
    public $brand;
    public $module;
    public $carBrands;
    public $name_ar;
    public $name_en;
    public $perPage =10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.car_cylinder');
        $this->carBrands = CarBrand::query()->where('is_active',1 )->get();
        $this->carModules = CarModule::query()->where('is_active',1 )->get();
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function destroy(CarCylinder $carCylinder)
    {
        $name =  app()->getLocale() =='ar' ?$carCylinder->name_ar : $carCylinder->name_en;
        if($carCylinder->carCylinders()->count() > 0 || $carCylinder->carModules()->count() > 0) {
            $this->message = __('site.can_not_delete') . ' ( ' . $name . ' ) ';

        }else{
            $carCylinder->delete();
        }
    }
    public function getRecords()
    {
        return CarCylinder::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->whereIsActive(1) : $query->whereIsActive(0);
            })->when(!empty($this->brand), function ($query) {
                return $query->where('car_brand_id', $this->brand);
            })->when(!empty($this->module), function ($query) {
                return $query->where('car_module_id', $this->module);
            })->when(!empty($this->name), function ($query) {
                return $query->where(function($query){
                    $query->where('name_ar', 'LIKE', '%' . $this->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $this->name . '%');
                });
            })->latest()->paginate();
    }

    public function resetData()
    {
        $this->reset(['status', 'brand', 'module', 'name']);
    }

    public function toggleStatus(CarCylinder $carCylinder)
    {
        $carCylinder->update(['is_active' => !$carCylinder->is_active]);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.car-cylinders.index', compact('records'))->layout('layouts.admin');
    }
}
