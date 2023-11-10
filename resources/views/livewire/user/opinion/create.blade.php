<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>@lang('site.write_opinion')</h2>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="contact">
        <div class="container">
            @isset($message)
                <div class="alert alert-info w-50">{{$message}} </div>
            @endisset
            <div class="row">
                <div class="col-lg-7">
                    <form class="con_form  rounded p-3">
                        <h3>@lang('site.write_opinion')</h3>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" wire:model="form.opinion" @if($rateBefore) disabled @endif></textarea>
                                @error('form.opinion') <span  class="text-danger text-danger">{{$message}}</span> @enderror
                            </div>
                            <button type="button" wire:click="store" wire:loading.attr="disabled" class="btn btn-1 w-50 mx-auto" @if($rateBefore) disabled @endif>@lang('site.send')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
