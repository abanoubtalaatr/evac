<div>
    @php
        $settings = \App\Models\Setting::query()->first();
       $logoPath = public_path('uploads/pics/' . $settings->logo);

    @endphp
    <div class="my-2 text-center">
        <img src="https://dev.evaclb.com/uploads/pics/2023/11/17/BSDdW4RdKoI2yaILbTHgPAkNS3M3VY96c1NDp5Gt7TPXvv5NwX.png" alt="" style="width: 150px; height: 150px;">
    </div>

    <h4>Evac</h4>
    <h4>{{$settings->address}} </h4>
    <h4>Reg no :{{$settings->registration_no}}</h4>
    <h4>Tel : {{$settings->mobile}}</h4>
{{--    @if(isset($settings->vat_no) && !empty($settings->vat_no))--}}
{{--        <div>Vat registration : {{$settings->vat_no}}</div>--}}
{{--    @endif--}}
</div>
