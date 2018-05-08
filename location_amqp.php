<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('location', false, false, false, false);
$channel->queue_declare('lat', false, false, false, false);
$channel->queue_declare('lng', false, false, false, false);
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
$channel->basic_consume('location', '', false, true, false, false, $callback);
$channel->basic_consume('lat', '', false, true, false, false, $callback);
$channel->basic_consume('lng', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {  
$channel->wait();
}
function writeMsg() {
$servername = "localhost";
$username = "root";
$password = "Master@1234";
$dbname = "mihad";
$location=  $GLOBALS['list'][0];
$lat=  $GLOBALS['list'][1];
$lng=  $GLOBALS['list'][2];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO site (location, latitude, longitude) VALUES ('$location', '$lat', '$lng' );";


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
