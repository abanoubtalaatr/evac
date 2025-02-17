<div>
    @php $settings = \App\Models\Setting::query()->first(); @endphp

    <div class="my-3" style="font-size: 12px;">
        {{$settings->invoice_footer}}
    </div>
    @if(\App\Helpers\isExistVat())
                <div class="" style="font-size: 12px;">VAT Regt : {{\App\Helpers\registrationNumber()}}</div>
                @endif
</div>
