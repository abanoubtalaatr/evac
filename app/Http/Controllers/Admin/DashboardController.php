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
use Carbon\Carbon;
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

        // Get the total applications created yesterday
        $yesterday = Carbon::yesterday();
        $today = Carbon::today();
        $firstDayOfMonth = now()->startOfMonth();
        $lastDayOfMonth = now()->endOfMonth();

        $applications_created_yesterday = Application::whereDate('created_at', $yesterday)->count();
        $applications_created_today = Application::whereDate('created_at', $today)->count();

        $serviceTransactionsYesterday = ServiceTransaction::whereDate('created_at', $yesterday)->count();
        $serviceTransactionsToday = ServiceTransaction::whereDate('created_at', $today)->count();

        $applications_created_this_month = Application::whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->count();
        $serviceTransactionsThisMonth = ServiceTransaction::whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->count();

        return view('admin.dashboard.home',
            compact(
                'agent_count',
                'new_applications',
                'appraised_applications',
                'services',
                'serviceTransactions',
                'visaProviders',
                'visaTypes',
                'applicants',
                'deletedApplications',
                'applications_created_today',
                'applications_created_yesterday',
                'serviceTransactionsYesterday',
                'serviceTransactionsToday',
                'applications_created_this_month',
                'serviceTransactionsThisMonth'
            ));
    }
}
