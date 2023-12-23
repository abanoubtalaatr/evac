<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
            <a style='text-align:center;cursor:pointer' class="button btn-red big"  wire:click="emptyForm"  id="addInvoiceButton">@lang('site.create_new')</a>

        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded">

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
                        <th class="text-center">@lang('admin.date')</th>
                        <th class="text-center">@lang('admin.title_invoice')</th>
                        <th class="text-center">@lang('admin.total_amount')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class="text-center">{{$record->agent?$record->agent->name:""}}</td>
                            <td class='text-center'>{{\Illuminate\Support\Carbon::parse($record->created_at)->format('Y-m-d')}}</td>
                            <td class='text-center'>{{$record->invoice_title}}</td>
                            <td class='text-center'>{{$record->total_amount}}</td>

                            <td>
                                <div class="actions">

                                    @include('livewire.admin.invoice.edit', ['invoice' => $record])
                                    <a style="cursor:pointer;" wire:click="showInvoice({{$record->id}})" class="no-btn"><i
                                            class="far fa-edit blue"></i></a>
                                    <button  style="cursor:pointer;" wire:click="destroy({{$record->id}})" class="btn btn-danger">Delete</button>

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


