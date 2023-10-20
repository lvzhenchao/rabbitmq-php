<?php
require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//数据库名称
$v_host = "order";

//交换器名称
$exchange_name = 'exchange';

//创建连接
$connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

//创建连接
$channel = $connection->channel();

//声明交换器
$channel->exchange_declare($exchange_name, 'fanout', false, false, false);//第二个参数：交换器类型：fanout、direct、topic、headers

//数据
$data = "this is exchange message ".date("Y-m-d H:i:s");

//创建消息
$msg = new AMQPMessage($data, ["delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT]);//消息持久化

//推到消息队列：将消息发送到队列
$channel->basic_publish($msg, $exchange_name);//第二个参数：交换器名称,第三个参数队列名称

$channel->close();

$connection->close();
