<?php

$tpl = erLhcoreClassTemplate::getInstance('lhsystemconfig/watermark.tpl.php');

// If already set during account update
$ConfigData = erLhcoreClassSystemConfig::getSession()->load( 'erLhcoreClassModelSystemConfig', 'watermark_data' );

if (isset($_POST['UpdateConfig']))
{
	$configDataArray = $ConfigData->data;
	
	$definition = array(              
        'WatermarkEnabled' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'DeleteWaterMark' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'WatermarkPosition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'PaddingX' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        ),
        'PaddingY' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        )
    );
    
	$form = new ezcInputForm( INPUT_POST, $definition );
    
    if ( $form->hasValidData( 'WatermarkEnabled' ) && $form->WatermarkEnabled == 'thumbnails')
    {
        $configDataArray['watermark_enabled'] = true;
        $configDataArray['watermark_disabled'] = false;
    } else {$configDataArray['watermark_enabled'] = false;}
        
    if ( $form->hasValidData( 'WatermarkEnabled' ) && $form->WatermarkEnabled == 'thumbnails_all')
    {
        $configDataArray['watermark_enabled_all'] = true;
        $configDataArray['watermark_disabled'] = false;        
    } else {$configDataArray['watermark_enabled_all'] = false;}  
    
    if ( $form->hasValidData( 'WatermarkEnabled' ) && $form->WatermarkEnabled == 'thumbnails_none')
    {
        $configDataArray['watermark_disabled'] = true;        
    }  
        
    if ( $form->hasValidData( 'WatermarkPosition' ) )
    {
        $configDataArray['watermark_position'] = $form->WatermarkPosition;        
    }  
          
    if ( $form->hasValidData( 'PaddingX' ) )
    {
        $configDataArray['watermark_position_padding_x'] = $form->PaddingX;        
    } 
          
    if ( $form->hasValidData( 'PaddingY' ) )
    {
        $configDataArray['watermark_position_padding_y'] = $form->PaddingY;        
    }  
    
    if ( $form->hasValidData( 'DeleteWaterMark' ) && $form->DeleteWaterMark == true)
    {
    	$file = erLhcoreClassSystem::instance()->SiteDir.'/var/watermark/'.$configDataArray['watermark'];    	
    	if (file_exists($file))	unlink($file);    	
    	$configDataArray['watermark'] = '';
    }
    
	if (isset($_FILES["WatermarkFile"]) && is_uploaded_file($_FILES["WatermarkFile"]["tmp_name"]) && $_FILES["WatermarkFile"]["error"] == 0 && erLhcoreClassImageConverter::isPhoto('WatermarkFile'))
	{
		if ($configDataArray['watermark'] != '')
		{
			$file = erLhcoreClassSystem::instance()->SiteDir.'/var/watermark/'.$configDataArray['watermark'];    	
	    	if (file_exists($file))	unlink($file);    	
	    	$configDataArray['watermark'] = '';
		}
		
		$fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().'-'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['WatermarkFile']['name']);		
		move_uploaded_file($_FILES["WatermarkFile"]["tmp_name"],'var/watermark/'.$fileNamePhysic);
		$configDataArray['watermark'] = $fileNamePhysic;
		
		$imageAnalyze = new ezcImageAnalyzer( 'var/watermark/'.$fileNamePhysic );
		
		$configDataArray['size_x'] = $imageAnalyze->data->width;		
		$configDataArray['size_y'] = $imageAnalyze->data->height;		
		
	} elseif (!isset($configDataArray['watermark'])) {		
			$configDataArray['watermark'] = '';			
			$configDataArray['watermark_disabled'] = false;
	} elseif ($configDataArray['watermark'] == ''){
			$configDataArray['watermark_disabled'] = true;
			$configDataArray['watermark_enabled_all'] = false;
			$configDataArray['watermark_enabled'] = false;
	}
	
	$ConfigData->value = serialize($configDataArray);
	$ConfigData->data = $configDataArray;

	erLhcoreClassSystemConfig::getSession()->update( $ConfigData );
		
	
	$tpl->set('data_updated',true);
}

$tpl->set('systemconfig',$ConfigData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','System configuration')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/watermark','Watermark')))

?>