<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_postcode";
$def->class = "erLhAbstractModelPostcode";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['postcode'] = new ezcPersistentObjectProperty();
$def->properties['postcode']->columnName   = 'postcode';
$def->properties['postcode']->propertyName = 'postcode';
$def->properties['postcode']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['paper'] = new ezcPersistentObjectProperty();
$def->properties['paper']->columnName   = 'paper';
$def->properties['paper']->propertyName = 'paper';
$def->properties['paper']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['lat'] = new ezcPersistentObjectProperty();
$def->properties['lat']->columnName   = 'lat';
$def->properties['lat']->propertyName = 'lat';
$def->properties['lat']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_FLOAT;

$def->properties['lon'] = new ezcPersistentObjectProperty();
$def->properties['lon']->columnName   = 'lon';
$def->properties['lon']->propertyName = 'lon';
$def->properties['lon']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_FLOAT; 

$def->properties['ad_searchtext'] = new ezcPersistentObjectProperty();
$def->properties['ad_searchtext']->columnName   = 'ad_searchtext';
$def->properties['ad_searchtext']->propertyName = 'ad_searchtext';
$def->properties['ad_searchtext']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

return $def;

?>