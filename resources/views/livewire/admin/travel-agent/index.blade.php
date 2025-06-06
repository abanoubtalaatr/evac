<main class="main-content">
    <!--head-->
    <x-admin.head />
    <!--table-->
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>{{ $page_title }}</h4>
            <button style='text-align:center;cursor:pointer' wire:click="emptyForm" wire:loading.attr="disabled"
                class="button btn-red big" id="addAgentButton">
                @lang('site.create_new')
            </button>
        </div>
        <div class="table-page-wrap">
            <div class="row mt-2">
                <div class="col-md-6 d-flex flex-column gap-2">
                    <label class="form-label text-muted">
                        Enter the new service fee that will apply to all agents
                    </label>
                    <input type="number" wire:model.defer="serviceFee" class="form-control">
                    
                    <!-- Dropdown for visa types -->
                    <label class="form-label text-muted mt-2">Select Visa Type</label>
                    <select wire:model.defer="visa_type_id" class="form-control border " style="border: 1px solid !important">
                        <option value="" >Select Visa Type</option>
                        
                        @foreach ($visaTypes as $visa)
                            <option value="{{ $visa->id }}">{{ $visa->name }}</option>
                        @endforeach
                    </select>
                    
                    <button wire:click="saveAgentPrices" class="btn btn-primary text-uppercase mt-3">
                        Save Agent Prices
                    </button>
                </div>
            </div>
            
            <div class="row d-flex align-items-center my-3 border p-2 rounded">

                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.search')</label>
                    @include('livewire.admin.shared.agent_search_html')
                </div>
                <div class="form-group col-3">
                    <label for="status-select">@lang('admin.is_active')</label>
                    <select wire:model='is_active' id='status-select' class="form-control border  contact-input">
                        <option value>@lang('admin.choose')</option>
                        <option value="1">@lang('admin.active')</option>
                        <option value="not_active">@lang('admin.not_active')</option>
                    </select>
                </div>

                <div class="form-group col-2 mt-4">
                    <button wire:click="resetData()"
                        class="btn btn-primary form-control contact-input">@lang('site.reset')</button>
                </div>


                

            </div>

            @if (count($records))
                <table class="table-page table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">@lang('admin.name')</th>
                            <th class="text-center">@lang('admin.telephone')</th>
                            <th class="text-center">@lang('admin.contact_name')</th>
                            <th class="text-center">@lang('admin.is_active')</th>
                            <th>@lang('site.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <td>#{{ ($records->currentPage() - 1) * $records->perPage() + $loop->index + 1 }}</td>

                                <td class='text-center'>{{ $record->name }}</td>
                                <td class='text-center'>{{ $record->telephone }}</td>
                                <td class='text-center'>{{ $record->contact_name }}</td>
                                <td class='text-center'>
                                    @if ($record->is_active)
                                        Active
                                    @else
                                        Not active
                                    @endif
                                </td>

                                <td>
                                    <div class="actions">
                                        <button wire:click='toggleStatus({{ $record->id }})' class="no-btn">
                                            <i
                                                class="fas @if ($record->is_active) fa-lock red @else fa-unlock green @endif"></i>
                                        </button>


                                        @include('livewire.admin.travel-agent.edit', ['agent' => $record])
                                        <a style="cursor:pointer;" wire:click="showAgent({{ $record->id }})"
                                            class="no-btn"><i class="far fa-edit blue"></i></a>

                                        @if (\App\Helpers\isOwner() && $record->is_visible == 0)
                                            <i class="fas fa-eye"></i>
                                        @endif
                                        <!-- Add this button inside the actions column -->
                                        <a href="{{ route('admin.travel-agents.visa-pricing', $record->id) }}"
                                            class="no-btn">
                                            <i class="mx-4 fas fa-dollar-sign green"></i>
                                        </a>

                                    </div>
                                </td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>

                {{ $records->links() }}
            @else
                <div class="row" style='margin-top:10px'>
                    <div class="alert alert-warning">@lang('site.no_data_to_display')</div>
                </div>
            @endif
        </div>

        @include('livewire.admin.travel-agent.add')

    </div>
</main>

@include('livewire.admin.shared.agent_search_script')
<script>
    document.getElementById('addAgentButton').addEventListener('click', function() {
        $('#agentModal').modal('show');
    });

    document.addEventListener('livewire:load', function() {
        Livewire.on('showAgentModal', function(agentId) {
            console.log()
            $('#agentModal' + agentId).modal('show');
        });
    });

    function performEmptyFormFirst() {
        Livewire.emit('emptyForm');

        // Introduce a delay (e.g., 100 milliseconds) before triggering the click event
        setTimeout(() => {
            document.getElementById('addAgentButton').click();
        }, 1000);
    }
</script>


<script>
    $(document).ready(function() {
        // ... existing JavaScript code

        Livewire.on('agentSetToNull', function() {
            // Code to handle the Livewire event, for example, set agent to null
            $('#agent_search').val(''); // Clear the input field
            // Additional logic as needed
        });
    });
</script>
