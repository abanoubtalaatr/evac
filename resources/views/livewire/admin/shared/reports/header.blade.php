<div>
    @php $settings = \App\Models\Setting::query()->first(); @endphp
    <div class="my-2 text-center">
        <img class="rounded" height="100" width="200" src="{{asset('uploads/pics/'. $settings->logo??"")}}">
    </div>

    <h4>Evac</h4>
    <h4>{{$settings->address}}</h4>
    <h4>Reg no :{{$settings->registration_no}}</h4>
    <h4>Tel : {{$settings->registration_no}}</h4>
    @if(isset($settings->vat_no) && !empty($settings->vat_no))
        <div>Vat registration : {{$settings->vat_no}}</div>
    @endif
</div>
