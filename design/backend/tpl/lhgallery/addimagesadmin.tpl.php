<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/addimages','Add images to album')?> - <?=htmlspecialchars($album->title)?></h1>
</div>
<? if (isset($errArr)) : ?>
<div class="error-list">
<br />

<ul>
<?php foreach ($errArr as $err) : ?>
    <li><?=$err?></li>
<?php endforeach;?>
</ul>
</div>
<? endif;?>
<div id="ad-image-upload">
	<noscript>
		<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/orderitemdetails/orderitemdetails_display_ad_magazine','Please enable JavaScript to upload image');?></p>
	</noscript>
</div>

<ul id="listElement-ad-image">
	
</ul>

<input type="hidden" id="AlbumIDToUpload" value="<?=$album->aid?>" />

<input type="button" onclick="uploader.startUpload()" class="default-button" id="ConvertButton" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Upload')?>" />

<script type="text/javascript">
var uploader = new qq.FileUploader({
    element: document.getElementById('ad-image-upload'),
    listElement: document.getElementById('listElement-ad-image'),
    action: '<?=erLhcoreClassDesign::baseurl('gallery/upload')?>',
    allowedExtensions:[<?=erLhcoreClassModelSystemConfig::fetch('allowed_file_types')->current_value;?>],
    autoStart : false,
    sizeLimit : <?=(int)(erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024)?>,
    maxFiles : <?=(int)(erLhcoreClassModelSystemConfig::fetch('file_upload_limit')->current_value)?>,
    paramsCallback : function(file_id) {
        return {
				title	    : $('#PhotoTitle'+file_id).val(),
				keywords    : $('#PhotoKeyword'+file_id).val(),				
				description	: $('#PhotoDescription'+file_id).val(),				
				anaglyph    : $('#PhotoAnaglyph'+file_id).attr('checked'),
				album_id    : $('#AlbumIDToUpload').val()
		}
    },
    onComplete: function(id, fileName, responseJSON) {
        if (responseJSON.success == 'true'){
            var strintID = String(id);       
            $('#file_id_row_'+strintID.replace('qq-upload-handler-iframe','')).fadeOut();
        }
    },
    onStart : function(){ 
        $('.qq-upload-spinner').addClass('active-spinner');
        return true;
    },
	template: '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                '<div class="qq-upload-button"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/publicupload','Click to choose images to upload, you can also drop files here.')?></div>' +
                '<ul class="qq-upload-list"></ul>' + 
             '</div>',
    fileTemplate: '<li class="float-break" id="file_id_row_{file_id}"><span class="qq-upload-file"></span>' +
                '<span class="qq-upload-spinner"></span>' +
                '<span class="qq-upload-size"></span>' +
                '<a class="qq-upload-cancel" href="#"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/uploadadorderitem_content','Cancel');?></a>' +
                '<span class="qq-upload-failed-text"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('lhad/uploadadorderitem_content','Failed');?></span>' +
                '<div class="right"><div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Title')?></div>' +
				'<input type="text" id="PhotoTitle{file_id}" value="" class="inputfield" /> ' +
				'<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Keywords')?></div>	'+ 
				'<input type="text" id="PhotoKeyword{file_id}" value="" class="inputfield" />' +					
				'<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Cross eye image')?></div>	' +
				'<input type="checkbox" id="PhotoAnaglyph{file_id}" value="" class="inputfield" />' +
				'<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Caption')?></div>	' +		
				'<textarea class="default-textarea" id="PhotoDescription{file_id}"></textarea>' +
				'</div></li>',
    multiple: true
});
</script>



