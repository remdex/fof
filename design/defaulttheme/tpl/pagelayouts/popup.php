<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="margin:0;background:#FFFFFF;">
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
<script>
var WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>
<script src="<?=erConfigClassLhConfig::getInstance()->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::designJS('js/jquery.js;js/colorbox.js;js/hw.js;js/markitup/jquery.markitup.js;js/markitup/sets/bbcode/set.js');?>"></script>

</head>
<body style="margin:0;background:#FFFFFF;">	 
    <div class="popup-page-layout">
		 <?					
		     echo $Result['content'];		
		 ?>	
	</div>
</body>
</html>