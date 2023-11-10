<div>
<section class="head_banner">
    <div class="layer">
        &nbsp;
    </div>

</section>
<section class="profile">
    <div class="container">
        <div class="row">
            <div class="col-12 position-relative">
                <div class="circle">
                    @if($photo)
                        <img  style='max-width:100%'  src="{{$photo->temporaryUrl()}}" alt="">
                    @else
                        @if($user->avatar)
                            <img style='max-width:100%' src="{{asset('uploads/pics/' . $user->avatar)}}" alt="">
                        @else
                            <img class="profile-pic" src="{{ asset('website/assets/images/public/prof.png') }}">
                        @endif
                    @endif
                </div>
                <div class="p-image">
                    <img class="upload-button" src="{{ asset('website/assets/images/public/ed-pen.svg') }}">
                    <input type="file" wire:model="photo" wire:change="uploadPhoto" class="file-upload">
                </div>
            </div>
            <div class="col-12">
                @isset($message)
                <span class="alert alert-info">{{$message}}</span>
                @endif
                <form class="sign_box row sign_box_prof">
                    <div class=" mb-3 text-end col-md-6">
                        <label class="form-label" for="user">@lang('site.full_name')</label>

                        <div class="input-group border rounded">
                            <span class="input-group-text bg-transparent border-0"><img src="{{asset('website/assets/images/auth/user.svg')}}" alt=""></span>
                            <input wire:model.defer="username"  type="text" class="form-control bg-transparent border-0" id="user" value="{{$user->username}}">
                        </div>
                        @error('username')<p style='color:red'> {{$message}} </p>@enderror

                    </div>
                    <div class="mb-3 text-end col-md-6">
                        <label class="form-label" for="mail"> @lang('site.email')</label>

                        <div class="input-group  border rounded">
                            <span class="input-group-text bg-transparent border-0"><img src="{{asset('website/assets/images/auth/msg.svg')}}" alt=""></span>
                            <input type="email" wire:model.defer="email" class="form-control bg-transparent border-0" id="mail" value="{{$user->email}}">
                        </div>
                        @error('email')<p style='color:red'> {{$message}} </p>@enderror

                    </div>
                    <div class="mb-3 text-end col-md-6">
                        <label class="form-label" for="add">@lang('site.address')</label>

                        <div class="input-group  border rounded">
                            <input id="search-input" class="form-control mb-3 bg-transparent border-0 pac-target-input"  type="text" placeholder="Search for places">

                        </div>
                        <div id="map" wire:ignore></div>
                    </div>
                    <div class=" mb-3 text-end col-md-6">
                        <label class="form-label" for="user">@lang('site.phone_number')</label>

                        <div class="input-group border rounded">
                            <input wire:model.defer="mobile" type="text" class="form-control bg-transparent border-0" id="user" value="{{$user->mobile}}" disabled>
                            <span class="input-group-text bg-transparent border-0">+966 </span>

                        </div>
                        @error('mobile')<p style='color:red'> {{$message}} </p>@enderror

                    </div>
                    <div class="mb-4 col-md-3">
                        <button type="button" wire:click="update" class="btn btn-1 px-5 ">@lang('site.edit')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
</div>

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


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5LCZLdS1cq7-buPkwyLDjcARlFjiljYk&libraries=places&callback=initMap" async defer></script>
