var map;
  var directionsService;
  var directionsDisplay;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 19.124384, lng: 72.897517},
          zoom: 12
        });
        directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer;
      }

 $(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $( "#usersubmit" ).click(function() {
       
         directionsDisplay.setMap(map);
        calculateAndDisplayRoute(directionsService, directionsDisplay);
    });

  });

   function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
          origin: $("#source").val(),
          destination:$("#destination").val(),
          travelMode: 'DRIVING'
        }, function(response, status) {
          console.log(response);
          if (status === 'OK') {
              console.log(response);
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }


          