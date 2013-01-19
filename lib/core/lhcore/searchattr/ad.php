<?php

$fieldsSearch = array();

$fieldsSearch['keyword'] = array(
    'type' => 'text',
    'trans' => 'Keyword',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => 'filterkeyword',
    'filter_table_field' => 'keyword',
    'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    )
);

$fieldsSearch['date'] = array (
    'type' => 'text',
    'trans' => 'Date',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => 'filterdate',
    'filter_table_field' => 'ptime',
    'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', array( 'min_range' => 0 )
    )
);

$fieldsSearch['distance'] = array (
    'type' => 'combobox',
    'trans' => 'Distance',
    'filter_table_field' => 'distance',    
    'required' => false,
    'filter_type' => false,
    'valid_if_filled' => true,   
    'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int' 
    )
);

$fieldsSearch['type'] = array (
		'type' => 'text',
		'trans' => 'Notice type',
		'required' => false,
		'valid_if_filled' => false,
		'filter_type' => 'filter',
		'filter_table_field' => 'classification_id',
		'validation_definition' => new ezcInputFormDefinitionElement(
				ezcInputFormDefinitionElement::OPTIONAL, 'int', array( 'min_range' => 1, 'max_range' => 10 )
		)
);

$fieldsSearch['sortby'] = array (
    'type' => 'text',
    'trans' => 'Sort by',
    'required' => false,
    'valid_if_filled' => false,
    'filter_type' => 'none',  
    'validation_definition' => new ezcInputFormDefinitionElement (
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
    )
);

$fieldSortAttr = array (
'field' => 'sortby',
'default' => 'new',
'options' => erLhcoreClassRenderHelper::renderArray(array('list_function_params' => array('filter' => array('sort_type' => 'search')), 'list_function' => 'erLhAbstractModelSortOption::getList','identifier' => 'identifier', 'elements_items' => array('sort_column' => 'value'))) 
);

return array(
    'filterAttributes' => $fieldsSearch,
    'sortAttributes'   => $fieldSortAttr
);