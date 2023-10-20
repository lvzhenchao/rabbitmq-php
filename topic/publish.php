<?php

require_once  '../vendor/autoload.php';


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$v_host = 'order';

$exc_name ='topic_log';

//$routing_key = 'goods.warn';
//$routing_key = 'user.warn';
$routing_key = 'user.info';

$connection = new AMQPStreamConnection('localhost',5672,'lzc','lzc',$v_host);

$channel = $connection->channel();


$channel->exchange_declare($exc_name,'topic',false,false,false);

$data = 'this is '.$routing_key.' message';

$msg = new AMQPMessage($data,['delivery_mode'=>AMQPMEssage::DELIVERY_MODE_PERSISTENT]);


$channel->basic_publish($msg,$exc_name,$routing_key);

$channel->close();

$connection->close();



