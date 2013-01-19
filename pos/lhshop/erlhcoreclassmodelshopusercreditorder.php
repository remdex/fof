<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_user_credit_order";
$def->class = "erLhcoreClassModelShopUserCreditOrder";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['credits'] = new ezcPersistentObjectProperty();
$def->properties['credits']->columnName   = 'credits';
$def->properties['credits']->propertyName = 'credits';
$def->properties['credits']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['status'] = new ezcPersistentObjectProperty();
$def->properties['status']->columnName   = 'status';
$def->properties['status']->propertyName = 'status';
$def->properties['status']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['date'] = new ezcPersistentObjectProperty();
$def->properties['date']->columnName   = 'date';
$def->properties['date']->propertyName = 'date';
$def->properties['date']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

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