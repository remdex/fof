<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_oid_map";
$def->class = "erLhcoreClassModelOidMap";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['open_id'] = new ezcPersistentObjectProperty();
$def->properties['open_id']->columnName   = 'open_id';
$def->properties['open_id']->propertyName = 'open_id';
$def->properties['open_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['email'] = new ezcPersistentObjectProperty();
$def->properties['email']->columnName   = 'email';
$def->properties['email']->propertyName = 'email';
$def->properties['email']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['open_id_type'] = new ezcPersistentObjectProperty();
$def->properties['open_id_type']->columnName   = 'open_id_type';
$def->properties['open_id_type']->propertyName = 'open_id_type';
$def->properties['open_id_type']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>