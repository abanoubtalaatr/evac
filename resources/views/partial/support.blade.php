<div class="contact_support">
    <div class="container">
        <div class=" d-md-flex justify-content-between align-items-center">
            <h4>@lang("site.do_you_have_question")</h4>
            <div class="d-flex align-items-center">
                <img src="{{asset('website/assets/images/call.png')}}" alt="">
                <div class="">
                    <h4>@lang("site.customer_service")</h4>
                    <h4><a href="#">{{$settings ? $settings->customer_service_number:""}}</a></h4>
                </div>
            </div>

        </div>
    </div>
</div>
