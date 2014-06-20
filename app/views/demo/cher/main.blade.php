
@extends('layout-main')

@section('head')
<title>後期中等教育資料庫查詢平台</title>

<script type="text/javascript" src="<?=asset('js/jquery-1.10.2.min.js')?>"></script>
<script type="text/javascript" src="<?=asset('js/angular.min.js')?>"></script>
<!--[if lt IE 9]><script src="<?=asset('js/html5shiv.js')?>"></script><![endif]-->

<link href="<?=asset('demo/use/css/use100_content.css')?>" rel="stylesheet" type="text/css" />

<script type="text/javascript">
$(document).ready(function(){	//選單功能

    $('.queryLogBtn').click(function(){
        if( $('.queryLog').css('height')==='0px' ){
            $('.queryLog').animate({height: '50%'}); 
        }else{            
            $('.queryLog').animate({height: '0%'}); 
        }
    });
    $('.context').click(function(){
        $('.queryLog').height(0);
    });

    $('.shareBtn').click(function(){
        if( $('.share').css('left')==='0px' ){
            $('.share').animate({left: -501}); 
        }else{
            $('.share').animate({left: 0}); 
        }
    });
});
</script>

@stop

@section('body')

<div style="width: 100%;height: 100%;max-height:100%">

	<div style="width:100%;height: 30px;position: absolute;z-index:10;background-color: #fff">
		<div style="background-color: #ffffff;width:100%;height:0px"></div>
		<div style="background-color: #458A00;width:100%;height:30px;line-height: 30px;border-bottom: 1px solid #ddd;color:#fff" align="right">			
			<div style="float:left">
				<? if( Auth::user()->id==1 ){ ?>
				<a href="<?=URL::to('page/upload')?>" style="margin-left:10px" class="login-bar">上傳檔案</a>
				<? } ?>
			</div>
			<div style="float:right">
				<? if( Auth::user()->id==1 ){ ?>
                <span style="margin-right:10px;cursor: pointer" class="login-bar shareBtn">share</span>
				<span style="margin-right:10px;cursor: pointer" class="login-bar queryLogBtn">queryLog</span>
				<? } ?>
				<a href="<?=URL::to('page/project')?>" style="margin-right:10px" class="login-bar">回首頁</a>
				<a href="<?=URL::to('page/project/profile')?>" style="margin-right:10px" class="login-bar">個人資料</a>
				<a href="<?=URL::to('auth/password/change')?>" style="margin-right:10px" class="login-bar">更改密碼</a>
				<a href="<?=URL::to('auth/logout')?>" style="margin-right:10px" class="login-bar">登出</a>
			</div>
        </div>
	</div>
	
	<div class="border-box" style="height:100%;width:100%;background-color: #fff;padding-top:30px">
		
		<div style="height:100%;overflow-y: hidden;float:left">
			<div style="width: 350px;height:100%;background-color: #fff;border-right: 1px solid #ddd;overflow-y: auto;margin-top:0">

				
				<div style="font-size:18px;margin-top:10px;margin-left:10px">
					檔案夾
				</div>
				
				<h2>【 資料庫查詢平台 】</h2>
				<div>				
				<?
				$user = Auth::user();
				$packageDocs = $user->get_file_provider()->lists();
				
                $intent_key = is_null(@$fileAcitver) ? '' : $fileAcitver->intent_key;
				
				foreach($packageDocs as $packageDoc){
					foreach($packageDoc['actives'] as $active){		

						if( $active['active']=='open' ){
							echo '<div class="inbox" style="clear:both;overflow: hidden;cursor:default;margin-top:10px">';
							echo '<div class="count button page-menu '.($intent_key==$active['intent_key']?'active':'').'" folder="" style="font-size:16px;text-decoration: none;float:left;margin-left:10px">';
							//echo '<div class="intent button" intent_key="'.$active['intent_key'].'">'.$active['active'].'</div>';
							echo '<a href="'.URL::to('user/doc/'.$active['intent_key']).'">'.$packageDoc['title'].'</a>';
							echo '</div>';
							echo '</div>';
						}

					}
				}

				?>
				</div>
				
			</div>
		</div>

		<div style="height: 100%;overflow-y: hidden;margin:0 0 0 200px; position: relative" class="context">
            
            <div style="width:500px;position: absolute;top:0;background-color: #fff;border-right: 1px solid #ddd;height: 100%;left:-501px;font-size:16px;overflow: auto" class="share">
                <div style="margin:10px"><?=$request?></div>
            </div>
            
			<div style="height: 100%;overflow: auto;background-color: #fff;font-size:16px;text-align: left;margin-top:0">		              
                <div style="margin:10px"><?=$context?></div>
			</div>		
            
		</div>
		
		<div class="queryLog" style="position: absolute;bottom:0;height:0;width:100%;background-color: #fff;overflow-y: scroll;border-top:1px solid #000">			
			<?
				if( Auth::user()->id==1 ){
					$queries = DB::getQueryLog();
					foreach($queries as $key => $query){
						echo $key.' - ';var_dump($query);echo '<br /><br />';
					}
				}
			?>
		</div>
		
	</div>
	
</div>	

@stop