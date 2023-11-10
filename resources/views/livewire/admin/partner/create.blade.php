
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
                            <input wire:model.defer='form.name_ar' class="@error('form.name_ar') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.name_ar')"/>
                            @error('form.name_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>

                        <div class="col-6">
                            <input wire:model.defer='form.name_en' class="@error('form.name_en') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.name_en')"/>
                            @error('form.name_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="custom-file-upload">
                                @if($image)
                                    <img style='max-width:100%' src="{{$image->temporaryUrl()}}" alt="">
                                @else
                                    @isset($partner)
                                        <img style='max-width:100%' src="{{$partner->picture_url}}" alt="">
                                    @endisset
                                @endif
                            <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                            <span>@lang('validation.attributes.image')</span>
                            <input wire:model='image' class='form-control @error('image') is-invalid @enderror' type="file"/>
                            @error('image') <p class="text-danger">{{$message}}</p> @enderror
                        </div>
                    </div>

                    <div class="btns text-center">
                        <button type='submit' class="button btn-red big">@lang('site.save')</button>
                    </div>

                </form>
            </div>
          </div>
        </main>
