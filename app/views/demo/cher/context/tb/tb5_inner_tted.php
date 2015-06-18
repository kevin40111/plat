<?php
session_start();
$census_school_array = $_SESSION['census_school_array'];
?>
<script type="text/javascript" src="js/compareMeans.js"></script>

<div class="page7_box"> 
<div class="page7_box_title">選擇依變數及分類變數</div>
<table border="0" cellspacing="5" cellpadding="0" style="width:600px;margin:2px auto;border:0px dashed #ddd">
<tr>

<td style="border:2px dashed #777;background-color:#fff">

<table>
	<tr><th align="center" colspan="2"><div style="width:570px">依變數</div></th></tr>
    
	<tr>
    <td width="46px"><button class="selectbtn empty" state="empty" group="6"></button></td>
    <td><ul name="select_box" group="6" max_select="1"></ul><span name="hints" style="color:#999"><img src="./images/warning.png" />選擇題目後點選向右箭頭加入題目<br /><span style="color:#F00">(限選一題)</span></span></td>
    </tr>
</table>

</td>

</tr>
<tr>

<td style="border:2px dashed #777;background-color:#fff">

<table>
	<tr><th align="center" colspan="2"><div style="width:570px">分類變數</div></th></tr>
    
	<tr>
    <td width="46px"><button class="selectbtn empty" state="empty" group="7"></button></td>
    <td><ul name="select_box" group="7" max_select="1"></ul><span name="hints" style="color:#999"><img src="./images/warning.png" />選擇題目後點選向右箭頭加入題目<br /><span style="color:#F00">(限選一題)</span></span></td>
    </tr>
</table>

<table id="compareMeans_variable_box" style="display:none;width:580px">
	<tr>
    <td style="border:2px dashed #aaa;font-size:9px;width:180px"><table id="compareMeans_combine_org" style="border-spacing:0 7px"></table></td>
    <td style="border:2px dashed #aaa;font-size:9px"><table id="compareMeans_combine" style="border-spacing:0 7px"></table>
	</td>    
    </tr>
</table>

</td>

</tr>
</table>
</div>

<div class="page7_box">
<div class="page7_box_title">勾選要輸出的統計量</div>
					

	<div style="border: 0px solid #aaaaaa;float:left;margin:0;padding:5px;display:none">
    <!--<div style="background-color:#888;margin:-4px;margin-bottom:1px;padding:1px;width:80px;text-align:center;color:#fff"></div>-->
	<input type="checkbox" name="otherval_1" checked="checked" /><span>模式摘要</span>
    <input type="checkbox" name="otherval_2" checked="checked" /><span>迴歸係數</span>
	<input type="checkbox" name="otherval_3" /><span>敘述統計</span>
    <input type="checkbox" name="otherval_4" /><span>相關係數</span>
    </div>	
    
    <p style="margin:0;margin-left:5px; clear:left">選擇輸出資料小數點後位數<select name="dotmount" style="padding:6px;border-radius: 5px;-webkit-border-radius: 5px;border: 1px solid silver;">
    <?
	for($i=1;$i<10;$i++){
		echo '<option value="'.$i.'" '.($i==3?'selected="selected"':'').'>'.$i.'</option>';
	}
	?>
	</select></p>
							
</div>



<div class="page7_box">
<div class="page7_box_title">是否選擇加權</div>
<table width="150" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input type="radio" name="ext_pg4" value="1" checked="checked" />是</td>
    <td><input type="radio" name="ext_pg4" value="0" />否</td>  
  </tr>
</table>
</div>
					
<div class="page7_box"> 
<div class="page7_box_title">分析對象</div>


<div class="page7-box2">
<table width="590" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="111" height="25" style="background:#ccc">
        	<input type="checkbox" name="ext5" value="4" disabled="disabled" checked="checked" style="display:none" />
            <? if( $_SESSION['userType'] == "school" ){ ?>
            <p style="margin:2px;padding:1px;border:2px solid #fff">
        	<span>本校：</span>
            <input type="checkbox" name="target_group" value="my-child" />幼教學程
            <input type="checkbox" name="target_group" value="my-elementary" />小教學程
            <input type="checkbox" name="target_group" value="my-secondary" />中教學程
            <input type="checkbox" name="target_group" value="my-special" />特教學程
            <input type="checkbox" name="target_group" value="my-none" />不分學程
            </p>
            <? } ?>
            <p style="margin:2px;padding:1px;border:2px solid #fff">
            <span>全國：</span>
            <input type="checkbox" name="target_group" value="all-child" />幼教學程
            <input type="checkbox" name="target_group" value="all-elementary" />小教學程
            <input type="checkbox" name="target_group" value="all-secondary" />中教學程
            <input type="checkbox" name="target_group" value="all-special" />特教學程
            <input type="checkbox" name="target_group" value="all-none" />不分學程
            </p>
            <p style="margin:2px;padding:1px;border:2px solid #fff">
            <span>公立：</span>
			<input type="checkbox" name="target_group" value="public-child" ext_a1="1" />幼教學程
			<input type="checkbox" name="target_group" value="public-secondary" ext_a1="3" />中教學程
			<input type="checkbox" name="target_group" value="public-none" ext_a1="5" />不分學程
            </p>
            <p style="margin:2px;padding:1px;border:2px solid #fff">
            <span>私立：</span>
			<input type="checkbox" name="target_group" value="private-child" ext_a1="1" />幼教學程
			<input type="checkbox" name="target_group" value="private-secondary" ext_a1="3" />中教學程
			<input type="checkbox" name="target_group" value="private-none" ext_a1="5" />不分學程
            </p>
		</td>	
	</tr>
</table>
</div>






</div>
												
