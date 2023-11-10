function showDiv() {
  var div = document.getElementById('myDiv');
  div.style.display = 'block';
}
function toggleDiv() {
  var div = document.getElementById('myDiv');
  div.classList.toggle('hidden');
}
// header slider
$('.hero_slider').slick({
  dots: true,
  infinite: true,
  speed: 300,
  slidesToShow: 1,
  slidesToScroll: 1,
  rtl:true,
  arrows:true,
  prevArrow:" <img class=' slick-next' src='assets/images/right.svg'>",
  nextArrow:"<img class='next slick-prev' src='assets/images/left.svg'>"

});
// testmonial
$('.test_slider').slick({
dots: true,
infinite: true,
speed: 300,
slidesToShow: 3,
slidesToScroll:3,
rtl:true,
arrows:true,
prevArrow:" <img class='test-prev slick-next' src='assets/images/right-sm.svg'>",
nextArrow:"<img class='test-next slick-prev' src='assets/images/left-sm.svg'>",
responsive: [
{
breakpoint: 1024,
settings: {
slidesToShow: 2,
slidesToScroll: 2,
infinite: true,
dots: true
}
},
{
breakpoint: 600,
settings: {
slidesToShow: 1,
slidesToScroll: 1
}
},
{
breakpoint: 480,
settings: {
slidesToShow: 1,
slidesToScroll: 1
}
}
// You can unslick at a given breakpoint now by adding:
// settings: "unslick"
// instead of a settings object
]
});
// map
// staps
$('#demo').steps({

});
// profile img
$(document).ready(function() {


var readURL = function(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.profile-pic').attr('src', e.target.result);
            Livewire.emit('profilePicUpdated', e.target.result);

        }

        reader.readAsDataURL(input.files[0]);
    }
}


    $(".file-upload").on('change', function() {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.profile-pic').attr('src', e.target.result);

                // Trigger Livewire component's method to update the image
                Livewire.emit('photo', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    });

$(".upload-button").on('click', function() {
   $(".file-upload").click();
});
});

