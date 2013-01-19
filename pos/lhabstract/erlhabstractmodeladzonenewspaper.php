<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_ad_zone_newspaper";
$def->class = "erLhAbstractModelAdZoneNewspaper";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['ad_id'] = new ezcPersistentObjectProperty();
$def->properties['ad_id']->columnName   = 'ad_id';
$def->properties['ad_id']->propertyName = 'ad_id';
$def->properties['ad_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['newspaper_id'] = new ezcPersistentObjectProperty();
$def->properties['newspaper_id']->columnName   = 'newspaper_id';
$def->properties['newspaper_id']->propertyName = 'newspaper_id';
$def->properties['newspaper_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['newspaper_group_id'] = new ezcPersistentObjectProperty();
$def->properties['newspaper_group_id']->columnName   = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyName = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;
?>