<?php

$twitterObj = new EpiTwitter(erConfigClassLhConfig::getInstance()->getSetting( 'twitter', 'consumer_key' ), erConfigClassLhConfig::getInstance()->getSetting( 'twitter', 'consumer_secret' ));

?>

<a class="button twitter" href="<?=$twitterObj->getAuthenticateUrl()?>"><span aria-hidden="true" data-icon="&#x44;"></span>Sign in with Twitter</a>