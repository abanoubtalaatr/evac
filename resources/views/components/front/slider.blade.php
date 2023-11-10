<div class="row">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($slides as $slide)
                                <div class="carousel-item @if($loop->first) active @endif">
                                    <div class="row ">
                                        <div class="col-lg-7">
                                            <div class="hero-box mt-lg-5">
                                                <img src="{{$slide->picture_url}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-5 d-flex align-items-center">
                                            <div class="hero-info">
                                                <h1>{{$slide->{"line1_".app()->getLocale()} }}</h1>
                                                <p>{{$slide->{"line2_".app()->getLocale()} }}</p>
                                                <a href="{{$slide->{"button_link_".app()->getLocale()} }}" class="btn btn-1">{{$slide->{"button_text_".app()->getLocale()} }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Next</span>
                        </button>
                      </div>
                </div>
