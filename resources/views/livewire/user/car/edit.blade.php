<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head d-md-flex justify-content-between align-items-center">
                        <h2>@lang('site.my_cars')</h2>
                        <button onclick="window.location='{{route('user.cars.create')}}'" class="btn btn-1 position-relative z-index" type="button">
                            <img src="{{asset('website/assets/images/steps/Plus.svg')}}" alt="">@lang('site.add_new_car')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="myCar_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-3">@lang('site.car_info')</h3>
                    @isset($message)
                    <div class="alert alert-info">{{$message}}</div>
                    @endisset
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.car_brand')</label>
                            <select wire:model.defer="form.car_brand_id" class="form-select" aria-label="Default select example">
                                <option selected="" disabled="">@lang('site.select')</option>
                                @foreach($carBrands as $carBrand)
                                   <option @if($carBrand->id == $car->id)selected @endif value="{{$carBrand->id}}">{{$carBrand->name}}</option>
                                @endforeach
                            </select>
                            @error('form.car_brand_id')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.car_module')</label>
                            <select wire:model.defer="form.car_module_id" class="form-select" aria-label="Default select example">
                                <option selected="" disabled="">@lang('site.select')</option>
                                @foreach($carModules as $carModule)
                                    <option @if($carModule->id == $car->id)selected @endif value="{{$carModule->id}}">{{$carModule->name}}</option>
                                @endforeach
                            </select>
                            @error('form.car_module_id')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.manufacturing_year')</label>

                            <select wire:model.defer="form.manufacturing_year" name="year"  class="form-select" aria-label="Default select example">
                                <option selected="" disabled="">@lang('site.select')</option>

                                @foreach(range(1900, date('Y')) as $year)
                                    <option value="{{ $year }}" {{ $car->manufacturing_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('form.manufacturing_year')<p style='color:red'> {{$message}} </p>@enderror


                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.car_color')</label>
{{--                            <select wire:model.defer="form.color" class="form-select" aria-label="Default select example">--}}
{{--                                <option selected="" disabled="">@lang('site.select')</option>--}}
{{--                            @foreach($colors as $color)--}}
{{--                                <option value="{{$color}}" @if($color == $car->color) selected @endif >@lang("site.$color")</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                            <input type="color" class="form-control" wire:model="form.color">

                            @error('form.color')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.number_of_engine_valves') </label>
                            <select wire:model.defer="form.number_of_engine_valves" class="form-select" aria-label="Default select example">
                                <option selected="" disabled="">@lang('site.select')</option>
                                @for($numberOfEngineValves = 1; $numberOfEngineValves < 40; $numberOfEngineValves++)
                                    <option value="{{$numberOfEngineValves}}" @if($numberOfEngineValves == $car->number_of_engine_valves) selected @endif >{{$numberOfEngineValves}}</option>
                                @endfor
                            </select>
                            @error('form.number_of_engine_valves')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.plate')</label>
                            <div class="d-md-flex">
                                <input wire:model.defer="form.plat_char" type="text" class="form-control ms-2 mb-md-0 mb-3" placeholder="أ ج - -" >

                                <input wire:model.defer="form.plat_number" type="text" class="form-control " placeholder="0000">

                            </div>
                            @error('form.plat_number')<p style='color:red' class="mt-2"> {{$message}} </p>@enderror

                            @error('form.plat_char')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.fuel_type')</label>
                            <select wire:model.defer="form.fuel_type" class="form-select" aria-label="Default select example">
                                <option selected="" disabled="">اختر</option>
                                <option value="diesel" @if($car->fuel_type =='diesel') selected @endif>@lang('site.diesel')</option>
                                <option value="petrol" @if($car->fuel_type =='petrol') selected @endif>@lang('site.petrol')</option>

                            </select>
                            @error('form.fuel_type')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="col-md-12">
                            <button wire:click="update" class="btn btn-1 mt-3 px-5" type="button">
                                @lang('site.edit')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

