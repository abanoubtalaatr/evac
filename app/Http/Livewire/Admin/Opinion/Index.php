<?php

namespace App\Http\Livewire\Admin\Opinion;

use App\Models\Opinion;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $is_active, $name, $page_title, $status;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->page_title = __('site.opinions');
    }

    public function getRecords()
    {
        return Opinion::query()
            ->when(!empty($this->status), function ($query) {
                return $this->status == 'active' ? $query->where('is_active', 1) : $query->where('is_active', 0);
            })
            ->when($this->name, function ($query) {
                return $query->where('name', 'LIKE', '%' . $this->name . '%');
            })
            ->latest()
            ->paginate();
    }

    public function toggleStatus(Opinion $opinion)
    {
        if ($opinion->is_active == 1) {
            $opinion->update(['is_active' => '0']);
        } else {
            $opinion->is_active = '1';
            $opinion->save();
        }
    }

    public function resetData()
    {
        $this->reset(['status', 'name']);
    }

    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.opinion.index', compact('records'))->layout('layouts.admin');
    }
}
