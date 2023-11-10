<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Front\ContactUs;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Admin\Pages\Edit as PagesEdit;
use App\Http\Livewire\Admin\Pages\Index as PagesIndex;
use App\Http\Livewire\Admin\Settings as SettingsIndex;
use App\Http\Livewire\Admin\Slider\Edit as SliderEdit;
use App\Http\Livewire\Admin\Pages\Create as PagesCreate;
use App\Http\Livewire\Admin\Pages\Delete as PagesDelete;
use App\Http\Livewire\Admin\Slider\Index as SliderIndex;
use App\Http\Livewire\Admin\Slider\Create as SliderCreate;
use App\Http\Livewire\Admin\Slider\Delete as SliderDelete;
use App\Http\Livewire\Admin\Role\Index as RoleIndex;
use App\Http\Livewire\Admin\Role\Edit as RoleEdit;
use App\Http\Livewire\Admin\Role\Create as RoleCreate;
use App\Http\Livewire\Admin\Admins\Index as AdminIndex;
use App\Http\Livewire\Admin\Admins\Edit as AdminEdit;
use App\Http\Livewire\Admin\Admins\Create as AdminCreate;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {

//    Route::get('/', [HomeController::class, 'index'])->name('homepage');
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

            Route::get('day-office', \App\Http\Livewire\Admin\DayOffice::class)->name('day_office');
            Route::get('travel-agents', App\Http\Livewire\Admin\TravelAgent\Index::class)->name('travel_agents');

            Route::get('settings', SettingsIndex::class)->name('settings');

            Route::get('notifications', \App\Http\Livewire\Admin\Notifications::class )->name('notifications');
        });
    });
});


require __DIR__ . '/website.php';
Route::get('send-sms', function (){
    $data = [
        "userName" => env('MESGAT_USER_NAME'),
        "password" => env('MESGAT_PASSWORD'),
        "userSender" => env('MESGAT_SENDER_NAME'),
        "numbers" => "503185050",
        "apiKey" => env('MESGAT_KEY'),
        "msg" => "Verification Code:1234 ",
        "msgEncoding" => "UTF8",
    ];
    $client = new \GuzzleHttp\Client();
    $res = $client->request('POST', 'https://www.msegat.com/gw/sendsms.php', [
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Accept-Language' => app()->getLocale() == 'ar' ? 'ar-Sa' : 'en-Uk',
        ],
        'body' => json_encode($data),
    ]);

    if ($res) {
        $data = json_decode($res->getBody()->getContents());
        dd($data);
        if (isset($data->code)) {
            //----- if code == 1 => Success, otherwise failed.
            $code = $data->code;
            $message = $data->message;

            return $code == 1 ? 1 : $code;
            // return response()->json(['code'=> $code,'message' => __($message)]);
        }
        return 0;
    }
    return 0;
});
