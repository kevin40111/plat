<?
##########################################################################################
#
# filename: fieldwork102.php
# function: 102�~��߮v��͸�ƤW�ǻP�פJ�򥻸��(�s��)
#
# ���@��  : �P�a�N
# ���@���: 2013/11/19
#
##########################################################################################

	session_start();

	if (!($_SESSION['Login'])){
		header("Location: ../../index.php");
	}
	$sch_id=$_SESSION['sch_id100'];//�ǮեN��

	include_once("/home/leon/data/edu/config/use_102/setting.inc.php"); 


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<title>�W��102�~��߮v��͸��</title>
<style>
	a, A:link, a:visited, a:active
		{color: #0000aa; text-decoration: none; font-family: Tahoma, Verdana; font-size: 11px}
	A:hover
		{color: #ff0000; text-decoration: none; font-family: Tahoma, Verdana; font-size: 11px}
	p, tr, td, ul, li
		{color: #000000; font-family: Tahoma, Verdana; font-size: 11px}
	.header1, h1
		{color: #ffffff; background: #B69866; font-weight: bold; font-family: Tahoma, Verdana; font-size: 13px; margin: 0px; padding: 3px;}
	.header2, h2
		{color: #000000; background: #B69866; font-weight: bold; font-family: Tahoma, Verdana; font-size: 12px;}
	.intd
		{color: #000000; font-family: Tahoma, Verdana; font-size: 11px; padding-left: 15px;}
</style>
<?
  
  //��Ʈw�s���A�Φs������ƪ�
  $sql_name = "tted_edu_102";
  $sql = new mod_db();  
  $q_string2 = "select * from [upload102] where school='".$sch_id."' AND type=2 order by cid";

  $obj_query2 = $sql->query("$q_string2");
  $sql->disconnect();
  

?>
</head>

<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white">
<table cellpadding="3" cellspacing="1" border="0" width="100%">
	<tr>
	  <td class="header2">&nbsp;�W��102�Ǧ~�׹�߮v��͸��</td>
	</tr>
</table>
<table id="newupload" width="95%" align="center" style="display:inline" border="1">
  <tr bgcolor="#FFFFCC">
    <td colspan="8" align="center">�W��102�Ǧ~�׹�߮v��͸��</td>
  </tr>
  <tr id="gen_content">
	<td colspan="8" align="left" style="padding-left:10px;border-bottom:1px solid black;border-left:1px solid black;">
		<br>��&nbsp;&nbsp;�򥻸�����榡�G<a href="../function/download.php?file=fieldwork102.xls">���U��</a> /
        									  <a href="../function/download.php?file=fieldwork102_example.xls">�d���U��</a>
        <!--<a href="../../download.php?file=101tutor_2007.xlsx">�d�����U��(2007����)</a><br><br>-->
		<fieldset style="width:60%;font-size:16px">
			<legend>�פJ�����G</legend>
			1.�ФU���d������A�Ш̷Ӧ����榡��J��ơC<br>
			2.�פJ�ɮ������Шϥ�<b>Excel 2003</b>�C<br>
			3.��J��ƮɽЯd�i���榡���Y�j�A�i��פJ�A���Χ�ʦ������@���A�H�K�y���פJ���~�C<br>
			 <font color='red'>
			4.�פJ�ɮ׭Y�ϥΡi�[�K�j�Ρi���ê��Y���j�Ρi�ᵲ���Y���j�Ҧ��i��y���פJ���~�C
			</font><br>			
			5.�Y�פJ�t���S���r�A�|��ܬ��i�L��ơj�A���ɥi�N�S���r�H�P��(*)���N�A�Y�i���`�פJ��Ʈw�C<br>
			 �Y���L�k���`�פJ�A�Ь��е����ߩӿ�H����U�ư��C</font>(02-7734-3669)<br>
            6.<font color="#0000FF"><strong>�p�J����ƬҤw�ǳƥ��T�A�����µL�k�W�ǡA��ĳ�t�~�U�������߮榡�A�N�쥻����ƥH<u><font color="#FF0000">�u�ƻs�v�H�u��ܩʶK�W�v��ܡu�ȡv</font></u>���覡�K�ܥ����ߪ����椤�C</strong></font><br>
            7.<a href="../function/download.php?file=fieldwork102QA.doc"><font size="3"><strong>�W��W��Q&amp;A</strong></font></a>
	  </fieldset>
		<br><br>
		  <fieldset style="width:60%">
		  <legend>�ɮ׶פJ�G</legend>
			&nbsp;��&nbsp;��&nbsp;��&nbsp;�ܡG
			<input name="chooseFun" type="radio" value="1" onClick="file1.style.display='inline'; file2.style.display='none';" checked="true">Excel 2003����(xls)
			<!-- <input name="chooseFun" type="radio" value="2" onClick="file2.style.display='inline'; file1.style.display='none';" >Excel 2007�H�W����(xlsx) -->
			
			
			<form enctype="multipart/form-data" method="POST" name="FileForm1" action="upload_fieldwork102Data.php" STYLE="margin: 0px; padding: 0px;">
				<table id="file1" style="display='inline'">
				<tr>
				<td>
				�\&nbsp;��&nbsp;��&nbsp;�ܡG&nbsp;<select name="memo" size="1">
                <option value="0">�п��</option>
                <option value="1">�W�[�s�W��</option>
                <option value="2">���W����</option>
                <!--<option value="3">�R���W����</option>-->
                <option value="4">����W����</option></select>
				</td>
				</tr>
					<tr>
						<td align="left">
							��&nbsp;��&nbsp;��&nbsp;�ܡG
							<input name="sfile1" type="file" id="sfile1">
							<input type="button" value="�e�X�ɮ�" onClick="checkFile1()">
						</td>
					</tr>
					<tr>
						<td align="left">
							�s���H��T�G<input name="contactinfo" size="50"> (50�r��)<br><br>
						</td>
					</tr>
				</table>
			</form>			
		  </fieldset>
		  <br>
		<?php
			echo "<br>".$_POST["post_arr"][0];
			echo "<br>".$_POST["post_arr"][1];
			echo "<br>".$_POST["post_arr"][2]."<br>";
		 ?>
	</td>
  </tr>
<?php
	
  $i=1;
  while($obj_all2 = $sql->objects('',$obj_query2)){
		if ($i==1){
?>
<tr>
<td colspan="10" align="left"><input type="button" name="Submit" value="�d�ߤw�W�ǦW���`�C��" onClick="location.href='../function/modify_fieldwork102.php'" style="width:200;height:30;color:#999;background-color:#F00";> </td>
</tr>
  <tr id="gen_content">
	<td colspan="10" align="center" style="background: #FFFFCC;padding-left:10px;border-bottom:1px solid black;border-left:1px solid black;">�w�W�Ǫ��W��</td>
  </tr>
<?php
		}
?>
  <tr id="gen_content">
	<td colspan="10" align="left" style="padding-left:10px;border-bottom:1px solid black;border-left:1px solid black;"><?php echo "�@�ɮ�".$i."�@�W�ǩ�".$obj_all2->uploadtime."___���檬�A :".$obj_all2->status."�C";?></td>
  </tr>
<?php
		$i++;
  }
?>


</table>
</body>
</html>

<script language="JavaScript">

//�o�̱���n�ˬd�����ءAtrue��ܭn�ˬd�Afalse��ܤ��ˬd   

var isCheckFileType = true;  //�O�_�ˬd�ɮװ��ɦW 

function checkFile() {   

    var f = document.FileForm;   
    var re = /\.(xlsx)$/i;  //���\���ɮװ��ɦW   

    if (isCheckFileType && !re.test(f.sfile.value)) {   
        alert("�ɮ��������~�G�u���\�W��xlsx(excel 2007�H�W)�ɮ�");   
    } else {   
        document.FileForm.submit();   
    }   
} 

function checkFile1() {   

	var f1 = document.FileForm1;	
    var re = /\.(xls)$/i;  //���\���ɮװ��ɦW    

	if (isCheckFileType && !re.test(f1.sfile1.value)) {   
        alert("�ɮ��������~�G�u���\�W��xls(excel 2003)�ɮ�");   
    } else {   
        document.FileForm1.submit();   
    } 
} 

</script> 
