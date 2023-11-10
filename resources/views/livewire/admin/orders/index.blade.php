
<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded">


                <div class="form-group col-3">
                    <label for="status-select">@lang('site.services')</label>
                    <select wire:model='service' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('site.service')</option>
                        @foreach($services as $service)
                        <option value="{{$service->id}}">{{app()->getLocale()=='ar' ? $service->name_ar : $service->name_en}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('site.status_order')</label>
                    <select wire:model='status_order' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('site.status_order')</option>
                        <option value="approved">@lang('site.approved')</option>
                        <option value="not_approved">@lang('site.not_approved')</option>
                        <option value="finished">@lang('site.finished')</option>
                        <option value="reject">@lang('site.rejected')</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('site.status_paid')</label>
                    <select wire:model='status_paid' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('site.status_order')</option>
                        <option value="1">@lang('site.paid')</option>
                        <option value="0">@lang('site.not_paid')</option>

                    </select>
                </div>


                <div class="form-group col-3">
                    <button wire:click="resetData()" class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>

            </div>

            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('site.service')</th>
                        <th class="text-center">@lang('validation.attributes.created_at')</th>
                        <th class="text-center">@lang('site.status_order')</th>
                        <th class="text-center">@lang('site.status_paid')</th>

                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$record->id}}</td>
                            <td class='text-center'>{{app()->getLocale() =='ar'? $record->service->name_ar: $record->service->name_en}}</td>
                            <td class='text-center'>{{$record->created_at}}</td>
                            <td class='text-center'>
                                <div class="status {{$record->status_class}}">
                                    <span>@lang('site.'.$record->status)</span>
                                </div>
                            </td>
                            <td class='text-center'>
                                <div class="status {{$record->paid}}">
                                    <span>@lang('admin.'.$record->is_paid)</span>
                                </div>
                            </td>

                            <td>
                                <div class="actions">

                                    @if($record->status =='approved')
                                        <button wire:click="finishOrder({{$record->id}})"
                                                x-data="{ loading: false }"
                                                x-on:click="loading = true"
                                                class="btn btn-warning px-2">
                                            @lang('site.finish_order')
                                            <span x-show="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </button>
                                    @endif
                                    @if($record->status =='not_approved')
                                        <button id="{{$record->id}}finished"
                                            wire:click='acceptOrder({{$record->id}})' wire:target="{{$record->id}}"
                                                x-data="{ loading: false }"
                                                x-on:click="loading = true"
                                            class="btn btn-primary px-2">
                                            @lang('site.accept_order')
                                            <span x-show="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>

                                        </button>

                                        <button type="button" class="btn btn-danger px-2" data-toggle="modal" data-target="#exampleModal{{$record->id}}">
                                            @lang('site.reject')
                                        </button>

                                        <div wire:ignore class="modal fade" id="exampleModal{{$record->id}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{$record->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel{{$record->id}}">@lang('site.reject_reason')</h5>
                                                        <button type="button" class="close border-0 bg-none" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="post" action="{{route('admin.order.reject',['order' => $record->id])}}">
                                                        <div class="modal-body">
                                                            <textarea class="form-control" required name="reject_reason"></textarea>
                                                        </div>
                                                        <div class="modal-footer" wire:ignore>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.close')</button>

                                                            @csrf
                                                            @method('post')
                                                            <button type="submit" class="btn btn-danger">@lang('site.confirm')</button>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @endif


                                        <a href='{{route('admin.order_details',$record->id)}}' class="no-btn"><i
                                                class="far fa-eye blue"></i></a>

                                </div>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>

                {{$records->links()}}
            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
        </div>
    </div>
</main>
<style>
    .primary {
        background: #34a57b;
        color:white;
    }
    .rejected {
        background: red;
        color:white;
    }
    .spinner {
        display: none;
    }
    .spinner.show {
        display: inline-block;
    }
</style>
@include('partial.modal-scripts')
