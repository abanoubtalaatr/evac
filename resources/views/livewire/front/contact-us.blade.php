@section('content')
    <section class="privacy">
        <div class="container">
            <div class="row">

                <div class="col-12">
                    <div class="section-head  head-shape pt-5 mb-0 text-center con-head mt-lg-5">
                        <h2>@lang('site.contact_us')</h2>
                        <p>
                            <small>
                                It's very easy to get in touch with us. Just use the contact form or pay us a visit
                                for a coffee at the office. Dynamically innovate competitive technology after an
                                expanded array of leadership
                            </small>
                        </p>
                    </div>
                </div>

            </div>
            <div class="row bg-gray">
                <div class="col-lg-6">
                    <div class="map-box position-relative">
                        <div id="contact-map"></div>
                        <div class="con-info">
                            <h5>@lang('general.address')</h5>
                            <ul>
                                <li>
                                    <a href="#">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{$settings->address}}
                                    </a>
                                </li>
                                <li><a href="#"><i class="fa-solid fa-mobile-button"></i> {{$settings->mobile}}</a></li>
                                <li><a href="#"><i class="fa-solid fa-envelope"></i> {{$settings->email}}</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form wire:submit.prevent='store' class="contact-info row">
                        <div class="mb-3 col-lg-6">
                            <input wire:model='form.sender_name' type="text" class="form-control @error('form.sender_name') is-invalid @enderror" id="input1" placeholder="@lang('site.sender_name')">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <input wire:model='form.sender_email' type="email" class="form-control @error('form.sender_name') is-invalid @enderror" id="input2" aria-describedby="emailHelp"  placeholder="Your email">
                        </div>

                        {{-- <div class="mb-3 col-12">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>@lang('site.select_subject')</option>
                                <option value="complaint">@lang('site.complaint')</option>
                                <option value="suggestion">@lang('site.suggestion')</option>
                                <option value="question">@lang('site.question')</option>
                                <option value="help">@lang('site.help')</option>
                            </select>
                        </div> --}}
                        <div class="mb-3 col-12">
                            <textarea wire:model='form.message' class="form-control form-control-x  @error('form.message') is-invalid @enderror" id="exampleFormControlTextarea1" rows="5"
                                placeholder="@lang('site.message')"></textarea>
                        </div>
                        <div class="text-center">
                            <button wire:click='store' type="button" class="btn btn-send">@lang('site.send')</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


     <script>
    function initMap() {
        var uluru = {
            lat: {{ $settings->lat }},
            lng: {{ $settings->lng }}
        };
        var map = new google.maps.Map(document.getElementById('contact-map'), {
            zoom: 14,
            center: uluru,
            scrollwheel: false
        });
        var marker = new google.maps.Marker({
            position: uluru,
            map: map,
             icon: 'https://easetemplate.com/free-website-templates/kitchen/images/map_marker.png'

        });
    }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAYncBCESMBWD_v3lRE5p7s1uMmHtHDF8k&callback=initMap">
    </script>
    <style>
        #contact-map {
            height: 505px;
            width: 100%;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .contact-map-section {
            position: relative;
        }
    </style>
@endsection
