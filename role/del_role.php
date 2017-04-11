<?php
//修改MOVI作品
require("../config/config.php");
require ($cfg_inc_common_path);
$rid=$_GET['id'];
$rid=intval($rid);
if($rid == ROLE_SUPER) {
	$rdstation="<script language='javascript'>alert('超级管理员不能删除');location='$web_path/role/man_role.php' </script>";
	echo $rdstation;
	exit;
}
$delSql="update db_roles set status=-1 where id=$rid";
mysql_query($delSql)or die("error548");
mysql_close();
$inc_title="角色管理";
$inc_tieValue="角色删除成功!";
$inc_urlPath=$web_path."/role/man_role.php";
include($cfg_admin_php_result_path);
echo $resultBuffer;
exit;
?>
