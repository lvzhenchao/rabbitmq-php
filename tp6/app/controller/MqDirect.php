<?php
declare (strict_types = 1);

namespace app\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MqDirect
{

    /**
     * 生产者，负责发送消息
     * 交换机：direct交换机
     * 直接交换背后的算法很简单 - 消息将传递到订阅routing key完全匹配的队列
     *
     */
    public function send()
    {

        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", "tp6");
        //创建连接
        $channel = $connection->channel();

        //交换器名称
        $exchange_name = "direct_exchange";

        //声明交换器
        $channel->exchange_declare($exchange_name, 'direct', false, false, false);


        //模拟发送error消息内容
        $messageBody = "error, Now Time:".date("h:i:s");
        //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
        $message = new AMQPMessage($messageBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        //绑定error路由
        $channel->basic_publish($message, $exchange_name, "error");

        //模拟发送info消息内容
        $messageBody = "info, Now Time:".date("h:i:s");
        //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
        $message = new AMQPMessage($messageBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        //绑定info路由
        $channel->basic_publish($message, $exchange_name, "info");


        //模拟发送warning消息内容
        $messageBody = "warning, Now Time:".date("h:i:s");
        //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
        $message = new AMQPMessage($messageBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        //绑定warning路由
        $channel->basic_publish($message, $exchange_name, "warning");


        //关闭信道
        $channel->close();
        //关闭连接
        $connection->close();
        return 'Send Success';
    }
}
