<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$uname=$_POST['uid'];
$pwd=$_POST['pwd'];

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('uname', false, false, false, false);
$msg = new AMQPMessage($uname);
$channel->basic_publish($msg, '', 'uname');
echo " [x] Request sent'\n";
$channel->close();
$connection->close();
?>
