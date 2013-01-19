<?php

class erLhcoreClassShopMail {

	function __construct()
	{

	}

	public static function sendOrderMail($order) {
		
		$host = $_SERVER['HTTP_HOST'];	
			
		$adminEmail = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'site_admin_email' );	
			
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->From = $adminEmail;
		$mail->FromName = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'title' );
		$mail->Subject = erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Order confirmed');

		$tpl = new erLhcoreClassTemplate();	
		$tpl->set('order',$order);			
		$mail->Body    = $tpl->fetch('lhshop/mails/ordermail.tpl.php');
		
		$tpl->set('order',$order);
		$mail->AltBody = $tpl->fetch('lhshop/mails/ordermail_plain.tpl.php');
		$mail->AddAddress( $order->email);
				
		$mail->Send();
		$mail->ClearAddresses();
	}
	
	public static function sendOrderCreditsMail($order) {
		
		$host = $_SERVER['HTTP_HOST'];	
			
		$adminEmail = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'site_admin_email' );	
			
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->From = $adminEmail;
		$mail->FromName = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'title' );
		$mail->Subject = erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Credits order confirmed');

		$tpl = new erLhcoreClassTemplate();	
		$tpl->set('order',$order);			
		$mail->Body    = $tpl->fetch('lhshop/mails/ordermail_credits.tpl.php');
		
		$tpl->set('order',$order);
		$mail->AltBody = $tpl->fetch('lhshop/mails/ordermail_credits_plain.tpl.php');
		$mail->AddAddress( $order->user->email);
				
		$mail->Send();
		$mail->ClearAddresses();
	}

}


?>