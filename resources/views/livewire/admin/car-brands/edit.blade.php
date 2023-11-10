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

                    <div class="btns text-center my-2">
                        <button type = 'submit' class="button btn-red big">@lang('site.edit')</button>
                    </div>

                </form>


            </div>

        </div>
    </div>
</main>
