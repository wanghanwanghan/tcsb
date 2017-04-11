<?php
require("config/config.php");
require_once($sub_sub_path);
require_once($sub_page_path);
require_once($array_path);
require_once($mysql_path);
require_once(dirname(__FILE__)."/inc/inc_mysql.php");
require_once(dirname(__FILE__)."/inc/inc_privilege.php");

$path = "/sw.php";
$path = preg_replace("/^\//", "", $path);
echo $path."\n";
$action = '';
$ret = checkPrivilege($path, $action, 1);
var_dump($ret);

