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

        $data['payment_method'] = 'cash';
        if(isset($this->form['createdDate'])){
            $data['created_at'] = $this->form['createdDate'];
            $data['updated_at'] = $this->form['createdDate'];
        }
        $application->update($data);

        $this->toggleConfirmPayInvoice();
    }

}
