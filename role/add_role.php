<?php
//职员添加
require("../config/config.php");
require ($cfg_inc_common_path);
require_once (dirname(__FILE__) . "/inc_role.php");

if($_POST['add'])
{
    $name = trimHtml($_POST['name']);
    $remark=trimHtml($_POST['remark']);
    $funcIds = $_POST['chkFunc'];
    if(empty($name))
    {
        echo "<script>alert('角色不能为空！');location='add_role.php'</script>";
        exit;
    }
    $role = mysql_get_row("select * from db_roles where name='$name' and status>0");
    if($role) {
        echo "<script>alert('角色名已经被占用，请您重新填写！');location='add_role.php'</script>";
        exit;
    }
    $insertSql="insert into db_roles(name, remark) values('$name', '$remark')";
    mysql_query($insertSql)or die("error977".mysql_error());
    $rid = mysql_insert_id();
    setRoleFunctions($rid, $funcIds);

    mysql_close();
    $inc_title="添加角色";
    $inc_tieValue="角色添加成功!";
    $inc_urlPath=$web_path."/role/man_role.php";
    include($cfg_admin_php_result_path);
    echo $resultBuffer;
    exit;
}

$funcListr = getFuncTreeStr();

$templateFile = $template_mk."/role/add_role.html";

$buffer=implode("",file($templateFile));
$buffer=str_replace("{roleName}",$role['name'],$buffer);
$buffer=str_replace("{remark}",$role['remark'],$buffer);
$buffer=str_replace("{funcListr}",$funcListr,$buffer);

include($cfg_inc_replace_path);
$buffer=str_replace("{posiStr}","角色管理 >> 添加角色",$buffer);

echo $buffer;
?>