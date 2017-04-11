<?php
require("./config/config.php");
require ($cfg_inc_common_path);

$topMenuStr = "";
$subMenuStr = "";
$myModules = getMyModules();
$currentModule = getPageModule($g_path);
if(!empty($currentModule)) {
	$_SESSION['module'] = $currentModule;
} else {
	$currentModule = $_SESSION['module'];
}
if(empty($currentModule)) {
	$currentModule = "sys";
}

$cnt = 0;
foreach($myModules as $module=>$topItem) {
	$cnt++;
	$menuLink = $topItem['link'];
	$menuText = $topItem['text'];
	$showSub = " style=\"display:none;\"";
	$activeStr = "";
	if($currentModule == $module) {
		$showSub = "";
		$activeStr = " class=\"active\"";
	}

	$topMenuStr .= "<a href=\"{web_path}$menuLink\" target=\"mainframe\"$activeStr onClick=\"showContent('lab','nav', $cnt)\" id=\"lab$cnt\">$menuText</a>\n";
	$subMenuStr .= "<div id=\"nav$cnt\"$showSub>\n";
	foreach($topItem['sub'] as $sub=>$subItem) {
		$menuLink = $subItem['link'];
		$menuText = $subItem['text'];
		$subMenuStr .= "<a href=\"{web_path}$menuLink\" target=\"mainframe\">$menuText</a>\n";
	}
	$subMenuStr .= "</div>\n";
}

for($idx=$cnt+1; $idx<=8; $idx++) {
	$topMenuStr .= "<a href=\"###\" style=\"display:none\" target=\"mainframe\" id=\"lab$idx\" onClick=\"showContent('lab','nav',$idx)\"></a>\n";
	$subMenuStr .= "<div id=\"nav$idx\" style=\"display:none;\"></div>\n";
}

$buffer=implode("",file($template_index_path));
$buffer=str_replace("{topMenuStr}",$topMenuStr,$buffer);
$buffer=str_replace("{subMenuStr}",$subMenuStr,$buffer);

include($cfg_inc_replace_path);
$buffer=str_replace("{sin_uname}",$sin_uname,$buffer);
echo $buffer;
?>
