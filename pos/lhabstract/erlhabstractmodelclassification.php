<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_classification";
$def->class = "erLhAbstractModelClassification";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

foreach (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'available_locales' ) as $locale) {
	$def->properties['name_'.strtolower($locale)] = new ezcPersistentObjectProperty();
	$def->properties['name_'.strtolower($locale)]->columnName   = 'name_'.strtolower($locale);
	$def->properties['name_'.strtolower($locale)]->propertyName = 'name_'.strtolower($locale);
	$def->properties['name_'.strtolower($locale)]->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
}

$def->properties['position'] = new ezcPersistentObjectProperty();
$def->properties['position']->columnName   = 'position';
$def->properties['position']->propertyName = 'position';
$def->properties['position']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['identifier'] = new ezcPersistentObjectProperty();
$def->properties['identifier']->columnName   = 'identifier';
$def->properties['identifier']->propertyName = 'identifier';
$def->properties['identifier']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def;
?>