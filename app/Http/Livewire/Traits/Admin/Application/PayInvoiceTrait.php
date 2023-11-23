<?php
namespace App\Http\Livewire\Traits\Admin\Application;
use App\Models\Application;

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
        $application = Application::query()->find($this->payInvoiceRecordId);

        $application->update(['payment_method' => 'cash']);
    }

}
