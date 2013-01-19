<?php

$def = new ezcPersistentObjectDefinition();
//$def->table = "lh_word_word";

$def->table = erLhcoreClassWord::$wordTable;
$def->class = "erLhcoreClassModelWordWord";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = '_id';
$def->idProperty->propertyName = 'id';
$def->idProperty->propertyType = ezcPersistentObjectProperty::PHP_TYPE_MONGO_ID;
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['word'] = new ezcPersistentObjectProperty();
$def->properties['word']->columnName   = 'word';
$def->properties['word']->propertyName = 'word';
$def->properties['word']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

// Language
$def->properties['ln'] = new ezcPersistentObjectProperty();
$def->properties['ln']->columnName   = 'ln';
$def->properties['ln']->propertyName = 'ln';
$def->properties['ln']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

// Language
$def->properties['we'] = new ezcPersistentObjectProperty();
$def->properties['we']->columnName   = 'we';
$def->properties['we']->propertyName = 'we';
$def->properties['we']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

// Is it dictionary word
$def->properties['dw'] = new ezcPersistentObjectProperty();
$def->properties['dw']->columnName   = 'dw';
$def->properties['dw']->propertyName = 'dw';
$def->properties['dw']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
// Is it main form of word
$def->properties['mf'] = new ezcPersistentObjectProperty();
$def->properties['mf']->columnName   = 'mf';
$def->properties['mf']->propertyName = 'mf';
$def->properties['mf']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['ptime'] = new ezcPersistentObjectProperty();
$def->properties['ptime']->columnName   = 'ptime';
$def->properties['ptime']->propertyName = 'ptime';
$def->properties['ptime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_MONGO_DATE;

// Form of word, points to self
$def->properties['fof'] = new ezcPersistentObjectProperty();
$def->properties['fof']->columnName   = 'fof';
$def->properties['fof']->propertyName = 'fof';
$def->properties['fof']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_ARRAY;

// Is uppercase word
$def->properties['uc'] = new ezcPersistentObjectProperty();
$def->properties['uc']->columnName   = 'uc';
$def->properties['uc']->propertyName = 'uc';
$def->properties['uc']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

// Word forms
$def->properties['wf'] = new ezcPersistentObjectProperty();
$def->properties['wf']->columnName   = 'wf';
$def->properties['wf']->propertyName = 'wf';
$def->properties['wf']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_ARRAY;

// Is it user word
$def->properties['uw'] = new ezcPersistentObjectProperty();
$def->properties['uw']->columnName   = 'uw';
$def->properties['uw']->propertyName = 'uw';
$def->properties['uw']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

// Synonymous
$def->properties['sn'] = new ezcPersistentObjectProperty();
$def->properties['sn']->columnName   = 'sn';
$def->properties['sn']->propertyName = 'sn';
$def->properties['sn']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_ARRAY;

// Antonyms
$def->properties['an'] = new ezcPersistentObjectProperty();
$def->properties['an']->columnName   = 'an';
$def->properties['an']->propertyName = 'an';
$def->properties['an']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_ARRAY;

return $def; 

?>