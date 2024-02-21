<main class="main-content">
    <!--head-->

{{--    <x-admin.head/>--}}
<!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="edit-c">
            <form wire:submit.prevent='store'>
                <div class="row">
                    <div class="col-6">
                        <input
                            wire:model='form.name_ar'
                            class="@error('form.name_ar') is-invalid @enderror form-control contact-input"
                            type="text" placeholder="@lang('site.name_ar')"/>
                        @error('form.name_ar') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>


                    <div class="col-6">
                        <input
                            wire:model='form.name'
                            class="@error('form.name') is-invalid @enderror form-control contact-input"
                            type="text" placeholder="@lang('site.name')"/>
                        @error('form.name') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>


                </div>

        <div class="form-group" wire:ignore>
            <label for="">@lang('site.permissions')</label>
            <br>
            {{--                    <input type="checkbox" id="select_all_genders"/>@lang('site.select_all')--}}
            <br>
            <select id='permissions' wire:model='selectedPermissions' multiple
                    class="@error('form.permissions') is-invalid @enderror form-control contact-input  my-select-2">
                @foreach($permissions as $permission)
                    <option  value="{{$permission->id}}">{{app()->getLocale()=='ar'?$permission->name_ar:$permission->name}}</option>
                @endforeach
            </select>
        </div>
        @error('form.permissions') <p class="text-danger">{{$message}}</p> @enderror
        <hr>

        <div class="btns text-center">
            <button type='submit' class="button btn-red big">@lang('site.save')</button>
        </div>

        </form>
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

            $('#permissions').change(e=>{
                @this.set('selectedPermissions', $('#permissions').select2('val'));
            });
            $('#permissions').click(e=>{
                 if($("#permissions").is(':checked') ){
                    $("#permissions > option").attr("selected","selected");
                    $("#permissions").trigger("change");
                }else{
                    $("#permissions > option").removeAttr("selected");
                    $("#permissions").trigger("change");
                }
            });

            $('#permissions').change(e=>{
                @this.set('form.permissions', $('#permissions').select2('val'));
            });
            $('#permissions').click(e=>{
                 if($("#permissions").is(':checked') ){
                    $("#permissions > option").attr("selected","selected");
                    $("#permissions").trigger("change");
                }else{
                    $("#permissions > option").removeAttr("selected");
                    $("#permissions").trigger("change");
                }
            });
        });
    </script>
@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{asset('frontAssets/css/multiselect.css')}}"> --}}
    <style>
        .whats-p{
            /* background: url('whats-app-b.png'); */
            width: 100%;
            float: right;
            padding-bottom: 15px;
        }
        .w-det{
            background: rgb(0, 0, 0,.05);
            border-radius:5px ;
            overflow: hidden;
        }
        .chat{
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
        .chat p,.chat h5,.chat h4{color: rgb(89, 89, 89);text-decoration: none;}
        .chat a{text-decoration: none;}
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
        .chat a{color: #4285f3;}
        .chat img{max-width: 100%;}
    </style>
@endpush
