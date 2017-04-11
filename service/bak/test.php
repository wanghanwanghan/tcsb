<?php
require("../config/config.php");
require($client_path);
$ID="8";
$state="-2";
//电话
$telPn="8888";
$fileurl="4543543.swf";
$wsdl = "http://tcweb.gotoip4.com/service/serviceClass.wsdl";
/*
$client = new SoapClient($wsdl);
//$client = new SoapClient(null, array('location'=>"$wsdl",'uri'=>"urn://tyler/req"));
$param = array('operateid'=>$ID,'telno'=>"$telPno",'state'=>"$state",'fileurl'=>"$fileurl");
print_r($param);
//$ret = $client->AddIdentifyResult($ID,$telPno,$state,$fileurl);
echo $ret;
	if ($ret){
	 echo "ppOK";
	}else{
	  echo "ppON";
	}
*/

$client = new SoapClient($wsdl);
echo $client->AddIdentifyResult($ID,$telPn,$state,$fileurl);
?>