<div>
    <section class="head_banner">
        <div class="layer">

        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>@lang('site.create_service')</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="steps_wrapper" wire:ignore>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="step-app" id="demo">
                        <ul class="step-steps">
                            <li data-step-target="step1" class="active"> <span>1</span>
                                <p>@lang('site.choose_car') </p>
                            </li>
                            <li data-step-target="step2"><span> 2</span>
                                <p>@lang("site.choose_appointment")</p>
                            </li>
                            <li data-step-target="step3"><span>3</span>
                                <p>@lang("site.choose_address")</p>
                            </li>
                            @if($subServiceCount > 0)
                                <li data-step-target="step4"><span> 4</span>
                                    <p>@lang("site.sub_services")</p>
                                </li>
                            @endif
                            <li data-step-target="step5"> <span>5</span>
                                <p>@lang("site.oil_company")</p>
                            </li>
                            <li data-step-target="step6" class="last_step"> <span>6</span>
                                <p>@lang("site.payment")</p>
                            </li>
                        </ul>
                        <form class="step-content border-0 p-0" >
                            <div class="step-tab-panel active" data-step="step1">
                                <div class="row step_tab">
                                    <div class="col-md-4">
                                        <h3><img src="{{asset('website/assets/images/steps/car.svg')}}" alt="">@lang('site.choose_car')</h3>

                                        <select  id="car-select" wire:ignore wire:model="form.car_id" class="form-select my-3" aria-label="Default select example">
                                            <option selected="">@lang('site.select')</option>
                                            @foreach($cars as $car)
                                                <option value="{{$car->id}}">{{$car->carBrand->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('form.car_id')<p style='color:red'> {{$message}} </p>@enderror

                                        <button class="btn btn-1" onclick="toggleDiv()" type="button">
                                            <img src="{{asset('website/assets/images/steps/Plus.svg')}}" alt="">@lang('site.add_new_car')
                                        </button>
                                    </div>
                                    <!-- add car card -->
                                    <div id="myDiv" class="mt-3 hidden" >
                                        <div class="row">
                                            @livewire("user.car.store-car")
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="step-tab-panel" data-step="step2">
                                <div class="row step_tab">
                                    <div class="col-md-4">
                                        <h3><img src="{{asset('website/assets/images/steps/Time Square.svg')}}" alt="">اختيار الموعد</h3>

                                        <div class="my-3">
                                            <input wire:model.defer="form.appointment" type="date" class="form-control " placeholder="0000">
                                        </div>
                                        @error('form.appointment')<p style='color:red'> {{$message}} </p>@enderror


                                    </div>
                                </div>
                            </div>
                            <div class="step-tab-panel" data-step="step3">
                                <div class="row step_tab">
                                    <div class="col-md-6">
                                        <h3><img src="{{asset('website/assets/images/steps/Location.svg')}}" alt="">@lang('site.choose_address')</h3>


                                        <div class="my-3">
                                            <div class="input-group  border rounded">
                                                <input id="search-input" class="form-control mb-3 bg-transparent border-0 pac-target-input"  type="text" placeholder="Search for places">
                                            </div>
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($subServiceCount)
                            <div class="step-tab-panel" data-step="step4">
                                <div class="row step_tab">
                                    <div class="col-md-12">
                                        <h3>@lang('site.services')</h3>
                                        <div class="row">
                                            <div class="my-3 col-6">
                                                <h3 class="my-2">@lang('site.sub_services')</h3>
                                                @livewire("user.service.sub-service")
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="step-tab-panel" data-step="step5" >
                                <div class="row step_tab">
                                    <div class="col-md-4">
                                        <h3>@lang('site.choose_brand_oil')</h3>

                                        <select id="oilBrandSelect" class="form-select my-3" wire:model.defer="form.oil_brand_id"  aria-label="Default select example">

                                        <option selected="">@lang('site.select')</option>
                                          @foreach($oilBrands as $brand)
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        </select>

                                        @error('form.oil_brand_id')<p style='color:red'> {{$message}} </p>@enderror


                                        <h3>@lang('site.choose_oil')</h3>

                                        <select class="form-select my-3" id="oils" wire:model.defer="form.oil_id" aria-label="Default select example">
                                            <option selected="">@lang('site.select')</option>

                                        </select>
                                        @error('form.oil_id')<p style='color:red'> {{$message}} </p>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="step-tab-panel" data-step="step6">
                                <div class="row step_tab">
                                    <div class="col-md-12">
                                        <div class="d-lg-flex justify-content-between align-items-center">
                                            <div class="w-25 w-sm-100">
                                                <h3>@lang('site.payment_type')  </h3>

                                                <select wire:model.defer="form.payment_type" wire:change="changePayment" class="form-select my-3" aria-label="Default select example">
                                                    <option >@lang("site.select")</option>

                                                @foreach($paymentTypes as $payment)
                                                    <option value="{{$payment}}">@lang("site.$payment")</option>
                                                    @endforeach
                                                </select>
                                                @error('form.payment_type')<p style='color:red'> {{$message}} </p>@enderror

                                            </div>
                                            <div class="" wire.target="changeSubService">
                                                <h3> @lang('site.total_service_cost')  </h3>
                                                <div class="label_serv_price" >
                                                   <span id="priceServices" class="mx-1"> {{$price}}</span>  @lang('site.ryal')
                                                </div>
                                            </div>
                                        </div>


                                        <button id="buttonToCompletePayment" wire:click="store" class="btn btn-1 px-lg-5 hidden" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalpay">
                                           @lang('site.complete_payment')
                                        </button>

                                    </div>

                                </div>
                            </div>

                        </form>
                        <div class="step-footer d-md-flex justify-content-between">
                            <button data-step-action="prev" class="step-btn" style="display: none;">@lang("site.previous")</button>
                            <button data-step-action="next" class="step-btn">@lang("site.next")</button>
                            <button wire:click="store" data-step-action="finish" class="step-btn" style="display: none;">@lang('site.save')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container mb-2">
        <div class="container mb-2">
            @if ($errors->any() && empty($wire->value))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="{{asset('website/assets/js/jquery.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5LCZLdS1cq7-buPkwyLDjcARlFjiljYk&libraries=places&callback=initMap" async defer></script>

<script>
    function initMap() {
        var defaultLatitude = {{env('DEFAULT_LATITUDE')??12}};
        var defaultLongitude = {{env('DEFAULT_LONGITUDE')??23}};

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: defaultLatitude, lng: defaultLongitude}, // San Francisco coordinates
            zoom: 13
        });
        var input = document.getElementById('search-input');
        var searchBox = new google.maps.places.SearchBox(input);

        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length === 0) {
                return;
            }

            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log('Returned place contains no geometry');
                    return;
                }

                var marker = new google.maps.Marker({
                    map: map,
                    title: place.name,
                    position: place.geometry.location
                });

                bounds.extend(place.geometry.location);
            });

            map.fitBounds(bounds);
            var selectedPlace = places[0];
             @this.latitude = selectedPlace.geometry.location.lat();
             @this.longitude = selectedPlace.geometry.location.lng();
        });

        // Add click event listener on the map
        map.addListener('click', function(event) {
            @this.latitude = event.latLng.lat();
            @this.longitude = event.latLng.lng();
        });
    }
</script>

<script>
    $(document).ready(function() {
        Livewire.on('appendCar', function(car) {
            var option = $('<option>', {
                value: car.id,
                text: car.car_brand.name_ar
            });
            $('#car-select').append(option);
        });

        //update the price
        Livewire.on('updatePrice', function (price){
           $('#priceServices').html(price)
        });
        Livewire.on('paymentChanged', function (payment){
          if(payment == 'online'){
              $('#buttonToCompletePayment').removeClass('hidden')
          }else{
              $('#buttonToCompletePayment').addClass('hidden')
          }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#oilBrandSelect').on('change', function() {
            var oilBrandId = $(this).val();
            $.ajax({
                url: '/api/oil-brands/' + oilBrandId,
                method: 'GET',
                success: function(response) {
                    var oils = response.data;

                    var selectDropdown = $('#oils');

                    selectDropdown.empty();
                    selectDropdown.append('<option selected="">@lang('site.select')</option>');

                    oils.forEach(function(brand) {

                        var brandName = @json(app()->getLocale() == 'ar' ? $brand->name_ar : $brand->name_en);
                        selectDropdown.append('<option value="' + brand.id + '">' + brand.name_ar + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error); // Handle the error gracefully
                }
            });
        });
    });
</script>
