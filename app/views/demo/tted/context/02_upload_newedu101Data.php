<?
##########################################################################################
#
# filename: upload_newedu101Data.php
# function: �W��101�Ǧ~�׷s�i�v��͸��
#
# ���@��  : �P�a�N
# ���@���: 2013/11/21
#
##########################################################################################

	// ��ܩҦ������~�Bĵ�i(E_ALL)�A����ɴ�������(E_NOTICE)
	session_start();
	
	if (!($_SESSION['Login'])){
		header("Location: ../../index.php");
	}	
	
	//��Ʈw�s���A�Φs������ƪ�
		include_once('../../public/logincheck.php') ; //newcid by use
		include_once("/home/leon/data/edu/config/use_102/setting.inc.php"); 
		require("/home/leon/data/edu/config/ftp.inc.php");
	
		$funname ='edu_102/upload/upnewedu101Data_2003.php';
		$serverdir ='/home/leon/data/edu/data/newedu101/';
	
		$tb_name='[tted_edu_102].[dbo].[newedu101_userinfo] ';
		date_default_timezone_set('Asia/Taipei'); // �վ�ɰϡA���M�ɶ��|�֤K�p��
		//���o�ϥΪ̵n�Jip
		$ip = getenv("REMOTE_ADDR");		
		$validation = 0;		
		$now = date("Ymd-His");
		
		//�����
		$memo = $_POST['memo'];
		$contact  = $_POST['contactinfo'];
		//���o�ϥΪ̵n�Jip
		$ip = getenv("REMOTE_ADDR");		
		$sch_id=$_SESSION['sch_id100'];//�ǮեN��
		$name=$_SESSION['name'];//�ӿ�H�m�W
		$account=$_SESSION['account'];//�n�J�b��	
				
		$InsertStr="";// �ˬd�פJ���(���)
		$error_pstr ="";// ���~�פJ���(���)
		$error_str="";// ���~�פJ���(���)
		
		$status_db_str="";// db�פJ���(���)
		
		$insert_count=0;// �פJ����(���)
		$delete_count=0;// ���~����(���)
		
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�W��101�Ǧ~���������~�v��͸��</title>
</head>
<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white" onLoad="document.form1.submit()"> 
<?		

	if ($_FILES["sfile1"]["error"] == 0){	
			//�B�z�n�g�J���Ѽ�
			$sfilename = stripslashes($_FILES['sfile1']['name']);
			$sfilename = $sch_id."_".$now."_".$sfilename;
			
			$sdestination = "$serverdir".$sfilename;
			
			//�N�W�Ǫ��ɮ׼g�J���A����
			move_uploaded_file($_FILES["sfile1"]["tmp_name"],"$sdestination");
			
			$sql = new mod_db();
			$query_str = "Insert into [tted_edu_102].[dbo].[upload102] (school,name,account,memo,contact,filename,ip,type) 
			Values ('".$sch_id."','".$name."','".$account."','".$memo."','".$contact."','".$sfilename."','".$ip."','1')";
			$upload_query=$sql->query("$query_str");			
			$sql->disconnect();
				
			if ($_FILES["file"]["error"] ==0){			
				 $validation = 1; //�W�Ǧ��\
			}else{
				$validation = 2;//�W�ǥ���
			}
		}

	
	if($validation == 1){
			
		require_once("../../public/Excel/reader.php");
		$Import_Sheet = new Spreadsheet_Excel_Reader(); //�Ĥ@���x�s��$Import_Sheet->sheets[0]['cells'][1][1] //[row][col]
		$Import_Sheet->setOutputEncoding('BIG5');
		$Import_Sheet->read($sdestination);
		$Import_RowCount = $Import_Sheet->sheets[0]['numRows'];
		$Import_ColCount = $Import_Sheet->sheets[0]['numCols'];// ���o�`���$Import_RowCount,�`�C��$Import_ColCount
		
		// �h���ťզC�A�íp��פJ�ɮפ��`�C�ơA$Import_NewColCount
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
		$Import_NewColCount=21;
		// �h���ťզ�A�íp��פJ�ɮפ��`��ơA$Import_NewRowCount
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

		// �ʺA�ŧi�}�C�j�p
		$check_error = array('0');
		$check_error = array_pad($check_error, $Import_NewColCount, '0');
		$error_row =0;
		
	//�i�� excel �ɮ�Ū�� 
	for ($i = 4; $i <= $Import_NewRowCount; $i++) { 
		
		$check_error = array();
		$datalength = array();
		/*
		for($step =2;$step <=21;$step++){
			$temp = strlen(str_replace("?","",str_replace("'","",rtrim(ltrim($Import_Sheet->sheets[0]['cells'][$i][$step])))));
			$strlen = $step.' _ '.$temp.'<br>';
			echo $strlen;
		}		
		exit;
		*/		
		
		//�~�[ 18��
		//$temp = str_replace("?","",str_replace("'","",rtrim(ltrim($Import_Sheet->sheets[0]['cells'][$i][$step]))));
			$temp =$Import_Sheet->sheets[0]['cells'][$i][18];
			$templength = strlen($temp);
			
			//�P�_���ƬO�_�s�b��ơA�����datalength��0�A�_�h��1�A�ñN����Ƽg�Jdatalist[tmp]��
			if($templength==0){
				$datalength=0;				
			}else{
				$datalength=1;
				$datalist[18]=$temp;
			}
			
			if ($datalength!=0){
				if (preg_match("/^[0-9]+$/i", $datalist[18])) {	
					;	
				}else{
					$check_error[$step]=1;
				}
			}else{
				$check_error[$step]=2;
			}
			
		
		for($step =2;$step <=21;$step++){
		
			//$temp = str_replace("?","",str_replace("'","",rtrim(ltrim($Import_Sheet->sheets[0]['cells'][$i][$step]))));
			$temp =str_replace("?","",str_replace("'","",trim($Import_Sheet->sheets[0]['cells'][$i][$step])));
			$templength = strlen($temp);
			
			//�P�_���ƬO�_�s�b��ơA�����datalength��0�A�_�h��1�A�ñN����Ƽg�Jdatalist[tmp]��
			if($templength==0){
				$datalength=0;				
			}else{
				$datalength=1;
				$datalist[$step]=$temp;
			}
			
			
			// �ˬd���}�l-------------------------------------------------------------------
			// ** $check_error = 1 �����D, $check_error = 2 �L���, $check_error = 0 ���T�L�~ **			
			
			//�ǮեN�X 2
			if($step ==2){
				if ($datalength!=0){
					$datalist[$step]=$sch_id;
				}else{
					$check_error[$step]=2;
				}
			}
			
			//�Ǹ� 3 ��t�ҥN�X 4 ��t����W�� 5 ����m�W 8 ���y�l���ϸ� 11 ���y�a�} 12			
			if($step ==3 || $step ==4 || $step ==5 ||$step ==8 ||$step ==11 ||$step ==12 ){
				if ($datalength!=0){
					;
				}else{
					$check_error[$step]=2;
				}
			}
			
			//�~��6��
			if($step ==6){
				if ($datalength!=0){
					if (eregi("^([1-7]{1})$",$datalist[$step])) {
						;	
					}else{
						$check_error[$step]=1;
					}
				}else{
					$check_error[$step]=2;
				}
			}
			
			
			//�����Ҧr�� 9��
			if($step ==9){
				if($datalist[18] == '0' || $datalist[18] =='1' || $datalist[18]=='2' || $datalist[18]=='5' || $datalist[18]=='6' || $datalist[18]=='12'|| $datalist[18]=='13') {
					$check_stdidnumber=checkid($datalist[$step]);
				}else if($datalist[18]=='3'|| $datalist[18]=='4' || $datalist[18]=='7' || $datalist[18]=='8' || $datalist[18]=='9' || $datalist[18]=='10 '|| $datalist[18]=='11'){	
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
			//�q�l�l��H�c10��
			if($step ==10){
				if ($datalength!=0){
					;
				}else{
					$check_error[$step]=2;
				}
			}
			
			//�Ǩ�O7��
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
			
			//�s���q�� 13
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
			//�㥮�X��v��¾�e�Ш|�ҵ{�׽Ҹ��14��
			//�����p�Ǯv��¾�e�Ш|�׽Ҹ��15��
			//�㤤���Ǯծv��¾�e�Ш|�׽Ҹ��16��
			//��S��Ш|�v��¾�e�Ш|�ҵ{�׽Ҹ��17��
			if($step ==14  ||$step ==15  ||$step ==16 ||$step ==17){
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
			//�X�ͦ~19��	
			//echo $step.'<br>';
			if($step ==19){
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
			//�����O20
			//�ʧO21
			if($step ==20 || $step ==21){
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
		//var_dump ($check_error);exit;
		
		// for step ���� �ˬd��쵲��-------------------------------------------------------------------
		
		$count_error=0;
		
		if($datalist[18] == 0 || $datalist[18] ==1 || $datalist[18]==2 || $datalist[18]==5 || $datalist[18]==6){
				$newcid = createnewcid($datalist[9]); 
			}else if($datalist[18]==3|| $datalist[18]==4 || $datalist[18]==7 || $datalist[18]==8 || $datalist[18]==10 || $datalist[18]==11){
				$newcid = $datalist[9]; 
			}else{
				$newcid = createnewcid($datalist[9]);
			}
		
		$value='';
		for($step =2;$step <=21;$step++){
			$count_error=$count_error+$check_error[$step];
			
			//���XSQL Command
			$value.= "'".$datalist[$step]."'";
			if($step!=21){
				$value.= ",";
			}
			else if($step==21){
				$value.= ",'".$newcid."'";
			}	
		}
		//echo $value;
		//echo  'count_error : '.$count_error .'</br>';exit;

		if ($count_error>0){
					
				// ��������~�T���}�l-------------------------------------------------------------------
				
				if ($i == 4){
				$error_str = "<table width=1500' align='center'  cellpadding='0' cellspacing='0' border='1'>";
				$error_str .= "<tr bgcolor=#DCE2EE>";

				$error_str .= "	<td align=\��center\��><b>�ǮեN�X</b></td>
								<td align=\��center\��><b>�Ǹ�</b></td>
								<td align=\��center\��><b>��t�ҥN�X</b></td>
								<td align=\��center\��><b>��t����W��</b></td>
								<td align=\��center\��><b>�~��</b></td>
								<td align=\��center\��><b>�Ǩ�O</b></td>
								<td align=\��center\��><b>����m�W</b></td>
								<td align=\��center\��><b>�����Ҧr��</b></td>
								<td align=\��center\��><b>�q�l�l��H�c</b></td>
								<td align=\��center\��><b>���y�l���ϸ�</b></td>
								<td align=\��center\��><b>���y�a�}</b></td>
								<td align=\��center\��><b>�s���q��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w���X��v��¾�e�Ш|�ҵ{�׽Ҹ��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w����p�Ǯv��¾�e�Ш|�׽Ҹ��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w�����Ǯծv��¾�e�Ш|�׽Ҹ��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w�S��Ш|�v��¾�e�Ш|�ҵ{�׽Ҹ��</b></td>
								<td align=\��center\��><b>�~�[�W�B</b></td>
								<td align=\��center\��><b>�X�ͦ~</b></td>
								<td align=\��center\��><b>�����O</b></td>
								<td align=\��center\��><b>�ʧO</b></td> ";
				$error_str .= "</tr>";		 
				}
				
				if ($i%4==1)
					$error_str .= "<tr bgcolor=#FFFFFF >";
				else
					$error_str .= "<tr bgcolor=#F5F8FD >";
				
				for($step =2;$step <=21;$step++){
					if ($check_error[$step]==1){
						$error_str .= "<td><font color=red>".$datalist[$step]."</font></td>";
						}
					else if ($check_error[$step]==2){
						$error_str .= "<td><font color=red>�L���</font></td>";
						}
					else{
						$error_str .= "<td>".$datalist[$step]."</td>";
						}
				}
				if ($i == $Import_NewRowCount){
					$error_str .= "</table>";
				}

				$error_row +=1; //���~�p��

	// ��������~�T������-------------------------------------------------------------------				
		}
		else{
			if ($i == 4){
				$error_str = "<table width=1500' align='center'  cellpadding='0' cellspacing='0' border='1'>";
				$error_str .= "<tr bgcolor=#DCE2EE>";
				$error_str .= "	<td align=\��center\��><b>�ǮեN�X</b></td>
								<td align=\��center\��><b>�Ǹ�</b></td>
								<td align=\��center\��><b>��t�ҥN�X</b></td>
								<td align=\��center\��><b>��t����W��</b></td>
								<td align=\��center\��><b>�~��</b></td>
								<td align=\��center\��><b>�Ǩ�O</b></td>
								<td align=\��center\��><b>����m�W</b></td>
								<td align=\��center\��><b>�����Ҧr��</b></td>
								<td align=\��center\��><b>�q�l�l��H�c</b></td>
								<td align=\��center\��><b>���y�l���ϸ�</b></td>
								<td align=\��center\��><b>���y�a�}</b></td>
								<td align=\��center\��><b>�s���q��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w���X��v��¾�e�Ш|�ҵ{�׽Ҹ��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w����p�Ǯv��¾�e�Ш|�׽Ҹ��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w�����Ǯծv��¾�e�Ш|�׽Ҹ��</b></td>
								<td align=\��center\��><b>101�Ǧ~�׮֩w�S��Ш|�v��¾�e�Ш|�ҵ{�׽Ҹ��</b></td>
								<td align=\��center\��><b>�~�[�W�B</b></td>
								<td align=\��center\��><b>�X�ͦ~</b></td>
								<td align=\��center\��><b>�����O</b></td>
								<td align=\��center\��><b>�ʧO</b></td> ";
				$error_str .= "</tr>";	 
			$error_str .= "<tr>	<td colspan =20 align=\'center\'><b>��ƵL�~</b></td> </tr>";

			}
						
			if ($i == $Import_NewRowCount){
				$error_str .= "</table>";
			}
		
			$sql_exist = new mod_db();
			$sql_str ="SELECT newcid FROM $tb_name WHERE newcid = '$newcid'";
			//echo $sql_str;exit;
			$obj_ck_exist=$sql_exist->objects("$sql_str");
			$sql_exist->disconnect();	
			//memo = 1 �s�W  memo = 2 �ק�  memo = 3 �R�� memo = 4 ����W�� obj_ck_exist �w�s�b�ۦP�W��i��upadte						

			if($obj_ck_exist->newcid ==''){
			//echo '�W�['.'</br>' ;
				$InsertStr.="Insert into $tb_name(	[uid],
													[stdid],
													[udepcode],
													[udepname],
													[stdyear],
													[stdschoolsys],
													[stdname],
													[stdidnumber],
													[stdemail],
													[stdregzipcode],
													[stdregaddr],
													[tel],
													[childprogram],
													[priprogram],
													[secprogram],
													[speprogram],
													[other],
													[birthyear],
													[aboriginal],
													[gender],
													[newcid]) 
													values (".$value.")\n ;";	
				$InsertStr.="Insert into [tted_edu_102].[dbo].[newedu101_id] (stdidnumber,newcid) Values ('".$datalist[9]."','".$newcid."')\n ;";		
				$InsertStr.="Insert into [tted_edu_102].[dbo].[newedu101_pstat](newcid) Values ('".$newcid."')\n ;";				
				$insert_count +=1;			
				
			}elseif($obj_ck_exist->newcid == $newcid){
			//echo '��s'.'</br>' ;
				$InsertStr.= "UPDATE $tb_name SET 	[uid]='".$datalist[2]."',
													[stdid]='".$datalist[3]."',
													[udepcode]='".$datalist[4]."',
													[udepname]='".$datalist[5]."',
													[stdyear]='".$datalist[6]."',
													[stdschoolsys]='".$datalist[7]."',
													[stdname]='".$datalist[8]."',
													[stdemail]='".$datalist[10]."',
													[stdregzipcode]='".$datalist[11]."',
													[stdregaddr]='".$datalist[12]."',
													[tel]='".$datalist[13]."',
													[childprogram]='".$datalist[14]."',
													[priprogram]='".$datalist[15]."',
													[secprogram]='".$datalist[16]."',
													[speprogram]='".$datalist[17]."',
													[other]='".$datalist[18]."',
													[birthyear]='".$datalist[19]."',
													[aboriginal]='".$datalist[20]."',
													[gender]='".$datalist[21]."'
													 where newcid='".$newcid."'\n ;";									
													 
				$insert_count +=1;				
			}elseif($memo == "3"){
			//echo '�R��'.'</br>' ;
				$InsertStr.="delete from $tb_name where newcid='".$newcid."'\n";
				$InsertStr.="delete from [tted_edu_102].[dbo].[newedu101_id] where newcid='".$newcid."'\n";
				$InsertStr.="delete from [tted_edu_102].[dbo].[newedu101_pstat] where newcid='".$newcid."'\n";
				$delete_count +=1;
			}	
		}			
	}
	//echo $InsertStr;exit;
	//���� excel �ɮ�Ū�� 	
	if ($error_row>0){
			$status_db_str.="�z�����~��Ʀ@ ".$error_row." �����";
			$error_pstr ="<div><font color=red>��".$status_db_str."</font></div>";
?>				
			<script language="javascript">
				alert('�z�פJ����Ʀ��~�A�аѦҭ����W���~�T���ץ�');
			</script>
<?php			
		}
		elseif ($error_row==0 && strlen($InsertStr)==0){
?>
			<script language="javascript">
				alert('�פJ���~:�ɮ׼g�J���~');
			</script>
<?php		
		}
				
	if (strlen($InsertStr)!=0){
		if($memo == "3"){
?>
			<script language="javascript">
				var delete_str = "�ثe���~��Ʀ@ "+ <?php echo $error_row ?> + "���A���i�R���j��Ʀ@" + <?php echo $delete_count ?> + "�����";
				alert(delete_str);
			</script>
<?php			
		}else{
?>
			<script language="javascript">
				var str = "�ثe���~��Ʀ@ "+ <?php echo $error_row ?> + "���A���i�s�W/��s�j��Ʀ@" + <?php echo $insert_count ?> + "�����";
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
			$import_query=$sql->query($InsertStr); //����excel ��Ƽg�JDB
			$sql->disconnect();
	
			if (!$import_query){
				if($memo == "3"){
?>
				<script language="javascript">
					alert('��ƧR������');
				</script>
<?php					
				}else{
?>
				<script language="javascript">
					alert('��Ʒs�W����');
				</script>
<?php					
				}
			}else{
				if($memo == "3"){
					$status_db_str .="�z�w���\�R�� ".$delete_count." �����";
					$success_pstr ="<div><font color=red>��".$status_db_str."</font></div>";
?>				
					<script language="javascript">
						alert('��ƧR�����\');
					</script>
<?php					
				}else{
					$status_db_str .="�z�w���\�s�W ".$insert_count." �����";
					$success_pstr ="<div><font color=red>��".$status_db_str."</font></div>";
?>				
					<script language="javascript">
						alert('��Ʒs�W���\');
					</script>
<?php					
				}
			}			
		}
		//�g�J�ɮפ��i��ƥ� Server
			$query_name = $sch_id."_".$now."_query_".".sql";
			$query_file_name = "$serverdir".$query_name;			
			$f = fopen($query_file_name,"a+");
			fwrite($f,$InsertStr);
			fclose($f);	
		//�g�J�ɮפ��i��log
			$filename = "upnewedu101_userinfoData_2003.log";
			$f=fopen("/home/leon/data/edu/log/$filename","a+");
			$fstring= "user=".$name." ���� sql=".$InsertStr." ip=".$ip." date=".$now."\n";
			fwrite($f,$fstring);
			fclose($f);	
			
		//�g�J�ɮפ��i��log Server	
			$sql_log = new mod_db();
			$q_string_sname = "INSERT INTO [tted_edu_102].[dbo].[log_102] ([function] ,[school] ,[name] ,[account] ,[type],[nasdir] ,[serverdir],[filename] ,[ip])
								VALUES ('$funname','$sch_id' ,'$name','$account','0','$nsadir','$serverdir','$query_name' ,'$ip')";	 
			
			$sql_log->query("$q_string_sname");	
		//��supload status 
			$q_string_sname = "UPDATE [tted_edu_102].[dbo].[upload102] SET status ='$status_db_str' WHERE filename = '$sfilename' ";	 
			$sql_log->query("$q_string_sname");	
			$sql_log->disconnect();  	
			

			
			}else if ($validation == 2){
?>
				<script language="javascript">
					alert('�п���ɮ�');
				</script>
<?php			
			}else{
?>
				<script language="javascript">
					alert('��ƤW�ǥ���,�Э��s�W��');
				</script>
<?php						
			}
			


  	// �۰�post���\�ο��~�T���Aonload="document.form1.submit()"
?>	
	<form id="form1" name="form1" method="post" action="newedu101.php">
		<input type="hidden" name="post_arr[0]" value="<?php echo $error_pstr;?>"/>
		<input type="hidden" name="post_arr[1]" value="<?php echo $error_str;?>"/>	
		<input type="hidden" name="post_arr[2]" value="<?php echo "�`�@:".$success_pstr;?>"/>		
	</form>	
</body>
</html>

