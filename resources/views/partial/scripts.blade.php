<script>
    function changeLanguage() {
        var currentLang = '{{ app()->getLocale() }}';
        var otherLang = currentLang === 'en' ? 'ar' : 'en';
        var currentUrl = window.location.href;

        if (currentUrl.includes("ar")) {
            currentUrl = currentUrl.replace("ar", "en");
        } else if (currentUrl.includes("en")) {
            currentUrl = currentUrl.replace("en", "ar");
        }
        window.location.replace(currentUrl);
    }
</script>
<script src="{{asset('website/assets/js/bootstrap.bundle.js')}}"></script>
<script src="{{asset('website/assets/js/jquery.js')}}"></script>
<script src="{{asset('website/assets/js/slick.min.js')}}"></script>

<script src="{{asset('website/assets/js/jquery-steps.min.js')}}"></script>

<script src="{{asset('website/'.app()->getLocale() . '/assets/js/code.js')}}"></script>
