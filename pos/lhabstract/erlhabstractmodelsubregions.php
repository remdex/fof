<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_sub_regions";
$def->class = "erLhAbstractModelSubRegions";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['position'] = new ezcPersistentObjectProperty();
$def->properties['position']->columnName   = 'position';
$def->properties['position']->propertyName = 'position';
$def->properties['position']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['region_id'] = new ezcPersistentObjectProperty();
$def->properties['region_id']->columnName   = 'region_id';
$def->properties['region_id']->propertyName = 'region_id';
$def->properties['region_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['visible'] = new ezcPersistentObjectProperty();
$def->properties['visible']->columnName   = 'visible';
$def->properties['visible']->propertyName = 'visible';
$def->properties['visible']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['distance'] = new ezcPersistentObjectProperty();
$def->properties['distance']->columnName   = 'distance';
$def->properties['distance']->propertyName = 'distance';
$def->properties['distance']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['lat'] = new ezcPersistentObjectProperty();
$def->properties['lat']->columnName   = 'lat';
$def->properties['lat']->propertyName = 'lat';
$def->properties['lat']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['lon'] = new ezcPersistentObjectProperty();
$def->properties['lon']->columnName   = 'lon';
$def->properties['lon']->propertyName = 'lon';
$def->properties['lon']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['aurl'] = new ezcPersistentObjectProperty();
$def->properties['aurl']->columnName   = 'aurl';
$def->properties['aurl']->propertyName = 'aurl';
$def->properties['aurl']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def; 

?>