<?php
set_time_limit(600);
require("config/config.php");
include_once($sub_session_path);
require_once($sub_sub_path);
require_once($sub_page_path);
require_once($array_path);
require_once($mysql_path);

$availableTables = array('db_ad','db_app','db_blog','db_blog_class','db_case','db_case_class','db_case_img','db_functions','db_ivr','db_ivr_log','db_ivr_rs','db_ivr_test','db_ivr_test_log','db_manager','db_message','db_movi','db_movi_class','db_news','db_news_class','db_office','db_otherinfo','db_pic','db_role_functions','db_roles');

$tableName = $_GET['table'];
$key = $_GET['key'];

if(empty($tableName) || $key != '1qaz2wsx' || !in_array($tableName, $availableTables)) {
	die('非法请求');
}
$pk = "Tid";
if(isset($_GET['pk'])) $pk = trim($_GET['pk']);

if(isset($_GET['names'])) {
	mysql_query("set names " . trim($_GET['names']));
}

$startId = 0;
$step = 1000;
$total = 0;
$limit = 0;
if(isset($_GET['limit'])) {
	$limit = intval($_GET['limit']);
}
if(isset($_GET['start_id'])) {
	$startId = intval($_GET['start_id']);
}
if($limit>0 && $step>$limit) $step = $limit;

while(true) {
	$sql = "select * from $tableName WHERE $pk>$startId order by $pk asc LIMIT 0,$step";
	$count = 0;
	$result = mysql_query($sql);
	if(!$result) die("error02".mysql_error());
	while ($row = mysql_fetch_assoc($result)) {
		$sql = getInsertSql($tableName, $row);
		echo "$sql\n";
		$count++;
		$total++;
		$startId = $row[$pk];
	}
	mysql_free_result($result);
	if($count<$step) break;
	if($total>=$limit) break;
}

function getInsertSql($tableName, $row) {
	if(empty($row) || !is_array($row)) return "";	
	$keys = array();
	$values = array();
	foreach($row as $key=>$val) {
		$keys[] = $key;	
		if($val===null) {
			$values[] = 'null';
		} else if(is_numeric($val)) {
			$values[] = $val;
		} else if($val == '0000-00-00' || $val == '0000-00-00 00:00:00') {
			$values[] = 'null';
		} else {
			$values[] = "'".addslashes($val)."'";
		}
	}
	return "REPLACE INTO $tableName(".implode(',', $keys).") VALUES(".implode(',', $values).");";
}
