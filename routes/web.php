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

            //Car brands
            Route::get('car-brands', \App\Http\Livewire\Admin\CarBrand\Index::class)->middleware('can:Manage car brands')->name('car_brands');
            Route::get('car-brands/create', \App\Http\Livewire\Admin\CarBrand\Create::class)->middleware('can:Manage car brands')->name('create_car_brands');
            Route::get('car-brands/{carBrand}/edit', \App\Http\Livewire\Admin\CarBrand\Edit::class)->middleware('can:Manage car brands')->name('edit_car_brands');

            //car modules
            Route::get('car-modules', \App\Http\Livewire\Admin\CarModule\Index::class)->middleware('can:Manage car modules')->name('car_modules');
            Route::get('car-modules/create', \App\Http\Livewire\Admin\CarModule\Create::class)->middleware('can:Manage car modules')->name('create_car_modules');
            Route::get('car-modules/{carModule}/edit', \App\Http\Livewire\Admin\CarModule\Edit::class)->middleware('can:Manage car modules')->name('edit_car_modules');

            //car cylinder
            Route::get('car-cylinders', \App\Http\Livewire\Admin\CarCylinder\Index::class)->middleware('can:Manage car cylinders')->name('car_cylinders');
            Route::get('car-cylinders/create', \App\Http\Livewire\Admin\CarCylinder\Create::class)->middleware('can:Manage car cylinders')->name('create_car_cylinders');
            Route::get('car-cylinders/{carCylinder}/edit', \App\Http\Livewire\Admin\CarCylinder\Edit::class)->middleware('can:Manage car cylinders')->name('edit_car_cylinders');

            //oil brand
            Route::get('oil-brands', \App\Http\Livewire\Admin\OilBrand\Index::class)->middleware('can:Manage oil brands')->name('oil_brands');
            Route::get('oil-brands/create', \App\Http\Livewire\Admin\OilBrand\Create::class)->middleware('can:Manage oil brands')->name('create_oil_brands');
            Route::get('oil-brands/{oilBrand}/edit', \App\Http\Livewire\Admin\OilBrand\Edit::class)->middleware('can:Manage oil brands')->name('edit_oil_brands');

            //oils
            Route::get('oils', \App\Http\Livewire\Admin\Oil\Index::class)->middleware('can:Manage oils')->name('oils');
            Route::get('oils/create', \App\Http\Livewire\Admin\Oil\Create::class)->middleware('can:Manage oils')->name('create_oils');
            Route::get('oils/{oil}/edit', \App\Http\Livewire\Admin\Oil\Edit::class)->middleware('can:Manage oils')->name('edit_oils');

            //sub services
            Route::get('sub-services', \App\Http\Livewire\Admin\SubService\Index::class)->middleware('can:Manage sub services')->name('sub_services');
            Route::get('sub-services/create', \App\Http\Livewire\Admin\SubService\Create::class)->middleware('can:Manage sub services')->name('create_sub_services');
            Route::get('sub-services/{subService}/edit', \App\Http\Livewire\Admin\SubService\Edit::class)->middleware('can:Manage sub services')->name('edit_sub_services');

            Route::get('orders', \App\Http\Livewire\Admin\Order\Index::class)->middleware('can:Manage orders')->name('orders');
            Route::get('orders/{order}', \App\Http\Livewire\Admin\Order\Show::class)->middleware('can:Manage orders')->name('order_details');
            Route::post('orders/{order}/reject', \App\Http\Livewire\Admin\Order\Reject::class)->middleware('can:Manage order details')->name('order.reject');
            //services
            Route::get('services', \App\Http\Livewire\Admin\Service\Index::class)->middleware('can:Manage services')->name('services');
            Route::get('services/create', \App\Http\Livewire\Admin\Service\Create::class)->middleware('can:Manage services')->name('create_services');
            Route::get('services/{service}/edit', \App\Http\Livewire\Admin\Service\Edit::class)->middleware('can:Manage services')->name('edit_services');

            Route::get('contact', App\Http\Livewire\Admin\Contact\Index::class)->name('contacts');
            Route::get('contact/{contact}', \App\Http\Livewire\Admin\Contact\Show::class)->name('contact.show');

            Route::get('slider/index', SliderIndex::class)->name('slider');
            Route::get('slider/create', SliderCreate::class)->name('create_slider');
            Route::get('slider/{slider}/edit', SliderEdit::class)->name('edit_slider');
            Route::get('slider/{slider}/delete', SliderDelete::class)->name('delete_slider');

            Route::get('page/index', PagesIndex::class)->name('pages.index');
            Route::get('page/create', PagesCreate::class)->name('pages.create');
            Route::get('page/{page}/edit', PagesEdit::class)->name('pages.edit');
            Route::get('page/{page}/delete', PagesDelete::class)->name('pages.delete');

            Route::get('opinions', \App\Http\Livewire\Admin\Opinion\Index::class)->middleware('can:Manage services')->name('opinions');
            Route::get('opinions/create', \App\Http\Livewire\Admin\Opinion\Create::class)->middleware('can:Manage services')->name('create_opinions');
            Route::get('opinions/{opinion}/edit', \App\Http\Livewire\Admin\Opinion\Edit::class)->middleware('can:Manage services')->name('edit_opinions');


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
