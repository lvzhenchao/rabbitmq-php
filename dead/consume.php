<?php
require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

//数据库名称
$v_host = "order";

$dead_exc_name    = 'dead_exchange_pay';
$dead_routing_key = 'dead_route_pay';
$dead_queue_name  = 'dead_queue_pay';

//创建连接
$connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

//创建连接
$channel = $connection->channel();

//声明交换器
$channel->exchange_declare($dead_exc_name, 'direct', false, false, false);//第二个参数：交换器类型：fanout、direct、topic、headers


//将交换器上获取的随机名称绑定到 消息队列上
$channel->queue_bind($dead_queue_name, $dead_exc_name, $dead_routing_key);

//更改并发数量
$channel->basic_qos(null, 1, null);

//取数据：消费数据
$callback = function ($msg) {
//    print_r($msg);
    echo 'received: ',$msg->body,"\n";
    $msg->ack();
};

//消费
$channel->basic_consume($dead_queue_name, '', false, false, false, false, $callback);


//监控
while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();

$connection->close();
