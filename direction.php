<?php
include_once 'includes/dbh.inc.php';
$result = mysqli_query($conn, "SELECT * FROM site WHERE loc_id=(SELECT MAX(loc_id) FROM site);");
while ($row = $result->fetch_assoc()) {
	$site_lat = $row['latitude'];
	$site_lng = $row['longitude'];
	$site_location = $row['location'];
}

$result = mysqli_query($conn, "SELECT * FROM saved_place WHERE loc_id=(SELECT MAX(loc_id) FROM saved_place);");
while ($row = $result->fetch_assoc()) {
	$lat = $row['latitude'];
	$lng = $row['longitude'];
	$location = $row['location'];
}
$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location="
	. $lat . "," . $lng ."&radius=1500&type=point_of_interest&key=AIzaSyDOkeAlNsJ99bRq69k755VTvBAotb_0IDQ";
echo "$url";
$json = file_get_contents($url);
$array = json_decode($json, true);	
$first_poi_name = $array['results'][0]['name'];
$first_poi_addr = $array['results'][0]['vicinity'];
$second_poi_name = $array['results'][1]['name'];
$second_poi_addr = $array['results'][1]['vicinity'];
$third_poi_name = $array['results'][2]['name'];
$third_poi_addr = $array['results'][2]['vicinity'];
$fourth_poi_name = $array['results'][3]['name'];
$fourth_poi_addr = $array['results'][3]['vicinity'];
$fifth_poi_name = $array['results'][4]['name'];
$fifth_poi_addr = $array['results'][4]['vicinity'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Waypoints in directions</title>
    <style>
      #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
        float: left;
        width: 70%;
        height: 100%;
      }
      #right-panel {
        margin: 20px;
        border-width: 2px;
        width: 20%;
        height: 400px;
        float: left;
        text-align: left;
        padding-top: 0;
      }
      #directions-panel {
        margin-top: 10px;
        background-color: #FFEE77;
        padding: 10px;
        overflow: scroll;
        height: 174px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <div id="right-panel">
    <div>
    <b>Start:</b>
    <select id="start">
      <option value="<?php echo $site_location ?>"><?php echo $site_location ?></option>
      <option value="Boston, MA">Boston, MA</option>
      <option value="Gild Hall, a Thompson Hotel">New York, NY</option>
      <option value="Miami, FL">Miami, FL</option>
    </select>
    <br>
    <b>Waypoints:</b> <br>
    <i>(Ctrl+Click or Cmd+Click for multiple selection)</i> <br>
    <select multiple id="waypoints">
      <option value="<?php echo $first_poi_addr ?>"><?php echo $first_poi_name ?></option>
      <option value="<?php echo $second_poi_addr ?>"><?php echo $second_poi_name ?></option>
      <option value="<?php echo $third_poi_addr ?>"><?php echo $third_poi_name ?></option>
      <option value="<?php echo $fourth_poi_addr ?>"><?php echo $fourth_poi_name ?></option>
      <option value="<?php echo $fifth_poi_addr ?>"><?php echo $fifth_poi_name ?></option>
    </select>
    <br>
    <b>End:</b>
    <select id="end">
      <option value="<?php echo $location ?>"><?php echo $location ?></option>
    </select>
    <br>
      <input type="submit" id="submit">
    </div>
    <div id="directions-panel"></div>
    </div>
    <script>
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
		var lat = <?php echo $site_lat; ?>; 
		var lng = <?php echo $site_lng; ?>;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: {lat: lat, lng: lng}
        });
        directionsDisplay.setMap(map);

        document.getElementById('submit').addEventListener('click', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        });
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var waypts = [];
        var checkboxArray = document.getElementById('waypoints');
        for (var i = 0; i < checkboxArray.length; i++) {
          if (checkboxArray.options[i].selected) {
            waypts.push({
              location: checkboxArray[i].value,
              stopover: true
            });
          }
        }

        directionsService.route({
          origin: document.getElementById('start').value,
          destination: document.getElementById('end').value,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
            var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                  '</b><br>';
              summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
              summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              summaryPanel.innerHTML += route.legs[i].distance.text + '<br>';
			  summaryPanel.innerHTML += route.legs[i].duration.text + '<br><br>';

            }
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOkeAlNsJ99bRq69k755VTvBAotb_0IDQ&callback=initMap">
    </script>
  </body>
</html>