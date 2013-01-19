<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/profilesettings.tpl.php' );

$currentUser = erLhcoreClassUser::instance();
$UserData = $currentUser->getUserData();
$profile = $UserData->profile;              

if (isset($_POST['Update_account']))
{    
   $definition = array(
        'Name' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'Surname' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'Intro' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'Website' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( $form->hasValidData( 'Name' ) )
    {
        $profile->name = $form->Name;
    } 
    
    if ( $form->hasValidData( 'Surname' ) )
    {
        $profile->surname = $form->Surname;
    } 
    
    if ( $form->hasValidData( 'Intro' ) )
    {
        $profile->intro = $form->Intro;
    } 
    
    if ( $form->hasValidData( 'Website' ) )
    {
        $profile->website = $form->Website;
    } 
        
    if ( empty($Errors) ) {
        
        if ( isset($_POST['DeletePhoto']) ) {
            $profile->removeFile();
        } 
        
        if (isset($_FILES["qqfile"]) && is_uploaded_file($_FILES["qqfile"]["tmp_name"]) && $_FILES["qqfile"]["error"] == 0 && erLhcoreClassImageConverter::isPhoto('qqfile'))
	    {
	        $profile->removeFile();
	        
            $dir = 'albums/userpics/profile/' . date('Y') . 'y/' . date('m') . '/' . date('d') .'/' . $profile->id . '/';
            erLhcoreClassImageConverter::mkdirRecursive( $dir );
                        
            $qqfile = new qqFileUploader(array('jpg','png','jpeg'));
            $resultUpload = $qqfile->handleUpload($dir,true);
                        
            if ( isset($resultUpload['success']) && $resultUpload['success'] == 'true' ) { 
                $profile->removeFile();
    	    	$profile->photo           = $qqfile->getFileName();
        		$profile->filepath        = dirname($qqfile->getFilePath());
            }
	    }
    }
        
    if (count($Errors) == 0)
    {        
        $profile->updateThis();
        $tpl->set('account_updated','done');        
    }
}


$tpl->set('profile',$profile);
$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('user/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Profile settings'))
);

?>