<div>
    @php $settings = \App\Models\Setting::query()->first(); @endphp

    <div class="my-3" style="font-size: 12px;">
        {{$settings->invoice_footer}}
    </div>
</div>
