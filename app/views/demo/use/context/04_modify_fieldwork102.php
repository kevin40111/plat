<?
##########################################################################################
#
# filename: modify_fieldwork102.php
# function: �ק�102���@�s�ͪ��A
#           1. �ˬd�O�_�w�n�J
#           2. �C�X���
#
# ���@��  : �P�a�N
# ���@���: 2013/11/22
#
##########################################################################################

  //���ɥثe�ɶ�
  $now=date("Y/n/d g:i");
  
 /* session_start();
  if (!($_SESSION['Login'])) {
           //�p�G���n�J,�h��ܵn�J�e��
           header("Location: ../../index.php");
  }else{
	  $user=$_SESSION['sname'];
	  $sch_id=$_SESSION['sch_id100'];//�ǮեN��
	  
	  $table_name = '[tted_edu_102].[dbo].[fieldwork102_userinfo]';
		
		include("/home/leon/data/edu/config/use_102/setting.inc.php"); 	
	 
	  $sql = new mod_db();
	  $pmode = $_GET['pmode'];
	  	  $list_string = "SELECT stdid,udepcode ,udepname ,stdschoolsys ,stdname  ,birthyear,pstat ,qtype ,newcid        
						  FROM $table_name WHERE uid='$sch_id' " ;
	 
	  $pmode = $_GET['pmode'];
	  switch ($pmode) {  //�P�_��ܰ���d�߻y�y
	         case "0":
	              $list_string = $list_string. " AND pstat=0 "; //���~
	         break;
	         case "1":
	              $list_string = $list_string. " AND pstat=1 "; //�����~
	         break;
	         default:
				  $list_string = $list_string. " AND pstat=0 "; //���~
	         break;
	  }
	  $list_string= $list_string . "order by stdid asc";
	  $num_all = $sql->nums("$list_string");*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
	<title>��ƭק�</title>
<script language="JavaScript" src="../../js/tigra_tables.js"></script>
<script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>   
<script type="text/javascript" src="../../js/jeditable.js"></script>
<script type="text/javascript">
$( function() {
		$("#all_table").on("mouseover",'th.qtype', function() {
        	$(this).editable("save_modify_fieldwork102.php" ,{
				data   : "{'0':'�լd��H','1':'�D�լd��H'}",
    			type   : "select",
				id        : 'elementid',
				name      : 'newvalue',
				tooltip   : '�I��U�i�ק�',
				cancel    : '����',
				submit    : '�T�w',
				indicator : '�ק襤...',
				event     : "dblclick",
				callback : function(value, settings) {
					var pmode = '<? //echo $pmode; ?>';
					if(pmode== 0)
					{
						if(value.substr(0,9) == '	�ק令:�լd��H' ){
							 $(this).parent().find(":checkbox").prop("disabled",true);
						 }else{
							$(this).parent().find(":checkbox").prop("disabled",false);
						}
					}
					else
					{
						console.log(value);
						if(value.substr(0,9) == '	�ק令:�լd��H' ){
							 $(this).parent().find(":checkbox").prop("disabled",true);
						 }else{
							$(this).parent().find(":checkbox").prop("disabled",false);
						}	
					}
				 }
				});			
	     });
		 });
</script>
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
	.head5 {
color: red;font-family : Verdana, Helvetica, sans-serif;
font-size : 16pt;
font-weight : bold; 
}
.style3 {color: #FF0000; font-weight: bold; }
.style4 {color: #0000FF}
.style11 {font-size: 18px; color: #FF0000; font-weight: bold; }
</style>
</head>
<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white">
<table cellpadding="3" cellspacing="1" border="0" width="100%" align="center">
	<tr>
	  <td class="header2">&nbsp;���102�~��߮v��ͪ��A</td>
	</tr>
	<tr>
	  <td>
	    <table cellpadding=4 cellspacing=0 border=0 width=90% align="left">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
					<table id="menu_table" width="30%" border="1" cellspacing=1 cellpadding=1>
					<tr>
						<th scope="col" width="50%" <?if ($pmode==0) {echo "bgcolor=#F0F0FD";} else {echo "bgcolor=#ffffff";}?>><span class="head2"><a href="<? //echo $_SERVER['PHP_SELF'].'?pmode=0' ?>">102�~��߮v��ͦW��</a></span></th>
								<th scope="col" width="50%" <?if ($pmode==1) {echo "bgcolor=#F0F0FD";} else {echo "bgcolor=#ffffff";}?>><span class="head2"><a href="<? //echo $_SERVER['PHP_SELF'].'?pmode=1' ?>">�w�R��102�~��߮v��ͦW��</a></span></th>
							</tr>
			</table>
					</td>
				</tr>
			</table>
			<p>&nbsp;</p> 
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<table cellpadding=4 cellspacing=0 border=0 align="left" width="95%">
				<tr>
				<td>
				<form name="form2" method="post" action="modify_data.php">
			  	  <p><font color="#FF0000"><? echo $user; ?> </font>�z�n�G�Q�եثe�@�� <font color="blue" size="+3"><? echo $num_all;?></font> �쪬�A��&lt;<font color="red"><?php //if($pmode==1){ echo "�D102�~��߮v���";}else{ echo "102�~��߮v���";} ?></font>&gt;��102�~��߮v���</p>
			    <input type="hidden" name="stu_mode" value="<?php //if($pmode==1){ echo "0";}else{ echo "1";}?>">
						<span class="head5"></span><?php //if($pmode==1){ echo "�ФĿ�n<<font color=\"red\">�_��R��</font>>���H��";}else{ echo "�ФĿ�n<<font color=\"red\">�R��</font>>���H��";}?>�A�Ŀ粒���Ы��̤U��Υk�褧�e�X���s�C
						<input type="submit" name="Submit2" value="�e�X">
						<input type="hidden" name="degree" value="2"/>
						<br>
		  	      <table width="100%" border="1" id="all_table" cellpadding="2">
		            <tr class="header1">
                    				<th width="5%" scope="col" >���</th>
									<th width="10%" scope="col" align="center">�Ǹ�</th>
                                    <th width="10%" scope="col" align="center">��t�ҥN�X</th>
                                    <th width="10%" scope="col" align="center">��t����W��</th>
                                    <th width="10%" scope="col" align="center">�Ǩ�O</th>
									<th width="10%" scope="col" align="center">�m�W</th>
                                    <th width="10%" scope="col" align="center">�X�ͦ~</th>
									<th width="20%" scope="col" align="center">�O/�_�լd��H</th>
								    <th width="15%" scope="col">�ثe���A</th>
		            </tr>
<?
	  /*$all_query = $sql->query("$list_string");
		$dep = 0;
	
		if ($pmode == 0)
			$std_pstat = '102�Ǧ~�׹�߮v���';
		else
			$std_pstat = '�D102�Ǧ~�׹�߮v���';
	
	    while ($obj_all = $sql->objects('',$all_query)){

		 //��h�Ǩ䥦
		if($obj_all->qtype == 0)
			{$qtype ='�լd��H';}
		else if($obj_all->qtype == 1)
			{$qtype ='�D�լd��H';}
				  
			echo "<tr>";
			if($qtype == '�լd��H' && $pmode == 0){
				echo "<td align=center><input type=\"checkbox\" name=junior[] value=". $obj_all->newcid ."  disabled></td>\n";
			}
			elseif($qtype != '�D�լd��H' && $pmode == 1)
			{
				echo "<td align=center><input type=\"checkbox\" name=junior[] value=". $obj_all->newcid ." disabled></td>\n";
			}
			else
			{
				echo "<td align=center><input type=\"checkbox\" name=junior[] value=". $obj_all->newcid ." ></td>\n";
			}
			echo "<td scope=col align=center>&nbsp;&nbsp;&nbsp;".$obj_all->stdid. "</td>\n";
			echo "<td scope=col align=center>&nbsp;&nbsp;&nbsp;".$obj_all->udepcode. "</td>\n";
			echo "<td scope=col align=center>&nbsp;&nbsp;&nbsp;".$obj_all->udepname. "</td>\n";
			echo "<td scope=col align=center>".$obj_all->stdschoolsys ."</td>\n";
			echo "<td scope=col align=center>&nbsp;&nbsp;&nbsp;".$obj_all->stdname . "</font></td>\n";	
			echo "<td scope=col align=center>&nbsp;&nbsp;&nbsp;".$obj_all->birthyear. "</font></td>\n";		
			
		if($qtype == '�լd��H'){
				echo "<th class=qtype id=$obj_all->newcid  scope=col align=center><font color=\"#990000\">".$qtype.' ���I���U'. "</th >\n";
			}
		else if($qtype == '�D�լd��H'){
				echo "<th class=qtype id=$obj_all->newcid  scope=col align=center><font color=\"FF8040\">".$qtype.' ���I���U'. "</th >\n";
			}
		
		
			if($pmode == 0){
				echo "<td scope=col align=center><font color=\"blue\">".$std_pstat. "</td>\n";
			}
			else{
				echo "<td scope=col align=center><font color=\"red\">".$std_pstat. "</td>\n";
				}
	
		
	
			echo "</tr>";
	  }
	  $sql->disconnect();*/
?>
        </table>
	      <p>
	        <input type="submit" name="Submit" value="�e�X"/>
		  </p>
	    </form>
	    <p></p>
	    <p>&nbsp;</p>
		</td></tr></table>
   </td>
  </tr>
  <tr>
	 <td class="intd">&nbsp;</td>
  </tr>
</table>
<script language="JavaScript">
	tigra_tables('all_table', 2, 0, '#FFFFFF', '#F0F0FD', '#8FBEFD', '#8FBEFD');
</script>
</body>
</html>
<?
//	}
?>