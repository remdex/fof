<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_base_setting";
$def->class = "erLhcoreClassModelShopBaseSetting";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'identifier';
$def->idProperty->propertyName = 'identifier';
$def->idProperty->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );
 
$def->properties['value'] = new ezcPersistentObjectProperty();
$def->properties['value']->columnName   = 'value';
$def->properties['value']->propertyName = 'value';
$def->properties['value']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['explain'] = new ezcPersistentObjectProperty();
$def->properties['explain']->columnName   = 'explain';
$def->properties['explain']->propertyName = 'explain';
$def->properties['explain']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;  
  
return $def; 

?>