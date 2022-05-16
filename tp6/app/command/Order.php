<?php
declare (strict_types = 1);

namespace app\command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class order extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('order_msg')
            ->setDescription('the order_msg command');
    }

    protected function execute(Input $input, Output $output)
    {

        //数据库名称
        $v_host = "tp6";

        $exchange_name = 'delay_exchange_order';

        $routing_key = 'delay_route_order';

        $queue_name = 'delay_queue_order';

        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

        //创建连接
        $channel = $connection->channel();

        //声明交换器
        $channel->exchange_declare($exchange_name, 'x-delayed-message', false, true, false);//第二个参数：交换器类型：fanout、direct、topic、headers


        //将交换器上获取的随机名称绑定到 消息队列上
        $channel->queue_bind($queue_name, $exchange_name, $routing_key);

        //更改并发数量
        $channel->basic_qos(null, 1, null);

        //取数据：消费数据
        $callback = function ($msg) use ($output) {

            $output->writeln($msg->body);
            $res = Db::name('goods_order')->where('id', $msg->body)->update(['delete' => 1]);
            if ($res) {
                $msg->ack();
            }
        };

        //消费
        $channel->basic_consume($queue_name, '', false, false, false, false, $callback);

        //监控
        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();

        $connection->close();
    }
}
