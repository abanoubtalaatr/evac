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
                        $monthVisaTypeCount = 0;

                    @endphp
                    @foreach($dayReport['visaTypes'] as $visaType)
                        @php
                        $monthCount = $visaType->applications()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->count();
                        $monthVisaTypeCount += $monthCount;
                        @endphp
                        <tr>
                            <td>{{$visaType->name}}</td>
                            <td>{{$monthCount}}</td>
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
                        <td colspan="">Total</td>
                        <td>{{$monthVisaTypeCount}}</td>
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
                        $monthServiceCount =0;
                    @endphp
                    @foreach($dayReport['services'] as $service)
                        @php
                            $monthService = $service->serviceTransactions()->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])->count();
                            $monthServiceCount +=$monthService;
                        @endphp
                        <tr>
                            <td>{{$service->name}}</td>
                            <td>{{$monthService}}</td>
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
                        <td colspan="">Total</td>
                        <td>{{$monthServiceCount}}</td>
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
                    <td colspan="">Monthly totals</td>
                    <td>{{$monthServiceCount + $monthVisaTypeCount}}</td>
                    <td class="total-amount">$ {{$monthServiceTotal + $monthVisaTypeTotal}}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <hr>
</div>
