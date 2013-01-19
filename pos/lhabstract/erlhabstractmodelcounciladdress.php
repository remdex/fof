<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_council_address";
$def->class = "erLhAbstractModelCouncilAddress";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['contact_person'] = new ezcPersistentObjectProperty();
$def->properties['contact_person']->columnName   = 'contact_person';
$def->properties['contact_person']->propertyName = 'contact_person';
$def->properties['contact_person']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['email'] = new ezcPersistentObjectProperty();
$def->properties['email']->columnName   = 'email';
$def->properties['email']->propertyName = 'email';
$def->properties['email']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['telephone'] = new ezcPersistentObjectProperty();
$def->properties['telephone']->columnName   = 'telephone';
$def->properties['telephone']->propertyName = 'telephone';
$def->properties['telephone']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['address1'] = new ezcPersistentObjectProperty();
$def->properties['address1']->columnName   = 'address1';
$def->properties['address1']->propertyName = 'address1';
$def->properties['address1']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['address2'] = new ezcPersistentObjectProperty();
$def->properties['address2']->columnName   = 'address2';
$def->properties['address2']->propertyName = 'address2';
$def->properties['address2']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['council_id'] = new ezcPersistentObjectProperty();
$def->properties['council_id']->columnName   = 'council_id';
$def->properties['council_id']->propertyName = 'council_id';
$def->properties['council_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>