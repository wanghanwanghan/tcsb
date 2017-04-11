<?php
require("../config/config.php");
require($sub_sub_path);
require($array_path);
require($array_path);
require($client_path);
$dateH=date("H");
$dateH=intval($dateH);
require($mysql_path);

$nowTime=time();
//开始运行进行是否重新开始验证
$ysql="select Tid,up_bdate,up_edate,dowm_bdate,dowm_edate,ydate,jg_grade,mobi from db_ivr where regstate=1 and jz_type=0 and flag=1";
$ysqlBuffer=mysql_query($ysql)or die("error548");
while(list($y_Tid,$y_up_bdate,$y_up_edate,$y_dowm_bdate,$y_dowm_edate,$y_ydate,$y_jg_grade,$y_mobi)=mysql_fetch_row($ysqlBuffer))
{
	$y_jg_grade=intval($y_jg_grade);
	
	$yzTypeStr=$jg_grade_row[$y_jg_grade];
	echo "<br><br>===================开始进行解封：".$y_Tid."[".$y_mobi."]"."$yzTypeStr"."-----最后验证日期：".$y_ydate."====================<br><br>";
	$y_ydateRow=explode(" ",$y_ydate);
	$y_ydate2=$y_ydateRow[0];
	$y_ydate2Str=$y_ydate2." 00:00:00";
	$tempTime=secChangeTime($y_ydate2Str);
	
	$numTime=$jg_grade_date_row[$y_jg_grade];
	
	$time7date=$numTime*86400;
	$endTime=$tempTime+$time7date;
	echo "第二次执行时间：".date("Y-m-d H:i:s",$endTime);
	
	if($endTime<$nowTime)
	{
		$dateValue=date("Y-m-d");
		
		
		$uSql="update db_ivr set flag=0,ydate='$dateValue 00:00:00' where Tid='$y_Tid'";
		mysql_query($uSql)or die("error54");
	}
	
}
echo "<br><br>=================开始进行自动拨打==============================<br><br>";


$dateStr=date("Y-m-d H:i:s");
if($dateH>=0 and $dateH<=12)
{
	$sql="select Tid,up_bdate,up_edate,dowm_bdate,dowm_edate,ydate,mobi from db_ivr where regstate=1 and jz_type=0 and ejz_date>='$dateStr' and bjz_date<='$dateStr' and up_bdate<='$dateH' and up_edate>='$dateH' and flag=0";
}
else
{
	$sql="select Tid,up_bdate,up_edate,dowm_bdate,dowm_edate,ydate,mobi from db_ivr where regstate=1 and jz_type=0 and ejz_date>='$dateStr' and bjz_date<='$dateStr' and dowm_bdate<='$dateH' and dowm_edate>='$dateH' and flag=0";
}
echo $sql."<br>";
$sqlBuffer=mysql_query($sql)or die("error548");
while(list($Tid,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate,$ydate,$mobi)=mysql_fetch_row($sqlBuffer))
{
		echo "<br>==========================编号：$Tid ======".$mobi."开始进入声纹发送==========================<br>";
		$yflag=0;//是否继续运行 0:继续运行 1:停止运行
		$pnum=0;//验证次数
		$yflag_norun=0;//是否有未执行完项目
		$nowTempDate="";//最后一次验证时间
		//是否有已经运行过的	
		$logSql="select Tid,yzdate,server_state,sw_state,sh_state,end_yzdate,sh_state,state from db_ivr_log where uid='$Tid' and flag=1 and type=1 and yzdate>='$ydate' order by yzdate asc";
		$logSqlBuffer=mysql_query($logSql)or die("error548");
		while(list($logTid,$yzdate2,$server_state2,$sw_state2,$sh_state2,$end_yzdate2,$sh_state2,$state2)=mysql_fetch_row($logSqlBuffer))
		{
			if($state2==2)//声纹成功
			{
				$uSql="update db_ivr set flag=1 where Tid='$Tid'";
				$uSqlBuffer=mysql_query($uSql)or die("error48");
				$yflag=1;
			}
			if($state2==1)
			{
				$pnum++;
				$nowTempDate=$yzdate2;
			}
			if($state2==0)
			{
				$tempTime=secChangeTime($yzdate2);
				$centeTime=time()-$tempTime;
				if($centeTime>=$cfg_run_error_Sec)
				{
					$uSql3="update db_ivr_log set end_yzdate=now(),state=1,sw_state='5',server_state='999' where Tid='$logTid'";
					mysql_query($uSql3)or die("error548");
					$pnum++;
					$nowTempDate=$yzdate2;
				}
				else
				{
					$yflag_norun=1;
				}
			}
		}
		if($pnum>=$cfg_warm_error_num)
		{
			$uSql="update db_ivr set flag=1 where Tid='$Tid'";
			$uSqlBuffer=mysql_query($uSql)or die("error48");
			$yflag=1;
		}
		
		if($yflag==0)
		{
			
			if($yflag_norun==1)
			{
				//有未执行完任务开始跳过
			}
			else
			{
				//是否超过半个小时
				if($nowTempDate and $nowTempDate!='0000-00-00 00:00:00')
				{
					$timeNum=secChangeTime($nowTempDate);
				}
				$centerTime=time()-$timeNum;
				if($centerTime>=$cfg_half_h_time)//超过半个小时开始拨打
				{
					$insertSql="insert into db_ivr_log(Tid,flag,uid,type,yzdate,up_yzdate,server_state,state,sw_state,sw_url,end_yzdate,sh_state,shjg)values('',1,'$Tid',1,now(),now(),'99999',0,'0','','',0,'')";
					$insertSqlBuffer=mysql_query($insertSql)or die("error548");
					$ID=mysql_insert_id();
					$flag=sendSwReg($ID,$mobi,1,$service_tie_path);
					echo $mobi."开始发送执行<br>";
				}
			}
			
		}
		
	
}
mysql_close();
echo "本次运行成功";
$url2=$web_path."/run/run_shell.php";
$buffer=implode("",file($template_run_shuaxin_sw_path));
$buffer=str_replace("{cfg_warmSec}","10",$buffer);
$buffer=str_replace("{shuaxin}","Load('$url2')",$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>
