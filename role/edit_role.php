<?php
require("../config/config.php");
require ($cfg_inc_common_path);
require_once (dirname(__FILE__) . "/inc_role.php");

$rid=$_GET['id'];
$rid=intval($rid);
if($_POST['edit'])
{
    $name = trimHtml($_POST['name']);
    $remark=trimHtml($_POST['remark']);
    $funcIds = $_POST['chkFunc'];
    $uSql = "update db_roles set name='$name', remark='$remark'";
    $uSql = $uSql . " where id='$rid'";
    mysql_query($uSql)or die("更新失败");
    setRoleFunctions($rid, $funcIds);

    mysql_close();
    $inc_title="角色编辑";
    $inc_tieValue="角色编辑成功!";
    $inc_urlPath=$web_path."/role/man_role.php";
    include($cfg_admin_php_result_path);
    echo $resultBuffer;
    exit;

}


$sql="select * from db_roles where id='$rid' and status>0";
$role = mysql_get_row($sql);
if(empty($role)) {
    echo "<script>alert('角色不存在！');location='man_role.php'</script>";
    exit;
}
$funcIds = getRoleFunctions($rid);
if($rid == ROLE_SUPER) $funcIds[] = 9999999;

$funcListr = getFuncTreeStr(implode(',', $funcIds));

$templateFile = $template_mk."/role/edit_role.html";

$buffer=implode("",file($templateFile));
$buffer=str_replace("{roleId}",$rid,$buffer);
$buffer=str_replace("{roleName}",$role['name'],$buffer);
$buffer=str_replace("{remark}",$role['remark'],$buffer);
$buffer=str_replace("{funcListr}",$funcListr,$buffer);

include($cfg_inc_replace_path);
$buffer=str_replace("{posiStr}","角色管理 >> 编辑角色",$buffer);

echo $buffer;
?>
