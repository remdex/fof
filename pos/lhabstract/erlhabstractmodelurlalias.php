<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_url_alias";
$def->class = "erLhAbstractModelUrlAlias";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['url_alias'] = new ezcPersistentObjectProperty();
$def->properties['url_alias']->columnName   = 'url_alias';
$def->properties['url_alias']->propertyName = 'url_alias';
$def->properties['url_alias']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['url_destination'] = new ezcPersistentObjectProperty();
$def->properties['url_destination']->columnName   = 'url_destination';
$def->properties['url_destination']->propertyName = 'url_destination';
$def->properties['url_destination']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

return $def;