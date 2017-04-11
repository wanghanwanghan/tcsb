<?php
require("config/config.php");
require ($cfg_inc_common_path);
require ($client_path);

$type=$_GET['type'];

$Tid=$_GET['Tid'];
$Tid=intval($Tid);
if($type=="del")
{
	$sql="update db_ivr set regstate=0 where Tid='$Tid'";
	$sqlBuffer=mysql_query($sql)or die("error548");
	$opStr="op_".$Tid;
	$regStr="reg_".$Tid;
	
	echo "<script>alert('删除成功!');parent.document.getElementById(\"$regStr\").innerHTML='未注册';parent.document.getElementById(\"$opStr\").innerHTML='登记';</script>";
	exit;	

}
if($type=="logyy")
{
	$totleBuffer="";
	$buffer=implode("",file($template_man_sw_yy_path));
	$whilebuffer2=Template("$template_man_sw_yy_path","<!--begin-->","<!--end-->");
	if($Tid) {
		$sql4="select Tid,sw_url,cdate from db_ivr_rs where oid='$Tid' order by cdate desc";
	} else {
		$sql4="select Tid,sw_url,cdate from db_ivr_rs where 1=2";
	}
	$sqlBuffer=mysql_query($sql4)or die("eeror48");
	$bflag=1;
	while(list($w_Tid,$sw_url2,$cdate2)=mysql_fetch_row($sqlBuffer))
	{
		$temp=$whilebuffer2;
		$temp=str_replace("{Tid}",$w_Tid,$temp);
		$temp=str_replace("{cdate}",$cdate2,$temp);
		$sw_url2Row=explode("|",$sw_url2);
		$swCount=count($sw_url2Row);
		$astr="";
		$whilebuffer22=Template("$template_man_sw_yy_path","<!--begin2-->","<!--end2-->");
		$totleBuffer2="";
		for($a=0;$a<$swCount;$a++)
		{
			$aTemp=$sw_url2Row[$a];
			if($aTemp)
			{
				$yuyin_path_true=$cfg_yuyin_path."/".$aTemp;
				$temp2=$whilebuffer22;
				$temp2=str_replace("{sw_url2}","<a href='$yuyin_path_true' onclick=\"opfun('$bflag','$aTemp')\"><img src='$web_path/images/aag.gif' border='0'></a>",$temp2);
				$temp2=str_replace("{sid}","$bflag",$temp2);
				$temp2=str_replace("{downUrl}","<a href='$yuyin_path_true' target='_blank'>[下载]</a>",$temp2);
				$bflag++;
				$totleBuffer2.=$temp2;
			}
		}
		$temp=str_replace($whilebuffer22,$totleBuffer2,$temp);
		$totleBuffer.=$temp;
	}
	$buffer=str_replace($whilebuffer2,$totleBuffer,$buffer);
	
	include($cfg_inc_replace_path);
	echo $buffer;
	exit;
}
if($type=="yy")
{
	$totleBuffer="";
	$buffer=implode("",file($template_man_sw_yy_path));
	$sql2="select Tid from db_ivr_log where uid='$Tid' and flag=0 and state=2 order by Tid desc limit 0,1";
	$sql2Buffer=mysql_query($sql2)or die("error548");
	list($Tid2)=mysql_fetch_row($sql2Buffer);
	$whilebuffer2=Template("$template_man_sw_yy_path","<!--begin-->","<!--end-->");
	if($Tid2) {
		$sql4="select Tid,sw_url,cdate from db_ivr_rs where oid='$Tid2' order by Tid desc";
	} else {
		$sql4="select Tid,sw_url,cdate from db_ivr_rs where 1=2";
	}
	$sqlBuffer=mysql_query($sql4)or die("eeror48");
	$bflag=1;
	while(list($w_Tid,$sw_url2,$cdate2)=mysql_fetch_row($sqlBuffer))
	{
		$temp=$whilebuffer2;
		$temp=str_replace("{Tid}",$w_Tid,$temp);
		$temp=str_replace("{cdate}",$cdate2,$temp);
		$sw_url2Row=explode("|",$sw_url2);
		$swCount=count($sw_url2Row)-1; //多了一条
		$astr="";
		
		$whilebuffer22=Template("$template_man_sw_yy_path","<!--begin2-->","<!--end2-->");
		for($a=0;$a<$swCount;$a++)
		{
			$aTemp=$sw_url2Row[$a];
			if($aTemp)
			{                    
                                $aTemp=$aTemp.".wav";
				$yuyin_path_true=$cfg_yuyin_path."/".$aTemp;                                
				$temp2=$whilebuffer22;
				$temp2=str_replace("{sw_url2}","<a href='$yuyin_path_true' onclick=\"opfun('$bflag','$aTemp')\"><img src='$web_path/images/aag.gif' border='0'></a>",$temp2);                                
				$temp2=str_replace("{sid}","$bflag",$temp2);                                
				$temp2=str_replace("{downUrl}","<a href='$yuyin_path_true' target='_blank'>[下载]</a>",$temp2);
				$bflag++;
				$totleBuffer2.=$temp2;
			}	
		}
		$temp=str_replace($whilebuffer22,$totleBuffer2,$temp);
		$totleBuffer.=$temp;
	}
	$buffer=str_replace($whilebuffer2,$totleBuffer,$buffer);
	
	include($cfg_inc_replace_path);
	echo $buffer;
	exit;
}
if($type=="dj")
{
	$buffer=implode("",file($template_result_op_path));
	$sql="select Tid,oid,name,mobi,bjz_date,ejz_date,jz_type,jg_grade,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr where  Tid='$Tid' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
	list($Tid,$oid,$name,$mobi,$bjz_date,$ejz_date,$jz_type,$jg_grade,$regstate,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate)=mysql_fetch_row($sqlBuffer);
	//写入声纹注册信息
	$now = date('Y-m-d H:i:s');
	$insertSql="insert into db_ivr_log(flag,uid,type,yzdate,up_yzdate,server_state,state,sw_state,sw_url,end_yzdate,sh_state,shjg)
	values(0,'$Tid',0,'$now','$now','99999',0,'0','',NULL,0,'')";
	$insertSqlBuffer=mysql_query($insertSql)or die("error548");
	$ID=mysql_insert_id();
	$flag=sendSwReg($ID,$mobi,0,$service_tie_path);
	if($flag)
	{
		$buffer=str_replace("{tie2}","姓名：$name 手机号码：$mobi",$buffer);
		$buffer=str_replace("{tie}","声纹登记，请您耐心等待......",$buffer);
		$buffer=str_replace("{title}","声纹登记",$buffer);
		$buffer=str_replace("{img}","<img src=\"$img_path/images/001.gif\" />",$buffer);
	}
	else
	{
		$buffer=str_replace("{tie2}","姓名：$name 手机号码：$mobi",$buffer);
		$buffer=str_replace("{tie}","网络中断，登记失败",$buffer);
		$buffer=str_replace("{title}","网络中断，登记失败",$buffer);
		$buffer=str_replace("{img}","",$buffer);
		$uSql="update db_ivr_log set up_yzdate=now(),end_yzdate=now(),server_state='999',state='1',sw_state=5,sh_state=2 where Tid='$ID'";
		mysql_query($uSql)or die("error15");
		
	}
	$mTime=time();
	$m5=$m_key_md5.$ID.$mTime;
	$m5Value=md5($m5);
	
	$opurl2=$web_path."/ifrm_task.php?Tid=$ID"."&m5=$m5Value"."&t=".$mTime;
	$buffer=str_replace("{opurl2}",$opurl2,$buffer);
	include($cfg_inc_replace_path);
	echo $buffer;
	exit;
}
if($type=="rz")
{
		$buffer=implode("",file($template_result_op_path));
	$sql="select Tid,oid,name,mobi,bjz_date,ejz_date,jz_type,jg_grade,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr where  Tid='$Tid' and status>0";
	$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
	list($Tid,$oid,$name,$mobi,$bjz_date,$ejz_date,$jz_type,$jg_grade,$regstate,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate)=mysql_fetch_row($sqlBuffer);
	//写入声纹注册信息
	$now = date('Y-m-d H:i:s');
	$insertSql="insert into db_ivr_log(flag,uid,type,yzdate,up_yzdate,server_state,state,sw_state,sw_url,end_yzdate,sh_state,shjg)
	values(1,'$Tid',0,'$now','$now','99999',0,'0','',NULL,0,'')";
	$insertSqlBuffer=mysql_query($insertSql)or die("error548 ". mysql_error());
	$ID=mysql_insert_id();
	$flag=sendSwReg($ID,$mobi,1,$service_tie_path);
	if($flag)
	{
		$buffer=str_replace("{tie2}","姓名：$name 手机号码：$mobi",$buffer);
		$buffer=str_replace("{tie}","声纹认证中，请您耐心等待......",$buffer);
		$buffer=str_replace("{title}","声纹认证",$buffer);
		$buffer=str_replace("{img}","<img src=\"$img_path/images/001.gif\" />",$buffer);
	}
	else
	{
		$buffer=str_replace("{tie2}","姓名：$name 手机号码：$mobi",$buffer);
		$buffer=str_replace("{tie}","网络中断，声纹认证失败",$buffer);
		$buffer=str_replace("{title}","网络中断，声纹认证失败",$buffer);
		$buffer=str_replace("{img}","",$buffer);
		$uSql="update db_ivr_log set up_yzdate=now(),end_yzdate=now(),server_state='999',state='1',sw_state=5,sh_state=2 where Tid='$ID'";
		mysql_query($uSql)or die("error15");
		
	}
	$mTime=time();
	$m5=$m_key_md5.$ID.$mTime;
	$m5Value=md5($m5);
	
	$opurl2=$web_path."/ifrm_task.php?Tid=$ID"."&m5=$m5Value"."&t=".$mTime;
	$buffer=str_replace("{opurl2}",$opurl2,$buffer);
	include($cfg_inc_replace_path);
	echo $buffer;
	exit;
}
?>
