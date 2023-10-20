# 发布订阅
## 主要用到的是rabbitmq的交换器 Exchange

# 交换器、路由键、绑定
- Exchange：交换器；发送消息的AMQP实体，拿到消息之后将它路由给一个或几个队列；使用哪种路由算法是由交换机类型和和规则【Binding】决定的
- RoutingKey：路由键
- Binding：绑定规则

# 交换器类型
- Direct：定向
- Topic：通配符模式
- Fanout：广播
- Headers：不处理路由键