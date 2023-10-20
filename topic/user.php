<?php

require_once  '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$v_host ='order';

$exc_name = 'topic_log';

$routing_key = 'user.*';

$connection = new AMQPStreamConnection('localhost',5672,'lzc','lzc',$v_host);

$channel = $connection->channel();

$channel->exchange_declare($exc_name,'topic',false,false,false);


list($queue_name,,) = $channel->queue_declare('',false,false,true,false);

$channel->queue_bind($queue_name,$exc_name,$routing_key);

$callback = function($msg){
	echo 'received ' ,$msg->body,"\n";
	$msg->ack();
};

$channel->basic_qos(null,1,null);

$channel->basic_consume($queue_name,'',false,false,false,false,$callback);

while($channel->is_open()){
	$channel->wait();
}

$channel->close();

$connection->close();



