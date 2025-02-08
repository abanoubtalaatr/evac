<main class="main-content" id="main-content">
    <x-admin.head/>
    <div class="border-div">
        <div class="b-btm flex-div-2">
            <h4>Visa Pricing for Agent: {{ $agentName }}</h4>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
        <div class="table-page-wrap">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Visa Type</th>
                            <th>Dubai Price</th>
                            <th>Service Fee Price</th>
                            <th>Agent Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visaTypes as $visaType)
                            <tr>
                                <td>{{ $visaType->name }}</td>
                                <td>$ {{ $visaType->dubai_fee }}</td>
                                <td>$ {{ $visaType->service_fee }}</td>
                                <td>
                                    <input
                                        type="number"
                                        wire:model="agentPrices.{{ $visaType->id }}.price"
                                        id="agent_price_{{ $visaType->id }}"
                                        name="agent_price_{{ $visaType->id }}"
                                        class="form-control"
                                        style="width: 100px;"
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-4">
                <button wire:click="savePrices" class="btn btn-primary">Save Prices</button>
            </div>
        </div>
    </div>
</main>