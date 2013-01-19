<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_image_variation";
$def->class = "erLhcoreClassModelShopImageVariation";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['width'] = new ezcPersistentObjectProperty();
$def->properties['width']->columnName   = 'width';
$def->properties['width']->propertyName = 'width';
$def->properties['width']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['height'] = new ezcPersistentObjectProperty();
$def->properties['height']->columnName   = 'height';
$def->properties['height']->propertyName = 'height';
$def->properties['height']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;  

$def->properties['credits'] = new ezcPersistentObjectProperty();
$def->properties['credits']->columnName   = 'credits';
$def->properties['credits']->propertyName = 'credits';
$def->properties['credits']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['position'] = new ezcPersistentObjectProperty();
$def->properties['position']->columnName   = 'position';
$def->properties['position']->propertyName = 'position';
$def->properties['position']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['type'] = new ezcPersistentObjectProperty();
$def->properties['type']->columnName   = 'type';
$def->properties['type']->propertyName = 'type';
$def->properties['type']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>