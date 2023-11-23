<?php
namespace App\Http\Livewire\Traits\Admin\ServiceTransaction;
use App\Models\Application;
use App\Models\ServiceTransaction;

trait PayInvoiceTrait
{
    public $confirmingPayInvoice = false;
    public $payInvoiceRecordId;

    public function toggleConfirmPayInvoice()
    {
        $this->confirmingPayInvoice = !$this->confirmingPayInvoice;
    }

    public function showPayInvoiceConfirmation($recordId)
    {
        $this->confirmingPayInvoice = true;
        $this->payInvoiceRecordId = $recordId;
    }
    public function payInvoice()
    {
        $serviceTransaction = ServiceTransaction::query()->find($this->payInvoiceRecordId);

        $serviceTransaction->update(['payment_method' => 'cash']);
    }

}
