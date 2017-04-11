<?php
session_start();
$manager = null;
$sin_uid=0;
if(isset($_SESSION['manager']) && $_SESSION['manager']) {
	$manager = $_SESSION['manager'];
	$sin_uid=$manager['Tid'];
	$sin_uname=$manager['userName'];
}
if(empty($manager) || empty($sin_uid))
{
	$rdstation="<script language='javascript'>alert('您还未登陆,请您先登陆');location='$web_path/login.php' </script>";
	echo $rdstation;
	exit;
}
?>
