<?php
if(MFLAG!=1)
{
	echo "非法操作01";
	exit;
}
$dbServerHost='localhost';
$dbServerName='root';
$dbPassword='linyanyang';
$dbName='tcjk';
//连接服务器
mysql_connect($dbServerHost,$dbServerName,$dbPassword)or die("mysql_error01".mysql_error());
mysql_select_db($dbName)or die("mysql_select_db_error02".mysql_error());
?>