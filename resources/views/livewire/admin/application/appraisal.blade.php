
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
                        <th class="text-center">@lang('admin.application_ref')</th>
                        <th class="text-center">@lang('admin.applicants')</th>
                        <th class="text-center">@lang('admin.passport_no')</th>
                        <th class="text-center">@lang('admin.travel_agent')</th>
                        <th class="text-center">@lang('admin.visa_type')</th>
                        <th class="text-center">@lang('admin.status')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->application_ref}}</td>
                            <td class='text-center'>{{$record->first_name .' '. $record->last_name}}</td>
                            <td class="text-center">{{$record->passport_no}}</td>
                            <td class='text-center'>{{$record->travelAgent ? $record->travelAgent->name :''}}</td>
                            <td class="text-center">{{$record->visaType->name}}</td>

                            <td class='text-center'>{{$record->status}}</td>

                            <td>
                                @include('livewire.admin.application.show',  ['application' => $record])

                                <div class="actions">
                                    <a style="cursor:pointer;" wire:click="showApplicationModal({{$record->id}})" class="no-btn">
                                        <i class="far fa-eye blue"></i>
                                    </a>

                                    <a style="cursor:pointer;" href="{{route('admin.applications.update', ['application' => $record])}}" class="no-btn">
                                        <i class="far fa-edit blue"></i>
                                    </a>

                                    @if($record->status == 'new')
                                        <button wire:click='appraisal({{$record->id}})' class="btn btn-primary">
                                            Appraise
                                        </button>
                                    @endif


                                    <button wire:click="showCancelConfirmation({{$record->id}})" class="btn btn-warning">
                                        Cancel
                                    </button>

                                    <button wire:click="showDeleteConfirmation({{$record->id}})" class="btn btn-danger">
                                        Delete
                                    </button>

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

        @include('livewire.admin.application.popup.delete-confirmation')
        @include('livewire.admin.application.popup.cancel-confirmation')

    </div>
</main>

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

