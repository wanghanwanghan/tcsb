<?php
/*
ְԱ༭
*/
require("../config/config.php");
require ($cfg_inc_common_path);
require_once (dirname(__FILE__) . "/inc_member.php");

$Tid=$_GET['Tid'];
$Tid=intval($Tid);
if($_POST['edit'])
{
    $role_id = trimHtml($_POST['role_id']);
	$password=trimHtml($_POST['password']);
    $areaStr = implode(",", $_POST['chkArea']);
    $uSql = "update db_manager set ";
    $upArr = [];
    if($password) {
        if(!validPasswd($password)) {
            echo "<script>alert('密码太简单，至少需要8位以上，包含数字，字母，特殊符号其中的至少两种！');location='edit_member.php?Tid=$Tid'</script>";
            exit;
        }
        $password=encodePasswd($password);
        $upArr[] = "pwd='$password'";
    }
    $upArr[] = "role_id='$role_id'";
    $upArr[] = "areas='$areaStr'";
    $uSql = $uSql . implode(",", $upArr) . " where Tid='$Tid'";
	mysql_query($uSql)or die("更新失败");
	mysql_close();
	$inc_title="管理员编辑";
	$inc_tieValue="管理员编辑成功!";
	$inc_urlPath=$web_path."/member/man_member.php";
	include($cfg_admin_php_result_path);
	echo $resultBuffer;
	exit;

}


$sql="select userName,pwd,role_id,areas from db_manager where Tid='$Tid'";
$manager = mysql_get_row($sql);
if(empty($manager)) {
    echo "<script>alert('账户不存在！');location='man_member.php'</script>";
    exit;
}

$roleList = mysql_get_list("select id, name from db_roles WHERE status>0");
$roleListOptionStr = getOptionListStr($roleList, $manager['role_id'], "id", "name");

$areaListr = getAreaTreeStr($manager['areas']);

$buffer=implode("",file($tpl_edit_admin_member));
$buffer=str_replace("{userId}",$Tid,$buffer);
$buffer=str_replace("{userName}",$manager['userName'],$buffer);
$buffer=str_replace("{roleListOptionStr}",$roleListOptionStr,$buffer);
$buffer=str_replace("{areaListr}",$areaListr,$buffer);

include($cfg_inc_replace_path);
$buffer=str_replace("{posiStr}","角色管理 >> 编辑管理员",$buffer);

echo $buffer;
?>
