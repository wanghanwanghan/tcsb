<?php
if(MFLAG!=1)
{
	echo "非法操作01";
	exit;
}
//服务账户
$dbServerHost='localhost';
//用户名
$dbServerName='shebao';
//用户密码
$dbPassword='zbxl123';
//数据库名
$dbName='shebao';
//连接服务器
mysql_connect($dbServerHost,$dbServerName,$dbPassword)or die("mysql_error01".mysql_error());
mysql_select_db($dbName)or die("mysql_select_db_error02".mysql_error());
?>
