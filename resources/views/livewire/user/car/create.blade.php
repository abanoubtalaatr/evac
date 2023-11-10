<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head d-md-flex justify-content-between align-items-center">
                        <h2>@lang('site.my_cars')</h2>
                        <button  class="btn btn-1 position-relative z-index" type="button">
                            <img src="{{asset('website/assets/images/steps/Plus.svg')}}" alt="">@lang('site.add_new_car')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
  @livewire("user.car.store-car")
</div>

