<?php

namespace App\Http\Livewire\User\Service;

use App\Http\Livewire\Traits\ValidationTrait;
use App\Models\Car;
use App\Models\Oil;
use App\Models\OilBrand;
use App\Models\Order;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SubService;
use Livewire\Component;
use function App\Helpers\createDatabaseNotification;


class Create extends Component
{
    use ValidationTrait;
    public $subServices, $oilBrands, $oil_brand_id, $selectedOilBrand, $form, $services;
    public $paymentTypes = [], $selectedSubServices = [], $subServicesIds = [],  $oils = [];
    public $paymentType, $price, $latitude, $longitude, $subServiceCount;

    protected $listeners = ['carAdded', 'chooseSubService'];

    public function mount()
    {
       if(is_null(session()->get('service_id'))){
           return redirect()->to(route('services.index'));
       }

        $this->cars = auth('users')->user()->cars;
        $this->services = Service::active()->get();
        $this->form['service_id'] = session()->get('service_id');

        $this->service = Service::query()->find($this->form['service_id']);
        $this->oilBrands = OilBrand::active()->whereHas('oils')->get();
        $this->subServiceCount = $this->service->subServices->count();

        $this->getPaymentTypes();
        $this->form['payment_type'] = null;
//        $this->latitude = env('DEFAULT_LATITUDE');
//        $this->longitude = env('DEFAULT_LONGITUDE');

        $this->form['user_id'] = auth('users')->id();
        $this->price = $this->service->price;
    }

    public function carAdded($car)
    {
        $car = Car::query()->find($car['id']);
        $this->emit('appendCar', $car->load('carBrand'));
        $this->cars = auth('users')->user()->cars;
    }

    public function chooseSubService($subServiceId)
    {
        $subService = SubService::find($subServiceId);


        if ($subService) {
            if (in_array($subServiceId, $this->subServicesIds)) {
                // SubService is already selected, remove it and subtract its price
                $key = array_search($subServiceId, $this->subServicesIds);
                unset($this->subServicesIds[$key]);
                $this->price -= $subService->price;
                session()->put('price', $this->price);
            } else {
                // SubService is not selected, add it and add its price
                $this->subServicesIds[] = $subServiceId;

                $this->price += $subService->price;
                session()->put('price', $this->price);
            }
        }
        $this->emit('updatePrice', $this->price);
    }

    public function getPaymentTypes()
    {
        $settings = Setting::first();
       if($settings->pay_online ==1){
           array_push($this->paymentTypes, 'online');
       }
        if($settings->pay_cash ==1){
            array_push($this->paymentTypes, 'cash');
        }
    }

    public function getOils()
    {
        $this->oils = Oil::active()->where('oil_brand_id', $this->form['oil_brand_id'])->get();
    }

    public function store()
    {
        $this->form['latitude'] = $this->latitude;
        $this->form['longitude'] = $this->longitude;
        $this->form['total'] = $this->price;

        $this->validate();

        $order = Order::query()->create($this->form);
        $titleAr = __('site.title_new_order_ar');
        $contentAr = __('site.content_ar_new_order');
        $titleEn = __('site.title_new_order_en');
        $contentEn = __("site.content_new_order_en");

        createDatabaseNotification($order->user->id, $order->id,$titleAr,  $contentAr , $titleEn, $contentEn, 'admin',1);

        if(!empty($this->subServicesIds)){
            $order->subServices()->attach($this->subServicesIds);
        }

        if($this->form['payment_type'] =='online'){
            dd('must redirect to payment gateway');
        }

        return redirect()->to(route('user.orders'));
    }


    public function getRules()
    {
        return [
            'form.car_id' => ['required', 'exists:cars,id'],
            'form.appointment' => ['required', 'date', 'after_or_equal:today'],
            'form.payment_type' =>  ['required'],
            'form.oil_brand_id' => ['required', 'exists:oil_brands,id'],
            'form.oil_id' => ['required', 'exists:oils,id'],
            'form.service_id' => ['required', 'exists:services,id'],
            'selectedSubServices' => ['nullable','array'],
            'form.latitude' => ['required'],
            'form.longitude' => ['nullable']
        ];
    }

    public function changePayment()
    {
        $this->paymentType = $this->form['payment_type'];
        $this->emit('paymentChanged', $this->paymentType);
    }
    protected function getMessages()
    {
        return [
          'form.appointment.after_or_equal' => __('site.after_or_equal_today')
        ];
    }

    public function payOnline()
    {
        $this->store();
    }

    public function render()
    {
        return view('livewire.user.service.create')->layout('layouts.user');
    }
}
