# MQ （Message Queue）消息队列，是一种应用件的通信方式
## 消息队列：是在消息的传递过程中保存消息的容器
- 分布式系统中重要的组件
- 解决应用耦合，异步消息，流量削锋，实现高性能，高并发，高可用
## 生产者、消费者，实现了生产者和消费者的解耦

# 特性
- 可靠性
- 灵活的路由
- 消息集群
- 高可用
- 多种协议
- 多种客户端
- 管理界面
- 跟踪机制
- 插件机制

# 工作原理
- Broker 接收和分发消息的应用
- Virtual host类似于mysql的数据库
- Connection 发布者/消费者和Broker之间的TCP链接
- Channel 解决消息量大的时候，Connection TCP链接开销大；是轻量级Connection
- Exchange 在应用内分发消息，路由规则
- Queue 具体的类型存储器；等待消费者取走，一个消息可以被同时拷贝到多个queue中

# AMQP协议：高级消息队列协议；是应用层协议的一个开放标准，为面向消息的中间件设计

# 需安装erlang，并且需要版本一致；erlang的版本、centOS的版本
- yum install erlang-23.3.4.4-1.el7.x86_64.rpm
- yum install rabbitmq-server-3.8.19-1.el7.noarch.rpm 
- systemctl start rabbitmq-server//启动
- systemctl stop rabbitmq-server//关闭

# 端口含义
- 4369：epmd(Erlang Port Mapper Daemon), erlang服务端口
- 5672：client端通信口（AMQP协议）
- 15672：HTTP API客户端，管理UI（仅在启用了管理插件的情况下）http://192.168.33.10:15672/
- 25672：用于节点间通信（Erlang分发服务器端口）

# 启动管理界面
- rabbitmq-plugins  enable  rabbitmq_management

# 查看插件
- rabbitmq-plugins  list

# rabbitmqctl 相关命令
- rabbitmqctl  add_user  Username  Password 新增用户
- rabbitmqctl  delete_user  Username 删除用户
- rabbitmqctl  change_password  Username  Newpassword 修改用户密码
- rabbitmqctl  list_users 查看当前用户列表
- rabbitmqctl  set_user_tags  User  Tag 
`
User为用户名， Tag为角色名(对应于上面的administrator，monitoring，policymaker，management，或其他自定义名称)。
`












