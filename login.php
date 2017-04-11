<?php
session_start();
require("config/config.php");
require($sub_sub_path);
if($_POST['subBtn'])
{
	$userName=trimHtml($_POST['userName']);
	$uPwd=trimHtml($_POST['uPwd']);
	$verifycode=trimHtml($_POST['verifycode']);
	if($userName=="")
	{
		echo "<script>alert('用户名不能为空！');location='$web_path/login.php';</script>";
		exit;
	}
	if($uPwd=="")
	{
		echo "<script>alert('密码不能为空！');location='$web_path/login.php';</script>";
		exit;
	}
	if($verifycode=="")
	{
		echo "<script>alert('验证码不能为空！');location='$web_path/login.php';</script>";
		exit;
	}
	//
	$Checknum=$_SESSION['Checknum'];
	$Checknum=strtolower($Checknum);
	$verifycode=strtolower($verifycode);
	if($verifycode!=$Checknum)
	{
		echo "<script>alert('验证码填写错误！');location='$web_path/login.php';</script>";
		exit;
	}
	require($mysql_path);
	require_once (dirname(__FILE__) . "/member/inc_member.php");

	$uPwd=encodePasswd($uPwd);
	

	$sql="select Tid, role_id, areas from db_manager where userName='$userName' and pwd='$uPwd' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error548");
	list($aTid, $roleId, $areas)=mysql_fetch_row($sqlBuffer);
	if($aTid)
	{
		$_SESSION['manager'] = array('Tid'=>$aTid, 'userName'=>$userName, 'role_id'=>$roleId, 'areas'=>$areas); 
		mysql_close();
		echo "<script>location='$web_path/index.php';</script>";
		exit;
	}
	else
	{
		mysql_close();
		echo "<script>alert('用户名，密码错误！');location='$web_path/login.php';</script>";
		exit;
	}


}
$buffer=implode("",file($template_admin_logo_path));
$buffer=str_replace("{img_path}",$img_path,$buffer);
$buffer=str_replace("{postName}",$_SERVER[PHP_SEFL],$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>
