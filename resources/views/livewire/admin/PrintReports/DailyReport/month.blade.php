<div class="col-md-12">
    <h4 class="text-center">Monthly Report : {{\Illuminate\Support\Carbon::now()->format('Y/m')}}</h4>
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

                    @php
                        $monthVisaTypeTotal = 0;
                        $firstDayOfMonth = \Illuminate\Support\Carbon::now()->startOfMonth();
                        $lastDayOfMonth = \Illuminate\Support\Carbon::now()->endOfMonth();

                    @endphp
                    @foreach($dayReport['visaTypes'] as $visaType)
                        <tr>
                            <td>{{$visaType->name}}</td>
                            <td>{{$visaType->applications()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->count()}}</td>
                            <td>{{$visaType->applications()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->sum('amount')}}</td>
                            @php
                                $monthVisaTypeTotal += $visaType->applications()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->sum('amount');
                            @endphp
                        </tr>
                    @endforeach

                    </tbody>
                    <!-- Total Row -->
                    <tfoot>
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td>$ {{$monthVisaTypeTotal}}</td>
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
                        $monthServiceTotal = 0;
                    @endphp
                    @foreach($dayReport['services'] as $service)
                        <tr>
                            <td>{{$service->name}}</td>
                            <td>{{$service->serviceTransactions()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->count()}}</td>
                            <td>$ {{$service->serviceTransactions()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->sum('amount')}}</td>
                            @php
                                $monthServiceTotal +=$service->serviceTransactions()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->sum('amount');
                            @endphp
                        </tr>
                    @endforeach


                    </tbody>
                    <!-- Total Row -->
                    <tfoot>
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td>$ {{$monthServiceTotal}}</td>
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
                    <td colspan="2">Monthly totals</td>
                    <td class="total-amount">$ {{$monthServiceTotal + $monthVisaTypeTotal}}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <hr>
</div>
