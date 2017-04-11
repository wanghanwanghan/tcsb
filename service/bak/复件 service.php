<?php
require("../config/config.php");
require($mysql_path);
class Service{
    function CheckUserState($telno)
	{
		$sql="select regstate from db_ivr where mobi='$telno'";
		$sqlsBuffer=mysql_query($sql)or die("error548");
		list($regstate)=mysql_fetch_row($sqlsBuffer);
		if($regstate==1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function UpdateUserState($telno,$state)
	{
		if($state==1)
		{
			$oidSql="update db_ivr set regstate=1 where mobi='$telno'";
			mysql_query($oidSql)or die("error458");
		}
		if($state==0)
		{
			$oidSql="update db_ivr set regstate=0 where mobi='$telno'";
			mysql_query($oidSql)or die("error458");
		}
		return true;
	}
	//添加用户声纹认证结果
	function AddIdentifyResult($operateid,$telno,$state,$fileurl)   
	{
		$sql="select flag,uid from db_ivr_log where Tid='$operateid'";
		$sqlsBuffer=mysql_query($sql)or die("error548");
		list($aflag,$uid)=mysql_fetch_row($sqlsBuffer);
			$sql="";
			if($state=="-1")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
			}
			if($state=="-2")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='1',end_yzdate=now() where Tid='$operateid'";
			}
			if($state=="-3")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
			}
			if($state=="-4")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='3',end_yzdate=now() where Tid='$operateid'";
			}
			
			if($state=="-5")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='3',end_yzdate=now() where Tid='$operateid'";
			}
			
			if($state=="-6")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='2',end_yzdate=now() where Tid='$operateid'";
			}
			
			if($state=="0")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='2',sw_state='4',end_yzdate=now() where Tid='$operateid'";
					if($aflag==0)
					{
						$oidSql="update db_ivr set regstate=1 where Tid='$uid'";
						mysql_query($oidSql)or die("error458");
					}
			}
			
			if($state=="1")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
			}
			
			if($state=="2")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
			}
			
			if($state=="3")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='3',end_yzdate=now() where Tid='$operateid'";
			}
			
			if($state=="10")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='2',sw_state='4',end_yzdate=now() where Tid='$operateid'";
					if($aflag==0)
					{
						$oidSql="update db_ivr set regstate=1 where Tid='$uid'";
						mysql_query($oidSql)or die("error458");
					}
			}
			
			if($state=="11")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='2',end_yzdate=now() where Tid='$operateid'";
			}
			if($state=="12")
			{
				$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='2',sw_state='4',end_yzdate=now() where Tid='$operateid'";
			}
		//判断是否有语音文件
		if($fileurl)
		{
			$insertSql="insert into db_ivr_rs(Tid,uid,oid,sw_url,cdate,sw_tell)values('','$uid','$operateid','$fileurl',now(),'$telno')";
			mysql_query($insertSql)or die("error54822");
		}
			
		if($sql)
		{
			mysql_query($sql)or die("error548");
			return true;
		}
		else
		{
			return false;
		}
	}
}
/*
function CheckUserState($telno)
{
	$sql="select regstate from db_ivr where mobi='$telno'";
	$sqlsBuffer=mysql_query($sql)or die("error548");
	list($regstate)=mysql_fetch_row($sqlsBuffer);
	if($regstate==1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function UpdateUserState($telno,$state)
{
	if($state==1)
	{
		$oidSql="update db_ivr set regstate=1 where mobi='$telno'";
		mysql_query($oidSql)or die("error458");
	}
	if($state==0)
	{
		$oidSql="update db_ivr set regstate=0 where mobi='$telno'";
		mysql_query($oidSql)or die("error458");
	}
	return true;
}
//添加用户声纹认证结果
function AddIdentifyResult($operateid,$telno,$state,$fileurl)   
{
	$sql="select flag,uid from db_ivr_log where Tid='$operateid'";
	$sqlsBuffer=mysql_query($sql)or die("error548");
	list($aflag,$uid)=mysql_fetch_row($sqlsBuffer);
		$sql="";
		if($state=="-1")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
		}
		if($state=="-2")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='1',end_yzdate=now() where Tid='$operateid'";
		}
		if($state=="-3")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
		}
		if($state=="-4")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='3',end_yzdate=now() where Tid='$operateid'";
		}
		
		if($state=="-5")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='3',end_yzdate=now() where Tid='$operateid'";
		}
		
		if($state=="-6")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='2',end_yzdate=now() where Tid='$operateid'";
		}
		
		if($state=="0")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='2',sw_state='4',end_yzdate=now() where Tid='$operateid'";
				if($aflag==0)
				{
					$oidSql="update db_ivr set regstate=1 where Tid='$uid'";
					mysql_query($oidSql)or die("error458");
				}
		}
		
		if($state=="1")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
		}
		
		if($state=="2")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state' where Tid='$operateid'";
		}
		
		if($state=="3")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='3',end_yzdate=now() where Tid='$operateid'";
		}
		
		if($state=="10")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='2',sw_state='4',end_yzdate=now() where Tid='$operateid'";
				if($aflag==0)
				{
					$oidSql="update db_ivr set regstate=1 where Tid='$uid'";
					mysql_query($oidSql)or die("error458");
				}
		}
		
		if($state=="11")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='1',sw_state='2',end_yzdate=now() where Tid='$operateid'";
		}
		if($state=="12")
		{
			$sql="update db_ivr_log set up_yzdate=now(),server_state='$state',state='2',sw_state='4',end_yzdate=now() where Tid='$operateid'";
		}
	//判断是否有语音文件
	if($fileurl)
	{
		$insertSql="insert into db_ivr_rs(Tid,uid,oid,sw_url,cdate,sw_tell)values('','$uid','$operateid','$fileurl',now(),'$telno')";
		mysql_query($insertSql)or die("error54822");
	}
		
	if($sql)
	{
		mysql_query($sql)or die("error548");
		return true;
	}
	else
	{
		return false;
	}
}

function TS_CallupAndIdentify($operateid,$telPno,$extion)   
{
	if($state=="-1")
	{
		
	}
	return 1;
}
$server = new SoapServer(null,array('uri'=>$service_uri,'location'=>$service_uri_function));
$server->addFunction('AddIdentifyResult');
$server->addFunction('TS_CallupAndIdentify');
$server->addFunction('CheckUserState');
$server->addFunction('UpdateUserState');
$server->handle();
mysql_close();
*/
require_once 'SoapDiscovery.class.php';
$disco = new SoapDiscovery('Service','Service');//第一个是类名 第二个可以随便写
header("Content-type: text/xml;charset=utf-8");
echo $disco->getWSDL();
mysql_close();
?>
