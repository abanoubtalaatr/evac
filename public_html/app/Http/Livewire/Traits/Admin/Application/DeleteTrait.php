<?php
namespace App\Http\Livewire\Traits\Admin\Application;
use App\Models\Application;
use App\Models\DeletedApplication;

trait DeleteTrait
{
    public $confirmingDelete = false;
    public $deleteRecordId;
    public $reasonForDeletion;
    public function toggleConfirmDelete()
    {
        $this->reasonForDeletion = '';
        $this->confirmingDelete = !$this->confirmingDelete;
    }

    public function showDeleteConfirmation($recordId)
    {
        $this->confirmingDelete = true;
        $this->deleteRecordId = $recordId;
    }

    public function deleteApplication()
    {
        $application = Application::query()->find($this->deleteRecordId);
        $application->delete();
        $this->confirmingDelete = false;
    }

    public function validateAndDelete($recordId)
    {
        $application = Application::query()->find($this->deleteRecordId);
        $this->validate([
            'reasonForDeletion' => 'required|string|max:255', // Adjust the validation rules as needed
        ]);

        $deleteApplication = DeletedApplication::query()->create([
            'agent_id' => $application->travel_agent_id,
            'passport_no' => $application->passport_no,
            'reference_no' => $application->application_ref,
            'applicant_name' => $application->first_name . " ". $application->last_name,
            'application_create_date' => $application->created_at,
            'deletion_date' => now(),
            'user_name' => auth('admin')->user()->name,
            'office_name' => 'EVAC',
            'delete_reason' => $this->reasonForDeletion,
            'last_app_status' => $application->status,
        ]);

        $this->deleteApplication();

        $this->reasonForDeletion = '';
    }
}
