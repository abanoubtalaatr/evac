$(function(){
  //datatable
  $('.table-sort').DataTable({
    paging: false,
    bFilter: false,
    searching: false,
    "sDom": 'Rfrtlip'
  });
  $('.table-page').DataTable({
    "oLanguage": {
        "oPaginate": {
          "sNext": "<i class='fas fa-chevron-right'></i>",
          "sPrevious": "<i class='fas fa-chevron-left'></i>"
        }
    }
  });
  //datepicker
  $('.datepicker').datepicker();
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
  //register
  $('.register-u').on('click',function(){
    $('.register-u.active').removeClass('active');
    $(this).addClass('active');
  });
  //toggle steps
  $('.next-step-btn').on('click',function(e) {
    e.preventDefault();
    $(this).parents('.step').hide();
    $(this).parents('.step').next().show();
  });
  $('.prev-step-btn').on('click',function() {
    $(this).parents('.step').hide();
    $(this).parents('.step').prev().show();
  });
  //charts
  const ctx = document.getElementById('line-chart').getContext('2d');
  const ctx2 = document.getElementById('doughnut-chart').getContext('2d');
  const ctx3 = document.getElementById('bar-chart').getContext('2d');
  const ctx4 = document.getElementById('pie-chart').getContext('2d');
  const datapoints = [5,3,6,9,4,6,9];
  const datapoints3 = [10,2,4,3,9,8,9];
  const datapoints2 = [5,3,6];
  const datapoints4 = [20,40];
  const myChart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: ['sat', 'sun', 'mon', 'tue', 'wed', 'thu','fri'],
          datasets: [
            {
              data: datapoints,
              borderColor: '#B7B7B7',
              fill: false,
              cubicInterpolationMode: 'monotone',
              tension: 0.4
            }
        ]
      },
      options: {
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            grid: {
              color: '#F0EDFF'
            }
          },
          xAxes: [{
            ticks: {
               fontColor: "#fff",
            }
         }],
        }
      }
  });
  const myChart2 = new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['sat', 'sun', 'mon'],
      datasets: [
        {
          data: datapoints2,
          backgroundColor:['#F7D154','#1070CA','#EC4C47']
        }
      ]
    },
    options: {
      responsive: true,
      cutout: '85%',
      plugins: {
        legend: {
          display: false
        },
        title: {
          display: false
        }
      }
    }
  });
  const myChart4 = new Chart(ctx4, {
    type: 'pie',
    data: {
      labels: ['sat', 'sun'],
      datasets: [
        {
          data: datapoints4,
          backgroundColor:['#EC4C47','#1070CA']
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        title: {
          display: false
        }
      }
    }
  });
  var gradient = ctx.createLinearGradient(0, 0, 0, 400);
  gradient.addColorStop(0, 'rgba(142,10,70,1)');   
  gradient.addColorStop(1, 'rgba(97,15,94,1)');
  const myChart3 = new Chart(ctx3, {
    type: 'bar',
    data: {
      labels: ['Country Name', 'Country Name', 'Country Name','Country Name','Country Name','Country Name','Country Name'],
      datasets: [
        {
          label: 'Dataset 1',
          data: datapoints3,
          backgroundColor:gradient,
          borderRadius:'3'
        },
        {
          label: 'Dataset 2',
          data: datapoints,
          backgroundColor:'#091E43',
          barPercentage: 0.5,
          barThickness: 6,
          borderRadius:'3'
        }
      ]
    },
    options: {
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        x: {
          grid: {
            display: false
          }
        },
        y: {
          grid: {
            color: '#F0EDFF'
          }
        },
        xAxes: [{
          ticks: {
             fontColor: "#fff",
          }
       }],
      }
    }
  });
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