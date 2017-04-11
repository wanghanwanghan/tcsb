<?php
//职员添加
require("../config/config.php");
require ($cfg_inc_common_path);
require_once (dirname(__FILE__) . "/inc_member.php");

if($_POST['add'])
{
	$username=trimHtml($_POST['username']);
	$password=trimHtml($_POST['password']);
    $roleId=trimHtml($_POST['role_id']);
	if(!$username or !$password)
	{
		echo "<script>alert('账户,密码不能为空！');location='add_member.php'</script>";
		exit;
	}
    if(!validPasswd($password)) {
        echo "<script>alert('密码太简单，至少需要8位以上，包含数字，字母，特殊符号其中的至少两种！');location='add_member.php'</script>";
        exit;
    }
	//判断账户是否重复
	$accountSql="select Tid from db_manager where userName='$username' AND status>0";
	$accountSqlBuffer=mysql_query($accountSql)or die("error776".mysql_error());
	list($accountTid)=mysql_fetch_row($accountSqlBuffer);
	if(!$accountTid)
	{
				$password=encodePasswd($password);
				$insertSql="insert into db_manager(type,userName,pwd,role_id)values('0','$username','$password','$roleId')";
				mysql_query($insertSql)or die("error977".mysql_error());
				mysql_close();
				$inc_title="管理员添加";
				$inc_tieValue="管理员添加成功!";
				$inc_urlPath=$web_path."/member/man_member.php";
				include($cfg_admin_php_result_path);
				echo $resultBuffer;
				exit;
	}
	else
	{
		mysql_close();
		echo "<script>alert('账户名已经被占用，请您重新填写！');location='add_member.php'</script>";
		exit;
	}
}

$roleList = mysql_get_list("select id, name from db_roles WHERE status>0");
$roleListOptionStr = getOptionListStr($roleList, 0, "id", "name");

$areaListr = getAreaTreeStr("");

$buffer=implode("",file($tpl_add_admin_member));
$buffer=str_replace("{roleListOptionStr}",$roleListOptionStr,$buffer);
$buffer=str_replace("{areaListr}",$areaListr,$buffer);

include($cfg_inc_replace_path);
echo $buffer;
?>