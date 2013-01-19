<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_council";
$def->class = "erLhAbstractModelCouncil";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['lat'] = new ezcPersistentObjectProperty();
$def->properties['lat']->columnName   = 'lat';
$def->properties['lat']->propertyName = 'lat';
$def->properties['lat']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_FLOAT;

$def->properties['lon'] = new ezcPersistentObjectProperty();
$def->properties['lon']->columnName   = 'lon';
$def->properties['lon']->propertyName = 'lon';
$def->properties['lon']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_FLOAT;

$def->properties['location'] = new ezcPersistentObjectProperty();
$def->properties['location']->columnName   = 'location';
$def->properties['location']->propertyName = 'location';
$def->properties['location']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['telephone'] = new ezcPersistentObjectProperty();
$def->properties['telephone']->columnName   = 'telephone';
$def->properties['telephone']->propertyName = 'telephone';
$def->properties['telephone']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

// alias name - used in URL
$def->properties['aname'] = new ezcPersistentObjectProperty();
$def->properties['aname']->columnName   = 'aname';
$def->properties['aname']->propertyName = 'aname';
$def->properties['aname']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

// Other option.
$def->properties['other'] = new ezcPersistentObjectProperty();
$def->properties['other']->columnName   = 'other';
$def->properties['other']->propertyName = 'other';
$def->properties['other']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

// Position, to force other in the top always
$def->properties['position'] = new ezcPersistentObjectProperty();
$def->properties['position']->columnName   = 'position';
$def->properties['position']->propertyName = 'position';
$def->properties['position']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def; 

?>