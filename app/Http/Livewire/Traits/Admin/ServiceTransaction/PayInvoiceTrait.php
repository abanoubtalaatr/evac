<?php
namespace App\Http\Livewire\Traits\Admin\ServiceTransaction;
use App\Models\Application;
use App\Models\ServiceTransaction;

trait PayInvoiceTrait
{
    public $confirmingPayInvoice = false;
    public $payInvoiceRecordId;
    public $createDate;

    public function toggleConfirmPayInvoice()
    {
        $this->confirmingPayInvoice = !$this->confirmingPayInvoice;
    }

    public function showPayInvoiceConfirmation($recordId, $date = null)
    {
        $this->confirmingPayInvoice = true;
        $this->payInvoiceRecordId = $recordId;
        $this->createDate = $date;
    }
    public function payInvoice()
    {
        $serviceTransaction = ServiceTransaction::query()->find($this->payInvoiceRecordId);


        $data['payment_method'] = 'cash';
        if(isset($this->form['createDate'])){
            $data['created_at'] = $this->form['createDate'];
            $data['updated_at'] = $this->form['createDate'];
        }
        $url = route('admin.service_transactions.print', ['service_transaction' => $this->payInvoiceRecordId]);
        $this->emit('payInvoiceAndPrint', $url);
        $serviceTransaction->update($data);
        $this->toggleConfirmPayInvoice();;
    }

}
