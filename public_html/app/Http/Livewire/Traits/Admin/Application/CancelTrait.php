<?php
namespace App\Http\Livewire\Traits\Admin\Application;
use App\Models\Application;

trait CancelTrait
{
    public $confirmingCancel = false;
    public $cancelRecordId;

    public function toggleConfirmCanceled()
    {
        $this->confirmingCancel = !$this->confirmingCancel;
    }

    public function showCancelConfirmation($recordId)
    {
        $this->confirmingCancel = true;
        $this->cancelRecordId = $recordId;
    }
    public function canceled()
    {
        $application = Application::query()->find($this->cancelRecordId);
        $application->update(['status' => 'canceled']);
        $this->confirmingCancel = false;
    }

}
