<?php

function getFromArray($arr, $key, $defauleValue=null) {
	if(isset($arr[$key])) return $arr[$key];
	return $defauleValue;
}

//过滤参数
function trimHtml($mkStr)
{
	$mkStr=trim($mkStr);
	return $mkStr;
}

//出错跳回
function backFun($htmlPath,$info,$url,$sec=10)
{
	$buffer=implode("",file($htmlPath));
	$buffer=str_replace("{info}",$info,$buffer);
	$buffer=str_replace("{url}",$url,$buffer);
	$buffer=str_replace("{sec}",$sec,$buffer);
	return $buffer;
}
function optionStr($arr,$num,$name){
	$option ='';
	if(is_array($arr)){
		foreach($arr as $key => $val){
			if($key == $num){
				$option.="<option value='$key' selected>$val</option>";
			}else{
				$option.="<option value='$key'>$val</option>";
			}
		}
	}
	return $option;
}

//---替换查询结果表（list.html）----
//调用格式 Template([模板名]) 
function Template($TemplateName,$beginflag,$endflag) 
{
	$TempBuffer = '';
	$Begin = 0;
	//模板载入数组
	$FileRow=File("$TemplateName");
	$count = count($FileRow);
	For($I=0;$I<$count;$I++)
	{
		//判断条件是否符合，如果符合条件将模板读入变量
		if($I>0 && eregi($beginflag,$FileRow[$I-1],$Head)!="")
		{
			$Begin=1;
		}
		if($Begin==1)
		{
			$TempBuffer.=$FileRow[$I];
		}
		if($I<$count-1 && eregi($endflag,$FileRow[$I+1],$Head)!="")
		{
			$Begin=0;
		}
		
	}
	//begin与end中间部分
	return $TempBuffer;
}



//获取下拉菜单公共函数
function getOptionListStr($arr, $value, $valueField, $textField)
{
	$str = "";

    foreach($arr as $item)
    {
        $optValue = isset($item[$valueField]) ? $item[$valueField] : null;
        if($optValue === null) continue;
        $optText = isset($item[$textField]) ? $item[$textField] : null;
        if($optValue==$value)
        {
            $selectedType="selected";
        }
        else
        {
            $selectedType="";
        }
        $str = $str."<option $selectedType value=\"$optValue\">$optText</option>\n";
    }
	return $str;
}
//获取下拉菜单公共函数 从1开始
function getListArr1($arr_name,$id,$limitNum)
{
	foreach ($arr_name as $k => $v)
	{
		if($id==$k)
		{
			$str.="<option value='$k' selected > $v </option>";
		}
		else
		{
			$str.="<option value='$k'> $v </option>";
		}
	}
	return $str;
}
function fun_empty($valuestr,$errstr)
{
	IF($valuestr=="" OR $valuestr=="0")
	{
		echo "<script language='javascript'>alert(' $errstr ');history.go(-1);</script>";
		exit;
	}
}
function Template12($TemplateName) 
{
	$TempBuffer = '';
	//模板载入数组
	$FileRow=File("$TemplateName");
	For($I=0;$I<count($FileRow);$I++)
	{
		//判断条件是否符合，如果符合条件将模板读入变量
		if(eregi("<!--begin-->",$FileRow[$I-1],$Head)!="")
		{
			$Begin=1;
		}
		if($Begin==1)
		{
			$TempBuffer.=$FileRow[$I];
		}
		if(eregi("<!--end-->",$FileRow[$I+1],$Head)!="")
		{
			$Begin=0;
		}
		
	}
	//begin与end中间部分
	return $TempBuffer;
}
//判断只能为数字或者字母
function checkNL($Str)
{
		if(ereg("^[0-9a-zA-Z]+$",$Str)) 
		return 1;
		else 
		return 2;
}

//判断只能为数字或者字母
function checkNL_num($Str)
{
		if(ereg("^[0-9]+$",$Str)) 
		return 1;
		else 
		return 2;
}

//判断是否为中文
Function CheckCnStrNew($Variable)
{
	if(eregi("^[^\4E00-\9Fa5]+$","$Variable"))
	{
		return 1;
	}
	else
	{
		return 2;
	}
}

//图片后缀格式
function judgeImgEnd($str1,$type=0)
{
	$picMimeType=array(
	"image/jpeg",
	"image/gif",
	"image/x-png",
	"image/png",
	"image/pjpeg",
	"image/bmp",
	"image/cgm",
	"image/svg+xml",
	"image/tiff",
	"image/tiff-fx",
	"image/x-icon",
	"image/x-xbitmap",
	"image/x-xpixmap",
	"image/x-xwindowdump",
	"image/vnd.wap.wbmp"
	);//图片类型

	$congfigStr=",".$str1.",";
	$congfigStrAll=",gif,jpg,bmp,png,";
	if($type==0)
	{
		if(eregi($congfigStr,$congfigStrAll))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	else
	{
		if(in_array($str1,$picMimeType))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
}

/*
//中文反编译
function unescape($str) 
{ 
         $str = rawurldecode($str); 
         preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U",$str,$r); 
         $ar = $r[0]; 
         foreach($ar as $k=>$v) { 
                  if(substr($v,0,2) == "%u") 
                           $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,-4))); 
                  elseif(substr($v,0,3) == "&#x") 
                           $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,3,-1))); 
                  elseif(substr($v,0,2) == "&#") { 
                           $ar[$k] = iconv("UCS-2","GBK",pack("n",substr($v,2,-1))); 
                  } 
         } 
         return join("",$ar); 
}
*/



//特殊文字处理
function changefunfuhao($stdr)
{
	$stdr=str_replace("\\\"","\"",$stdr);
	$stdr=str_replace("“","\"",$stdr);
	$stdr=str_replace("”","\"",$stdr);
	$stdr=str_replace("！","!",$stdr);
	if($stdr)
	{
		$restr= preg_replace('/\xa3([\xa1-\xfe])/e', 'chr(ord(\1)-0x80)',$stdr);   
		return $stdr;
	}
	else
	{
		return $stdr;
	}  
}


function cutstr($string, $length, $dot = ' ...',$charset='utf-8') {

	if(strlen($string) <= $length) {
		return $string;
	}
	//对字符段中的特殊符号进行非html处理
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

	$strcut = '';
	if(strtolower($charset) == 'utf-8') {//先对charset小写处理，再进行比较

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);//字符串中每一个单字符的ASCII值赋值给$t
			//下面这个处理是用来干嘛的？查ASCII表得知。
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			//这个是判断是否为水平制表符、换行符或者键盘上所有的字符
				$tn = 1; $n++; $noc++;
			//以下都是其他字符的判断了
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		//这个循环的处理，我个人理解是因为多字符的占位不同，并不能一概而论的用substr简单截断。根据ASCII值来判断截断多少位是可取的。
		if($noc > $length) {
			$n -= $tn;
		}
		//用substr函数截断处理
		$strcut = substr($string, 0, $n);

	} else {
		//以下不是utf8格式的截断处理
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	//还原字符串中的特殊字符
	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	
	return $strcut.$dot;//输出截断后的字符串
}
function getclassStr($a, $where="")
{
	$optionBuffer="";
    if($where) {
        $where = " and $where";
    }

	$selectSql="select Tid,name from db_office where ftid=0 and status>0$where";
	$selectSqlBuffer=mysql_query($selectSql)or die("error654");
	while(list($office_Tid,$office_name)=mysql_fetch_row($selectSqlBuffer))
	{
		if($a==$office_Tid)
		{
			$optionBuffer.="<option value='$office_Tid' selected=\"selected\" >$office_name </option>";
		}
		else
		{
			$optionBuffer.="<option value='$office_Tid'>$office_name </option>";
		}
		
		$selectSql2="select Tid,name from db_office where ftid=$office_Tid and status>0$where";
		$selectSqlBuffer2=mysql_query($selectSql2)or die("error654");
		while(list($office_Tid2,$office_name2)=mysql_fetch_row($selectSqlBuffer2))
		{
			if($a==$office_Tid2)
			{
				$optionBuffer.="<option value='$office_Tid2' selected=\"selected\"  >|--$office_name2 </option>";
			}
			else
			{
				$optionBuffer.="<option value='$office_Tid2'>|--$office_name2 </option>";
			}
			
				$selectSql3="select Tid,name from db_office where ftid=$office_Tid2 and status>0$where";
				$selectSqlBuffer3=mysql_query($selectSql3)or die("error654");
				while(list($office_Tid3,$office_name3)=mysql_fetch_row($selectSqlBuffer3))
				{
					if($a==$office_Tid3)
					{
						$optionBuffer.="<option value='$office_Tid3' selected=\"selected\"  >|----$office_name3 </option>";
					}
					else
					{
						$optionBuffer.="<option value='$office_Tid3'>|----$office_name3 </option>";
					}
					
						$selectSql4="select Tid,name from db_office where ftid=$office_Tid3 and status>0$where";
						$selectSqlBuffer4=mysql_query($selectSql4)or die("error654");
						while(list($office_Tid4,$office_name4)=mysql_fetch_row($selectSqlBuffer4))
						{
							if($a==$office_Tid4)
							{
								$optionBuffer.="<option value='$office_Tid4' selected=\"selected\" >|------$office_name4 </option>";
							}
							else
							{
								$optionBuffer.="<option value='$office_Tid4'>|------$office_name4 </option>";
							}
						}
				}	
		}
	}
	return $optionBuffer;
}

//时间转化
function secChangeTime($a)
{
	$aRow=explode(" ",$a);
	$str1=$aRow[0];
	$str2=$aRow[1];
	$str1Row=explode("-",$str1);
	$day=$str1Row[2];
	$year=$str1Row[0];
	$month=$str1Row[1];
	
	$str2Row=explode(":",$str2);
	
	$s=intval($str2Row[2]);
	$h=intval($str2Row[0]);
	$m=intval($str2Row[1]);
	$mkValue=mktime("$h","$m","$s","$month","$day","$year");
	return $mkValue;
}

function logInfo($msg) {
	common_log("info", $msg);
}


function logError($msg) {
	common_log("error", $msg);
}


function common_log($level, $msg) {
	global $config_log_path;
	$logFile = "$config_log_path/" . date('Y-m-d') . ".log";
	$fp = fopen($logFile, "a");
	if(!$fp) return false;
	$level = strtoupper($level);
	$logstr = date('Y-m-d H:i:s') . " $level $msg";
	if($level=="ERROR") {
		$trace = debug_backtrace(false);
		$trace = array_slice($trace, 0, 5);
		$logstr .= " trace: " . json_encode($trace);
	}
	fwrite($fp, "$logstr\n");
	fclose($fp);
}

?>
