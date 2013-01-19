<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhsystemconfig/list.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','System configuration')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','List'))
)
?>