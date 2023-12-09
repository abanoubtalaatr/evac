<div>
    @php $settings = \App\Models\Setting::query()->first(); @endphp

    {{$settings->invoice_footer}}
</div>
