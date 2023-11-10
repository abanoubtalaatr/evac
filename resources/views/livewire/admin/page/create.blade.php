
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
                            <label>@lang('site.title_ar')</label>
                            <input wire:model.defer='form.title_ar' class="@error('form.title_ar') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.title_ar')"/>
                            @error('form.title_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>

                        <div class="col-6">
                            <label>@lang('site.title_en')</label>
                            <input wire:model.defer='form.title_en' class="@error('form.title_en') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.title_en')"/>
                            @error('form.title_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-6">
                            <label>@lang('validation.attributes.desc_ar')</label>
                            <textarea wire:model.defer='form.desc_ar' class="@error('form.desc_ar') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.desc_ar')"></textarea>
                            @error('form.desc_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>

                        <div class="col-6">
                            <label>@lang('validation.attributes.desc_en')</label>
                            <textarea wire:model.defer='form.desc_en' class="@error('form.desc_en') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.desc_en')"></textarea>
                            @error('form.desc_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>


                    </div>

                    <div class="row">
                        <div class="custom-file-upload">
                                @if($picture)
                                    <img style='max-width:100%' src="{{$picture->temporaryUrl()}}" alt="">
                                @else
                                    @isset($page)
                                        <img style='max-width:100%' src="{{$page->picture_url}}" alt="">
                                    @endisset
                                @endif
                            <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                            <span>@lang('validation.attributes.image')</span>
                            <input wire:model='picture' class='form-control @error('picture') is-invalid @enderror' type="file"/>
                            @error('picture') <p class="text-danger">{{$message}}</p> @enderror
                        </div>
                    </div>



                    <div class="row">

                        <div class="col-12"  wire:ignore>
                            <label>@lang('validation.attributes.content_ar')</label>
                            <textarea id="content_ar" wire:model.defer='form.content_ar' class="@error('form.content_ar') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.content_ar')"></textarea>
                            @error('form.content_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>

                        <div class="col-12" wire:ignore>
                            <label>@lang('validation.attributes.content_en')</label>
                            <textarea id="content_en" wire:model.defer='form.content_en' class="@error('form.content_en') is-invalid @enderror form-control contact-input" type="text" placeholder="@lang('validation.attributes.content_en')"></textarea>
                            @error('form.content_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>

                    </div>


                    <div class="btns text-center">
                        <button type='submit' class="button btn-red big">@lang('site.save')</button>
                    </div>

                </form>
            </div>
          </div>
        </main>
<script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content_ar'),{
            language: 'ar'
        })
        .then(editor => {
            editor.setData(@json($form['content_ar']??''));

            editor.model.document.on('change:data', () => {
            @this.set('form.content_ar', editor.getData());
            });
        })
        .catch(error => {
            console.error(error);
        });
    ClassicEditor
        .create(document.querySelector('#content_en'),{
            language: 'en'
        })
        .then(editor => {
            editor.setData(@json($form['content_en']??''));

            editor.model.document.on('change:data', () => {
            @this.set('form.content_en', editor.getData());
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>
