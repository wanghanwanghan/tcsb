<?php
define("ROLE_SUPER", 1);

global $g_moduleGroup;

$g_moduleGroup = array(
	'sw'=>array(
		'range'=>array(400000, 500000),
		'text' => '声纹管理',
		'link' => '/sw.php',
		'sub' => array(
			'dj' => array(
				'range'=>array(401000,402000),
				'text' => '声纹管理',
				'link' => '/sw.php',
			),
			'yz' => array(
				'range'=>array(402000,403000),
				'text' => '声纹验证信息',
				'link' => '/man_sw_log.php',
			),
		),
	),
	'user'=>array(
		'range'=>array(300000, 400000),
		'text' => '用户登记',
		'link' => '/man_xinxi.php',
		'sub' => array(
			'user' => array(
				'range'=>array(301000,301005),
				'text' => '登记管理',
				'link' => '/man_xinxi.php',
			),
			'imp' => array(
				'range'=>array(301005,302000),
				'text' => '导入管理',
				'link' => '/man_xinxi_imp.php',
			),
		),
	),
	'sq'=>array(
		'range'=>array(200000, 300000),
		'text' => '属地管理',
		'link' => '/sq.php',
		'sub' => array(
			'sq' => array(
				'range'=>array(201000,202000),
				'text' => '属地管理',
				'link' => '/sq.php',
			),
		),
	),
	'sys'=>array(
		'range'=>array(100000, 200000),
		'text' => '账号管理',
		'link' => '/member/passwd.php',
		'sub' => array(
			'pwd' => array(
				'range'=>array(101009,102000),
				'text' => '修改密码',
				'link' => '/member/passwd.php',
			),
			'member' => array(
				'range'=>array(101000,101009),
				'text' => '账号管理',
				'link' => '/member/man_member.php',
			),
			'role' => array(
				'range'=>array(102000,103000),
				'text' => '角色管理',
				'link' => '/role/man_role.php',
			),
		),
	),
);

function getManager($tid) {
    return  mysql_get_row("select * from db_manager where Tid='$tid'");
}

function getManagerAreas($tid, $manager=null) {
    if($manager===null) {
        $manager = getManager($tid);
    }
    if(empty($manager) || empty($manager['areas'])) return [];
    return explode(",", $manager['areas']);
}

function getCurrentManager() {
	if(isset($_SESSION['manager']) && $_SESSION['manager']) {
		return $_SESSION['manager'];
	} else {
		return null;
	}
}

function getCurrentRoleId() {
	$manager = getCurrentManager();
	if(empty($manager)) return 0;
	return $manager['role_id'];
}

function getAllFunctions() {
	static $allFunctions = null;
	if($allFunctions === null) {
		$list  = mysql_get_list("select id, path, action from db_functions where status>0 order by id asc");
		$allFunctions = array();
		foreach($list as $func) {
			$id = $func['id'];
			$path = $func['path'];
			$action = $func['action'];
			$allFunctions[$id] = array($path, $action);
		}
	}
	return $allFunctions;
}


function getRoleFunctionIds($roleId) {
	static $roleFunctions = array();
	if(!isset($roleFunctions[$roleId])) {
        if($roleId == ROLE_SUPER) {
            $sql = "select id as function_id from db_functions where status>0";
        } else {
            $sql = "select function_id from db_role_functions where role_id=$roleId";
        }
		$list = mysql_get_list($sql);
		$funcIds = array_column($list, 'function_id');
        if(empty($funcIds)) $funcIds = array(401000, 401001, 401002, 401003, 401004, 401005, 402000, 402001, 402002, 402003);
		$roleFunctions[$roleId] = $funcIds;
	}
	return $roleFunctions[$roleId];
}


function getRolePrivileges($roleId=0) {
	if(empty($roleId)) $roleId = getCurrentRoleId();
	static $rolePrivs = array();
	if(!isset($rolePrivs[$roleId])) {
		$funcIds = getRoleFunctionIds($roleId);
		$privs = getPrivilegesByFuncIds($funcIds);
		$rolePrivs[$roleId] = $privs;
	}
	return $rolePrivs[$roleId];
}


function getPrivilegesByFuncIds($functionIds) {
	$allFunctions = getAllFunctions();
	$ret = [];
	foreach($functionIds as $funcId) {
		if(!isset($allFunctions[$funcId])) continue; 
		list($path, $action) = $allFunctions[$funcId];
		if(!isset($ret[$path])) $ret[$path] = array();
		$ret[$path][$action] = 1;
	}
	return $ret;
}


function checkPrivilege($path, $action, $roleId=0) {
	if(empty($roleId)) $roleId = getCurrentRoleId();
	$whiteList = array (
		'index.php' => 1,
		'login.php' => 1,
		'ifrm_task.php' => 1,
		'ifrm_task_test.php' => 1,
		'bf_yy.php' => 1,
		'member/passwd.php' => 1,
	);
	if(isset($whiteList[$path])) return true;
	if($roleId == ROLE_SUPER) return true;
	$privs = getRolePrivileges($roleId);
	if(!isset($privs[$path])) {
		return false;
	}
	if(isset($privs[$path]['*'])) {
		return true;
	} else if(isset($privs[$path][$action])) {
		return true;
	}
	return false;
}

function getMyModules() {
	$roleId = getCurrentRoleId();
	global $g_moduleGroup;
    if($roleId == ROLE_SUPER) return $g_moduleGroup;
	$funcIds = getRoleFunctionIds($roleId);
	$myModules = array();
	foreach($g_moduleGroup as $module=>$topItem) {
		$range = $topItem['range'];	
		list($minFuncId, $maxFuncId) = $range;
		$subModule = $topItem['sub'];
		$topItem['sub'] = array();
		if($module == "sys") {
			$myModules[$module] = $topItem;	
		} else if(isInRange($funcIds, $minFuncId, $maxFuncId)) {
			$myModules[$module] = $topItem;	
		} else {
			continue;
		}
		
		foreach($subModule as $sub=>$subItem) {
			$subRange = $subItem['range'];	
			list($minFuncId, $maxFuncId) = $subRange;
			if($module =="sys" && $sub == "pwd") {
				$myModules[$module]['sub'][$sub] = $subItem;
			} else if(isInRange($funcIds, $minFuncId, $maxFuncId)) {
				$myModules[$module]['sub'][$sub] = $subItem;
			}
		}
	}
	return $myModules;
}

function isInRange($funcIds, $minFuncId, $maxFuncId) {
	foreach($funcIds as $funcId) {
		if($funcId >= $minFuncId && $funcId < $maxFuncId) {
			return true;
		}
	}
	return false;
}


function getPageModule($path="") {
	if(empty($path)) {
		global $g_path;
		$path = $g_path;
	}
	if(empty($path)) return "";
	$func  = mysql_get_row("select id from db_functions where path='$path' and status>0 LIMIT 0,1");
	if(empty($func)) return "";
	$funcId = $func['id'];
	global $g_moduleGroup;
	foreach($g_moduleGroup as $module=>$topItem) {
		$range = $topItem['range'];	
		list($minFuncId, $maxFuncId) = $range;
		if($funcId >= $minFuncId && $funcId < $maxFuncId) {
			return $module;
		}
	}
	return "";
}
