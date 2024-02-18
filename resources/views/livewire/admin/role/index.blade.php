<main class="main-content">
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
            <a style='text-align:center' href='{{route('admin.create_role')}}'
               class="button btn-red big">@lang('site.create_new')</a>
        </div>


        <div class="table-page-wrap">
            <div class="row d-flex align-items-center my-3 border p-2 rounded input-container">
                <div class="form-group col-3">
                    <label for="status-select">@lang('validation.attributes.name')</label>
                    <input wire:model='name' type="text" class="form-control contact-input" autofocus>
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('site.status')</label>
                    <select wire:model='status' id='status-select' class="form-control  contact-input">
                        <option value>@lang('site.status')</option>
                        <option value="active">@lang('site.active')</option>
                        <option value="inactive">@lang('site.inactive')</option>
                    </select>
                </div>

                <div class="form-group col-2 mt-4">
                    <button wire:click="resetData()" class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>
            </div>
            <div class="table-responsive">
                @if(count($records))
                    <table class="table-page table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('site.name_en')</th>
                            <th>@lang('site.name_ar')</th>
                            <th>@lang('site.created_at')</th>
                            <th class="text-center">@lang('site.status')</th>
                            <th>@lang('site.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>#{{$loop->index + 1}}</td>
                                <td>{{$record->name}}</td>
                                <td>{{$record->name_ar}}</td>
                                <td>{{$record->created_at}}</td>
                                <td class='text-center'>
                                    <div class="status {{$record->is_active ?'green': 'yellow'}}">
                                        <span>{{$record->is_active ? trans('site.active') : trans('site.inactive') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a class="no-btn" href='{{route('admin.edit_role',$record)}}'><i
                                                class="far fa-edit blue"></i></a>
                                        @if(!$record->is_owner)
                                            <button
                                                wire:click='toggleStatus({{$record->id}})'
                                                class="no-btn">
                                                <i class="fas @if($record->is_active==1) fa-lock red @else fa-unlock green @endif"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$records->links()}}
                @else
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                @endif

            </div>
        </div>
    </div>
</main>
@include('livewire.admin.shared.move_using_tab')
