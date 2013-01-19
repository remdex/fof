<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/report_form.tpl.php');
$message = erLhcoreClassModelForumMessage::fetch((int)$Params['user_parameters']['msg_id']);
$tpl->set('message',$message);
 
if (isset($_POST['message_user'])) {

    $definition = array(
        'message_user' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'message_user' ) || $form->message_user == '' )
    {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('forum/report','Please enter message!');
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
        $phpMailer->Subject = erTranslationClassLhTranslation::getInstance()->getTranslation('forum/report','Forum message report') . " - " . $_SERVER['HTTP_HOST'];
        
        $tplMail = new erLhcoreClassTemplate( 'lhforum/report_mail.tpl.php');
        
        $tplMail->set('message',$message);
        $tplMail->set('message_user',$form->message_user);
        
        $phpMailer->MsgHTML($tplMail->fetch());
                         
        if ($phpMailer->Send())
        {
            
        } 
         
        $reportMessage = new erLhcoreClassModelForumReport();
        $reportMessage->msg_id = $message->id;
        $reportMessage->message = $form->message_user;
        $reportMessage->ctime = time();
        $reportMessage->saveThis();
        
        $tpl->set('messageSend',true);
        echo json_encode(array('error' => 'false','result' => $tpl->fetch()));
        exit;
        
    } else { 
        $tpl->set('errArr',$Errors);
        echo json_encode(array('error' => 'true','result' => $tpl->fetch()));
        exit;
    }    
      
} else {
    echo $tpl->fetch();
}
exit;