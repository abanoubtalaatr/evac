<?php

namespace App\Http\Livewire\Admin\Oil;

use App\Models\Oil;
use App\Models\OilBrand;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $status;
    public $brand;
    public $oilBrands;
    public $name_ar;
    public $name_en;
    public $perPage =10;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.oils');
        $this->oilBrands = OilBrand::query()->where('is_active',1 )->get();
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return Oil::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->whereIsActive(1) : $query->whereIsActive(0);
            })->when(!empty($this->brand), function ($query) {
                return $query->where('oil_brand_id', $this->brand);
            })->when(!empty($this->name), function ($query) {
                $query->where(function ($query){
                    return $query->where('name_ar', 'LIKE', '%' . $this->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $this->name . '%');
                });
            })->latest()->paginate();
    }

    public function toggleStatus(Oil $oil)
    {
        $oil->update(['is_active' => !$oil->is_active]);
    }

    public function resetData()
    {
        $this->reset(['name', 'brand', 'status']);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.oil.index', compact('records'))->layout('layouts.admin');
    }
}
