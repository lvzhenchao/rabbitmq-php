<?php
//declare (strict_types = 1);

namespace app\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use think\facade\Db;
use think\Request;

class Goods
{
    public function index()
    {
        return view('goods');
    }

    public function paySuccess(Request $request)
    {
        Db::table('goods_order')->insert(['is_pay'=>1]);
        $id = $request->param('id');
        $this->sendMsg($id);
    }

    public function payFail(Request $request)
    {
        Db::table('goods_order')->insert(['is_pay'=>0]);
        $id = $request->param('id');
        $this->sendMsg($id);
    }

    public function sendMsg($id)
    {
        //数据库名称
        $v_host = "tp6";

//交换器名称
        $exchange_name = 'delay_exchange_order';

        $routing_key = 'delay_route_order';

        $queue_name = 'delay_queue_order';

        $ttl = 20000;

//创建连接
        $connection = new AMQPStreamConnection("127.0.0.1", "5672", "lzc", "lzc", $v_host);

//创建连接
        $channel = $connection->channel();

//声明交换器
        $channel->exchange_declare($exchange_name, 'x-delayed-message', false, true, false);//第二个参数：交换器类型：fanout、direct、topic、headers

        $args = new AMQPTable();
        $args->set('x-delayed-type', 'direct');

        $channel->queue_declare($queue_name, false, true, false, false, false, $args);

//绑定
        $channel->queue_bind($queue_name, $exchange_name, $routing_key);


//数据
        $data = $id;

        $arr = [
            "delivery_mode"       => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            "application_headers" => new AMQPTable(['x-delay' => $ttl])
        ];
//创建消息
        $msg = new AMQPMessage($data, $arr);//消息持久化

//推到消息队列：将消息发送到队列
        $channel->basic_publish($msg, $exchange_name, $routing_key);//第二个参数：交换器名称,第三个参数routing_key名称

        $channel->close();

        $connection->close();
    }
}
