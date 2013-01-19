<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_user_oauth";
$def->class = "erLhcoreClassModelUserOauth";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentSequenceGenerator' );

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['twitter_user_id'] = new ezcPersistentObjectProperty();
$def->properties['twitter_user_id']->columnName   = 'twitter_user_id';
$def->properties['twitter_user_id']->propertyName = 'twitter_user_id';
$def->properties['twitter_user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['user_name'] = new ezcPersistentObjectProperty();
$def->properties['user_name']->columnName   = 'user_name';
$def->properties['user_name']->propertyName = 'user_name';
$def->properties['user_name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['oauth_token'] = new ezcPersistentObjectProperty();
$def->properties['oauth_token']->columnName   = 'oauth_token';
$def->properties['oauth_token']->propertyName = 'oauth_token';
$def->properties['oauth_token']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['oauth_token_secret'] = new ezcPersistentObjectProperty();
$def->properties['oauth_token_secret']->columnName   = 'oauth_token_secret';
$def->properties['oauth_token_secret']->propertyName = 'oauth_token_secret';
$def->properties['oauth_token_secret']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

return $def; 

?>