<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
include_once 'includes/dbh.inc.php';
if (!empty($_GET['search'])) {
    /**
     * Here we build the url we'll be using to access the google maps api
     */
	$location=$_GET['search'];
    $maps_url = 'https://' .
        'maps.googleapis.com/' .
        'maps/api/geocode/json' .
        '?address=' . urlencode($_GET['search']);
    $maps_json = file_get_contents($maps_url);
    $maps_array = json_decode($maps_json, true);
    $lat = $maps_array['results'][0]['geometry']['location']['lat'];
    $lng = $maps_array['results'][0]['geometry']['location']['lng'];
	$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
	$channel = $connection->channel();
	$channel->queue_declare('location', false, false, false, false);
	$channel->queue_declare('lat', false, false, false, false);
	$channel->queue_declare('lng', false, false, false, false);
	
	$msg = new AMQPMessage($location);
	$channel->basic_publish($msg, '', 'location');

	$msg2 = new AMQPMessage($lat);
	$channel->basic_publish($msg2, '', 'lat');


	$msg3 = new AMQPMessage($lng);
	$channel->basic_publish($msg3, '', 'lng');
	echo " [x] Sent....!'\n";
	$channel->close();
	$connection->close();
	//exit(); 
	/*
	 * Here we build url to use google places api to search nearby area with given coords
	 */
    $url ="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location="
		. $lat . "," . $lng ."&radius=5000&key=AIzaSyCM0hYU7Z0XVl5qc0rwiZbtRfEhHHxJiMM"; 
    $json = file_get_contents($url);
	$array = json_decode($json, true);
	//$pho = $array['results'][0]['photos'][0]['photo_reference'];
	/*
	 * Here we build url to use google places api to search POIs nearby with given coords
	 */
	$pois_url ="https://maps.googleapis.com/maps/api/place/radarsearch/json?location="
		. $lat . "," . $lng ."&radius=5000&type=park&key=AIzaSyCM0hYU7Z0XVl5qc0rwiZbtRfEhHHxJiMM"; 
	$pois_json = file_get_contents($pois_url);
	$pois_array = json_decode($pois_json, true);
	//$place_id = $pois_array['results'][0]['place_id'];
	/*echo "$place_id</br>";
	$detail_url="https://maps.googleapis.com/maps/api/place/details/json?placeid="
		. $place_id ."&key=AIzaSyBoXkm3QoPfMJ8WhAuGe8Pxs1VHRgv9BqU"; 
	$detail_json = file_get_contents($detail_url);
	$detail_array = json_decode($detail_json, true);
	$address = $detail_array['result']['formatted_address'];
	echo "$address";*/
	if (!empty($pois_array)) {
		$i= 0;
		$counter = 0;
		foreach ($pois_array['results'] as $item ) {
			$pla_id = $item['place_id'];
			if (!empty($pla_id)){
					if ($i == 5) break;
					$detail_url= "https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $pla_id . "&key=AIzaSyCM0hYU7Z0XVl5qc0rwiZbtRfEhHHxJiMM";
					//echo "$detail_url<br/>";
					$detail_json = file_get_contents($detail_url);
					$detail_array = json_decode($detail_json, true);
					$phone = $detail_array['result']['formatted_phone_number'];
					$address = $detail_array['result']['formatted_address'];
					$name = $detail_array['result']['name'];
					$website = $detail_array['result']['website'];
					$rating = $detail_array['result']['rating'];
					$poi_lat = $detail_array['result']['geometry']['location']['lat'];
					$poi_lng = $detail_array['result']['geometry']['location']['lng'];
					echo "Name:$name</br>Address:$address</br>Phone:$phone<br/>Website:$website<br/>Google Rating:$rating<br/>";
					//echo '<iframe name="votar" style="display:none;"></iframe>';
					echo '<tr><td><form method="post" action = ""></td>';
					//echo '<input type="hidden" name="action" value="form_submitted">';
					echo '<td><button type="submit" name="submit1" value="' .(int)$counter .'">save</button></td><br/>';
					echo '</form></tr>';
					echo '<tr><td><form method="post" action = "direction.php"></td>';
					echo '<td><button type="submit" name="getDirection" >getDirection</button></td><br/>';
					echo '</form></tr>';
					error_reporting( error_reporting() & ~E_NOTICE );
					$test = $_POST["submit1"];
					//echo $counter;
					if($test == "$counter"){
						$channel = $connection->channel();
						$channel->queue_declare('poi_location', false, false, false, false);
						$channel->queue_declare('poi_lat', false, false, false, false);
						$channel->queue_declare('poi_lng', false, false, false, false);	
						
						$msg = new AMQPMessage($address);
						$channel->basic_publish($msg, '', 'poi_location');

						$msg2 = new AMQPMessage($poi_lat);
						$channel->basic_publish($msg2, '', 'poi_lat');


						$msg3 = new AMQPMessage($poi_lng);
						$channel->basic_publish($msg3, '', 'poi_lng');
						echo " [x] Sent....!'\n";
						$channel->close();
						$connection->close();
						//mysqli_query($conn,"INSERT INTO saved_place (location, latitude, longitude) VALUES ('$address', 2.0, 5.0);");		
					} 
					//else{}
					echo"--------------------------------------------------------------------------------------<br/>";
					$i++;
					$counter++;
					
			}
			
        }
    }
	
}
?> 
<!DOCTYPE>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      
      .map {height: 50%; width:30%;float:left;}
      html, body { height: 100%; margin: 0; padding: 0; }
	  .controls {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

    </style>
  </head>
  <body>
  
    <div class = "map" id="map"></div>
	<!--<input type = submit class = "controls" name = "save" value = "save" ><br>-->
	
	
    <script type="text/javascript">
      var map, marker;
	  var lat = <?php echo json_encode($lat); ?>; 
	  var lng = <?php echo json_encode($lng); ?>; 
	  var myLatLng = {lat: lat , lng: lng};
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: myLatLng,
          zoom: 8
        });
		marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM0hYU7Z0XVl5qc0rwiZbtRfEhHHxJiMM
&callback=initMap"
    async defer></script>
  </body>
</html>

<!DOCTYPE html>
<html>
<head>
	<title>Rating system</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- font awesome -->
  	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
	<!-- rating star css -->
  	<link rel="stylesheet" href="js/ratingstar.css">  	
</head>
<body class="container">
<div class="row">
<div class="col-md-12">
	<div class="form-group">	 
	    <label for="email">User Rating :</label>	  	
	  	<div class='starrr' id='rating-student'></div> 	<br>
	  	<input type= "button"  id="submit" class="btn btn-success" value="save" > 
	  	<div class="msg"></div>
	</div>	
</div>
<div id="results" data-url="<?php if (!empty($url)) echo $url ?>">
    <?php
	
    if (!empty($array)) {
        foreach ($array['results'] as $image ) {
			if (array_key_exists("photos",$image)){
				$pho = $image['photos'][0]['photo_reference'];
				if (!empty($pho)){
					$url= "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=" . $pho . "&key=AIzaSyCM0hYU7Z0XVl5qc0rwiZbtRfEhHHxJiMM";
					echo '<img src="' . $url . '"/><br/><br/>';
					//echo "$pho<br/>";
				}
			}
        }
    }
    ?>
	</div>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- star js -->
<script src="js/ratingstar.js"></script>
<!-- ajax -->
<script>
// rating
var rate;
$('#rating-student').starrr({
  change: function(e, value){ 
  	rate = value;  	       
    if (value) {
      $('.your-choice-was').show();      
    } else {
      $('.your-choice-was').hide();
    }
  }
});
// ajax submit
$("#submit").click(function(){	
	$.ajax({		
        url: "rating.php",
        type: 'post',
        data: {v3 : rate},
        success: function (status) {
        	if(status == 1){
            	$('.msg').html('<b>User Inserted !</b>');
        	}else{
            	$('.msg').html('<b>Server side error !</b>');        		
        	}
        }
    });

});

function myAjax() {
      $.ajax({
           type: "POST",
           url: 'locationsave.php',
           data:{action:'call_this'},
           success:function() {
             $(this).addClass("clicked");
			 
			
           }

      });
 }
 
</script>
</body>
</html>
	
