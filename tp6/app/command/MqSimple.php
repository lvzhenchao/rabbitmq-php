<?php
declare (strict_types = 1);

namespace app\command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class MqSimple extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('simple_msg')
            ->setDescription('the simple_msg command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        //$output->writeln('simple_msg');

        //队列名称
        $queue = "simpleMq";
        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", "tp6");
        //创建连接
        $channel = $connection->channel();
        //声明创建队列
        $channel->queue_declare($queue, false, false, false, false);

        //消息消费
        $channel->basic_consume($queue, '', false, true, false, false, function ($msg) use ($output)  {
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
