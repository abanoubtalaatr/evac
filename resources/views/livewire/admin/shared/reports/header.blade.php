<div>
    @php
        $settings = \App\Models\Setting::query()->first();
       $logoPath = public_path('uploads/pics/' . $settings->logo);

    @endphp
    <div class="my-2 text-center">
        <img width="200" height="200" src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}">
    </div>

    <h4>Evac</h4>
    <h4>{{$settings->address}} </h4>
    <h4>Reg no :{{$settings->registration_no}}</h4>
    <h4>Tel : {{$settings->mobile}}</h4>
{{--    @if(isset($settings->vat_no) && !empty($settings->vat_no))--}}
{{--        <div>Vat registration : {{$settings->vat_no}}</div>--}}
{{--    @endif--}}
</div>
