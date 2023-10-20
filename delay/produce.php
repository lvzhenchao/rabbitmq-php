<?php
require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

//数据库名称
$v_host = "order";

//交换器名称
$exchange_name = 'delay_exchange_pay';

$routing_key = 'delay_route_pay';

$queue_name = 'delay_queue_pay';

$ttl = 20000;

//创建连接
$connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

//创建连接
$channel = $connection->channel();

//声明交换器
$channel->exchange_declare($exchange_name, 'x-delayed-message', false, true, false);//第二个参数：交换器类型：fanout、direct、topic、headers

$args = new AMQPTable();
$args->set('x-delayed-type', 'direct');

$channel->queue_declare($queue_name, false, true, false, false, false, $args);

//绑定
$channel->queue_bind($queue_name, $exchange_name, $routing_key);


//数据
$data = "this is dead message ".date("Y-m-d H:i:s");

$arr = [
    "delivery_mode"       => AMQPMessage::DELIVERY_MODE_PERSISTENT,
    "application_headers" => new AMQPTable(['x-delay' => $ttl])
];
//创建消息
$msg = new AMQPMessage($data, $arr);//消息持久化

//推到消息队列：将消息发送到队列
$channel->basic_publish($msg, $exchange_name, $routing_key);//第二个参数：交换器名称,第三个参数routing_key名称

$channel->close();

$connection->close();
