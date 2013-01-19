<?php

$fieldsSearch = array();

$fieldsSearch['email'] = array (
    'type' => 'text',
    'trans' => 'Sort by',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => 'filter',  
    'filter_table_field' => 'email',
    'validation_definition' => new ezcInputFormDefinitionElement (
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
    )
);

$fieldSortAttr = array (
'field'      => false,
'default'    => false,
'serialised' => true,
'disabled' => true,
'options'    => erLhcoreClassRenderHelper::renderArray(array('list_function_params' => array('filter' => array('sort_type' => 'account')), 'list_function' => 'erLhAbstractModelSortOption::getList','identifier' => 'identifier', 'elements_items' => array('sort_column' => 'value'))) 
); 

return array(
    'filterAttributes' => $fieldsSearch,
    'sortAttributes'   => $fieldSortAttr
);