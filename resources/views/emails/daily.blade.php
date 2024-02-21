<div>
    @php
        $closedBy = \App\Models\DayOffice::query()->where('day_status', "0")->latest()->first();
        $today = \Illuminate\Support\Carbon::today();
        $applications_created_today = \App\Models\Application::whereDate('created_at', $today)->count();
        $serviceTransactionsToday = \App\Models\ServiceTransaction::whereDate('created_at', $today)->count();

        $html ='';
        $admin = null; // Initialize $admin to null

        if($closedBy){
            $admin = \App\Models\Admin::query()->find($closedBy->end_admin_id);
            $today = \Illuminate\Support\Carbon::parse(now())->format('Y-m-d');
            $html .= "<p>Day closed by ( $admin->name - $today)</p>";
        }
    @endphp

        <!-- Your HTML content here -->

    <p>Day Closed by {{$admin->name}} - {{\Carbon\Carbon::today()->format('Y-m-d')}}</p>
    <p>Total visa applications today {{$applications_created_today}}</p>
    <p>Total services today {{ $serviceTransactionsToday }}</p>
</div>

