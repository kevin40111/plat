

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
	<title>�ܧ�i�J�Ǯ�</title>
<script language="JavaScript" src="tigra_tables.js"></script>
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
.style1 {font-size: 16px}
.style2 {font-size: 24px}
.style3 {
	color: #000000;
	font-size: 14px;
}
.style5 {font-size: 11px}
.style9 {font-size: 16px; font-family: "�s�ө���"; }
.style12 {font-size: 16px; font-family: "�s�ө���"; color: #0033FF; }
.style15 {color: #FFFFFF}
</style>
</head>

<body bottommargin="0" topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" bgcolor="white">
<table cellpadding="3" cellspacing="1" border="0" width="100%" align="center">
	<tr>
	  <td class="header2">&nbsp;�n�J��T</td>
	</tr>
	<tr>
		<td class="intd">
		  <p>�z�ثe�n�J���b����&nbsp;
		  	<font color="red">
		  		
		  	</font>�A�z�n�C
		  </p>
	</tr>
	<tr>
	  <td class="header2">&nbsp;�ܧ�i�J�Ǯ�</td>
	</tr>
	<tr><td><p>&nbsp;</p></td></tr>
	<tr><td><p>&nbsp;</p></td></tr>
	<tr>
		<td>
			<table id="depart" width="75%" align="center" border="1" style="display:inline">
				<tr>
					<td class="header1" align="center">&nbsp;<span class="style1">�ܧ�i�J�Ǯ�</span></td>
				</tr>
				<tr>
					<td>
							<form name="form2" method="post" action="changschid.php">
								<p align="center" class="style3 style5">�п�ܾǮաA�ë��e�X</p>
								<p align="center">
								<select name="uid" id="uid">
								<option value='0'>------------------------------</option>
								<?php
									while ($obj_all = $sql->objects('',$obj_query)){
											echo "<option value='$obj_all->uid'>$obj_all->uname($obj_all->uid)</option>";
									}
								?>
							  </select>
							  <input type="submit" name="Submit2" value="�e�X">
							  </p>
							</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>

</table>


</body>
</html>
