<title><? if (isset($Result['path'])) : ?>
<? $ReverseOrder = $Result['path'];
krsort($ReverseOrder);
foreach ($ReverseOrder as $pathItem) : ?>
<?=$pathItem['title']?>&laquo;
<? endforeach;?>
<? endif; ?>
<?=erConfigClassLhConfig::getInstance()->getSetting( 'site', 'title' )?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?=erLhcoreClassDesign::designCSS('css/foundation.css;css/app.css');?>" /> 
<link rel="icon" type="image/png" href="<?=erLhcoreClassDesign::design('images/favicon.ico')?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?=erLhcoreClassDesign::design('images/favicon.ico')?>" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<script type="text/javascript">
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::designJS('js/jquery.js;js/foundation.min.js');?>"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>
<meta name="viewport" content="width=device-width" />
<!-- IE Fix for HTML5 Tags -->
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->