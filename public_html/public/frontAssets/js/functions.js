$(function(){
  //datatable
  $('.table-sort').DataTable({
    paging: false,
    searching: false
  });
  //menu
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
  $(window).resize(function(e) {
    if($(window).width()<=768){
      $("#wrapper").removeClass("toggled");
    }else{
      $("#wrapper").addClass("toggled");
    }
  });
  //login inputs
  if($('.login-group').length){
    $('.login-group').each(function(){
      $(this).find('input').attr('value','');
    });
  }
  //password
  $('.check').click(function(e){
    e.preventDefault();
    var input = $(this).parents('.input-group').find('input');
    input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
    $(this).find('i').toggleClass('fa-eye fa-eye-slash');
  });

//map
function initMap() {
  // The location of Uluru
  if($('#map').length){
    const uluru = {lat: -33.9, lng: 151.2};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center:uluru
    });
    // The marker, positioned at Uluru
    const marker = new google.maps.Marker({
      position: uluru,
      map: map,
    });
  }
  else{
    return null;
  }
}
