<?php
require("config/config.php");
require ($cfg_inc_common_path);

$myAreas = getManagerAreas($sin_uid, $sin_manager);

$buffer=implode("",file($template_man_xinxi_path_imp));
if($_POST['add'])
{	 		
	$sjg=trimHtml($_POST['sjg']);	   
	if(!$sjg)
	{
		echo "<script>alert('所属地区必须选择！');</script>";
		exit;		 
	}
	if($_POST['leadExcel'] == "true")
	{
		$filename = $_FILES['inputExcel']['name'];
		$tmp_name = $_FILES['inputExcel']['tmp_name'];
		if(!$filename)
		{
			echo "<script>alert('必须选择导入文件！');</script>";
			exit;
			 
		}
		else
			$msg = uploadFile($filename,$tmp_name,$sjg);
		echo "<script>alert('$msg');</script>";
		exit;		
	}
	else
	{
		echo "<script>alert('必须选择导入文件！');</script>";
		exit;
	}
	/* 
	$sql="insert into db_ivr(oid,name,mobi,id_card,id_shebao,bjz_date,ejz_date,jz_type,jg_grade,up_bdate,up_edate,dowm_bdate,dowm_edate,regstate,flag,ydate)values('$sjg','$name','$mobi','$id_card','$id_shebao','$bjz_date','$ejz_date','0','0','0','0','0','0','0',1,now())";
	mysql_query($sql)or die("error");
	mysql_close();*/
	//echo "<script>alert('导入成功!');parent.location='$web_path/man_xinxi.php'</script>";
	exit;	
	 
} 
//导入Excel文件
function uploadFile($file,$filetempname,$myoid) 
{
 	//自己设置的上传文件存放路径
    $filePath = 'upFile/';
    $msg = "test";   	
	 
    //下面的路径按照你PHPExcel的路径来修改
	require 'PHPExcel/PHPExcel/Reader/Excel5.php';
	$objReader = new PHPExcel_Reader_Excel5;	   
	$count=0;
	$all=0;
	//注意设置时区
    $time=date("y-m-d-H-i-s");//去当前上传的时间 
    //获取上传文件的扩展名
    $extend=strrchr ($file,'.');
    //上传后的文件名
    $name=$time.$extend;
    $uploadfile=$filePath.$name;//上传后的文件名地址 
    //move_uploaded_file() 函数将上传的文件移动到新位置。若成功，则返回 true，否则返回 false。
    $result=move_uploaded_file($filetempname,$uploadfile);//假如上传到当前目录下
    //echo $result;
    if($result) //如果上传文件成功，就执行导入excel操作
    {		
		// include "conn.php";		
        $objPHPExcel = $objReader->load($uploadfile); 
		 
  	    $objWorksheet = $objPHPExcel->getSheet(0); //->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); 
        echo 'row='.$highestRow;
        echo "<br>";
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
        echo 'col='.$highestColumnIndex;
        echo "<br>";
		if($highestColumnIndex<3)
		{
			return "error: 文件列数 <3.";
		}
        $telnos=array(); 
		$accountSql="select mobi from db_ivr where status>0";
		$accountSqlBuffer=mysql_query($accountSql)or die("error776".mysql_error());
		if(mysql_num_rows($accountSqlBuffer)>0)
		{
			while (list($telno)=mysql_fetch_row($accountSqlBuffer))
			{
				$telnos[]=$telno;
			}
		}
		
		$stmt = "insert into db_ivr(`name`, `id_card`, `mobi`,oid,regstate) values ";
		$sql = $stmt;
			
        for ($row = 2;$row <= $highestRow;$row++) 
        {			
            $strs=array();
            //注意highestColumnIndex的列数索引从0开始
            for ($col = 0;$col < $highestColumnIndex;$col++)
            {
                $strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }  
			$hmlen=strlen($strs[2]);		
			if($hmlen!=7 && $hmlen!=11 && $hmlen!=12) {
			//	return "导入 {$strs[2]} error telno!";
				continue;
			}
			
			$find=0;			
			
			foreach ($telnos as $i => $value) {
				if($value==$strs[2]) {$find=1;break;}
			}
			
			if($find)
			{
				//return "导入 {$strs[2]} error!";
				continue;
			}
			else
			{	
				$telnos[]=$strs[2];
				$sql =$sql."('{$strs[0]}','{$strs[1]}','{$strs[2]}',$myoid,0),";
			//$msg=$msg .$sql;           
			/*   if(!mysql_query($sql))
				{
					return   'sql insert error!';
				}*/
				$count++;
				if($count>=1000)
			    {
					$all=$all+$count;
			        $count = 0;
					//砍掉最后一个逗号;
			        $sql = rtrim($sql, ',');
			        $sql .= ";";
		            $sqls[] = $sql;
			        $sql = $stmt;
			    }
			 }
        }
		if($count>0 && $count<1000)
		{
			    //砍掉最后一个逗号;
			    $sql = rtrim($sql, ',');
			    $sql .= ";";
				$sqls[] = $sql;
				$all=$all+$count;
		}
		
		if($all>0)
		{
			foreach($sqls as $key => $sql)
					mysql_query($sql);
		}
		$msg = "成功导入 $all 个号码!";
    }
    else
    {
       $msg = "导入 error!";
    } 
    return $msg;
}
$sql="select Tid,oid,name,mobi,id_card,id_shebao,regstate,up_bdate,up_edate,dowm_bdate,dowm_edate from db_ivr where status>0";
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$totleSta=mysql_num_rows($sqlBuffer);
pageft($totleSta,$admin_page_config,$GetUrl);
$sql.=" order by Tid desc";
$sql.=" limit $firstcount,$displaypg";
$sqlBuffer=mysql_query($sql)or die("error02".mysql_error());
$whilebuffer=Template("$template_man_xinxi_path","<!--begin-->","<!--end-->");
$totleBuffer="";
while(list($Tid,$oid,$name,$mobi,$id_card,$id_shebao,$regstate,$up_bdate,$up_edate,$dowm_bdate,$dowm_edate)=mysql_fetch_row($sqlBuffer))
{
	$temp=$whilebuffer;
	$temp=str_replace("{name}",$name,$temp);
	
	$cSql="select name from db_office where Tid='$oid' and status>0";
	$cSqlBuffer=mysql_query($cSql)or die("error548".mysql_error());
	list($cname2)=mysql_fetch_row($cSqlBuffer);
	$temp=str_replace("{oid}",$cname2,$temp);
	$temp=str_replace("{mobi}",$mobi,$temp);
	$temp=str_replace("{Tid}",$Tid,$temp);
	$totleBuffer.=$temp;
}
$buffer=str_replace($whilebuffer,$totleBuffer,$buffer);


//=========添加
$buffer=str_replace("{postName}",$_SERVER['PHP_SELF'],$buffer);

if($myAreas) {
    $ftidNameStr=getclassStr($_REQUEST["classid"], "Tid in(".implode(",", $myAreas).")");
} else {
    $ftidNameStr=getclassStr($_REQUEST["classid"], '1=2');
}

$buffer=str_replace("{sjgDStr}",$ftidNameStr,$buffer);
include($cfg_inc_replace_path);
echo $buffer;
?>
