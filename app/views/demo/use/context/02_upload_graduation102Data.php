<?
##########################################################################################
#
# filename: upload_graduation102Data.php
# function: 上傳102學年度應屆畢業師資生資料
#
# 維護者  : 周家吉
# 維護日期: 2013/11/21
#
##########################################################################################

	// 顯示所有的錯誤、警告(E_ALL)，執行時期的提醒(E_NOTICE)
	session_start();
	
	if (!($_SESSION['Login'])){
		header("Location: ../../index.php");
	}	
	
	//資料庫連結，及存取之資料表
		include_once('../../public/logincheck.php') ; //newcid by use
		include_once("/home/leon/data/edu/config/use_102/setting.inc.php");  
		require("/home/leon/data/edu/config/ftp.inc.php");
	
		$funname ='edu_102/upload/upgraduation102Data_2003.php';
		$serverdir ='/home/leon/data/edu/data/graduation102/';
	
		$tb_name='[tted_edu_102].[dbo].[graduation102_userinfo] ';
		date_default_timezone_set('Asia/Taipei'); // 調整時區，不然時間會少八小時
		//取得使用者登入ip
		$ip = getenv("REMOTE_ADDR");		
		$validation = 0;		
		$now = date("Ymd-His");
		
		//表單資料
		$memo = $_POST['memo'];
		$contact  = $_POST['contactinfo'];
		//取得使用者登入ip
		$ip = getenv("REMOTE_ADDR");		
		$sch_id=$_SESSION['sch_id100'];//學校代號
		$name=$_SESSION['name'];//承辦人姓名
		$account=$_SESSION['account'];//登入帳號	
				
		$InsertStr="";// 檢查匯入資料(初值)
		$error_pstr ="";// 錯誤匯入資料(初值)
		$error_str="";// 錯誤匯入資料(初值)
		
		$status_db_str="";// db匯入資料(初值)
		
		$insert_count=0;// 匯入筆數(初值)
		$delete_count=0;// 錯誤筆數(初值)
		
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>上傳101學年度應屆畢業師資生資料</title>
</head>
<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white" onLoad="document.form1.submit()"> 
<?		

	if ($_FILES["sfile1"]["error"] == 0){		
			//處理要寫入的參數
			$sfilename = stripslashes($_FILES['sfile1']['name']);
			$sfilename = $sch_id."_".$now."_".$sfilename;
			
			$sdestination = "$serverdir".$sfilename;
			
			//將上傳的檔案寫入伺服器內
			move_uploaded_file($_FILES["sfile1"]["tmp_name"],"$sdestination");
			
			$sql = new mod_db();
			$query_str = "Insert into [tted_edu_102].[dbo].[upload102] (school,name,account,memo,contact,filename,ip,type) 
			Values ('".$sch_id."','".$name."','".$account."','".$memo."','".$contact."','".$sfilename."','".$ip."','4')";
			$upload_query=$sql->query("$query_str");			
			$sql->disconnect();
				
			if ($_FILES["file"]["error"] ==0){			
				 $validation = 1; //上傳成功
			}else{
				$validation = 2;//上傳失敗
			}
		}

	
	if($validation == 1){
			
		require_once("../../public/Excel/reader.php");
		$Import_Sheet = new Spreadsheet_Excel_Reader(); //第一個儲存格$Import_Sheet->sheets[0]['cells'][1][1] //[row][col]
		$Import_Sheet->setOutputEncoding('BIG5');
		$Import_Sheet->read($sdestination);
		$Import_RowCount = $Import_Sheet->sheets[0]['numRows'];
		$Import_ColCount = $Import_Sheet->sheets[0]['numCols'];// 取得總行數$Import_RowCount,總列數$Import_ColCount
		
		// 去除空白列，並計算匯入檔案之總列數，$Import_NewColCount
		/*
		$Import_NewColCount = 0;
		for ($count = 1; $count <= $Import_ColCount; $count++) { 
			if(rtrim(ltrim($Import_Sheet->sheets[0]['cells'][1][$count])) != ""){ 
				$Import_NewColCount +=1;
			}else{
				break;
					}	
		}
		*/
		$Import_NewColCount=22;
		// 去除空白行，並計算匯入檔案之總行數，$Import_NewRowCount
		$Check_Null = 0;
		$Import_NewRowCount = 0;
		for ($rcount = 1; $rcount <= $Import_RowCount; $rcount++) { 
			for ($lcount = 1; $lcount <= $Import_NewColCount; $lcount++) { 
				if(rtrim(ltrim($Import_Sheet->sheets[0]['cells'][$rcount][$lcount])) == ""){
					$Check_Null += 1; 
					}
				}
				if($Check_Null != $Import_NewColCount){
					$Import_NewRowCount +=1;
				}
				$Check_Null = 0;
			}

		// 動態宣告陣列大小
		$check_error = array('0');
		$check_error = array_pad($check_error, $Import_NewColCount, '0');
		$error_row =0;
		
	//進行 excel 檔案讀行 
	for ($i = 4; $i <= $Import_NewRowCount; $i++) { 
		
		$check_error = array();
		$datalength = array();
		/*
		for($step =2;$step <=22;$step++){
			$temp = strlen(str_replace("?","",str_replace("'","",rtrim(ltrim($Import_Sheet->sheets[0]['cells'][$i][$step])))));
			$strlen = $step.' _ '.$temp.'<br>';
			echo $strlen;
		}		
		exit;
		*/		
		
		//外加 19欄
		//$temp = str_replace("?","",str_replace("'","",rtrim(ltrim($Import_Sheet->sheets[0]['cells'][$i][$step]))));
			$temp =$Import_Sheet->sheets[0]['cells'][$i][19];
			$templength = strlen($temp);
			
			//判斷欄位數是否存在資料，有資料datalength為0，否則為1，並將欄位資料寫入datalist[tmp]中
			if($templength==0){
				$datalength=0;				
			}else{
				$datalength=1;
				$datalist[19]=$temp;
			}
			
			if ($datalength!=0){
				if (preg_match("/^[0-9]+$/i", $datalist[19])) {	
					;	
				}else{
					$check_error[$step]=1;
				}
			}else{
				$check_error[$step]=2;
			}
		
		
		for($step =2;$step <=22;$step++){
		
			//$temp = str_replace("?","",str_replace("'","",rtrim(ltrim($Import_Sheet->sheets[0]['cells'][$i][$step]))));
			
			$temp =str_replace("?","",str_replace("'","",trim($Import_Sheet->sheets[0]['cells'][$i][$step])));
			$templength = strlen($temp);
			
			//判斷欄位數是否存在資料，有資料datalength為0，否則為1，並將欄位資料寫入datalist[tmp]中
			if($templength==0){
				$datalength=0;				
			}else{
				$datalength=1;
				$datalist[$step]=$temp;
			}
			
			
			// 檢查欄位開始-------------------------------------------------------------------
			// ** $check_error = 1 有問題, $check_error = 2 無資料, $check_error = 0 正確無誤 **			
			
			//學校代碼 2
			if($step ==2){
				if ($datalength!=0){
					$datalist[$step]=$sch_id;
				}else{
					$check_error[$step]=2;
				}
			}
			
			//學號 3 科系所代碼 5 科系中文名稱 6 中文姓名 8 戶籍郵遞區號 11 戶籍地址 12			
			if($step ==3 || $step ==5 || $step ==6 ||$step ==8 ||$step ==11 ||$step ==12 ){
				if ($datalength!=0){
					;
				}else{
					$check_error[$step]=2;
				}
			}
			
			//入學年度4欄
			if($step ==4){
				if ($datalength!=0){
					if (preg_match("/^[0-9]+$/i", $datalist[$step])) {	
						;	
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
			
		
			
			//身分證字號 9欄
			if($step ==9){
				if($datalist[19] == '0' || $datalist[19] =='1' || $datalist[19]=='2' || $datalist[19]=='5' || $datalist[19]=='6' || $datalist[19]=='12' || $datalist[19]=='13'){
					$check_stdidnumber=checkid($datalist[$step]);
				}else if($datalist[19]=='3'|| $datalist[19]=='4' || $datalist[19]=='7' || $datalist[19]=='8' || $datalist[19]=='9'|| $datalist[19]=='10' || $datalist[19]=='11'){
				
					$check_stdidnumber= true;
				}else $check_stdidnumber==false;				
					if ($datalength!=0){
						if ($check_stdidnumber==true) {
							;	
						}else{
							$check_error[$step]=1;
						}
					}else{
							$check_error[$step]=2;
					}
			}
					
			
			//電子郵件信箱10欄
			if($step ==10){
				if ($datalength!=0){
					;
				}else{
					$check_error[$step]=2;
				}
			}
			
			//學制別7欄
			if($step ==7){
				if ($datalength!=0){
					if (eregi("^([1-6]{1})$",$datalist[$step])) {
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
			
			//連絡電話 13
			if($step ==13){			
				if ($datalength!=0){
					if (preg_match("/^[0-9]+$/", $datalist[$step])==1) {					
						;	
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
			
			
			//入學方式14欄
			if($step ==14){
				if ($datalength!=0){
					if (preg_match("/^[0-9]+$/i", $datalist[$step])) {	
						;	
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
			//具幼稚園師資職前教育課程修課資格15欄
			//具國民小學師資職前教育修課資格16欄
			//具中等學校師資職前教育修課資格17欄
			//具特殊教育師資職前教育課程修課資格18欄
			if($step ==15  ||$step ==16  ||$step ==17 ||$step ==18){
				if ($datalength!=0){
					if (eregi("^([0-4]{1})$",$datalist[$step])) {
						;	
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
			//出生年20欄	
			//echo $step.'<br>';
			if($step ==20){
				if ($datalength!=0){
					if (eregi("^([0-9]{4})$",$datalist[$step])) {
						;	
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
			//原住民別21
			//性別22
			if($step ==21 || $step ==22){
				if ($datalength!=0){
					if (eregi("^[1-2]{1}$",$datalist[$step])) {
						;	
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
		} 
		//var_dump ($datalist);
		//echo '<br>';  
		//var_dump ($check_error);
		
		// for step 結束 檢查欄位結束-------------------------------------------------------------------
		
		$count_error=0;
		
		if($datalist[19] == '0' || $datalist[19] =='1' || $datalist[19]=='2' || $datalist[19]=='5' || $datalist[19]=='6' || $datalist[19]=='12' || $datalist[19]=='13'){
				$newcid = createnewcid($datalist[9]); 
			}else if($datalist[19]=='3'|| $datalist[19]=='4' || $datalist[19]=='7' || $datalist[19]=='8' || $datalist[19]=='9'|| $datalist[19]=='10' || $datalist[19]=='11'){
				$newcid = $datalist[9]; 
			}
		//echo $newcid;exit;
		
		$value='';
		for($step =2;$step <=22;$step++){
			$count_error=$count_error+$check_error[$step];
			
			//產出SQL Command
			$value.= "'".$datalist[$step]."'";
			if($step!=22){
				$value.= ",";
			}
			else if($step==22){
				$value.= ",'".$newcid."'";
			}	
		}
		///echo $value;
		//echo  'count_error : '.$count_error .'</br>';exit;

		if ($count_error>0){
					
				// 顯示欄位錯誤訊息開始-------------------------------------------------------------------
				
				if ($i == 4){
				$error_str = "<table width=1500' align='center'  cellpadding='0' cellspacing='0' border='1'>";
				$error_str .= "<tr bgcolor=#DCE2EE>";

				$error_str .= "	<td align=\'center\'><b>學校代碼</b></td>
								<td align=\'center\'><b>學號</b></td> 
								<td align=\'center\'><b>入學學年度</b></td> 
								<td align=\'center\'><b>科系所代碼</b></td> 
								<td align=\'center\'><b>科系中文名稱</b></td> 
								<td align=\'center\'><b>學制別</b></td> 
								<td align=\'center\'><b>中文姓名</b></td> 
								<td align=\'center\'><b>身分證字號</b></td> 
								<td align=\'center\'><b>電子郵件信箱</b></td> 
								<td align=\'center\'><b>戶籍郵遞區號</b></td> 
								<td align=\'center\'><b>戶籍地址</b></td> 
								<td align=\'center\'><b>連絡電話</b></td> 
								<td align=\'center\'><b>入學方式</b></td> 
								<td align=\'center\'><b>具幼稚園師資職前教育課程修課資格</b></td> 
								<td align=\'center\'><b>具國民小學師資職前教育修課資格</b></td> 
								<td align=\'center\'><b>具中等學校師資職前教育修課資格</b></td> 
								<td align=\'center\'><b>具特殊教育師資職前教育課程修課資格</b></td>
								<td align=\'center\'><b>外加名額</b></td> 
								<td align=\'center\'><b>出生年</b></td> 
								<td align=\'center\'><b>原住民別</b></td> 
								<td align=\'center\'><b>性別1</b></td> ";
				$error_str .= "</tr>";		 
				}
				
				if ($i%4==1)
					$error_str .= "<tr bgcolor=#FFFFFF >";
				else
					$error_str .= "<tr bgcolor=#F5F8FD >";

				for($step =2;$step <=22;$step++){
					if ($check_error[$step]==1){
						$error_str .= "<td><font color=red>".$datalist[$step]."</font></td>";
						}
					else if ($check_error[$step]==2){
						$error_str .= "<td><font color=red>無資料</font></td>";
						}
					else{
						$error_str .= "<td>".$datalist[$step]."</td>";
						}
				}
				if ($i == $Import_NewRowCount){
					$error_str .= "</table>";
				}

				$error_row +=1; //錯誤計數

	// 顯示欄位錯誤訊息結束-------------------------------------------------------------------				
		}
		else{
			if ($i == 4){
				$error_str = "<table width=1500' align='center'  cellpadding='0' cellspacing='0' border='1'>";
				$error_str .= "<tr bgcolor=#DCE2EE>";
				$error_str .= "	<td align=\'center\'><b>學校代碼</b></td>
								<td align=\'center\'><b>學號</b></td> 
								<td align=\'center\'><b>入學學年度</b></td> 
								<td align=\'center\'><b>科系所代碼</b></td> 
								<td align=\'center\'><b>科系中文名稱</b></td> 
								<td align=\'center\'><b>學制別</b></td> 
								<td align=\'center\'><b>中文姓名</b></td> 
								<td align=\'center\'><b>身分證字號</b></td> 
								<td align=\'center\'><b>電子郵件信箱</b></td> 
								<td align=\'center\'><b>戶籍郵遞區號</b></td> 
								<td align=\'center\'><b>戶籍地址</b></td> 
								<td align=\'center\'><b>連絡電話</b></td> 
								<td align=\'center\'><b>入學方式</b></td> 
								<td align=\'center\'><b>具幼稚園師資職前教育課程修課資格</b></td> 
								<td align=\'center\'><b>具國民小學師資職前教育修課資格</b></td> 
								<td align=\'center\'><b>具中等學校師資職前教育修課資格</b></td> 
								<td align=\'center\'><b>具特殊教育師資職前教育課程修課資格</b></td>
								<td align=\'center\'><b>外加名額</b></td> 
								<td align=\'center\'><b>出生年</b></td> 
								<td align=\'center\'><b>原住民別</b></td> 
								<td align=\'center\'><b>性別2</b></td> ";
				$error_str .= "</tr>";	 
			$error_str .= "<tr>	<td colspan =21 align=\'center\'><b>資料無誤</b></td> </tr>";

			}
						
			if ($i == $Import_NewRowCount){
				$error_str .= "</table>";
			}
		
			$sql_exist = new mod_db();
			$sql_str ="SELECT newcid FROM $tb_name WHERE newcid = '$newcid'";
			//echo $sql_str;exit;
			$obj_ck_exist=$sql_exist->objects("$sql_str");
			$sql_exist->disconnect();	
			//memo = 1 新增  memo = 2 修改  memo = 3 刪除 memo = 4 完整名單 obj_ck_exist 已存在相同名單進行upadte						

			if($obj_ck_exist->newcid ==''){
			//echo '增加'.'</br>' ;
				$InsertStr.="Insert into $tb_name([uid],[stdid],[yearenroll],[udepcode],[udepname],[stdschoolsys],[stdname],[stdidnumber],[stdemail],[stdregzipcode],[stdregaddr],[tel],[wayenroll],[childprogram],[priprogram],[secprogram],[speprogram],[other],[birthyear],[aboriginal],[gender],[newcid]) 
							values (".$value.")\n ;";	
				$InsertStr.="Insert into [tted_edu_102].[dbo].[graduation102_id] (stdidnumber,newcid) Values ('".$datalist[9]."','".$newcid."')\n ;";		
				$InsertStr.="Insert into [tted_edu_102].[dbo].[graduation102_pstat](newcid) Values ('".$newcid."')\n ;";				
				$insert_count +=1;			
				
			}elseif($obj_ck_exist->newcid == $newcid){
			//echo '更新'.'</br>' ;
				$InsertStr.= "UPDATE $tb_name SET 	[uid]='".$datalist[2]."',
													[stdid]='".$datalist[3]."',
													[yearenroll]='".$datalist[4]."',
													[udepcode]='".$datalist[5]."',
													[udepname]='".$datalist[6]."',
													[stdschoolsys]='".$datalist[7]."',
													[stdname]='".$datalist[8]."',
													[stdemail]='".$datalist[10]."',
													[stdregzipcode]='".$datalist[11]."',
													[stdregaddr]='".$datalist[12]."',
													[tel]='".$datalist[13]."',
													[wayenroll]='".$datalist[14]."',
													[childprogram]='".$datalist[15]."',
													[priprogram]='".$datalist[16]."',
													[secprogram]='".$datalist[17]."',
													[speprogram]='".$datalist[18]."',
													[other]='".$datalist[19]."',
													[birthyear]='".$datalist[20]."',
													[aboriginal]='".$datalist[21]."',
													[gender]='".$datalist[22]."'
													 where newcid='".$newcid."'\n ;";									
				$insert_count +=1;				
			}elseif($memo == "3"){
			//echo '刪除'.'</br>' ;
				$InsertStr.="delete from $tb_name where newcid='".$newcid."'\n";
				$InsertStr.="delete from [tted_edu_102].[dbo].[graduation102_id] where newcid='".$newcid."'\n";
				$InsertStr.="delete from [tted_edu_102].[dbo].[graduation102_pstat] where newcid='".$newcid."'\n";
				$delete_count +=1;
			}	
		}			
	}
	//完成 excel 檔案讀行 	
	if ($error_row>0){
			$status_db_str.="您有錯誤資料共 ".$error_row." 筆資料";
			$error_pstr ="<div><font color=red>※".$status_db_str."</font></div>";
?>				
			<script language="javascript">
				alert('您匯入的資料有誤，請參考頁面上錯誤訊息修正');
			</script>
<?php			
		}
		elseif ($error_row==0 && strlen($InsertStr)==0){
?>
			<script language="javascript">
				alert('匯入錯誤:檔案寫入錯誤');
			</script>
<?php		
		}
				
	if (strlen($InsertStr)!=0){
		if($memo == "3"){
?>
			<script language="javascript">
				var delete_str = "目前錯誤資料共 "+ <?php echo $error_row ?> + "筆，欲【刪除】資料共" + <?php echo $delete_count ?> + "筆資料";
				alert(delete_str);
			</script>
<?php			
		}else{
?>
			<script language="javascript">
				var str = "目前錯誤資料共 "+ <?php echo $error_row ?> + "筆，欲【新增/更新】資料共" + <?php echo $insert_count ?> + "筆資料";
				alert(str);
					// if(window.confirm(str) == false)
					// {
					// location.replace('UploadData_Hedu.php');
					// }
			</script>
<?php						
			}
			$sql = new mod_db();	
			$InsertStr = str_replace("?","'",$InsertStr);	
			$import_query=$sql->query($InsertStr); //執行excel 資料寫入DB
			$sql->disconnect();
	
			if (!$import_query){
				if($memo == "3"){
?>
				<script language="javascript">
					alert('資料刪除失敗');
				</script>
<?php					
				}else{
?>
				<script language="javascript">
					alert('資料新增失敗');
				</script>
<?php					
				}
			}else{
				if($memo == "3"){
					$status_db_str .="您已成功刪除 ".$delete_count." 筆資料";
					$success_pstr ="<div><font color=red>※".$status_db_str."</font></div>";
?>				
					<script language="javascript">
						alert('資料刪除成功');
					</script>
<?php					
				}else{
					$status_db_str .="您已成功新增 ".$insert_count." 筆資料";
					$success_pstr ="<div><font color=red>※".$status_db_str."</font></div>";
?>				
					<script language="javascript">
						alert('資料新增成功');
					</script>
<?php					
				}
			}			
		}
		//寫入檔案中進行備份 Server
			$query_name = $sch_id."_".$now."_query_".".sql";
			$query_file_name = "$serverdir".$query_name;			
			$f = fopen($query_file_name,"a+");
			fwrite($f,$InsertStr);
			fclose($f);	
		//寫入檔案中進行log
			$filename = "upgraduation102_userinfoData_2003.log";
			$f=fopen("/home/leon/data/edu/log/$filename","a+");
			$fstring= "user=".$name." 執行 sql=".$InsertStr." ip=".$ip." date=".$now."\n";
			fwrite($f,$fstring);
			fclose($f);	
			
		//寫入檔案中進行log Server	
			$sql_log = new mod_db();
			$q_string_sname = "INSERT INTO [tted_edu_102].[dbo].[log_102] ([function] ,[school] ,[name] ,[account] ,[type],[nasdir] ,[serverdir],[filename] ,[ip])
								VALUES ('$funname','$sch_id' ,'$name','$account','0','$nsadir','$serverdir','$query_name' ,'$ip')";	 
			
			$sql_log->query("$q_string_sname");	
		//更新upload status 
			$q_string_sname = "UPDATE [tted_edu_102].[dbo].[upload102] SET status ='$status_db_str' WHERE filename = '$sfilename' ";	 
			$sql_log->query("$q_string_sname");	
			$sql_log->disconnect();  	
			

			
			}else if ($validation == 2){
?>
				<script language="javascript">
					alert('請選擇檔案');
				</script>
<?php			
			}else{
?>
				<script language="javascript">
					alert('資料上傳失敗,請重新上傳');
				</script>
<?php						
			}
			


  	// 自動post成功或錯誤訊息，onload="document.form1.submit()"
?>	
	<form id="form1" name="form1" method="post" action="graduation102.php">
		<input type="hidden" name="post_arr[0]" value="<?php echo $error_pstr;?>"/>
		<input type="hidden" name="post_arr[1]" value="<?php echo $error_str;?>"/>	
		<input type="hidden" name="post_arr[2]" value="<?php echo "總共:".$success_pstr;?>"/>		
	</form>	
</body>
</html>

