<?
##########################################################################################
#
# filename: gra102_puserdata.php
# function: ����W�ǡB�ק�B�R��101�Ǧ~�ײ��~�ꤤ�ǥͰ򥻸��
#
# ���@��  : �P�a�N
# ���@���: 2013/04/26
#
##########################################################################################
	session_start();

	if (!($_SESSION['Login'])){
		header("Location: ../../index.php");
	}
		
	$value=$_POST['memo'];//�ާ@���O
	$filetype=$_POST['filetype'];
	
	$sch_id=$_SESSION['sch_id'];
	$name=$_SESSION['name'];
	$account=$_SESSION['account'];    
	$page_index=$_SESSION['page_index'];
	    
	$rpage = $_SERVER['HTTP_REFERER']; //�e��
	
	$path="junior/";
	
	$validation = 0;
	$ip = getenv("REMOTE_ADDR");
	$now=date("Ymd-His");
		
	$today = date("Y/n/d H:i:s");
	$school = $_SESSION['sch_id'];	

  	$tb_name='[use_102].[dbo].[upload102]';
	
	$funname='use102/upload/gra/gra102_puserdata.php';
	$nasdir='/se/use/use_102/'.$path;
	$serverdir ='../../../../../../../home/leon/data/use102/data/'.$path;
	$query_name=$now.".sql";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�W��101�Ǧ~�ײ��~�ꤤ�Ͱ򥻸��</title>
<link href="../../css/theme_inc.css" rel="stylesheet" type="text/css">

</head>

<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white">
<table cellpadding="3" cellspacing="1" border="0" width="100%">
	<tr>
	  <td class="header2">&nbsp;�W�ǦW����</td>
	</tr>
</table>
<p>&nbsp;</p>

<?
 //��Ʈw�s���A�Φs������ƪ�
 include("../../../../../../../home/leon/data/use102/config/setting102.inc.php");
 include_once("../../../../../../../home/leon/data/use102/config/ftp102.inc.php");

  $sql = new mod_db();

		//�B�z�n�g�J���Ѽ�
		$sfilename = stripslashes($_FILES['sfile']['name']);
		$sfilename = $now."_".$school."_".$value."_".$filetype."_".$sfilename;
		
        $sdestination = $serverdir."$sfilename";		
	
		//�N�W�Ǹ�Ʃ�m�t�ΩҦb�������A����
     	copy($sfile,$sdestination);		
		
		//�}��FTP�s��
		$conn = ftp_connect($ftp_host);
     	$login = ftp_login($conn, $ftp_user, $ftp_password);

		if($login == true){
		
			//�N�W�Ǫ��ɮ׼g�J���A����
				$mode = ftp_pasv($conn, TRUE);
			
				$query_str1 = "Insert into $tb_name (school,name,account,memo,contact,filename,ip,type,uploadtime) 
				Values ('".$school."','".$name."','".$account."','".$memo."','".$contact."','".$sfilename."','".$ip."','".$filetype."','".$today."')";
			
			//�g�JDB �O�� �P�ɲ���sql�ƥ�
				$file_name =  $serverdir.$now.".sql";		

				$f = fopen($file_name,"a+");
				fwrite($f,$query_str1);
				fclose($f);
			
			//�W�Ǥ��ɮ׻Psql�i��ƥ���FTP
				
				if($login == true){
				
					ftp_put($conn,$nasdir."$sfilename","$sdestination",FTP_BINARY);
					ftp_put($conn,$nasdir."$now".".sql","$file_name",FTP_BINARY);
					ftp_quit($conn);
				}
				$query1=$sql->query("$query_str1");
				
				
				
			if ($query1){
					$validation = 1;		
							
					$sql_log = new mod_db();
				
					$insert_log = "INSERT INTO [use_102].[dbo].[log_102] ([function] ,[school] ,[name] ,[account] ,[type],[nasdir] ,[serverdir],[filename] ,[ip])
VALUES ('$funname','$sch_id' ,'$name','$account','0','$nasdir','$serverdir','$query_name' ,'$ip')";	 
								
					$sql_log->query("$insert_log");
					$sql_log->disconnect();
			}else{
					$validation = 2;
			}

			//�P�_�O�_��s���\�H�M�wecho���T��
			
			if ($validation == 1){
		?>

	<script language="javascript">
					alert('��ƤW�Ǧ��\');
					location.replace('<?=$rpage?>');
				</script>
	<?php			
			}else if ($validation == 2){
	?>
				<script language="javascript">
					alert('�п���ɮ�');
					
					location.replace('<?=$rpage?>');
				</script>
	<?php			
			}else{
	?>
				<script language="javascript">
					alert('��ƤW�ǥ���,�Э��s�W��');
					location.replace('<?=$rpage?>');
				</script>
	<?php						
			}
		}
 $sql->disconnect();
?>

</body>
</html>