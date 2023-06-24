-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2023-05-28 22:04:12
-- 服务器版本： 5.7.40-log
-- PHP 版本： 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `ttt`
--

-- --------------------------------------------------------

--
-- 表的结构 `sjb_list`
--

CREATE TABLE `sjb_list` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `qudao` int(11) NOT NULL DEFAULT '1',
  `del` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT '赛事ID',
  `gid` int(11) NOT NULL COMMENT '游戏ID',
  `title` varchar(64) NOT NULL COMMENT '赛事标题',
  `a` varchar(64) NOT NULL COMMENT 'A队伍',
  `b` varchar(64) NOT NULL COMMENT 'B队伍',
  `aid` int(11) NOT NULL COMMENT 'a队伍id',
  `bid` int(11) NOT NULL COMMENT 'b队id',
  `aurl` varchar(256) NOT NULL COMMENT 'A队国标',
  `burl` varchar(256) NOT NULL COMMENT 'B队国标',
  `tvurl` varchar(256) NOT NULL COMMENT '动画直播地址',
  `time` int(11) NOT NULL COMMENT '开赛时间',
  `date` timestamp NOT NULL,
  `go` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sjb_logmoney`
--

CREATE TABLE `sjb_logmoney` (
  `id` int(11) NOT NULL,
  `sub` varchar(16) NOT NULL,
  `del` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `uname` varchar(16) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `yue` decimal(10,2) NOT NULL,
  `type` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_logmoney`
--

INSERT INTO `sjb_logmoney` (`id`, `sub`, `del`, `username`, `uname`, `money`, `yue`, `type`, `remark`, `time`) VALUES
(1, '', 1, 'kspade', '马云', '1000.00', '1000.00', 1, '开户', 1650000000),
(2, '', 1, 'kspade', '马云', '-100.00', '990.00', 2, '开户', 1650000000),
(3, '', 1, 'kspade2', '王健林', '100.00', '100.00', 1, '新开户', 1669883822),
(4, '', 1, 'kspade', '马云', '795.00', '72517.00', 4, '【中奖】突尼斯 vs. 法国■(让球)突尼斯■500.00*1.59', 1669884343),
(5, '', 1, 'kspade', '马云', '795.00', '71722.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884422),
(6, '', 1, 'kspade', '马云', '795.00', '72517.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884577),
(7, '', 1, 'kspade', '马云', '-795.00', '72017.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884590),
(8, '', 1, 'kspade', '马云', '795.00', '72517.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884634),
(9, '', 1, 'kspade', '马云', '-795.00', '73312.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884640),
(10, '', 1, 'kspade', '马云', '795.00', '80795.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884717),
(11, '', 1, 'kspade', '马云', '-795.00', '81590.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884726),
(12, '', 1, 'kspade', '马云', '795.00', '80795.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884781),
(13, '', 1, 'kspade', '马云', '-795.00', '80795.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884782),
(14, '', 1, 'kspade', '马云', '795.00', '80795.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884812),
(15, '', 1, 'kspade', '马云', '-795.00', '81590.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884813),
(16, '', 1, 'kspade', '马云', '795.00', '80795.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884847),
(17, '', 1, 'kspade', '马云', '-795.00', '80000.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884848),
(18, '', 1, 'kspade', '马云', '795.00', '80795.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884879),
(19, '', 1, 'kspade', '马云', '-795.00', '80000.00', 0, '【取消中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884916),
(20, '', 1, 'kspade', '马云', '795.00', '80795.00', 4, '【中奖】突尼斯 vs. 法国■[让球·突尼斯+1]■500.00 @1.59', 1669884983);

-- --------------------------------------------------------

--
-- 表的结构 `sjb_mg`
--

CREATE TABLE `sjb_mg` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `sid` int(11) NOT NULL,
  `mty` int(11) NOT NULL,
  `nm` varchar(32) NOT NULL,
  `pe` int(11) NOT NULL,
  `mc` tinyint(1) NOT NULL,
  `off` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_mg`
--

INSERT INTO `sjb_mg` (`id`, `del`, `type`, `sid`, `mty`, `nm`, `pe`, `mc`, `off`) VALUES
(1, 1, 2, 6522269, 1127, '开球球队', 1001, 0, 0),
(2, 1, 2, 6522269, 1000, '让球', 1001, 0, 0),
(3, 1, 2, 6522269, 1000, '让球-上半场', 1002, 0, 0),
(4, 1, 2, 6522269, 1007, '大/小', 1001, 0, 0),
(5, 1, 2, 6522269, 1007, '大/小-上半场', 1002, 0, 0),
(6, 1, 2, 6522269, 1005, '独赢', 1001, 0, 0),
(7, 1, 2, 6522269, 1005, '独赢-上半场', 1002, 0, 0),
(8, 1, 2, 6522269, 1099, '波胆', 1001, 0, 0),
(9, 1, 2, 6522269, 1100, '波胆-上半场', 1002, 0, 0),
(10, 1, 2, 6525421, 1127, '开球球队', 1001, 0, 0),
(11, 1, 2, 6525421, 1000, '让球', 1001, 0, 0),
(12, 1, 2, 6525421, 1000, '让球-上半场', 1002, 0, 0),
(13, 1, 2, 6525421, 1007, '大/小', 1001, 0, 0),
(14, 1, 2, 6525421, 1007, '大/小-上半场', 1002, 0, 0),
(15, 1, 2, 6525421, 1005, '独赢', 1001, 0, 0),
(16, 1, 2, 6525421, 1005, '独赢-上半场', 1002, 0, 0),
(17, 1, 2, 6525421, 1099, '波胆', 1001, 0, 0),
(18, 1, 2, 6525421, 1100, '波胆-上半场', 1002, 0, 0),
(19, 1, 2, 6522285, 1127, '开球球队', 1001, 0, 0),
(20, 1, 2, 6522285, 1000, '让球', 1001, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sjb_mks`
--

CREATE TABLE `sjb_mks` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `gidm` int(11) NOT NULL,
  `mty` int(11) NOT NULL,
  `nm` varchar(32) NOT NULL,
  `mgid` int(12) NOT NULL,
  `au` int(11) NOT NULL,
  `mksid` bigint(11) NOT NULL,
  `li` varchar(16) NOT NULL,
  `mbl` int(11) NOT NULL,
  `ss` int(11) NOT NULL,
  `pe` int(11) NOT NULL,
  `op` json NOT NULL,
  `gnum_h` int(11) NOT NULL,
  `gnum_c` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_mks`
--

INSERT INTO `sjb_mks` (`id`, `del`, `type`, `sid`, `gid`, `gidm`, `mty`, `nm`, `mgid`, `au`, `mksid`, `li`, `mbl`, `ss`, `pe`, `op`, `gnum_h`, `gnum_c`) VALUES
(1, 1, 2, 6531975, 4075356, 4630889, 1000, '让球', 0, 0, 0, '', 0, 0, 0, 'null', 62070, 62069),
(2, 1, 2, 6531975, 4075356, 4630889, 1000, '让球-上半场', 0, 0, 0, '0', 0, 0, 0, 'null', 62070, 62069),
(3, 1, 2, 6531975, 4075356, 4630889, 1007, '大/小', 0, 0, 0, '2 / 2.5', 0, 0, 0, 'null', 62070, 62069),
(4, 1, 2, 6531975, 4075356, 4630889, 1007, '大/大小-上半场', 0, 0, 0, '0.5 / 1', 0, 0, 0, 'null', 62070, 62069),
(5, 1, 2, 6531975, 4075356, 4630889, 1005, '独赢', 0, 0, 0, '', 0, 0, 0, 'null', 62070, 62069),
(6, 1, 2, 6531975, 4075356, 4630889, 1005, '独赢-上半场', 0, 0, 0, '', 0, 0, 0, 'null', 62070, 62069),
(7, 1, 2, 6531975, 4075358, 4630889, 1000, '让球', 0, 0, 0, '', 0, 0, 0, 'null', 62072, 62071),
(8, 1, 2, 6531975, 4075358, 4630889, 1000, '让球-上半场', 0, 0, 0, '0 / 0.5', 0, 0, 0, 'null', 62072, 62071),
(9, 1, 2, 6531975, 4075358, 4630889, 1007, '大/小', 0, 0, 0, '2.5', 0, 0, 0, 'null', 62072, 62071),
(10, 1, 2, 6531975, 4075358, 4630889, 1007, '大/大小-上半场', 0, 0, 0, '0.5', 0, 0, 0, 'null', 62072, 62071),
(11, 1, 2, 6531975, 4075360, 4630889, 1000, '让球', 0, 0, 0, '', 0, 0, 0, 'null', 62074, 62073),
(12, 1, 2, 6531975, 4075360, 4630889, 1007, '大/小', 0, 0, 0, '2', 0, 0, 0, 'null', 62074, 62073),
(13, 1, 2, 6531975, 4075362, 4630889, 1000, '让球', 0, 0, 0, '', 0, 0, 0, 'null', 62076, 62075),
(14, 1, 2, 6531975, 4075362, 4630889, 1007, '大/小', 0, 0, 0, '1.5 / 2', 0, 0, 0, 'null', 62076, 62075),
(15, 1, 2, 6531975, 4075356, 4630889, 1099, '波胆', 0, 0, 0, '', 0, 0, 0, 'null', 62070, 62069),
(16, 1, 2, 6531975, 4075356, 4630889, 1100, '波胆-上半场', 0, 0, 0, '', 0, 0, 0, 'null', 62070, 62069),
(17, 1, 2, 6531975, 4075356, 4630889, 1007, '大/小-上半场', 0, 0, 0, '0.5 / 1', 0, 0, 0, 'null', 62070, 62069),
(18, 1, 2, 6531975, 4075358, 4630889, 1007, '大/小-上半场', 0, 0, 0, '0.5', 0, 0, 0, 'null', 62072, 62071),
(19, 1, 2, 6531975, 4075356, 4630889, 1000, '让球', 0, 0, 0, '', 0, 0, 0, 'null', 62070, 62069),
(20, 1, 2, 6531975, 4075356, 4630889, 1000, '让球-上半场', 0, 0, 0, '0', 0, 0, 0, 'null', 62070, 62069);

-- --------------------------------------------------------

--
-- 表的结构 `sjb_op`
--

CREATE TABLE `sjb_op` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT '赛事id',
  `gid` int(11) NOT NULL,
  `gidm` int(11) NOT NULL,
  `gnum_h` int(11) NOT NULL,
  `gnum_c` int(11) NOT NULL,
  `mksid` bigint(12) NOT NULL COMMENT '玩法ID',
  `mty` int(11) NOT NULL COMMENT '玩法',
  `pe` int(11) NOT NULL,
  `nm` varchar(16) NOT NULL COMMENT '玩法B',
  `na` varchar(16) NOT NULL COMMENT '玩法A',
  `li` varchar(16) NOT NULL,
  `GOD` decimal(10,2) NOT NULL COMMENT '官方赔率',
  `od` decimal(10,2) NOT NULL COMMENT '赔率',
  `ty` int(11) NOT NULL COMMENT '标识',
  `f` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赔率浮动',
  `lok` int(11) NOT NULL COMMENT '999锁盘'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_op`
--

INSERT INTO `sjb_op` (`id`, `del`, `type`, `sid`, `gid`, `gidm`, `gnum_h`, `gnum_c`, `mksid`, `mty`, `pe`, `nm`, `na`, `li`, `GOD`, `od`, `ty`, `f`, `lok`) VALUES
(1, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 1, 1000, 0, '让球', '英格兰', '0 / 0.5', '1.87', '1.87', 0, '0.00', 0),
(2, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 1, 1000, 0, '让球', '法国', '0 / 0.5', '2.01', '2.01', 0, '0.00', 0),
(3, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 2, 1000, 0, '让球-上半场', '英格兰', '0', '2.17', '2.17', 0, '0.00', 0),
(4, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 2, 1000, 0, '让球-上半场', '法国', '0', '1.73', '1.73', 0, '0.00', 0),
(5, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 3, 1007, 0, '大/小', '大', '2 / 2.5', '1.98', '1.98', 0, '0.00', 0),
(6, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 3, 1007, 0, '大/小', '小', '2 / 2.5', '1.88', '1.88', 0, '0.00', 0),
(7, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 4, 1007, 0, '大/大小-上半场', '大', '0.5 / 1', '1.79', '1.79', 0, '0.00', 0),
(8, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 4, 1007, 0, '大/大小-上半场', '小', '0.5 / 1', '2.07', '2.07', 0, '0.00', 0),
(9, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 5, 1005, 0, '独赢', '英格兰', '', '3.05', '3.05', 0, '0.00', 0),
(10, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 5, 1005, 0, '独赢', '法国', '', '2.28', '2.28', 0, '0.00', 0),
(11, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 5, 1005, 0, '独赢', '和', '', '3.20', '3.20', 0, '0.00', 0),
(12, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 6, 1005, 0, '独赢-上半场', '英格兰', '', '3.95', '3.95', 0, '0.00', 0),
(13, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 6, 1005, 0, '独赢-上半场', '法国', '', '3.15', '3.15', 0, '0.00', 0),
(14, 1, 2, 6531975, 4075356, 4630889, 62070, 62069, 6, 1005, 0, '独赢-上半场', '和', '', '1.89', '1.89', 0, '0.00', 0),
(15, 1, 2, 6531975, 4075358, 4630889, 62072, 62071, 7, 1000, 0, '让球', '英格兰', '0', '2.25', '2.25', 0, '0.00', 0),
(16, 1, 2, 6531975, 4075358, 4630889, 62072, 62071, 7, 1000, 0, '让球', '法国', '0', '1.68', '1.68', 0, '0.00', 0),
(17, 1, 2, 6531975, 4075358, 4630889, 62072, 62071, 8, 1000, 0, '让球-上半场', '英格兰', '0 / 0.5', '1.56', '1.56', 0, '0.00', 0),
(18, 1, 2, 6531975, 4075358, 4630889, 62072, 62071, 8, 1000, 0, '让球-上半场', '法国', '0 / 0.5', '2.47', '2.47', 0, '0.00', 0),
(19, 1, 2, 6531975, 4075358, 4630889, 62072, 62071, 9, 1007, 0, '大/小', '大', '2.5', '2.20', '2.20', 0, '0.00', 0),
(20, 1, 2, 6531975, 4075358, 4630889, 62072, 62071, 9, 1007, 0, '大/小', '小', '2.5', '1.69', '1.69', 0, '0.00', 0);

-- --------------------------------------------------------

--
-- 表的结构 `sjb_pay`
--

CREATE TABLE `sjb_pay` (
  `id` int(11) NOT NULL,
  `sub` varchar(16) NOT NULL,
  `del` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ok` int(11) NOT NULL COMMENT '确认订单?',
  `sid` int(11) NOT NULL COMMENT '赛事id',
  `opid` int(11) NOT NULL,
  `titlea` varchar(64) NOT NULL COMMENT '赛事',
  `userid` int(11) NOT NULL COMMENT '用户id',
  `username` varchar(16) NOT NULL,
  `uname` varchar(16) NOT NULL,
  `money` decimal(10,2) NOT NULL COMMENT '下注金额',
  `od` decimal(10,2) NOT NULL COMMENT '赔率',
  `z` int(11) NOT NULL COMMENT '是否中奖',
  `z2` int(11) NOT NULL,
  `okmoney` decimal(10,2) NOT NULL COMMENT '赢m',
  `p` int(11) NOT NULL COMMENT '是否派奖',
  `oktime` int(11) NOT NULL COMMENT '派奖时间',
  `mksid` int(11) NOT NULL COMMENT '下注游戏ID',
  `mty` int(11) NOT NULL COMMENT '下注类型ID',
  `titleb` varchar(16) NOT NULL COMMENT '下注类型',
  `nm` varchar(16) NOT NULL,
  `na` varchar(16) NOT NULL,
  `pe` int(11) NOT NULL,
  `li` varchar(10) NOT NULL,
  `time` int(11) NOT NULL COMMENT '下注时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_pay`
--

INSERT INTO `sjb_pay` (`id`, `sub`, `del`, `type`, `ok`, `sid`, `opid`, `titlea`, `userid`, `username`, `uname`, `money`, `od`, `z`, `z2`, `okmoney`, `p`, `oktime`, `mksid`, `mty`, `titleb`, `nm`, `na`, `pe`, `li`, `time`) VALUES
(1, '', 1, 1, 0, 929753, 12, '加纳 vs. 乌拉圭', 4, 'test001', '王健林', '100.00', '2.23', 0, 0, '0.00', 0, 0, 39006836, 1000, '让球', '0', '加纳', 0, '0', 1670000094),
(2, '', 1, 1, 0, 930249, 62, '塞尔维亚 vs. 瑞士', 4, 'test001', '王健林', '1200.00', '1.50', 0, 0, '0.00', 0, 0, 37240901, 1000, '让球-上半场', '+0/0.5', '塞尔维亚', 0, '+0/0.5', 1670000122),
(3, '', 1, 1, 0, 930249, 46, '塞尔维亚 vs. 瑞士', 4, 'test001', '王健林', '300.00', '1.85', 0, 0, '0.00', 0, 0, 36047525, 1127, '开球球队', '主', '塞尔维亚', 0, '', 1670000122),
(4, '', 1, 1, 0, 930250, 122, '喀麦隆 vs. 巴西', 4, 'test001', '王健林', '980.00', '1.85', 0, 0, '0.00', 0, 0, 36046833, 1127, '开球球队', '主', '喀麦隆', 0, '', 1670000149),
(5, '', 1, 1, 0, 930249, 46, '塞尔维亚 vs. 瑞士', 4, 'test001', '王健林', '300.00', '1.85', 0, 0, '0.00', 0, 0, 36047525, 1127, '开球球队', '主', '塞尔维亚', 0, '', 1670000220),
(6, '', 1, 1, 0, 930249, 46, '塞尔维亚 vs. 瑞士', 4, 'test001', '王健林', '1000.00', '1.85', 0, 0, '0.00', 0, 0, 36047525, 1127, '开球球队', '主', '塞尔维亚', 0, '', 1670000674),
(7, '', 1, 1, 0, 930249, 46, '塞尔维亚 vs. 瑞士', 5, 'test002', '刘德华', '1000.00', '1.89', 0, 0, '0.00', 0, 0, 36047525, 1127, '开球球队', '主', '塞尔维亚', 0, '', 1670001610),
(8, '', 1, 1, 0, 930249, 80, '塞尔维亚 vs. 瑞士', 5, 'test002', '刘德华', '1000.00', '2.41', 0, 0, '0.00', 0, 0, 36047413, 1005, '独赢', '主', '塞尔维亚', 0, '', 1670001631),
(9, '', 1, 1, 0, 930249, 52, '塞尔维亚 vs. 瑞士', 5, 'test002', '刘德华', '1000.00', '1.63', 0, 0, '0.00', 0, 0, 37240896, 1000, '让球', '+0/0.5', '塞尔维亚', 0, '+0/0.5', 1670001765),
(10, '', 1, 1, 0, 930249, 83, '塞尔维亚 vs. 瑞士', 5, 'test002', '刘德华', '1000.00', '3.15', 0, 0, '0.00', 0, 0, 36047414, 1005, '独赢-上半场', '主', '塞尔维亚', 0, '', 1670001798),
(11, '', 1, 1, 0, 930249, 67, '塞尔维亚 vs. 瑞士', 5, 'test002', '刘德华', '1000.00', '1.67', 0, 0, '0.00', 0, 0, 37240895, 1007, '大/小', '小 2.5/3', '小', 0, '2.5/3', 1670001839),
(12, '', 1, 1, 0, 930250, 122, '喀麦隆 vs. 巴西', 5, 'test002', '刘德华', '1000.00', '1.84', 0, 0, '0.00', 0, 0, 36046833, 1127, '开球球队', '主', '喀麦隆', 0, '', 1670001965),
(13, '', 1, 1, 0, 930250, 130, '喀麦隆 vs. 巴西', 5, 'test002', '刘德华', '1000.00', '1.59', 1, 0, '1590.00', 1, 0, 37070819, 1000, '让球', '+1.5/2', '喀麦隆', 0, '+1.5/2', 1670018557),
(14, '', 1, 1, 0, 930250, 128, '喀麦隆 vs. 巴西', 5, 'test002', '刘德华', '1000.00', '2.37', 1, 0, '2370.00', 1, 0, 38877498, 1000, '让球', '+1', '喀麦隆', 0, '+1', 1670018555),
(15, '', 1, 1, 0, 930250, 126, '喀麦隆 vs. 巴西', 5, 'test002', '刘德华', '1000.00', '1.79', 1, 0, '1790.00', 1, 0, 37070817, 1000, '让球', '+1.5', '喀麦隆', 0, '+1.5', 1670018552),
(16, '', 1, 1, 0, 930250, 156, '喀麦隆 vs. 巴西', 5, 'test002', '刘德华', '1000.00', '6.79', 1, 0, '6790.00', 1, 0, 36046721, 1005, '独赢', '主', '喀麦隆', 0, '', 1670018550),
(17, '', 1, 1, 0, 930249, 80, '塞尔维亚 vs. 瑞士', 6, 'test003', '郭富城', '1000.00', '2.42', 0, 0, '0.00', 0, 0, 36047413, 1005, '独赢', '主', '塞尔维亚', 0, '', 1670002213),
(18, '', 1, 1, 0, 930249, 52, '塞尔维亚 vs. 瑞士', 6, 'test003', '郭富城', '1000.00', '1.64', 0, 0, '0.00', 0, 0, 37240896, 1000, '让球', '+0/0.5', '塞尔维亚', 0, '+0/0.5', 1670002213),
(19, '', 1, 1, 0, 930249, 46, '塞尔维亚 vs. 瑞士', 6, 'test003', '郭富城', '1000.00', '1.85', 0, 0, '0.00', 0, 0, 36047525, 1127, '开球球队', '主', '塞尔维亚', 0, '', 1670002213),
(20, '', 1, 1, 0, 930249, 87, '塞尔维亚 vs. 瑞士', 6, 'test003', '郭富城', '1000.00', '13.25', 0, 0, '0.00', 0, 0, 36047499, 1099, '波胆', '2-0', '2-0', 0, '', 1670002237);

-- --------------------------------------------------------

--
-- 表的结构 `sjb_set`
--

CREATE TABLE `sjb_set` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `odset` int(11) NOT NULL DEFAULT '1',
  `smoney` decimal(10,2) NOT NULL,
  `sod` decimal(10,2) NOT NULL,
  `c1127` int(11) NOT NULL COMMENT '开球球队',
  `c1000` int(11) NOT NULL COMMENT '让球',
  `c1007` int(11) NOT NULL COMMENT '大/小',
  `c1005` int(11) NOT NULL COMMENT '独赢',
  `c1099` int(11) NOT NULL COMMENT '波胆',
  `c1100` int(11) NOT NULL COMMENT '波胆下半场'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_set`
--

INSERT INTO `sjb_set` (`id`, `type`, `odset`, `smoney`, `sod`, `c1127`, `c1000`, `c1007`, `c1005`, `c1099`, `c1100`) VALUES
(1, 1, 1, '10.00', '1.20', 5, 5, 5, 5, 5, 5);

-- --------------------------------------------------------

--
-- 表的结构 `sjb_tongji`
--

CREATE TABLE `sjb_tongji` (
  `id` int(11) NOT NULL,
  `sub` varchar(16) NOT NULL,
  `del` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `opid` int(11) NOT NULL,
  `mksid` int(11) NOT NULL,
  `mty` int(11) NOT NULL,
  `pe` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `date` int(11) NOT NULL,
  `nm` varchar(16) NOT NULL,
  `na` varchar(16) NOT NULL,
  `li` varchar(16) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_tongji`
--

INSERT INTO `sjb_tongji` (`id`, `sub`, `del`, `type`, `sid`, `opid`, `mksid`, `mty`, `pe`, `num`, `money`, `date`, `nm`, `na`, `li`, `time`) VALUES
(1, '0', 1, 1, 929752, 1, 39011009, 1000, 1001, 0, '0.00', 0, '0', '韩国', '0', 0),
(2, '0', 1, 1, 929752, 2, 39011009, 1000, 1001, 0, '0.00', 0, '0', '葡萄牙', '0', 0),
(3, '0', 1, 1, 929752, 3, 39011016, 1000, 1001, 0, '0.00', 0, '+0/0.5', '韩国', '+0/0.5', 0),
(4, '0', 1, 1, 929752, 4, 39011016, 1000, 1001, 0, '0.00', 0, '-0/0.5', '葡萄牙', '-0/0.5', 0),
(5, '0', 1, 1, 929752, 5, 39006144, 1007, 1001, 0, '0.00', 0, '大 3.5', '大', '3.5', 0),
(6, '0', 1, 1, 929752, 6, 39006144, 1007, 1001, 0, '0.00', 0, '小 3.5', '小', '3.5', 0),
(7, '0', 1, 1, 929752, 7, 39006883, 1007, 1001, 0, '0.00', 0, '大 3.5/4', '大', '3.5/4', 0),
(8, '0', 1, 1, 929752, 8, 39006883, 1007, 1001, 0, '0.00', 0, '小 3.5/4', '小', '3.5/4', 0),
(9, '0', 1, 1, 929752, 9, 36046591, 1005, 1001, 0, '0.00', 0, '主', '韩国', '', 0),
(10, '0', 1, 1, 929752, 10, 36046591, 1005, 1001, 0, '0.00', 0, '和', '和', '', 0),
(11, '0', 1, 1, 929752, 11, 36046591, 1005, 1001, 0, '0.00', 0, '客', '葡萄牙', '', 0),
(12, '0', 1, 1, 929753, 12, 39006836, 1000, 1001, 1, '100.00', 20221203, '0', '加纳', '0', 1670000094),
(13, '0', 1, 1, 929753, 13, 39006836, 1000, 1001, 0, '0.00', 0, '0', '乌拉圭', '0', 0),
(14, '0', 1, 1, 929753, 14, 39007022, 1000, 1001, 0, '0.00', 0, '+0/0.5', '加纳', '+0/0.5', 0),
(15, '0', 1, 1, 929753, 15, 39007022, 1000, 1001, 0, '0.00', 0, '-0/0.5', '乌拉圭', '-0/0.5', 0),
(16, '0', 1, 1, 929753, 16, 37070855, 1007, 1001, 0, '0.00', 0, '大 2.5', '大', '2.5', 0),
(17, '0', 1, 1, 929753, 17, 37070855, 1007, 1001, 0, '0.00', 0, '小 2.5', '小', '2.5', 0),
(18, '0', 1, 1, 929753, 18, 37240906, 1007, 1001, 0, '0.00', 0, '大 2.5/3', '大', '2.5/3', 0),
(19, '0', 1, 1, 929753, 19, 37240906, 1007, 1001, 0, '0.00', 0, '小 2.5/3', '小', '2.5/3', 0),
(20, '0', 1, 1, 929753, 20, 36046547, 1099, 1001, 0, '0.00', 0, '1-0', '1-0', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `sjb_user`
--

CREATE TABLE `sjb_user` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `zt` int(11) NOT NULL DEFAULT '1',
  `sub` varchar(16) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `user` varchar(16) NOT NULL,
  `tel` bigint(20) NOT NULL,
  `lianxi` varchar(16) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sjb_user`
--

INSERT INTO `sjb_user` (`id`, `del`, `zt`, `sub`, `username`, `password`, `money`, `user`, `tel`, `lianxi`, `remark`, `time`) VALUES
(1, 0, 1, '', 'kspade', 'e10adc3949ba59abbe56e057f20f883e', '43346.00', '马云', 13111111115, '微信', '马云', 1652124031),
(4, 0, 1, '', 'test001', 'e10adc3949ba59abbe56e057f20f883e', '1744.00', '王健林', 0, '飞机', '121212@.', 1669883822),
(5, 0, 1, '', 'test002', 'e10adc3949ba59abbe56e057f20f883e', '320.00', '刘德华', 0, '其它', '', 1670001388),
(6, 0, 1, '', 'test003', 'e10adc3949ba59abbe56e057f20f883e', '3450.00', '郭富城', 0, '其它', '', 1670001425),
(7, 0, 1, '', 'test004', 'e10adc3949ba59abbe56e057f20f883e', '28690.00', '谢霆锋', 0, '其它', '', 1670001465),
(8, 0, 1, '', 'test005', 'e10adc3949ba59abbe56e057f20f883e', '9830.00', '张学友', 0, '其它', '', 1670001513),
(9, 0, 1, 'kspade3', 'test111', 'e10adc3949ba59abbe56e057f20f883e', '176529.00', '马化腾', 0, '支付宝', '测试1111', 1670152259),
(10, 0, 1, 'kspade3', 'test112', 'e10adc3949ba59abbe56e057f20f883e', '0.00', '王健林', 0, '飞机', '大', 1670152400),
(11, 0, 1, 'kspade3', '5454545', 'e10adc3949ba59abbe56e057f20f883e', '100.00', '231312', 0, '飞机', '321321', 1670152521),
(12, 0, 1, 'kspade3', 'kspade2112', 'ef293c4637ddd7d135229cdc1a2bba56', '31.00', '对对对', 0, 'QQ', 'dsadsa', 1670152832),
(13, 0, 1, 'kspade3', 'dsadsa', '165c468905fa4e852e23d2ab8ab2c33a', '110.00', 'sad', 0, 'QQ', 'dsadsa', 1670152857);

-- --------------------------------------------------------

--
-- 表的结构 `sys_crontab`
--

CREATE TABLE `sys_crontab` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '任务标题',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '任务类型 (1 command, 2 class, 3 url, 4 eval)',
  `rule` varchar(100) NOT NULL COMMENT '任务执行表达式',
  `target` varchar(150) NOT NULL DEFAULT '' COMMENT '调用任务字符串',
  `parameter` varchar(500) NOT NULL COMMENT '任务调用参数',
  `running_times` int(11) NOT NULL DEFAULT '0' COMMENT '已运行次数',
  `last_running_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次运行时间',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序，越大越前',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '任务状态状态[0:禁用;1启用]',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `singleton` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否单次执行 (0 是 1 不是)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='定时器任务表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `sys_crontab`
--

INSERT INTO `sys_crontab` (`id`, `title`, `type`, `rule`, `target`, `parameter`, `running_times`, `last_running_time`, `remark`, `sort`, `status`, `create_time`, `update_time`, `singleton`) VALUES
(1, 'class', 2, '*/10 * * * *', 'app\\task\\curlmsg', '', 0, 1671900900, '主动推送商户余额', 0, 0, 1657776338, 1657776338, 1),
(2, 'class', 2, '*/3 * * * *', 'app\\task\\trx_price', '', 33584, 1687366260, 'trx兑换价格监听', 0, 1, 1657776338, 1657776338, 1),
(3, 'class', 2, '*/6 * * * * *', 'app\\task\\trx_jt', '', 495691, 1687366328, 'trx转账监听', 0, 1, 1657776338, 1657776338, 1),
(4, 'class', 2, '*/5 * * * * *', 'app\\task\\usdt_jt', '', 594808, 1687366327, 'usdt到账监听', 0, 1, 1657776338, 1657776338, 1),
(5, 'class', 2, '0 0 */1 * * *', 'app\\task\\group_vip', '', 83, 1680073201, '群白名单检查', 0, 0, 1657776338, 1657776338, 1),
(6, 'class', 2, '*/5 * * * * *', 'app\\task\\keep_buy', '', 1381034, 1687156666, '云记账机器人购买监听', 0, 0, 1657776338, 1657776338, 1);

-- --------------------------------------------------------

--
-- 表的结构 `sys_crontab_log`
--

CREATE TABLE `sys_crontab_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `crontab_id` bigint(20) UNSIGNED NOT NULL COMMENT '任务id',
  `target` varchar(255) NOT NULL COMMENT '任务调用目标字符串',
  `parameter` varchar(500) NOT NULL COMMENT '任务调用参数',
  `exception` text NOT NULL COMMENT '任务执行或者异常信息输出',
  `return_code` tinyint(1) NOT NULL DEFAULT '0' COMMENT '执行返回状态[0成功; 1失败]',
  `running_time` varchar(10) NOT NULL COMMENT '执行所用时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='定时器任务执行日志表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `sys_menu`
--

CREATE TABLE `sys_menu` (
  `menuId` int(11) NOT NULL COMMENT '菜单id',
  `parentId` int(11) NOT NULL DEFAULT '0' COMMENT '上级id, 0是顶级',
  `title` varchar(200) NOT NULL COMMENT '菜单名称',
  `path` varchar(200) DEFAULT NULL COMMENT '菜单路由地址',
  `component` varchar(200) DEFAULT NULL COMMENT '菜单组件地址, 目录可为空',
  `menuType` int(11) DEFAULT '0' COMMENT '类型, 0菜单, 1按钮',
  `sortNumber` int(11) NOT NULL DEFAULT '1' COMMENT '排序号',
  `authority` varchar(200) DEFAULT NULL COMMENT '权限标识',
  `target` varchar(200) DEFAULT '_self' COMMENT '打开位置',
  `icon` varchar(200) DEFAULT NULL COMMENT '菜单图标',
  `color` varchar(200) DEFAULT NULL COMMENT '图标颜色',
  `hide` int(11) NOT NULL DEFAULT '0' COMMENT '是否隐藏, 0否, 1是(仅注册路由不显示在左侧菜单)',
  `active` varchar(200) DEFAULT NULL COMMENT '菜单侧栏选中的path',
  `meta` varchar(800) DEFAULT NULL COMMENT '其它路由元信息',
  `del` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除, 0否, 1是',
  `tenantId` int(11) NOT NULL DEFAULT '1' COMMENT '租户id',
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `sys_menu`
--

INSERT INTO `sys_menu` (`menuId`, `parentId`, `title`, `path`, `component`, `menuType`, `sortNumber`, `authority`, `target`, `icon`, `color`, `hide`, `active`, `meta`, `del`, `tenantId`, `createTime`, `updateTime`) VALUES
(1, 0, 'Dashboard ', '/dashboard', '', 0, 0, '', '_self', 'el-icon-s-home', NULL, 0, NULL, '', 0, 1, '2022-06-15 11:49:57', '2022-06-15 11:49:57'),
(2, 1, '分析页', '/dashboard/monitor', '/dashboard/monitor', 0, 11, '', '_self', 'el-icon-odometer', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:02:41', '2022-06-15 12:02:41'),
(3, 0, '商户信息', '/user/profile', '/user/profile', 0, 2, '', '_self', 'el-icon-user-solid', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:06:23', '2022-06-15 12:06:23'),
(4, 3, '基本信息', '/user/profile', '/user/profile', 0, 3, '', '_self', 'el-icon-setting', NULL, 0, NULL, '', 1, 1, '2022-06-15 12:07:45', '2022-06-15 12:07:45'),
(5, 1, '消息', '/user/message', '/user/message', 0, 22, '', '_self', 'el-icon-chat-dot-square', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:08:38', '2022-06-15 12:08:38'),
(6, 0, '收款通道', '/item', '', 0, 15, '', '_self', 'el-icon-_integral-solid', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:09:33', '2022-06-15 12:09:33'),
(7, 6, '新增通道', '/item/add', '/pay/item_add.vue', 0, 1, '', '_self', 'el-icon-_vercode', NULL, 1, NULL, '', 0, 1, '2022-06-15 12:10:38', '2022-06-15 12:10:38'),
(8, 6, '通道列表', '/item/list', '/pay/item.vue', 0, 5, '', '_self', 'el-icon-_template', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:11:11', '2022-06-15 12:11:11'),
(9, 0, '系统管理', '/admin', '', 0, 1, '', '_self', 'el-icon-_setting-solid', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:11:58', '2022-06-15 12:11:58'),
(10, 9, '菜单管理', '/admin/menu', '/admin/menu', 0, 10, '', '_self', 'el-icon-s-operation', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:12:25', '2022-06-15 12:12:25'),
(11, 9, '角色管理', '/admin/role', '/admin/role', 0, 91, '', '_self', 'el-icon-user', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:12:50', '2022-06-15 12:12:50'),
(12, 0, '订单列表', '/order', '', 0, 20, '', '_self', 'el-icon-s-order', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:46:07', '2022-06-15 12:46:07'),
(13, 12, '银行卡订单', '/order/pay', '/pay/dingdan.vue', 0, 121, '', '_self', 'el-icon-bank-card', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:47:28', '2022-06-15 12:47:28'),
(14, 12, '异常订单', '/order/payerr', '/pay/dingdan_err.vue', 0, 122, '', '_self', 'el-icon-document-delete', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:48:22', '2022-06-15 12:48:22'),
(15, 9, '消息管理', '/admin/allmsg', '/admin/allmsg', 0, 20, '', '_self', 'el-icon-chat-line-round', NULL, 0, NULL, '', 0, 1, '2022-06-15 12:53:20', '2022-06-15 12:53:20'),
(16, 8, '编辑通道', NULL, NULL, 1, 2, '/api/payment/updatequdao', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 13:33:30', '2022-06-15 13:33:30'),
(17, 8, '获取通道列表', NULL, NULL, 1, 1, '/api/payment/itemlist', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 13:45:21', '2022-06-15 13:45:21'),
(18, 13, '获取订单', NULL, NULL, 1, 0, '/api/payment/AllPage', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 13:47:27', '2022-06-15 13:47:27'),
(19, 5, '获取消息', NULL, NULL, 1, 0, '/api/user/my_message_notice', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 13:48:39', '2022-06-15 13:48:39'),
(20, 3, '获取账户信息', NULL, NULL, 1, 31, '/api/user/user_basic', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 13:49:55', '2022-06-15 13:49:55'),
(21, 3, '绑解谷歌验证', NULL, NULL, 1, 31, '/api/user/my_open_Google', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 13:56:48', '2022-06-15 13:56:48'),
(22, 3, '绑定telgram', NULL, NULL, 1, 31, '/api/user/my_open_Telegram', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 13:58:39', '2022-06-15 13:58:39'),
(23, 5, '删除消息', NULL, NULL, 1, 20, '/api/user/my_message_del', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 14:00:54', '2022-06-15 14:00:54'),
(24, 8, '删除通道', NULL, NULL, 1, 5, '/api/payment/delqudao', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 14:02:21', '2022-06-15 14:02:21'),
(25, 13, '删除订单', NULL, NULL, 1, 2, '/api/payment/deldingdan', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 14:03:14', '2022-06-15 14:03:14'),
(26, 13, '补发订单', NULL, NULL, 1, 1, '/api/payment/repair', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 14:05:11', '2022-06-15 14:05:11'),
(27, 8, '启用通道', NULL, NULL, 1, 3, '/api/payment/updateztA', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-15 18:38:59', '2022-06-15 18:38:59'),
(28, 7, '新增通道', NULL, NULL, 1, 0, '/api/payment/addqudao', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-22 08:44:29', '2022-06-22 08:44:29'),
(29, 0, '财务报表', '/log', '', 0, 50, '', '_self', 'el-icon-s-flag', NULL, 0, NULL, '', 0, 1, '2022-06-22 10:50:58', '2022-06-22 10:50:58'),
(30, 29, '每日总计', '/log/r', '/pay/log_R.vue', 0, 2, '', '_self', 'el-icon-time', NULL, 0, NULL, '', 0, 1, '2022-06-22 10:52:56', '2022-06-22 10:52:56'),
(31, 29, '每日通道统计', '/log/qd', '/pay/log_qd.vue', 0, 3, '', '_self', 'el-icon-pie-chart', NULL, 0, NULL, '', 0, 1, '2022-06-22 10:54:17', '2022-06-22 10:54:17'),
(32, 30, '获取每日账单', NULL, NULL, 1, 0, '/api/payment/getlogtday', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-26 01:25:27', '2022-06-26 01:25:27'),
(33, 30, '删除每日账单', NULL, NULL, 1, 1, '/api/payment/dellogtday', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-26 01:25:55', '2022-06-26 01:25:55'),
(34, 31, '获取通道账单', NULL, NULL, 1, 0, '/api/payment/getlogqd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-26 01:26:28', '2022-06-26 01:26:28'),
(35, 31, '删除通道账单', NULL, NULL, 1, 1, '/api/payment/dellogqd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-06-26 01:26:46', '2022-06-26 01:26:46'),
(36, 0, '对接文档', '/url', '', 0, 88, '', '_self', 'el-icon-_service', NULL, 1, NULL, '', 0, 1, '2022-07-02 09:07:18', '2022-07-02 09:07:18'),
(37, 36, '在线测试', 'http://www.baidu.com', NULL, 0, 1, '', '_self', 'el-icon-_upload', NULL, 0, NULL, '', 0, 1, '2022-07-02 09:07:43', '2022-07-02 09:07:43'),
(38, 36, '对接文档', 'http://www.baidu.com/2', NULL, 0, 2, '', '_self', 'el-icon-_network', NULL, 0, NULL, '', 0, 1, '2022-07-02 09:07:58', '2022-07-02 09:07:58'),
(39, 3, '重置秘钥', NULL, NULL, 1, 31, '/api/user/retkey', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-02 09:32:25', '2022-07-02 09:32:25'),
(40, 0, '卡商列表', '/agent', '', 0, 6, '', '_self', 'el-icon-_user-group', '123456', 1, NULL, '', 1, 1, '2022-07-27 00:51:57', '2022-07-27 00:51:57'),
(41, 6, '卡商', '/item/up', '/pay/up.vue', 0, 1, '', '_self', 'el-icon-coordinate', NULL, 0, NULL, '{\"badge\": \"供\"}', 0, 1, '2022-07-27 00:53:00', '2022-07-27 00:53:00'),
(42, 41, '获取卡商', NULL, NULL, 1, 1, '/api/up/uplist', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-27 00:58:21', '2022-07-27 00:58:21'),
(43, 41, '新增卡商', NULL, NULL, 1, 2, '/api/up/upadd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-27 01:01:07', '2022-07-27 01:01:07'),
(44, 41, '修改卡商', NULL, NULL, 1, 3, '/api/payment/upput', '_self', NULL, NULL, 0, NULL, '', 1, 1, '2022-07-27 01:01:45', '2022-07-27 01:01:45'),
(45, 41, '删除卡商', NULL, NULL, 1, 4, '/api/up/updel', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-27 01:02:08', '2022-07-27 01:02:08'),
(46, 41, '卡商权限修改', NULL, NULL, 1, 12, '/api/up/upauth', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-27 11:58:40', '2022-07-27 11:58:40'),
(47, 6, '通道回款记录', '/item/dec', '/pay/log_up_dec.vue', 0, 30, '', '_self', 'el-icon-_refund', NULL, 0, NULL, '', 0, 1, '2022-07-27 16:55:17', '2022-07-27 16:55:17'),
(48, 47, '获取清算记录', NULL, NULL, 1, 1, '/api/up/upmoneylog', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-27 16:56:31', '2022-07-27 16:56:31'),
(49, 47, '删除清算记录', NULL, NULL, 1, 2, '/api/up/delmoneylog', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-27 16:57:13', '2022-07-27 16:57:13'),
(50, 3, '修改密码', NULL, NULL, 1, 31, '/api/user/UpdatePassword', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-07-27 17:44:15', '2022-07-27 17:44:15'),
(51, 9, '用户管理', '/admin/user', '/admin/user', 0, 0, '', '_self', 'el-icon-coordinate', NULL, 0, NULL, '', 0, 1, '2022-07-29 17:43:50', '2022-07-29 17:43:50'),
(52, 14, '获取异常订单', NULL, NULL, 1, 1, '/api/payment/dderrlist', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-11 15:39:06', '2022-08-11 15:39:06'),
(53, 14, '删除异常订单', NULL, NULL, 1, 2, '/api/payment/delerrdd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-11 15:39:39', '2022-08-11 15:39:39'),
(54, 6, '通道加款记录', '/item/inc', '/pay/log_up_inc.vue', 0, 20, '', '_self', 'el-icon-_refund-solid', NULL, 0, NULL, '', 0, 1, '2022-08-12 12:41:09', '2022-08-12 12:41:09'),
(55, 54, '获取加款记录', NULL, NULL, 1, 1, '/api/up/incmoneylog', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-12 12:41:47', '2022-08-12 12:41:47'),
(56, 54, '删除加款记录', NULL, NULL, 1, 2, '/api/up/delincmoneylog', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-12 12:42:26', '2022-08-12 12:42:26'),
(57, 3, '设置谷歌授权页', NULL, NULL, 1, 31, '/api/user/SetGoogelMenu', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-14 08:59:42', '2022-08-14 08:59:42'),
(58, 8, '停用通道', NULL, NULL, 1, 4, '/api/payment/updateztB', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-14 13:52:08', '2022-08-14 13:52:08'),
(59, 41, '下发扣款', NULL, NULL, 1, 10, '/api/up/UpDecMoney', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-19 13:44:55', '2022-08-19 13:44:55'),
(60, 41, '查单加款', NULL, NULL, 1, 7, '/api/up/UpIncMoney', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-19 15:26:52', '2022-08-19 15:26:52'),
(61, 41, '封禁卡商', NULL, NULL, 1, 3, '/api/up/upputzt', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-19 16:12:42', '2022-08-19 16:12:42'),
(62, 41, '开关谷歌验证', NULL, NULL, 1, 13, '/api/up/upputgoog', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-19 16:13:43', '2022-08-19 16:13:43'),
(63, 41, '重置卡商密码', NULL, NULL, 1, 15, '/api/up/upputpassword', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-19 16:17:12', '2022-08-19 16:17:12'),
(64, 41, '重置卡商数据', NULL, NULL, 1, 20, '/api/up/upresetData', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-19 16:19:06', '2022-08-19 16:19:06'),
(65, 3, '设置登录IP白名单', NULL, NULL, 1, 31, '/api/user/LoginIp', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-20 02:54:32', '2022-08-20 02:54:32'),
(66, 3, '设置api接口白名单', NULL, NULL, 1, 31, '/api/user/ApiIp', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-20 03:44:34', '2022-08-20 03:44:34'),
(67, 3, '重置谷歌验证', NULL, NULL, 1, 31, '/api/user/googelRet', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-20 04:16:11', '2022-08-20 04:16:11'),
(68, 29, '收支明细报表', '/log/mingxi', '/pay/log_mingxi.vue', 0, 5, '', '_self', 'el-icon-_table', NULL, 0, NULL, '', 0, 1, '2022-08-20 04:28:28', '2022-08-20 04:28:28'),
(69, 68, '获取收支报表', NULL, NULL, 1, 1, '/api/user/log_money', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-08-20 04:32:35', '2022-08-20 04:32:35'),
(70, 113, '设置子账号权限', NULL, NULL, 1, 1, '/api/sub/setSubMenu', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-05 09:33:55', '2022-09-05 09:33:55'),
(71, 113, '获取子账号列表', NULL, NULL, 1, 1131, '/api//user/sublist', '_self', NULL, NULL, 0, NULL, '', 1, 1, '2022-09-05 09:36:37', '2022-09-05 09:36:37'),
(72, 113, '获取子账号权限', NULL, NULL, 1, 1131, '/api/user/subMenu', '_self', NULL, NULL, 0, NULL, '', 1, 1, '2022-09-05 09:37:20', '2022-09-05 09:37:20'),
(73, 113, '删除子账号', NULL, NULL, 1, 1131, '/api/sub/subdel', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-05 10:49:51', '2022-09-05 10:49:51'),
(74, 113, '新增子账号', NULL, NULL, 1, 1131, '/api/sub/subadd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-05 10:50:35', '2022-09-05 10:50:35'),
(75, 80, '代付订单', '/daifu/list', '/pay/daifu.vue', 0, 801, '', '_self', 'el-icon-_table', NULL, 0, NULL, '', 0, 1, '2022-09-08 11:19:42', '2022-09-08 11:19:42'),
(76, 75, '获取代付订单', NULL, NULL, 1, 1, '/api/daifu/alldd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-08 11:21:09', '2022-09-08 11:21:09'),
(77, 75, '上传下发凭证图', NULL, NULL, 1, 10, '/api/daifu/upload', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-08 12:37:50', '2022-09-08 12:37:50'),
(78, 75, '代付订单标记成功', NULL, NULL, 1, 15, '/api/daifu/setdd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-08 12:39:53', '2022-09-08 12:39:53'),
(79, 75, '取消代付订单', NULL, NULL, 1, 6, '/api/daifu/deldd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-08 12:40:18', '2022-09-08 12:40:18'),
(80, 0, '代付下发', '/daifu', '', 0, 30, '', '_self', 'el-icon-_money-solid', NULL, 0, NULL, '', 0, 1, '2022-09-08 14:11:08', '2022-09-08 14:11:08'),
(81, 13, '修改订单金额', NULL, NULL, 1, 2, '/api/payment/xiugaimoney', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-14 14:40:04', '2022-09-14 14:40:04'),
(82, 75, '撤回代付订单', NULL, NULL, 1, 2, '/api/daifu/chehui', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-14 14:40:33', '2022-09-14 14:40:33'),
(83, 3, '设置代付IP白名单', NULL, NULL, 1, 31, '/api/user/DaifuIp', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-20 08:46:26', '2022-09-20 08:46:26'),
(84, 3, '设置代付自动分笔', NULL, NULL, 1, 31, '/api/user/daifuAuto', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-09-20 08:46:56', '2022-09-20 08:46:56'),
(85, 8, '设置通道优先出款', NULL, NULL, 1, 1, '/api/payment/youxiandec', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-10-07 15:30:50', '2022-10-07 15:30:50'),
(86, 5, '清空消息', NULL, NULL, 1, 10, '/api/user/message_read', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-10-07 16:41:23', '2022-10-07 16:41:23'),
(87, 8, '扣款', NULL, NULL, 1, 0, '/api/payment/koukuan', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-10-10 02:18:14', '2022-10-10 02:18:14'),
(88, 8, '加款', NULL, NULL, 1, 0, '/api/up/UpIncMoney', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-10-10 02:18:55', '2022-10-10 02:18:55'),
(89, 13, '撤销订单资金', NULL, NULL, 1, 2, '/api/payment/chehuidingdan', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-10-10 02:20:11', '2022-10-10 02:20:11'),
(90, 3, '飞机群机器人授权', NULL, NULL, 1, 31, '/api/user/TGqunbind', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-10-21 16:37:46', '2022-10-21 16:37:46'),
(91, 3, '查看在线终端', NULL, NULL, 1, 0, '/api/user/zxzd', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-19 15:48:18', '2022-11-19 15:48:18'),
(92, 0, '世界杯管理', '/sjb', '', 0, 200, '', '_self', 'el-icon-_network', NULL, 0, NULL, '', 0, 1, '2022-11-29 17:58:26', '2022-11-29 17:58:26'),
(93, 92, '用户管理', '/sjb/userlist', '/sjb/userlist.vue', 0, 20, '', '_self', 'el-icon-user', NULL, 0, NULL, '', 0, 1, '2022-11-29 17:59:50', '2022-11-29 17:59:50'),
(94, 92, '赛事管理', '/sjb/list', '/sjb/list.vue', 0, 30, '', '_self', 'el-icon-video-play', NULL, 0, NULL, '', 0, 1, '2022-11-29 18:00:40', '2022-11-29 18:00:40'),
(95, 92, '投注订单', '/sjb/pay', '/sjb/pay.vue', 0, 40, '', '_self', 'el-icon-_table', NULL, 0, NULL, '', 0, 1, '2022-11-29 18:01:26', '2022-11-29 18:01:26'),
(96, 93, '新增用户', NULL, NULL, 1, 1, '/api/sjb/adduser', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:02:02', '2022-11-29 18:02:02'),
(97, 93, '加款扣款', NULL, NULL, 1, 2, '/api/sjb/money', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:02:21', '2022-11-29 18:02:21'),
(98, 93, '修改用户密码', NULL, NULL, 1, 3, '/api/sjb/upputpassword', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:03:46', '2022-11-29 18:03:46'),
(99, 94, '赛事详情', '/sjb/list/details', '/sjb/details.vue', 0, 1, '', '_self', 'el-icon-_trending-up', NULL, 1, NULL, '', 0, 1, '2022-11-29 18:05:00', '2022-11-29 18:05:00'),
(100, 99, '修改赔率', NULL, NULL, 1, 1, '/api/sjb/xiugaiod', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:05:27', '2022-11-29 18:05:27'),
(101, 99, '手动锁盘', NULL, NULL, 1, 2, '/api/sjb/xiugailok', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:05:49', '2022-11-29 18:05:49'),
(102, 99, '显示隐藏玩法', NULL, NULL, 1, 3, '/api/sjb/xiugaizt', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:06:11', '2022-11-29 18:06:11'),
(103, 95, '标记中奖', NULL, NULL, 1, 1, '/api/sjb/zhongjiang', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:06:27', '2022-11-29 18:06:27'),
(104, 95, '取消中奖', NULL, NULL, 1, 2, '/api/sjb/delzhongjiang', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-11-29 18:06:44', '2022-11-29 18:06:44'),
(105, 92, '数据统计', '/sjb/tongji', '/sjb/tongji', 0, 0, '', '_self', 'el-icon-_trending-up', NULL, 0, NULL, '', 0, 1, '2022-12-02 16:44:37', '2022-12-02 16:44:37'),
(106, 92, '系统配置', '/sjb/set', '/sjb/set.vue', 0, 8, '', '_self', 'el-icon-_setting', NULL, 0, NULL, '', 0, 1, '2022-12-02 16:45:21', '2022-12-02 16:45:21'),
(107, 106, '清理数据', NULL, NULL, 1, 0, '/api/sjb/qingli', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-02 16:46:05', '2022-12-02 16:46:05'),
(108, 106, '修改配置', NULL, NULL, 1, 0, '/api/sjb/putsetting', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-02 16:46:40', '2022-12-02 16:46:40'),
(109, 92, '资金记录', '/sjb/logmoney', '/sjb/logmoney.vue', 0, 60, '', '_self', 'el-icon-postcard', NULL, 0, NULL, '', 0, 1, '2022-12-02 16:48:29', '2022-12-02 16:48:29'),
(110, 93, '封禁用户', NULL, NULL, 1, 0, '/api/sjb/userzt', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-03 14:55:53', '2022-12-03 14:55:53'),
(111, 0, '账户中心', '/user', '', 0, 2, '', '_self', 'el-icon-setting', NULL, 0, NULL, '', 0, 1, '2022-12-03 16:06:54', '2022-12-03 16:06:54'),
(112, 111, '账户安全', '/user/safe', '/user/safe.vue', 0, 6, '', '_self', 'el-icon-set-up', NULL, 0, NULL, '', 0, 1, '2022-12-03 16:09:21', '2022-12-03 16:09:21'),
(113, 111, '子账户', '/user/sub', '/user/sub', 0, 10, '', '_self', 'el-icon-coordinate', NULL, 0, NULL, '', 0, 1, '2022-12-03 16:10:09', '2022-12-03 16:10:09'),
(114, 113, '修改子户账户状态', NULL, NULL, 1, 0, '/api/sub/apizt', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 06:54:29', '2022-12-04 06:54:29'),
(115, 113, '开关子户谷歌验证', NULL, NULL, 1, 0, '/api/sub/googlezt', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 06:57:01', '2022-12-04 06:57:01'),
(116, 113, '修改限管下级号', NULL, NULL, 1, 0, '/api/sub/onezt', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 07:02:12', '2022-12-04 07:02:12'),
(117, 113, '修改加款限制模式', NULL, NULL, 1, 0, '/api/sub/mtzt', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 07:03:33', '2022-12-04 07:03:33'),
(118, 113, '加减余额', NULL, NULL, 1, 0, '/api/sub/money', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 08:20:51', '2022-12-04 08:20:51'),
(119, 113, '修改子账户密码', NULL, NULL, 1, 0, '/api/sub/subpw', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 10:03:11', '2022-12-04 10:03:11'),
(120, 99, '获取赛事', NULL, NULL, 1, 0, '', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 10:48:32', '2022-12-04 10:48:32'),
(121, 95, '获取投注订单', NULL, NULL, 1, 0, '', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 12:23:22', '2022-12-04 12:23:22'),
(122, 109, '获取记录', NULL, NULL, 1, 0, '', '_self', NULL, NULL, 0, NULL, '', 0, 1, '2022-12-04 12:40:50', '2022-12-04 12:40:50'),
(123, 111, '账户信息', '/user/info', '/user/my.vue', 0, 0, '', '_self', 'el-icon-document', NULL, 0, NULL, '', 0, 1, '2022-12-04 15:54:19', '2022-12-04 15:54:19'),
(124, 0, '获取赛事', NULL, NULL, 1, 1, '', '_self', NULL, NULL, 0, NULL, '', 1, 1, '2022-12-04 15:57:36', '2022-12-04 15:57:36'),
(125, 0, 'Dashboard', '/tg', '', 0, 1, '', '_self', 'el-icon-s-home', NULL, 0, NULL, '', 0, 2, '2023-03-14 02:22:36', '2023-03-14 02:22:36'),
(126, 125, '数据概况', '/tg/index', '/tg/index', 0, 10, '', '_self', 'el-icon-data-line', NULL, 0, NULL, '', 0, 2, '2023-03-14 02:43:09', '2023-03-14 02:43:09'),
(127, 0, '兑换配置', '/tg/setup', '/tg/setup', 0, 30, '', '_self', 'el-icon-setting', NULL, 0, NULL, '', 0, 2, '2023-03-14 03:53:34', '2023-03-14 03:53:34'),
(128, 0, '快捷菜单命令', '/tg/command', '/tg/command', 0, 80, '', '_self', 'el-icon-s-operation', NULL, 0, NULL, '', 0, 2, '2023-03-14 03:54:01', '2023-03-14 03:54:01'),
(129, 0, '消息下方按钮', '/tg/msgbtn', '/tg/msgbtn', 0, 90, '', '_self', 'el-icon-_pad', NULL, 0, NULL, '', 0, 2, '2023-03-14 03:55:01', '2023-03-14 03:55:01'),
(130, 0, '回复键盘按钮', '/tg/reply', '/tg/reply', 0, 100, '', '_self', 'el-icon-_keyboard', NULL, 0, NULL, '', 0, 2, '2023-03-14 03:55:34', '2023-03-14 03:55:34'),
(131, 0, '机器人', '/tg/info', '/tg/info', 0, 20, '', '_self', 'el-icon-monitor', NULL, 0, NULL, '', 0, 2, '2023-03-14 04:03:52', '2023-03-14 04:03:52'),
(132, 0, '群组列表', '/tg/group', '/tg/group', 0, 50, '', '_self', 'el-icon-_user-group-solid', NULL, 0, NULL, '', 0, 2, '2023-03-14 04:29:12', '2023-03-14 04:29:12'),
(133, 0, '用户列表', '/tg/user', '/tg/user', 0, 40, '', '_self', 'el-icon-user-solid', NULL, 0, NULL, '', 0, 2, '2023-03-14 04:30:03', '2023-03-14 04:30:03'),
(134, 0, '地址列表', '/tg/trc20', '/tg/trc20', 0, 60, '', '_self', 'el-icon-_cube', NULL, 0, NULL, '', 0, 2, '2023-03-14 04:33:36', '2023-03-14 04:33:36'),
(135, 0, '兑换记录', '/tg/dh_log', '/tg/dh_log', 0, 33, '', '_self', 'el-icon-tickets', NULL, 0, NULL, '{\"badge\": \"New\"}', 0, 2, '2023-03-14 04:37:32', '2023-03-14 04:37:32'),
(136, 129, '发布更新按钮', NULL, NULL, 1, 10, '/api/tgbot/command_markup', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-19 01:25:48', '2023-03-19 01:25:48'),
(137, 129, '删除按钮', NULL, NULL, 1, 20, '/api/tgbot/markup_del', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-19 05:26:16', '2023-03-19 05:26:16'),
(138, 129, '添加事件', NULL, NULL, 1, 19, '/api/tgbot/command_add', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-19 09:35:16', '2023-03-19 09:35:16'),
(139, 128, '添加菜单命令', NULL, NULL, 1, 10, '/api/tgbot/commands_add', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-21 15:04:08', '2023-03-21 15:04:08'),
(140, 128, '删除菜单命令', NULL, NULL, 1, 20, '/api/tgbot/command_del', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-21 16:30:20', '2023-03-21 16:30:20'),
(141, 133, '发送消息', NULL, NULL, 1, 10, '/api/tgbot/send_Msg', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-25 12:57:22', '2023-03-25 12:57:22'),
(142, 132, '发送消息', NULL, NULL, 1, 10, '/api/tgbot/send_Msg', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-25 12:57:40', '2023-03-25 12:57:40'),
(143, 132, '加白名单', NULL, NULL, 1, 8, '/api/tgbot/group_update_zt', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-28 10:37:48', '2023-03-28 10:37:48'),
(144, 132, '机器人退群', NULL, NULL, 1, 6, '/api/tgbot/bot_tuiqun', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-03-28 10:39:20', '2023-03-28 10:39:20'),
(145, 131, '修改机器人信息', NULL, NULL, 1, 10, '/api/tgbot/bot_setup', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-04-27 09:51:59', '2023-04-27 09:51:59'),
(146, 127, '修改兑换配置信息', NULL, NULL, 1, 10, '/api/tgbot/bot_trx_setup', '_self', NULL, NULL, 0, NULL, '', 0, 2, '2023-04-27 15:21:07', '2023-04-27 15:21:07');

-- --------------------------------------------------------

--
-- 表的结构 `sys_role`
--

CREATE TABLE `sys_role` (
  `roleId` int(11) NOT NULL COMMENT '角色id',
  `roleName` varchar(200) NOT NULL COMMENT '角色名称',
  `roleCode` varchar(200) NOT NULL COMMENT '角色标识',
  `comments` varchar(400) DEFAULT NULL COMMENT '备注',
  `del` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除, 0否, 1是',
  `tenantId` int(11) NOT NULL DEFAULT '1' COMMENT '租户id',
  `theme` varchar(255) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `sys_role`
--

INSERT INTO `sys_role` (`roleId`, `roleName`, `roleCode`, `comments`, `del`, `tenantId`, `theme`, `createTime`, `updateTime`) VALUES
(1, '系统管理员', 'admin', '系统管理员', 0, 1, '', '2020-02-26 07:18:37', '2020-03-21 07:15:54'),
(2, '支付商户', 'payuser', '支付系统商户', 0, 1, '', '2020-02-26 07:18:52', '2020-03-21 07:16:02'),
(3, '卡商/码商', 'payks', '支付系统码商/卡商', 0, 1, '', '2020-02-26 07:18:52', '2020-03-21 07:16:02'),
(4, '主管', 'sub', '支付系统子账户', 0, 1, '', '2022-08-11 07:08:08', '2022-08-11 07:08:08'),
(5, '世界杯超管', 'sjbadmin', '世界杯系统管理员', 0, 1, '', '2022-11-29 18:07:30', '2022-11-29 18:07:30'),
(6, 'TRX兑换管理员', 'trxadmin', 'trx兑换项目管理员', 0, 2, '{\"sideUniqueOpen\":false,\"colorfulIcon\":true}', '2023-02-08 07:05:08', '2023-02-08 07:05:08'),
(7, '记账机器管理员', 'keep', '记账机器人管理员', 0, 2, '{\"sideUniqueOpen\":false,\"colorfulIcon\":true}', '2023-03-22 03:11:26', '2023-03-22 03:11:26'),
(8, '云记账超管', 'keepadmin', '', 0, 2, '{\"sideUniqueOpen\":false,\"colorfulIcon\":true}', '2023-04-03 16:31:01', '2023-04-03 16:31:01');

-- --------------------------------------------------------

--
-- 表的结构 `sys_role_menu`
--

CREATE TABLE `sys_role_menu` (
  `id` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `userId` int(11) NOT NULL DEFAULT '0',
  `tenantId` int(11) NOT NULL,
  `menuText` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sys_role_menu`
--

INSERT INTO `sys_role_menu` (`id`, `roleId`, `userId`, `tenantId`, `menuText`) VALUES
(1, 1, 0, 1, '[2,51,10,11,1,9]'),
(2, 2, 0, 1, '[1,2,5,19,86,23,3,91,20,21,22,39,50,57,65,66,67,83,84,90,123,113,114,115,116,117,118,119,70,73,74,6,7,28,41,42,43,61,45,60,59,46,62,63,64,8,87,88,17,85,16,27,58,24,54,55,56,47,48,49,12,13,18,26,25,81,89,14,52,53,80,75,76,82,79,77,78,29,30,32,33,31,34,35,68,69,111]'),
(3, 3, 10002, 1, '[1,2]'),
(4, 3, 10003, 1, '[1,2]'),
(5, 3, 0, 1, '[1,2,19,20,48,55,17,16,18,52,76,77,78,32,34,68,69,3,5,4,40,47,54,6,8,12,13,14,80,75,29,30,31]'),
(6, 4, 0, 1, '[2,1]'),
(8, 4, 100029, 1, '[2,19,86,42,17,55,48,18,76,32,34,68,69,1,5,6,41,8,54,47,12,13,80,75,29,30,31]'),
(9, 4, 100032, 1, '[1,2,3,5,19,86,23,4,20,50,39,66,84,83,65,21,67,57,22,74,73,71,72,70,6,41,42,43,61,45,60,59,46,62,63,64,7,28,8,88,87,85,17,16,27,58,24,54,55,56,47,48,49,12,13,18,26,89,81,14,52,53,80,75,76,82,79,77,78,29,30,32,31,34,68,69]'),
(10, 4, 100036, 1, '[2,19,86,3,91,21,84,83,20,39,22,90,74,73,72,71,70,50,57,67,66,65,18,52,76,78,32,34,68,69,1,5,12,13,14,80,75,29,30,31]'),
(11, 5, 0, 1, '[123,113,114,115,116,117,118,119,70,73,74,92,105,106,107,108,93,110,96,97,98,94,99,120,100,101,102,95,121,103,104,109,122,111]'),
(12, 4, 100042, 1, '[123,93,110,96,97,98,121,104,109,122,111,92,95]'),
(13, 6, 0, 2, '[125,126,131,145,127,146,135,133,141,132,144,143,142,134,128,139,140,129,136,138,137,130]'),
(14, 7, 0, 2, '[125,126,131,145,133,141,132,144,143,142,128,139,140,129,136,138,137,130]'),
(15, 8, 0, 2, '[125,126,131,145,133,141,132,144,143,142,128,139,140,129,136,138,137,130]');

-- --------------------------------------------------------

--
-- 表的结构 `sys_tenantId`
--

CREATE TABLE `sys_tenantId` (
  `tenantId` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `name` varchar(16) NOT NULL,
  `text` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='租户';

--
-- 转存表中的数据 `sys_tenantId`
--

INSERT INTO `sys_tenantId` (`tenantId`, `del`, `name`, `text`) VALUES
(1, 0, '支付系统', '支付 世界杯 卡商 码商'),
(2, 0, 'TG机器人系统', '电报机器人系统');

-- --------------------------------------------------------

--
-- 表的结构 `sys_theme`
--

CREATE TABLE `sys_theme` (
  `userId` int(11) NOT NULL,
  `theme` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `sys_theme`
--

INSERT INTO `sys_theme` (`userId`, `theme`) VALUES
(100046, '{\"sideUniqueOpen\":false}'),
(100047, '{\"sideUniqueOpen\":false,\"colorfulIcon\":true}'),
(100051, '{\"colorfulIcon\":true,\"sideUniqueOpen\":false}');

-- --------------------------------------------------------

--
-- 表的结构 `sys_user_googel`
--

CREATE TABLE `sys_user_googel` (
  `id` int(11) NOT NULL,
  `myid` int(11) NOT NULL,
  `menuText` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sys_user_googel`
--

INSERT INTO `sys_user_googel` (`id`, `myid`, `menuText`) VALUES
(1, 100012, '[57,39,50,3,4]'),
(2, 100011, '[57,39,50,3,4]'),
(3, 100013, '[57,39,50,3,4,65,66]'),
(4, 100020, '[57,39,50,3,4,65,66]');

-- --------------------------------------------------------

--
-- 表的结构 `tb_account`
--

CREATE TABLE `tb_account` (
  `id` int(11) NOT NULL COMMENT '商户',
  `del` int(11) NOT NULL DEFAULT '0',
  `roleId` int(11) NOT NULL DEFAULT '0',
  `tenantId` int(11) NOT NULL DEFAULT '0',
  `upid` int(11) NOT NULL DEFAULT '0' COMMENT '上家id',
  `key` char(32) DEFAULT NULL COMMENT '通讯密匙',
  `username` char(15) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `spassword` varchar(11) DEFAULT NULL,
  `SecretKey` varchar(32) NOT NULL COMMENT '谷歌key',
  `tgid` bigint(20) NOT NULL,
  `Telegram` json NOT NULL,
  `money` decimal(10,2) DEFAULT '5.00' COMMENT '余额',
  `tmoney` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '待提现',
  `smoney` decimal(13,2) DEFAULT '0.00' COMMENT '流水',
  `ddnumber` int(11) DEFAULT '0' COMMENT '总订单',
  `rate` decimal(5,2) DEFAULT '1.38',
  `tel` decimal(11,0) DEFAULT '18800000000',
  `regtime` int(11) DEFAULT '1514736000',
  `api` int(11) DEFAULT '1' COMMENT '?可用',
  `google` int(11) NOT NULL DEFAULT '0' COMMENT '?谷歌',
  `post` int(11) NOT NULL DEFAULT '1' COMMENT '回调',
  `moshi` int(11) NOT NULL,
  `numMoney` decimal(10,2) NOT NULL,
  `sxf` decimal(10,2) NOT NULL,
  `etc` decimal(10,2) NOT NULL COMMENT '下发中',
  `sauto` int(11) NOT NULL DEFAULT '1' COMMENT '分笔',
  `webhook` int(11) NOT NULL,
  `webhookurl` varchar(64) NOT NULL,
  `dfset` int(11) NOT NULL,
  `DecSet` int(11) NOT NULL,
  `one` int(11) NOT NULL COMMENT '自己',
  `mt` int(11) NOT NULL COMMENT '扣自身余额'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `tb_account`
--

INSERT INTO `tb_account` (`id`, `del`, `roleId`, `tenantId`, `upid`, `key`, `username`, `password`, `spassword`, `SecretKey`, `tgid`, `Telegram`, `money`, `tmoney`, `smoney`, `ddnumber`, `rate`, `tel`, `regtime`, `api`, `google`, `post`, `moshi`, `numMoney`, `sxf`, `etc`, `sauto`, `webhook`, `webhookurl`, `dfset`, `DecSet`, `one`, `mt`) VALUES
(100000, 0, 1, 1, 0, '-', 'admin', 'd1120e2540282ea96c0fe30212b23819', '123456', 'YZRAR3RWULHBITV3', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18888888888', 1514736000, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100001, 0, 2, 1, 0, '07F6570C31FA81FE18720295B659B101', '123456', 'd1120e2540282ea96c0fe30212b23819', '123456', 'DSC5HXZJCZ4SGNEN', 0, 'null', '99998.62', '5500.00', '5600.00', 2, '1.38', '18800000000', 1610350806, 1, 0, 1, 0, '0.00', '70.38', '0.00', 0, 0, '', 0, 0, 0, 0),
(100002, 1, 3, 1, 100001, 'F9F3342B11F9699DEF6D4C8B95E330AD', '456789', 'd1120e2540282ea96c0fe30212b23819', '123456', ' ', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18800000000', 1610350806, 0, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100003, 1, 3, 1, 100001, 'F9F3342B11F9699DEF6D4C8B95E330AD', '1472555', 'd1120e2540282ea96c0fe30212b23819', '123456', ' ', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18800000000', 1610350806, 0, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100004, 0, 2, 1, 0, '3DAB465CC0BE31683734A73FA852F19C', 'ag888666', 'd1120e2540282ea96c0fe30212b23819', NULL, 'E6BY65GSCZDV6OD7', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.01', '18800000000', 1659925320, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100005, 0, 2, 1, 0, '317ABB80CFF67692E5159870C57C1C84', 'AG7444', 'd1120e2540282ea96c0fe30212b23819', 'AG7444', 'DTSYTV64JDSAI4WX', 0, 'null', '100000.00', '0.00', '0.00', 0, '2.90', '18800000000', 1659948718, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100006, 1, 3, 1, 100005, '29602E3E4AA834E79A544A397C4331E9', '123444', 'd1120e2540282ea96c0fe30212b23819', NULL, 'G5GT4K64GZOPLSEG', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18800000000', 1660030533, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100007, 1, 3, 1, 100001, 'C3F4FF6D14AA13075530CEB2E7402B45', '444555', 'd1120e2540282ea96c0fe30212b23819', NULL, 'DV7UF7CFHS24TGNW', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18800000000', 1660205380, 0, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100010, 0, 2, 1, 0, '2D8B1DD71E24FA86982CC869F88AAFC3', 'JM001', 'd1120e2540282ea96c0fe30212b23819', '123456', '66MIIBNZANEO4NEN', 0, 'null', '100000.00', '0.00', '0.00', 0, '2.20', '18800000000', 1660727030, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100008, 0, 4, 1, 100005, '6A24C83B9CCC11B8D7BC779FC7B36BBF', 'niu123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'INAEVSYDOAOZCK2D', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18800000000', 1660263322, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100009, 0, 3, 1, 100005, '425E3001276B22F2D15C83D4D0DDF960', 'shouge001', 'd1120e2540282ea96c0fe30212b23819', NULL, 'MPZ5S5UWRITIP7KO', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18800000000', 1660546776, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100011, 0, 2, 1, 0, 'C2B9B588A12B96EBB8FEE54304739F4D', 'ggqpmy207', 'd1120e2540282ea96c0fe30212b23819', 'oNHpr37bXPz', 'VV3UC6VKXFSQJL75', 0, 'null', '100000.00', '0.00', '0.00', 0, '2.00', '18800000000', 1660729424, 0, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100012, 0, 2, 1, 0, '0B6996F65096A44C0576266DCB57BEFE', '18luck', 'd1120e2540282ea96c0fe30212b23819', NULL, 'ZN52GE54JX7IG6UC', 0, 'null', '100000.00', '0.00', '0.00', 0, '2.20', '18800000000', 1660830644, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100013, 0, 2, 1, 0, 'A6DFC45380C1976B357CE337A375BB39', 'ag888999', 'd1120e2540282ea96c0fe30212b23819', 'JYU45TYR', 'KD3OY34QBFKRREIZ', 0, 'null', '89069.68', '-7254.21', '672213.79', 178, '1.60', '18800000000', 1662622025, 1, 0, 1, 1, '2.00', '10930.32', '0.00', 0, 0, '', 0, 0, 0, 0),
(100014, 0, 4, 1, 100013, '1F4541623EB45701B5CA37694B8F96B0', 'laohu111', 'd1120e2540282ea96c0fe30212b23819', NULL, 'QJLBMQVSEJUXSXKQ', 0, 'null', '100000.00', '0.00', '0.00', 0, '0.00', '18800000000', 1662783070, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100015, 0, 3, 1, 100013, 'code15', 'tu123456', 'd1120e2540282ea96c0fe30212b23819', NULL, 'NASB2TLZLMY6X7PH', 0, 'null', '100000.00', '2582.56', '225972.56', 65, '0.00', '18800000000', 1662787826, 1, 0, 1, 0, '0.00', '3615.57', '0.00', 0, 0, '', 0, 0, 0, 0),
(100016, 0, 3, 1, 100001, '6B71A3AB571050BD602AF69F94E98E45', '111222', 'd1120e2540282ea96c0fe30212b23819', NULL, 'LZRVNQP6VKFVMT7C', 0, 'null', '100000.00', '5500.00', '5600.00', 2, '0.00', '18800000000', 1662795303, 1, 0, 1, 0, '0.00', '70.38', '0.00', 0, 0, '', 6, 0, 0, 0),
(100017, 0, 3, 1, 100013, 'CB3DB1F7ADA90D4A090B5DC65573CEA7', 'huai123456', 'd1120e2540282ea96c0fe30212b23819', NULL, 'PZHI5KMAFBPJFQIZ', 0, 'null', '100000.00', '0.17', '88485.17', 7, '0.00', '18800000000', 1662948520, 1, 0, 1, 0, '0.00', '1415.76', '0.00', 0, 0, '', 0, 0, 0, 0),
(100018, 0, 3, 1, 100013, 'code18', 'hai123456', 'd1120e2540282ea96c0fe30212b23819', NULL, 'CATMPFGSCGRZAA2E', 0, 'null', '5.00', '1143.38', '368686.38', 106, '0.00', '18800000000', 1663128742, 1, 0, 1, 0, '0.00', '5898.99', '0.00', 0, 0, '', 0, 0, 0, 0),
(100019, 0, 3, 1, 100013, 'n19', 'nong1011', 'd1120e2540282ea96c0fe30212b23819', NULL, 'U75KDEBMHX5BUCOT', 0, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1663239130, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100020, 0, 2, 1, 0, 'D86DD89C61FB786CAB91791CBBC81CE4', 'jin666', 'd1120e2540282ea96c0fe30212b23819', '090807aa', '67UW5TNI7P5LSQMN', 0, 'null', '-40767.13', '29987.13', '5597161.00', 1981, '2.50', '18800000000', 1663663709, 1, 0, 1, 0, '2.00', '139929.13', '0.00', 0, 0, '', 0, 0, 0, 0),
(100021, 0, 4, 1, 100020, '7996D5ACB6835CC376F4F2AAC2665C43', 'test001', 'd1120e2540282ea96c0fe30212b23819', NULL, 'LFLOZALVPCHDOVP6', 0, 'null', '0.00', '0.00', '0.00', 0, '0.00', '18800000000', 1663995291, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100022, 1, 3, 1, 100020, '181AA216E527D68D3726EBC23A472946', 'nong001', 'd1120e2540282ea96c0fe30212b23819', NULL, 'AIXH7BITZUX6ZYSE', 0, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1663995335, 0, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100025, 0, 3, 1, 100020, '8D22305255B028CFD5B6BEA9E850A8A6', 'wang123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'VV5LENQRMKGQMFIF', 0, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1664601834, 0, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100023, 0, 2, 1, 0, '7AF40282BE5C53EB8E53BD314B032A47', 'gd8888', 'd1120e2540282ea96c0fe30212b23819', NULL, 'J4LJF3JC6L6Y35AD', 0, 'null', '100000.00', '0.00', '0.00', 0, '1.80', '18800000000', 1664358448, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100024, 0, 2, 1, 0, '33FFF40580F6FD0608044D92CAC2D69E', 'wg0001', 'd1120e2540282ea96c0fe30212b23819', NULL, 'WYLEHV4XIUX3CTSR', 0, 'null', '99961.36', '2800.00', '2800.00', 4, '1.38', '18800000000', 1664529920, 1, 0, 1, 0, '0.00', '38.64', '0.00', 0, 0, '', 0, 0, 0, 0),
(100026, 0, 3, 1, 100020, '8270A950C19FDD438D3C59825B53C594', 'tai123', 'd1120e2540282ea96c0fe30212b23819', NULL, '4FQXPA3Y7NF7XQGQ', 0, 'null', '5.00', '30983.55', '5259196.00', 1902, '0.00', '18800000000', 1664610636, 1, 0, 1, 0, '0.00', '131479.99', '0.00', 0, 0, '', 6, 0, 0, 0),
(100027, 0, 3, 1, 100020, 'ADB9D39C4B46E145A6C1A90E16D8AABE', 'tuying123', 'd1120e2540282ea96c0fe30212b23819', NULL, '2PR56HRHWYDSCGG3', 0, 'null', '5.00', '389.58', '121436.00', 21, '0.00', '18800000000', 1664678891, 1, 0, 1, 0, '0.00', '3035.91', '0.00', 0, 0, '', 0, 0, 0, 0),
(100028, 0, 3, 1, 100020, '780F1D22F265826392148B359028438C', 'hai123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'UPCVHTMWYVFOYK4V', 0, 'null', '5.00', '0.00', '216529.00', 58, '0.00', '18800000000', 1664679151, 1, 0, 1, 0, '0.00', '5413.23', '0.00', 0, 0, '', 0, 0, 0, 0),
(100029, 0, 4, 1, 100020, '67294190E7AC362D90240648DC772A38', 'timo123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'WHYLMKKB6YNARQMV', 0, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1664720778, 1, 0, 1, 0, '0.00', '0.00', '0.00', 0, 0, '', 0, 0, 0, 0),
(100030, 0, 3, 1, 100024, 'D1510A1BE14FFB88D373747C0B79190D', '621357666888015', 'd1120e2540282ea96c0fe30212b23819', NULL, 'LE4XXGXZOKAG3WIH', 0, 'null', '5.00', '2800.00', '2800.00', 4, '0.00', '18800000000', 1664809418, 1, 0, 1, 0, '0.00', '38.64', '0.00', 0, 0, '', 0, 0, 0, 0),
(100031, 0, 2, 1, 0, '54950D308F9BA92CFD6E8A96025F972C', 'laotou656', 'd1120e2540282ea96c0fe30212b23819', NULL, 'M5D6EE343JEIUBZV', 0, 'null', '100000.00', '0.00', '0.00', 0, '1.80', '18800000000', 1665672756, 1, 0, 1, 0, '2.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100032, 1, 4, 1, 100031, '9431BCB9382EFE6C0016872720FB7ACC', 'beiyi666', 'd1120e2540282ea96c0fe30212b23819', NULL, 'EEY4TEGDRB7K54RC', 0, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1665672973, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100033, 0, 3, 1, 100031, 'D30EFA3E3DE5B2D347836D8BFAA239BC', '1', 'd1120e2540282ea96c0fe30212b23819', NULL, 'JTAWGC2WWS4DF2K5', 0, 'null', '5.00', '1.00', '0.00', 0, '0.00', '18800000000', 1665673551, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100034, 0, 3, 1, 100020, '33F2FBB0026F1A72F9258C294F0F85EF', 'tuohai123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'APCTZ7I3SLOAOSIC', 0, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1665843673, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100035, 0, 2, 1, 0, 'FE2208CABDDB4EFBB004B545AFE3F111', 'yabotest', 'd1120e2540282ea96c0fe30212b23819', NULL, 'LE4XXGXZOKAG3WIH', 0, 'null', '0.00', '0.00', '0.00', 0, '0.00', '18800000000', 1668318489, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100036, 0, 4, 1, 100035, 'FE2208CABDDB4EFBBD94B545AFE3F9D9', 'yabo123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'Q4NZDTTALUHOHE27', 0, 'null', '1000000.00', '0.00', '0.00', 0, '2.40', '18800000000', 1668318902, 1, 0, 1, 0, '2.00', '0.00', '0.00', 1, 0, '', 0, 1, 0, 0),
(100037, 0, 3, 1, 100035, '4BF9427F0DFABA0EB1BC88C4232188F0', 'testyb', 'd1120e2540282ea96c0fe30212b23819', NULL, 'SEFTUB53WB2JYP25', 0, 'null', '5.00', '2173.00', '2733.00', 2, '0.00', '18800000000', 1668349030, 1, 0, 1, 0, '0.00', '73.79', '320.00', 1, 0, '', 6, 0, 0, 0),
(100038, 0, 3, 1, 100020, 'AAF6F50EDED2D8C2C827C555A48E83B5', 'kaka123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'MEZCQJRIZ6GY475P', 0, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1668679464, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 6, 0, 0, 0),
(100039, 0, 5, 1, 0, 'E4D4C66586051D2AB23FD9727BF19ED8', 'adminsjb', 'd1120e2540282ea96c0fe30212b23819', NULL, 'QMX2WKDR2LH3NRUQ', 0, 'null', '5.00', '0.00', '0.00', 0, '1.38', '18800000000', 1669745432, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100040, 1, 4, 1, 100039, '58F60A039DB42B997BAD60B64C2DB431', 'testsjb', 'd1120e2540282ea96c0fe30212b23819', NULL, 'ZIQW2HWMT3YQBDIQ', 0, 'null', '106.00', '0.00', '0.00', 0, '0.00', '18800000000', 1670070712, 1, 1, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100041, 1, 4, 1, 100039, '96197B5E2551EAF19746A60D49EF4D49', 'kspade', 'd1120e2540282ea96c0fe30212b23819', NULL, 'IJ5QF7LMSSO2MS73', 0, 'null', '10.00', '0.00', '10.00', 0, '0.00', '18800000000', 1670144932, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100042, 0, 4, 1, 100039, '7F17FAAC3625EFA1113FC14834F9D2A1', 'kspade3', 'd1120e2540282ea96c0fe30212b23819', NULL, 'UUTDK4ZITC5VS3QY', 0, 'null', '60.00', '0.00', '100.00', 0, '0.00', '18800000000', 1670145844, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 1, 0),
(100043, 0, 4, 1, 100001, '1AB6BE428A986CF1DC56C32585C955ED', '789789', 'd1120e2540282ea96c0fe30212b23819', NULL, 'OXLSDJFNZGGDWAZM', 0, 'null', '0.00', '0.00', '0.00', 0, '0.00', '18800000000', 1673585637, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100044, 0, 6, 2, 0, 'CB0126DA375594534D434DA6C931D464', 'trx123', 'd1120e2540282ea96c0fe30212b23819', NULL, 'A7YXVJQZX5LYMXTA', 0, 'null', '5.00', '0.00', '0.00', 0, '1.38', '18800000000', 1675839997, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100045, 0, 6, 2, 0, '3BA4822C4DA2B03C62B863E7F7FC1A82', '1418208536', 'd1120e2540282ea96c0fe30212b23819', NULL, 'REB4FV5MYX4FJJZ5', 1418208536, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1678804441, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100046, 0, 7, 2, 0, '93C3964D413AE78B5D76815B8A50F74C', '5677571362', 'd1120e2540282ea96c0fe30212b23819', NULL, 'JSW4UULLXXD4FG5M', 5677571362, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1679487108, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100047, 0, 6, 2, 0, '3DF0D8F39FAE9814E3053EE599E190D7', '65677571362', 'd1120e2540282ea96c0fe30212b23819', NULL, 'RKRLU7SVDJ2M7VAW', 5677571362, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1680098736, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100048, 0, 7, 2, 0, '38180A9B2649F58AE433637A91135BA1', '71147496086', NULL, NULL, 'DUB7UGKB6ZUONBVW', 1147496086, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1680410189, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100049, 0, 8, 2, 0, '239D128876745E97B73E310C8ADADD8C', '71418208536', NULL, NULL, 'Z23H7G55NQK6C6XC', 1418208536, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1680457475, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100050, 0, 8, 2, 0, '6E54B0C2F84B2511674CE870D575EFB0', '81418208536', NULL, NULL, 'SIPZWX5QSIBUO5ER', 1418208536, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1680574100, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0),
(100051, 0, 8, 2, 0, 'D332770FF7C64C192204EE09DC6029E3', '85677571362', NULL, NULL, '3CHLAPLYAHSSSWCC', 5677571362, 'null', '5.00', '0.00', '0.00', 0, '0.00', '18800000000', 1680767661, 1, 0, 1, 0, '0.00', '0.00', '0.00', 1, 0, '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tb_account_tg`
--

CREATE TABLE `tb_account_tg` (
  `id` int(6) NOT NULL,
  `del` int(1) NOT NULL COMMENT '停',
  `roleId` int(2) NOT NULL DEFAULT '1' COMMENT '角色id',
  `up` bigint(20) NOT NULL COMMENT '邀请',
  `bot` varchar(32) NOT NULL COMMENT '所属机器人',
  `tgid` bigint(20) NOT NULL COMMENT '电报ID',
  `username` varchar(16) NOT NULL COMMENT '电报用户名',
  `name` varchar(32) NOT NULL COMMENT '称呼',
  `regtime` int(11) NOT NULL COMMENT '注册时间',
  `tgnum` int(5) NOT NULL COMMENT '邀请数量',
  `tgtrx` bigint(20) NOT NULL,
  `tgyue` bigint(20) NOT NULL,
  `dhnum` int(11) NOT NULL,
  `dhusdt` bigint(20) NOT NULL,
  `dhtrx` bigint(20) NOT NULL,
  `send` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_account_tg`
--

INSERT INTO `tb_account_tg` (`id`, `del`, `roleId`, `up`, `bot`, `tgid`, `username`, `name`, `regtime`, `tgnum`, `tgtrx`, `tgyue`, `dhnum`, `dhusdt`, `dhtrx`, `send`) VALUES
(1, 0, 1, 0, 'uuuttjzbot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1679932542, 0, 0, 0, 0, 0, 0, 0),
(2, 0, 1, 0, 'SwapTRX8bot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1680096869, 0, 0, 0, 0, 0, 0, 0),
(3, 0, 1, 0, 'SwapTRX8bot', 5451126148, 'shenma090', '神马源码资源网shenma.store', 1680101724, 0, 0, 0, 0, 0, 0, 0),
(4, 0, 1, 0, 'uuuttjzbot', 5331998515, 'skskil', '飞机代理 飞机会员TRX兑换 USDT靓号', 1680264578, 0, 0, 0, 0, 0, 0, 0),
(5, 0, 1, 0, 'jzjqe_bot', 1147496086, 'lw17688', '小高', 1680409946, 0, 0, 0, 0, 0, 0, 0),
(6, 0, 1, 0, 'jzjqe_bot', 5028546025, 'qxhu1121', '小虾', 1680409946, 0, 0, 0, 0, 0, 0, 0),
(7, 0, 1, 0, 'jzjqe_bot', 5473052747, 'tgsvipcn', '厉飞雨TRX承兑 | 代开✈️会员|', 1680410022, 0, 0, 0, 0, 0, 0, 0),
(8, 0, 1, 0, 'jzjqe_bot', 1418208536, 'gd801', '@GD国际', 1680410813, 0, 0, 0, 0, 0, 0, 0),
(9, 0, 1, 0, 'jizhangbot_bot', 1418208536, 'gd801', '@GD国际', 1680457419, 0, 0, 0, 0, 0, 0, 0),
(10, 0, 1, 0, 'jizhangbot_bot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1680493740, 0, 0, 0, 0, 0, 0, 0),
(11, 0, 1, 0, 'jizhangbot_bot', 1147496086, 'lw17688', '小高', 1680502122, 0, 0, 0, 0, 0, 0, 0),
(12, 0, 1, 0, 'AIjizhangbot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1680532732, 0, 0, 0, 0, 0, 0, 0),
(13, 0, 1, 0, 'jizhangbot_bot', 5751451194, 'xb5187', '|̲̅S̲̅V̲̅I̲̅P̲̅|海外引流软件ZZZ', 1680594753, 0, 0, 0, 0, 0, 0, 0),
(14, 0, 1, 0, 'jzjqe_bot', 5191065740, 'Aknices', 'Aknices(简介白嫖5u）', 1680620310, 0, 0, 0, 0, 0, 0, 0),
(15, 0, 1, 0, 'jizhangadminbot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1680766494, 0, 0, 0, 0, 0, 0, 0),
(16, 0, 1, 0, 'AIjizhangbot', 5650195126, '未设置', '欧鹿comba', 1680792844, 0, 0, 0, 0, 0, 0, 0),
(17, 1, 1, 0, 'AIjizhangbot', 6124327833, 'xtjt01', '新泰集团 出海项目招商入金秒返80%', 1680793075, 0, 0, 0, 0, 0, 0, 0),
(18, 1, 1, 0, 'AIjizhangbot', 1302909587, 'jklovedxd', '发卡对接tokenpay/epusdt微软邮箱，代搭咨询', 1680829556, 0, 0, 0, 0, 0, 0, 0),
(19, 0, 1, 0, 'SwapTRX8bot', 6105260885, 'Qiangge52', '高启强', 1680839468, 0, 0, 0, 0, 0, 0, 0),
(20, 0, 1, 0, 'SwapTRX8bot', 5155650641, 'PonyYun', '3秒自动部署记账机器人', 1680839556, 0, 0, 0, 0, 0, 0, 0),
(21, 0, 1, 0, 'SwapTRX8bot', 5331998515, 'skskil', '飞机代理 飞机会员TRX兑换 USDT靓号', 1680877338, 0, 0, 0, 0, 0, 0, 0),
(22, 0, 1, 0, 'SwapTRX8bot', 5650195126, '未设置', '欧鹿comba', 1680877575, 0, 0, 0, 0, 0, 0, 1),
(23, 0, 1, 0, 'SwapTRX8bot', 829908253, 'ZhangYiQiu', 'Light', 1680882799, 0, 0, 0, 0, 0, 0, 0),
(24, 0, 1, 0, 'SwapTRX8bot', 5273100625, 'kabuxibu', 'Deftq', 1680918269, 0, 0, 0, 0, 0, 0, 0),
(25, 0, 1, 0, 'SwapTRX8bot', 6122867634, '未设置', 'Groot', 1681138010, 0, 0, 0, 0, 0, 0, 0),
(26, 0, 1, 0, 'AIjizhangbot', 6242062754, 'xtjt02', '新泰-cs002 trx兑换', 1681347791, 0, 0, 0, 0, 0, 0, 0),
(27, 0, 1, 0, 'SwapTRX8bot', 1976182001, 'XDZF006', '锦鲤（转账语音确认）', 1681631046, 0, 0, 0, 0, 0, 0, 0),
(28, 0, 1, 0, 'SwapTRX8bot', 5474642108, 'zoxin66669', '挂壁集团-子安', 1681653575, 0, 0, 0, 0, 0, 0, 0),
(29, 0, 1, 0, 'SwapTRX8bot', 1405597912, 'vgrpc', 'blackhole', 1682073473, 0, 0, 0, 0, 0, 0, 0),
(30, 0, 1, 0, 'SwapTRX8bot', 5811051132, 'chuanzhangb', 'laBar', 1682073475, 0, 0, 0, 0, 0, 0, 0),
(31, 0, 1, 0, 'SwapTRX8bot', 825512163, 'HFTGID', 'HF', 1682073650, 0, 0, 0, 0, 0, 0, 0),
(32, 0, 1, 0, 'SwapTRX8bot', 1302909587, 'jklovedxd', 'JKLOVE的小店', 1682074092, 0, 0, 0, 0, 0, 0, 0),
(33, 0, 1, 0, 'SwapTRX8bot', 5602956089, 'YC1798', '月初', 1682074356, 0, 0, 0, 0, 0, 0, 0),
(34, 0, 1, 0, 'SwapTRX8bot', 5509347058, 'jiadianbao', '啥都会', 1682095583, 0, 0, 0, 0, 0, 0, 0),
(35, 0, 1, 0, 'SwapTRX8bot', 6264268800, 'doocop', 'miya', 1682098435, 0, 0, 0, 0, 0, 0, 0),
(36, 0, 1, 0, 'SwapTRX8bot', 5445132792, 'Ac551688', 'Ac', 1682128505, 0, 0, 0, 0, 0, 0, 0),
(37, 0, 1, 0, 'AIjizhangbot', 1418208536, 'gd801', 'GD国际', 1682263132, 0, 0, 0, 0, 0, 0, 0),
(38, 0, 1, 0, 'Tonvip_bot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1682403967, 0, 0, 0, 0, 0, 0, 0),
(39, 0, 1, 0, 'Tonvip_bot', 1418208536, 'gd801', 'GD国际', 1682424067, 0, 0, 0, 0, 0, 0, 0),
(40, 0, 1, 0, 'Tonvip_bot', 5473052747, 'tgsvipcn', 'PLUS代开代付|代开✈️会员【官方认证】', 1682424279, 0, 0, 0, 0, 0, 0, 0),
(41, 0, 1, 0, 'Tonvip_bot', 825512163, 'HFTGID', 'HF', 1682425717, 0, 0, 0, 0, 0, 0, 0),
(42, 0, 1, 0, 'Tonvip_bot', 6264268800, 'doocop', 'miya', 1682426719, 0, 0, 0, 0, 0, 0, 0),
(43, 0, 1, 0, 'Tonvip_bot', 829908253, 'ZhangYiQiu', 'Light', 1682438089, 0, 0, 0, 0, 0, 0, 0),
(44, 1, 1, 0, 'SwapTRX8bot', 1305972842, 'ayang1', '阳阿', 1682438925, 0, 0, 0, 0, 0, 0, 0),
(45, 0, 1, 0, 'SwapTRX8bot', 5117608612, 'yiba55555', '北小', 1682482840, 0, 0, 0, 0, 0, 0, 0),
(46, 0, 1, 0, 'Tonvip_bot', 5117608612, 'yiba55555', '北小', 1682482847, 0, 0, 0, 0, 0, 0, 0),
(47, 1, 1, 0, 'Tonvip_bot', 1305972842, 'ayang1', '阳阿', 1682492111, 0, 0, 0, 0, 0, 0, 0),
(48, 0, 1, 0, 'SwapTRX8bot', 389194643, 'Limson_2023', '森【格物致知】', 1682508419, 0, 0, 0, 0, 0, 0, 0),
(49, 0, 1, 0, 'uuuhfjfjffbot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1682653326, 0, 0, 0, 0, 0, 0, 0),
(50, 0, 1, 0, 'UIUIUI111bot', 5677571362, 'smalpony', '接单机器人定制(任何)', 1682683722, 0, 0, 0, 0, 0, 0, 0),
(51, 0, 1, 0, 'Tonvip_bot', 2102459731, 'wallgooo', 'wall', 1682735226, 0, 0, 0, 0, 0, 0, 0),
(52, 0, 1, 0, 'SwapTRX8bot', 1914346828, 'BzmDwy', '不知名的网友', 1682768137, 0, 0, 0, 0, 0, 0, 0),
(53, 0, 1, 0, 'SwapTRX8bot', 5808856500, 'Kaoru0102', 'hungKaoru', 1682810387, 0, 0, 0, 0, 0, 0, 0),
(54, 0, 1, 0, 'SwapTRX8bot', 5672083820, 'guanzi66', '📞GuanZi', 1682827154, 0, 0, 0, 0, 0, 0, 0),
(55, 0, 1, 0, 'SwapTRX8bot', 2127845398, 'shanjunwangluo', 'tonl', 1682853190, 0, 0, 0, 0, 0, 0, 0),
(56, 0, 1, 0, 'SwapTRX8bot', 884907661, 'wcnm886', 'papapa', 1682862060, 0, 0, 0, 0, 0, 0, 0),
(57, 0, 1, 0, 'Tonvip_bot', 2127845398, 'shanjunwangluo', 'tonl', 1682866345, 0, 0, 0, 0, 0, 0, 0),
(58, 0, 1, 0, 'SwapTRX8bot', 6136616141, 'w33789', '王语嫣', 1682896975, 0, 0, 0, 0, 0, 0, 0),
(59, 0, 1, 0, 'SwapTRX8bot', 5650031836, 'TRXNL6666', '大眼哥（24小时兑换能量）司法/流水/案件/同住', 1682954853, 0, 0, 0, 0, 0, 0, 0),
(60, 0, 1, 0, 'Tonvip_bot', 5250309421, 'Bigcliem', '飞鸟', 1682997631, 0, 0, 0, 0, 0, 0, 0),
(61, 0, 1, 0, 'SwapTRX8bot', 6012778301, 'xf510', '天天', 1683088511, 0, 0, 0, 0, 0, 0, 0),
(62, 0, 1, 0, 'Tonvip_bot', 1405597912, 'vgrpc', 'blackhole', 1683170566, 0, 0, 0, 0, 0, 0, 0),
(63, 0, 1, 0, 'Tonvip_bot', 6012778301, 'xf510', '天天', 1683170863, 0, 0, 0, 0, 0, 0, 0),
(64, 0, 1, 0, 'Tonvip_bot', 5602956089, 'YC1798', '月初', 1683177495, 0, 0, 0, 0, 0, 0, 0),
(65, 1, 1, 0, 'SwapTRX8bot', 5489480329, 'LinRan8888', '杰森', 1683179768, 0, 0, 0, 0, 0, 0, 0),
(66, 1, 1, 0, 'SwapTRX8bot', 6130578235, 'Lin339', '帅气的林', 1683183249, 0, 0, 0, 0, 0, 0, 0),
(67, 1, 1, 0, 'SwapTRX8bot', 5298429576, 'dabao6699', '大宝', 1683259373, 0, 0, 0, 0, 0, 0, 0),
(68, 0, 1, 0, 'SwapTRX8bot', 1147496086, 'lw17688', '小高', 1683305597, 0, 0, 0, 0, 0, 0, 0),
(69, 0, 1, 0, 'SwapTRX8bot', 6262653640, 'tgfei8', '小飞科技【地址尾号：88888888】', 1683416500, 0, 0, 0, 0, 0, 0, 0),
(70, 0, 1, 0, 'SwapTRX8bot', 1849784142, 'OpenAIBal', '白', 1683439369, 0, 0, 0, 0, 0, 0, 0),
(71, 0, 1, 0, 'Tonvip_bot', 5191065740, 'PurchasePremium', 'Aknice', 1683445155, 0, 0, 0, 0, 0, 0, 0),
(72, 0, 1, 0, 'SwapTRX8bot', 1088857444, 'yuejian', '月見🌷认准TRC20尾号【9个9】', 1683455133, 0, 0, 0, 0, 0, 0, 0),
(73, 0, 1, 0, 'SwapTRX8bot', 2098023734, 'junhuofan', '将军', 1683865705, 0, 0, 0, 0, 0, 0, 0),
(74, 0, 1, 0, 'Tonvip_bot', 2098023734, 'junhuofan', '将军', 1683865802, 0, 0, 0, 0, 0, 0, 0),
(75, 0, 1, 0, 'jizhangadminbot', 5701696422, 'cdbcc22', '林king（im0.4无限）', 1683968908, 0, 0, 0, 0, 0, 0, 0),
(76, 0, 1, 0, 'SwapTRX8bot', 5934125146, 'shanheA8', '山河/开会员/TRX兑换/卖飞机号 🅥业务看简介', 1684066639, 0, 0, 0, 0, 0, 0, 0),
(77, 0, 1, 0, 'SwapTRX8bot', 5371814506, 'DD3080', '达达-出售波场能量', 1684303947, 0, 0, 0, 0, 0, 0, 0),
(78, 1, 1, 0, 'SwapTRX8bot', 5535621261, '未设置', '已销号', 1684304462, 0, 0, 0, 0, 0, 0, 0),
(79, 0, 1, 0, 'SwapTRX8bot', 6012972316, 'aabb55555', '|开户代投金牌谷歌上架', 1684402698, 0, 0, 0, 0, 0, 0, 0),
(80, 0, 1, 0, 'Tonvip_bot', 5934125146, 'shanheA8', '山河TRX兑换🅥开会员', 1684521041, 0, 0, 0, 0, 0, 0, 0),
(81, 0, 1, 0, 'Tonvip_bot', 5650195126, 'cutedeer233', '欧鹿', 1684564527, 0, 0, 0, 0, 0, 0, 0),
(82, 0, 1, 0, 'Tonvip_bot', 1147496086, 'lw17688', '小高', 1684749946, 0, 0, 0, 0, 0, 0, 0),
(83, 0, 1, 0, 'SwapTRX8bot', 2102459731, 'wallgooo', 'wall', 1684756558, 0, 0, 0, 0, 0, 0, 0),
(84, 0, 1, 0, 'SwapTRX8bot', 6253439303, 'wangzhetuandui', '安卓内核IOS定制批卡开端', 1684784142, 0, 0, 0, 0, 0, 0, 0),
(85, 0, 1, 0, 'SwapTRX8bot', 5500576635, 'Xixihaha55555', 'v vvhh', 1684841785, 0, 0, 0, 0, 0, 0, 0),
(86, 0, 1, 0, 'SwapTRX8bot', 5990575900, 'pcdd2828', 'a', 1684931647, 0, 0, 0, 0, 0, 0, 0),
(87, 0, 1, 0, 'Tonvip_bot', 6123643758, 'gtq168', 'Lily', 1685001839, 0, 0, 0, 0, 0, 0, 0),
(88, 0, 1, 0, 'SwapTRX8bot', 5806901665, 'qjl88888', '神人、', 1685096000, 0, 0, 0, 0, 0, 0, 0),
(89, 0, 1, 0, 'SwapTRX8bot', 1327672966, 'TAI888999', 'TAI太', 1685260205, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tb_banklist`
--

CREATE TABLE `tb_banklist` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL DEFAULT '0',
  `name` varchar(16) NOT NULL,
  `png` varchar(16) NOT NULL DEFAULT 'no.png',
  `lv` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tb_banklist`
--

INSERT INTO `tb_banklist` (`id`, `del`, `name`, `png`, `lv`) VALUES
(1, 1, '网商银行', 'no.png', 0),
(2, 0, '农业银行', 'no.png', 0),
(3, 1, '邮储银行', 'no.png', 0),
(4, 1, '建设银行', 'no.png', 0),
(5, 1, '工商银行', 'no.png', 0),
(6, 1, '交通银行', 'no.png', 0),
(7, 1, '招商银行', 'no.png', 0),
(8, 1, '光大银行', 'no.png', 0),
(9, 1, '中信银行', 'no.png', 0),
(10, 1, '浦发银行', 'no.png', 0),
(11, 1, '平安银行', 'no.png', 0),
(12, 1, '兴业银行', 'no.png', 0),
(13, 1, '民生银行', 'no.png', 0),
(14, 1, '中国银行', 'no.png', 0),
(15, 0, '四川农信', 'no.png', 0),
(16, 1, '柳州银行', 'no.png', 0),
(17, 1, '邢台银行', 'no.png', 0),
(18, 0, '河北省农村信用社', 'no.png', 0),
(19, 0, '黑龙江农村信用社', 'no.png', 0),
(20, 0, '吉林农村信用社', 'no.png', 0),
(21, 1, '黑龙江农村商业银行', 'no.png', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_channel`
--

CREATE TABLE `tb_bot_channel` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `plugin` varchar(16) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `pid` bigint(20) NOT NULL,
  `title` varchar(64) NOT NULL,
  `info` json NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_bot_channel`
--

INSERT INTO `tb_bot_channel` (`id`, `del`, `plugin`, `bot`, `pid`, `title`, `info`, `time`) VALUES
(1, 0, 'keepbot', 'AIjizhangbot', -1001962482814, 'pindao', '{\"id\": -1001962482814, \"type\": \"channel\", \"title\": \"pindao\", \"username\": \"dsadsa11234\"}', 1681746894),
(2, 0, 'keepbot', 'AIjizhangbot', -1001945731899, '测试消息频道', '{\"id\": -1001945731899, \"type\": \"channel\", \"title\": \"测试消息频道\", \"username\": \"ceshi111000\"}', 1683875049);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_comclass`
--

CREATE TABLE `tb_bot_comclass` (
  `id` int(11) NOT NULL,
  `plugin` varchar(16) NOT NULL,
  `del` int(1) NOT NULL,
  `chatType` varchar(16) NOT NULL DEFAULT 'private',
  `name` varchar(16) NOT NULL COMMENT '名称',
  `class` varchar(32) NOT NULL COMMENT '参数',
  `place` varchar(16) NOT NULL COMMENT '提示',
  `value` varchar(88) NOT NULL COMMENT '值',
  `yes` int(1) NOT NULL COMMENT '必须'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_bot_comclass`
--

INSERT INTO `tb_bot_comclass` (`id`, `plugin`, `del`, `chatType`, `name`, `class`, `place`, `value`, `yes`) VALUES
(1, 'keepbot', 0, 'all', '打开url', 'url', '上面示例输入后(自己修改)', 'https://', 1),
(2, 'keepbot', 0, 'all', '联系用户飞机', 'lianxiren', '飞机用户名不要加@', 'gd801', 1),
(3, 'keepbot', 0, 'all', '登录网址(需设定Domain)', 'login_url', '需与机器人Domain一致', '', 1),
(4, 'keepbot', 0, 'all', '添加机器人进群', 'group', '', '', 0),
(5, 'keepbot', 0, 'all', '按钮点击事件', 'callback_data', '按钮发送data(开发者)', '', 1),
(6, 'keepbot', 0, 'supergroup', '@机器人', 'switch_inline_query_current_chat', '@机器人时附加输入的文字', '', 1),
(7, 'keepbot', 0, 'all', '分享机器人', 'switch_inline_query', '', '', 0),
(8, 'keepbot', 0, 'private', '打开小程序', 'web_app', '小程序地址(https://)', '', 1),
(9, 'keepbot', 0, 'supergroup', '查看网页账单', 'excel', '不需要输入值', '', 0),
(10, 'SwapTRX8bot', 0, 'all', '打开url', 'url', '上面示例输入后(自己修改)', 'https://', 1),
(11, 'SwapTRX8bot', 0, 'all', '联系用户飞机', 'lianxiren', '飞机用户名不要加@', 'gd801', 1),
(12, 'SwapTRX8bot', 0, 'all', '登录网址(需设定Domain)', 'login_url', '需与机器人Domain一致', '', 1),
(13, 'SwapTRX8bot', 0, 'all', '添加机器人进群', 'group', '', '', 0),
(14, 'SwapTRX8bot', 0, 'all', '按钮点击事件', 'callback_data', '按钮发送data(开发者)', '', 1),
(15, 'SwapTRX8bot', 0, 'supergroup', '@机器人', 'switch_inline_query_current_chat', '@机器人时附加输入的文字', '', 1),
(16, 'SwapTRX8bot', 0, 'all', '分享机器人', 'switch_inline_query', '', '', 0),
(17, 'SwapTRX8bot', 0, 'private', '打开小程序', 'web_app', '小程序地址(https://)', '', 1),
(18, 'adminbot', 0, 'all', '打开url', 'url', '上面示例输入后(自己修改)', 'https://', 1),
(19, 'adminbot', 0, 'all', '联系用户飞机', 'lianxiren', '飞机用户名不要加@', 'gd801', 1),
(20, 'adminbot', 0, 'all', '登录网址(需设定Domain)', 'login_url', '需与机器人Domain一致', '', 1),
(21, 'adminbot', 0, 'all', '添加机器人进群', 'group', '', '', 0),
(22, 'adminbot', 0, 'all', '按钮点击事件', 'callback_data', '按钮发送data(开发者)', '', 1),
(23, 'adminbot', 0, 'supergroup', '@机器人', 'switch_inline_query_current_chat', '@机器人时附加输入的文字', '', 1),
(24, 'adminbot', 0, 'all', '分享机器人', 'switch_inline_query', '', '', 0),
(25, 'adminbot', 0, 'private', '打开小程序', 'web_app', '小程序地址(https://)', '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_commands`
--

CREATE TABLE `tb_bot_commands` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `parentId` int(1) NOT NULL COMMENT '层级',
  `command` varchar(10) NOT NULL,
  `description` varchar(64) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1菜单命令2其它事件',
  `mtype` varchar(16) NOT NULL DEFAULT 'sendMessage' COMMENT 'sendPhoto,sendMessage',
  `chatType` varchar(16) NOT NULL DEFAULT 'private' COMMENT '私人群组频道',
  `photo` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `istext` int(1) NOT NULL DEFAULT '1' COMMENT '允许text',
  `reply_markup` varchar(16) NOT NULL DEFAULT 'inline_keyboard'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='保留1 2';

--
-- 转存表中的数据 `tb_bot_commands`
--

INSERT INTO `tb_bot_commands` (`id`, `del`, `bot`, `parentId`, `command`, `description`, `type`, `mtype`, `chatType`, `photo`, `text`, `istext`, `reply_markup`) VALUES
(1, 0, 'jzjqe_bot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(2, 0, 'jzjqe_bot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(3, 0, 'jzjqe_bot', 1, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '开始(私人聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(4, 0, 'jzjqe_bot', 1, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人使用说明：</b>\n先将机器人拉入群，并设置为管理员\n\n<b>——命令—————或者——</b>\n添加操作人      设置操作人\n删除操作人\n显示操作人      查看操作人\n\n<b>费率汇率：</b> \n设置费率\n显示汇率          实时汇率,z0\n设置汇率  \n设置实时汇率\n\n<b>账单：</b>\n显示账单\n删除账单          清理账单\n\n<b>入款出款：</b>\n+100  (记账100)             \n-100    (扣账100)\n下发10  (下发10U)   \n\n\n<b>特别说明：</b>\n1、固定汇率和实时汇率不可同时设置，默认为最后一次的设置。\n2、清理账单&删除账单,会清理掉所有当前记录\n3、记账中途可以修改汇率或费率,修改汇率后下发不再显示人民币\n4、记账机器只能下发u,下发人民币时,设置汇率1 \n5、网页账单,支持导出excel\n6、所有命令可以不带空格也可以带空格\n\n<b>命令操作示例：</b>\n\n<b>设置操作人@gd801</b>  将用户设定为操作人\n<b>删除操作人@gd801</b>  删除对应操作人\n<b>设置费率2</b>  手续费2% = 入款每100扣2元\n<b>设置汇率7</b>  入款数÷汇率=应下发USDT，若同时设置了费率：(入款数-费率%)÷汇率=应下发USDT【举例：100 - 2%  ÷ 7 = 14】\n\n', 1, 'inline_keyboard'),
(5, 0, 'jzjqe_bot', 1, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '开始(群组聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(6, 1, 'jzjqe_bot', 2, '成为管理员', '自定义机器人被设定为管理员时的消息和按钮', 2, 'sendMessage', 'supergroup', '', '这是自定义的成为管理员信息', 1, 'inline_keyboard'),
(7, 0, 'jzjqe_bot', 2, '账单通用', '自定义(加款,下发,显示账单等)时的按钮(不支持自定义消息)', 2, 'sendMessage', 'supergroup', '', '', 1, 'inline_keyboard'),
(8, 0, 'uuuttjzbot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(9, 0, 'uuuttjzbot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(10, 0, 'uuuttjzbot', 8, 'help', '231', 1, 'sendMessage', 'supergroup', '', '3214344', 1, 'inline_keyboard'),
(11, 0, 'jizhangbot_bot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(12, 0, 'jizhangbot_bot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(13, 0, 'jizhangbot_bot', 11, 'start', '开始部署机器人', 1, 'sendMessage', 'private', '', '\n<b>云记账机器人托管服务</b>\n1.只需将您的机器人Token发送给我\n2.我就可以帮你定制一个属于你的记账机器人\n\n<b>如何申请自己的机器人?</b>\n1.给电报官方 @BotFather 发送：/newbot\n2.输入你想创建的机器人用户名如(举例)：jizhangbot_bot\n3.成功后就会获得一个TOken,点击即可复制\n4.将TOken发送给我,3秒即可帮你创造一个记账功能的机器人\n', 1, 'inline_keyboard'),
(14, 0, 'jizhangbot_bot', 11, 'help', '使用说明', 1, 'sendMessage', 'private', '', '<b>3秒自动搭建一个记账机器人</b>\n1.发送你的机器人Token\n2.云端自动为您创造全功能的记账机器人\n\n<b>基本功能介绍：</b>\n记账功能完善,100+客户使用中...\n支持网页账单丶excel导出下载\n支持计算器功能群内发1+1测试\n支持@机器人查询钱包余额功能\n支持@机器人查询哈希交易详情\n支持实时汇率,固定汇率,手续费率\n\n<b>管理员后台功能：</b>\n今日新增使用用户\n可以管理所有关注了机器人的用户,发消息等\n可以管理机器人加入的所有群发消息退群等\n可以自己增加机器人命令和回复内容\n可以自己定义所有消息下方按钮&功能\n更多功能自行登录后台体验..\n\n<b>我们的宗旨：\n打造全网最强云托管机器人</b>', 1, 'inline_keyboard'),
(15, 1, 'jizhangbot_bot', 11, 'starts', '2', 1, 'sendMessage', 'supergroup', '', '2', 1, 'inline_keyboard'),
(16, 0, 'AIjizhangbot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(17, 0, 'AIjizhangbot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(24, 0, 'AIjizhangbot', 16, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '开始(私人聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(25, 0, 'AIjizhangbot', 16, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人使用说明：</b>\n先将机器人拉入群，并设置为管理员\n\n<b>——命令—————或者——</b>\n添加操作人      设置操作人\n删除操作人\n显示操作人      查看操作人\n\n<b>费率汇率：</b> \n设置费率\n显示汇率          实时汇率,z0\n设置汇率  \n设置实时汇率\n\n<b>账单：</b>\n显示账单\n删除账单          清理账单\n\n<b>入款出款：</b>\n+100  (记账100)             \n-100    (扣账100)\n下发10  (下发10U)   \n\n\n<b>特别说明：</b>\n1、固定汇率和实时汇率不可同时设置，默认为最后一次的设置。\n2、清理账单&删除账单,会清理掉所有当前记录\n3、记账中途可以修改汇率或费率,修改汇率后下发不再显示人民币\n4、记账机器只能下发u,下发人民币时,设置汇率1 \n5、网页账单,支持导出excel\n6、所有命令可以不带空格也可以带空格\n\n<b>命令操作示例：</b>\n\n<b>设置操作人@gd801</b>  将用户设定为操作人\n<b>删除操作人@gd801</b>  删除对应操作人\n<b>设置费率2</b>  手续费2% = 入款每100扣2元\n<b>设置汇率7</b>  入款数÷汇率=应下发USDT，若同时设置了费率：(入款数-费率%)÷汇率=应下发USDT【举例：100 - 2%  ÷ 7 = 14】\n\n', 1, 'inline_keyboard'),
(26, 0, 'AIjizhangbot', 16, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '开始(群组聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(27, 0, 'jizhangbot_bot', 11, 'admin', '管理后台地址', 1, 'sendMessage', 'private', '', '1.首先<a href=\"https://user.jizhangbot.com/app/user/\">点击打开管理后台地址</a>\n2.进入页面后点击“telegram登录”\n3.按提示给你自己机器人发送4位数命令登录', 1, 'inline_keyboard'),
(28, 0, 'AIjizhangbot', 17, '机器人进群', '自定义机器人进群时的提示消息内容和按钮', 2, 'sendMessage', 'supergroup', '', '', 1, 'inline_keyboard'),
(29, 0, 'jizhangbot_bot', 11, 'start', '回复键盘', 3, 'sendMessage', 'private', '', '', 1, 'keyboard'),
(30, 0, 's1s1d1d_bot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(31, 0, 's1s1d1d_bot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(32, 0, 's1s1d1d_bot', 30, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '开始(私人聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(33, 0, 's1s1d1d_bot', 30, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人使用说明：</b>\n先将机器人拉入群，并设置为管理员\n\n<b>——命令—————或者——</b>\n添加操作人      设置操作人\n删除操作人\n显示操作人      查看操作人\n\n<b>费率汇率：</b> \n设置费率\n显示汇率          实时汇率,z0\n设置汇率  \n设置实时汇率\n\n<b>账单：</b>\n显示账单\n删除账单          清理账单\n\n<b>入款出款：</b>\n+100  (记账100)             \n-100    (扣账100)\n下发10  (下发10U)   \n\n\n<b>特别说明：</b>\n1、固定汇率和实时汇率不可同时设置，默认为最后一次的设置。\n2、清理账单&删除账单,会清理掉所有当前记录\n3、记账中途可以修改汇率或费率,修改汇率后下发不再显示人民币\n4、记账机器只能下发u,下发人民币时,设置汇率1 \n5、网页账单,支持导出excel\n6、所有命令可以不带空格也可以带空格\n\n<b>命令操作示例：</b>\n\n<b>设置操作人@gd801</b>  将用户设定为操作人\n<b>删除操作人@gd801</b>  删除对应操作人\n<b>设置费率2</b>  手续费2% = 入款每100扣2元\n<b>设置汇率7</b>  入款数÷汇率=应下发USDT，若同时设置了费率：(入款数-费率%)÷汇率=应下发USDT【举例：100 - 2%  ÷ 7 = 14】\n\n', 1, 'inline_keyboard'),
(34, 0, 's1s1d1d_bot', 30, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '开始(群组聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(35, 0, 'uuuttjzbot', 8, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '开始(私人聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(36, 0, 'uuuttjzbot', 8, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人使用说明：</b>\n先将机器人拉入群，并设置为管理员\n\n<b>——命令—————或者——</b>\n添加操作人      设置操作人\n删除操作人\n显示操作人      查看操作人\n\n<b>费率汇率：</b> \n设置费率\n显示汇率          实时汇率,z0\n设置汇率  \n设置实时汇率\n\n<b>账单：</b>\n显示账单\n删除账单          清理账单\n\n<b>入款出款：</b>\n+100  (记账100)             \n-100    (扣账100)\n下发10  (下发10U)   \n\n\n<b>特别说明：</b>\n1、固定汇率和实时汇率不可同时设置，默认为最后一次的设置。\n2、清理账单&删除账单,会清理掉所有当前记录\n3、记账中途可以修改汇率或费率,修改汇率后下发不再显示人民币\n4、记账机器只能下发u,下发人民币时,设置汇率1 \n5、网页账单,支持导出excel\n6、所有命令可以不带空格也可以带空格\n\n<b>命令操作示例：</b>\n\n<b>设置操作人@gd801</b>  将用户设定为操作人\n<b>删除操作人@gd801</b>  删除对应操作人\n<b>设置费率2</b>  手续费2% = 入款每100扣2元\n<b>设置汇率7</b>  入款数÷汇率=应下发USDT，若同时设置了费率：(入款数-费率%)÷汇率=应下发USDT【举例：100 - 2%  ÷ 7 = 14】\n\n', 1, 'inline_keyboard'),
(37, 0, 'uuuttjzbot', 8, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '开始(群组聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(38, 0, 'jizhangadminbot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(39, 0, 'jizhangadminbot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(40, 0, 'jizhangadminbot', 38, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '开始(私人聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(41, 0, 'jizhangadminbot', 38, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人说明：</b>\n请把你的机器人Token发送给我进行下一步操作\n\n', 1, 'inline_keyboard'),
(42, 0, 'jizhangadminbot', 38, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '开始(群组聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(43, 0, 'SwapTRX8bot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(44, 0, 'SwapTRX8bot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(45, 0, 'SwapTRX8bot', 43, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(46, 0, 'SwapTRX8bot', 43, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人使用说明：</b>\n暂未添加说明\n\n', 1, 'inline_keyboard'),
(47, 0, 'SwapTRX8bot', 43, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '', 1, 'inline_keyboard'),
(48, 0, 'AIjizhangbot', 17, '用户进群', '定义用户进群欢迎语', 2, 'sendMessage', 'supergroup', '', '欢迎你兄弟', 1, 'inline_keyboard'),
(49, 0, 'Tonvip_bot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(50, 0, 'Tonvip_bot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(51, 0, 'Tonvip_bot', 49, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '开始(私人聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(52, 0, 'Tonvip_bot', 49, 'help', '使用说明', 1, 'sendMessage', 'private', '', '没有添加说明\n请登录机器人管理面板自行添加修改', 1, 'inline_keyboard'),
(53, 0, 'Tonvip_bot', 49, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '开始(群组聊天) \n回复内容-请在后台自定义修改内容或增加按钮', 1, 'inline_keyboard'),
(54, 0, 'uuuhfjfjffbot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(55, 0, 'uuuhfjfjffbot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(56, 0, 'uuuhfjfjffbot', 54, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(57, 0, 'uuuhfjfjffbot', 54, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人使用说明：</b>\n暂未添加说明\n\n', 1, 'inline_keyboard'),
(58, 0, 'uuuhfjfjffbot', 54, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '', 1, 'inline_keyboard'),
(59, 0, 'UIUIUI111bot', 0, '菜单命令', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(60, 0, 'UIUIUI111bot', 0, '消息事件', '', 0, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(61, 0, 'UIUIUI111bot', 59, 'start', '开始(私人聊天)', 1, 'sendMessage', 'private', '', '', 1, 'inline_keyboard'),
(62, 0, 'UIUIUI111bot', 59, 'help', '使用说明', 1, 'sendMessage', 'private', '', '\n<b>机器人使用说明：</b>\n暂未添加说明\n\n', 1, 'inline_keyboard'),
(63, 0, 'UIUIUI111bot', 59, 'start', '开始(群组聊天)', 1, 'sendMessage', 'supergroup', '', '', 1, 'inline_keyboard');

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_comtype`
--

CREATE TABLE `tb_bot_comtype` (
  `id` int(11) NOT NULL,
  `plugin` varchar(16) NOT NULL COMMENT '所属应用',
  `chatType` varchar(16) NOT NULL DEFAULT 'supergroup',
  `command` varchar(16) NOT NULL COMMENT '事件名称',
  `tips` varchar(50) NOT NULL,
  `ismsg` int(1) NOT NULL DEFAULT '1' COMMENT '允许设定消息?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_bot_comtype`
--

INSERT INTO `tb_bot_comtype` (`id`, `plugin`, `chatType`, `command`, `tips`, `ismsg`) VALUES
(1, 'keepbot', 'supergroup', '机器人进群', '自定义机器人进群时的提示消息内容和按钮', 1),
(2, 'keepbot', 'supergroup', '成为管理员', '自定义机器人被设定为管理员时的消息和按钮', 1),
(3, 'keepbot', 'supergroup', '设置费率', '自定义设置费率成功时的消息和按钮', 1),
(4, 'keepbot', 'supergroup', '设置汇率', '自定义设置汇率成功时的消息和按钮', 1),
(5, 'keepbot', 'supergroup', '账单通用', '自定义(加款,下发,显示账单等)时的按钮(不支持自定义消息)', 0),
(6, 'keepbot', 'private', '推广成功 - 待开发', '推广别人使用机器人成功时的消息和按钮', 1),
(7, 'SwapTRX8bot', 'supergroup', '机器人进群', '自定义机器人进群时的提示消息内容和按钮', 1),
(8, 'SwapTRX8bot', 'supergroup', '成为管理员', '自定义机器人被设定为管理员时的消息和按钮', 1),
(9, 'keepbot', 'supergroup', '用户进群', '定义用户进群欢迎语', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_group`
--

CREATE TABLE `tb_bot_group` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `plugin` varchar(32) NOT NULL,
  `bot` varchar(16) NOT NULL,
  `groupid` bigint(20) NOT NULL,
  `admin` int(1) NOT NULL COMMENT '?管理员',
  `grouptitle` varchar(64) NOT NULL,
  `groupname` varchar(32) NOT NULL,
  `send` int(1) NOT NULL,
  `vip` int(1) NOT NULL,
  `welcome` text NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='机器人加群表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_group_user`
--

CREATE TABLE `tb_bot_group_user` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `qunid` bigint(20) NOT NULL COMMENT '群ID',
  `quninfo` json NOT NULL COMMENT '群信息',
  `userid` bigint(20) NOT NULL COMMENT '用户ID',
  `userinfo` json NOT NULL COMMENT '用户信息',
  `ufrom` json NOT NULL COMMENT '邀请人',
  `tfrom` json NOT NULL,
  `cretae_time` int(10) NOT NULL COMMENT '进入时间',
  `exit_time` int(10) NOT NULL COMMENT '退群时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群用户';

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_list`
--

CREATE TABLE `tb_bot_list` (
  `id` int(11) NOT NULL,
  `zt` int(1) NOT NULL DEFAULT '1',
  `plugin` varchar(32) NOT NULL,
  `API_BOT` varchar(32) NOT NULL COMMENT '机器用户名',
  `WEB_URL` varchar(64) NOT NULL COMMENT '部署域名',
  `WEB_IP` varchar(15) NOT NULL COMMENT '部署IP',
  `API_URL` varchar(64) NOT NULL DEFAULT 'https://api.telegram.org/bot' COMMENT '电报API',
  `API_TOKEN` varchar(64) NOT NULL COMMENT '机器人TOKEN',
  `Admin` bigint(11) NOT NULL DEFAULT '1418208536' COMMENT '管理员ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `outime` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_markup`
--

CREATE TABLE `tb_bot_markup` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `comId` int(8) NOT NULL COMMENT '事件ID\r\n',
  `chatType` varchar(16) NOT NULL COMMENT '群组 私人',
  `type` varchar(32) NOT NULL,
  `aid` int(11) NOT NULL,
  `sortId` int(11) NOT NULL,
  `text` varchar(32) NOT NULL,
  `class` varchar(32) NOT NULL,
  `url` varchar(64) NOT NULL,
  `web_app` varchar(64) NOT NULL,
  `login_url` varchar(64) NOT NULL,
  `callback_data` varchar(64) NOT NULL,
  `switch_inline_query` varchar(64) NOT NULL,
  `switch_inline_query_current_chat` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_bot_markup`
--

INSERT INTO `tb_bot_markup` (`id`, `del`, `bot`, `comId`, `chatType`, `type`, `aid`, `sortId`, `text`, `class`, `url`, `web_app`, `login_url`, `callback_data`, `switch_inline_query`, `switch_inline_query_current_chat`) VALUES
(1, 0, 'jzjqe_bot', 4, 'private', 'inline_keyboard', 1, 10, '自定义按钮', 'url', 'https://www.baidu.com', '', '', '', '', ''),
(2, 1, 'jzjqe_bot', 4, 'private', 'inline_keyboard', 1, 11, '新按钮', 'group', '', '', '', '', '', ''),
(3, 0, 'jzjqe_bot', 7, 'supergroup', 'inline_keyboard', 1, 10, '加我进群', 'group', '', '', '', '', '', ''),
(4, 0, 'jzjqe_bot', 7, 'supergroup', 'inline_keyboard', 1, 11, '网页账单', 'excel', '', '', '', '', '', ''),
(5, 0, 'jzjqe_bot', 5, 'supergroup', 'inline_keyboard', 1, 10, '推广链接', 'callback_data', '', '', '', '推广链接', '', ''),
(6, 0, 'uuuttjzbot', 10, 'supergroup', 'inline_keyboard', 1, 10, '新按钮', 'url', 'https://www.baidu.com', '', '', '', '', ''),
(7, 0, 'jizhangbot_bot', 13, 'private', 'inline_keyboard', 1, 10, '👮‍♀️联系客服', 'lianxiren', 'gd801', '', '', '', '', ''),
(8, 0, 'jizhangbot_bot', 13, 'private', 'inline_keyboard', 1, 11, '❇️开始试用', 'callback_data', '开始试用', 'https://www.baidu.com', '', '开始试用', '开始试用', ''),
(9, 0, 'jizhangbot_bot', 13, 'private', 'inline_keyboard', 2, 20, '👥用户使用交流群', 'url', 'https://t.me/jizhangbot_com', '', '', '', '', ''),
(10, 0, 'jizhangbot_bot', 13, 'private', 'inline_keyboard', 3, 30, '⏰功能更新通知频道', 'callback_data', '加入频道', '', '', '加入频道', '', ''),
(11, 0, 'jizhangbot_bot', 27, 'private', 'inline_keyboard', 1, 10, '🌐管理后台登录地址', 'url', 'https://user.jizhangbot.com/app/user', '', '', '', '', ''),
(12, 0, 'jizhangbot_bot', 29, 'private', 'keyboard', 1, 10, '✳️我的机器人', 'url', 'https://www.baidu.com', '', '', '', '', ''),
(13, 0, 'jizhangbot_bot', 29, 'private', 'keyboard', 1, 11, '➕新增机器人', 'group', '', '', '', '', '', ''),
(14, 0, 'AIjizhangbot', 48, 'supergroup', 'inline_keyboard', 1, 10, '新按钮', 'url', 'https://www.baidu.com', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_total_d`
--

CREATE TABLE `tb_bot_total_d` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `dated` int(11) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `botid` int(6) NOT NULL,
  `numu` int(11) NOT NULL,
  `usdt` bigint(20) NOT NULL,
  `numt` int(11) NOT NULL,
  `trx` bigint(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_bot_total_d`
--

INSERT INTO `tb_bot_total_d` (`id`, `del`, `dated`, `bot`, `botid`, `numu`, `usdt`, `numt`, `trx`, `time`) VALUES
(1, 0, 20230313, 'SwapTRX8bot', 0, 5, 7000000, 0, 0, 1678720485);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_total_h`
--

CREATE TABLE `tb_bot_total_h` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `dateh` int(11) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `botid` int(11) NOT NULL,
  `numu` int(11) NOT NULL,
  `usdt` bigint(20) NOT NULL,
  `numt` int(11) NOT NULL,
  `trx` bigint(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_bot_total_h`
--

INSERT INTO `tb_bot_total_h` (`id`, `del`, `dateh`, `bot`, `botid`, `numu`, `usdt`, `numt`, `trx`, `time`) VALUES
(1, 0, 2023031323, 'SwapTRX8bot', 0, 2, 4000000, 0, 0, 1678720485),
(2, 0, 2023031322, 'SwapTRX8bot', 0, 4, 4000000, 0, 0, 1678716198);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_total_tg`
--

CREATE TABLE `tb_bot_total_tg` (
  `id` int(11) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `tgid` bigint(20) NOT NULL,
  `date` int(8) NOT NULL,
  `time` int(11) NOT NULL,
  `tgnum` int(11) NOT NULL,
  `account` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='每日推广统计表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_total_trc20`
--

CREATE TABLE `tb_bot_total_trc20` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `tgid` bigint(20) NOT NULL,
  `disable` int(1) NOT NULL COMMENT '黑名单',
  `trc20` varchar(34) NOT NULL,
  `send` int(1) NOT NULL,
  `numu` int(11) NOT NULL,
  `usdt` bigint(20) NOT NULL,
  `numt` int(11) NOT NULL,
  `trx` bigint(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tb_bot_total_trc20`
--

INSERT INTO `tb_bot_total_trc20` (`id`, `del`, `bot`, `tgid`, `disable`, `trc20`, `send`, `numu`, `usdt`, `numt`, `trx`, `time`) VALUES
(1, 0, 'SwapTRX8bot', 0, 0, 'TJiEqLhLs567W3yu7h6XAHe6uBNe888888', 0, 6, 8000000, 0, 0, 1678716003),
(2, 0, 'SwapTRX8bot', 0, 0, 'TK9T9TLLRos6jdjKp9ELDvPEQ6Ng355555', 1, 0, 0, 0, 0, 1683021233),
(3, 0, 'SwapTRX8bot', 5650195126, 0, 'TGBu2pkBbaTc4qCoynfWeuGAHaKaW3S99b', 0, 0, 0, 0, 0, 1683021449);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_usdt_list`
--

CREATE TABLE `tb_bot_usdt_list` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `txid` varchar(64) NOT NULL,
  `ufrom` varchar(34) NOT NULL,
  `uto` varchar(34) NOT NULL,
  `value` bigint(20) NOT NULL,
  `time` int(10) NOT NULL,
  `oktxid` varchar(64) NOT NULL,
  `oktrx` decimal(10,2) NOT NULL,
  `okzt` int(1) NOT NULL,
  `msg` varchar(64) NOT NULL,
  `oktime` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='usdt收款列表';

--
-- 转存表中的数据 `tb_bot_usdt_list`
--

INSERT INTO `tb_bot_usdt_list` (`id`, `del`, `bot`, `txid`, `ufrom`, `uto`, `value`, `time`, `oktxid`, `oktrx`, `okzt`, `msg`, `oktime`) VALUES
(1, 0, 'SwapTRX8bot', '32123d6d3071c1d5896988b1cd78b2e876d6cd83524833faf1dbc7b97e4202d6', 'TJiEqLhLs567W3yu7h6XAHe6uBNe888888', 'TK9T9TLLRos6jdjKp9ELDvPEQ6Ng355555', 2000000, 1678720485, '', '27.96', 0, '闪兑终止,trx不足', 0),
(2, 0, 'SwapTRX8bot', 'e20dada58c8244c106dd33edd3e500c69e35cbd22b7b17f2c983527398153b4f', 'TJiEqLhLs567W3yu7h6XAHe6uBNe888888', 'TK9T9TLLRos6jdjKp9ELDvPEQ6Ng355555', 1000000, 1678716198, '', '13.98', 0, '闪兑终止,trx不足', 0),
(3, 0, 'SwapTRX8bot', 'a68520f12c00b5f5e2339d0405b1a7a51cba760c94baf63389d979039c60e2a3', 'TJiEqLhLs567W3yu7h6XAHe6uBNe888888', 'TK9T9TLLRos6jdjKp9ELDvPEQ6Ng355555', 1000000, 1678716003, '', '13.98', 0, '闪兑终止,trx不足', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_vip_paylog`
--

CREATE TABLE `tb_bot_vip_paylog` (
  `id` int(11) NOT NULL,
  `plugin` varchar(32) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `user` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `num` int(11) NOT NULL,
  `amout` bigint(20) NOT NULL,
  `time` int(11) NOT NULL,
  `oktime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_vip_setup`
--

CREATE TABLE `tb_bot_vip_setup` (
  `id` int(11) NOT NULL,
  `plugin` varchar(16) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `cookie` json DEFAULT NULL,
  `3` decimal(10,2) NOT NULL DEFAULT '15.00',
  `6` decimal(10,2) NOT NULL DEFAULT '30.00',
  `12` decimal(10,2) NOT NULL DEFAULT '55.00',
  `24` decimal(10,2) NOT NULL DEFAULT '100.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_vip_userlist`
--

CREATE TABLE `tb_bot_vip_userlist` (
  `id` int(11) NOT NULL,
  `user` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `rec` varchar(64) NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_bot_xufei_log`
--

CREATE TABLE `tb_bot_xufei_log` (
  `id` int(11) NOT NULL,
  `plugin` varchar(16) NOT NULL COMMENT '插件',
  `bot` varchar(32) NOT NULL COMMENT '续费机器人',
  `tgid` bigint(20) NOT NULL COMMENT '发起人TGID',
  `msgid` int(11) NOT NULL,
  `addr` varchar(34) NOT NULL COMMENT '收款地址',
  `payaddr` varchar(34) NOT NULL COMMENT '付款人地址',
  `txid` varchar(64) NOT NULL,
  `zt` int(1) NOT NULL COMMENT '状态',
  `tday` int(11) NOT NULL COMMENT '续费时长',
  `money` decimal(12,2) NOT NULL COMMENT '订单金额',
  `time` int(10) NOT NULL COMMENT '创建时间',
  `oktime` int(10) NOT NULL COMMENT '成功时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_douyin`
--

CREATE TABLE `tb_douyin` (
  `id` int(11) NOT NULL,
  `ddid` int(11) NOT NULL,
  `post` text NOT NULL,
  `data` varchar(50) NOT NULL,
  `sporder` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tb_err_order`
--

CREATE TABLE `tb_err_order` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL DEFAULT '0',
  `myid` int(11) NOT NULL DEFAULT '0',
  `upid` int(11) NOT NULL,
  `upname` varchar(16) NOT NULL,
  `errid` int(11) NOT NULL,
  `paycode` int(11) NOT NULL DEFAULT '0',
  `AA` varchar(64) DEFAULT NULL,
  `BB` varchar(64) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tb_ipsafe`
--

CREATE TABLE `tb_ipsafe` (
  `id` int(11) NOT NULL,
  `myid` int(11) NOT NULL COMMENT 'myid',
  `type` int(11) NOT NULL COMMENT '1登录2请求3代付',
  `ip` text NOT NULL COMMENT 'ip内容',
  `url` text NOT NULL COMMENT '白名单url'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `tb_ipsafe`
--

INSERT INTO `tb_ipsafe` (`id`, `myid`, `type`, `ip`, `url`) VALUES
(1, 100011, 1, '34.87.0.62#18.162.148.132#156.255.88.36#183.232.227.251#52.229.184.189#35.241.83.81#183.232.227.240', ''),
(2, 100013, 1, '223.119.193.154,103.243.180.140,203.168.226.130,203.168.226.2', ''),
(3, 100020, 1, '35.220.245.31#8.219.137.69', ''),
(4, 100020, 2, '34.81.26.140,175.100.205.123,103.148.147.1', ''),
(5, 100020, 3, '103.148.147.1#20.205.8.45#20.205.97.214', ''),
(6, 100035, 3, '16.163.37.5,#18.167.7.208,16.162.211.53,34.92.38.237,35.220.203.65,18.162.111.169,16.162.100.149,34.92.79.190,34.92.73.159,18.163.186.123,18.162.50.230,34.92.212.85,34.150.68.87\r\n\r\n', 'https://callback.jptxcallbackac.com/thirdwithdraw/withdraw/verify  https://callback.jptxcallbackab.com/thirdwithdraw/withdraw/verify  https://callback.jptxcallbackbc.com/thirdwithdraw/withdraw/verify  https://callback.jiantxcallbackac.com/thirdwithdraw/withdraw/verify https://callback.jiantxcallbackbc.com/thirdwithdraw/withdraw/verify https://callback.jiantxcallbackab.com/thirdwithdraw/withdraw/verify  https://callback.skgtxcallbackac.com/thirdwithdraw/withdraw/verify https://callback.skgtxcallbackbc.com/thirdwithdraw/withdraw/verify https://callback.skgtxcallbackab.com/thirdwithdraw/withdraw/verify  '),
(7, 100036, 3, '16.163.37.5,#18.167.7.208,16.162.211.53,34.92.38.237,35.220.203.65,18.162.111.169,16.162.100.149,34.92.79.190,34.92.73.159,18.163.186.123,18.162.50.230,34.92.212.85,34.150.68.87\r\n\r\n', ''),
(8, 100039, 1, '*.*.*.*', '');

-- --------------------------------------------------------

--
-- 表的结构 `tb_keep_log`
--

CREATE TABLE `tb_keep_log` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `qunid` bigint(20) NOT NULL,
  `did` int(11) NOT NULL COMMENT '日记录ID',
  `huilv` decimal(10,2) NOT NULL,
  `feilv` decimal(10,2) NOT NULL,
  `def` decimal(12,2) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `usdt` decimal(10,2) NOT NULL,
  `from` varchar(32) NOT NULL COMMENT '操作人',
  `reply` json NOT NULL,
  `time` int(11) NOT NULL,
  `date` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_keep_logc`
--

CREATE TABLE `tb_keep_logc` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `qunid` bigint(20) NOT NULL,
  `did` int(11) NOT NULL COMMENT '日记录ID',
  `type` int(1) NOT NULL COMMENT '出款类型0u 1人民币',
  `huilv` decimal(10,2) NOT NULL COMMENT '汇率',
  `usdt` decimal(12,2) NOT NULL COMMENT 'usdt数量',
  `money` decimal(12,2) NOT NULL COMMENT '人民币数量',
  `from` varchar(16) NOT NULL,
  `reply` json NOT NULL,
  `time` int(11) NOT NULL,
  `date` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_keep_setup`
--

CREATE TABLE `tb_keep_setup` (
  `id` int(11) NOT NULL,
  `bot` varchar(32) NOT NULL,
  `qunid` bigint(20) NOT NULL,
  `info` json DEFAULT NULL,
  `huilv` decimal(10,2) NOT NULL,
  `sshuilv` int(1) NOT NULL COMMENT '实时汇率',
  `dangwei` int(1) NOT NULL DEFAULT '1',
  `weitiao` decimal(4,2) NOT NULL COMMENT '微调',
  `feilv` decimal(10,2) NOT NULL,
  `rmb` int(1) NOT NULL DEFAULT '1' COMMENT '显示人民币',
  `decmoshi` int(1) NOT NULL COMMENT '出款模式0u 1人民币',
  `admin` varchar(255) NOT NULL COMMENT '管理员',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_keep_total`
--

CREATE TABLE `tb_keep_total` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `save` int(1) NOT NULL DEFAULT '0' COMMENT '储存',
  `qunid` bigint(20) NOT NULL COMMENT '群ID',
  `info` json DEFAULT NULL,
  `date` int(8) NOT NULL COMMENT '时间表达',
  `incnum` int(11) NOT NULL COMMENT '入款笔数',
  `defmoney` decimal(12,2) NOT NULL,
  `incmoney` decimal(12,2) NOT NULL COMMENT '入款money',
  `incusdt` decimal(12,2) NOT NULL COMMENT '入款usdt',
  `decnum` int(11) NOT NULL,
  `decmoney` decimal(12,2) NOT NULL,
  `decusdt` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_keep_totalz`
--

CREATE TABLE `tb_keep_totalz` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `qunid` bigint(20) NOT NULL COMMENT '群ID',
  `info` json DEFAULT NULL,
  `def` decimal(12,2) NOT NULL COMMENT '总金额(原始)',
  `num` int(11) NOT NULL COMMENT '总入款笔数',
  `money` decimal(12,2) NOT NULL COMMENT '应下发金额',
  `usdt` decimal(12,2) NOT NULL COMMENT '应下发usdt',
  `decnum` int(11) NOT NULL COMMENT '出款笔数',
  `decmoney` decimal(12,2) NOT NULL COMMENT '已下发money',
  `decusdt` decimal(12,2) NOT NULL COMMENT '已下发usdt',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '日期',
  `deltime` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_keep_user`
--

CREATE TABLE `tb_keep_user` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1正常2回复',
  `qunid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `username` varchar(32) NOT NULL,
  `num1` int(11) NOT NULL,
  `money1` decimal(14,2) NOT NULL,
  `num2` int(11) NOT NULL,
  `money2` decimal(14,2) NOT NULL,
  `date` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_log`
--

CREATE TABLE `tb_log` (
  `id` int(11) NOT NULL,
  `del` int(1) NOT NULL,
  `type` varchar(16) NOT NULL,
  `uid` int(6) NOT NULL,
  `username` varchar(16) NOT NULL,
  `money` decimal(10,2) NOT NULL COMMENT '金额',
  `remark` text NOT NULL,
  `y` int(6) NOT NULL COMMENT '操作人id',
  `z` varchar(16) NOT NULL COMMENT '操作手',
  `time` int(11) NOT NULL,
  `date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tb_message`
--

CREATE TABLE `tb_message` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL DEFAULT '0' COMMENT '显示',
  `myid` int(11) NOT NULL COMMENT '商户id',
  `type` varchar(8) NOT NULL COMMENT '消息类型',
  `title` varchar(32) NOT NULL COMMENT '标题',
  `class` varchar(16) NOT NULL COMMENT '颜色calss',
  `icon` varchar(32) NOT NULL COMMENT 'icon',
  `content` text COMMENT '私信内容',
  `avatar` varchar(100) DEFAULT NULL COMMENT '私信头像',
  `status` int(11) NOT NULL DEFAULT '2' COMMENT '待办状态',
  `description` varchar(32) DEFAULT NULL COMMENT '待办日期',
  `time` int(11) NOT NULL COMMENT '时间',
  `read` int(11) NOT NULL DEFAULT '0' COMMENT '已阅'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tb_money_log`
--

CREATE TABLE `tb_money_log` (
  `id` int(11) NOT NULL,
  `myid` int(11) NOT NULL COMMENT '商户id',
  `upid` int(11) NOT NULL COMMENT '码商id',
  `upname` varchar(16) NOT NULL COMMENT '码商账号',
  `sub` varchar(16) NOT NULL COMMENT '操作员',
  `type` int(11) NOT NULL COMMENT '类型',
  `source` varchar(66) NOT NULL,
  `remark` varchar(88) NOT NULL COMMENT '说明',
  `end` decimal(10,2) NOT NULL COMMENT '变动后',
  `del` int(11) NOT NULL COMMENT '删除',
  `go` decimal(10,2) NOT NULL COMMENT '变动前',
  `money` decimal(10,2) NOT NULL COMMENT '变动金额',
  `sxf` decimal(10,2) NOT NULL,
  `time` int(11) NOT NULL COMMENT '时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tb_money_log`
--

INSERT INTO `tb_money_log` (`id`, `myid`, `upid`, `upname`, `sub`, `type`, `source`, `remark`, `end`, `del`, `go`, `money`, `sxf`, `time`) VALUES
(1, 100016, 100001, '111222', '', 3, '1_农业银行(王思聪*4555)', '人工加款|无', '100.00', 0, '0.00', '100.00', '0.00', 1682338867),
(2, 100001, 100016, '111222', '', 3, '加款通道|1_农业银行(王思聪*4555)', '人工加款|无', '5500.00', 0, '5400.00', '100.00', '1.38', 1682338867);

-- --------------------------------------------------------

--
-- 表的结构 `tb_pay_lei`
--

CREATE TABLE `tb_pay_lei` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL COMMENT '名字',
  `expire` int(11) NOT NULL DEFAULT '60',
  `inte` int(11) NOT NULL COMMENT '订单整数金额',
  `only` int(11) NOT NULL DEFAULT '0' COMMENT '金额回调模式',
  `sxf` decimal(13,2) NOT NULL COMMENT '费率',
  `smoney` decimal(13,2) NOT NULL COMMENT '单笔最小金额',
  `mmoney` decimal(13,2) NOT NULL COMMENT '单笔最小金额',
  `lmoney` decimal(12,2) NOT NULL DEFAULT '100000.00' COMMENT '超额停止'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tb_pay_lei`
--

INSERT INTO `tb_pay_lei` (`id`, `del`, `name`, `expire`, `inte`, `only`, `sxf`, `smoney`, `mmoney`, `lmoney`) VALUES
(100, 0, '卡卡收付', 600, 0, 1, '0.40', '0.01', '200000.00', '2000000.00'),
(101, 0, '个码转账', 600, 0, 1, '0.40', '0.01', '100000.00', '1000000.00'),
(102, 0, '抖币充值', 200, 0, 0, '2.00', '1.00', '10000.00', '2000000.00');

-- --------------------------------------------------------

--
-- 表的结构 `tb_pay_log`
--

CREATE TABLE `tb_pay_log` (
  `id` int(11) NOT NULL COMMENT '标识ID',
  `del` int(11) NOT NULL DEFAULT '0',
  `upid` int(11) NOT NULL DEFAULT '0',
  `myid` int(11) NOT NULL,
  `zt` int(11) NOT NULL DEFAULT '0',
  `saoma` int(11) DEFAULT '0',
  `paycode` int(11) NOT NULL COMMENT '通道编号',
  `qdid` int(11) NOT NULL,
  `qdname` varchar(32) NOT NULL,
  `qdmsg` varchar(16) NOT NULL,
  `shopname` char(50) NOT NULL COMMENT '商品名称',
  `dingdan` varchar(38) NOT NULL COMMENT '订单号',
  `mqdd` char(64) NOT NULL COMMENT '免签订单',
  `gfdd` varchar(66) NOT NULL COMMENT '官方流水号',
  `token` varchar(64) NOT NULL COMMENT '标识',
  `money` decimal(10,2) NOT NULL COMMENT '订单金额',
  `bakmoney` decimal(12,2) NOT NULL COMMENT '递减金额',
  `okmoney` decimal(13,2) NOT NULL COMMENT '成功金额',
  `sxfmoney` decimal(13,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `time` int(11) NOT NULL COMMENT '日期time',
  `oktime` int(11) NOT NULL COMMENT '付款回调成功时间',
  `notifyzt` int(11) NOT NULL DEFAULT '0' COMMENT '回调商户状态',
  `notifymsg` varchar(188) NOT NULL,
  `payurl` varchar(100) NOT NULL COMMENT '支付链接',
  `remark` varchar(64) NOT NULL,
  `returnurl` varchar(100) NOT NULL COMMENT '同步跳转地址',
  `notifyurl` char(255) NOT NULL COMMENT '商户回调URL',
  `ip` varchar(16) NOT NULL COMMENT '付款人IP',
  `ip3` varchar(11) NOT NULL,
  `sheng` varchar(12) NOT NULL COMMENT '省',
  `shi` varchar(12) NOT NULL COMMENT '市区',
  `system` int(11) NOT NULL,
  `ua` varchar(255) NOT NULL COMMENT 'ua',
  `qr` int(11) NOT NULL COMMENT 'qrID',
  `code` text NOT NULL COMMENT '数据'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `tb_pay_qudao`
--

CREATE TABLE `tb_pay_qudao` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `del` int(11) NOT NULL DEFAULT '0',
  `upid` int(11) NOT NULL DEFAULT '0',
  `upname` varchar(16) NOT NULL,
  `sub` varchar(16) NOT NULL,
  `zt` int(11) DEFAULT '0',
  `myid` int(11) DEFAULT '0',
  `name` varchar(32) NOT NULL,
  `mid` decimal(10,0) NOT NULL,
  `lv` int(11) DEFAULT '0',
  `paycode` int(11) NOT NULL DEFAULT '0' COMMENT '支付编码',
  `msg1` varchar(60) NOT NULL,
  `payname` varchar(18) NOT NULL,
  `payaccount` varchar(30) NOT NULL,
  `qrshow` int(11) NOT NULL DEFAULT '0' COMMENT '显',
  `qrcode` varchar(255) NOT NULL COMMENT '数据',
  `cookie` varchar(255) NOT NULL,
  `payrate` decimal(4,2) NOT NULL DEFAULT '0.00' COMMENT '额外',
  `auto` int(11) NOT NULL DEFAULT '0',
  `data1` varchar(16) NOT NULL,
  `data2` text NOT NULL,
  `smoney` decimal(8,2) NOT NULL DEFAULT '0.01',
  `mmoney` decimal(12,2) NOT NULL DEFAULT '8000.00',
  `lmoney` decimal(12,2) NOT NULL DEFAULT '100000.00',
  `ddnumber` int(11) NOT NULL,
  `oknumber` int(11) NOT NULL,
  `okmoney` decimal(13,2) NOT NULL,
  `tmoney` decimal(10,2) NOT NULL COMMENT '总收',
  `decmoney` decimal(10,2) NOT NULL COMMENT '已提',
  `sxf` decimal(10,2) NOT NULL,
  `date` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `zmoney` decimal(10,2) NOT NULL COMMENT '总',
  `etc` decimal(10,2) NOT NULL COMMENT '下发中'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `tb_pay_qudao`
--

INSERT INTO `tb_pay_qudao` (`id`, `del`, `upid`, `upname`, `sub`, `zt`, `myid`, `name`, `mid`, `lv`, `paycode`, `msg1`, `payname`, `payaccount`, `qrshow`, `qrcode`, `cookie`, `payrate`, `auto`, `data1`, `data2`, `smoney`, `mmoney`, `lmoney`, `ddnumber`, `oknumber`, `okmoney`, `tmoney`, `decmoney`, `sxf`, `date`, `time`, `zmoney`, `etc`) VALUES
(1, 0, 100016, '111222', '', 1, 100001, '测试', '1682338851', 0, 100, '卡卡收付', '王思聪', '622454484444555', 0, '4555', '北京支行', '0.00', 0, '52E9C580', '农业银行', '0.01', '200000.00', '2000000.00', 1, 1, '100.00', '100.00', '0.00', '1.38', 20230424, 1682338851, '100.00', '0.00');

-- --------------------------------------------------------

--
-- 表的结构 `tb_timelog`
--

CREATE TABLE `tb_timelog` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `myid` int(11) NOT NULL,
  `YYYY` int(11) NOT NULL DEFAULT '0',
  `MM` int(11) NOT NULL DEFAULT '0',
  `DD` int(11) NOT NULL DEFAULT '0',
  `HH` int(11) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL DEFAULT '0',
  `oknum` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `okmoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sxf` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tb_timelog`
--

INSERT INTO `tb_timelog` (`id`, `del`, `myid`, `YYYY`, `MM`, `DD`, `HH`, `num`, `oknum`, `money`, `okmoney`, `sxf`) VALUES
(1, 0, 100001, 2023, 4, 24, 20, 1, 1, '100.00', '0.00', '1.38'),
(2, 0, 100016, 2023, 4, 24, 20, 1, 1, '100.00', '100.00', '1.38');

-- --------------------------------------------------------

--
-- 表的结构 `tb_tixianlog`
--

CREATE TABLE `tb_tixianlog` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `myid` int(11) NOT NULL COMMENT '商户id',
  `upid` int(11) NOT NULL COMMENT '卡商id',
  `sub` varchar(16) NOT NULL COMMENT '操作员',
  `qdtext` varchar(30) NOT NULL,
  `dingdan` varchar(32) NOT NULL COMMENT '订单号',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '笔',
  `zt` int(11) NOT NULL COMMENT '状态',
  `okzt` int(11) NOT NULL,
  `dfset` int(11) NOT NULL,
  `qdname` varchar(12) NOT NULL,
  `qdbank` varchar(18) NOT NULL,
  `qdcard` int(11) NOT NULL,
  `bankname` varchar(32) NOT NULL COMMENT '开户行',
  `bankaddr` varchar(20) NOT NULL,
  `bankuser` varchar(16) NOT NULL COMMENT '收款人',
  `bankcard` varchar(32) NOT NULL COMMENT '卡号',
  `amoney` decimal(10,2) NOT NULL COMMENT '原余额',
  `money` decimal(10,2) NOT NULL COMMENT '金额',
  `sxf` decimal(10,2) NOT NULL,
  `time` int(11) NOT NULL COMMENT '时间',
  `oktime` int(11) NOT NULL COMMENT '成功时间',
  `icon` varchar(16) NOT NULL COMMENT '银行图标',
  `imgurl` varchar(64) NOT NULL COMMENT '凭证图',
  `sms` text NOT NULL COMMENT '出款短信',
  `qdid` int(11) NOT NULL COMMENT '出款通道',
  `notifyurl` varchar(100) NOT NULL,
  `orid` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tb_today_log`
--

CREATE TABLE `tb_today_log` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL DEFAULT '0',
  `myid` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1',
  `oknum` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `okmoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sxf` decimal(10,2) NOT NULL,
  `moneydf` int(11) NOT NULL,
  `okmoneydf` int(11) NOT NULL,
  `numdf` int(11) NOT NULL,
  `oknumdf` int(11) NOT NULL,
  `time` int(11) NOT NULL COMMENT '时间戳',
  `md` int(11) NOT NULL DEFAULT '0' COMMENT '1为月'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tb_today_log`
--

INSERT INTO `tb_today_log` (`id`, `del`, `myid`, `date`, `num`, `oknum`, `money`, `okmoney`, `sxf`, `moneydf`, `okmoneydf`, `numdf`, `oknumdf`, `time`, `md`) VALUES
(1, 0, 100001, 20230424, 1, 1, '100.00', '100.00', '1.38', 0, 0, 0, 0, 1682338851, 0),
(2, 0, 100001, 202304, 1, 1, '100.00', '100.00', '1.38', 0, 0, 0, 0, 1682338851, 1),
(3, 0, 100016, 20230424, 1, 1, '100.00', '100.00', '1.38', 0, 0, 0, 0, 1682338867, 0),
(4, 0, 100016, 202304, 1, 1, '100.00', '100.00', '1.38', 0, 0, 0, 0, 1682338867, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tb_today_qd_log`
--

CREATE TABLE `tb_today_qd_log` (
  `id` int(11) NOT NULL,
  `del` int(11) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL COMMENT '日期',
  `myid` int(11) DEFAULT '0' COMMENT '商户ID',
  `qdid` int(11) DEFAULT '0' COMMENT '渠道ID',
  `paycode` int(11) NOT NULL COMMENT '支付编码',
  `codename` varchar(20) NOT NULL,
  `payaccount` varchar(20) DEFAULT '0' COMMENT '实际收款账号',
  `payname` varchar(20) DEFAULT NULL COMMENT '渠道收款人名',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '创建订单',
  `oknum` int(11) NOT NULL DEFAULT '0' COMMENT '成功订单',
  `money` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '创建总金额',
  `okmoney` decimal(10,2) DEFAULT '0.00' COMMENT '成功金额',
  `sxf` decimal(10,2) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `tb_today_qd_log`
--

INSERT INTO `tb_today_qd_log` (`id`, `del`, `date`, `myid`, `qdid`, `paycode`, `codename`, `payaccount`, `payname`, `num`, `oknum`, `money`, `okmoney`, `sxf`, `time`) VALUES
(1, 0, 20230424, 100001, 1, 100, '农业银行', '622454484444555', '王思聪', 1, 1, '100.00', '100.00', '1.38', 1682338867),
(2, 0, 20230424, 100016, 1, 100, '农业银行', '622454484444555', '王思聪', 1, 1, '100.00', '100.00', '1.38', 1682338867);

-- --------------------------------------------------------

--
-- 表的结构 `tb_trx_setup`
--

CREATE TABLE `tb_trx_setup` (
  `id` int(11) NOT NULL,
  `plugin` varchar(32) NOT NULL,
  `bot` varchar(16) NOT NULL,
  `PrivateKey` varchar(64) NOT NULL COMMENT '钱包秘钥',
  `addr` varchar(34) NOT NULL,
  `TRON_API_KEY` varchar(48) NOT NULL COMMENT '波场APIKEY',
  `Ttime` int(5) NOT NULL DEFAULT '600' COMMENT '监听时间阈值',
  `maxusdt` decimal(5,2) NOT NULL DEFAULT '100.00' COMMENT '最大兑换U',
  `type` int(1) NOT NULL DEFAULT '2' COMMENT '兑换模式',
  `Rate` int(2) NOT NULL COMMENT '抽成比例',
  `Price` decimal(5,2) NOT NULL COMMENT '固定价格',
  `Minusdt` decimal(5,2) NOT NULL COMMENT '最小兑换',
  `fanli` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转储表的索引
--

--
-- 表的索引 `sjb_list`
--
ALTER TABLE `sjb_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`);

--
-- 表的索引 `sjb_logmoney`
--
ALTER TABLE `sjb_logmoney`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- 表的索引 `sjb_mg`
--
ALTER TABLE `sjb_mg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`),
  ADD KEY `mty` (`mty`),
  ADD KEY `nm` (`nm`);

--
-- 表的索引 `sjb_mks`
--
ALTER TABLE `sjb_mks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mgid` (`mgid`),
  ADD KEY `mksid` (`mksid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `nm` (`nm`);

--
-- 表的索引 `sjb_op`
--
ALTER TABLE `sjb_op`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`),
  ADD KEY `gid` (`gid`),
  ADD KEY `na` (`na`),
  ADD KEY `nm` (`nm`);

--
-- 表的索引 `sjb_pay`
--
ALTER TABLE `sjb_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `username` (`username`),
  ADD KEY `z` (`z`);

--
-- 表的索引 `sjb_set`
--
ALTER TABLE `sjb_set`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `sjb_tongji`
--
ALTER TABLE `sjb_tongji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`),
  ADD KEY `opid` (`opid`),
  ADD KEY `date` (`date`),
  ADD KEY `mksid` (`mksid`);

--
-- 表的索引 `sjb_user`
--
ALTER TABLE `sjb_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- 表的索引 `sys_crontab`
--
ALTER TABLE `sys_crontab`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `title` (`title`) USING BTREE,
  ADD KEY `create_time` (`create_time`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE;

--
-- 表的索引 `sys_crontab_log`
--
ALTER TABLE `sys_crontab_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `create_time` (`create_time`) USING BTREE,
  ADD KEY `crontab_id` (`crontab_id`) USING BTREE;

--
-- 表的索引 `sys_menu`
--
ALTER TABLE `sys_menu`
  ADD PRIMARY KEY (`menuId`) USING BTREE,
  ADD KEY `tenant_id` (`tenantId`) USING BTREE;

--
-- 表的索引 `sys_role`
--
ALTER TABLE `sys_role`
  ADD PRIMARY KEY (`roleId`) USING BTREE,
  ADD KEY `tenant_id` (`tenantId`) USING BTREE;

--
-- 表的索引 `sys_role_menu`
--
ALTER TABLE `sys_role_menu`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `sys_tenantId`
--
ALTER TABLE `sys_tenantId`
  ADD PRIMARY KEY (`tenantId`);

--
-- 表的索引 `sys_theme`
--
ALTER TABLE `sys_theme`
  ADD PRIMARY KEY (`userId`);

--
-- 表的索引 `sys_user_googel`
--
ALTER TABLE `sys_user_googel`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `tb_account`
--
ALTER TABLE `tb_account`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `tgid` (`tgid`);

--
-- 表的索引 `tb_account_tg`
--
ALTER TABLE `tb_account_tg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `tgid` (`tgid`),
  ADD KEY `up` (`up`);

--
-- 表的索引 `tb_banklist`
--
ALTER TABLE `tb_banklist`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `tb_bot_channel`
--
ALTER TABLE `tb_bot_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `time` (`time`),
  ADD KEY `pid` (`pid`),
  ADD KEY `title` (`title`);

--
-- 表的索引 `tb_bot_comclass`
--
ALTER TABLE `tb_bot_comclass`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin` (`plugin`);

--
-- 表的索引 `tb_bot_commands`
--
ALTER TABLE `tb_bot_commands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `del` (`del`),
  ADD KEY `command` (`command`),
  ADD KEY `type` (`type`),
  ADD KEY `chatType` (`chatType`);

--
-- 表的索引 `tb_bot_comtype`
--
ALTER TABLE `tb_bot_comtype`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plugin` (`plugin`);

--
-- 表的索引 `tb_bot_group`
--
ALTER TABLE `tb_bot_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `groupid` (`groupid`),
  ADD KEY `send` (`send`),
  ADD KEY `plugin` (`plugin`);

--
-- 表的索引 `tb_bot_group_user`
--
ALTER TABLE `tb_bot_group_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qunid` (`qunid`),
  ADD KEY `userid` (`userid`);

--
-- 表的索引 `tb_bot_list`
--
ALTER TABLE `tb_bot_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zt` (`zt`),
  ADD KEY `API_BOT` (`API_BOT`),
  ADD KEY `plugin` (`plugin`);

--
-- 表的索引 `tb_bot_markup`
--
ALTER TABLE `tb_bot_markup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chatType` (`chatType`),
  ADD KEY `aid` (`aid`),
  ADD KEY `comId` (`comId`),
  ADD KEY `bot` (`bot`);

--
-- 表的索引 `tb_bot_total_d`
--
ALTER TABLE `tb_bot_total_d`
  ADD PRIMARY KEY (`id`),
  ADD KEY `del` (`del`),
  ADD KEY `dated` (`dated`),
  ADD KEY `bot` (`bot`),
  ADD KEY `botid` (`botid`);

--
-- 表的索引 `tb_bot_total_h`
--
ALTER TABLE `tb_bot_total_h`
  ADD PRIMARY KEY (`id`),
  ADD KEY `del` (`del`),
  ADD KEY `dateh` (`dateh`),
  ADD KEY `bot` (`bot`),
  ADD KEY `botid` (`botid`);

--
-- 表的索引 `tb_bot_total_tg`
--
ALTER TABLE `tb_bot_total_tg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `date` (`date`),
  ADD KEY `tgid` (`tgid`);

--
-- 表的索引 `tb_bot_total_trc20`
--
ALTER TABLE `tb_bot_total_trc20`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trc20` (`trc20`),
  ADD KEY `del` (`del`),
  ADD KEY `tgid` (`tgid`);

--
-- 表的索引 `tb_bot_usdt_list`
--
ALTER TABLE `tb_bot_usdt_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `txid_2` (`txid`),
  ADD KEY `bot` (`bot`),
  ADD KEY `txid` (`txid`),
  ADD KEY `zt` (`okzt`),
  ADD KEY `oktxid` (`oktxid`),
  ADD KEY `ufrom` (`ufrom`);

--
-- 表的索引 `tb_bot_vip_paylog`
--
ALTER TABLE `tb_bot_vip_paylog`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `tb_bot_vip_setup`
--
ALTER TABLE `tb_bot_vip_setup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bot` (`bot`);

--
-- 表的索引 `tb_bot_vip_userlist`
--
ALTER TABLE `tb_bot_vip_userlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- 表的索引 `tb_bot_xufei_log`
--
ALTER TABLE `tb_bot_xufei_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `tgid` (`tgid`),
  ADD KEY `zt` (`zt`),
  ADD KEY `plugin` (`plugin`),
  ADD KEY `txid` (`txid`);

--
-- 表的索引 `tb_douyin`
--
ALTER TABLE `tb_douyin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `tb_err_order`
--
ALTER TABLE `tb_err_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `tb_ipsafe`
--
ALTER TABLE `tb_ipsafe`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `myid` (`myid`);

--
-- 表的索引 `tb_keep_log`
--
ALTER TABLE `tb_keep_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qunid` (`qunid`),
  ADD KEY `time` (`time`),
  ADD KEY `del` (`del`),
  ADD KEY `did` (`did`);

--
-- 表的索引 `tb_keep_logc`
--
ALTER TABLE `tb_keep_logc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qunid` (`qunid`),
  ADD KEY `time` (`time`),
  ADD KEY `del` (`del`),
  ADD KEY `did` (`did`);

--
-- 表的索引 `tb_keep_setup`
--
ALTER TABLE `tb_keep_setup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `qunid` (`qunid`);

--
-- 表的索引 `tb_keep_total`
--
ALTER TABLE `tb_keep_total`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qunid` (`qunid`),
  ADD KEY `date` (`date`),
  ADD KEY `del` (`del`);

--
-- 表的索引 `tb_keep_totalz`
--
ALTER TABLE `tb_keep_totalz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qunid` (`qunid`),
  ADD KEY `del` (`del`);

--
-- 表的索引 `tb_keep_user`
--
ALTER TABLE `tb_keep_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date` (`date`);

--
-- 表的索引 `tb_log`
--
ALTER TABLE `tb_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `type` (`type`),
  ADD KEY `y` (`y`);

--
-- 表的索引 `tb_message`
--
ALTER TABLE `tb_message`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `myid` (`myid`),
  ADD KEY `type` (`type`),
  ADD KEY `read` (`read`),
  ADD KEY `show` (`del`);

--
-- 表的索引 `tb_money_log`
--
ALTER TABLE `tb_money_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `del` (`del`),
  ADD KEY `myid` (`myid`),
  ADD KEY `upid` (`upid`),
  ADD KEY `upname` (`upname`),
  ADD KEY `type` (`type`),
  ADD KEY `time` (`time`);

--
-- 表的索引 `tb_pay_lei`
--
ALTER TABLE `tb_pay_lei`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `tb_pay_log`
--
ALTER TABLE `tb_pay_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `dingdan` (`dingdan`) USING BTREE,
  ADD KEY `gfdd` (`gfdd`),
  ADD KEY `time` (`time`),
  ADD KEY `zt` (`zt`),
  ADD KEY `qdid` (`qdid`),
  ADD KEY `token` (`token`),
  ADD KEY `mqdd` (`mqdd`),
  ADD KEY `myid` (`myid`),
  ADD KEY `upid` (`upid`);

--
-- 表的索引 `tb_pay_qudao`
--
ALTER TABLE `tb_pay_qudao`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `data1` (`data1`),
  ADD KEY `myid` (`myid`),
  ADD KEY `mid` (`mid`),
  ADD KEY `lei` (`paycode`),
  ADD KEY `auto` (`auto`),
  ADD KEY `zt` (`zt`),
  ADD KEY `del` (`del`),
  ADD KEY `upid` (`upid`);

--
-- 表的索引 `tb_timelog`
--
ALTER TABLE `tb_timelog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `myid` (`myid`),
  ADD KEY `HH` (`HH`),
  ADD KEY `YYYY` (`YYYY`),
  ADD KEY `MM` (`MM`),
  ADD KEY `DD` (`DD`),
  ADD KEY `YYYY_2` (`YYYY`,`MM`,`DD`,`HH`);

--
-- 表的索引 `tb_tixianlog`
--
ALTER TABLE `tb_tixianlog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dingdan` (`dingdan`),
  ADD KEY `myid` (`myid`),
  ADD KEY `upid` (`upid`),
  ADD KEY `zt` (`zt`);

--
-- 表的索引 `tb_today_log`
--
ALTER TABLE `tb_today_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `myid` (`myid`),
  ADD KEY `date` (`date`);

--
-- 表的索引 `tb_today_qd_log`
--
ALTER TABLE `tb_today_qd_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `date` (`date`);

--
-- 表的索引 `tb_trx_setup`
--
ALTER TABLE `tb_trx_setup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bot` (`bot`),
  ADD KEY `plugin` (`plugin`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `sjb_list`
--
ALTER TABLE `sjb_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `sjb_logmoney`
--
ALTER TABLE `sjb_logmoney`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `sjb_mg`
--
ALTER TABLE `sjb_mg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `sjb_mks`
--
ALTER TABLE `sjb_mks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `sjb_op`
--
ALTER TABLE `sjb_op`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `sjb_pay`
--
ALTER TABLE `sjb_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `sjb_set`
--
ALTER TABLE `sjb_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `sjb_tongji`
--
ALTER TABLE `sjb_tongji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `sjb_user`
--
ALTER TABLE `sjb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `sys_crontab`
--
ALTER TABLE `sys_crontab`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `sys_crontab_log`
--
ALTER TABLE `sys_crontab_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `sys_menu`
--
ALTER TABLE `sys_menu`
  MODIFY `menuId` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单id', AUTO_INCREMENT=147;

--
-- 使用表AUTO_INCREMENT `sys_role`
--
ALTER TABLE `sys_role`
  MODIFY `roleId` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id', AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `sys_role_menu`
--
ALTER TABLE `sys_role_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 使用表AUTO_INCREMENT `sys_tenantId`
--
ALTER TABLE `sys_tenantId`
  MODIFY `tenantId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `sys_user_googel`
--
ALTER TABLE `sys_user_googel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `tb_account`
--
ALTER TABLE `tb_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商户', AUTO_INCREMENT=100052;

--
-- 使用表AUTO_INCREMENT `tb_account_tg`
--
ALTER TABLE `tb_account_tg`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- 使用表AUTO_INCREMENT `tb_banklist`
--
ALTER TABLE `tb_banklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用表AUTO_INCREMENT `tb_bot_channel`
--
ALTER TABLE `tb_bot_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `tb_bot_comclass`
--
ALTER TABLE `tb_bot_comclass`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `tb_bot_commands`
--
ALTER TABLE `tb_bot_commands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- 使用表AUTO_INCREMENT `tb_bot_comtype`
--
ALTER TABLE `tb_bot_comtype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `tb_bot_group`
--
ALTER TABLE `tb_bot_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_bot_group_user`
--
ALTER TABLE `tb_bot_group_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_bot_list`
--
ALTER TABLE `tb_bot_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_bot_markup`
--
ALTER TABLE `tb_bot_markup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `tb_bot_total_d`
--
ALTER TABLE `tb_bot_total_d`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `tb_bot_total_h`
--
ALTER TABLE `tb_bot_total_h`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `tb_bot_total_tg`
--
ALTER TABLE `tb_bot_total_tg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_bot_total_trc20`
--
ALTER TABLE `tb_bot_total_trc20`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `tb_bot_usdt_list`
--
ALTER TABLE `tb_bot_usdt_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `tb_bot_vip_paylog`
--
ALTER TABLE `tb_bot_vip_paylog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_bot_vip_setup`
--
ALTER TABLE `tb_bot_vip_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_bot_vip_userlist`
--
ALTER TABLE `tb_bot_vip_userlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_bot_xufei_log`
--
ALTER TABLE `tb_bot_xufei_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_douyin`
--
ALTER TABLE `tb_douyin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_err_order`
--
ALTER TABLE `tb_err_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_ipsafe`
--
ALTER TABLE `tb_ipsafe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `tb_keep_log`
--
ALTER TABLE `tb_keep_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_keep_logc`
--
ALTER TABLE `tb_keep_logc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_keep_setup`
--
ALTER TABLE `tb_keep_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_keep_total`
--
ALTER TABLE `tb_keep_total`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_keep_totalz`
--
ALTER TABLE `tb_keep_totalz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_keep_user`
--
ALTER TABLE `tb_keep_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_log`
--
ALTER TABLE `tb_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_message`
--
ALTER TABLE `tb_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_money_log`
--
ALTER TABLE `tb_money_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `tb_pay_lei`
--
ALTER TABLE `tb_pay_lei`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- 使用表AUTO_INCREMENT `tb_pay_log`
--
ALTER TABLE `tb_pay_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '标识ID';

--
-- 使用表AUTO_INCREMENT `tb_pay_qudao`
--
ALTER TABLE `tb_pay_qudao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `tb_timelog`
--
ALTER TABLE `tb_timelog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `tb_tixianlog`
--
ALTER TABLE `tb_tixianlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `tb_today_log`
--
ALTER TABLE `tb_today_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `tb_today_qd_log`
--
ALTER TABLE `tb_today_qd_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `tb_trx_setup`
--
ALTER TABLE `tb_trx_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
