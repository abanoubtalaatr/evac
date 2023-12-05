<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\DeletedApplication;
use App\Models\Service;
use App\Models\ServiceTransaction;
use App\Models\User;
use App\Models\VisaProvider;
use App\Models\VisaType;
use function App\Helpers\isOwner;

class DashboardController extends Controller{
    public function index(){
        if(isOwner()){
            $agent_count = Agent::query()->count();
        }else{
            $agent_count = Agent::query()->owner()->count();

        }
        $new_applications = Application::query()->where('status', 'new')->count();
        $appraised_applications = Application::query()->where('status', 'appraised')->count();
        $services = Service::query()->count();
        $serviceTransactions = ServiceTransaction::query()->count();
        $visaProviders= VisaProvider::query()->count();
        $visaTypes = VisaType::query()->count();
        $applicants = Applicant::query()->count();
        $deletedApplications = DeletedApplication::query()->count();
        return view('admin.dashboard.home',
            compact( 'agent_count', 'new_applications', 'appraised_applications', 'services', 'serviceTransactions', 'visaProviders', 'visaTypes', 'applicants', 'deletedApplications'));
    }
}
