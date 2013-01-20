<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_article";
$def->class = "erLhcoreClassModelArticle";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['article_name'] = new ezcPersistentObjectProperty();
$def->properties['article_name']->columnName   = 'article_name';
$def->properties['article_name']->propertyName = 'article_name';
$def->properties['article_name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['intro'] = new ezcPersistentObjectProperty();
$def->properties['intro']->columnName   = 'intro';
$def->properties['intro']->propertyName = 'intro';
$def->properties['intro']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['file_name'] = new ezcPersistentObjectProperty();
$def->properties['file_name']->columnName   = 'file_name';
$def->properties['file_name']->propertyName = 'file_name';
$def->properties['file_name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['body'] = new ezcPersistentObjectProperty();
$def->properties['body']->columnName   = 'body';
$def->properties['body']->propertyName = 'body';
$def->properties['body']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['publishtime'] = new ezcPersistentObjectProperty();
$def->properties['publishtime']->columnName   = 'publishtime';
$def->properties['publishtime']->propertyName = 'publishtime';
$def->properties['publishtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['mtime'] = new ezcPersistentObjectProperty();
$def->properties['mtime']->columnName   = 'mtime';
$def->properties['mtime']->propertyName = 'mtime';
$def->properties['mtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['descriptionoveride'] = new ezcPersistentObjectProperty();
$def->properties['descriptionoveride']->columnName   = 'descriptionoveride';
$def->properties['descriptionoveride']->propertyName = 'descriptionoveride';
$def->properties['descriptionoveride']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['category_id'] = new ezcPersistentObjectProperty();
$def->properties['category_id']->columnName   = 'category_id';
$def->properties['category_id']->propertyName = 'category_id';
$def->properties['category_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['has_photo'] = new ezcPersistentObjectProperty();
$def->properties['has_photo']->columnName   = 'has_photo';
$def->properties['has_photo']->propertyName = 'has_photo';
$def->properties['has_photo']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['pos'] = new ezcPersistentObjectProperty();
$def->properties['pos']->columnName   = 'pos';
$def->properties['pos']->propertyName = 'pos';
$def->properties['pos']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['is_modal'] = new ezcPersistentObjectProperty();
$def->properties['is_modal']->columnName   = 'is_modal';
$def->properties['is_modal']->propertyName = 'is_modal';
$def->properties['is_modal']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['alias_url'] = new ezcPersistentObjectProperty();
$def->properties['alias_url']->columnName   = 'alias_url';
$def->properties['alias_url']->propertyName = 'alias_url';
$def->properties['alias_url']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['alternative_url'] = new ezcPersistentObjectProperty();
$def->properties['alternative_url']->columnName   = 'alternative_url';
$def->properties['alternative_url']->propertyName = 'alternative_url';
$def->properties['alternative_url']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def; 

?>