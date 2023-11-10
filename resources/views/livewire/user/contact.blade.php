<div>
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>@lang('site.contact_with_us')</h2>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="contact">
        <div class="container">
            @isset($success_message)
                <div class="alert alert-info">{{$success_message}} </div>
            @endisset
            <div class="row">
                <div class="col-lg-7">
                    <form class="con_form border rounded p-3">
                        <h3>@lang('site.send_message')</h3>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="Input1" class="form-label">@lang('site.full_name')</label>
                                <input type="text" class="form-control" id="Input1" wire:model="form.sender_name">
                                @error('form.sender_name') <span class="text-danger">{{$message}}</span> @enderror

                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="Input2" class="form-label">@lang('site.email')</label>
                                <input type="email" class="form-control" id="Input2" wire:model="form.sender_email">
                                @error('form.sender_email') <span class="text-danger">{{$message}}</span> @enderror

                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="Input3" class="form-label">@lang('site.message_subject')</label>
                                <input type="text" class="form-control" id="Input3" wire:model="form.subject">
                                @error('form.subject') <span class="text-danger">{{$message}}</span> @enderror

                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="Input3" class="form-label">@lang('site.phone_number')</label>
                                <input type="text" class="form-control" id="Input3" wire:model="form.mobile">
                                @error('form.mobile') <span class="text-danger">{{$message}}</span> @enderror

                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">@lang('site.message')</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" wire:model="form.message"></textarea>
                                @error('form.message') <span  class="text-danger text-danger">{{$message}}</span> @enderror

                            </div>
                            <button type="button" wire:click="store" wire:loading.attr="disabled" class="btn btn-1 w-100">@lang('site.message')</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5">
                    <div class="con_info">
                        <h4>@lang('site.contact_with_us')</h4>
                        <p>@lang('site.description_contact')</p>
                        <div class="con_card d-flex mb-4 mt-4">
                            <img src="{{asset('website/assets/images/public/con1.svg')}}" alt="">
                            <div class="me-3 mx-2">
                                <h4>@lang('site.address')</h4>
                                <p>{{$settings->address}}</p>
                            </div>
                        </div>
                        <div class="con_card d-flex mb-4">
                            <img src="{{asset('website/assets/images/public/con1.svg')}}" alt="">
                            <div class="me-3 mx-2">
                                <h4>@lang('site.email')</h4>
                                <p>{{$settings->email}}</p>
                            </div>
                        </div>
                        <div class="con_card d-flex mb-4">
                            <img src="{{asset('website/assets/images/public/con1.svg')}}" alt="">
                            <div class="me-3 mx-2">
                                <h4>@lang('site.contact_with_us')</h4>
                                <p><span>@lang('site.phone_number') : </span>{{$settings->mobile}}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
                <div class="col-12 mt-lg-5 mt-3" wire:ignore>
                    <div id="map" style="height: 400px; position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div class="gm-err-container"><div class="gm-err-content"><div class="gm-err-icon"><img src="https://maps.gstatic.com/mapfiles/api-3/images/icon_error.png" alt="" draggable="false" style="user-select: none;"></div><div class="gm-err-title">Oops! Something went wrong.</div><div class="gm-err-message">This page didn't load Google Maps correctly. See the JavaScript console for technical details.</div></div></div></div></div>
                </div>
        </div>
    </section>
</div>

</div>
<script src="{{asset('website/'  . app()->getLocale() . '/assets/js/jquery.js')}}"></script>
<script>
    function initMap() {
        var defaultLatitude = {{ $latitude }};
        var defaultLongitude = {{ $longitude }};

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: defaultLatitude, lng: defaultLongitude}, // San Francisco coordinates
            zoom: 8
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5LCZLdS1cq7-buPkwyLDjcARlFjiljYk&libraries=places&callback=initMap" async defer></script>
