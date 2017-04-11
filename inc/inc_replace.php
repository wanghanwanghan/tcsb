<?php
$buffer=str_replace("{img_path}",$img_path,$buffer);
$buffer=str_replace("{admin_mk_name}",$admin_mk_path,$buffer);
$buffer=str_replace("{admin_mk_path}",$admin_mk_path,$buffer);
$buffer=str_replace("{web_path}",$web_path,$buffer);
$buffer=str_replace("{css_path}",$css_path,$buffer);
$buffer=str_replace("{js_path}",$js_path,$buffer);
if(isset($cfg_kz_id)) $buffer=str_replace("{cfg_kz_id}",$cfg_kz_id,$buffer);