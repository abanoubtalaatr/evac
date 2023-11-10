<?php

namespace App\Http\Livewire\Admin\Order;

use App\Models\Order;
use App\Models\Service;
use App\Notifications\ChangeOrderStatusNotification;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name;
    public $status_order, $status_paid;
    public $name_ar;
    public $name_en;
    public $perPage =10;
    public $services;
    public $service;
    protected $paginationTheme = 'bootstrap';
    public $reject_reason;

    public function mount()
    {
        $this->services = Service::query()->whereIsActive(1)->get();
        $this->page_title = __('site.orders');
    }

    public function updatedPage()
    {
        $this->rowNumber = ($this->page - 1) * $this->perPage + 1;
    }

    public function getRecords()
    {
        return Order::query()
            ->when(!empty($this->status_order), function ($query) {
                return  $query->whereStatus($this->status_order);
            }) ->when(!empty($this->status_paid), function ($query) {
                return  $query->whereIsPaid($this->status_paid);
            })->when($this->service, function ($query){
                $query->where('service_id', $this->service);
            })
            ->latest()->orderBy('status')
            ->paginate();
    }

    public function resetData()
    {
        $this->reset(['status_order', 'status_paid', 'service']);
    }

    public function acceptOrder(Order $order)
    {
        $order->status = 'approved';
        $order->save();
        $order->user->notify(new ChangeOrderStatusNotification($order, $order->user));
        $this->loading = false;
        return redirect()->route('admin.orders');

        //send sms to user
    }

    public function finishOrder(Order $order)
    {

        $order->status = 'finished';
        $order->save();
        $order->user->notify(new ChangeOrderStatusNotification($order, $order->user));

        $this->loading = false;
        return redirect()->route('admin.orders');
    }

    public function getRules()
    {
        return [
            'reject_reason' => 'required|string'
        ];
    }
    public function render()
    {
        $records = $this->getRecords();

        return view('livewire.admin.orders.index', compact('records'))->layout('layouts.admin');
    }
}
