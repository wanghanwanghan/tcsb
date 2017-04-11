<?php
//发送声纹验证
function sendSwReg($ID,$telPno,$type,$service2_path)
{
	$wsdl = $service2_path;
	$client = new SoapClient($wsdl);
	//$client = new SoapClient(null, array('location'=>"$wsdl",'uri'=>"urn://tyler/req"));
	  
	$param = array('operateid'=>$ID,'telno'=>"$telPno",'extion'=>"$type");
	$ret = $client->TS_CallupAndIdentify($param);
	if ($ret){
	 return 1;
	}else{
	   return 0;
	}
}
?>