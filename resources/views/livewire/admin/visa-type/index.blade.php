<main class="main-content">
    <!--head-->
    <x-admin.head/>
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{$page_title}}</h4>
            <a style='text-align:center;cursor:pointer' class="button btn-red big"  wire:click="emptyForm"  id="addVisaTypeButton">@lang('site.create_new')</a>

        </div>
        <div class="table-page-wrap">

            <div class="row d-flex align-items-center my-3 border p-2 rounded input-container">

                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.search')</label>
                    <input wire:model='search' type="text" class="form-control contact-input" autofocus>
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
                        <th class="text-center">@lang('admin.name')</th>
                        <th class="text-center">@lang('admin.dubai_fee')</th>
                        <th class="text-center">@lang('admin.service_fee')</th>
                        <th class="text-center">@lang('admin.total')</th>
                        <th class="text-center">@lang('admin.default')</th>
                        <th>@lang('site.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>#{{$loop->index + 1}}</td>
                            <td class='text-center'>{{$record->name}}</td>
                            <td class='text-center'>{{ \App\Helpers\formatCurrency($record->dubai_fee) }}</td>
                            <td class='text-center'>{{ \App\Helpers\formatCurrency($record->service_fee) }}</td>
                            <td class='text-center'>{{ \App\Helpers\formatCurrency($record->total) }}</td>
                            <td class='text-center'>
                                @if ($record->is_default)
                                    <i class="fas fa-check text-green circle"></i>
                                @else
                                    <i class="fas fa-times text-red circle"></i>
                                @endif
                            </td>
                            <td>
                                <div class="actions">

                                    @include('livewire.admin.visa-type.edit', ['visaType' => $record])
                                    <a style="cursor:pointer;" wire:click="showVisaType({{$record->id}})" class="no-btn"><i
                                            class="far fa-edit blue"></i></a>
                                    <button  wire:click="makeDefault({{$record->id}})" class="btn btn-primary">Make default</button>


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

        @include('livewire.admin.visa-type.add')

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

    document.getElementById('addVisaTypeButton').addEventListener('click', function() {
        $('#visaTypeModal').modal('show');
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('showVisaTypeModal', function (visaId) {
            $('#showVisaTypeModal' + visaId).modal('show');
        });
    });
</script>


