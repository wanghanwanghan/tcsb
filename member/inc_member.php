<?php

function encodePasswd($pwd) {
    global $cfg_salt_md5;
    $pwd = md5($cfg_salt_md5.$pwd);
    return substr($pwd, 0, 24);
}

function validPasswd($pwd) {
    if(strlen($pwd)<8) {
        return false;
    }
    $regarr = array(
        '/[A-Z]/',
        '/[a-z]/',
        '/[0-9]/',
        '/[~!@#$%^&*()\-_=+{};:<,.>?]/'
    );
    $regcnt = 0;
    foreach($regarr as $reg) {
        if(preg_match($reg, $pwd)) {
            $regcnt++;
        }
    }
    if($regcnt < 2) return false;
    return true;
}

function getAreaTreeStr($initCheckedStr="") {
    $checkArr = [];
    if($initCheckedStr) {
        $arr = explode(",", $initCheckedStr);
        foreach($arr as $checkedId) {
            $checkArr[$checkedId] = 1;
        }
    }
    $areaList = mysql_get_list("select Tid, ftid, level, name from db_office where status>0 order by level,orid");

    $rootid = 9999999;
    $checked = "";
    if(isset($checkArr[$rootid])) $checked = ", checked:true";
    $liStr = "<li><a lang=\"{id:$rootid,pid:-1,level:0,checkbox:{id:$rootid, name:'chkArea[]', value:'$rootid'$checked}}\">全部</a></li>";
    foreach ($areaList as $item) {
        $id = $item['Tid'];
        $pid = $item['ftid'];
        $name = $item['name'];
        $level = $item['level'];
        if($pid==0) $pid = $rootid;
        $checked = "";
        if(isset($checkArr[$id])) $checked = ", checked:true";
        $liStr .= "<li><a lang=\"{id:$id,pid:$pid,level:$level,checkbox:{id:$id, name:'chkArea[]', value:'$id'$checked}}\">$name</a></li>";
    }
    return $liStr;
}