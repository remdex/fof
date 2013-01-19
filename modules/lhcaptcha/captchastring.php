<?php 

$captcha = erLhcoreClassModelForgotPassword::randomPassword();

$_SESSION[$_SERVER['REMOTE_ADDR']][$Params['user_parameters']['captcha_name']] = $captcha;
			 
echo json_encode(array('result' => $captcha)); 

exit;