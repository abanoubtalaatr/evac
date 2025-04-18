<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--campaign-->
    <div class="border-div">
        <div class="b-btm">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="edit-c">
            <form wire:submit.prevent='update'>
                <div class="row">
                    <div class="col-6">
                        <div class="custom-file-upload w-25">
                            @if($logo)
                                <img  style='max-width:100%'  src="{{$logo->temporaryUrl()}}" alt="">
                            @else
                                @isset($settings)
                                    <img style='max-width:100%' src="{{$form['logo']??""}}" alt="">
                                @endisset
                            @endif
                            <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                            <span>@lang('validation.attributes.image')</span>
                            <input wire:model='logo' class='form-control @error('logo') is-invalid @enderror' type="file"/>
                            @error('logo') <p class="text-danger">{{$message}}</p> @enderror
                        </div>
                        <div wire:loading wire:target="logo">    <i class="fas fa-spinner fa-spin"></i> </div>

                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.office_id')</label>
                        <input wire:model='form.office_id' placeholder="@lang('admin.office_id')"
                               class="@error('form.office_id') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.office_id') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.office_name')</label>
                        <input wire:model='form.office_name' placeholder="@lang('admin.office_name')"
                               class="@error('form.office_name') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.office_name') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.address')</label>
                        <input wire:model='form.address' placeholder="@lang('admin.address')"
                               class="@error('form.address') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.address') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.telephone')</label>
                        <input wire:model='form.mobile' placeholder="@lang('validation.attributes.mobile')"
                               class="@error('form.mobile') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.mobile') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.registration_no')</label>
                        <input wire:model='form.registration_no' placeholder="@lang('admin.registration_no')"
                               class="@error('form.registration_no') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.registration_no') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>

                    <div class="col-6">
                        <label for="">@lang('admin.vat_no')</label>
                        <input wire:model='form.vat_no' placeholder="@lang('admin.vat_no')"
                               class="@error('form.vat_no') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.vat_no') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.no_of_days_to_check_visa')</label>
                        <input wire:model='form.no_of_days_to_check_visa' placeholder="@lang('admin.no_of_days_to_check_visa')"
                               class="@error('form.no_of_days_to_check_visa') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.no_of_days_to_check_visa') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.vat_rate')</label>
                        <input wire:model='form.vat_rate' placeholder="@lang('admin.vat_rate')"
                               class="@error('form.vat_rate') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.vat_rate') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>

                    <div class="col-6">
                        <label for="">@lang('admin.color')</label>
                        <input wire:model='form.color' placeholder="@lang('admin.color')"
                               class="@error('form.color') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.color') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.background')</label>
                        <input wire:model='form.background' placeholder="@lang('admin.background')"
                               class="@error('form.background') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.background') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="" class="text-danger">If you have more than one email, you must write like this <p style="margin-bottom: 0px;font-size: 18px;font-weight: bold;" class="">test@gmail.com, test2@gmail.com</p> separate by, to avoid any issues in application</label>
                        <input wire:model='form.email' placeholder="@lang('admin.email')"
                               class="@error('form.email') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.email') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.fax')</label>
                        <input wire:model='form.fax' placeholder="@lang('admin.fax')"
                               class="@error('form.fax') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.fax') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.invoice_start')</label>
                        <input wire:model='form.invoice_start' placeholder="@lang('admin.invoice_start')"
                               class="@error('form.invoice_start') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.invoice_start') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.passport_expiry_days')</label>
                        <input wire:model='form.passport_expiry_days' placeholder="@lang('admin.passport_expiry_days')"
                               class="@error('form.passport_expiry_days') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.passport_expiry_days') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    
                    <div class="col-6">
                        <label for="">@lang('admin.invoice_footer')</label>
                        <textarea class="form-control" wire:model="form.invoice_footer" ></textarea>
                        @error('form.invoice_footer') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                </div>


                <div class="btns text-center d-block mt-4">
                    <button type='button' wire:click="update" class="button btn-red big">@lang('site.save')</button>
                </div>

            </form>
        </div>
    </div>
</main>
<script>
    document.addEventListener('livewire-upload-progress', event => {
    @this.progress = Math.floor(event.detail.progress);
    });
</script>

<script src="{{asset('website/assets/js/jquery.js')}}"></script>
