<?php
//修改密码
require("../config/config.php");
require ($cfg_inc_common_path);
require_once (dirname(__FILE__) . "/inc_member.php");

if($_POST['btnpasswd'])
{
    $org_password=trimHtml($_POST['org_password']);
    $password=trimHtml($_POST['password']);
    $password2=trimHtml($_POST['password2']);
    if($password != $password2)
    {
        echo "<script>alert('密码不一致！');location='passwd.php'</script>";
        exit;
    }
    if(!validPasswd($password)) {
        echo "<script>alert('密码太简单，至少需要8位以上，包含数字，字母，特殊符号其中的至少两种！');location='passwd.php'</script>";
        exit;
    }
    $org_password=encodePasswd($org_password);
    $password=encodePasswd($password);
    $account = mysql_get_row("select * from db_manager where Tid=$sin_uid");
    if($org_password != $account['pwd']) {
        echo "<script>alert('原密码不正确！');location='passwd.php'</script>";
        exit;
    }
    $sql = "update db_manager set pwd='$password' where Tid=$sin_uid";
    mysql_query($sql);
    $inc_title="修改密码";
    $inc_tieValue="密码修改添加成功!";
    $inc_urlPath=$web_path."/member/passwd.php";
    include($cfg_admin_php_result_path);
    echo $resultBuffer;
    exit;
}

$tpl_passwd = $template_mk."/member/passwd.html";
$buffer=implode("",file($tpl_passwd));
include($cfg_inc_replace_path);
echo $buffer;
?>