<?
##########################################################################################
#
# filename: gra102_uploadtimes.php
# function: 102�Ǧ~�װ�����ǲ��~�͸�ƤW�Ǫ��A
# ���@��  : �P�a�N
# ���@���: 2013/6/16
#
##########################################################################################
include("../../../css/use100.css");  
 session_start();
 
 $city = substr($_SESSION['sname'], 0, 6);	 

if (!($_SESSION['Login'])){
		header("Location: ../../index.php");
	}
      $sch_id=$_SESSION['sch_id'];
 include("../../../../../../../../../home/leon/data/use101/config/setting101.inc.php");
	  
	  $sql = new mod_db();
	  $list_string = "SELECT s.[gsch_id]
							  ,s.[grasname] 
							  ,count(u.[uploadtime]) as times
							  ,max(u.[uploadtime]) as time
						  FROM [use_public].[dbo].[pub_school_gra] s
						  left join [use_102].[dbo].[upload102] u
						  on u.school = s.[gsch_id]
						  where s.city ="."'$city'" ."and s.year ='102'
						  group by s.[gsch_id],s.[grasname]
						  order by s.[gsch_id]" ;
	  $all_query = $sql->query("$list_string");
	  echo $city;
	  $sql->disconnect();
	  	  
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
	<title>���񵪸��</title>
<script language="JavaScript" src="../../../js/tigra_tables.js"></script>
</head>
<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white">


<table cellpadding="3" cellspacing="1" border="0" width="100%" align="center">
	<tr>
	  <td class="header2">&nbsp;102�Ǧ~�װ�����ǲ��~�͸�ƤW�Ǫ��A</td>
	</tr>
	<tr>
		<td>
		<table cellpadding=4 cellspacing=0 border=0 align="left" width="95%">
		  <tr>
			<td>
		       <table width="50%" border="1" id="all_table"  cellpadding="5">
		       <tr class="header1">
               						<th width="5%" scope="col">�ǮեN��</th>
									<th width="5%" scope="col">�ǮզW��</th>
                                    <th width="5%" scope="col">�W�Ǧ���</th>
                                    <th width="5%" scope="col">�̫�W�Ǯɶ�</th>
                                   </tr>
<?
	  while ($obj_all = $sql->objects('',$all_query)){
			echo "<tr>";
			echo "<td scope=col align=".center.">".$obj_all->depcode."</td>\n";
			echo "<td scope=col align=".center.">".$obj_all->clsname."</td>\n";
			echo "<td scope=col align=".center.">".$obj_all->stdname."</td>\n";
			echo "<td scope=col align=".center.">".$obj_all->birth."</td>\n";
			
			echo "</tr>";
	
	  }
?>          </table>
		</td></tr></table>
	</td>
  </tr>
</table>
<script language="JavaScript">
	tigra_tables('all_table', 2, 0, '#FFFFFF', '#F0F0FD', '#8FBEFD', '#8FBEFD');
</script>
</body>
</html>