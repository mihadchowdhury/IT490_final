<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('response', false, false, false, false);
$msg = new AMQPMessage('yes');
$channel->basic_publish($msg, '', 'response');
echo " [x] Sent....!'\n";
$channel->close();
$connection->close();

?>
