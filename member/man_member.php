<?php
/*
职员列表
*/
require("../config/config.php");
require ($cfg_inc_common_path);

$buffer=implode("",file($tpl_man_admin_member));

$sql="select Tid,userName from db_manager where status>0";
$whilebuffer=Template("$tpl_man_admin_member","<!--begin-->","<!--end-->");
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleBuffer="";
while(list($Tid,$userName)=mysql_fetch_row($sqlBuffer))
{
	$temp=$whilebuffer;
	$temp=str_replace("{ID}",$Tid,$temp);
	$temp=str_replace("{userName}",$userName,$temp);
	$totleBuffer.=$temp;
}
$buffer=str_replace($whilebuffer,$totleBuffer,$buffer);


mysql_close();
include($cfg_inc_replace_path);
$buffer=str_replace("{postName}",$_SERVER[PHP_SELF],$buffer);
$buffer=str_replace("{sStateStr}",$employStr,$buffer);
$buffer=str_replace("{pageStr}",$pagenav,$buffer);
$buffer=str_replace("{posiStr}","角色管理 >> 管理员管理",$buffer);
echo $buffer;
?>