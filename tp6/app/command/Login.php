<?php
//declare (strict_types = 1);

namespace app\command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class Login extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('login_msg')
            ->setDescription('the login_msg command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        //$output->writeln('login_msg');
        //数据库名称
        $v_host = "tp6";

        //队列名称
        $queue_name = "login_msg";

        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

        //创建连接
        $channel = $connection->channel();

        //声明队列：着重看参数
        $channel->queue_declare($queue_name, false, true, false, false);//第三个参数：队列持久化

        //取数据：消费数据
        $callback = function ($msg) use($output) {
            //print_r($msg);
            //echo 'received: ',$msg->body,"\n";
            $output->writeln($msg->body);//此处可以写入数据库
            list($login_msg, $login_time) = explode("/", $msg->body);
            $res = Db::table('login_log')->insert(['msg'=>$login_msg, 'login_time'=>$login_time]);
            if ($res) {
                $msg->ack();//插入数据成功后 可以 手动确认信息
            }
        };
        $channel->basic_consume($queue_name, '', false, false, false, false, $callback);
        //监控
        while ($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();

        $connection->close();

    }
}
