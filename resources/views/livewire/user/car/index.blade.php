@include('partial.modal-scripts')

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
                        <button onclick="window.location='{{route('user.cars.create')}}'" class="btn btn-1 position-relative z-index" type="button">

                            <img src="{{asset('website/assets/images/steps/Plus.svg')}}" alt="">@lang('site.add_new_car')

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="myCar_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="table_wrapper table-responsive border rounded">
                        <table class="table table-borderless mb-0">
                            <!-- start table head -->

                            <tbody><tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang("site.car_brand")</th>
                                <th scope="col">@lang("site.car_module")</th>
                                <th scope="col">@lang("site.manufacturing_year")</th>
                                <th scope="col">@lang("site.car_color")</th>
                                <th scope="col">@lang("site.plate")</th>
                                <th scope="col">@lang("site.number_of_engine_valves")</th>
                                <th scope="col">@lang("site.fuel_type")</th>
                                <th scope="col">@lang("site.created_at")</th>
                                <th scope="col">@lang("site.actions")</th>
                            </tr>
                            <!-- start table body -->
                            @foreach($records as $record)
                            <tr>
                                <th scope="row">{{$loop->index + 1}}</th>
                                <td>{{$record->carBrand->name??""}}</td>
                                <td>{{$record->carModule->name??''}}</td>
                                <td>{{$record->manufacturing_year}}</td>
                                <td>{{$record->color}}</td>
                                <td>{{$record->plat_char .' - '. $record->plat_number}}</td>
                                <td>{{$record->number_of_engine_valves}}</td>
                                <td>{{ $record->fuel_type}}</td>
                                <td>{{\Carbon\Carbon::parse($record->created_at)->diffForHumans()}}</td>
                                <td>
                                    <ul class="d-flex justify-content-evenly">

                                        <li>
                                            <a href="{{route('user.cars.edit', ['car' => $record->id])}}">
                                            <button  class="btn p-0">
                                                <img src="{{asset('website/assets/images/public/edit.svg')}}" alt="">
                                            </button>
                                            </a>
                                        </li>

                                        <li>
                                            <button type="button" class="btn p-0" data-toggle="modal" data-target="#exampleModal{{$record->id}}">
                                                <img src="{{asset('website/assets/images/public/del.svg')}}" alt="">
                                            </button>
                                        </li>
                                        <!-- Modal -->
                                        <div wire:ignore class="modal fade" id="exampleModal{{$record->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{$record->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel{{$record->id}}">{{$record->carBrand->name . ' -  ' . $record->carModule->name}}</h5>
                                                        <button type="button" class="close border-0 bg-none" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @lang('site.are_you_sure_to_delete_item')
                                                    </div>
                                                    <div class="modal-footer" wire:ignore>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.close')</button>
                                                        <form action="{{route('user.cars.delete',['car' => $record->id])}}">
                                                            @csrf
                                                            @method('post')
                                                        <button type="submit" class="btn btn-danger">@lang('site.delete')</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                            </tbody></table>
                        <div class="col-12 mt-4 d-flex justify-content-center">
                            {{$records->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
