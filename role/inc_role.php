<?php

function getFuncTreeStr($initCheckedStr="") {
    $checkArr = [];
    if($initCheckedStr) {
        $arr = explode(",", $initCheckedStr);
        foreach($arr as $checkedId) {
            $checkArr[$checkedId] = 1;
        }
    }
    $areaList = mysql_get_list("select id, pid, name, level from db_functions where status>0 order by level,id");

    $rootid = 9999999;
    $checked = "";
    if(isset($checkArr[$rootid])) $checked = ", checked:true";
    $liStr = "<li><a lang=\"{id:$rootid,pid:-1,level:0,checkbox:{id:$rootid, name:'chkFunc[]', value:'$rootid'$checked}}\">全部</a></li>";
    foreach ($areaList as $item) {
        $id = $item['id'];
        $pid = $item['pid'];
        $name = $item['name'];
        $level = $item['level'];
        if($pid==0) $pid = $rootid;
        $checked = "";
        if(isset($checkArr[$id])) $checked = ", checked:true";
        $liStr .= "<li><a lang=\"{id:$id,pid:$pid,level:$level,checkbox:{id:$id, name:'chkFunc[]', value:'$id'$checked}}\">$name</a></li>";
    }
    return $liStr;
}


function getRoleFunctions($roleId) {
    if($roleId == ROLE_SUPER) {
        $sql = "select id as function_id from db_functions where status>0";
    } else {
        $sql = "select function_id from db_role_functions where role_id=$roleId";
    }
	$list = mysql_get_list($sql);
	return array_column($list, 'function_id');
}


function setRoleFunctions($roleId, $funcIds) {
	try{
		if(empty($funcIds)) {
			mysql_query("delete from db_role_functions where role_id=$roleId");
			return;
		}
		if(!is_array($funcIds)) $funcIds = array($funcIds);
		$idStr = implode(',', $funcIds);
		mysql_query("delete from db_role_functions where role_id=$roleId and function_id not in($idStr)");
		$existFuncIds = getRoleFunctions($roleId);
		$needInsertIds = array_diff($funcIds, $existFuncIds);
		foreach($needInsertIds as $funcId) {
			$sql = "insert into db_role_functions(role_id, function_id) values($roleId, $funcId)";
			mysql_query($sql);
		}
	} catch(Exception $e) {

	}
}
