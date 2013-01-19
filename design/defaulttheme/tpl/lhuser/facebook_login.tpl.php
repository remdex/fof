<?php if ( erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'enabled' ) == true ) : 
                $facebook = new Facebook(array(
                		'appId'  => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'app_id' ),
                		'secret' => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'secret' ) ,
                ));
                $appendURL = isset($appendURL) ? $appendURL : null;
                $loginUrl = $facebook->getLoginUrl(array('scope' => 'email','redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('user/completefblogin').$appendURL ));
                ?>
    <a href="<?=$loginUrl?>" class="button facebook"><span aria-hidden="true" data-icon="&#x43;"></span> Sign in with Facebook</a>
<?php endif;?>