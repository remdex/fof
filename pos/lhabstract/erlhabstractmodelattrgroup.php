<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_attr_group";
$def->class = "erLhAbstractModelAttrGroup";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['display_name'] = new ezcPersistentObjectProperty();
$def->properties['display_name']->columnName   = 'display_name';
$def->properties['display_name']->propertyName = 'display_name';
$def->properties['display_name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['data_type'] = new ezcPersistentObjectProperty();
$def->properties['data_type']->columnName   = 'data_type';
$def->properties['data_type']->propertyName = 'data_type';
$def->properties['data_type']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['identifier'] = new ezcPersistentObjectProperty();
$def->properties['identifier']->columnName   = 'identifier';
$def->properties['identifier']->propertyName = 'identifier';
$def->properties['identifier']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['identifier_value'] = new ezcPersistentObjectProperty();
$def->properties['identifier_value']->columnName   = 'identifier_value';
$def->properties['identifier_value']->propertyName = 'identifier_value';
$def->properties['identifier_value']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['defaultvalue'] = new ezcPersistentObjectProperty();
$def->properties['defaultvalue']->columnName   = 'defaultvalue';
$def->properties['defaultvalue']->propertyName = 'defaultvalue';
$def->properties['defaultvalue']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['position'] = new ezcPersistentObjectProperty();
$def->properties['position']->columnName   = 'position';
$def->properties['position']->propertyName = 'position';
$def->properties['position']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['parent_id'] = new ezcPersistentObjectProperty();
$def->properties['parent_id']->columnName   = 'parent_id';
$def->properties['parent_id']->propertyName = 'parent_id';
$def->properties['parent_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['has_child'] = new ezcPersistentObjectProperty();
$def->properties['has_child']->columnName   = 'has_child';
$def->properties['has_child']->propertyName = 'has_child';
$def->properties['has_child']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def; 

?>