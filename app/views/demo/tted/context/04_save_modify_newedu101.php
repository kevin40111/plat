<? 
//��Ʈw�s���A�Φs������ƪ�

$newcid = Input::get('elementid');
$value = Input::get('newvalue'); 

//echo $newcid."+".$value;

DB::update('update tted_edu_102.dbo.newedu101_userinfo set pstat = $value where newcid= ?', array('$newcid'));  
//DB::update('update tted_edu_102.dbo.graduation102_userinfo set pstat = 0 where newcid= ?', array('25418924724'));  

//�N�ȶǦ^�e�� 
 if($value == 0)
	    {$pstat ='�ק令:�լd��H';}
	else if($value == 1)
	    {$pstat ='�ק令:�D�լd��H';}
				  
echo $pstat;

?>	
