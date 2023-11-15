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
                    <label for="status-select">@lang('admin.visa_type')</label>
                    <select wire:model='visaType' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('admin.choose')</option>
                        @foreach($visaTypes as $visaType)
                            <option value="{{$visaType->id}}">{{$visaType->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-2">
                    <button wire:click="resetData()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>

            </div>

            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('admin.applicants')</th>
                        <th class="text-center">@lang('admin.submit_date')</th>
                        <th class="text-center">@lang('admin.travel_agent')</th>
                        <th class="text-center">@lang('admin.status')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->first_name .' '. $record->last_name}}</td>
                            <td class='text-center'>{{\Carbon\Carbon::parse($record->created_at)->format('Y-m-d h:m')}}</td>
                            <td class='text-center'>{{$record->travelAgent? $record->travelAgent->name:''}}</td>
                            <td class='text-center'>{{$record->status}}</td>

                            <td>
                                <div class="actions">
                                    <button
                                        wire:click='toggleStatus({{$record->id}})'
                                        class="no-btn">
                                        <i class="fas @if($record->is_active) fa-lock red @else fa-unlock green @endif"></i>
                                    </button>

                                    @include('livewire.admin.application.show',  ['application' => $record])
                                    <a style="cursor:pointer;" wire:click="showApplicationModal({{$record->id}})" class="no-btn"><i
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

<script>


    document.addEventListener('livewire:load', function () {
        Livewire.on('showApplicationModal', function (applicationId) {
            $('#applicationModal' + applicationId).modal('show');
        });
    });
</script>

