# 死信：是一种消息机制
- 消息被拒绝
- 消息队列的存活时间超过设置TTL时间【经常遇到的】
- 消息队列的消息数量超过最大队列长度

# 死信交换器：DLX

# 延迟队列
- 存放需要在指定时间被处理的元素的队列

## 延迟队列的应用场景
- 订单在10分钟内未支付则自动取消
- 账单在一周内未支付，则自动结算
- 用户注册成功后，如果三天内没有登录则进行短信提醒
- 用户发起退款，如果三天内没有得到处理则通知相关运营人员 

## 延迟插件 https://www.rabbitmq.com/community-plugins.html
- 注意要下载与rabbit相对应的版本，此处下载3.8.x
- 上传位置：/usr/lib/rabbitmq/lib/rabbitmq_server-3.8.19/plugins/
- rabbitmq-plugins list
- rabbitmq-plugins enable  rabbitmq_delayed_message_exchange

