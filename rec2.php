<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('first', false, false, false, false);
$channel->queue_declare('last', false, false, false, false);
$channel->queue_declare('email', false, false, false, false);
$channel->queue_declare('uid', false, false, false, false);
$channel->queue_declare('pwd', false, false, false, false);
$a=0;
$list = array("a", "b", "c", "d", "e");
$callback = function($msg) {
  
if($GLOBALS['a']<4){
$GLOBALS['list'][$GLOBALS['a']]=$msg->body;
$GLOBALS['a']=$GLOBALS['a']+1;
		} else {
$GLOBALS['list'][$GLOBALS['a']]=$msg->body;
	writeMsg();
	$GLOBALS['a']=0;
}
 
};
$channel->basic_consume('first', '', false, true, false, false, $callback);
$channel->basic_consume('last', '', false, true, false, false, $callback);
$channel->basic_consume('email', '', false, true, false, false, $callback);
$channel->basic_consume('uid', '', false, true, false, false, $callback);
$channel->basic_consume('pwd', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {  
$channel->wait();
}
function writeMsg() {
$servername = "localhost";
$username = "root";
$password = "Master@1234";
$dbname = "mihad";
$user_first=  $GLOBALS['list'][0];
$user_last=  $GLOBALS['list'][1];
$user_email=  $GLOBALS['list'][2];
$user_uid=  $GLOBALS['list'][3];
$user_pwd=  $GLOBALS['list'][4];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd)
VALUES ('$user_first', '$user_last', '$user_email','$user_uid','$user_pwd')";

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
