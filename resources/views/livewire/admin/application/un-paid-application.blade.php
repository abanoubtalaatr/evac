<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded input-container">

                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.passport')</label>
                    <input wire:model.defer='passport' type="text" class="form-control contact-input" autofocus>
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.first_name')</label>
                    <input wire:model.defer='firstName' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.surname')</label>
                    <input wire:model.defer='lastName' type="text" class="form-control contact-input">
                </div>

                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model.defer="from">
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model.defer="to">
                </div>



                <div class="form-group col-2 mt-4">
                    <button wire:click="searchUnPaidApplication()" id="searchButton"
                            class="btn btn-primary form-control contact-input">@lang('site.search')</button>
                </div>
                <div class="form-group col-2 mt-4">
                    <button wire:click="resetData()"
                            class="btn btn-warning form-control contact-input">@lang('site.reset')</button>
                </div>


            </div>

            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('admin.passport')</th>
                        <th class="text-center">@lang('admin.application_ref')</th>

                        <th class="text-center">@lang('admin.applicant')</th>
                        <th class="text-center">@lang('admin.amount')</th>

                        {{--                        <th class="text-center">@lang('admin.application_ref')</th>--}}
{{--                        <th class="text-center">@lang('admin.travel_agent')</th>--}}
{{--                        <th class="text-center">@lang('admin.visa_provider')</th>--}}
{{--                        <th class="text-center">@lang('admin.visa_type')</th>--}}
                        <th class="text-center">@lang('admin.submit_date')</th>
                        <th class="text-center">@lang('admin.type')</th>
                        <th class="text-center">@lang('admin.payment_method')</th>

                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->passport_no}}</td>
                            <td class='text-center'>{{$record->application_ref}}</td>

                            <td class='text-center'>{{$record->first_name . ' ' . $record->last_name}}</td>
                            <td class='text-center'>{{ \App\Helpers\formatCurrency($record->vat + $record->dubai_fee + $record->service_fee) }}</td>

                            {{--                            <td class='text-center'>{{$record->application_ref}}</td>--}}
{{--                            <td class='text-center'>{{$record->travelAgent ? $record->travelAgent->name :''}}</td>--}}
{{--                            <td class='text-center'>{{$record->visaProvider ? $record->visaProvider->name :''}}</td>--}}
{{--                            <td class='text-center'>{{$record->visaType ? $record->visaType->name :''}}</td>--}}
{{--                            <td class='text-center'><button class="border-0">{{$record->status}}</button></td>--}}
                            <td class='text-center'>{{ \Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d') }}</td>
                            <td class='text-center'>
                                @if($record->travel_agent_id)
                                    <button class="border-0 rounded bg-info">
                                         Agent
                                    </button>
                                @else
                                    <button class="border-0 rounded " style="background:  #24ee88">
                                        Direct
                                    </button>
                                @endif

                            </td>

                            <td class='text-center'><button class="border-0">{{$record->payment_method}}</button></td>

                            <td>
                                <div class="actions">

                                        <button class="btn btn-primary mt-2" wire:click="showPayInvoiceConfirmation({{$record->id}})">Pay invoice</button>

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
        @include('livewire.admin.application.popup.pay-invoice')
    </div>
</main>

@include('livewire.admin.shared.move_using_tab')

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('showApplicationInvoiceModal', function (application) {
            $('#showApplicationInvoiceModal' + application).modal('show');
        });
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('showPayInvoiceModal', function (applicationId) {
            $('#payInvoiceModal' + applicationId).modal('show');
        });
    });
</script>


