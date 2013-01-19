<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_order_item";
$def->class = "erLhcoreClassModelShopOrderItem";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['order_id'] = new ezcPersistentObjectProperty();
$def->properties['order_id']->columnName   = 'order_id';
$def->properties['order_id']->propertyName = 'order_id';
$def->properties['order_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['pid'] = new ezcPersistentObjectProperty();
$def->properties['pid']->columnName   = 'pid';
$def->properties['pid']->propertyName = 'pid';
$def->properties['pid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

$def->properties['image_variation_id'] = new ezcPersistentObjectProperty();
$def->properties['image_variation_id']->columnName   = 'image_variation_id';
$def->properties['image_variation_id']->propertyName = 'image_variation_id';
$def->properties['image_variation_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['credits'] = new ezcPersistentObjectProperty();
$def->properties['credits']->columnName   = 'credits';
$def->properties['credits']->propertyName = 'credits';
$def->properties['credits']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['credit_price'] = new ezcPersistentObjectProperty();
$def->properties['credit_price']->columnName   = 'credit_price';
$def->properties['credit_price']->propertyName = 'credit_price';
$def->properties['credit_price']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_FLOAT; 

$def->properties['hash'] = new ezcPersistentObjectProperty();
$def->properties['hash']->columnName   = 'hash';
$def->properties['hash']->propertyName = 'hash';
$def->properties['hash']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['download_count'] = new ezcPersistentObjectProperty();
$def->properties['download_count']->columnName   = 'download_count';
$def->properties['download_count']->propertyName = 'download_count';
$def->properties['download_count']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
return $def; 

?>