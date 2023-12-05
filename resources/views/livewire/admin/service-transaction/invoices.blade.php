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
                    <label for="status-select">@lang('admin.passport_no')</label>
                    <input wire:model='passportNo' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.name')</label>
                    <input wire:model='name' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.surname')</label>
                    <input wire:model='surname' type="text" class="form-control contact-input">
                </div>
{{--                <div class="form-group col-3">--}}
{{--                    <label for="status-select">@lang('admin.agent')</label>--}}
{{--                    @include('livewire.admin.shared.agent_search_html')--}}
{{--                </div>--}}
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.service')</label>
                    <input wire:model='service' type="text" class="form-control contact-input">
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.from')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="from">
                </div>
                <div class="form-group col-3 mt-2">
                    <label for="status-select">@lang('admin.to')</label>
                    <input class="form-control border  contact-input" type="date" wire:model="to">
                </div>
                <div class="form-group col-2 mt-4">
                    <button wire:click="resetData()" class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>

            </div>
            @if(count($records))
                <table class="table-page table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">@lang('admin.service')</th>
                        <th class="text-center">@lang('admin.agent')</th>
                        <th class="text-center">@lang('admin.passport_no')</th>
                        <th class="text-center">@lang('admin.name')</th>
                        <th class="text-center">@lang('admin.surname')</th>
                        <th class="text-center">@lang('admin.vat')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->service? $record->service->name:"--"}}</td>
                            <td class='text-center'>{{$record->agent ? $record->agent->name :"--"}}</td>
                            <td class='text-center'>{{$record->passport_no}}</td>
                            <td class='text-center'>{{$record->name}}</td>
                            <td class='text-center'>{{$record->surname}}</td>
                            <td class='text-center'>{{$record->vat}}</td>
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
        @include('livewire.admin.service-transaction.popup.pay-invoice')

    </div>
</main>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="{{asset('js/select2.min.js')}}"></script>
    <script>
        window.addEventListener('onContentChanged', () => {
            $('#agent_id').select2();
        });

        $(document).ready(()=>{
            $('#agent_id').select2();
            $('#agent_id').change(e=>{
            @this.set('form.agent_id', $('#agent_id').select2('val'));
            });

        });
    </script>
@endpush
@push('styles')
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet"/>
@endpush
@include('livewire.admin.shared.agent_search_script')


<script>
    document.getElementById('addServiceTransactionButton').addEventListener('click', function() {
        $('#serviceTransactionModal').modal('show');
    });
    document.addEventListener('livewire:load', function () {
        Livewire.on('showServiceTransactionModal', function (transaction) {
            $('#showServiceTransactionModal' + transaction).modal('show');
        });
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('showServiceTransactionInvoiceModal', function (transaction) {
            $('#showServiceTransactionInvoiceModal' + transaction).modal('show');
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
