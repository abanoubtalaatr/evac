<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
             <a style='text-align:center' href='{{route('admin.create_car_brands')}}' class="button btn-red big">@lang('site.create')</a>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded">


                <div class="form-group col-3">
                    <label for="status-select">@lang('validation.attributes.name')</label>
                    <input wire:model='name' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('site.status')</label>
                    <select wire:model='status' id='status-select' class="form-control  contact-input">
                        <option value>@lang('site.status')</option>
                        <option value="active">@lang('site.active')</option>
                        <option value="inactive">@lang('site.inactive')</option>
                    </select>
                </div>

                <div class="form-group col-3">
                    <button wire:click="resetData()" class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>

            </div>
            <span id="message">
    @if(isset($message))
                    <div class="alert alert-warning">
            {{$message}}
        </div>
                @endif
</span>
            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('validation.attributes.name_ar')</th>
                        <th class="text-center">@lang('validation.attributes.name_en')</th>
                        <th class="text-center">@lang('validation.attributes.created_at')</th>
                        <th class="text-center">@lang('site.status')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr  >

                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->name_ar}}</td>
                            <td class='text-center'>{{$record->name_en}}</td>
                            <td class='text-center'>{{$record->created_at}}</td>
                            <td class='text-center'>
                                <div class="status {{$record->status_class}}">
                                    <span>@lang('site.'.$record->status)</span>
                                </div>
                            </td>

                            <td>
                                <div class="actions">
                                    <button
                                        wire:click='toggleStatus({{$record->id}})'
                                        class="no-btn">
                                        <i class="fas @if($record->status=='active') fa-lock red @else fa-unlock green @endif"></i>
                                    </button>
                                    <a href='{{route('admin.edit_car_brands',$record->id)}}' class="no-btn"><i
                                            class="far fa-edit blue"></i></a>
{{--                                    <button type="button" class="no-btn" data-bs-toggle="modal" data-bs-target="#deleteModal{{$record->id}}" >--}}
{{--                                        <i class="fas fa-trash"></i>--}}
{{--                                    </button>--}}

                                </div>
                            </td>
                            <div  class="modal fade" id="deleteModal{{$record->id}}" tabindex="-1" aria-labelledby="deleteModalLabel{{$record->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{$record->id}}">@lang('site.confirm_delete')</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @lang('site.are_you_sure_to_delete_item')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('site.cancel')</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="destroy({{$record->id}})">@lang('site.delete')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                            @endforeach


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
@push('script')
    <script>
        setTimeout(function() {
            document.getElementById("message").style.display = "none";
        }, 2000);
    </script>
@endpush
@push('style')
    .btn:focus {
    outline: none;
    box-shadow: none;
    }
@endpush


