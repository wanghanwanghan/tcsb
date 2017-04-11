<?php
/**
 * Created by PhpStorm.
 * User: liyunshan
 * Date: 16-10-16
 * Time: 上午7:46
 */
function mysql_get_one($sql, $field) {
    $result = mysql_query($sql);
    if(!$result) die("error02".mysql_error());
    $ret = null;
    while ($row = mysql_fetch_assoc($result)) {
        if(isset($row[$field])) {
            $ret = $row[$field];
        }
        break;
    }
    mysql_free_result($result);
    return $ret;
}

function mysql_get_row($sql) {
    $result = mysql_query($sql);
    if(!$result) die("error02".mysql_error());
    $ret = null;
    while ($row = mysql_fetch_assoc($result)) {
        $ret = $row;
        break;
    }
    mysql_free_result($result);
    return $ret;
}

function mysql_get_list($sql) {
    $result = mysql_query($sql);
    if(!$result) die("error02".mysql_error());
    $ret = [];
    $count = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $ret[] = $row;
	$count++;
        if($count>2000) break;
    }
    mysql_free_result($result);
    return $ret;
}
