<?php

class erLhcoreClassSearchHandler {
    
    public static function getParams($params = array() ) {   
        
        $uparams = isset($params['uparams']) ? $params['uparams'] : array();
             
        $fieldsObjects = include('lib/core/lhpn/searchattr/'.$params['module_file'].'.php');
                        
        $fields = $fieldsObjects['filterAttributes'];

        if ( isset($params['category_attr']) && !empty($params['category_attr']) ) {
           $fields = array_merge($fields,$params['category_attr']);
        }    
        
        $orderOptions = $fieldsObjects['sortAttributes'];
        
        foreach ( $fields as $key => $field ) {
            $definition[$key] = $field['validation_definition'];
        }
        
        foreach ($uparams as $key => &$value) {
            if (!is_array($value))
            $value = urldecode($value);
        }

        $inputParams = new stdClass(); 
        $inputFrom = new stdClass();
                     
        $form = new erLhcoreClassInputForm( INPUT_GET, $definition, null, $uparams, isset($params['use_override']) ? $params['use_override'] : false);
        $Errors = array();
            
        foreach ($fields as $key => $field)
        {
            $inputParams->$key = null;
            $inputFrom->$key = null;
            
            if ($form->hasValidData( $key ) && (($field['required'] == false && $field['valid_if_filled'] == false) || ($field['type'] == 'combobox') || ($field['required'] == true && $field['type'] == 'text' && $form->{$key} != '') )) {                
                           
                 $inputParams->$key = $form->{$key}; 
                 $inputFrom->$key = $form->{$key};
                 
                if ( isset($field['depend_fields']) ) {                   
                    foreach ( $field['depend_fields'] as $depend ) {
                        if (!$form->hasValidData( $depend ) && !key_exists($depend,$Errors)) {
                            $Errors[$depend] = $fields[$depend]['trans'].' is required'; 
                        }                    
                    }                
                }            
                 
            } elseif ($field['required'] == true) {
                $Errors[$key] = $field['trans'].' is required'; 
            } elseif (isset($field['valid_if_filled']) && $field['valid_if_filled'] == true && $form->hasValidData( $key ) && $form->{$key} != '' ) {                           
                $inputFrom->$key = $form->{$key};
                $inputParams->$key = $form->{$key}; 
                
                if (isset($field['depend_fields'])) {                   
                    foreach ($field['depend_fields'] as $depend){
                                                
                        if (!$form->hasValidData( $depend ) && !key_exists($depend,$Errors)) {
                            $Errors[$depend] = $fields[$depend]['trans'].' is required'; 
                        }                    
                    }                
                }                
                
            } elseif (isset($field['valid_if_filled']) && $field['valid_if_filled'] == true && isset($_GET[$key]) && $_GET[$key] != '') { 
                $Errors[$key] = $field['trans'].' is filled incorrectly!';              
                $inputFrom->$key = $_GET[$key]; 
            } elseif ( isset($field['depend_fields']) ) { // No value, we can clean dependence fields
                
                foreach ($field['depend_fields'] as $depend) {                                            
                     $inputFrom->$depend = null;                  
                     $inputParams->$depend = null;                  
                }
            }            
        }
               
        
        $filter = array();
        
        if (isset($params['format_filter']) && count($Errors) == 0) { 
                       
            foreach ($fields as $key => $field) {
                                
                if (($field['filter_type'] !== false && $inputParams->$key != '') || $inputParams->$key === 0)  {  
     
                    if ($field['filter_type'] == 'filter' ) {    
                        
                        if (is_bool($inputParams->$key) && $inputParams->$key == true) {
                            $filter[$field['filter_type']][$field['filter_table_field']] = 1;
                        } else {               
                            $filter[$field['filter_type']][$field['filter_table_field']] = $inputParams->$key;
                        }
                    } else if ($field['filter_type'] == 'filterlike' ) {    
                       $filter[$field['filter_type']][$field['filter_table_field']] =  $inputParams->$key;   
                        
                    } else if ($field['filter_type'] == 'filterdate' ) { 
                    	
                    	if ( $inputParams->$key >= 1 ){                    		
                    		$filter['filtergte'][$field['filter_table_field']] = mktime(0,0,0,date('m'),date('d')-(int)$inputParams->$key,date('Y'));                    		                    		                    		
                    	}

                    } elseif ($field['filter_type'] == 'filterin_remote') {  
              
                        $args = array();
                        foreach ($field['filter_in_args'] as $fieldInput) {
                            $args[] = $inputParams->$fieldInput;
                        }                        
                        $filter['filterin'][$key] = call_user_func_array($field['filter_in_generator'],$args);

                        if (count($filter['filterin'][$key]) == 0){
                            $filter['filterin'][$key] = array(-1);
                        }
                    
                        if (isset($field['depend_fields'])){
                            foreach ($field['depend_fields'] as $depend) {                                            
                                 if ($inputFrom->$depend == -1){ 
                                       unset($filter['filterin'][$key]);               
                                }
                            }
                        }
                                               
                    } elseif ($field['filter_type'] == 'filtergte') {  
                                
                        if ( isset($field['datatype']) && $field['datatype'] == 'price' ) {
                            $filter['filtergte'][$field['filter_table_field']] = round($inputParams->$key * 100);
                        } elseif (isset($field['datatype']) && $field['datatype'] == 'date') {
                        	
                        	$dateFormat = DateTime::createFromFormat('Y-m-d H:i:s', $inputParams->$key.' 00:00:00');
                        	
                        	if (is_object($dateFormat)){
                        		$filter['filtergte'][$field['filter_table_field']] = intval($dateFormat->getTimestamp());
                        	}
                        	
                        	
                        } else {                    
                            $filter['filtergte'][$field['filter_table_field']] = $inputParams->$key;
                        }
                        
                    } elseif ($field['filter_type'] == 'filterlte') {           

                    	if (isset($field['datatype']) && $field['datatype'] == 'date') {
                    		 
                    		$dateFormat = DateTime::createFromFormat('Y-m-d H:i:s', $inputParams->$key.' 00:00:00');
                    		
                    		if ( is_object($dateFormat) ) {
                    			$filter['filterlte'][$field['filter_table_field']] = intval($dateFormat->getTimestamp());
                    		}
                    		
                    	} else if (isset($field['range_from']) && isset($filter['filtergte'][$fields[$field['range_from']]['filter_table_field']]) && $filter['filtergte'][$fields[$field['range_from']]['filter_table_field']] == $inputParams->$key) {                            
                            unset($filter['filtergte'][$fields[$field['range_from']]['filter_table_field']]);
                            
                            if ( isset($field['datatype']) && $field['datatype'] == 'price' ) {
                                $filter['filter'][$field['filter_table_field']] = round($inputParams->$key * 100);
                            } else {
                                $filter['filter'][$field['filter_table_field']] = $inputParams->$key;
                            }
                        } else {
                            if ( isset($field['datatype']) && $field['datatype'] == 'price' ) {
                                $filter['filterlte'][$field['filter_table_field']] = round( $inputParams->$key * 100 ); 
                            } else {
                                $filter['filterlte'][$field['filter_table_field']] = $inputParams->$key; 
                            }
                        }
                                                                                   
                    } elseif ($field['filter_type'] == 'filter_join') {                      
                        $filter['filterin'][$field['filter_table_field']] = $inputParams->$key;                         
                        $filter['filter_join'][$field['join_table_name']] = $field['join_attributes'];
                        $filter['filter_having'][] = 'COUNT(*) = '.count($inputParams->$key);
                        $filter['filter_group'][] = $field['group_by_field'];                                                                                              
                    } elseif ($field['filter_type'] == 'filter_map') {     
                 
                        $mapObject =  call_user_func($field['class'].'::fetch',$inputParams->$key);                                          
                        $filter['filter'][$mapObject->field] = (isset($field['datatype']) && $field['datatype'] == 'int') ? (int)$mapObject->status : $mapObject->status;                         
                          
                    } elseif ($field['filter_type'] == 'like') {  
                        $filter['filterlike'][$field['filter_table_field']] = $inputParams->$key;                    
                    } elseif ($field['filter_type'] == 'filterkeyword') {  

                        
                        
                        $keyword =  trim(str_replace('+',' ',urldecode($inputParams->$key)));
                        $inputFrom->$key = $inputParams->$key = $keyword;
                    
                                               
                        if ( $keyword != 'Enter keywords' ) {
                            $filter['filterkeyword'][$field['filter_table_field']] = $keyword;
                        } else {
                            $inputParams->$key = null;
                        }                    

                    } elseif ($field['filter_type'] == 'filter_keyword') {
                        $valueFilter = erLhAbstractModelAttrItem::getAttrIdByGroupAndIdentifier($field['attr_id'],$inputParams->$key);                        
                        $filter['filter_keywords'][] = $field['attr_start'].'_'.$field['attr_id'].'_'.$valueFilter;
                    } elseif ($field['filter_type'] == 'filterin') {  

                        if ( $field['datatype'] == 'mongoid' )
                        {
                            $filterInData = array();
                            foreach ($inputParams->$key as $mongoId)
                            {
                                $filterInData[] = new MongoId($mongoId);
                            }

                            if ( !empty($filterInData) ) {
                                $filter['filterin'][$field['filter_table_field']] = $filterInData;
                            }

                        } else {                    
							$filter['filterin'][$field['filter_table_field']] = $inputParams->$key;  
                        } 
                                      
                    }
                }
            }

            
                        
            
            if (isset($currentOrder['as_append'])){
                foreach ($currentOrder['as_append'] as $key => $appendSelect) { 
                         
                   if (isset($currentOrder['replace_params'])) { 
                       
                       $returnObj = call_user_func($currentOrder['param_call_func'],$inputParams->{$currentOrder['param_call_name_attr']});
                       
                       foreach ($currentOrder['replace_params'] as $attrObj => $targetString)
                       {
                           $appendSelect = str_replace($targetString,$returnObj->$attrObj,$appendSelect);
                       }                                     
                   } 
                   
                   $filter['as_append'] = $appendSelect.' AS ' .$key;
                }
            }   
            
            if (!isset($orderOptions['disabled'])){
	            $keySort = key_exists($inputParams->{$orderOptions['field']},$orderOptions['options']) ? $inputParams->{$orderOptions['field']} : $orderOptions['default'];
	            $currentOrder = $orderOptions['options'][$keySort];                                   
	            $filter['sort'] = isset($orderOptions['serialised']) ? unserialize($currentOrder['sort_column']) : $currentOrder['sort_column'];
	            $inputFrom->sortby = $keySort;                     
            
	            if (key_exists($inputParams->{$orderOptions['field']},$orderOptions['options']) && $orderOptions['default'] != $inputParams->{$orderOptions['field']})
	            {            
	                $inputParams->sortby = $keySort;
	            } else {
	                if (isset($inputParams->sortby)) {
	                    unset($inputParams->sortby);
	                }
	            }
            }
            
        }
        
        return array('errors' => $Errors,'input_form' => $inputFrom, 'input' => $inputParams,'filter' => $filter);                
    }
	
    public static function getURLAppendFromInput($inputParams,$skipSort = false, $locationPrepend = false, $skipArray = array()) {
   	
        $URLappend = '';       
        $sortByAppend = '';
        $locationValue = '';
        
        foreach ($inputParams as $key => $value) {
            if (is_numeric($value) || $value != '') {                
                $value = is_array($value) ? implode('/',$value) : urlencode($value);
                
                if ($key == 'location' && $locationPrepend == true) {
                    $locationValue = '/'.$value;
                } elseif ($key == 'sortby' && !in_array($key,$skipArray)) {
                    $sortByAppend = "/({$key})/".$value;
                } elseif (!in_array($key,$skipArray)) {
                    $URLappend .= "/({$key})/".$value;
                }
            }            
        }              
      
        if ($skipSort == false){
            return $locationValue.$URLappend.$sortByAppend;
        } else {
            return $locationValue.$URLappend;
        }
        
     }

    public static function getUrlSeoParameters($inputParams, $skipArray = array() ) {

        $URLappend = array();                
        foreach ($inputParams as $key => $value) {
            if (is_numeric($value) || $value != '') {                
                $value = is_array($value) ? implode('/',$value) : urlencode($value);                
               if (!in_array($key,$skipArray)) {
                    $URLappend[] = $key."=".$value;
                }
            }            
        }              

        return implode('|',$URLappend);
    }
        
    public static function escapeDescription($content) { 
        
        $content = str_replace("\n",'',$content);
        $content = str_replace("\r",'',$content);
                        
        $content = str_replace(
            array('&pound;','&nbsp;','&amp;', '<strong>','</strong>','<B>','<b>','</b>','</B>','<ul>','</ul>','<li>','</li>','<em>','</em>','<BR>','<br />','<br>','<br/>','</p>','<i>','</i>'),
            array('£',' ','&', '[b]','[/b]','[b]','[b]','[/b]','[/b]','[ul]','[/ul]','[li]','[/li]','[i]','[/i]','[br]','[br]','[br]','[br]','[br]','[i]','[/i]'),
            $content
        );
             
        $content = strip_tags($content);
        $content = trim(str_replace('[br]',"\n",$content));
              
        return $content;
    }

    /**
     * Validates date in 13/09/2012
     * */
    public static function isValidDate( $date ) {
    	
    	$parts = explode('/', $date);
    	
    	if ( count($parts) == 3 ) {    		
    		if ( checkdate($parts[1], $parts[0], $parts[2]) ) {
    			$timestamp = mktime(0,0,0,$parts[1],$parts[0], $parts[2]);
    			return $timestamp;
    		} else {
    			return false;
    		}    		
    	} else {
    		return false;
    	}    	
    }
    
    public static function randomString($lenght = 10)
    {
    	$allchar = "abcdefghijklmnopqrstuvwxyz1234567890";
    	 
    	$str = "" ;
    	 
    	mt_srand (( double) microtime() * 1000000 );
    	 
    	for ( $i = 0; $i<$lenght ; $i++ ) {
    		$str .= substr( $allchar, mt_rand (0,36), 1 );
    	}
    	 
    	return $str ;
    }
    
    public static function isFile($fileName)
    {
    	$supportedExtensions = array (
    			'zip','doc','docx','pdf','xls','xlsx','jpg','jpeg','png'
    	);
    	 
    	if (isset($_FILES[$fileName]) &&  is_uploaded_file($_FILES[$fileName]["tmp_name"]) && $_FILES[$fileName]["error"] == 0 )
    	{
    		$fileNameAray = explode('.',$_FILES[$fileName]['name']);
    		end($fileNameAray);
    		$extension = strtolower(current($fileNameAray));
    		return in_array($extension,$supportedExtensions);
    	}
    	 
    	return false;
    }
    
    public static function moveUploadedFile($fileName,$destination_dir)
    {
    	if (isset($_FILES[$fileName]) &&  is_uploaded_file($_FILES[$fileName]["tmp_name"]) && $_FILES[$fileName]["error"] == 0 )
    	{
    		$fileNameAray = explode('.',$_FILES[$fileName]['name']);
    		end($fileNameAray);
    		$extension = current($fileNameAray);
    		 
    		$fileNamePhysic = sha1(self::randomString().time().$_FILES[$fileName]['name']).'.'.strtolower($extension);
    
    		move_uploaded_file($_FILES[$fileName]["tmp_name"],$destination_dir . $fileNamePhysic);
    		    		 
    		$config = erConfigClassLhConfig::getInstance();
    		$wwwUser = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_user' );
    		$wwwUserGroup = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_group' );
    		chown($destination_dir . $fileNamePhysic,$wwwUser);
    		chgrp($destination_dir . $fileNamePhysic,$wwwUserGroup);
    		chmod($destination_dir . $fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
    		    		
    		return $fileNamePhysic;
    	}
    }

    public function validateInputTwitter(& $email )
    {
    	$definition = array(
    			'EmailCreat' => new ezcInputFormDefinitionElement(
    					ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
    			)
    	);

    	$form = new ezcInputForm( INPUT_POST, $definition );
    
    	$Errors = array();
    
    	if ( !$form->hasValidData( 'EmailCreat' ) ) {
    		$Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Incorrect e-mail address!');
    	} else {
    		if ( erLhcoreClassModelUser::userEmailExists($form->EmailCreat) === true ) {
    			$Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','E-mail taken!');
    		} else {
    			$email = $form->EmailCreat;
    		}
    	}
    	 
    	return $Errors;
    }
    
}