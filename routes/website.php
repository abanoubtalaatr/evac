<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\NotificationController;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {

    Route::get('/', [\App\Http\Controllers\User\HomeController::class,'index'])->name('home');
    Route::view('test', 'test');
    Route::get('page/{page}', \App\Http\Livewire\User\Page::class)->name('page');
    Route::get('/services', \App\Http\Livewire\User\Service\Index::class)->name('services.index');
    Route::get('/services/{service}', \App\Http\Livewire\User\Service\Show::class)->name('services.show');
    Route::get('/about', \App\Http\Livewire\User\About::class)->name('about');

    Route::get('contact', \App\Http\Livewire\User\ContactUs::class)->name('contact');

    Route::group(['as' => 'user.', 'prefix' => 'user/'], function () {
        Route::get('login', \App\Http\Livewire\User\Auth\Login::class)->name('login')->middleware('checkUserIsLogin');
        Route::get('verify-code/{mobile}', \App\Http\Livewire\User\Auth\VerifyCode::class)->middleware('checkUserIsLogin')->name('verify_code');
        Route::get('sign-up/{mobile}', \App\Http\Livewire\User\Auth\Signup::class)->middleware('checkUserIsLogin')->name('signup');

        Route::group(['middleware' => 'auth'], function () {
            Route::get('notifications', \App\Http\Livewire\User\Notification::class)->name('notifications');
            Route::get('logout', \App\Http\Livewire\User\Auth\Logout::class)->name('logout');
            Route::get('profile', \App\Http\Livewire\User\Auth\Profile::class)->name('profile');

            Route::get('cars/create', App\Http\Livewire\User\Car\Create::class)->name('cars.create');
            Route::get('cars', App\Http\Livewire\User\Car\Index::class)->name('cars');
            Route::get('cars/{car}', App\Http\Livewire\User\Car\Edit::class)->name('cars.edit');
            Route::get('cars/{car}/delete', App\Http\Livewire\User\Car\Delete::class)->name('cars.delete');

            Route::get('services/create', App\Http\Livewire\User\Service\Create::class)->name('services.create');

            //orders
            Route::get('orders', App\Http\Livewire\User\Order\Index::class)->name('orders');
            Route::get('orders/{order}', App\Http\Livewire\User\Order\Show::class)->name('orders.show');

           Route::post('update-price',\App\Http\Controllers\User\SubServiceInSessionController::class )->name('update-price');

           Route::get('opinion/{order}', App\Http\Livewire\User\Opinion\Create::class)->name('opinion');

        });/*authenticated users*/


    });
});
