<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_log_slow";
$def->class = "erLhAbstractModelLogSlow";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['atime'] = new ezcPersistentObjectProperty();
$def->properties['atime']->columnName   = 'atime';
$def->properties['atime']->propertyName = 'atime';
$def->properties['atime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['ttime'] = new ezcPersistentObjectProperty();
$def->properties['ttime']->columnName   = 'ttime';
$def->properties['ttime']->propertyName = 'ttime';
$def->properties['ttime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['message'] = new ezcPersistentObjectProperty();
$def->properties['message']->columnName   = 'message';
$def->properties['message']->propertyName = 'message';
$def->properties['message']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def; 

?>