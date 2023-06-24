# TRX自动兑换机器人部署教程
### 运行环境
	服务器系统：Linux 64位(暂不支持windows)
	服务器配置：2核4G (推荐4核8G / 4核16G)
	服务器地区：香港 台湾 新加坡等境外服务器（国内服务器不行） 
### 服务器系统推荐(务必安装宝塔面板)

| 系统镜像        | 版本   |  推荐  |
| --------   | -----:  | :----:  |
| **Centos**      | **7, 8, 9**   |   **Stream9 64位 (最佳建议)**     |
| Alibaba Cloud        |   2.x,3.x   |   3.x LTS 64位   |
| Anolis OS        |    8.4,8.6    |  8.6 RHCK 64位  |


------------ 

## 机器人部署详细教程
教程地址：[https://www.telegbot.org/jiaocheng/29.html](https://www.telegbot.org/jiaocheng/29.html "https://www.telegbot.org/jiaocheng/29.html")



## 机器人功能说明  
	转USDT自动回TRX
	支持黑名单
	支持自动sunswap闪兑补TRX
    支持计算器 
	支持钱包地址查询
	支持交易哈希查询
	支持后台管理 
	支持后台可视化自定义消息下发按钮 菜单命令 用户管理 群管理 推广返利等等 
	支持小程序
	支持兑换成功后群发消息
	支持定制群发广告

	详细的自己看图吧 太多了，总之就是：我认为我开发的是全网最牛B的 兑换机器人
## 更新历史
	2023年6月24日14:45（完美运营版）
	修复兑换成功后发送消息给群通知失败问题
	该版本对很多细节上进行有检查优化修复
	这是一个下载部署就可以直接运营的TRX兑换机器人源代码
	遇到问题可以在群里请求群友帮助，100%无错完美搭建运营 
	

	2023年6月22日02:52
	修复z0显示币价问题
	修复兑换监听延迟问题
	修复TRX实时价格刷新延迟问题
	新增任务自检查修复功能
	新增目录存在updata.data时自动更新数据功能
	新增目录存在update.tar时自动更新文件功能
	新增.env中检查TRONSCAN_APIKEY是否正确配置功能
	修复start命令是没有发送图片+汇率问题
	修复后台更改钱包私钥后机器人没有同步刷新问题



	2023年6月19日00:44
	修复兑换汇率没反应问题
	修复给机器人发送哈西或钱包地址无法查询余额信息问题
	修复后台新增菜单不能同步更新问题
	请注意 配置文件：.env 中新增了一个：TRONSCAN_APIKEY 配置项
	因为那个查链上数据的网站强制要求携带apikei了
	
## 机器人预览图
##### 启动项目(docker compose up)
<img src="https://github.com/smalpony/trxbot/blob/main/photo/001.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/002.png">
##### 配置机器人钱包信息
<img src="https://github.com/smalpony/trxbot/blob/main/photo/003.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/004.png">
##### 手机上机器人样式图
<img src="https://github.com/smalpony/trxbot/blob/main/photo/005.jpg">
##### 手机上进入小程序图
<img src="https://github.com/smalpony/trxbot/blob/main/photo/006.jpg">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/007.jpg">
##### 电报PC端进入小程序图
<img src="https://github.com/smalpony/trxbot/blob/main/photo/008.png">
##### 电脑端登入管理后台图
###### 管理后台地址：http://你的域名/app/user  (telegram登录)
<img src="https://github.com/smalpony/trxbot/blob/main/photo/009.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0010.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0011.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0012.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0013.png">
##### 开发调试机器人时终端显示的信息方便
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0014.png">


## 机器人二次开发文件路径说明
定时任务目录：/app/task(比如监听钱包USDT到账业务)：

消息队列处理目录：/app/queue/redis

机器人安装界面模板目录：/plugin/install/app/view

机器人所有功能性业务核心目录：plugin/SwapTRX8bot/app/controller为了方便快速开发，我们把TG机器人的API接口进行了分类包装

	message.php  消息接口（群组 频道 私聊的所有消息都是这个文件处理）
	
	Command.php 菜单命令接口 （所有的菜单命令都由这个文件处理）

	inline_query.php 内联消息（就是@机器人时触发都是这个文件处理 - 需要开通内联功能）

	callback_query.php 内联按钮点击 （就是电报消息下方的内联按钮 点击时消息都是都是这个文件处理）

	group_power.php  机器人 进群，退群，被踢出群 ，成为管理员等，反正就是专属的机器人权限这块的通知

	group_new.php 群内(所有) 新用户进群时这个文件处理

	group_exit.php  群内(所有) 有用户退群时

	Template.php 通用的模板文件，减少代码量，难得多次去写，所以写了个模板，方便调用

	User_api.php 用户小程序功能API

	Admin_api.php 管理小程序功能API（不是管理员后台哦）
	
##### 关于目录下：Telegram.php这个文件 
	是框架包装文件,同时小程序的登录算法在内,为了安全该文件进行了简单混淆 请忽略
	
	交流电报群：@phpTRON

