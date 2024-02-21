<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="edit-c">
            <form wire:submit.prevent='store'>
                <div class="row">
                    <div class="col-6">
                        <input wire:model='form.title_ar'
                               class="@error('form.title_ar') is-invalid @enderror form-control contact-input"
                               type="text" placeholder="@lang('validation.attributes.title_ar')"/>
                        @error('form.title_ar') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <input wire:model='form.title_en'
                               class="@error('form.title_en') is-invalid @enderror form-control contact-input"
                               type="text" placeholder="@lang('validation.attributes.title_en')"/>
                        @error('form.title_en') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>


                </div>

                <div class="row">
                    <div class="col-6">
                        <textarea wire:model='form.description_ar'
                                  class="@error('form.description_ar') is-invalid @enderror form-control contact-input"
                                  placeholder="@lang('validation.attributes.description_ar')"></textarea>
                        @error('form.description_ar') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <textarea wire:model='form.description_en'
                                  class="@error('form.description_en') is-invalid @enderror form-control contact-input"
                                  placeholder="@lang('validation.attributes.description_en')"></textarea>
                        @error('form.description_en') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label class="form-check-label" for="isAdmin">
                            {{trans('general.show_banner_in_site')}}
                        </label> :
                        <input wire:model='form.is_active'
                               class="@error('form.is_active') is-invalid @enderror form-check-input form-check-input-lg"
                               type="checkbox" placeholder="@lang('validation.attributes.is_active')"/>
                        @error('form.is_active') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                </div>

{{--                <div class="row">--}}
{{--                    <div class="custom-file-upload">--}}
{{--                        @if($picture)--}}
{{--                            <img style='max-width:100%' src="{{$picture->temporaryUrl()}}" alt="">--}}
{{--                        @else--}}
{{--                            @isset($slider)--}}
{{--                                <img style='max-width:100%' src="{{$slider->picture_url}}" alt="">--}}
{{--                            @endisset--}}
{{--                        @endif--}}
{{--                        <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">--}}
{{--                        <span>@lang('validation.attributes.picture')</span>--}}
{{--                        <input wire:model='picture' class='form-control @error('picture') is-invalid @enderror'--}}
{{--                               type="file"/>--}}
{{--                        @error('picture') <p class="text-danger">{{$message}}</p> @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="btns text-center">
                    <button type='submit' class="button btn-red big">@lang('site.save')</button>
                </div>

            </form>
        </div>
    </div>
</main>
