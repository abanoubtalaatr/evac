@php
    $settings = \App\Models\Setting::query()->first();
    $logoPath = $settings->logo ? public_path('uploads/pics/' . $settings->logo) : null;
@endphp

<div class="my-2 text-center">
    @if($logoPath && file_exists($logoPath))
        <img width="220" height="200" src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}">
    @endif
    @if(isset($showInvoiceTitle))
    <h4 class="text-center">INVOICE</h4>
    @endif
    @if(isset($showReportsAgentStatement))
    <h4 class="text-center">STATEMENT {{ \Carbon\Carbon::now()->format('d M Y') }}</h4>

    @endif
</div>

    <span class="span-block">Evac</span>
    <span class="span-block">{{$settings->address}} </span>
    <span class="span-block">Reg no :{{$settings->registration_no}}</span>
    <span class="span-block">Tel : {{$settings->mobile}}</span>
{{--    @if(isset($settings->vat_no) && !empty($settings->vat_no))--}}
{{--        <div>Vat registration : {{$settings->vat_no}}</div>--}}
{{--    @endif--}}
<style>
    .span-block{
        display: block;
        margin-bottom: 3px;
        font-size: 12px;
    }
</style>
