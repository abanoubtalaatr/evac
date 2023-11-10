<?php

namespace App\Http\Livewire\Admin\OilBrand;

use App\Models\OilBrand;
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
        //count page = 15 in first page the counter start from 1 and then in next page
        $this->page_title = __('site.oil_brands');
    }

    public function updatedPage()
    {
        // Calculate the row number for the first record on the current page
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return OilBrand::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->whereIsActive(1) : $query->whereIsActive(0);
            })->when(!empty($this->name), function ($query) {
               $query->where(function ($query){
                   return $query->where('name_ar', 'LIKE', '%' . $this->name . '%')
                       ->orWhere('name_en', 'LIKE', '%' . $this->name . '%');
               });
            })->latest()->paginate();
    }

    public function toggleStatus(OilBrand $oilBrand)
    {
        $oilBrand->update(['is_active' => !$oilBrand->is_active]);
    }

    public function resetData()
    {
        $this->reset(['status', 'name']);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.oil-brands.index', compact('records'))->layout('layouts.admin');
    }
}
