<?php
if(PHP_SAPI !== 'cli') {
	header("Content-Type:text/html;charset=utf-8");
}
define("MFLAG","1");
//$root_path=$_SERVER["DOCUMENT_ROOT"]."";
$root_path=dirname(__DIR__);
//人工检测声纹刷新频率
$cfg_warmSec=5;
//判断最后多少秒未响应为未知原因
$cfg_error_Sec=300;
//判断自动运行隔几秒为自动放弃
$cfg_run_error_Sec=300;
//一天几次判定失败
$cfg_warm_error_num=3;
//半个小时的秒数
$cfg_half_h_time=1800;

//后台文件夹名称
$admin_mk_name='';
//css路径
$css_path="";
//站点路径
$img_path="";
//站点路径
$web_path="";
//js站点路径
$js_path="";
//分页条数
$admin_page_config=20;

//加密salt
$cfg_salt_md5 = "b2b4d2b664be215fbc236341babb09b5";
//密钥
$m_key_md5="sdfwerew21334534@@@";
//====================================MAKE生成文件===============================
$cfg_xml_path=$root_path."/seoxml.xml";
//配置文件夹
$config_mk=$root_path."/config";
$config_log_path=$root_path."/logs";
//函数文件
$sub_sub_path=$config_mk."/sub.php";
//登陆判断文件
$sub_session_path=$config_mk."/check_session.php";
//分页函数
$sub_page_path=$config_mk."/page1.inc.php";
//数据库文件
$mysql_path=$config_mk."/mysql.php";
//数组文件
$array_path=$config_mk."/array.php";

//全局引入文件
$cfg_inc_path=$root_path."/inc";

//公共文件
$cfg_inc_common_path=$cfg_inc_path."/inc_common.php";

//替换文件
$cfg_inc_replace_path=$cfg_inc_path."/inc_replace.php";

//反馈结果处理
$cfg_php_result_path=$root_path."/result.php";

//反馈结果处理
$cfg_admin_php_result_path=$cfg_php_result_path;


//后台访问地址
$admin_mk_path="/".$admin_mk_name;
//=========模板文件夹=============
$template_mk=$root_path."/template";
//首页
$template_index_path=$template_mk."/index.html";
//添加社区
$template_add_sq_path=$template_mk."/add_sq_class.html";
//管理社区
$template_man_sq_path=$template_mk."/man_sq.html";
//添加信息
$template_add_xinxi_path=$template_mk."/add_xinxi.html";
//信息管理
$template_man_xinxi_path=$template_mk."/man_xinxi.html";
$template_man_xinxi_path_imp=$template_mk."/man_xinxi_imp.html";
//编辑信息
$template_edit_xinxi_path=$template_mk."/edit_xinxi.html";
//声纹列表
$template_sw_path=$template_mk."/sw.html";
//声纹认证操作
$template_result_op_path=$template_mk."/result_op.html";
//声纹认证审核操作
$template_man_sw_path=$template_mk."/man_sw_log.html";
//声纹刷新模版
$template_shuaxin_sw_path=$template_mk."/shuaxin.html";

//结果
$template_result_path=$template_mk."/result.html";
//审核结果
$template_result_shenhe_path=$template_mk."/result_shenhe.html";

//审核结果
$template_man_sw_yy_path=$template_mk."/man_sw_yy.html";

//声纹刷新模版
$template_run_shuaxin_sw_path=$template_mk."/run_shuaxin.html";

//职员管理
$tpl_admin_member=$template_mk."/member";
//添加
$tpl_add_admin_member=$tpl_admin_member."/add_member.html";
//管理
$tpl_man_admin_member=$tpl_admin_member."/man_member.html";
//编辑
$tpl_edit_admin_member=$tpl_admin_member."/edit_member.html";

$template_admin_logo_path=$template_mk."/Login.html";

$template_bf_yy_path=$template_mk."/bf_yy.html";

//=============webservice==============
//客户端文件
$client_path=$root_path."/service/client.php";

//service地址
$service_uri='http://58.51.146.103:9002/';
//调用地址
$service_uri_function='http://58.51.146.103:9002/service/service.php';
//远程声纹识别
$service_tie_path="http://58.51.146.105:8089/voiceservice/wsdl/VoiceprintService.wsdl";
//语音地址
$cfg_yuyin_path="http://58.51.146.105:8089/voice";
?>
