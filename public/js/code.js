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
    rtl: true,
    arrows: true,
    prevArrow: "<img class='slick-next' src='http://bogi.test/images/right.svg'>",
    nextArrow: "<img class='next slick-prev' src='http://bogi.test/images/left.svg'>"
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
    prevArrow:" <img class='test-prev slick-next' src='http://bogi.test/images/right-sm.svg'>",
    nextArrow:"<img class='test-next slick-prev' src='http://bogi.test/images/left-sm.svg'>",
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
function initMap() {
// Create a map centered on a default location
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 37.7749, lng: -122.4194}, // San Francisco coordinates
        zoom: 12
    });

// Create a search box and link it to the search input
    var input = document.getElementById('search-input');
    var searchBox = new google.maps.places.SearchBox(input);

// Bias the search box results towards the current map's viewport
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

// Listen for the event when a place is selected
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        // Clear any existing markers on the map
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, add a marker and center the map on it
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log('Returned place contains no geometry');
                return;
            }

            var marker = new google.maps.Marker({
                map: map,
                title: place.name,
                position: place.geometry.location
            });

            markers.push(marker);
            bounds.extend(place.geometry.location);
        });

        map.fitBounds(bounds);
    });
}
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
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    $(".file-upload").on('change', function(){
        readURL(this);
    });

    $(".upload-button").on('click', function() {
        $(".file-upload").click();
    });
});

