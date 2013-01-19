<title><? if (isset($Result['path'])) : ?>
<? 
$ReverseOrder = $Result['path'];
krsort($ReverseOrder);
foreach ($ReverseOrder as $pathItem) : ?>
 <?=$pathItem['title']?>&laquo;
<? endforeach;?>
<? endif; ?>
<?=erConfigClassLhConfig::getInstance()->getSetting( 'site', 'title' )?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?=erLhcoreClassDesign::designCSS('css/foundation.css;js/markitup/skins/simple/style.css;js/markitup/sets/bbcode/style.css');?>" /> 
<link rel="icon" type="image/png" href="<?=erLhcoreClassDesign::design('images/favicon.ico')?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?=erLhcoreClassDesign::design('images/favicon.ico')?>" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<script type="text/javascript">
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::designJS('js/jquery.js;js/hw.js;js/markitup/jquery.markitup.js;js/markitup/sets/bbcode/set.js');?>"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>
