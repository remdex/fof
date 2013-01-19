<script>
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script src="<?=erConfigClassLhConfig::getInstance()->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::design('js/hw.js');?>?v=3"></script>
<script src="<?=erConfigClassLhConfig::getInstance()->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::design('js/shop.js');?>?v=3"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>