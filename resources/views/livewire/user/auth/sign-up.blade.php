
<section class="auth">
    <div class="layer">
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
<div class="col-lg-5">
    <div class="sign_box text-center">
        <img src="{{asset('website/assets/images/auth/logo.svg')}}" alt="">
        <form class="mt-lg-5 mt-3">
            <div class=" mb-3 text-end">
                <label class="form-label" for="user">الاسم بالكامل</label>

                <div class="input-group border rounded">
                    <span class="input-group-text bg-transparent border-0"><img src="{{asset('website/assets/images/auth/user.svg')}}" alt=""></span>
                    <input  wire:model.defer="username"  type="text" class="form-control bg-transparent border-0" id="user">
                </div>
                @error('username')<p style='color:red'> {{$message}} </p>@enderror

            </div>
            <div class="mb-3 text-end">
                <label class="form-label" for="mail"> البريد الالكترونى</label>

                <div class="input-group  border rounded">
                    <span class="input-group-text bg-transparent border-0"><img src="{{asset('website/assets/images/auth/msg.svg')}}" alt=""></span>
                    <input  wire:model.defer="email"  type="email" class="form-control bg-transparent border-0" id="mail">
                </div>
                @error('email')<p style='color:red'> {{$message}} </p>@enderror

            </div>
            <div class="mb-3 text-end">
                <label class="form-label" for="add">العنوان</label>

                <div class="input-group  border rounded">
                    <input type="text" id="search-input" class="form-control mb-3 bg-transparent border-0 pac-target-input" placeholder=" " autocomplete="off">
                </div>
                <div id="map" wire:ignore style="height: 200px; position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div class="gm-err-container"><div class="gm-err-content"><div class="gm-err-icon"><img src="https://maps.gstatic.com/mapfiles/api-3/images/icon_error.png" alt="" draggable="false" style="user-select: none;"></div><div class="gm-err-title">Oops! Something went wrong.</div><div class="gm-err-message">This page didn't load Google Maps correctly. See the JavaScript console for technical details.</div></div></div></div></div>

            </div>
            <button type="button" wire:click="store" class="btn btn-1 px-5 mt-3">@lang('site.send')</button>

        </form>
    </div>
</div>
<div class="col-lg-1">

</div>
<div class="col-lg-6">
    <div class="sign_bg">
        <img src="{{asset('website/assets/images/auth/man.jpg')}}" alt="">
    </div>
</div>
</div>
</div>
</section>

<script src="{{asset('website/assets/js/jquery.js')}}"></script>
<script>
    function initMap() {
        var defaultLatitude = {{ $latitude }};
        var defaultLongitude = {{ $longitude }};

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: defaultLatitude, lng: defaultLongitude}, // San Francisco coordinates
            zoom: 13
        });
        var input = document.getElementById('search-input');
        var searchBox = new google.maps.places.SearchBox(input);

        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length === 0) {
                return;
            }

            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log('Returned place contains no geometry');
                    return;
                }

                var marker = new google.maps.Marker({
                    map: map,
                    title: place.name,
                    position: place.geometry.location
                });

                bounds.extend(place.geometry.location);
            });

            map.fitBounds(bounds);

            // Livewire: Update the latitude and longitude values
            var selectedPlace = places[0];
        @this.latitude = selectedPlace.geometry.location.lat();
        @this.longitude = selectedPlace.geometry.location.lng();
        });

        // Add click event listener on the map
        map.addListener('click', function(event) {
            // Livewire: Update the latitude and longitude values
        @this.updateCoordinates(event.latLng.lat(), event.latLng.lng());
        });
    }
</script>

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5LCZLdS1cq7-buPkwyLDjcARlFjiljYk&libraries=places&callback=initMap" async defer></script>
@endpush


