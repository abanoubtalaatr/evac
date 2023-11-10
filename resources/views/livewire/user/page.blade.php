@section('content')
    <section class="head_banner">
        <div class="layer">
            &nbsp;
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="term_head">
                        <h2>{{$data['title']??''}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="terms_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>{{$data['title'] ??''}}</h3>
                    {!! $data['content']??"" !!}

                </div>
            </div>
        </div>
    </section>
@endsection
