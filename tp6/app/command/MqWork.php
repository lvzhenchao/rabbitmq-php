<?php
declare (strict_types = 1);

namespace app\command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class MqWork extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('work_msg')
            ->setDescription('the work_msg command');
    }

    protected function execute(Input $input, Output $output)
    {

        //队列名称
        $queue = "workMq";
        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", "tp6");
        //创建连接
        $channel = $connection->channel();
        //声明创建队列
        $channel->queue_declare($queue, false, false, false, false);

        //公平调度，新的消息将发送到一个处于空闲的消费者。
        $channel->basic_qos(null, 1, null);

        //消息消费
        $channel->basic_consume($queue, '', false, true, false, false, function ($msg) use ($output)  {

            //模拟耗时
            sleep(3);

            $output->writeln(" Received " . $msg->body .  PHP_EOL);
        });

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        //关闭信道
        $channel->close();
        //关闭连接
        $connection->close();
    }
}
