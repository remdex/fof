<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_period";
$def->class = "erLhAbstractModelPeriod";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

foreach (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'available_locales' ) as $locale) { 
   $def->properties['name_'.strtolower($locale)] = new ezcPersistentObjectProperty();
   $def->properties['name_'.strtolower($locale)]->columnName   = 'name_'.strtolower($locale);
   $def->properties['name_'.strtolower($locale)]->propertyName = 'name_'.strtolower($locale);
   $def->properties['name_'.strtolower($locale)]->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
}

$def->properties['position'] = new ezcPersistentObjectProperty();
$def->properties['position']->columnName   = 'position';
$def->properties['position']->propertyName = 'position';
$def->properties['position']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['value'] = new ezcPersistentObjectProperty();
$def->properties['value']->columnName   = 'value';
$def->properties['value']->propertyName = 'value';
$def->properties['value']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['best_choise'] = new ezcPersistentObjectProperty();
$def->properties['best_choise']->columnName   = 'best_choise';
$def->properties['best_choise']->propertyName = 'best_choise';
$def->properties['best_choise']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['check_by_default'] = new ezcPersistentObjectProperty();
$def->properties['check_by_default']->columnName   = 'check_by_default';
$def->properties['check_by_default']->propertyName = 'check_by_default';
$def->properties['check_by_default']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def; 

?>