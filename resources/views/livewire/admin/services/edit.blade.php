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
                        <label for="form.price">@lang('validation.attributes.price')</label>
                        <input wire:model.defer='form.price' class="form-control" type="number"  min="1" placeholder="@lang('validation.attributes.price')"/>
                        @error('form.price')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="form-group col-6 my-4">
                        <label for="form.time">@lang('validation.attributes.time')</label>
                        <input wire:model.defer='form.time' class="form-control" type="text" placeholder="@lang('validation.attributes.time')"/>
                        @error('form.time')<p style='color:red'> {{$message}} </p>@enderror
                    </div>
                    <div class="form-group col-6 my-4">
                        <label for="form.description_ar">@lang('site.description_ar')</label>
                        <textarea wire:model.defer='form.description_ar' class="form-control" type="text" placeholder="@lang('site.description_ar')"></textarea>
                        @error('form.description_ar')<p style='color:red'> {{$message}} </p>@enderror
                    </div>
                    <div class="form-group col-6 my-4">
                        <label for="form.description_en">@lang('site.description_en')</label>
                        <textarea wire:model.defer='form.description_en' class="form-control" type="text" placeholder="@lang('site.description_en')"></textarea>
                        @error('form.description_en')<p style='color:red'> {{$message}} </p>@enderror
                    </div>

                    <div class="col-6">
                        <div class="custom-file-upload">
                            @if($video_url)

                                <video height="100%" width="100%" controls autoplay loop muted>
                                    <source src="{{$video_url->temporaryUrl()}}" type="video/mp4">
                                </video>
                            @else
                                @if($service && $service->video_url)
                                    <video height="100%" width="100%" controls autoplay loop muted>
                                        <source src="{{asset('uploads/pics/'. $service->video_url)}}" type="video/mp4">
                                    </video>
                                @endif
                            @endif
                            <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                            <span>@lang('validation.attributes.video')</span>
                            <input wire:model='video_url' class='form-control @error('video_url') is-invalid @enderror' type="file"/>
                            @error('video_url') <p class="text-danger">{{$message}}</p> @enderror
                        </div>
                        <div wire:loading wire:target="video_url">    <i class="fas fa-spinner fa-spin"></i> </div>
                    </div>

                    <div class="col-md-6 my-2">
                        <div class="custom-file-upload">
                            @if($image)
                                <img  style='max-width:100%' height="100%" src="{{$image->temporaryUrl()}}" alt="">
                            @else
                                @isset($service)
                                    <img style='max-width:100%' src="{{$service->image}}" alt="">
                                @endisset
                            @endif
                            <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                            <span>@lang('validation.attributes.image')</span>
                            <input wire:model='image' class='form-control @error('image') is-invalid @enderror' type="file"/>
                            @error('image') <p class="text-danger">{{$message}}</p> @enderror
                        </div>
                        <div wire:loading wire:target="image">    <i class="fas fa-spinner fa-spin"></i> </div>

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
            $('#sub_service_id').change(e=>{
            @this.set('form.sub_service_id', $('#sub_service_id').select2('val'));
            });
        });
    </script>
@endpush
