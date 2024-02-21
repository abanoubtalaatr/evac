<main class="main-content">
    <x-admin.head/>
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="row mt-30">
            <div class="col-lg-12">
                <form wire:submit.prevent='store' action="" class="contac-form row">

                    <div class="form-group col-6">
                        <label for="form.name">@lang('validation.attributes.name')</label>
                        <input wire:model='form.name' class="form-control" type="text"
                               placeholder="@lang('validation.attributes.name')"/>
                        @error('form.name')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="form-group col-6">
                        <label for="form.email">@lang('validation.attributes.email')</label>
                        <input wire:model='form.email' class="form-control" type="text"
                               placeholder="@lang('validation.attributes.email')"/>
                        @error('form.email')<p style='color:red'> {{$message}} </p>@enderror
                    </div>


                    <hr>

                    <div class="form-group col-6">
                        <label for="form.mobile">@lang('validation.attributes.phone')</label>
                        <input wire:model='form.phone' class="form-control" type="text"
                               placeholder="@lang('validation.attributes.phone')"/>
                        @error('form.phone')<p style='color:red'> {{$message}} </p>@enderror
                    </div>


                    <div class="form-group col-6">
                        <label for="form.password">@lang('validation.attributes.password')</label>
                        <input wire:model='form.password' class="form-control" type="password"
                               placeholder="@lang('validation.attributes.password')"/>
                        @error('form.password')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="form-group col-6">
                        <label
                            for="form.password_confirmation">@lang('validation.attributes.password_confirmation')</label>
                        <input wire:model='form.password_confirmation' class="form-control" type="password"
                               placeholder="@lang('validation.attributes.password_confirmation')"/>
                        @error('form.password_confirmation')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <hr>
                    <div class="col-md-12 my-2">
                        <div class="custom-file-upload w-25">
                            @if($image)
                                <img  style='max-width:100%'  src="{{$image->temporaryUrl()}}" alt="">
                            @endif
                            <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                            <span>@lang('validation.attributes.image')</span>
                            <input wire:model='image' class='form-control @error('image') is-invalid @enderror' type="file"/>
                            @error('image') <p class="text-danger">{{$message}}</p> @enderror
                        </div>
                        <div wire:loading wire:target="image">    <i class="fas fa-spinner fa-spin"></i> </div>

                    </div>

                    <div class="form-group" wire:ignore>
                        <label for="">@lang('site.roles')</label>
{{--                        <br>--}}
{{--                        <input type="checkbox" id="select_all_roles"/>@lang('site.select_all')--}}
{{--                        <br>--}}
                        <select  id="roles"  wire:model='form.roles' style="min-height: 300px" multiple
                                class="@error('form.roles') is-invalid @enderror form-control contact-input  my-select-2">
                            @foreach($roles as $role)
                                <option
                                    value="{{$role->id}}">{{app()->getLocale()=='ar'?$role->name_ar:$role->name}}</option>
                            @endforeach

                        </select>

                    </div>
                    @error('form.roles')<p class="text-danger"> {{$message}} </p>@enderror


                    <div class="btns text-center mt-2">
                        <button type='submit' class="button btn-red big">@lang('site.create_admin')</button>
                    </div>

                </form>


            </div>

        </div>
    </div>
</main>
@push('scripts')
    {{-- <script src='{{asset('frontAssets/js/multiselect.js')}}'></script> --}}

    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>

        window.addEventListener('onContentChanged', () => {
            $('select').select2();
        });

        $(document).ready(()=>{
           $('select').select2();
            $('#roles').change(e=>{
            @this.set('form.roles', $('#roles').select2('val'));
            });
        });


    </script>
@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet"/>
    {{-- <link rel="stylesheet" href="{{asset('frontAssets/css/multiselect.css')}}"> --}}
    <style>
        .whats-p {
            /* background: url('whats-app-b.png'); */
            width: 100%;
            float: right;
            padding-bottom: 15px;
        }

        .w-det {
            background: rgb(0, 0, 0, .05);
            border-radius: 5px;
            overflow: hidden;
        }

        .chat {
            background: #dbf8c7;
            /* margin: 10px 25px 3px 0px; */
            padding: 10px;
            background: #DCF8C6;

            width: 100%;

            display: block;
            border-radius: 5px;
            position: relative;
            box-shadow: 0px 2px 1px rgb(0 0 0 / 20%);
        }

        .chat p, .chat h5, .chat h4 {
            color: rgb(89, 89, 89);
            text-decoration: none;
        }

        .chat a {
            text-decoration: none;
        }

        .chat .bubble-arrow.alt {
            position: absolute;
            bottom: 20px;
            left: auto;
            right: 4px;
            float: right;
            top: 0;
        }

        .chat .bubble-arrow:after {
            content: "";
            position: absolute;
            border-top: 15px solid #DCF8C6;
            transform: scaleX(-1);
            border-left: 15px solid transparent;
            border-radius: 4px 0 0 0px;
            width: 0;

        }

        .chat a {
            color: #4285f3;
        }

        .chat img {
            max-width: 100%;
        }
    </style>
@endpush

