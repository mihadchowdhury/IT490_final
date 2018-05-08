<?php

?>

<!DOCTYPE>

<html>
<head>
    <meta charset="utf-8"/>
    <title>geogram</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    

</head>
<body>
<style> 
input[type=text] {width: 65%; padding: 6px 8px; margin: 6px 0;box-sizing: border-box ;}
.A { width: 100px; float:right; }
.B { width: 50px;}
.mySlides {display:none;}
.position {height:150px; width:65%; margin:auto;}
.fader{position: absolute;height: 100%;width: 100%;left:0;top:0;z-index: -99;}
</style>

<div class="home-btn">

<a class = "B" href="logout.php">Logout</a> 
</div>

<div class = "position" align = "center">
 <legend> Tourist Website </legend>
 <form action = "location.php" method = "get" >
	<input  type = text  name = "search"   id = "search" placeholder="search..." > <br>
	<label id = "lblResult"></label>
	<button  type = "submit">Submit </button><br><br>
</form>
</div>

<body>

<div class="container" style="max-width:100%;">
  <img class="mySlides" src="https://travelnoire.com/wp-content/uploads/2014/12/o-NEW-YORK-CITY-WRITER-facebook.jpg" style="width:100%">
  <img class="mySlides" src="https://www.grandcanyontrust.org/sites/default/files/Home_LCR_Jack_Dykinga.jpg" style="width:100%">
  <img class="mySlides" src="http://cdn-image.travelandleisure.com/sites/default/files/styles/1600x1000/public/1482443330/niagara-falls-neon-lights-horseshoe-falls-NEONFALLS1222.jpg?itok=017xtSpK" style="width:100%">
  <img class="mySlides" src="https://www.planetyatra.com/ketra_input/2017/09/d5.jpg" style="width:100%">
  <img class="mySlides" src="https://d1ljaggyrdca1l.cloudfront.net/wp-content/uploads/2017/05/Guests-admiring-Machu-Picchu-in-Peru.jpg" style="width:100%">
  <img class="mySlides" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Colosseum_in_Rome%2C_Italy_-_April_2007.jpg" style="width:100%">
</div>
<script type="text/javascript">
	google.maps.event.addDomListener(window, 'load', initialize);
	function initialize(){
		var autocomplete = new google.maps.places.Autocomplete(document.getElementById('search'));
		google.maps.event.addListener(autocomplete, 'place_changed', function(){
			var place = autocomplete.getPlace();
			var location = "<b>Address</b>: "+place.formatted_address + "<br/>";
			location += "<b>Lat</b>: "+ place.geometry.location.lat() + "<br/>";
			location += "<b>Lng</b>: "+ place.geometry.location.lng() + "<br/>";
			document.getElementById("lblResult").innerHTML = location
		});
	};
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCM0hYU7Z0XVl5qc0rwiZbtRfEhHHxJiMM&libraries=places&callback=initialize"></script>

<script>
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
	
    for (i = 0; i < x.length; i++) {
       
	   x[i].style.display = "none";
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 2000); // Change image every 2 seconds
}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="script.js"></script>

</body>
</html>