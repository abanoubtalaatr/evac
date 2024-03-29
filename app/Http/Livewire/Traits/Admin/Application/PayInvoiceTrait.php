<?php
namespace App\Http\Livewire\Traits\Admin\Application;
use App\Models\Application;
use Carbon\Carbon;

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
        $url = route('admin.applications.print', ['application' => $application->refresh()]);
        $this->emit('payInvoiceAndPrint', $url);

        $this->toggleConfirmPayInvoice();
    }

}
