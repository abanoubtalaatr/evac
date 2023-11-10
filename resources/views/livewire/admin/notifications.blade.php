
     <main class="main-content">
          <!--head-->
         <x-admin.head/>
          <!--dashboard-->
          <section class="dashboard p-3">
              <div class="row">
                <div class="col-md-12">
                  <div class="border bg-white rounded p-lg-5 p-3 mb-3">
                    <h2 class="head-term">{{$page_title}}</h2>
                    @if(count($records))
                        @foreach($records as $record)

                            <div class=" notif  bg-white p-3 d-flex">
                                <div class="">
                                <img src="{{$record->image_url}}" alt="">
                                </div>
                                <div class=" notif-info">
                                    <h6>{{$record->created_at}}</h6>
                                    <p>{{$record->{"content_".app()->getLocale()} .' '.  __('site.order_id')}} "{{$record->subject_id}}"</p>
                                </div>
                            </div>

                        @endforeach
                        {{$records->links()}}
                    @else
                        <div class="alert alert-warning">
                            @lang('site.no_data_to_display')
                        </div>
                    @endif

                  </div>
                </div>

              </div>

          </section>

        </main>


@push('styles')
    <link rel="stylesheet" href="{{asset('css/notifications.css')}}">
@endpush
