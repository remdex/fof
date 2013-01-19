<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhfeedback/form.tpl.php');

$formData = array(
'FormName' => '',
'FormEmail' => '',
'FormText' => ''
);

if (isset($_POST['SendRequest']))
{    
   $definition = array(
        'FormName' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        ),
        'FormEmail' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
        ),
        'FormText' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        ),
        'CaptchaCode' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'FormName' ) || $form->FormName == '' )
    {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Please enter name!');
    }    
    
    if ( !$form->hasValidData( 'FormEmail' ) )
    {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Invalid e-mail address!');
    }
    
    if ( !$form->hasValidData( 'FormText' ) || $form->FormText == '' )
    {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Please enter text!');
    }
  
    if ( !$form->hasValidData( 'CaptchaCode' ) || $form->CaptchaCode == '' || $form->CaptchaCode != $_SESSION[$_SERVER['REMOTE_ADDR']]['feedback_form'] )
    {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Please enter captcha code!');
    }   
    
    if (count($Errors) == 0)
    {
        $adminEmail = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'site_admin_email' );
                        
               
        $phpMailer = new PHPMailer();
        $phpMailer->AddAddress($adminEmail);
        $phpMailer->Sender = $adminEmail;
        $phpMailer->FromName = $adminEmail;
        $phpMailer->From = $adminEmail;
        $phpMailer->CharSet  = 'utf-8';
        $phpMailer->Subject = erTranslationClassLhTranslation::getInstance()->getTranslation('feedback/form','Feedback') . " - " . $_SERVER['HTTP_HOST'];
        
        $tplMail = new erLhcoreClassTemplate( 'lhfeedback/mail/form.tpl.php');
        
        $tplMail->set('FormName',$form->FormName);
        $tplMail->set('FormEmail',$form->FormEmail);
        $tplMail->set('FormText',$form->FormText);
                
        $phpMailer->MsgHTML($tplMail->fetch());        
                  
        if ($phpMailer->Send())
        {
            
        } 
         
        $tpl->set('messageSend',true);
        $tpl->setFile('lhfeedback/formsend.tpl.php');  
        
    } else {
        
        $formData['FormName'] = $form->FormName;
        $formData['FormText'] = $form->FormText;
        
        if ( $form->hasValidData( 'FormEmail' ) )
        {
           $formData['FormEmail'] = $form->FormEmail;
        }      
        
        $tpl->set('errArr',$Errors);
    }

}

$tpl->set('form_data',$formData);
$Result['content'] = $tpl->fetch();

?>