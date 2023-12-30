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
                    <label for="status-select">@lang('admin.passport')</label>
                    <input wire:model='passport' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.agent')</label>
                    <input wire:model='agent' type="text" class="form-control contact-input">
                </div>

                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="from">
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>

                <div class="form-group col-2 mt-2">
                    <button wire:click="resetData()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>

            </div>

            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('admin.application_ref')</th>

                        <th class="text-center">@lang('admin.passport')</th>
                        <th class="text-center">@lang('admin.applicant')</th>
                        <th class="text-center">@lang('admin.deleted_date')</th>
                        <th class="text-center">@lang('admin.travel_agent')</th>

                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->reference_no}}</td>
                            <td class='text-center'>{{$record->passport_no}}</td>
                            <td class='text-center'>{{$record->applicant_name}}</td>
                            <td class='text-center'>{{\Carbon\Carbon::parse($record->deletion_date)->format('Y-m-d h:m')}}</td>
                            <td class='text-center'>{{$record->agent ? $record->agent->name :''}}</td>


                            <td>
                                <div class="actions">
                                    @include('livewire.admin.application.popup.show-deleted-application',  ['application' => $record])
                                    <a style="cursor:pointer;" wire:click="showApplicationModal({{$record->id}})" class="no-btn">
                                        <i class="far fa-eye blue"></i></a>
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
