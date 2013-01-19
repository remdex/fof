<?php

$tpl = erLhcoreClassTemplate::getInstance('lhsystemconfig/edit.tpl.php');

// If already set during account update
$ConfigData = erLhcoreClassSystemConfig::getSession()->load( 'erLhcoreClassModelSystemConfig', $Params['user_parameters']['config_id'] );

if (isset($_POST['UpdateConfig']))
{
	switch ($ConfigData->type) {
		case erLhcoreClassModelSystemConfig::SITE_ACCESS_PARAM_ON:
			
				$data = array();
				foreach (erConfigClassLhConfig::getInstance()->getSetting('site','available_site_access') as $siteaccess)
				{
					$data[$siteaccess] = $_POST['Value'.$siteaccess];
				}					
				$ConfigData->value = serialize($data);
			break;
			
		case erLhcoreClassModelSystemConfig::SITE_ACCESS_PARAM_OFF:
				$ConfigData->value = $_POST['ValueParam'];
			break;
	
		default:
			break;
	}
		

	erLhcoreClassSystemConfig::getSession()->update( $ConfigData );
	$tpl->set('data_updated',true);
}

$tpl->set('systemconfig',$ConfigData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','System configuration')),
array('url' => erLhcoreClassDesign::baseurl('systemconfig/list'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','System config options')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Edit'))
)

?>