<?php
require("config/config_test.php");

require($client_path);
require ($cfg_inc_common_path);

$buffer=implode("",file($template_sw_path));
$sql="select a.Tid,oid,a.name,mobi,id_card,id_shebao,bjz_date,ejz_date,jz_type,jg_grade,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr a left join db_office b on a.oid = b.tid where regstate<2";
//搜索
if($_REQUEST["submit"] OR $_REQUEST["CallAll"] OR $_REQUEST["CheckAll"])
{
		$GetUrl=$_SERVER["PHP_SELF"]."?submit=ok&";
		if($_REQUEST["oname"])
		{
			$oname=$_REQUEST["oname"];
			$sql.=" and b.name like '%$oname%'";
			$GetUrl.="oname=$oname&";
		}
		
		if($_REQUEST["sname"])
		{
			$sname=$_REQUEST["sname"];
			$sql.=" and a.name like '%$sname%'";
			$GetUrl.="sname=$sname&";
		}
		
		if($_REQUEST["smobi"])
		{
			$smobi=$_REQUEST["smobi"];
			$sql.=" and mobi like '%$smobi%'";
			$GetUrl.="smobi=$smobi&";
		}

		if($_REQUEST["sid_card"])
		{
			$sid_card=$_REQUEST["sid_card"];
			$sql.=" and id_card like '%$sid_card%'";
			$GetUrl.="sid_card=$sid_card&";
		}		

		if($_REQUEST["classid"])
		{
			$classid=$_REQUEST["classid"];
			$classStr=$classid;
			$tempCid=",".$classid.",";
			$csql="select Tid from db_office where fstr like '%$tempCid%'";
			$csqlBuffer=mysql_query($csql)or die("error55");
			while(list($gTid)=mysql_fetch_row($csqlBuffer))
			{
				$classStr.=",".$gTid;
			}
			$sql.=" and oid in ($classStr)";
			$GetUrl.="classid=$classid&";
		}
		
		if($_REQUEST["sjz_type"]!="" and $_REQUEST["sjz_type"]!="999")
		{
			$sjz_type=$_REQUEST["sjz_type"];
			$sql.=" and jz_type ='$sjz_type'";
			$GetUrl.="sjz_type=$sjz_type&";
		}
		
		if($_REQUEST["sjg_grade"]!="" and $_REQUEST["sjg_grade"]!="999")
		{
			$sjg_grade=$_REQUEST["sjg_grade"];
			$sql.=" and jg_grade ='$sjg_grade'";
			$GetUrl.="sjg_grade=$sjg_grade&";
		}
		
		if($_REQUEST["sregstate"]!="" and $_REQUEST["sregstate"]!="999")
		{
			$sregstate=$_REQUEST["sregstate"];
			$sql.=" and regstate ='$sregstate'";
			$GetUrl.="sregstate=$sregstate&";
		}
		
}
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleSta=mysql_num_rows($sqlBuffer);
pageft($totleSta,$admin_page_config,$GetUrl);
$sql.=" order by Tid desc";
$sql.=" limit $firstcount,$displaypg";
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$whilebuffer=Template("$template_sw_path","<!--begin-->","<!--end-->");
$totleBuffer="";
$cnt = -1;
while(list($Tid,$oid,$name,$mobi,$id_card,$id_shebao,$bjz_date,$ejz_date,$jz_type,$jg_grade,$regstate,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate)=mysql_fetch_row($sqlBuffer))
{   $cnt = $cnt +1;
	$regstate=intval($regstate);
	$temp=$whilebuffer;
	$jg_grade=intval($jg_grade);
	$jg_gradeValue=$jg_grade_row[$jg_grade];
	$temp=str_replace("{jg_grade}",$jg_gradeValue,$temp);
	
	$regstate=intval($regstate);
	$regstateValue=$regstate_grade_row[$regstate];
	$temp=str_replace("{regstate}",$regstateValue,$temp);
	
	$temp=str_replace("{name}",$name,$temp);
	$cSql="select name from db_office where Tid='$oid'";
	$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
	list($cname2)=mysql_fetch_row($cSqlBuffer);
	$temp=str_replace("{oid}",$cname2,$temp);
	$temp=str_replace("{mobi}",$mobi,$temp);
	$temp=str_replace("{id_card}",$id_card,$temp);
	$temp=str_replace("{id_shebao}",$id_shebao,$temp);
	$temp=str_replace("{Tid}",$Tid,$temp);
	$bjz_dateRow=explode(" ",$bjz_date);
	$bjz_date1=$bjz_dateRow[0];
	
	$ejz_dateRow=explode(" ",$ejz_date);
	$ejz_date1=$ejz_dateRow[0];
	
	if($regstate==0)
	{
		$temp=str_replace("{op}","<a href='###' onclick=\"opfun('sw_op.php?type=dj&Tid=$Tid')\">登记</a>",$temp);
	}
	elseif($regstate==1)
	{
		$temp=str_replace("{op}","<a href='###'  onclick=\"opfun2('sw_op.php?type=yy&Tid=$Tid')\">语音</a>&nbsp;<a href='###'  onclick=\"opfun('sw_op.php?type=rz&Tid=$Tid')\">验证</a>&nbsp;<a onClick=\"return confirm('确认要删除？')\" href='sw_op.php?type=del&Tid=$Tid' target='ifrm'>删除</a>",$temp);
	}
	else
	{
		$temp=str_replace("{op}","",$temp);
	}
	
	
	$temp=str_replace("{bjz_date}",$bjz_date1,$temp);
	$temp=str_replace("{ejz_date}",$ejz_date1,$temp);
	$totleBuffer.=$temp;
	
	if($_REQUEST["CallAll"])
	{echo $Tid."          ";
		${"buffer" . $Tid}=implode("",file($template_result_op_path));
		${"sql" . $Tid}="select Tid,oid,name,mobi,bjz_date,ejz_date,jz_type,jg_grade,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr where  Tid='$Tid' and status>0";
		${"sqlBuffer" . $Tid}=mysql_query(${"sql" . $Tid})or die("error02".mysql_error());
		list(${"Tid" . $Tid},${"oid" . $Tid},${"name" . $Tid},${"mobi" . $Tid},${"bjz_date" . $Tid},${"ejz_date" . $Tid},${"jz_type" . $Tid},${"jg_grade" . $Tid},${"regstate" . $Tid},${"up_bdate" . $Tid},${"up_edate" . $Tid},${"dowm_bdate" . $Tid},${"dowm_edate" . $Tid})=mysql_fetch_row(${"sqlBuffer" . $Tid});
		//写入声纹注册信息
		${"insertSql" . $Tid}="insert into db_ivr_log(flag,uid,type,yzdate,up_yzdate,server_state,state,sw_state,sw_url,end_yzdate,sh_state,shjg)
		values(1,'$Tid',0,now(),now(),'99999',0,'0','',NULL,0,'')";
		${"insertSqlBuffer" . $Tid}=mysql_query(${"insertSql" . $Tid})or die("error548");
		$ID=mysql_insert_id();
		echo $ID."          ";
		${"flag" . $Tid}=sendSwReg($ID,${"mobi" . $Tid},1,$service_tie_path);
		if(${"flag" . $Tid})
		{
			${"buffer" . $Tid}=str_replace("{tie2}","姓名：${"name$Tid"} 手机号码：${"mobi$Tid"}",${"buffer$Tid"});
			${"buffer" . $Tid}=str_replace("{tie}","声纹认证中，请您耐心等待......",${"buffer" . $Tid});
			${"buffer" . $Tid}=str_replace("{title}","声纹认证",${"buffer" . $Tid});
			${"buffer" . $Tid}=str_replace("{img}","<img src=\"$img_path/images/001.gif\" />",${"buffer" . $Tid});
		}
		else
		{
            ${"buffer" . $Tid}=str_replace("{tie2}","姓名：${"name$Tid"} 手机号码：${"mobi$Tid"}",${"buffer$Tid"});
			${"buffer" . $Tid}=str_replace("{tie}","网络中断，声纹认证失败",${"buffer" . $Tid});
			${"buffer" . $Tid}=str_replace("{title}","网络中断，声纹认证失败",${"buffer" . $Tid});
			${"buffer" . $Tid}=str_replace("{img}","",${"buffer" . $Tid});
			${"uSql" . $Tid}="update db_ivr_log set up_yzdate=now(),end_yzdate=now(),server_state='999',state='1',sw_state=5,sh_state=2 where Tid='$ID'";
			mysql_query(${"uSql" . $Tid})or die("error15");
			
		}
		${"mTime" . $Tid}=time();
		$m5=$m_key_md5.$ID.${"mTime" . $Tid};
		$m5Value=md5($m5);
		
		$opurl2=$web_path."/ifrm_task_test.php?Tid=$ID"."&OTid=$Tid"."&cnt=$cnt"."&m5=$m5Value"."&t=".${"mTime" . $Tid};
		${"buffer" . $Tid}=str_replace("{opurl2}",$opurl2,${"buffer" . $Tid});
		include($cfg_inc_replace_path);
		//${"buffer" . $Tid}=str_replace("{img_path}",$img_path,${"buffer" . $Tid});
		//${"buffer" . $Tid}=str_replace("{admin_mk_name}",$admin_mk_path,${"buffer" . $Tid});
		//${"buffer" . $Tid}=str_replace("{admin_mk_path}",$admin_mk_path,${"buffer" . $Tid});
		//${"buffer" . $Tid}=str_replace("{web_path}",$web_path,${"buffer" . $Tid});
		//${"buffer" . $Tid}=str_replace("{css_path}",$css_path,${"buffer" . $Tid});
		//${"buffer" . $Tid}=str_replace("{js_path}",$js_path,${"buffer" . $Tid});
		//${"buffer" . $Tid}=str_replace("{cfg_kz_id}",$cfg_kz_id,${"buffer" . $Tid});

		echo ${"buffer" . $Tid};
//echo 1;

	}
     
	
}
$buffer=str_replace($whilebuffer,$totleBuffer,$buffer);

//矫正状态
$optionBuffer=optionStr($jz_type_row,"-1","");
$buffer=str_replace("{sjz_type}",$optionBuffer,$buffer);
//监管级别
$optionBuffer=optionStr($jg_grade_row,"-1","");
$buffer=str_replace("{sjg_grade}",$optionBuffer,$buffer);

$optionBuffer=optionStr($regstate_grade_row,"-1","");
$buffer=str_replace("{sregstate_Str}",$optionBuffer,$buffer);

$ftidNameStr=getclassStr('0');
$buffer=str_replace("{classidStr}",$ftidNameStr,$buffer);
$buffer=str_replace("{pageStr}",$pagenav,$buffer);
$buffer=str_replace("{postName}",$_SERVER['PHP_SELF'],$buffer);
include($cfg_inc_replace_path);
echo $buffer;

if($_REQUEST["CheckAll"])
	{
		$bufferNewNew=implode("",file($template_mk."/man_sw_log_test.html"));
		$sqlNewNew="select distinct g.Tid,g.uid,g.type,g.yzdate,g.up_yzdate,g.server_state,g.state,g.sw_state,g.sw_url,g.end_yzdate,g.sh_state,g.shjg,i.mobi,i.name,i.oid from db_ivr_log as g,db_ivr as i left join db_office b on i.oid = b.tid where g.uid=i.Tid and (i.Tid, g.yzdate) in (select uid, max(yzdate) from db_ivr_log group by uid)";
		
		$GetUrlNewNew=$_SERVER["PHP_SELF"]."?submit=ok&";
		if($_REQUEST["oname"])
		{
			$oname=$_REQUEST["oname"];
			$sqlNewNew.=" and b.name like '%$oname%'";
			$GetUrlNewNew.="oname=$oname&";
		}
		
		if($_REQUEST["sname"])
		{
			$sname=$_REQUEST["sname"];
			$sqlNewNew.=" and i.name like '%$sname%'";
			$GetUrlNewNew.="sname=$sname&";
		}
		
		if($_REQUEST["smobi"])
		{
			$smobi=$_REQUEST["smobi"];
			$sqlNewNew.=" and i.mobi like '%$smobi%'";
			$GetUrlNewNew.="smobi=$smobi&";
		}

		if($_REQUEST["sid_card"])
		{
			$sid_card=$_REQUEST["sid_card"];
			$sqlNewNew.=" and i.id_card like '%$sid_card%'";
			$GetUrlNewNew.="sid_card=$sid_card&";
		}		

		if($_REQUEST["classid"])
		{
			$classid=$_REQUEST["classid"];
			$classStr=$classid;
			$tempCid=",".$classid.",";
			$csql="select Tid from db_office where fstr like '%$tempCid%'";
			$csqlBuffer=mysql_query($csql)or die("error55");
			while(list($gTid)=mysql_fetch_row($csqlBuffer))
			{
				$classStr.=",".$gTid;
			}
			$sqlNewNew.=" and oid in ($classStr)";
			$GetUrlNewNew.="classid=$classid&";
		}
		
		if($_REQUEST["sjz_type"]!="" and $_REQUEST["sjz_type"]!="999")
		{
			$sjz_type=$_REQUEST["sjz_type"];
			$sqlNewNew.=" and i.jz_type ='$sjz_type'";
			$GetUrlNewNew.="sjz_type=$sjz_type&";
		}
		
		if($_REQUEST["sjg_grade"]!="" and $_REQUEST["sjg_grade"]!="999")
		{
			$sjg_grade=$_REQUEST["sjg_grade"];
			$sqlNewNew.=" and i.jg_grade ='$sjg_grade'";
			$GetUrlNewNew.="sjg_grade=$sjg_grade&";
		}
		
		if($_REQUEST["sregstate"]!="" and $_REQUEST["sregstate"]!="999")
		{
			$sregstate=$_REQUEST["sregstate"];
			$sqlNewNew.=" and i.regstate ='$sregstate'";
			$GetUrlNewNew.="sregstate=$sregstate&";
		}
		
		$sqlBufferNewNew=mysql_query($sqlNewNew)or die("error02".mysql_error());
		$totleStaNewNew=mysql_num_rows($sqlBufferNewNew);
		pageft($totleStaNewNew,$admin_page_config,$GetUrlNewNew);
		$sqlNewNew.=" order by Tid desc";
		$sqlNewNew.=" limit $firstcount,$displaypg";
		$sqlBufferNewNew=mysql_query($sqlNewNew)or die("error02".mysql_error());
		$whilebufferNewNew=Template("$template_man_sw_path","<!--begin-->","<!--end-->");
		$totleBufferNewNew="";
		while(list($Tid,$uid,$type,$yzdate,$up_yzdate,$server_state,$state,$sw_state,$sw_url,$end_yzdate,$sh_state,$shjg,$mobi,$name,$oid)=mysql_fetch_row($sqlBufferNewNew))
		{
			$tempNewNew=$whilebufferNewNew;
			$cSqlNewNew="select name from db_office where Tid='$oid'";
			$cSqlBufferNewNew=mysql_query($cSqlNewNew)or die("error548".mysql_error());
			list($cname2)=mysql_fetch_row($cSqlBufferNewNew);
			$tempNewNew=str_replace("{oid}",$cname2,$tempNewNew);
			$tempNewNew=str_replace("{name}",$name,$tempNewNew);
			$tempNewNew=str_replace("{mobi}",$mobi,$tempNewNew);
			$tempNewNew=str_replace("{yzdate}",$yzdate,$tempNewNew);
			if($type)
			{
				$tempNewNew=str_replace("{typeStr}","自动",$tempNewNew);
			}
			else
			{
				$tempNewNew=str_replace("{typeStr}","人工",$tempNewNew);
			}
			
			$state=intval($state);
			$stateValue=$sw_state_arr[$state];
			$tempNewNew=str_replace("{state}",$stateValue,$tempNewNew);
			$tempNewNew=str_replace("{Tid}",$Tid,$tempNewNew);
			

			$sw_stateValue=$sw_state_type_row[$sw_state];
			$tempNewNew=str_replace("{sw_state}",$sw_stateValue,$tempNewNew);
			//审核结果
			$sh_state=intval($sh_state);
			$sh_stateValue=$sh_state_arr[$sh_state];
			if($sh_state==0)
			{
				$tempNewNew=str_replace("{sh_state}","待审核",$tempNewNew);
			}
			else
			{
				$tempNewNew=str_replace("{sh_state}",$sh_stateValue,$tempNewNew);
			}
			$tempNewNew=str_replace("{shjg}",$shjg,$tempNewNew);
			$url="$web_path/man_sw_shenhe.php?Tid=$Tid";
			
			$urlyuyin="$web_path/sw_op.php?type=logyy&Tid=$Tid";
			$tempNewNew=str_replace("{op}","<a onclick=\"opfun2('$urlyuyin')\" href='###'>语音</a>&nbsp;&nbsp;<a href='###' onclick=\"opfun('$url')\">审核</a>",$tempNewNew);
			$totleBufferNewNew.=$tempNewNew;
		}
		$bufferNewNew=str_replace($whilebufferNewNew,$totleBufferNewNew,$bufferNewNew);

		//声纹审核结果
		$optionBufferNewNew=optionStr($sw_state_type_row,"-9999","");
		$bufferNewNew=str_replace("{sw_stateStr}",$optionBufferNewNew,$bufferNewNew);


		//声纹审核结果
		$optionBufferNewNew=optionStr($sh_state_arr,"-1","");
		$bufferNewNew=str_replace("{sh_state_Str}",$optionBufferNewNew,$bufferNewNew);
		//声纹结果
		$optionBufferNewNew=optionStr($sw_state_arr,"-1","");
		$bufferNewNew=str_replace("{state_value}",$optionBufferNewNew,$bufferNewNew);


		$ftidNameStr=getclassStr('0');
		$bufferNewNew=str_replace("{classidStr}",$ftidNameStr,$bufferNewNew);
		$bufferNewNew=str_replace("{pageStr}",$pagenav,$bufferNewNew);
		$bufferNewNew=str_replace("{postName}",$_SERVER['PHP_SELF'],$bufferNewNew);
		include($cfg_inc_replace_path);
		echo $bufferNewNew;
	}
?>
