<?php

namespace App\Http\Livewire\Admin\CarBrand;

use App\Models\CarBrand;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $status;
    public $message;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        //count page = 15 in first page the counter start from 1 and then in next page
        $this->page_title = __('site.car_brands');

    }

    public function updatedPage()
    {
        // Calculate the row number for the first record on the current page
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return CarBrand::query()
            ->when(!empty($this->status), function ($query) {
                $this->status == 'active' ? $query->whereIsActive(1) : $query->whereIsActive(0);
            })
            ->when(!empty($this->name), function ($query) {
                $query->where(function ($query) {
                    $query->where('name_ar', 'LIKE', '%' . $this->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $this->name . '%');
                });
            })
            ->latest()
            ->paginate();
    }

    public function resetData()
    {
        $this->reset(['name', 'status']);
    }

    public function destroy(CarBrand $carBrand)
    {
       $name =  app()->getLocale() =='ar' ?$carBrand->name_ar : $carBrand->name_en;
        if($carBrand->carCylinders()->count() > 0 || $carBrand->carModules()->count() > 0) {
            $this->message = __('site.can_not_delete') . ' ( ' . $name . ' ) ';

        }else{
            $carBrand->delete();
        }
    }

    public function toggleStatus(CarBrand $carBrand)
    {
        $carBrand->update(['is_active' => !$carBrand->is_active]);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.car-brands.index', compact('records'))->layout('layouts.admin');
    }
}
