<?php 
ini_set("soap.wsdl_cache_enabled", "0");
include "action.php";
$Server=new SoapServer('soap.wsdl',array('soap_version' => SOAP_1_2));   //创建SoapServer对象
$Server->setClass("action");
$Server->handle();
?>