<?php
//declare (strict_types = 1);

namespace app\controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use think\Request;

class Login
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user_name = $request->param('user_name');
        $password  = $request->param('password');

        if ($user_name == 'lzc' && $password == 'lzc') {
            $this->sendMsg();
            return "login success";
        }


    }

    public function sendMsg()
    {
        //数据库名称
        $v_host = "tp6";

        //队列名称
        $queue_name = "login_msg";

        //创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

        //创建连接
        $channel = $connection->channel();

        //声明创建队列：着重看参数
        $channel->queue_declare($queue_name, false, true, false, false);//第三个参数：队列持久化

        //数据
        $data = "lzc login success - ".date("Y-m-d H:i:s");

        //创建消息并标记消息持久化
        $msg = new AMQPMessage($data, ["delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT]);//消息持久化

        //推到消息队列：将消息发送到队列
        $channel->basic_publish($msg, "", $queue_name);//第二个参数：交换器名称

        $channel->close();

        $connection->close();
    }
}
