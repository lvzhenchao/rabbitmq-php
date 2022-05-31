<?php
declare (strict_types = 1);

namespace app\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MqWork
{

    /**
     * 工作队列（Work Queues）: 其主要的思想是避免立即执行资源密集型任务，并且阻塞进程等待任务完成
     * 用于处理在短时间的HTTP请求中无法处理的复杂任务
     *
     * 生产者，负责发送消息
     *
     */
    public function send()
    {
        //队列名称
        $queue = "workMq";
        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", "tp6");
        //创建连接
        $channel = $connection->channel();
        //声明创建队列
        $channel->queue_declare($queue, false, false, false, false);

        for ($i=0; $i < 5; ++$i) {
            sleep(1);//休眠1秒
            //消息内容
            $messageBody = "Hello,mq Now Time:".date("h:i:s")."\n";
            //将我们需要的消息标记为持久化 - 通过设置AMQPMessage的参数delivery_mode = AMQPMessage::DELIVERY_MODE_PERSISTENT
            $message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            //发送消息
            $channel->basic_publish($message, '', $queue);
            echo "Send Message:". $i ."\n";
        }

        //关闭信道
        $channel->close();
        //关闭连接
        $connection->close();
        return 'Send Success';
    }
}
