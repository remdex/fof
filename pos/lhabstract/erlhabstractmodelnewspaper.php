<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_newspaper";
$def->class = "erLhAbstractModelNewspaper";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['newspaper_group_id'] = new ezcPersistentObjectProperty();
$def->properties['newspaper_group_id']->columnName   = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyName = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['real_address'] = new ezcPersistentObjectProperty();
$def->properties['real_address']->columnName   = 'real_address';
$def->properties['real_address']->propertyName = 'real_address';
$def->properties['real_address']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['contact_email'] = new ezcPersistentObjectProperty();
$def->properties['contact_email']->columnName   = 'contact_email';
$def->properties['contact_email']->propertyName = 'contact_email';
$def->properties['contact_email']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['telephone_numbers'] = new ezcPersistentObjectProperty();
$def->properties['telephone_numbers']->columnName   = 'telephone_numbers';
$def->properties['telephone_numbers']->propertyName = 'telephone_numbers';
$def->properties['telephone_numbers']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

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

$def->properties['url_header'] = new ezcPersistentObjectProperty();
$def->properties['url_header']->columnName   = 'url_header';
$def->properties['url_header']->propertyName = 'url_header';
$def->properties['url_header']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['url_footer'] = new ezcPersistentObjectProperty();
$def->properties['url_footer']->columnName   = 'url_footer';
$def->properties['url_footer']->propertyName = 'url_footer';
$def->properties['url_footer']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['css'] = new ezcPersistentObjectProperty();
$def->properties['css']->columnName   = 'css';
$def->properties['css']->propertyName = 'css';
$def->properties['css']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['filename_footer'] = new ezcPersistentObjectProperty();
$def->properties['filename_footer']->columnName   = 'filename_footer';
$def->properties['filename_footer']->propertyName = 'filename_footer';
$def->properties['filename_footer']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['filename_footer_path'] = new ezcPersistentObjectProperty();
$def->properties['filename_footer_path']->columnName   = 'filename_footer_path';
$def->properties['filename_footer_path']->propertyName = 'filename_footer_path';
$def->properties['filename_footer_path']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['filename_header'] = new ezcPersistentObjectProperty();
$def->properties['filename_header']->columnName   = 'filename_header';
$def->properties['filename_header']->propertyName = 'filename_header';
$def->properties['filename_header']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['filename_header_path'] = new ezcPersistentObjectProperty();
$def->properties['filename_header_path']->columnName   = 'filename_header_path';
$def->properties['filename_header_path']->propertyName = 'filename_header_path';
$def->properties['filename_header_path']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['country'] = new ezcPersistentObjectProperty();
$def->properties['country']->columnName   = 'country';
$def->properties['country']->propertyName = 'country';
$def->properties['country']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['sitecode'] = new ezcPersistentObjectProperty();
$def->properties['sitecode']->columnName   = 'sitecode';
$def->properties['sitecode']->propertyName = 'sitecode';
$def->properties['sitecode']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['subdomain'] = new ezcPersistentObjectProperty();
$def->properties['subdomain']->columnName   = 'subdomain';
$def->properties['subdomain']->propertyName = 'subdomain';
$def->properties['subdomain']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['include_type'] = new ezcPersistentObjectProperty();
$def->properties['include_type']->columnName   = 'include_type';
$def->properties['include_type']->propertyName = 'include_type';
$def->properties['include_type']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['header_html'] = new ezcPersistentObjectProperty();
$def->properties['header_html']->columnName   = 'header_html';
$def->properties['header_html']->propertyName = 'header_html';
$def->properties['header_html']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['footer_html'] = new ezcPersistentObjectProperty();
$def->properties['footer_html']->columnName   = 'footer_html';
$def->properties['footer_html']->propertyName = 'footer_html';
$def->properties['footer_html']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['meta_html'] = new ezcPersistentObjectProperty();
$def->properties['meta_html']->columnName   = 'meta_html';
$def->properties['meta_html']->propertyName = 'meta_html';
$def->properties['meta_html']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['domain'] = new ezcPersistentObjectProperty();
$def->properties['domain']->columnName   = 'domain';
$def->properties['domain']->propertyName = 'domain';
$def->properties['domain']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['is_iframe'] = new ezcPersistentObjectProperty();
$def->properties['is_iframe']->columnName   = 'is_iframe';
$def->properties['is_iframe']->propertyName = 'is_iframe';
$def->properties['is_iframe']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def; 

?>