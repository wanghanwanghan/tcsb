<?php

include_once($sub_session_path);
require_once($sub_sub_path);
require_once($sub_page_path);
require_once($array_path);
require_once($mysql_path);
require_once(dirname(__FILE__)."/inc_error.php");
require_once(dirname(__FILE__)."/inc_mysql.php");
require_once(dirname(__FILE__)."/inc_privilege.php");


$request_uri = $_SERVER["REQUEST_URI"];
$arr = parse_url($request_uri);
$g_path = preg_replace("/^\//", "", $arr['path']);
$action = '';
if($g_path == "sw_op.php") {
        if(isset($_GET['type'])) {
                $action = trim($_GET['type']);
        }
} else if(isset($_GET['op'])) {
        $action = $_GET['op'];
}

$sin_manager = getCurrentManager();
if(empty($sin_manager)) {
	$rdstation="<script language='javascript'>alert('您还未登陆,请您先登陆');location='$web_path/login.php' </script>";
	echo $rdstation;
	exit;
}

$roleId = $sin_manager['role_id'];
$hasPriv = checkPrivilege($g_path, $action, $roleId);
if(!$hasPriv) {
	die('无权进行该操作');
}

