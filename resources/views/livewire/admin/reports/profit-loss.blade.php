
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

                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="from">
                </div>
                <div class="form-group col-3 mt-2 form_wrapper">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>


                <div class="my-2 form_wrapper">
                    @include('livewire.admin.shared.reports.actions')
                </div>
                <div class="form-group col-2 form_wrapper">
                    <button wire:click="resetrecords()"
                            class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>
            </div>
            <hr class="form_wrapper">

                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="" >@lang('admin.description')</th>
                        <th class="">@lang('admin.amount')</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Total sales  : </td>
                        <td>{{$records['total_sales']}} $</td>
                    </tr>
                    <tr>
                        <td>Less dubai fee :</td>
                        <td> {{$records['dubai_fee']}} $</td>
                    </tr>
                    <tr>
                        <td>VAT : </td>
                        <td>{{$records['vat']}} $</td>
                    </tr>
                    <tr>
                        <td>P&L : </td>
                        <td>{{$records['profit_loss']}} $</td>
                    </tr>
                    <tr>
                        <td>Payment received :  </td>
                        <td>{{$records['payments_received']}} $</td>
                    </tr>
                    </tbody>
                </table>

{{--                <div class="row" style='margin-top:10px'>--}}
{{--                    <div class="alert alert-warning">@lang('site.no_records_to_display')</div>--}}
{{--                </div>--}}
{{--            --}}
        </div>
    </div>
</main>
@push('scripts')
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
        document.addEventListener('livewire:load', function () {
            Livewire.on('printTable', function (url) {
                printPage(url);
            });
        });

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

            iframe.onload = function() {
                try {
                    iframe.contentWindow.print();
                } catch (error) {
                    // Handle errors
                    console.error('Error printing:', error);
                } finally {
                    // Remove the iframe after printing or in case of an error
                    console.log('Removing iframe');
                    // document.body.removeChild(iframe);
                }
            };
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
@endpush
