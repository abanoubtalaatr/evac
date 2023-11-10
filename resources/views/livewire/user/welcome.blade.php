
    <!DOCTYPE html>
<html lang="en">
<!-- Start head -->

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title> </title>
    <!-- bootstrap included -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.rtl.min.css')}}">
    <!-- slick slider -->
    <link rel="stylesheet" href="{{asset('css/slick-theme.css')}}" />
    <link rel="stylesheet" href="{{asset('css/slick.css')}}" />
    <link rel="stylesheet" href="{{asset('css/jquery-steps.css')}}" />

    <!-- font Awesome  library-->
    <link defer rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- start css file -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}" />
    <!-- start responsive -->
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}" />
</head>
<!-- start body -->

<body>
<!-- start header -->
<header>
    <div class="sub_nav">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <ul class="social_top_right d-flex">
                        <li><a href="#"><img src="{{asset('images/x.svg')}}" alt=""></a></li>
                        <li><a href="#"><img src="{{asset('images/insta.svg')}}" alt=""></a></li>
                        <li><a href="#"><img src="{{asset('images/youtube.svg')}}" alt=""></a></li>
                        <li><a href="#"><img src="{{asset('images/facebook.svg')}}" alt=""></a></li>

                    </ul>
                </div>
                <div class="col-md-6 d-md-flex justify-content-end">
                    <ul class="social_top_left d-flex">
                        <li><a href="#">buji@support.com <img src="{{asset('images/Mail.svg')}}" alt=""></a></li>
                        <li><a href="#">966-533-255 <img src="{{asset('images/phone.svg')}}" alt=""></a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="{{asset('images/logo.svg')}}" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">الصفحة الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">الخدمات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">اتصل بنا</a>
                    </li>
                    <!-- in case login un hash this -->

                    <li class="nav-item">
                        <a class="nav-link" href="#"><svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.6328 7.31363C16.6328 10.509 14.6425 13.2514 11.9922 13.2514C9.33979 13.2514 7.35154 10.509 7.35154 7.31263C7.35154 4.11823 9.06342 2.25 11.9922 2.25C14.9209 2.25 16.6328 4.11723 16.6328 7.31363ZM3.84736 20.3943C4.24439 20.8523 5.9542 22.2525 11.9922 22.2525C18.0301 22.2525 19.7389 20.8523 20.137 20.3953C20.1739 20.3513 20.2012 20.3006 20.2171 20.246C20.2331 20.1915 20.2374 20.1344 20.2298 20.0782C20.139 19.1961 19.3202 15.2516 11.9922 15.2516C4.66411 15.2516 3.84529 19.1961 3.75351 20.0782C3.74604 20.1345 3.75052 20.1916 3.76665 20.2462C3.78279 20.3007 3.81026 20.3504 3.84736 20.3943Z"/>
                            </svg>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                                <span class="notif">
                                   2
                                </span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_2_5658)">
                                    <path d="M2 20C2 21.1 2.9 22 4 22H20C21.1 22 22 21.1 22 20V7H17.91C17.4315 4.167 14.967 2 12 2C9.033 2 6.5685 4.167 6.0905 7H2V20ZM12 4C13.8595 4 15.4225 5.2775 15.8685 7H8.1315C8.5775 5.2775 10.1405 4 12 4Z" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_2_5658">
                                        <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                    </li>
                    <!-- in case login un hash this -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                                <span class="notif">
                                  1
                                </span>
                            <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.0294 12.74L14.0294 11.08C13.8194 10.71 13.6294 10.01 13.6294 9.6V7.07C13.6294 4.72 12.2494 2.69 10.2594 1.74C9.73936 0.82 8.77936 0.25 7.67936 0.25C6.58936 0.25 5.6094 0.84 5.0894 1.77C3.1394 2.74 1.7894 4.75 1.7894 7.07V9.6C1.7894 10.01 1.5994 10.71 1.3894 11.07L0.379396 12.74C-0.0206042 13.41 -0.110604 14.15 0.139396 14.83C0.379396 15.5 0.949396 16.02 1.6894 16.27C3.6294 16.93 5.6694 17.25 7.70936 17.25C9.74936 17.25 11.7894 16.93 13.7294 16.28C14.4294 16.05 14.9694 15.52 15.2294 14.83C15.4894 14.14 15.4194 13.38 15.0294 12.74Z"/>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn_login" href="#"> تسجيل الدخول</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link  btn_login2 " href="#">حساب جديد</a>
                    </li> -->
                </ul>

            </div>
        </div>
    </nav>
</header>
<!-- start main -->
<main>
    <!-- start hero section -->
    <section class="hero">
        <div class="layer"></div>
        <div class="container">
            <div class="hero_slider">
                <div class="hero_info">
                    <h2>مرحبا بكم فى بوجى </h2>
                    <h1>دعنا ناخذ خدمتك </h1>
                    <p>يعتبر بوجى من افضل المواقع لخدمتك وراحتك وانت فى بيتك
                        ولعرض مزيد من الخدمات الخاصة بسيارتكم حيث توجد العديد من الخدمات
                        لذلك من فضلك توجه الى الخدمة التى تريدها من القائمة</p>
                    <button class="btn btn-1">المزيد</button>
                </div>
                <div class="hero_info">
                    <h2>مرحبا بكم فى بوجى </h2>
                    <h1>دعنا ناخذ خدمتك </h1>
                    <p>يعتبر بوجى من افضل المواقع لخدمتك وراحتك وانت فى بيتك
                        ولعرض مزيد من الخدمات الخاصة بسيارتكم حيث توجد العديد من الخدمات
                        لذلك من فضلك توجه الى الخدمة التى تريدها من القائمة</p>
                    <button class="btn btn-1">المزيد</button>
                </div>
                <div class="hero_info">
                    <h2>مرحبا بكم فى بوجى </h2>
                    <h1>دعنا ناخذ خدمتك </h1>
                    <p>يعتبر بوجى من افضل المواقع لخدمتك وراحتك وانت فى بيتك
                        ولعرض مزيد من الخدمات الخاصة بسيارتكم حيث توجد العديد من الخدمات
                        لذلك من فضلك توجه الى الخدمة التى تريدها من القائمة</p>
                    <button class="btn btn-1">المزيد</button>
                </div>
            </div>
        </div>

    </section>
    <!-- start about -->
    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="heading_box">
                        <h3>من نحن </h3>
                        <h2>اكتشف الحلول المناسبة لسيارتك!</h2>
                        <p>خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له  يغامضة في نص لوريم إيبسوم وهي "consectetur"، وخلال تتبعه لهذهde Finibus Bonorum et Malorum  الميلاد. هذا الكتاب هو بمثابة مقالة علمية مطولة في نظرية الأخلاق، وكان له شعبية كبيرة في عصر النهضة. السطر الأول</p>
                        <p>خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له  يغامضة في نص لوريم إيبسوم وهي نظرية الأخلاق، وكان له شعبية كبيرة في عص ر   لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له لوريم إيبسوم ليس نصاَ عشوائياً، بل إن له النهضة. السطر الأول <a href="#" class="btn_link">المزيد</a></p>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ab_box2">
                        <img src="{{asset('images/ab1.png')}}" alt="">
                    </div>
                    <div class="ab_info">
                        <div class="text-center">
                            <p> +10</p>
                            <p>سنوات من الخبرة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="ab_box">
                        <img src="{{asset('images/ab-2.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- start services -->
    <section class="services">
        <div class="layer"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="heading_box">
                        <h3>خدماتنا </h3>
                        <h2>تاكد ان سيارتك فى ايد امينه</h2>
                        <p>يعتبر بوجى من افضل المواقع لخدمتك وراحتك وانت فى بيتك حيث انه هو
                            ولعرض مزيد من الخدمات الخاصة بسيارتكم حيث توجد العديد من الخدمات
                            لذلك من فضلك توجه الى الخدمة التى تريدها من القائمة من </p>
                        <div class="d-lg-flex justify-content-between">
                            <ul class="serv_list">
                                <li><a href="#"><i class="fa-solid fa-check"></i> الصيانة الشاملة للسيارات</a></li>
                                <li><a href="#"><i class="fa-solid fa-check"></i> إصلاحات ميكانيكية</a></li>
                                <li><a href="#"><i class="fa-solid fa-check"></i> الخدمات الكهربائية</a></li>

                            </ul>
                            <ul class="serv_list">
                                <li><a href="#"><i class="fa-solid fa-check"></i> خدمات هياكل السيارات والدهان</a></li>
                                <li><a href="#"><i class="fa-solid fa-check"></i> خدمات الإطارات والعجلات</a></li>
                                <li><a href="#"><i class="fa-solid fa-check"></i> تشخيص السيارات</a></li>

                            </ul>
                        </div>
                        <button class="btn btn-1 px-lg-5  mt-3">عرض الخدمات</button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="serv_img">
                        <img src="{{asset('images/man.png')}}" alt="">
                    </div>
                </div>


            </div>
        </div>
    </section>
    <!-- start video -->
    <section class="video-section">
        <div class="layer"></div>
        <div class="video-overlay">
            <div class="play-button">
                <button class="btn p-0 btn_play" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <img src="{{asset('images/play.svg')}}" alt="Play Button">

                </button>
            </div>
            <div class="text-content">
                <h2>أطلق العنان</h2>
                <h2>لقوة الصيانة المهنية!</h2>
                <p>ولعرض مزيد من الخدمات الخاصة بسيارتكم حيث توجد العديد من الخدمات
                    لذلك من فضلك توجه الى الخدمة التى تريدها من القائمة من </p>
            </div>
        </div>
        <video autoplay loop muted>
            <source src="{{asset('images/test.mp4')}}" type="video/mp4">
        </video>
    </section>
    <!-- start testmonial -->
    <section class="about testmonial">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading_box text-center">
                        <h2>اراء العملاء</h2>
                        <p>ولعرض مزيد من الخدمات الخاصة بسيارتكم حيث توجد العديد من الخدمات
                            لذلك من فضلك توجه الى الخدمة التى تريدها من القائمة من </p>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="test_slider">
                        <div class="test_box">
                            <img src="{{asset('images/tes-img.png')}}" alt="">
                            <p>"أوصي بشدة باصلاح السيارات وخدمة السيارات لخبرتهم الاستثنائية واحترافهم. لقد أصلحوا   وجعل التجربة بأكملها خالية من المتاعب."</p>
                            <h3>سارة محمود</h3>
                        </div>
                        <div class="test_box">
                            <img src="{{asset('images/tes-img.png')}}" alt="">
                            <p>"أوصي بشدة باصلاح السيارات وخدمة السيارات لخبرتهم الاستثنائية واحترافهم. لقد أصلحوا   وجعل التجربة بأكملها خالية من المتاعب."</p>
                            <h3>سارة محمود</h3>
                        </div>
                        <div class="test_box">
                            <img src="{{asset('images/tes-img.png')}}" alt="">
                            <p>"أوصي بشدة باصلاح السيارات وخدمة السيارات لخبرتهم الاستثنائية واحترافهم. لقد أصلحوا   وجعل التجربة بأكملها خالية من المتاعب."</p>
                            <h3>سارة محمود</h3>
                        </div>
                        <div class="test_box">
                            <img src="{{asset('images/tes-img.png')}}" alt="">
                            <p>"أوصي بشدة باصلاح السيارات وخدمة السيارات لخبرتهم الاستثنائية واحترافهم. لقد أصلحوا   وجعل التجربة بأكملها خالية من المتاعب."</p>
                            <h3>سارة محمود</h3>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </section>
</main>
<!-- start contact support -->
<div class="contact_support">
    <div class="container">
        <div class=" d-md-flex justify-content-between align-items-center">
            <h4>لديك سؤال ؟ إتصل بنا</h4>
            <div class="d-flex align-items-center">
                <img src="{{asset('images/call.png')}}" alt="">
                <div class="">
                    <h4>خدمة العملاء</h4>
                    <h4><a href="#">966-533-255</a></h4>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="foot">
                    <img src="{{asset('images/logo-foot.svg')}}" alt="">

                </div>
            </div>
            <div class="col-lg-4">
                <div class="foot">
                    <p class="mt-3">يعتبر بوجى من افضل المواقع لخدمتك
                        ولعرض مزيد من الخدمات الخاصة بسيارتك
                        لذلك من فضلك توجه الى الخدمة
                    </p>
                    <ul class="social_top_right d-flex mt-3">
                        <li><a href="#"><img src="{{asset('images/x.svg')}}" alt=""></a></li>
                        <li><a href="#"><img src="{{asset('images/insta.svg')}}" alt=""></a></li>
                        <li><a href="#"><img src="{{asset('images/youtube.svg')}}" alt=""></a></li>
                        <li><a href="#"><img src="{{asset('images/facebook.svg')}}" alt=""></a></li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="foot">
                    <h5>الخدمات</h5>
                    <ul>
                        <li><a href="#">الصيانة الوقائية</a></li>
                        <li><a href="#">خدمات المحرك</a></li>
                        <li><a href="#">الإطارات والعجلات</a></li>
                        <li><a href="#">إصلاحات</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="foot">
                    <h5>الدعم</h5>
                    <ul>
                        <li><a href="#">مركز المساعدة</a></li>
                        <li><a href="#">سياسة الخصوصية</a></li>
                        <li><a href="#">تواصل معنا</a></li>
                        <li><a href="#">أسئله واجابات</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="foot">
                    <h5>الشركة</h5>
                    <ul>
                        <li><a href="#"> من نحن</a></li>
                        <li><a href="#"> اخبار ومقالات</a></li>
                        <li><a href="#">الشروط والاحكام</a></li>

                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="copy_right text-center">
                <p>حقوق النشر © 2023 <a href="#">بوجى</a> جميع الحقوق محفوظ       -     برمجة وتصميم <a href="#">فيودكس</a></p>
            </div>
        </div>
    </div>
</footer>
<!--video Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <video autoplay loop  controls>
                    <source src="{{asset('images/test.mp4')}}" type="video/mp4">
                </video>
            </div>

        </div>
    </div>
</div>




<!-- start scripts included -->
<!-- bootstrap included -->
<script src="{{asset('js/bootstrap.bundle.js')}}"></script>
<!-- jquery included -->
<script src="{{asset('js/jquery.js')}}"></script>
<!-- slick slider included -->
<script src="{{asset('js/slick.min.js')}}"></script>
<!-- MY code included -->
<script src="{{asset('js/code.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap" async defer></script>
<script src="{{asset('js/jquery-steps.min.js')}}"></script>
<script>
    // otb
    var countdownTimer;
    var countdownDuration = 60; // Countdown duration in seconds

    function focusNext(currentField) {
        var nextField = currentField + 1;
        var nextFieldElement = document.querySelector('.otp-input:nth-child(' + nextField + ')');

        if (nextFieldElement) {
            nextFieldElement.focus();

        }
    }


    function startCountdown() {
        var timerElement = document.getElementById('timer');
        updateTimer(timerElement);

        countdownTimer = setInterval(function () {
            countdownDuration--;

            if (countdownDuration < 0) {
                clearInterval(countdownTimer);
                countdownTimer = null;
                timerElement.textContent = 'اعادة ارسال';
            } else {
                updateTimer(timerElement);
            }
        }, 1000);
    }

    function updateTimer(timerElement) {
        var minutes = Math.floor(countdownDuration / 60);
        var seconds = countdownDuration % 60;
        timerElement.textContent = minutes + ' : ' + seconds + ' ';
    }
</script>
</body>
<!-- end of body -->

</html>

