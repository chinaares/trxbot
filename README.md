# TRX自动兑换机器人部署教程
### 运行环境
	服务器系统：Linux 64位(暂不支持windows)
	服务器配置：2核4G (推荐4核8G / 4核16G)
	服务器地区：香港 台湾 新加坡等境外服务器（国内服务器不行）
	带宽需求：港澳台服务器建议 - 独享5M+ 其它境外服务器乱选
### 服务器系统推荐

| 系统镜像        | 版本   |  推荐  |
| --------   | -----:  | :----:  |
| **Centos**      | **7, 8, 9**   |   **Stream9 64位 (最佳建议)**     |
| Alibaba Cloud        |   2.x,3.x   |   3.x LTS 64位   |
| Anolis OS        |    8.4,8.6    |  8.6 RHCK 64位  |


------------

	在租服务器的时候可以要求客服给你安装上：宝塔面板（阿里云 腾讯云 华为云 百度云等）购买时选择镜像，可以在市场镜像找一下带：宝塔Linux面板 的镜像
	如果客服无法帮你安装,或者选购系统时也没有带宝塔的镜像就自己安装 

## 机器人部署详细教程
教程地址：[https://www.telegbot.org/jiaocheng/29.html](https://www.telegbot.org/jiaocheng/29.html "https://www.telegbot.org/jiaocheng/29.html")

## 机器人功能说明  
    支持计算器 
	支持钱包地址查询 
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
<img src="https://github.com/smalpony/trxbot/blob/main/photo/001.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/002.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/003.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/004.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/005.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/006.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/007.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/008.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/009.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0010.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0011.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0012.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0013.png">
<img src="https://github.com/smalpony/trxbot/blob/main/photo/0014.png">
