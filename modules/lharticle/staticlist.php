<?php

$tpl = erLhcoreClassTemplate::getInstance('lharticle/staticlist.tpl.php');
$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Static articles'))
)

?>