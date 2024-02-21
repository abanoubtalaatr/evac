<?php

namespace App\Http\Livewire\Admin\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use Livewire\Component;
use function App\Helpers\rejectOrderNotificationStoreInDatabase;

class Reject extends Component
{

    public function mount(Order $order, Request $request)
    {
        $order->update(['reject_reason' => $request->input('reject_reason'), 'status' => 'reject']);
        $order->notify(new \App\Notifications\RejectOrderNotification($order, $order->user, $request->input('reject_reason')));
        rejectOrderNotificationStoreInDatabase($order->user->id, $order->id,$request->input('reject_reason'));
        return redirect()->route('admin.orders');
    }
}
