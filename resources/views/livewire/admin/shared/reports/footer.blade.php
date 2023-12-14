<div>
    @php $settings = \App\Models\Setting::query()->first(); @endphp

    <div class="my-3">
        {{$settings->invoice_footer}}
    </div>
</div>
