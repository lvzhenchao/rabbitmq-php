<?php
require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

//数据库名称
$v_host = "order";

//交换器名称
$exchange_name = 'exchange_pay';

$routing_key = 'route_pay';

$queue_name = 'queue_pay';

$ttl = 20000;

$dead_exc_name    = 'dead_exchange_pay';
$dead_routing_key = 'dead_route_pay';
$dead_queue_name  = 'dead_queue_pay';

//创建连接
$connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

//创建连接
$channel = $connection->channel();

//声明交换器
$channel->exchange_declare($exchange_name, 'direct', false, false, false);//第二个参数：交换器类型：fanout、direct、topic、headers

$args = new AMQPTable();
// 消息过期方式：设置 queue.normal 队列中的消息5s之后过期
$args->set('x-message-ttl', $ttl);
// 设置队列最大长度方式： x-max-length
//$args->set('x-max-length', 1);
$args->set('x-dead-letter-exchange', $dead_exc_name);
$args->set('x-dead-letter-routing-key', $dead_routing_key);

$channel->queue_declare($queue_name, false, true, false, false, false, $args);

//绑定
$channel->queue_bind($queue_name, $exchange_name, $routing_key);

//声明死信交换器队列
$channel->exchange_declare($dead_exc_name, 'direct', false, false, false);
$channel->queue_declare($dead_queue_name, false, true, false, false);
$channel->queue_bind($dead_queue_name, $dead_exc_name, $dead_routing_key);

//数据
$data = "this is dead message ".date("Y-m-d H:i:s");


//创建消息
$msg = new AMQPMessage($data, ["delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT]);//消息持久化

//推到消息队列：将消息发送到队列
$channel->basic_publish($msg, $exchange_name, $routing_key);//第二个参数：交换器名称,第三个参数routing_key名称

$channel->close();

$connection->close();
