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
                    <label for="status-select">@lang('admin.search')</label>
                    @include('livewire.admin.shared.agent_search_html')
                </div>

                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.passport')</label>
                    <input wire:model='passport' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.full_name')</label>
                    <input wire:model='fullName' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.reference_no')</label>
                    <input wire:model='referenceNo' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.visa_type')</label>
                    <select wire:model='visaType' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('admin.choose')</option>
                        @foreach($visaTypes as $visaType)
                            <option value="{{$visaType->id}}">{{$visaType->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.status')</label>
                    <select wire:model='status' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('admin.choose')</option>
                        <option value="new">New</option>
                        <option value="appraised">Appraised</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.from')</label>
                   <input class="form-control border  contact-input" type="date"  wire:model="from">
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>

                <div class="form-group col-2 mt-4">
                    <button wire:click="resetData()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>

            </div>
            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <!-- Header row here -->
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('admin.passport')</th>
                        <th class="text-center">@lang('admin.applicant')</th>
                        <th class="text-center">@lang('admin.application_ref')</th>
                        <th class="text-center">@lang('admin.travel_agent')</th>
                        <th class="text-center">@lang('admin.visa_provider')</th>
                        <th class="text-center">@lang('admin.visa_type')</th>
                        <th class="text-center">@lang('admin.status')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $visaTypeRow)
                        <tr>
                            <td colspan="9"><strong>{{$visaTypeRow[0]['visaType']['name']??'' }}</strong></td>
                        </tr>
                        @foreach($visaTypeRow as $record)

                            <tr>
                                    <td>#{{$loop->index +1}}</td>
                                    <td class='text-center'>{{$record->passport_no}}</td>
                                    <td class='text-center'>{{$record->first_name . ' ' . $record->last_name}}</td>
                                    <td class='text-center'>{{$record->application_ref}}</td>
                                    <td class='text-center'>{{$record->travelAgent ? $record->travelAgent->name :''}}</td>
                                    <td class='text-center'>{{$record->visaProvider ? $record->visaProvider->name :''}}</td>
                                    <td class='text-center'>{{$record->visaType ? $record->visaType->name :''}}</td>
                                    <td class='text-center'><button class="border-0">{{$record->status}}</button></td>
                                    <td>
                                        <div class="actions">


                                            @include('livewire.admin.application.popup.invoice', ['application' => $record])
                                            <button  style="cursor:pointer;" wire:click="showApplicationInvoice({{$record->id}})" class="btn btn-info mt-2">Edit invoice</button>

                                            <button class="btn btn-primary mt-2" onclick="printPage('{{route('admin.applications.print', ['application' => $record->id])}}')">Print</button>

                                            @include('livewire.admin.application.show',  ['application' => $record])

                                            <button wire:click="showDeleteConfirmation({{$record->id}})" class="btn btn-danger mt-2">
                                                Delete
                                            </button>

                                            <a style="cursor:pointer;" wire:click="showApplicationModal({{$record->id}})" class="no-btn mt-2">
                                                <i class="far fa-eye blue"></i>
                                            </a>

                                            <a style="cursor:pointer;" href="{{route('admin.applications.update', ['application' => $record])}}" class="no-btn mt-2">
                                                <i class="far fa-edit blue"></i>
                                            </a>
                                            <button class="btn btn-secondary mt-1" wire:click="toggleShowModal({{$record->id}})">Send email</button>
                                        </div>
                                    </td>

                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif

        </div>
        @include('livewire.admin.application.popup.send-email')
        @include('livewire.admin.application.popup.delete-confirmation')
    </div>
</main>
@include('livewire.admin.shared.agent_search_script')

<script>
    document.addEventListener('livewire:load', function () {
       Livewire.on('showApplicationInvoiceModal', function (application) {
           $('#showApplicationInvoiceModal' + application).modal('show');
       });
   });

    document.addEventListener('livewire:load', function () {
        Livewire.on('showApplicationModal', function (applicationId) {
            $('#applicationModal' + applicationId).modal('show');
        });
    });
</script>

<script>
    // Function to load content into an iframe and trigger printing
    function printPage(url) {
        // Create an iframe element
        var iframe = document.createElement('iframe');

        // Set the source URL of the iframe
        iframe.src = url;

        // Set styles to hide the iframe
        iframe.style.position = 'absolute';
        iframe.style.top = '-9999px';
        iframe.style.left = '-9999px';
        iframe.style.width = '0';
        iframe.style.height = '0';

        // Append the iframe to the document body
        document.body.appendChild(iframe);

        // Wait for the iframe to load
        iframe.onload = function() {
            // Print the content of the iframe
            iframe.contentWindow.print();
        };
    }
</script>

