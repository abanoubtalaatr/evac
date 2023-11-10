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
                        <label for="">@lang('validation.attributes.email')</label>
                        <input wire:model='form.email' placeholder="@lang('validation.attributes.email')"
                               class="@error('form.email') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.email') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>

                    <div class="col-6">
                        <label for="">@lang('validation.attributes.address')</label>
                        <input wire:model='form.address' placeholder="@lang('validation.attributes.address')"
                               class="@error('form.address') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.address') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label for="">@lang('validation.attributes.mobile')</label>
                        <input wire:model='form.mobile' placeholder="@lang('validation.attributes.mobile')"
                               class="@error('form.mobile') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.mobile') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('validation.attributes.website_link')</label>
                        <input wire:model='form.website_link' placeholder="@lang('validation.attributes.website_link')"
                               class="@error('form.website_link') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.website_link') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>

                    <div class="col-6">
                        <label for="">@lang('validation.attributes.facebook')</label>
                        <input wire:model='form.facebook' placeholder="@lang('validation.attributes.facebook')"
                               class="@error('form.facebook') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.facebook') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('validation.attributes.instagram')</label>
                        <input wire:model='form.instagram' placeholder="@lang('validation.attributes.instagram')"
                               class="@error('form.instagram') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.instagram') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('validation.attributes.twitter')</label>
                        <input wire:model='form.twitter' placeholder="@lang('validation.attributes.twitter')"
                               class="@error('form.twitter') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.twitter') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.customer_service_number')</label>
                        <input wire:model='form.customer_service_number' placeholder="@lang('admin.customer_service_number')"
                               class="@error('form.customer_service_number') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.customer_service_number') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.brief_ar')</label>
                        <textarea wire:model='form.brief_ar' placeholder="@lang('admin.brief_ar')"
                               class="@error('form.brief_ar') is-invalid @enderror form-control contact-input"
                        ></textarea>
                        @error('form.brief_ar') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <label for="">@lang('admin.brief_en')</label>
                        <textarea wire:model='form.brief_en' placeholder="@lang('admin.brief_en')"
                                  class="@error('form.brief_en') is-invalid @enderror form-control contact-input"
                        ></textarea>
                        @error('form.brief_en') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="col-6">
                        <p>@lang('site.payment_type')</p>
                        <div class="col-6">
                            <label>@lang('site.pay_online')</label>
                            <input wire:click="updatePayOnline"  wire:model='form.pay_online' placeholder="@lang('site.pay_online')"
                                   class="form-check-input"
                                   type="checkbox"/>
                        </div>
                        <div class="col-6">
                            <label>@lang('site.pay_cash')</label>
                            <input wire:click="updatePayCash" wire:model='form.pay_cash' placeholder="@lang('site.pay_cash')"
                                   class="form-check-input"
                                   type="checkbox"/>
                        </div>
                        <hr/>
                    </div>
                    <div class="col-6">
                        <p>@lang('admin.number_of_experience')</p>
                        <input wire:model='form.number_of_experience' placeholder="@lang('admin.number_of_experience')"
                               class="@error('form.number_of_experience') is-invalid @enderror form-control contact-input"
                               type="text"/>
                        @error('form.number_of_experience') <p class="text-danger">{{$message}}</p> @enderror
                        <hr/>
                    </div>
                    <div class="row">
                        <h4>@lang('site.mission')</h4>
                        <div class="col-6">
                            <label for="">@lang('admin.mission_ar')</label>
                            <textarea wire:model='form.mission_ar' placeholder="@lang('admin.mission_ar')"
                                      class="@error('form.mission_ar') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.mission_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <label for="">@lang('admin.mission_en')</label>
                            <textarea wire:model='form.mission_en' placeholder="@lang('admin.mission_en')"
                                      class="@error('form.mission_en') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.mission_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <div class="custom-file-upload w-25">
                                @if($mission_image)
                                    <img  style='max-width:100%'  src="{{$mission_image->temporaryUrl()}}" alt="">
                                @else
                                    @isset($settings)
                                        <img style='max-width:100%' src="{{$form['mission_image']??""}}" alt="">
                                    @endisset
                                @endif
                                <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                                <span>@lang('validation.attributes.image')</span>
                                <input wire:model='mission_image' class='form-control @error('mission_image') is-invalid @enderror' type="file"/>
                                @error('mission_image') <p class="text-danger">{{$message}}</p> @enderror
                            </div>
                            <div wire:loading wire:target="mission_image">    <i class="fas fa-spinner fa-spin"></i> </div>

                        </div>
                    </div>
                    <hr>
                    <div class="row my-4">
                        <h4>@lang('site.vision')</h4>
                        <div class="col-6">
                            <label for="">@lang('admin.vision_ar')</label>
                            <textarea wire:model='form.vision_ar' placeholder="@lang('admin.vision_ar')"
                                      class="@error('form.vision_ar') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.vision_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <label for="">@lang('admin.vision_en')</label>
                            <textarea wire:model='form.vision_en' placeholder="@lang('admin.vision_en')"
                                      class="@error('form.vision_en') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.vision_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <div class="custom-file-upload w-25">
                                @if($vision_image)
                                    <img  style='max-width:100%'  src="{{$vision_image->temporaryUrl()}}" alt="">
                                @else
                                    @isset($settings)
                                        <img style='max-width:100%' src="{{$form['vision_image']??""}}" alt="">
                                    @endisset
                                @endif
                                <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                                <span>@lang('validation.attributes.image')</span>
                                <input wire:model='vision_image' class='form-control @error('vision_image') is-invalid @enderror' type="file"/>
                                @error('vision_image') <p class="text-danger">{{$message}}</p> @enderror
                            </div>
                            <div wire:loading wire:target="vision_image">    <i class="fas fa-spinner fa-spin"></i> </div>

                        </div>
                    </div>
                    <hr>
                    <div class="row my-4">
                        <h4>@lang('admin.objective')</h4>
                        <div class="col-6">
                            <label for="">@lang('admin.objective_ar')</label>
                            <textarea wire:model='form.objective_ar' placeholder="@lang('admin.objective_ar')"
                                      class="@error('form.objective_ar') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.objective_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <label for="">@lang('admin.objective_en')</label>
                            <textarea wire:model='form.objective_en' placeholder="@lang('admin.objective_en')"
                                      class="@error('form.objective_en') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.objective_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <div class="custom-file-upload w-25">
                                @if($objective_image)
                                    <img  style='max-width:100%'  src="{{$objective_image->temporaryUrl()}}" alt="">
                                @else
                                    @isset($settings)
                                        <img style='max-width:100%' src="{{$form['objective_image']??""}}" alt="">
                                    @endisset
                                @endif
                                <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                                <span>@lang('validation.attributes.image')</span>
                                <input wire:model='objective_image' class='form-control @error('objective_image') is-invalid @enderror' type="file"/>
                                @error('objective_image') <p class="text-danger">{{$message}}</p> @enderror
                            </div>
                            <div wire:loading wire:target="objective_image">    <i class="fas fa-spinner fa-spin"></i> </div>

                        </div>
                    </div>
                    <div class="row">
                        <h4>@lang('admin.video')</h4>
                        <div class="col-6">
                            <label for="">@lang('admin.video_title_ar')</label>
                            <textarea wire:model='form.video_title_ar' placeholder="@lang('admin.video_title_ar')"
                                      class="@error('form.video_title_ar') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.video_title_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <label for="">@lang('admin.video_title_en')</label>
                            <textarea wire:model='form.video_title_en' placeholder="@lang('admin.video_title_en')"
                                      class="@error('form.video_title_en') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.video_title_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <label for="">@lang('admin.video_description_ar')</label>
                            <textarea wire:model='form.video_description_ar' placeholder="@lang('admin.video_description_ar')"
                                      class="@error('form.video_description_ar') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.video_description_ar') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <label for="">@lang('admin.video_description_en')</label>
                            <textarea wire:model='form.video_description_en' placeholder="@lang('admin.video_description_en')"
                                      class="@error('form.video_description_en') is-invalid @enderror form-control contact-input"
                            ></textarea>
                            @error('form.video_description_en') <p class="text-danger">{{$message}}</p> @enderror
                            <hr/>
                        </div>
                        <div class="col-6">
                            <div class="custom-file-upload">
                                @if($video_url)
                                    <video height="100%" width="100%" controls autoplay loop muted>
                                        <source src="{{$video_url->temporaryUrl()}}" type="video/mp4">
                                    </video>
                                @else
                                    @isset($settings)
                                        <video height="100%" width="100%" controls autoplay loop muted>
                                            <source src="{{$form['video_url']??""}}" type="video/mp4">
                                        </video>
                                    @endisset
                                @endif
                                <img src="{{asset('frontAssets')}}/imgs/wallet/upload.svg" alt="">
                                <span>@lang('validation.attributes.video')</span>
                                <input wire:model='video_url' class='form-control @error('video_url') is-invalid @enderror' type="file"/>
                                @error('video_url') <p class="text-danger">{{$message}}</p> @enderror
                            </div>
                            <div wire:loading wire:target="video_url">    <i class="fas fa-spinner fa-spin"></i> </div>
                        </div>
                        <div class="mb-3 text-end col-md-12" >
                            <label class="form-label" for="add">@lang('site.address')</label>

                            <div class="input-group  border rounded">
                                <input id="search-input"  class="form-control mb-3 bg-transparent border-0 pac-target-input"  type="text" placeholder="Search for places">

                            </div>
                            <div id="map" wire:ignore style="height: 600px; position: relative; overflow: hidden;"><div style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);"><div class="gm-err-container"><div class="gm-err-content"><div class="gm-err-icon"><img src="https://maps.gstatic.com/mapfiles/api-3/images/icon_error.png" alt="" draggable="false" style="user-select: none;"></div><div class="gm-err-title">Oops! Something went wrong.</div><div class="gm-err-message">This page didn't load Google Maps correctly. See the JavaScript console for technical details.</div></div></div></div></div>
                        </div>
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

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5LCZLdS1cq7-buPkwyLDjcARlFjiljYk&libraries=places&callback=initMap" async defer></script>

<script>
    function initMap() {
        var defaultLatitude = {{ $latitude }};
        var defaultLongitude = {{ $longitude }};

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: defaultLatitude, lng: defaultLongitude}, // San Francisco coordinates
            zoom: 8
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
            console.log('fldksjfldskj')
            // Livewire: Update the latitude and longitude values
        @this.updateCoordinates(event.latLng.lat(), event.latLng.lng());
        });
    }
</script>

@endpush
