<?php

class erLhcoreClassRenderHelper {
    
    public static function renderCombobox($params = array())
    {
       
        $cache = CSCacheAPC::getMem();          
        $cacheKey = CSCacheAPC::multi_implode('attr',$params);
        $cacheKey = md5($cacheKey.'site_version_'.$cache->getCacheVersion('site_version'));
        
        if (($output = $cache->restore($cacheKey)) === false)
        {  
            $onchange = (isset($params['on_change']) && $params['on_change'] != '') ? ' onchange="'.$params['on_change'].'" ' : '';
            
            $output = ''; 
                   
            if (isset($params['optional_field'])){
               $defaultValue = isset($params['default_value']) ? $params['default_value'] : 0;
               $output .= "<option value=\"{$defaultValue}\">{$params['optional_field']}</option>";
            }
            
            $attrId = isset($params['attr_id']) ? $params['attr_id'] : 'id';
            
            if (isset($params['multi_call']) && $params['multi_call'] == true) {
                $items = call_user_func_array($params['list_function'],isset($params['list_function_params']) ? $params['list_function_params'] : array());
            }else {
                $items = call_user_func($params['list_function'],isset($params['list_function_params']) ? $params['list_function_params'] : array());
            }
              
            $nameSelect = isset($params['display_name']) ? $params['display_name'] : 'name';
            
            foreach ($items as $item)
            {
                $selected = ( (isset($params['is_editing']) && $params['is_editing'] == false && $item->{$params['use_default']} == 1 && (!is_array($params['selected_id']) && ($params['selected_id'] === null || $params['selected_id'] === '') )) || (is_array($params['selected_id']) && in_array($item->$attrId,$params['selected_id'])) || $params['selected_id'] == $item->$attrId) ? 'selected="selected"' : '';  
                $valueItem = $item->$nameSelect;
                
                if (isset($params['number_format'])) {
                    $valueItem = number_format($valueItem,0,'.',', ');
                } 
                
                if (isset($params['append_option_value'])) {
                    $valueItem = $params['append_option_value'].$valueItem;
                }
                
                if (isset($params['prepend_option_value'])) {
                    $valueItem = $valueItem.$params['prepend_option_value'];
                }            
                
                $output .= "<option value=\"{$item->$attrId}\" $selected >{$valueItem}</option>";
            } 
    
            $disbled = '';
            if ((isset($params['disable_on_empty']) && count($items) == 0) || (isset($params['disabled_edit']) && $params['disabled_edit'] == true) ) {
                $disbled = ' disabled="disabled" ';
            }
     
            
            
            $classItems = array();        
            $classItems[] = isset($params['is_error']) && $params['is_error'] == true ? 'error-inp' : null;
            $classItems[] = isset($params['css_class']) ? $params['css_class'] : null;
            
            $classItems = array_filter($classItems);  
                       
            $class = count($classItems) > 0 ? ' class="'.implode(' ',$classItems).'" ' : '';
            $title = isset($params['title_element']) ? ' title="'.$params['title_element'].'" ' : null;

            $ismultiple = isset($params['multiple']) ? 'multiple' : '';
                     
            $output = '<select '.$ismultiple.' id="id_'.$params['input_name'].'" name="'.$params['input_name'].'"'.$onchange.$disbled.$class.$title.'>' . $output; 
            
            if (isset($params['append_value'])) {
                $selected = $params['selected_id'] == $params['append_value'][0] ? 'selected="selected"' : '';  
                $output .= "<option value=\"{$params['append_value'][0]}\" $selected >{$params['append_value'][1]}</option>";
            }
                   
            $output .= '</select>';   
            
            $cache->store($cacheKey,$output);     
        }    
        
        return $output;  
    } 
    
    public static function renderCheckbox($params = array())
    {    
        $output  = '';
              
        foreach (call_user_func($params['list_function'],isset($params['list_function_params']) ? $params['list_function_params'] : array()) as $item)
        {            
            $checked = in_array($item->id,$params['selected_id']) ? 'checked="checked"' : '';
            $output .= "<label><input type=\"checkbox\" name=\"{$params['input_name']}\" value=\"{$item->id}\" {$checked} />".htmlspecialchars($item->name)."</label>";
        } 
        
        return $output;  
    }

    public static function renderCheckboxColums($params = array())
    {    
        $output  = '';
        
        $output  .= '<table width="100%">';
           
        $count = 0;
             
        foreach (call_user_func($params['list_function'],isset($params['list_function_params']) ? $params['list_function_params'] : array()) as $item)
        {            
            $checked = in_array($item->id,$params['selected_id']) ? 'checked="checked"' : '';
            
            $output .= '<td>';
            
            $output .= "<label><input type=\"checkbox\" name=\"{$params['input_name']}\" value=\"{$item->id}\" {$checked} />".htmlspecialchars($item->name)."</label>";
       		
            $output .= '</td>';
            
            
            
            $count++;
            
             if ($count == 5) {
            	$output .= '</tr><tr>';
            	$count = 0;
            }
             
       } 
     
        $output  .= '</td></tr></table>';
        return $output;  
    } 
    
    public static function renderRangeCombobox($params)
    {
        $returnArray = array();
        $paramsRender = $params;
        
        $paramsRender['selected_id'] = $params['selected_from'];
        $paramsRender['input_name'] = $params['input_name'].'_from';
        
        if (isset($params['optional_from'])){
            $paramsRender['optional_field'] = $params['optional_from'];
        }               
        $returnArray[0] = self::renderCombobox($paramsRender);
        
        $paramsRender['selected_id'] = $params['selected_to'];
        $paramsRender['input_name'] = $params['input_name'].'_to';
        if (isset($params['optional_to'])){
            $paramsRender['optional_field'] = $params['optional_to'];
        } 
        $returnArray[1] = self::renderCombobox($paramsRender);        
        
        return $returnArray;
    }
    
    public static function renderYear()
    {    
        $output  = '';
        
        $objects = array();
        
        for ($i = (date('Y')-50);$i <= date('Y')+1;$i++) {
           $object = new stdClass();
           $object->id = $i;
           $object->name = $i;
           $objects[] = $object;           
        }
         
        return  array_reverse($objects);
    }
    
    public static function transformURL($obj,$params,$module = 'c') {
        
        $paramsModule = array();
        
        foreach ($params as $key => $value)
        {
            $paramsModule[] = $obj->$key != '' ? $obj->$key.''.$value: '';
        }
                       
        return erLhcoreClassCharTransform::TransformToURL(implode(' ',array_filter($paramsModule))).'-'.$obj->id.$module.'.html';
    }
    
    public static function transformToTitle($obj,$params) {
        
        $paramsModule = array();
        
        foreach ($params as $key => $value)
        {
            if ($value === '') {
                $paramsModule[] = $obj->$key != '' ? $obj->$key.''.$value: '';
            } elseif (is_array($value)) {
                
                $subPart = array();
                foreach ($value as $subvalue) {
                   $subPart[] =  $obj->$subvalue != '' ? (string)$obj->$subvalue: '';
                }
                $valueImploded = trim(implode(' ',$subPart));
                
                if ($valueImploded != '') {
                    $paramsModule[] = $valueImploded;
                }
                
            } else {
                $paramsModule[] = $value;
            }
        }
                       
        return implode(', ',array_filter($paramsModule));
    }
    
    public static function transformToSearchTitle($params)
    {
        $titleItems = array();
        foreach ($params as $key => $paramsItem)
        {
            if ($paramsItem['mode'] == 'direct') {
                
                if (is_array($paramsItem['value'])) {
                    foreach ($paramsItem['value'] as $value){
                        $titleItems[] = $paramsItem['values'][$value];
                    }
                } else {
                    if (isset($paramsItem['values'][$paramsItem['value']])){
                        $titleItems[] = $paramsItem['values'][$paramsItem['value']];
                    }
                }
            } elseif ($paramsItem['mode'] == 'range') {
                
                $string = '';
                if ( $paramsItem['from'] > 0 || $paramsItem['to'] > 0 ) {
                    $string = $paramsItem['title'].' ';
                    if ($paramsItem['from'] > 0) {
                        $string .= 'from '.$paramsItem['prefix'].$paramsItem['from'].$paramsItem['pofix'].' ';
                    }
                    
                    if ($paramsItem['to'] > 0) {
                        $string .= 'to '.$paramsItem['prefix'].$paramsItem['to'].$paramsItem['pofix'].' ';
                    }
                };
                
                $titleItems[] = trim($string);
                
            } elseif ($paramsItem['mode'] == 'make_model') {

                if (is_array($paramsItem['params_make']))
                {
                    foreach ($paramsItem['params_make'] as $key => $make_id)
                    {
                        $pairMakeModel = array();
                        if ($make_id > 0) {
                            $pairMakeModel[] = erLhcoreClassRenderHelper::fetchFromCache(array('function' => $paramsItem['function_make'],'params' => $make_id));
                        }
                        
                        if ( $paramsItem['params_model'][$key] > 0 ) {
                            $pairMakeModel[] = erLhcoreClassRenderHelper::fetchFromCache(array('function' => $paramsItem['function_model'],'params' => $paramsItem['params_model'][$key]));
                        }
                        $titleItems[] = trim(implode(' ',$pairMakeModel));
                    }
                }
                
            } elseif ($paramsItem['mode'] == 'direct_value') {
                if ($paramsItem['params'] > 0)
                {
                    $pofix  = isset($paramsItem['pofix']) ? $paramsItem['pofix'] : '';
                    $prefix = isset($paramsItem['prefix']) ? $paramsItem['prefix'] : '';
                    $titleItems[] = $prefix.$paramsItem['params'].$pofix;
                }
            } else {
                try {
                    if ($paramsItem['params'] > 0){
                        $pofix  = isset($paramsItem['pofix']) ? $paramsItem['pofix'] : '';
                        $prefix = isset($paramsItem['prefix']) ? $paramsItem['prefix'] : '';
                        $titleItems[] = $prefix.erLhcoreClassRenderHelper::fetchFromCache(array('function' => $paramsItem['function'],'params' => $paramsItem['params'])).$pofix;
                    }
                } catch (Exception $e) {
                    
                }
            }
        }
        
        return implode(', ',array_filter($titleItems));
    }

    public static function fetchFromCache($params) {
        
        $cache = CSCacheAPC::getMem();          
        $cacheKey = CSCacheAPC::multi_implode('params_fetch',$params);
        $cacheKey = md5($cacheKey.'site_version_'.$cache->getCacheVersion('site_version'));
        
        if (($output = $cache->restore($cacheKey)) === false)
        {           
            if (isset($params['params1'])){
                $output = call_user_func($params['function'],$params['params'],$params['params1']);    
            } else {
                $output = call_user_func($params['function'],$params['params']);  
            }          
            $cache->store($cacheKey,$output);  
        }  
        
        return $output;
    }

    public static function renderArray($params)
    {
        $items = call_user_func($params['list_function'],isset($params['list_function_params']) ? $params['list_function_params'] : array());
        $array = array();
        
        foreach ($items as $item){
            
            $itemsElement = array();
            foreach ($params['elements_items'] as $identifier => $value){
                $itemsElement[$identifier] = $item->{$value};
            }
            
            $array[$item->{$params['identifier']}] = $itemsElement;
        }
               
        return $array;
    }    
    
    public static function getAttributeAppend($attrCategory)
    {
        $attrAppend = array();
        
        foreach ($attrCategory as $attrObj)
        {
            $attrAppend[] = $attrObj->identifier_value;
                
            if ( $attrObj->has_child == 1 ) {
                foreach (erLhAbstractModelAttrGroup::getList(array('enable_sql_cache' => true,'filter' => array('parent_id' => $attrObj->id))) as $child) { 
                    $attrAppend[] = $child->identifier_value;
                }
            }    
        }
        
        return $attrAppend;
    }
 		    
    public static function renderComboboxLink($params = array())
    {
       
        $cache = CSCacheAPC::getMem(); 

        $attrSelected = isset($params['attr_selected']) ? $params['attr_selected'] : new stdClass();
        $attrSelectedArray = array();
         
        if ( isset($params['attr_selected']) ) {
            $attrSelectedArray = get_object_vars($attrSelected);
            unset($params['attr_selected']);
        }
                
        $cacheKey = CSCacheAPC::multi_implode('attr',$params).'_attr_seelcted_'.CSCacheAPC::multi_implode('attr_si',$attrSelectedArray);
        $cacheKey = md5($cacheKey.'site_version_link_combobox_'.$cache->getCacheVersion('site_version'));
        
        if (($output = $cache->restore($cacheKey)) === false)
        {  
            $onchange = (isset($params['on_change']) && $params['on_change'] != '') ? ' onchange="'.$params['on_change'].'" ' : '';
            
            $output = ''; 
            $optionField = '';
            $selectedFirstItem = '';
            $exclude_attr = isset($params['exclude_attr']) ? $params['exclude_attr'] : array();
            
            if (isset($params['optional_field'])){ 
               $attrURL = erLhcoreClassSearchHandler::getURLAppendFromInput($attrSelected,false,true,array($params['input_name']));
               $output .= "<li><a href=\"{$params['base_url']}{$attrURL}\">{$params['optional_field']}</a></li>";
               $selectedFirstItem = $optionField = "<a class=\"selected-item\">{$params['optional_field']}</a>";
            }
            
            $attrId = isset($params['attr_id']) ? $params['attr_id'] : 'id';
            $attrIdDentifier = isset($params['identifier_value']) ? $params['identifier_value'] : 'identifier_value';
            
            if (isset($params['multi_call']) && $params['multi_call'] == true) {
                $items = call_user_func_array($params['list_function'],isset($params['list_function_params']) ? $params['list_function_params'] : array());
            }else {
                $items = call_user_func($params['list_function'],isset($params['list_function_params']) ? $params['list_function_params'] : array());
            }
              
            $nameSelect = isset($params['display_name']) ? $params['display_name'] : 'name';
                                   
            $counter = 0;
            
            $relationNoFollow = isset($params['nofollow']) && $params['nofollow'] == 1 ? ' rel="nofollow" ' : '';
            
            
            foreach ($items as $item)
            {
                $selected = ( (isset($params['is_editing']) && $params['is_editing'] == false && $item->{$params['use_default']} == 1 && (!is_array($params['selected_id']) && ($params['selected_id'] === null || $params['selected_id'] === '') )) || (is_array($params['selected_id']) && in_array($item->$attrId,$params['selected_id'])) || $params['selected_id'] == $item->$attrId) ? true : false;  
                $valueItem = $item->$nameSelect;
                
                if (isset($params['number_format'])) {
                    $valueItem = number_format($valueItem,0,'.',', ');
                } 
                
                if (isset($params['append_option_value'])) {
                    $valueItem = $params['append_option_value'].$valueItem;
                }
                
                if (isset($params['prepend_option_value'])) {
                    $valueItem = $valueItem.$params['prepend_option_value'];
                }            
                                
                $attrSelectedCurrent = clone $attrSelected;
                $attrSelectedCurrent->{$params['input_name']} = $item->{$attrIdDentifier};
                                
                $attrURL = erLhcoreClassSearchHandler::getURLAppendFromInput($attrSelectedCurrent,false,true,$exclude_attr);
                $output .= "<li><a href=\"{$params['base_url']}{$attrURL}\"{$relationNoFollow}><span>{$valueItem}</span></a></li>";
                
                if ($selected == true || $counter == 0) {
                    
                    if ( $selected == true ) {
                        $selectedFirstItem = "<a class=\"selected-item\">{$valueItem}</a>";
                    } elseif ($counter == 0) { 
                        if ($optionField == '') {
                            $selectedFirstItem = "<a class=\"selected-item\">{$valueItem}</a>";
                        } else {
                            $selectedFirstItem = $optionField;
                        }
                    }                        
                }
                $counter++;
            }
                
            $classItems = array();        
            $classItems[] = isset($params['is_error']) && $params['is_error'] == true ? 'error-inp' : null;
            $classItems[] = isset($params['css_class']) ? $params['css_class'] : null;
            
            $classItems = array_filter($classItems);
            $class = count($classItems) > 0 ? ' class="'.implode(' ',$classItems).'" ' : '';
                                 
            $output = '<ul class="link-select-menu"><li class="current-sort">'.$selectedFirstItem.'<ul class="sort-box">'.$output;                    
            $output .= '</ul></li></ul>';   
            
            $cache->store($cacheKey,$output);     
        }    
        
        return $output;  
    } 
    
    public static function renderAttrGroupLink($params)
    {      
        $attr_group = $params['attr_group'];
        $cssClass = isset($params['css_class']) ? $params['css_class'] : '';
        $renderedItems = array();
        $searchData = isset($params['search']) ? $params['search'] : false;
                
        foreach ($attr_group as $group)
        {
            $selectedID = isset($searchData->{$group->identifier_value}) ? $searchData->{$group->identifier_value} : null;
            
            switch ($group->data_type) {
            	case 'enum':
            		  $dataItem = array();
            		  $dataItem['title'] = $group->display_name;
            		  $dataItem['content'] = erLhcoreClassRenderHelper::renderComboboxLink(array(    	                
    	                'input_name'     => $group->identifier_value,
    	                'selected_id'    => $selectedID,
    	                'display_name'   => 'value',
    	                'optional_field' => 'Select',
    	                'default_value'  => '',
    	                'nofollow'       => $group->no_index,
    	                'attr_id'        => 'identifier_value',
    	                'css_class'      => $cssClass,
    	                'exclude_attr'   => $group->has_child == 1 ? array('car-model') : array(),
    	                'base_url'       => $params['base_url'],
    	                'attr_selected'  => isset($params['attr_selected']) ? $params['attr_selected'] : new stdClass() ,
    	                'list_function'  => 'erLhAbstractModelAttrItem::getList',
    	                'list_function_params' => array('filter' => array('group_id' => $group->id))
    	              ));
    	             $renderedItems[] = $dataItem;
            		break;            
            	default:
            		break;
            }

            if ( $group->has_child == 1 ) {
                foreach (erLhAbstractModelAttrGroup::getList(array('filter' => array('parent_id' => $group->id))) as $child) { 
                    
                    $filterSelected = 0;
                    
                    if ($searchData !== false) {
                        $subSelectedID = isset($searchData->{$child->identifier_value}) ? $searchData->{$child->identifier_value} : null;                       
                        $filterSelected = erLhAbstractModelAttrItem::getAttrIdByGroupAndIdentifier($group->id,$selectedID);
                    } else {
                        $subSelectedID = isset($ad->data['group_id_'.$child->id]) ? $ad->data['group_id_'.$child->id] : null;
                        $filterSelected = $selectedID;
                    }                   
                    
                    switch ($child->data_type) {
                    	case 'enum':
                    		  $dataItem = array();
                    		  $dataItem['title'] = $child->display_name;
                    		  $dataItem['content'] = erLhcoreClassRenderHelper::renderComboboxLink(array(
            	                'input_name'     => $child->identifier_value,
            	                'selected_id'    => $subSelectedID,
            	                'display_name'   => 'value',
            	                'optional_field' => 'Select',
            	                'nofollow'       => $child->no_index,
            	                'default_value'  => '',
            	                'base_url'       => $params['base_url'],
            	                'attr_selected'  => isset($params['attr_selected']) ? $params['attr_selected'] : new stdClass() ,
            	                'attr_id'        => 'identifier_value',
            	                'css_class'      => $cssClass,	                
            	                'list_function'  => 'erLhAbstractModelAttrItem::getList',
            	                'list_function_params' => array('filter' => array('parent_attr_item' => $filterSelected, 'group_id' => $child->id,))
            	              ));
            	              $dataItem['render_depend'] = array('parent' => $group,'child' => $child);
            	             $renderedItems[] = $dataItem;
                    		break;            
                    	default:
                    		break;
                    }
                }
            }
        }
        
        return $renderedItems;
    }
    
    public static function renderAttrGroup($params)
    {
        $ad = isset($params['ad']) ? $params['ad'] : null;
        $attr_group = $params['attr_group'];
        $cssClass = isset($params['css_class']) ? $params['css_class'] : 'select-menu-style select-menu-style2 input-width220';
        $renderedItems = array();
        $searchData = isset($params['search']) ? $params['search'] : false;
                
        foreach ($attr_group as $group)
        {
            if ( $searchData !== false ) {
                $selectedID = isset($searchData->{$group->identifier_value}) ? $searchData->{$group->identifier_value} : null;
            } else {
                $selectedID = isset($ad->data['group_id_'.$group->id]) ? $ad->data['group_id_'.$group->id] : null;
            }
            
            switch ($group->data_type) {
            	case 'enum':
            		  $dataItem = array();
            		  $dataItem['title'] = $group->display_name;
            		  $dataItem['content'] = erLhcoreClassRenderHelper::renderCombobox(array(    	                
    	                'input_name'     => $group->identifier_value,
    	                'selected_id'    => $selectedID,
    	                'display_name'   => 'value',
    	                'optional_field' => 'Select',
    	                'default_value'  => '',
    	                'attr_id'        => 'identifier_value',
    	                'css_class'      => $cssClass,
    	                'list_function'  => 'erLhAbstractModelAttrItem::getList',
    	                'list_function_params' => array('filter' => array('group_id' => $group->id))
    	              ));
    	             $renderedItems[] = $dataItem;
            		break;            
            	default:
            		break;
            }

            if ( $group->has_child == 1 ) {
                foreach (erLhAbstractModelAttrGroup::getList(array('filter' => array('parent_id' => $group->id))) as $child) { 
                    
                    $filterSelected = 0;
                    
                    if ($searchData !== false) {
                        $subSelectedID = isset($searchData->{$child->identifier_value}) ? $searchData->{$child->identifier_value} : null;                       
                        $filterSelected = erLhAbstractModelAttrItem::getAttrIdByGroupAndIdentifier($group->id,$selectedID);
                    } else {
                        $subSelectedID = isset($ad->data['group_id_'.$child->id]) ? $ad->data['group_id_'.$child->id] : null;
                        $filterSelected = $selectedID;
                    }                   
                    
                    switch ($child->data_type) {
                    	case 'enum':
                    		  $dataItem = array();
                    		  $dataItem['title'] = $child->display_name;
                    		  $dataItem['content'] = erLhcoreClassRenderHelper::renderCombobox(array(
            	                'input_name'     => $child->identifier_value,
            	                'selected_id'    => $subSelectedID,
            	                'display_name'   => 'value',
            	                'optional_field' => 'Select',
            	                'default_value'  => '',
            	                'attr_id'        => 'identifier_value',
            	                'css_class'      => $cssClass,	                
            	                'list_function'  => 'erLhAbstractModelAttrItem::getList',
            	                'list_function_params' => array('filter' => array('parent_attr_item' => $filterSelected, 'group_id' => $child->id,))
            	              ));
            	              $dataItem['render_depend'] = array('parent' => $group,'child' => $child);
            	             $renderedItems[] = $dataItem;
                    		break;            
                    	default:
                    		break;
                    }
                }
            }
        }
        
        return $renderedItems;
    }
    
    
    public static function renderAttrGroupEdit($params)
    {
        $ad = isset($params['ad']) ? $params['ad'] : null;
        $attr_group = $params['attr_group'];
        $cssClass = isset($params['css_class']) ? $params['css_class'] : 'select-menu-style select-menu-style2 input-width220';
        $renderedItems = array();
        $searchData = isset($params['search']) ? $params['search'] : false;
        $hideAttr = isset($params['hide_attr']) ? $params['hide_attr'] : array();
                
        foreach ($attr_group as $group)
        {
            if ($searchData !== false) {
                $selectedID = isset($searchData->{'attr_group_'.$group->id}) ? $searchData->{'attr_group_'.$group->id} : null;
            } else {
                $selectedID = isset($ad->data['group_id_'.$group->id]) ? $ad->data['group_id_'.$group->id] : null;
            }        
            
            switch ($group->data_type) {
                case 'enum':
                          $dataItem = array();
                          $dataItem['title'] = $group->display_name;
                          $dataItem['identifier'] = $group->identifier;
                          
                          $renderParams = array(
                            'input_name'     => 'attr_group_'.$group->id,
                            'selected_id'    => $selectedID,
                            'display_name'   => 'value',
                            //'optional_field' => 'Select',
                            'default_value'  => '',
                            'css_class'      => $cssClass,
                            'list_function'  => 'erLhAbstractModelAttrItem::getList',
                            'list_function_params' => array('filter' => array('group_id' => $group->id))
                          );
                          
                          if ( !in_array($group->identifier,$hideAttr) ) {
                              $renderParams['optional_field'] = 'Select';
                          }
                          
                          $dataItem['content'] = erLhcoreClassRenderHelper::renderCombobox($renderParams);
                     $renderedItems[] = $dataItem;
                        break;            
                default:
                        break;
            }
            
            
            if ( $group->has_child == 1 ) {
                foreach (erLhAbstractModelAttrGroup::getList(array('filter' => array('parent_id' => $group->id))) as $child) { 
                    
                    if ($searchData !== false) {
                        $subSelectedID = isset($searchData->{'attr_group_'.$child->id}) ? $searchData->{'attr_group_'.$child->id} : null;
                    } else {
                        $subSelectedID = isset($ad->data['group_id_'.$child->id]) ? $ad->data['group_id_'.$child->id] : null;
                    }
                    
                    switch ($child->data_type) {
                        case 'enum':
                                  $dataItem = array();
                                  $dataItem['title'] = $child->display_name;
                                  $dataItem['identifier'] = $child->identifier;
                                  $dataItem['content'] = erLhcoreClassRenderHelper::renderCombobox(array(
                                'input_name'     => 'attr_group_'.$child->id,
                                'selected_id'    => $subSelectedID,
                                'display_name'   => 'value',
                                'optional_field' => 'Select',
                                'default_value'  => '',
                                'css_class'      => $cssClass,                  
                                'list_function'  => 'erLhAbstractModelAttrItem::getList',
                                'list_function_params' => array('filter' => array('parent_attr_item' => $selectedID, 'group_id' => $child->id,))
                              ));
                              $dataItem['render_depend'] = array('parent' => $group,'child' => $child);
                             $renderedItems[] = $dataItem;
                                break;            
                        default:
                                break;
                    }
                }
            }
        }
        
        return $renderedItems;
    }
}