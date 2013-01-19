<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_attr_item";
$def->class = "erLhAbstractModelAttrItem";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['value'] = new ezcPersistentObjectProperty();
$def->properties['value']->columnName   = 'value';
$def->properties['value']->propertyName = 'value';
$def->properties['value']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 
$def->properties['value_short'] = new ezcPersistentObjectProperty();
$def->properties['value_short']->columnName   = 'value_short';
$def->properties['value_short']->propertyName = 'value_short';
$def->properties['value_short']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['position'] = new ezcPersistentObjectProperty();
$def->properties['position']->columnName   = 'position';
$def->properties['position']->propertyName = 'position';
$def->properties['position']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['group_id'] = new ezcPersistentObjectProperty();
$def->properties['group_id']->columnName   = 'group_id';
$def->properties['group_id']->propertyName = 'group_id';
$def->properties['group_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['parent_attr_item'] = new ezcPersistentObjectProperty();
$def->properties['parent_attr_item']->columnName   = 'parent_attr_item';
$def->properties['parent_attr_item']->propertyName = 'parent_attr_item';
$def->properties['parent_attr_item']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['identifier_value'] = new ezcPersistentObjectProperty();
$def->properties['identifier_value']->columnName   = 'identifier_value';
$def->properties['identifier_value']->propertyName = 'identifier_value';
$def->properties['identifier_value']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

return $def;
?>