<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$first=$_POST['first'];
$last=$_POST['last'];
$email=$_POST['email'];
$uid=$_POST['uid'];
$pwd=$_POST['pwd'];

$connection = new AMQPStreamConnection('192.168.1.4', 5672, 'guest3', 'guest3');
$channel = $connection->channel();
$channel->queue_declare('first', false, false, false, false);
$channel->queue_declare('last', false, false, false, false);
$channel->queue_declare('email', false, false, false, false);
$channel->queue_declare('uid', false, false, false, false);
$channel->queue_declare('pwd', false, false, false, false);

$msg = new AMQPMessage($first);
$channel->basic_publish($msg, '', 'first');

$msg2 = new AMQPMessage($last);
$channel->basic_publish($msg2, '', 'last');


$msg3 = new AMQPMessage($email);
$channel->basic_publish($msg3, '', 'email');


$msg4 = new AMQPMessage($uid);
$channel->basic_publish($msg4, '', 'uid');


$msg5 = new AMQPMessage($pwd);
$channel->basic_publish($msg5, '', 'pwd');

echo " [x] Sent....!'\n";
$channel->close();
$connection->close();
?>
