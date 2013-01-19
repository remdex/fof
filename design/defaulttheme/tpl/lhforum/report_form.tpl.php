<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
    
<?php elseif (isset($messageSend)): ?>

<div class="dataupdate"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Message send');?></div>

<?php else :?>

<div style="padding:10px">
<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Report abuse');?></h1>
</div>

<div id="issue-body">
<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/login')?>">

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Please write whats wrong within a post');?></label>
<textarea class="default-textarea" style="width:300px;height:100px;" id="PostWrongText"></textarea>
</div>

<input class="default-button" id="IDReportButton" onclick="sendReport(<?=$message->id?>)" type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Send');?>" name="Login" />

</form>
</div>

</div>

<script>
function sendReport(msg_id) {
    var pdata = {    
        'message_user' : $('#PostWrongText').val()       
    };        
    $('#IDReportButton').attr('disabled','disabled');
     
    $.postJSON("<?=erLhcoreClassDesign::baseurl('forum/report')?>/"+msg_id, pdata , function(data){	
               
        $('#IDReportButton').removeAttr('disabled');
        
		if (data.error == 'false') {	
			$('#issue-body').html(data.result); 
			$.colorbox.resize();
		} else {
		    $('#issue-body .error').remove();
		    $('#issue-body').prepend(data.result);
		    $.colorbox.resize();
		};
        return true;	          
	});
};
</script>

<?php endif;?>