<?php
declare (strict_types = 1);

namespace app\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MqPubSub
{

    /**
     * 生产者，负责发送消息
     * 发布/订阅（Publish/Subscribe）
     * 交换机：fanout交换机
     * fanout 交换非常简单，它是将所有的消息广播到所有已知的队列
     *
     */
    public function send()
    {

        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", "tp6");
        //创建连接
        $channel = $connection->channel();

        //交换器名称
        $exchange_name = "pubSub_exchange";

        //声明交换器
        $channel->exchange_declare($exchange_name, 'fanout', false, false, false);

        for ($i=0; $i < 5; ++$i) {
            sleep(1);//休眠1秒
            //消息内容
            $messageBody = "Hello,mq Now Time:".date("h:i:s")."\n";
            //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
            $message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

            //# 在这里，我们使用默认交换
            //发送消息
            //$channel->basic_publish($message, '', $queue);

            //发送消息, 会关联到交换器
            $channel->basic_publish($message, $exchange_name);

            echo "Send Message:". $i ."\n";
        }

        //关闭信道
        $channel->close();
        //关闭连接
        $connection->close();
        return 'Send Success';
    }
}
