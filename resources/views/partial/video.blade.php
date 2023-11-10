<section class="video-section">
    <div class="layer"></div>
    <div class="video-overlay">
        <div class="play-button">
            <button class="btn p-0 btn_play" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <img src="{{asset('website/assets/images/play.svg')}}" alt="Play Button">

            </button>
        </div>
        <div class="text-content">
            <h2>{{$settings->video_title}}</h2>
{{--            <h2>لقوة الصيانة المهنية!</h2>--}}
            <p>{{$settings->video_description}} </p>
        </div>
    </div>
    <video autoplay loop muted>
        <source src="{{url('uploads/pics/'. $settings->video_url)}}" type="video/mp4">
    </video>
</section>
