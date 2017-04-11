<?php
require("../config/config.php");
require_once($sub_sub_path);
require_once($mysql_path);
require_once($root_path."/inc/inc_mysql.php");
require_once($root_path."/inc//inc_error.php");

function checkRequest() {
	$iipp=$_SERVER["REMOTE_ADDR"];
	if($iipp!="192.168.1.222" && $iipp!="58.51.146.105")
	{
		//return false;
		return true;
	}
	return true;
}

function insertSoapLog($type, $log_id, $telno, $state, $fileurl) {
	$insertSql = "insert into db_soap_log(type, log_id, telno, state, file)values($type,$log_id,'$telno','$state','$fileurl')";
	mysql_query($insertSql);
}

function CheckUserState($telno)
{
		if(!checkRequest()) return false;
		$sql="select regstate from db_ivr where mobi='$telno' and status>0";
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
		logInfo("UpdateUserState: $telno,$state");
		if(!checkRequest()) return false;
		insertSoapLog(1, 0, $telno, $state, '');
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
function AddIdentifyResult($operateid,$telno,$state,$fileurl)   
{
		logInfo("AddIdentifyResult: $operateid,$telno,$state,$fileurl");
		if(!checkRequest()) return false;
		insertSoapLog(2, $operateid, $telno, $state, $fileurl);
		if($operateid==0) {
			//写入声纹注册信息
			$row = mysql_get_row("select * from db_ivr where mobi='$telno' AND status>0");
			if(empty($row)) {
				return false;
			}
			$uid = $row['Tid'];
			$logRow = mysql_get_row("select * from db_ivr_log WHERE uid=$uid AND flag=1 AND is_called=1 AND yzdate>='".date('Y-m-d H:i:s', time()-600)."' AND state<2");
			if($logRow) {
				$operateid = $logRow['Tid'];
			} else {
				$now = date('Y-m-d H:i:s');
				$insertSql="insert into db_ivr_log(flag,uid,type,yzdate,up_yzdate,server_state,state,sw_state,sw_url,end_yzdate,sh_state,shjg,is_called)
	values(1,'$uid',0,'$now','$now','99999',0,'0','',NULL,0,'',1)";
				mysql_query($insertSql)or die("error548");
				$operateid=mysql_insert_id();
			}
		}
		if($operateid==0) return false;

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
				if($aflag==0)
				{
						$oidSql="update db_ivr set regstate=1 where Tid='$uid'";
						mysql_query($oidSql)or die("error458");
				}
			}
		//判断是否有语音文件
		if($fileurl)
		{
			$insertSql="insert into db_ivr_rs(uid,oid,sw_url,cdate,sw_tell)values('$uid','$operateid','$fileurl',now(),'$telno')";
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
require_once("./lib/nusoap.php");
$soap = new soap_server;
$soap->configureWSDL('EventWSDL', 'http://tempuri.org/');
$soap->register('CheckUserState', array("telno"=>"xsd:string"),array("return"=>"xsd:boolean"));
$soap->register('UpdateUserState', array("telno"=>"xsd:string","state"=>"xsd:string"),array("return"=>"xsd:boolean"));
$soap->register('AddIdentifyResult', array("operateid"=>"xsd:string","telno"=>"xsd:string","state"=>"xsd:string","fileurl"=>"xsd:string"),array("return"=>"xsd:boolean"));
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$soap->service($HTTP_RAW_POST_DATA);
mysql_close();
?>
