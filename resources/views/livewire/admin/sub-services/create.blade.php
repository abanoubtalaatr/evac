<main class="main-content">
    <x-admin.head/>
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="row mt-30">
            <div class="col-lg-12">
                <form  wire:submit.prevent='store' action="" class="contac-form row">

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
                        <label for="form.price">@lang('validation.attributes.price')</label>
                        <input wire:model.defer='form.price' class="form-control" type="number" min="1" placeholder="@lang('validation.attributes.price')"/>
                        @error('form.price')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="form-group col-6 my-4">
                        <label for="form.time">@lang('validation.attributes.time')</label>
                        <input wire:model.defer='form.time' class="form-control" type="text" placeholder="@lang('validation.attributes.time')"/>
                        @error('form.time')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group my-4 " wire:ignore>
                            <label for="">@lang('site.service')</label>
                            <select id='service_id' wire:model='form.service_id'
                                    class="@error('form.service_id') is-invalid @enderror form-control contact-input  my-select-2">
                                <option value="">@lang('site.service')</option>

                                @foreach($services as $service)
                                    <option  value="{{$service->id}}">{{app()->getLocale() =='ar' ? $service->name_ar:$service->name_en}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.service_id')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="btns text-center my-2">
                        <button type = 'submit' class="button btn-red big">@lang('site.create')</button>
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
            $('#service_id').change(e=>{
            @this.set('form.service_id', $('#service_id').select2('val'));
            });
        });
    </script>
@endpush

