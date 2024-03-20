<?php
/*
#$inc_title //
#$inc_tieValue //
#$inc_urlPath  //صַ
*/
//1231232131 XueSi test
$resultBuffer=implode("",file($template_result_path));
$resultBuffer=str_replace("{title}",$inc_title,$resultBuffer);
$resultBuffer=str_replace("{tieValue}",$inc_tieValue,$resultBuffer);
$resultBuffer=str_replace("{urlPath}",$inc_urlPath,$resultBuffer);
$resultBuffer=str_replace("{img_path}",$img_path,$resultBuffer);
?>
