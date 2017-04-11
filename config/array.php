<?php
//社区分类等级
$level_arr[1]="一级";
$level_arr[2]="二级";
$level_arr[3]="三级";
$level_arr[4]="四级";

//矫正状态
$jz_type_row[0]="正常";
$jz_type_row[1]="解矫";
$jz_type_row[2]="中止";

//监管级别
$jg_grade_row[0]="文本无关";
$jg_grade_row[1]="文本无关-宽松";
$jg_grade_row[2]="动态口令";

$jg_grade_date_row[0]="7";
$jg_grade_date_row[1]="3";
$jg_grade_date_row[2]="1";


//注册数组
$regstate_grade_row[0]="未注册";
$regstate_grade_row[1]="已注册";
//声纹结果类型
$sw_state_type_row[0]="验证中";
$sw_state_type_row[1]="未接";
$sw_state_type_row[2]="中途挂机";
$sw_state_type_row[3]="失败";
$sw_state_type_row[4]="成功";
$sw_state_type_row[5]="接口失败";
//声纹结果状态
$sw_state_arr[0]="处理中";
$sw_state_arr[1]="失败";
$sw_state_arr[2]="成功";
//声纹审核状态
$sh_state_arr[0]="请选择";
$sh_state_arr[1]="声纹正常";
$sh_state_arr[2]="声纹失败";
//拨打类型
$sw_bd_type[0]="人工";
$sw_bd_type[1]="自动";

//服务器端数组
$service_state_row[-1]="对方正在振铃,请等待...";
$service_state_row[-2]="超时没有接电话,验证失败";
$service_state_row[-3]="对方已经接通,请等待...";
$service_state_row[-4]="用户还没有登记，验证失败";
$service_state_row[-5]="声纹验证繁忙，验证失败";
$service_state_row[-6]="中途挂机，验证失败";
$service_state_row[0]="认证通过";
$service_state_row[1]="认证失败等待第2次认证，请等待...";
$service_state_row[2]="认证失败等待第3次认证，请等待...";
$service_state_row[3]="3次认证不通过，验证失败";
$service_state_row[10]="登记成功";
$service_state_row[11]="登记失败,验证失败";
$service_state_row[12]="登记成功";
$service_state_row[999]="回应超时，无法继续，验证失败!";
?>