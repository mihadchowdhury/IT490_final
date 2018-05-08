<?php 
include_once 'includes/dbh.inc.php';
poi_lng
include_once 'includes/dbh.inc.php';
if(isset($_POST)){
	$result = mysqli_query($conn, "SELECT * FROM site WHERE loc_id=(SELECT MAX(loc_id) FROM site);");
	while ($row = $result->fetch_assoc()) {
		$lat = $row['latitude'];
		$lng = $row['longitude'];
		$location = $row['location'];
	}
	$rating = $_POST["v3"];
	if(mysqli_query($conn,"INSERT INTO map (location, latitude, longitude,rating) VALUES ('$location', '$lat', '$lng', '$rating');")){
		echo "1";		
	}else{
		echo "2";
	}
}

?>
