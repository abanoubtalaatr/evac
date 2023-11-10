<div>
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
                                <option selected="" >@lang('site.select')</option>
                                @foreach($carBrands as $carBrand)
                                    <option value="{{$carBrand->id}}">{{$carBrand->name}}</option>
                                @endforeach
                            </select>
                            @error('form.car_brand_id')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.car_module')</label>
                            <select wire:model.defer="form.car_module_id" class="form-select" aria-label="Default select example">
                                <option selected="" >@lang('site.select')</option>
                                @foreach($carModules as $carModule)
                                    <option value="{{$carModule->id}}">{{$carModule->name}}</option>
                                @endforeach
                            </select>
                            @error('form.car_module_id')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.manufacturing_year')</label>

                            <select wire:model.defer="form.manufacturing_year" name="year"  class="form-select" aria-label="Default select example">
                                <option selected="" >@lang('site.select')</option>

                                @foreach(range(1900, date('Y')) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('form.manufacturing_year')<p style='color:red'> {{$message}} </p>@enderror


                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.car_color')</label>
{{--                            <select wire:model.defer="form.color" class="form-select" aria-label="Default select example">--}}
{{--                                <option selected="" >@lang('site.select')</option>--}}
{{--                                @foreach($colors as $color)--}}
{{--                                    <option value="{{$color}}">@lang("site.$color")</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                            <input type="color" class="form-control" wire:model="form.color">
                            @error('form.color')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">@lang('site.number_of_engine_valves') </label>
                            <select wire:model.defer="form.number_of_engine_valves" class="form-select" aria-label="Default select example">
                                <option selected="" >@lang('site.select')</option>
                                @for($numberOfEngineValves = 1; $numberOfEngineValves < 40; $numberOfEngineValves++)
                                    <option value="{{$numberOfEngineValves}}" >{{$numberOfEngineValves}}</option>
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
                                <option selected="" >@lang('site.select')</option>
                                <option value="diesel" >@lang('site.diesel')</option>
                                <option value="petrol" >@lang('site.petrol')</option>

                            </select>
                            @error('form.fuel_type')<p style='color:red'> {{$message}} </p>@enderror

                        </div>
                        <div class="col-md-12">
                            <button wire:click="store" class="btn btn-1 mt-3 px-5" type="button">
                                <img src="{{asset('website/assets/images/steps/Plus.svg')}}" alt=""> @lang('site.add')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
