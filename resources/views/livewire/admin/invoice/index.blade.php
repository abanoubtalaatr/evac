<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
            <a style='text-align:center;cursor:pointer' class="button btn-red big"  wire:click="emptyForm"  id="addInvoiceButton">@lang('site.create_new')</a>

        </div>
        @if(session()->has('success'))
            <div class="alert alert-info">{{session('success')}}</div>
        @endif
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded">
                @include('livewire.admin.travel-agent.popup.send-email')

                    <div class="col-3 form-group form_wrapper">
                        <label for="status-select">@lang('admin.travel_agent')</label>
                        @include('livewire.admin.shared.agent_search_html')
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
                        <th class="text-center">@lang('admin.agent')</th>
                        <th class="text-center">@lang('admin.from')</th>
                        <th class="text-center">@lang('admin.to')</th>

                        <th class="text-center">@lang('admin.title_invoice')</th>
                        <th class="text-center">@lang('admin.total_amount')</th>
                        <th class="text-center">VAT</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class="text-center">{{$record->agent?$record->agent->name:""}}</td>
                            <td class='text-center'>{{\Illuminate\Support\Carbon::parse($record->from)->format('Y-m-d')}}</td>
                            <td class='text-center'>{{\Illuminate\Support\Carbon::parse($record->to)->format('Y-m-d')}}</td>

                            <td class='text-center'>{{$record->invoice_title}}</td>
                            <td class='text-center'>{{\App\Helpers\formatCurrency($record->total_amount)}}</td>
                            <td class='text-center'>{{\App\Helpers\formatCurrency($record->vat)}}</td>
                            <td>
                                <div class="actions">
                                    <a style="cursor:pointer;" wire:click="showInvoice({{$record->id}})" class="no-btn"><i
                                            class="far fa-eye blue"></i></a>
                                    <button class="btn btn-warning" wire:click="recalculateInvoice({{$record->id}})">Re calculate</button>
                                    @include('livewire.admin.invoice.edit', ['invoice' => $record])

                                    <button  style="cursor:pointer;" wire:click="destroy({{$record->id}})" class="btn btn-danger">Cancelled</button>
                                        <button class="btn btn-primary" wire:click="printData('{{ $record['agent_id'] }}', '{{ $record['from'] }}', '{{ $record['to'] }}')">Print</button>
                                        <button class="btn btn-secondary" wire:click="exportReport('{{ $record['agent_id'] }}', '{{ $record['from'] }}', '{{ $record['to'] }}')">CSV</button>
                                        <button class="btn btn-info" wire:click="toggleShowModal('{{ $record['agent_id'] }}', '{{ $record['from'] }}', '{{ $record['to'] }}')">Email</button>


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

        @include('livewire.admin.invoice.add')

    </div>
</main>
<style>
    .circle{
        width: 25px;
        background: black;
        color: white;
        padding: 4px;
        border-radius: 50%;
    }
</style>
<script>
    document.getElementById('addInvoiceButton').addEventListener('click', function() {
        $('#invoiceModal').modal('show');
    });
    document.addEventListener('livewire:load', function () {
         Livewire.on('showInvoiceModal', function (invoice) {
             $('#showInvoiceModal' + invoice).modal('show');
         });
     });
</script>
@include('livewire.admin.shared.agent_search_script')
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


