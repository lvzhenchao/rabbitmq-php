<?php
declare (strict_types = 1);

namespace app\command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class MqTop2 extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('top_msg2')
            ->setDescription('the top_msg2 command');
    }

    protected function execute(Input $input, Output $output)
    {

        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", "tp6");
        //创建连接
        $channel = $connection->channel();

        //交换器名称
        $exchange_name = "top_exchange";

        //声明交换器
        $channel->exchange_declare($exchange_name, 'topic', false, false, false);

        //声明创建队列  队列名称为空时，会生成一个随机名称队列
        list($queue, ,) = $channel->queue_declare('', false, false, true, false);

        //绑定交换机与队列，并指定路由*.*.mail
        $channel->queue_bind($queue, $exchange_name, '*.*.mail');
        //绑定交换机与队列，并指定路由email.#
        $channel->queue_bind($queue, $exchange_name, 'email.#');

        //消息消费添加确认机制ack：第四个参数修改为false
        $channel->basic_consume($queue, '', false, false, false, false, function ($msg) use ($output)  {

            //模拟耗时
            sleep(3);

            $output->writeln(" Received " . $msg->body .  PHP_EOL);

            $msg->ack();//手动确认
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


