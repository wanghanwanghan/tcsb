<?php
require("config/config.php");

require ($cfg_inc_common_path);

$spanBtn=$_GET['yurl'];
$spanBtn=trim($spanBtn);
$buffer=implode("",file($template_bf_yy_path));
$url_yy=$cfg_yuyin_path."/".$spanBtn;

if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
{
	$buffer=str_replace("{height}","40",$buffer);
}
else
{
	$buffer=str_replace("{height}","46",$buffer);
}
$buffer=str_replace("{url_yy}",$url_yy,$buffer);
echo $buffer;
?>
