<?php
require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

//数据库名称
$v_host = "order";

//队列名称
$queue_name = "task_queue";

//创建连接
$connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

//创建连接
$channel = $connection->channel();

//声明队列：着重看参数
$channel->queue_declare($queue_name, false, true, false, false);//第三个参数：队列持久化

//取数据：消费数据
$callback = function ($msg) {
//    print_r($msg);
    echo 'received: ',$msg->body,"\n";

    $msg->ack();//发出确认
};

$channel->basic_qos(null, 1, null);

$channel->basic_consume($queue_name, '', false, false, false, false, $callback);//第四个参数：是否自动确认消息被消费
//监控
while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();

$connection->close();
