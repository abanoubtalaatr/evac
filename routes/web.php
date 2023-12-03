<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Front\ContactUs;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Admin\Settings as SettingsIndex;
use App\Http\Livewire\Admin\Role\Index as RoleIndex;
use App\Http\Livewire\Admin\Role\Edit as RoleEdit;
use App\Http\Livewire\Admin\Role\Create as RoleCreate;
use App\Http\Livewire\Admin\Admins\Index as AdminIndex;
use App\Http\Livewire\Admin\Admins\Edit as AdminEdit;
use App\Http\Livewire\Admin\Admins\Create as AdminCreate;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Http\Request;
use App\Models\Agent;



Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {
    Route::get('/', function (){
        return redirect()->to(\route('admin.login_form'));
    })->name('homepage');
    Route::get('contact-us', ContactUs::class)->name('contact_us');
    Route::get('page/{page}', [HomeController::class, 'showPage'])->name('show_page');


    //Admin
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

        Route::get('login', \App\Http\Livewire\Admin\Auth\Login::class)->name('login_form')->middleware('checkAdminIsLogin');
        Route::get('forget-password', \App\Http\Livewire\Admin\Auth\ForgotPassword::class)->name('forgot_password');
        Route::get('verify-forget-password/{admin}', \App\Http\Livewire\Admin\Auth\VerifyForgetPasswordCode::class)
            ->name('verify_forget_password_code')
            ->middleware('checkAdminIsLogin');

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('profile', \App\Http\Livewire\Admin\Profile::class)->name('profile');
            Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');

            // admins
            Route::get('admins', AdminIndex::class)->middleware('can:Manage admins')->name('admins.index');
            Route::get('admins/{admin}/edit', AdminEdit::class)->middleware('can:Manage admins')->name('admins.edit');
            Route::get('/admins/create', AdminCreate::class)->middleware('can:Manage admins')->name('admins.create');

            //roles
            Route::get('role/index', RoleIndex::class)->middleware('can:Manage roles')->name('role');
            Route::get('role/create', RoleCreate::class)->middleware('can:Manage roles')->name('create_role');
            Route::get('role/{role}/edit', RoleEdit::class)->middleware('can:Manage roles')->name('edit_role');

            Route::get('users', \App\Http\Livewire\Admin\Users\Index::class)->middleware("can:Manage users")->name('users.index');
            Route::get('users/{user}/edit', \App\Http\Livewire\Admin\Users\Edit::class)->middleware("can:Manage users")->name('users.edit');
            Route::get('/users/create',\App\Http\Livewire\Admin\Users\Create::class )->name('create_user');

            //day office
            Route::get('day-office', \App\Http\Livewire\Admin\DayOffice::class)->name('day_office');

            //travel agents
            Route::get('travel-agents', App\Http\Livewire\Admin\TravelAgent\Index::class)->name('travel_agents');
            Route::get('travel-agents-applications', App\Http\Livewire\Admin\TravelAgent\Application::class )->name('travel_agent_applications');
            Route::get('travel-agent-payment-transactions',\App\Http\Livewire\Admin\TravelAgent\PaymentTransaction::class)->name('travel_agent_payment_transactions');
            //visa types
            Route::get('visa-types', App\Http\Livewire\Admin\VisaType\Index::class)->name('visa_types');

            //visa providers
            Route::get('visa-providers', App\Http\Livewire\Admin\VisaProvider\Index::class)->name('visa_providers');

            //applications
            Route::get('applications', App\Http\Livewire\Admin\Application\Index::class)->name('applications');
            Route::get('applications/create', App\Http\Livewire\Admin\Application\Create::class)->name('applications.store');
            Route::get('applications/edit', \App\Http\Livewire\Admin\Application\Edit::class)->name('applications.update');

            Route::get('applications-appraisal',\App\Http\Livewire\Admin\Application\Appraisal::class)->name('applications.appraisal');
            Route::get('applications-appraised',\App\Http\Livewire\Admin\Application\Appraised::class)->name('applications.appraised');

            Route::get('applications-receipt', \App\Http\Livewire\Admin\Application\Receipt::class)->name('applications.receipt');
            Route::get('applications-revise', \App\Http\Livewire\Admin\Application\Revise::class)->name('applications.revise');
            Route::get('applications-invoice/{application}', [\App\Http\Controllers\Admin\PrintApplicationController::class,'show'])->name('applications.print');
            Route::get('applications-deleted', \App\Http\Livewire\Admin\Application\DeletedApplication::class)->name('applications.deleted');
            Route::get('applications-un-paid', \App\Http\Livewire\Admin\Application\UnPaidApplication::class)->name('applications.un_paid');
            //settings
            Route::get('settings', SettingsIndex::class)->name('settings');

            //applicant
            Route::get('applicants', \App\Http\Livewire\Admin\Applicant\Index::class)->name('applicants');

            // service
            Route::get('services', \App\Http\Livewire\Admin\Service\Index::class)->name('services');

            //service Transaction
            Route::get('service-transaction', \App\Http\Livewire\Admin\ServiceTransaction\Index::class)->name('service_transactions');
            Route::get('service-transactions/{service_transaction}', [\App\Http\Controllers\Admin\ServiceTransactionController::class, 'show'])->name('service_transactions.print');
            Route::get('service-transactions-invoices', App\Http\Livewire\Admin\ServiceTransaction\Invoice::class)->name('service_transactions.invoices');

            //notifications
            Route::get('notifications', \App\Http\Livewire\Admin\Notifications::class )->name('notifications');

            Route::get('table', function (){
               return view('emails.TravelAgent.agent-applications-email');
            });

            Route::get('/agents/search', function(Request $request){
                $query = $request->input('query');
                $searchResults = Agent::where('name', 'like', '%' . $query . '%')->get();

                return response()->json($searchResults);
            });
        });
    });
});
