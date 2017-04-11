<?php
//修改MOVI作品
require("../config/config.php");
require ($cfg_inc_common_path);
$Tid=$_GET['Tid'];
$Tid=intval($Tid);
$delSql="update db_manager set status=-1 where Tid=$Tid";
mysql_query($delSql)or die("error548");
mysql_close();
$inc_title="管理员管理";
$inc_tieValue="管理员删除成功!";
$inc_urlPath=$web_path."/member/man_member.php?Tid=$Tid";
include($cfg_admin_php_result_path);
echo $resultBuffer;
exit;
?>