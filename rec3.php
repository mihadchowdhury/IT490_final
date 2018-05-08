 <?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('uname', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$callback = function($msg) {
if($msg->body==check($msg->body)){  

echo " Exists... ", $msg->body, "\n";
include 'response.php';


}
else{
echo "Sorry";
}
};
$channel->basic_consume('uname', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}
$channel->close();
$connection->close();


	function check($a){
		$servername = "localhost";
		$username = "root";
		$password = "Master@1234";
		$dbname = "mihad";
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "select user_uid from users where user_uid='$a';";
		$result = $conn->query($sql);
		$row = mysqli_fetch_array($result);
		$name = $row['user_uid'];
		if($a==$name){
			return $name;
		}
		else{
			return "errror";
		}

		if(check($GLOBALS['uname'])){
			echo "Found";
		}
		else {
			echo "Not Found";
		};
}




?>
