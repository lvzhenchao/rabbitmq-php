<?php
declare (strict_types = 1);

namespace app\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MqTop
{

    /**
     * 生产者，负责发送消息
     * 交换机：Topic 交换机
     * 基于通配符匹配进行路由选择
     * 类似于正则：
     *  * 可以代替一个单词
     #  # 可以匹配0个或多个单词
     *  当routing key为 “#” 时，topic exchange如同fanout exchange
     *  当不使用 “*” 和 “#” 特殊字符时，topic exchange如同direct exchange
     */
    public function send()
    {

        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", "tp6");
        //创建连接
        $channel = $connection->channel();

        //交换器名称
        $exchange_name = "top_exchange";

        //声明交换器
        $channel->exchange_declare($exchange_name, 'topic', false, false, false);


        //模拟发送com.register.mail消息内容
        $messageBody = "com.register.mail, Now Time:".date("h:i:s");
        //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
        $message = new AMQPMessage($messageBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        //绑定com.register.mail路由
        $channel->basic_publish($message, $exchange_name, "com.register.mail");

        //模拟发送email.register.test消息内容
        $messageBody = "email.register.test, Now Time:".date("h:i:s");
        //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
        $message = new AMQPMessage($messageBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        //绑定email.register.test路由
        $channel->basic_publish($message, $exchange_name, "email.register.test");


        //模拟发送com.register.wsh消息内容
        $messageBody = "com.register.wsh, Now Time:".date("h:i:s");
        //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
        $message = new AMQPMessage($messageBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        //绑定com.register.wsh路由
        $channel->basic_publish($message, $exchange_name, "com.register.wsh");


        //模拟发送email.com.wsh消息内容
        $messageBody = "email.com.wsh, Now Time:".date("h:i:s");
        //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
        $message = new AMQPMessage($messageBody, array(
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        //绑定com.register.wsh路由
        $channel->basic_publish($message, $exchange_name, "email.com.wsh");


        //关闭信道
        $channel->close();
        //关闭连接
        $connection->close();
        return 'Send Success';
    }
}
