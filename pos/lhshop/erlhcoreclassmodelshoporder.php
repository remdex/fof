<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_order";
$def->class = "erLhcoreClassModelShopOrder";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['order_time'] = new ezcPersistentObjectProperty();
$def->properties['order_time']->columnName   = 'order_time';
$def->properties['order_time']->propertyName = 'order_time';
$def->properties['order_time']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['basket_id'] = new ezcPersistentObjectProperty();
$def->properties['basket_id']->columnName   = 'basket_id';
$def->properties['basket_id']->propertyName = 'basket_id';
$def->properties['basket_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['status'] = new ezcPersistentObjectProperty();
$def->properties['status']->columnName   = 'status';
$def->properties['status']->propertyName = 'status';
$def->properties['status']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['email'] = new ezcPersistentObjectProperty();
$def->properties['email']->columnName   = 'email';
$def->properties['email']->propertyName = 'email';
$def->properties['email']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['payment_gateway'] = new ezcPersistentObjectProperty();
$def->properties['payment_gateway']->columnName   = 'payment_gateway';
$def->properties['payment_gateway']->propertyName = 'payment_gateway';
$def->properties['payment_gateway']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['currency'] = new ezcPersistentObjectProperty();
$def->properties['currency']->columnName   = 'currency';
$def->properties['currency']->propertyName = 'currency';
$def->properties['currency']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 
return $def; 

?>