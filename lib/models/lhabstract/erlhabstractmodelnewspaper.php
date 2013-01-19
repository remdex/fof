<?

class erLhAbstractModelNewspaper {
        
   public function getState()
   {
       return array(
           'id'         		  => $this->id,
           'name'      			  => $this->name,
           'real_address'      	  => $this->real_address,
           'contact_email'        => $this->contact_email,
           'telephone_numbers'    => $this->telephone_numbers,
           'lat'      			  => $this->lat,
           'lon'      			  => $this->lon,
           'location'      		  => $this->location,
           'newspaper_group_id'   => $this->newspaper_group_id,
           'url_header' 		  => $this->url_header,
           'url_footer' 		  => $this->url_footer,
           'css' 				  => $this->css,
           'filename_footer' 	  => $this->filename_footer,
           'filename_footer_path' => $this->filename_footer_path,
           'filename_header' 	  => $this->filename_header,
           'filename_header_path' => $this->filename_header_path,
           'country' 			  => $this->country,
           'sitecode' 			  => $this->sitecode,
       	   'subdomain'			  => $this->subdomain,
       	   'include_type'		  => $this->include_type,
       	   'header_html'		  => $this->header_html,
       	   'footer_html'		  => $this->footer_html,
       	   'meta_html'		  	  => $this->meta_html,
       	   'domain'		  	      => $this->domain,
       	   'is_iframe'		  	  => $this->is_iframe,
       		
       );
   }
   
   public function canEdit( $skipChecking = false)
   {
	   	if ( $skipChecking == true ) return true;
	   
	   	$currentUser = erLhcoreClassUser::instance();
	   
	   	if ( in_array($this->newspaper_group_id, erLhcoreClassUser::getAdminFilter(array('output_format' => 'array'))) == true ) return true;
	   
	   	return false;
   }
   
   public function getNewspaperGroupField() {
   		return 'newspaper_group_id';
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public function getNoticeFilter() {
   		if ($this->include_type == self::INCLUDE_TYPE_ALL) {
   			return array();
   		} elseif ( $this->include_type == self::INCLUDE_TYPE_NEWSPAPER ) {
   			return array('filter' => array('newspaper_id' =>$this->id));
   		} elseif ($this->include_type == self::INCLUDE_TYPE_NEWSPAPER_GROUP) {
   			return array('filter' => array('newspaper_group_id' => $this->newspaper_group_id));
   		}
   }
   
   public function __toString()
   {
       return $this->name;
   }
   
   public static function downloadPage($url) {
	   	$ch = curl_init();
	   	$timeout = 1;
	   	curl_setopt($ch, CURLOPT_URL, $url);
	   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	   	$data = curl_exec($ch);
	   	curl_close($ch);
	   	return $data;
   }
      
   public function __get($var)
   {
       switch ($var) {
       	
       	case 'left_menu':
       	       $this->left_menu = '';
       		   return $this->left_menu;
       		break;
       		
       	case 'css_front':
       			$this->css_front = $this->css;
       			if (empty($this->css_front) && $this->newspaper_group !== false){
       				$this->css_front = $this->newspaper_group->css;
       			}
       			
       			return $this->css_front;
       		break;	       		
       		
       	case 'meta_html_front':
       			$this->meta_html_front = $this->meta_html;
       			
       			if ( empty($this->meta_html_front) && $this->newspaper_group !== false ) {
       				$this->meta_html_front = $this->newspaper_group->meta_html;
       			}
       			
       			return $this->meta_html_front;
       		break;
       		
       	case 'header_html_front':
       			$this->header_html_front = $this->header_html;
       			
       			if ( empty($this->header_html_front) && $this->newspaper_group !== false ) {
       				$this->header_html_front = $this->newspaper_group->header_html;
       			}
       			
       			return $this->header_html_front;
       		break;
       		
       	case 'footer_html_front':
       			$this->footer_html_front = $this->footer_html;
       			
       			if ( empty($this->footer_html_front) && $this->newspaper_group !== false ) {
       				$this->footer_html_front = $this->newspaper_group->footer_html;
       			}
       			
       			return $this->footer_html_front;
       		break;
       			       		
       	case 'ad_zone_id':
       			$this->ad_zone_id = $this->getNewspaperGroupAssigned();
       			return $this->ad_zone_id;
       			break;
       			
       	case 'url_header_content':
       			$this->url_header_content = false;
       			
       			if ( $this->url_header != '' ) {
       				$this->url_header_content = self::downloadPage($this->url_header);       				
       			} elseif ($this->newspaper_group !== false) {
       				$this->url_header_content = $this->newspaper_group->url_header_content;
       			}
       			
       			return $this->url_header_content;
       		break;

       	case 'url_footer_content':
       			$this->url_footer_content = false;
       			
       			if ( $this->url_footer != '' ) {
       				$this->url_footer_content = self::downloadPage($this->url_footer);       				
       			} elseif ( $this->newspaper_group !== false ) {
       				$this->url_footer_content = $this->newspaper_group->url_footer_content;
       			}
       			
       			return $this->url_footer_content;
       		break;
       		
       	case 'newspaper_group':
       		       			
       	       $this->newspaper_group = false;
       	       
       	       if ( $this->newspaper_group_id > 0 ) {
       	       		try {
       	       			$this->newspaper_group = erLhAbstractModelNewspaperGroup::fetch($this->newspaper_group_id);
       	       		} catch (Exception $e) {
       	       			
       	       		}
       	       }
       	       
       		   return $this->newspaper_group;
       		break;
       		
       	case 'has_img_header':
       			return $this->filename_header != '';
       		break;
       		
       	case 'has_img_header_front':
       			$this->has_img_header_front = false;

       			if ($this->filename_header != ''){
       				$this->has_img_header_front = true; 
       			} elseif ( $this->newspaper_group !== false ) {
       				$this->has_img_header_front = $this->newspaper_group->has_img_header;
       			}

       			return $this->has_img_header_front;
       		break;
       		
       	case 'has_img_footer':
       			return $this->filename_footer != '';
       		break;

       	case 'has_img_footer_front':
       			$this->has_img_footer_front = false;
       			 
       			if ($this->filename_header != ''){
       				$this->has_img_footer_front = true;
       			} elseif ( $this->newspaper_group !== false ) {
       				$this->has_img_footer_front = $this->newspaper_group->has_img_footer;
       			}
       			 
       			return $this->has_img_footer_front;
       			break;

       	case 'url_img_header':       			 

       			$this->url_img_header = false;

       			if ( $this->filename_header != '' ) {
       					$this->url_img_header = '<img width="100" src="/var/' . $this->filename_header_path . $this->filename_header.'" />';       				       			       				
       					return $this->url_img_header;       				
       			}

       			return $this->url_img_header;
       		break;
            	
       	case 'url_img_footer':       			 
       			
       			$this->url_img_footer = false;
       			    		
       			if ( $this->filename_footer != '' ) {
       					$this->url_img_footer = '<img width="100" src="/var/' . $this->filename_footer_path . $this->filename_footer.'" />';       				       			       				
       					return $this->url_img_footer;       				
       			}
       			
       			return $this->url_img_footer;
       		break;
       		
       	case 'file_path_header_server':
       			$this->file_path_header_server = 'var/' . $this->filename_header_path . $this->filename_header;
       			return $this->file_path_header_server;
       		break;
       		
       	case 'file_path_header_server_front':
       		
       			if ( $this->filename_header != '') {
       				$this->file_path_header_server_front = 'var/' . $this->filename_header_path . $this->filename_header;
       			} elseif ( $this->newspaper_group !== false ) {
       				$this->file_path_header_server_front = $this->newspaper_group->file_path_header_server;
       			}
       			
       			return $this->file_path_header_server_front;
       		break;
       		
       	case 'file_path_footer_server':
       			$this->file_path_footer_server = 'var/' . $this->filename_footer_path . $this->filename_footer;
       			return $this->file_path_footer_server;
       		break;
       		
       	case 'file_path_footer_server_front':
       			 
       			if ( $this->filename_footer != '') {
       				$this->file_path_footer_server_front = 'var/' . $this->filename_footer_path . $this->filename_footer;
       			} elseif ( $this->newspaper_group !== false ) {
       				$this->file_path_footer_server_front = $this->newspaper_group->file_path_footer_server;
       			}
       		
       			return $this->file_path_footer_server_front;
       			break;
       			
       	default:
       		break;
       }
   }
   
   public function deleteImageFooter()
   {
   		if ( !empty($this->filename_footer) && file_exists($this->file_path_footer_server) ) {
	   		unlink($this->file_path_footer_server);	   			
	   		erLhcoreClassImageConverter::removeRecursiveIfEmpty('var/',$this->filename_footer_path);
	   		$this->filename_footer = '';
	   		$this->filename_footer_path = '';
	   	}
   }
    
   public function movePhotoFooter()
   {   		   
   		$this->deleteImageFooter();
   	
   		$photoDir = 'var/storage_newspapers/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id;
   		$photoDirPhoto = 'storage_newspapers/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id.'/';
   		erLhcoreClassImageConverter::mkdirRecursive($photoDir);
   		
   		$fileName = erLhcoreClassSearchHandler::moveUploadedFile('AbstractInput_filename_footer',  $photoDir . '/' );
   
   		$this->filename_footer = $fileName;
   		$this->filename_footer_path = $photoDirPhoto;
   }
   
   public function deleteImageHeader()
   {
	   	if ( !empty($this->filename_header) && file_exists($this->file_path_header_server) ) {
	   		unlink($this->file_path_header_server);	   			
	   		erLhcoreClassImageConverter::removeRecursiveIfEmpty('var/',$this->filename_header_path);
	   		$this->filename_header = '';
	   		$this->filename_header_path = '';
	   	}
   }
    
   public function movePhotoHeader()
   {   		   	
   		$this->deleteImageHeader();
   	
   		$photoDir = 'var/storage_newspapers/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id;
   		$photoDirPhoto = 'storage_newspapers/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id.'/';
   		erLhcoreClassImageConverter::mkdirRecursive($photoDir);
   		
   		$fileName = erLhcoreClassSearchHandler::moveUploadedFile('AbstractInput_filename_header', $photoDir . '/' );
   
   		$this->filename_header = $fileName;
   		$this->filename_header_path = $photoDirPhoto;
   }
     
   public static function getNoticeOptions()
   {
   		$options = array();
   		
   		$typeAll = new stdClass();
   		$typeAll->id = self::INCLUDE_TYPE_ALL;
   		$typeAll->name = 'All ads';
   	
   		$options[] = $typeAll;
   		
   		$typeAll = new stdClass();
   		$typeAll->id = self::INCLUDE_TYPE_NEWSPAPER_GROUP;
   		$typeAll->name = 'Newspaper group';
   	
   		$options[] = $typeAll;
   		
   		$typeAll = new stdClass();
   		$typeAll->id = self::INCLUDE_TYPE_NEWSPAPER;
   		$typeAll->name = 'Newspaper';
   	
   		$options[] = $typeAll;
   		
   		return $options;
   }
   
   public function getFields()
   {
       return array(
       'name' => array(
       'type' => 'text',
       'trans' => 'Name',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'country' => array(
       'type' => 'text',
       'trans' => 'Country',
       'required' => false,  
       'hidden' => true,     
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'sitecode' => array(
       'type' => 'text',
       	'hidden' => true,
       'trans' => 'Site code',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'subdomain' => array(
       'type' => 'text',
       	'hidden' => true,
       'trans' => 'Subdomain'/* of '.$_SERVER['HTTP_HOST']*/,
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'is_iframe' => array(
       'type' => 'checkbox',
       'hidden' => true,
       'trans' => 'Is iframe',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        )),
       'domain' => array(
       'type' => 'text',
       'hidden' => true,
       'trans' => 'Newspaper domain',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'contact_email' => array(
       'type' => 'text',
       'trans' => 'Contact e-mail',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'real_address' => array(
       'type' => 'textarea',
       'height' => '100px',
       'trans' => 'Real address',
       'required' => true,   
       	'hidden' => true,    
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'telephone_numbers' => array(
       'type' => 'textarea',
       'height' => '50px',
       'trans' => 'Telephone numbers',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'location' => array(
       'type' => 'location',
       'trans' => 'Location',
       'required' => true,
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),       	
       	'newspaper_group_id' => array (
       	'type' => 'combobox',
       	'trans' => 'Newspaper group',
       	'frontend' => 'newspaper_group',
       	'source' => 'erLhAbstractModelNewspaperGroup::getList',
       	'params_call' => array_merge(array('limit' => 5000, 'offset' => 0),erLhcoreClassUser::getAdminFilter(array('newspaper_group_field' => 'id'))),
       	'required' => true,
       	'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)       	
       	)), 
       	'include_type' => array (
       	'type' => 'combobox',
       	'trans' => 'Include these notices only',      
       	'source' => 'erLhAbstractModelNewspaper::getNoticeOptions',       	
       	'required' => true,
       	'hidden' => true,
       	'name_attr' => 'name',
       	'params_call' => array(),
       	'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 0)       	
       	)),
       	'url_header' => array(
       				'type' => 'text',
       				'trans' => 'URL Header',
       				'required' => false,
       				'hidden' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       				)),
       	'url_footer' => array(
       				'type' => 'text',
       				'trans' => 'URL footer',
       				'required' => false,
       				'hidden' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       				)),
       	'css' => array(
       				'type' => 'textarea',
       				'height' => '100px',
       				'trans' => 'Additional CSS',
       				'required' => false,
       				'hidden' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       				)),       	
       	'header_html' => array(
       				'type' => 'textarea',
       				'height' => '100px',
       				'trans' => 'Header HTML',
       				'required' => false,
       				'hidden' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       				)),       	
       	'footer_html' => array(
       				'type' => 'textarea',
       				'height' => '100px',
       				'trans' => 'Footer HTML',
       				'required' => false,
       				'hidden' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       				)),       	
       	'meta_html' => array(
       				'type' => 'textarea',
       				'height' => '100px',
       				'trans' => 'Meta HTML',
       				'required' => false,
       				'hidden' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       				)),       	
       	'filename_header' => array(
       				'type' => 'file',
       				'trans' => 'Image header',
       				'required' => false,
       				'hidden' => true,
       				'frontend' => 'url_img_header',
       				'backend_call' => 'movePhotoHeader',
       				'delete_call' => 'deleteImageHeader',
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'callback','erLhcoreClassSearchHandler::isFile()'
       				)),
       	'filename_footer' => array(
       				'type' => 'file',
       				'trans' => 'Image footer',
       				'required' => false,
       				'hidden' => true,
       				'frontend' => 'url_img_footer',
       				'backend_call' => 'movePhotoFooter',
       				'delete_call' => 'deleteImageFooter',
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'callback','erLhcoreClassSearchHandler::isFile()'
       				)),
       	/*'ad_zone' => array (
       				'type' => 'combobox_multi',
       				'frontend' => 'body',
       				'backend' => 'ad_zone_id',
       				'source' => 'erLhAbstractModelAdZone::getList',
       				'params_call' => array('limit' => 5000, 'offset' => 0),
       				'trans' => 'Allowed ad zones',
       				'hidden' => true,
       				'backend_call' => 'assignNewspaperToZone',
       				'required' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'int',
       						null,
       						FILTER_REQUIRE_ARRAY
       				)),*/
       );
   }
   
   public function getNewspaperGroupAssigned($fetchObjects = false)
   {
   	if ($this->id > 0) {
   		$session = erLhcoreClassAbstract::getSession();
   		$q = $session->database->createSelectQuery();
   		$q->select( "ad_id" )->from( "lh_abstract_ad_zone_newspaper" );
   		$q->where(
   				$q->expr->eq( 'newspaper_id', $this->id )
   		);
   		$stmt = $q->prepare();
   		$stmt->execute();
   		$result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
   		 
   		if ($fetchObjects == true && $result !== false && count($result) > 0){
   			//return erLhAbstractModelBodyStyle::getList(array('filterin' => array('id' => $result)));
   		}
   		 
   		return $result;
   	}
   
   	return array();
   }
   
   public function assignNewspaperToZone($shipperArray){
	   	if ($this->id > 0) {
	   	
	   		$session = erLhcoreClassAbstract::getSession();
	   		$q = $session->database->createDeleteQuery();
	   		$q->deleteFrom( 'lh_abstract_ad_zone_newspaper' )
	   		->where( $q->expr->eq( 'newspaper_id', $q->bindValue( $this->id ) ) );
	   		$stmt = $q->prepare();
	   		$stmt->execute();
	   	
	   		foreach ($shipperArray as $shipperID){
	   			$q = $session->database->createInsertQuery();
	   			$q->insertInto( 'lh_abstract_ad_zone_newspaper' )
	   			->set( 'newspaper_id', $q->bindValue($this->id) )
	   			->set( 'ad_id', $q->bindValue( $shipperID ) );
	   			$stmt = $q->prepare();
	   			$stmt->execute();
	   		}
	   	
	   	} else {
	   		$this->ad_zone_id = $shipperArray;
	   	}
   }
      
   public function getModuleTranslations()
   {
       return array('name' => 'Newspaper');
   }
   
   public function saveThis()
   {
       erLhcoreClassAbstract::getSession()->saveOrUpdate( $this );
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_newspaper" );   
         
       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           } 
      }  
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
      }     
       
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
       {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
      {
           foreach ($params['filtergte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gte( $field, $q->bindValue($fieldValue) );
           }
      } 

      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
      {
           foreach ($params['filterlte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
           }
      }
       
      if (isset($params['filterlike']) && count($params['filterlike']) > 0)
      {
           foreach ($params['filterlike'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->like( $field, $q->bindValue('%'.$fieldValue.'%') );
           }
      }

      if (isset($params['leftjoin']) && count($params['leftjoin']) > 0)
      {
           foreach ($params['leftjoin'] as $table => $joinOn)
           {
               $q->leftJoin( $table, $q->expr->eq( $joinOn[0], $joinOn[1] ) );
           } 
      }
           
             
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      } 
             
      $stmt = $q->prepare();       
      $stmt->execute();  
      $result = $stmt->fetchColumn(); 
            
      return $result; 
   }
   
   public static function fetch($id)
   {   
       if (isset($GLOBALS['CacheGlobalModelNewspaper_'.$id])) return $GLOBALS['CacheGlobalModelNewspaper_'.$id];         
       $GLOBALS['CacheGlobalModelNewspaper_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelNewspaper', (int)$id );
       
       return $GLOBALS['CacheGlobalModelNewspaper_'.$id];
   }
   
   public function removeThis()
   {
   		$this->deleteImageFooter();
   		$this->deleteImageHeader();
   		
        erLhcoreClassAbstract::getSession()->delete($this);
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 5000, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->createFindQuery( 'erLhAbstractModelNewspaper' );  
       
       $conditions = array();
        if (isset($params['filter']) && count($params['filter']) > 0)
      {                     
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           }
      } 
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
      {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
      }
      
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
      {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
      {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue) );
           } 
      }  
      
      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
      {
           foreach ($params['filtergte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gte( $field, $q->bindValue($fieldValue) );
           }
      } 

      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
      {
           foreach ($params['filterlte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
           }
      }

      if (isset($params['filterlike']) && count($params['filterlike']) > 0)
      {
           foreach ($params['filterlike'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->like( $field, $q->bindValue('%'.$fieldValue.'%') );
           }
      } 
                  
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      } 
       
      $q->limit($params['limit'],$params['offset']);
      $q->orderBy('name ASC');
              
       $objects = $session->find( $q, 'erLhAbstractModelNewspaper' );
         
      return $objects; 
   }
   
   public $id = null;
   public $name = '';
   public $real_address = '';
   public $contact_email = '';
   public $telephone_numbers = '';
   public $lat = 0;
   public $lon = 0;
   public $location = '';
   public $newspaper_group_id = 0;   
   public $url_header = '';   
   public $url_footer = '';   
   public $css = '';  
   public $filename_footer = '';  
   public $filename_footer_path = '';  
   public $filename_header = '';  
   public $filename_header_path = ''; 
   public $country = ''; 
   public $sitecode = ''; 
   public $subdomain = '';    
   public $header_html = ''; 
   public $footer_html = ''; 
   public $meta_html = ''; 
   public $domain = ''; 
   public $is_iframe = 0; 
   
   public $hide_add = false;
   public $hide_delete = true;
   public $can_create_newspaper = true;
   
   public $has_filter = true;
   
   const FILTER_NAME = 'filternewspaper';
   
   const INCLUDE_TYPE_ALL = 0;
   const INCLUDE_TYPE_NEWSPAPER_GROUP = 1;
   const INCLUDE_TYPE_NEWSPAPER = 2;
   
   public $include_type = self::INCLUDE_TYPE_ALL;
   
}

?>