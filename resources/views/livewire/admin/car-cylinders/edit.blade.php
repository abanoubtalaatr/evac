<main class="main-content">
    <x-admin.head/>
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="row mt-30">
            <div class="col-lg-12">
                <form  wire:submit.prevent='update' action="" class="contac-form row">

                    <div class="form-group col-6">
                        <label for="form.username">@lang('validation.attributes.name_ar')</label>
                        <input wire:model.defer='form.name_ar' class="form-control" type="text" placeholder="@lang('validation.attributes.name_ar')"/>
                        @error('form.name_ar')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="form-group col-6">
                        <label for="form.email">@lang('validation.attributes.name_en')</label>
                        <input wire:model.defer='form.name_en' class="form-control" type="text" placeholder="@lang('validation.attributes.name_en')"/>
                        @error('form.name_en')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="form-group col-6 my-4">
                        <label for="form.email">@lang('validation.attributes.engine_type')</label>
                        <select id='brand_id' wire:model='form.engine_type'
                                class="@error('form.car_brand_id') is-invalid @enderror form-control contact-input  my-select-2">
                            <option value="">@lang('site.select')</option>
                            <option value="petrol">@lang('site.petrol')</option>
                            <option value="diesel">@lang('site.diesel')</option>
                        </select>
                        @error('form.engine_type')<p style='color:red'> {{$message}} </p>@enderror

                    </div>
                    <div class="form-group col-6 my-4">
                        <label for="form.number_of_kilos_of_oil">@lang('validation.attributes.number_of_kilos_of_oil')</label>
                        <input wire:model.defer='form.number_of_kilos_of_oil' class="form-control" type="number" placeholder="@lang('validation.attributes.number_of_kilos_of_oil')"/>
                        @error('form.number_of_kilos_of_oil')<p style='color:red'> {{$message}} </p>@enderror
                    </div>
                    <div class="form-group col-6 my-4 " >
                        <label for="">@lang('site.car_brands')</label>
                        <select id='brand_id' wire:model='form.car_brand_id'
                                class="@error('form.car_brand_id') is-invalid @enderror form-control contact-input  my-select-2">
                            <option value="">@lang('site.select')</option>

                            @foreach($carBrands as $carBrand)
                                <option  value="{{$carBrand->id}}">{{app()->getLocale() =='ar' ? $carBrand->name_ar:$carBrand->name_en}}</option>
                            @endforeach
                        </select>
                        @error('form.car_brand_id')<span style='color:red'> {{$message}} </span>@enderror

                    </div>

                    <div class="form-group col-6 my-4 " >
                        <label for="">@lang('site.car_modules')</label>
                        <select id='module_id' wire:model='form.car_module_id'
                                class="@error('form.car_module_id') is-invalid @enderror form-control contact-input  my-select-2">
                            <option value="">@lang('site.select')</option>

                            @foreach($carModules as $carModule)
                                <option  value="{{$carModule->id}}">{{app()->getLocale() =='ar' ? $carModule->name_ar:$carModule->name_en}}</option>
                            @endforeach
                        </select>
                        @error('form.car_module_id')<span style='color:red'> {{$message}} </span>@enderror

                    </div>


                    <div class="btns text-center my-2">
                        <button type = 'submit' class="button btn-red big">@lang('site.edit')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>
@push('scripts')
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>

        window.addEventListener('onContentChanged', () => {
            $('select').select2();
        });

        $(document).ready(()=>{
            $('select').select2();
            $('#brand_id').change(e=>{
            @this.set('form.car_brand_id', $('#brand_id').select2('val'));
            });
            $('#module_id').change(e=>{
            @this.set('form.car_module_id', $('#module_id').select2('val'));
            });
        });


    </script>
@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet"/>
@endpush

