<?
##########################################################################################
#
# filename: gra102.php
# function: �W��101�Ǧ~�װ�T���~�Ͱ򥻸��
#
# ���@��  : �P�a�N
# ���@���: 2013/04/26
#
##########################################################################################
include("../../../css/use100.css");  

	session_start();

	if (!($_SESSION['Login'])){
		header("Location: ../../index.php");
	}
	
	$sch_id=$_SESSION['sch_id'];
	$tb_name='[use_102].[dbo].[upload102]';

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<script language="JavaScript" src="../../tigra_tables.js"></script>
<title> �W��101�Ǧ~�װ�T���~�Ͱ򥻸��</title>
<link href="../../../css/theme_inc.css" rel="stylesheet" type="text/css">
<?
  //��Ʈw�s���A�Φs������ƪ�

  include("../../../../../../../../../../../home/leon/data/use102/config/setting102.inc.php");

  $sql = new mod_db();
  $q_string = "select uploadtime,filename from $tb_name where school='".$_SESSION['sch_id']."' AND type=9 order by id ";
  $obj_query = $sql->query("$q_string");    
  $sql->disconnect();

?>
</head>

<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white">
<table cellpadding="3" cellspacing="1" border="0" width="100%">
	<tr>
	  <td class="header2">101�Ǧ~�װ�T���~�Ͱ򥻸��</td>
	</tr>
</table>
<table id="newupload" width="80%" align="left" style="display:inline" border="1" >
	<tr >
	  <td class="header1" colspan="8" align="center" >�W��101�Ǧ~�װ�T���~�Ͱ򥻸��</td>
  </tr>
  <tr id="gen_content">

    <td colspan="8" align="left" style="padding-left:10px;border-bottom:1px solid black;border-left:1px solid black;">�����ɮ�: 
    	<a href="../../function/download_file.php?file=101gra_form.xls">���U��</a> <!-- /
	    <a href="../../function/download.php?file=101gra_in.xls">�榡�d��</a>  -->
		<form enctype="multipart/form-data" method="POST" action="gra102_puserdata.php">
                <p align="center">�Ҵ��ѦW��@�~�����G
                  <select name="memo" size="1">
<option value="0">�п��</option>
                             <option value="4">����W����</option>
                             <option value="1">�W�[�s�W��</option>
                             <option value="2">���W����</option>
                             <option value="3">�R���W����</option>
                   </select><br><br>
            �Ƶ��G
        	<input name="contact" size="50"> 
           		<font COLOR ="red">(50�r��)</font><br><br>
					<input name="sfile" type="file">
					<input type="hidden" name="filetype" value="9">
            <br><br>																						
        	<input name="add" type="submit" value="�e�X�ɮ�"></p></p>
      </form>
	</td>
  </tr>		
<?php
  $i=1;
  while($obj_all = $sql->objects('',$obj_query)){
		if ($i==1){
?>
  <tr id="gen_content">
	<td colspan="8" class="header1" align="center" style="padding-left:10px;border-bottom:1px solid black;border-left:1px solid black;">�w�W�Ǫ��W��</td>
  </tr>
<?php
	}
?>
  <tr id="gen_content">
	<td align="left" style="padding-left:10px;border-bottom:1px solid black;border-left:1px solid black;"><?php echo "�ɮ�".$i."�@�W�ǩ�".$obj_all->uploadtime."___".$obj_all->filename."�C";?></td>
  </tr>
<?php
		$i++;
  }
?>
</table>
</body>
</html>