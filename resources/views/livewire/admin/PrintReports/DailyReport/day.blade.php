<div class="col-md-12">
    @php
        $visaTypeTotal = 0;
        $today = \Illuminate\Support\Carbon::parse(\App\Models\DayOffice::query()->latest()->first()->day_start)->format('Y-m-d');
;

    @endphp
    <h4 class="text-center">Day Report : {{\Illuminate\Support\Carbon::parse($today)->format('Y/m/d')}}</h4>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Visas</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Visa Type</th>
                        <th>Qty</th>
                        <th>Daily Total</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($dayReport['visaTypes'] as $visaType)
                        <tr>
                            <td>{{$visaType->name}}</td>
                            <td>{{$visaType->applications()->whereDate('created_at', $today)->count()}}</td>
                            <td>$ {{$visaType->applications()->whereDate('created_at', $today)->sum('amount')}}</td>
                            @php
                                $visaTypeTotal += $visaType->applications()->whereDate('created_at', $today)->sum('amount');
                             @endphp
                        </tr>
                    @endforeach


                    </tbody>
                    <!-- Total Row -->
                    <tfoot>
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td>$ {{$visaTypeTotal}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Services</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Service</th>
                        <th>Qty</th>
                        <th>Daily Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                    $todayServiceTotal = 0;
                    @endphp
                    @foreach($dayReport['services'] as $service)
                        <tr>
                            <td>{{$service->name}}</td>
                            <td>{{$service->serviceTransactions()->whereDate('created_at', $today)->count()}}</td>
                            <td>$ {{$service->serviceTransactions()->whereDate('created_at', $today)->sum('amount')}}</td>
                            @php
                            $todayServiceTotal +=$service->serviceTransactions()->whereDate('created_at', $today)->sum('amount');
                             @endphp
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td>$ {{$todayServiceTotal}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card total-card">
        <div class="card-body">
            <table class="table total-table">
                <tfoot>
                <tr class="total-row">
                    <td colspan="2">Daily totals</td>
                    <td class="total-amount">$ {{$todayServiceTotal + $visaTypeTotal}}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <hr>
</div>
