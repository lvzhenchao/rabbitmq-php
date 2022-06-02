<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'login_msg'  => 'app\command\Login',
        'order_msg'  => 'app\command\Order',
        'simple_msg' => 'app\command\MqSimple',
        'work_msg'   => 'app\command\MqWork',
        'pubSub_msg' => 'app\command\MqPubSub',
    ],

];
