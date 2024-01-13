<div class="col-md-12">
    <h4 class="text-center">{{\Illuminate\Support\Carbon::now()->format('Y')}}</h4>
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
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Aug</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>
                        <th style="font-weight: bolder">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $firstDayOfMonth = now()->firstOfMonth();
                        $lastDayOfMonth = now()->lastOfMonth();
                    @endphp
                    @foreach($dayReport['visaTypes'] as $visaType)
                        <tr>
                            <td>{{ $visaType->name }}</td>
                            <td>{{ $visaType->applications()->whereYear('created_at', now()->year)->count() }}</td>
                            @for ($month = 1; $month <= 12; $month++)
                                <td>{{ $visaType->applications->where('created_at', '>=', $firstDayOfMonth->copy()->month($month))->where('created_at', '<=', $lastDayOfMonth->copy()->month($month))->count()}}</td>
                            @endfor
                            <td>{{ $visaType->applications()->whereYear('created_at', now()->year)->sum('dubai_fee') +$visaType->applications()->whereYear('created_at', now()->year)->sum('service_fee') +$visaType->applications()->whereYear('created_at', now()->year)->sum('vat') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="total-row">
                        <td>Total</td>
                        <td>
                            {{\App\Models\Application::query()->whereYear('created_at', now()->year)->count()}}
                        </td>
                        @for ($month = 1; $month <= 12; $month++)
                            <td>
                                {{ \App\Models\Application::whereMonth('created_at', $month)->whereYear('created_at', now()->year)->count() }}
                            </td>
                        @endfor
                        <td>{{ \App\Models\Application::query()->whereYear('created_at', now()->year)->sum('dubai_fee') + \App\Models\Application::query()->whereYear('created_at', now()->year)->sum('service_fee') + \App\Models\Application::query()->whereYear('created_at', now()->year)->sum('vat')}}</td>
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
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Aug</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dec</th>
                        <th style="font-weight: bolder">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $firstDayOfMonth = now()->firstOfMonth();
                        $lastDayOfMonth = now()->lastOfMonth();
                    @endphp
                    @foreach($dayReport['services'] as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->serviceTransactions()->whereYear('created_at', now()->year)->count() }}</td>
                            @for ($month = 1; $month <= 12; $month++)
                                <td>{{ $service->serviceTransactions->where('created_at', '>=', $firstDayOfMonth->copy()->month($month))->where('created_at', '<=', $lastDayOfMonth->copy()->month($month))->count()}} </td>
                            @endfor
                            <td>{{ $service->serviceTransactions()->whereYear('created_at', now()->year)->sum('dubai_fee') +  $service->serviceTransactions()->whereYear('created_at', now()->year)->sum('service_fee') + $service->serviceTransactions()->whereYear('created_at', now()->year)->sum('vat') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="total-row">
                        <td>Total</td>
                        <td>
                            {{\App\Models\ServiceTransaction::whereYear('created_at', now()->year)->count()}}
                        </td>
                        @for ($month = 1; $month <= 12; $month++)
                            <td>
                                {{ \App\Models\ServiceTransaction::whereMonth('created_at', $month)->whereYear('created_at', now()->year)->count() }}
                            </td>
                        @endfor
                        <td>{{ \App\Models\ServiceTransaction::whereYear('created_at', now()->year)->sum('dubai_fee') + \App\Models\ServiceTransaction::whereYear('created_at', now()->year)->sum('service_fee') + \App\Models\ServiceTransaction::whereYear('created_at', now()->year)->sum('vat')}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table total-table">
                <thead>
                <tr>
                    <th>Total Yearly</th>
                    <th>Qty</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                    <th style="font-weight: bolder">Total</th>

                </tr>
                </thead>
                <tbody>
                <!-- Rows for data -->
                <!-- Add more rows as needed -->
                <tr>
                    <td>Yearly total</td>
                    <td>{{ \App\Models\Application::query()->whereYear('created_at', now()->year)->count() +
                        \App\Models\ServiceTransaction::whereYear('created_at', now()->year)->count()}}</td>
                    @for ($month = 1; $month <= 12; $month++)
                        <td>
                            {{ \App\Models\ServiceTransaction::whereMonth('created_at', $month)->whereYear('created_at', now()->year)->count() +  \App\Models\Application::whereMonth('created_at', $month)->whereYear('created_at', now()->year)->count() }}
                        </td>
                    @endfor
                    @php
                    $totalForYear =0;
                     $totalForYear += \App\Models\ServiceTransaction::whereYear('created_at', now()->year)->sum('dubai_fee') + \App\Models\ServiceTransaction::whereYear('created_at', now()->year)->sum('service_fee') + \App\Models\ServiceTransaction::whereYear('created_at', now()->year)->sum('vat');
                     $totalForYear +=\App\Models\Application::query()->whereYear('created_at', now()->year)->sum('dubai_fee') + \App\Models\Application::query()->whereYear('created_at', now()->year)->sum('service_fee') + \App\Models\Application::query()->whereYear('created_at', now()->year)->sum('vat');
                    @endphp
                    <td>
                        {{ $totalForYear}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
