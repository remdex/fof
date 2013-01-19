<script>
var WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>
<script src="<?=erConfigClassLhConfig::getInstance()->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::designJS('js/jquery.js;js/colorbox.js;js/hw.js;js/markitup/jquery.markitup.js;js/markitup/sets/bbcode/set.js');?>"></script>
<?=isset($Result['additional_js_prepend']) ? $Result['additional_js_prepend'] : ''?>