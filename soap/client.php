<?php
ini_set("soap.wsdl_cache_enabled", "0");
$client=new SoapClient("soap.wsdl");
$username="lvye";
$password="777777";
$email="lvye@qq.com";
$result=$client->register("$username","$password","$email");
if($result) {
	echo "注册成功！用户名为：<font color='red'>{$username}</font>,密码为：<font color='red'>{$password}</font>,邮箱为：<font color='red'>{$email}</font>";	
} else echo "注册失败！";
?>