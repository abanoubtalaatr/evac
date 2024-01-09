    @php
        $settings = \App\Models\Setting::query()->first();
       $logoPath = public_path('uploads/pics/' . $settings->logo);

    @endphp
    <div class="my-2 text-center">
        <img width="200" height="200" src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}">
    </div>

    <span class="span-block"><strong>Evac</strong></span>
    <span class="span-block"><strong>{{$settings->address}}</strong> </span>
    <span class="span-block"><strong>Reg no :{{$settings->registration_no}}</strong></span>
    <span class="span-block"><strong>Tel : {{$settings->mobile}}</strong></span>
{{--    @if(isset($settings->vat_no) && !empty($settings->vat_no))--}}
{{--        <div>Vat registration : {{$settings->vat_no}}</div>--}}
{{--    @endif--}}
<style>
    .span-block{
        display: block;
        margin-bottom: 3px;
    }
</style>
