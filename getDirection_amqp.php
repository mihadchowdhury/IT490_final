<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('poi_location', false, false, false, false);
$channel->queue_declare('poi_lat', false, false, false, false);
$channel->queue_declare('poi_lng', false, false, false, false);
$a=0;
$list = array("a", "b", "c");
$callback = function($msg) {
  
if($GLOBALS['a']<2){
$GLOBALS['list'][$GLOBALS['a']]=$msg->body;
$GLOBALS['a']=$GLOBALS['a']+1;
		} else {
$GLOBALS['list'][$GLOBALS['a']]=$msg->body;
	writeMsg();
	$GLOBALS['a']=0;
}
 
};
$channel->basic_consume('poi_location', '', false, true, false, false, $callback);
$channel->basic_consume('poi_lat', '', false, true, false, false, $callback);
$channel->basic_consume('poi_lng', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {  
$channel->wait();
}
function writeMsg() {
$servername = "localhost";
$username = "root";
$password = "Master@1234";
$dbname = "mihad";
$address=  $GLOBALS['list'][0];
$poi_lat=  $GLOBALS['list'][1];
$poi_lng=  $GLOBALS['list'][2];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO saved_place (location, latitude, longitude) VALUES ('$address', '$poi_lat', '$poi_lng');";


/*

*/

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully \n";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();


}
$channel->close();
$connection->close();
?>
