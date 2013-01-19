<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_basket_session";
$def->class = "erLhcoreClassModelShopBasketSession";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['session_hash'] = new ezcPersistentObjectProperty();
$def->properties['session_hash']->columnName   = 'session_hash';
$def->properties['session_hash']->propertyName = 'session_hash';
$def->properties['session_hash']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['session_hash_crc32'] = new ezcPersistentObjectProperty();
$def->properties['session_hash_crc32']->columnName   = 'session_hash_crc32';
$def->properties['session_hash_crc32']->propertyName = 'session_hash_crc32';
$def->properties['session_hash_crc32']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['mtime'] = new ezcPersistentObjectProperty();
$def->properties['mtime']->columnName   = 'mtime';
$def->properties['mtime']->propertyName = 'mtime';
$def->properties['mtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 

 
return $def; 

?>