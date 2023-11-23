<div style="margin: 50px auto; text-align: center;">
    @php
        $settings = \App\Models\Setting::query()->first();
    @endphp
    <div style="margin: 20px 0;">
        <h6>EVAC - {{$agent ? $agent->name : ""}} Applications</h6>
        <h6>Period from: {{$from ?? ''}} To: {{$to ?? ""}}</h6>
        <h6 style="margin-top: 20px;">Tel: {{$settings ? $settings->mobile : ""}}</h6>
        <h6>IATA Registration: {{$settings ? $settings->registration_no : ""}}</h6>
    </div>

    @foreach($data as $key => $item)
        <div style="margin-bottom: 30px;">
            @php $visaType = \App\Models\VisaType::query()->find($key); @endphp
            <h5 style="margin-bottom: 15px;">{{$visaType ? $visaType->name : ''}}</h5>

            <table style="width: 100%; border-collapse: collapse; margin: 0 auto; text-align: left;">
                <thead style="border-bottom: 1px solid #dee2e6;">
                <tr>
                    <th style="padding: 8px; border: 1px solid #dee2e6;">#</th>
                    <th style="padding: 8px; border: 1px solid #dee2e6;">Application Details</th>
                    <th style="padding: 8px; border: 1px solid #dee2e6;">Submit Date</th>
                    <th style="padding: 8px; border: 1px solid #dee2e6;">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($item as $key => $application)
                    <tr style="text-align: center">
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{$key + 1}}</td>
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{$application->first_name . ' ' . $application->last_name . ' - ' . $application->application_ref}}</td>
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{\Illuminate\Support\Carbon::parse($application->created_at)->format('Y-m-d')}}</td>
                        <td style="padding: 8px; border: 1px solid #dee2e6;">{{$application->status}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <hr style="margin-top: 20px; border: 1px solid #dee2e6;">
    @endforeach
</div>
