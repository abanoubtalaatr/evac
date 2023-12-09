<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2 form_wrapper">
            <h4>{{$page_title}}</h4>
        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3  p-2 rounded align form_wrapper">
                <div class="col-3 form-group form_wrapper">
                    <label for="status-select">@lang('admin.travel_agent')</label>
                    @include('livewire.admin.shared.agent_search_html')
                </div>
                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="from">
                </div>
                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>
                <div class="form-group col-3 mt-2 form_wrapper">
                    <button wire:click="setAgentToNull" class="btn mt-3 {{$isDirect ? 'btn-primary' :'btn-secondary'}}">Is Direct</button>
                </div>

                <div class="my-2 form_wrapper">
                @include('livewire.admin.shared.reports.actions',['url' => route('admin.travel_agents_print_applications') ,'routeName' => route('admin.test_export'),'className' => 'App\\Http\\Controllers\\Admin\\Reports\\DailyReport\\PrintController'])
                </div>
                <div class="form-group col-2 form_wrapper">
                    <button wire:click="resetData()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>
            </div>
            <hr class="form_wrapper">
            <div class="d-none form_wrapper">
            @include('livewire.admin.shared.reports.header')
            </div>
            @if(count($records['applications']) || count($records['serviceTransactions']))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" >@lang('admin.description')</th>
                        <th class="text-center">@lang('admin.type')</th>
                        <th class="text-center">@lang('admin.date')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records['applications'] as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->application_ref . ' - '. $record->first_name . ' ' . $record->last_name }}(application)</td>
                            <td class='text-center'>{{ $record->visaType->name}}</td>
                            <td class='text-center'><button class="border-0">{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d')}}</button></td>

                        </tr>
                    @endforeach
                    @foreach($records['serviceTransactions'] as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->name . ' - '. $record->surname }} (service)</td>
                            <td class='text-center'>{{ $record->service->name}}</td>
                            <td class='text-center'><button class="border-0">{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d')}}</button></td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
        </div>
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
    function printPage() {
        // Hide unnecessary elements during print
        var formWrappers = document.querySelectorAll('.form_wrapper, .form_wrapper2, .form_wrapper3');
        formWrappers.forEach(function (formWrapper) {
            formWrapper.classList.remove('d-none');

            formWrapper.style.display = 'none';
        });

        // Define a function to be called after printing
        function afterPrint() {
            // Show the hidden elements after printing
            formWrappers.forEach(function (formWrapper) {
                formWrapper.style.display = 'block';
            });

            // Remove the event listeners after printing
            window.removeEventListener('afterprint', afterPrint);
        }

        // Add an event listener for the 'afterprint' event
        window.addEventListener('afterprint', afterPrint);

        // Trigger the browser's print dialog
        window.print();
    }

</script>
<script>

    document.addEventListener('livewire:load', function () {
    Livewire.on('show-message', function () {
        var errorMessage = document.getElementById('error-message');

        // Show the message
        errorMessage.style.display = 'block';

        // Hide the message after 1000 milliseconds (1 second)
        setTimeout(function () {
            errorMessage.style.display = 'none';
        }, 1000);
    });
    });
</script>

