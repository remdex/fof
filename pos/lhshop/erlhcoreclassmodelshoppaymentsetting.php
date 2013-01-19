<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_payment_setting";
$def->class = "erLhcoreClassModelShopPaymentSetting";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['identifier'] = new ezcPersistentObjectProperty();
$def->properties['identifier']->columnName   = 'identifier';
$def->properties['identifier']->propertyName = 'identifier';
$def->properties['identifier']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 
$def->properties['param'] = new ezcPersistentObjectProperty();
$def->properties['param']->columnName   = 'param';
$def->properties['param']->propertyName = 'param';
$def->properties['param']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;  

$def->properties['value'] = new ezcPersistentObjectProperty();
$def->properties['value']->columnName   = 'value';
$def->properties['value']->propertyName = 'value';
$def->properties['value']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

return $def;

?>