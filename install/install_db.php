<?php
require("../config/config.php");
require($mysql_path);

//行政机关
$sql="CREATE TABLE IF NOT EXISTS `db_office` (
  `Tid` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,#机关名称
  `ftid` int(32) DEFAULT NULL,#父ID
  `fstr` varchar(100) DEFAULT NULL,#父类级别
  `level` int(32) DEFAULT NULL,#父类级别数
  `orid` int(32) DEFAULT NULL,#父ID
  PRIMARY KEY (`Tid`)
);";
$delSql="drop table IF EXISTS `db_office`";
mysql_query($delSql)or die("error488");
mysql_query($sql)or die("创建失败2".mysql_error());

//声纹登记表ID
$sql="CREATE TABLE IF NOT EXISTS `db_ivr` (
  `Tid` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(32) DEFAULT NULL,#机关ID
  `flag` int(32) DEFAULT NULL,#0:已结束运行 1：可以运行
  `name` varchar(100) DEFAULT NULL,#名称
  `mobi` varchar(100) DEFAULT NULL,#手机
  `bjz_date` datetime DEFAULT NULL,#矫正开始日
  `ejz_date` datetime DEFAULT NULL,#矫正结束日
  `jz_type` int(32) DEFAULT NULL,#矫正状态 数组：0:正常1:解矫2:矫正中止
  `jg_grade` int(32) DEFAULT NULL,#监管级别 数组：0：宽管1：普管2：严管
  `regstate` int(32) DEFAULT NULL,#数组：0：未注册1：注册
  `up_bdate` int(4) DEFAULT NULL,#上半部分：验证开始时间段
  `up_edate` int(4) DEFAULT NULL,#上半部分：验证结束时间段
  `dowm_bdate` int(4) DEFAULT NULL,#下半部分：验证开始时间段
  `dowm_edate` int(4) DEFAULT NULL,#下半部分：验证结束时间段
  `ydate` datetime DEFAULT NULL,#系统最后验证时间
  PRIMARY KEY (`Tid`)
);";
$delSql="drop table IF EXISTS `db_ivr`";
mysql_query($delSql)or die("error488");
mysql_query($sql)or die("创建失败2".mysql_error());

//声纹语音验证配置
$sql="CREATE TABLE IF NOT EXISTS `db_ivr_log` (
  `Tid` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `flag` int(32) DEFAULT NULL,# 0：登记语音 1：声纹认证
  `uid` int(32) DEFAULT NULL,#声纹登记表ID
  `type` int(32) DEFAULT NULL,#0:人工拨打  1：自动拨打
  `yzdate` datetime ,#验证开始时间
  `up_yzdate` datetime ,#最后一次验证时间
  `server_state` int(32) DEFAULT NULL,#服务端返回状态 
  `state` int(32) DEFAULT NULL,#声纹结果状态
  `sw_state` int(32) DEFAULT NULL,#声纹结果类型
  `sw_url` text DEFAULT NULL,#声纹语音URL地址
  `end_yzdate` datetime ,#最后结束时间
  `sh_state` int(32) DEFAULT NULL,#审核状态
  `shjg` text DEFAULT NULL,#审核结果
  PRIMARY KEY (`Tid`)
);";
$delSql="drop table IF EXISTS `db_ivr_log`";
mysql_query($delSql)or die("error488");
mysql_query($sql)or die("创建失败2".mysql_error());

//声纹语音列表
$sql="CREATE TABLE IF NOT EXISTS `db_ivr_rs` (
  `Tid` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(32) DEFAULT NULL,#声纹登记表ID
  `oid` int(32) DEFAULT NULL,#声纹验证表ID
  `sw_url` text DEFAULT NULL,#声纹语音URL地址
  `sw_tell` text DEFAULT NULL,#声纹语音URL地址
  `cdate` datetime ,#添加时间
  PRIMARY KEY (`Tid`)
);";
$delSql="drop table IF EXISTS `db_ivr_rs`";
mysql_query($delSql)or die("error488");
mysql_query($sql)or die("创建失败2".mysql_error());


//后台管理员用户表
$sql="CREATE TABLE IF NOT EXISTS `db_manager` (
  `Tid` int(32) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(32) DEFAULT NULL,#角色
  `userName` varchar(50) DEFAULT NULL,#用户名
  `pwd` varchar(50) DEFAULT NULL,#密码
  PRIMARY KEY (`Tid`)
);";
$delSql="drop table IF EXISTS `db_manager`";
mysql_query($delSql)or die("error488");
mysql_query($sql)or die("创建失败2".mysql_error());
$userName="movi520";
$pwd=md5("movi520");

$sql="insert into db_manager(type,userName,pwd)values('0','$userName','$pwd')";
mysql_query($sql)or die("error548");

echo "数据库初始化成功！";
?>