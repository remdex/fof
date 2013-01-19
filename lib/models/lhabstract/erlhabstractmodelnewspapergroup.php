<?

class erLhAbstractModelNewspaperGroup {
        
   public function getState()
   {
       return array(
           'id'         		  => $this->id,
           'name'      			  => $this->name,     
           'contact_email'        => $this->contact_email,
           'location'      	      => $this->location,
           'lat'      	    	  => $this->lat,
           'lon'      	    	  => $this->lon,
           'real_address'      	  => $this->real_address,
           'telephone_numbers'    => $this->telephone_numbers,
           'ad_targeting'  		  => $this->ad_targeting,
           'subdomain'  		  => $this->subdomain,
           'is_iframe'  		  => $this->is_iframe,
           'header_html'  		  => $this->header_html,
           'footer_html'  		  => $this->footer_html,
           'meta_html'  		  => $this->meta_html,
           'css'  				  => $this->css,
       	   'filename_footer' 	  => $this->filename_footer,
       	   'filename_footer_path' => $this->filename_footer_path,
       	   'filename_header' 	  => $this->filename_header,
       	   'filename_header_path' => $this->filename_header_path
       );
   }
    
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public function getNoticeFilter() {
   		return array('filter' => array('newspaper_group_id' => $this->id));
   }
   
   public function __toString()
   {
       return $this->name;
   }

   public function canEdit( $skipChecking = false)
   {	   		   
	   	if ( $skipChecking == true ) return true;
	   
	   	$currentUser = erLhcoreClassUser::instance();
	   	 
	   	if ( in_array($this->id, erLhcoreClassUser::getAdminFilter(array('output_format' => 'array'))) == true ) return true;
	   	 
	   	return false;
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
   	 
   	$photoDir = 'var/storage_newspapers_groups/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id;
   	$photoDirPhoto = 'storage_newspapers_groups/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id.'/';
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
   	 
   	$photoDir = 'var/storage_newspapers_groups/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id;
   	$photoDirPhoto = 'storage_newspapers_groups/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$this->id.'/';
   	erLhcoreClassImageConverter::mkdirRecursive($photoDir);
   	 
   	$fileName = erLhcoreClassSearchHandler::moveUploadedFile('AbstractInput_filename_header', $photoDir . '/' );
   	 
   	$this->filename_header = $fileName;
   	$this->filename_header_path = $photoDirPhoto;
   }

   public function __get($var)
   {
       switch ($var) {
       	
       	case 'left_menu':
       		$this->left_menu = '';
       		return $this->left_menu;
       		break;
       		
       	case 'css_front':
       			return $this->css;
       		break;	 
       		
       	case 'meta_html_front':
       			return $this->meta_html;
       		break;	 
       		
       	case 'header_html_front':
       			return $this->header_html;
       		break;	 
       		
       	case 'footer_html_front':
       			return $this->footer_html;
       		break;	 
       		
       	case 'users_assigned':
       		$this->users_assigned = erLhcoreClassModelNewspaperGroupUser::getList(array('filter' => array('newspaper_group_id' => $this->id)));
       		return $this->users_assigned;
       		break;
       		 
       	case 'url_header_content':
       		$this->url_header_content = false;
       		 
       		if ( $this->url_header != '' ) {
       			$this->url_header_content = erLhAbstractModelNewspaper::downloadPage($this->url_header);
       		}
       		 
       		return $this->url_header_content;
       		break;
       		 
       	case 'url_footer_content':
       		$this->url_footer_content = false;
       		 
       		if ( $this->url_footer != '' ) {
       			$this->url_footer_content = erLhAbstractModelNewspaper::downloadPage($this->url_footer);
       		}
       		 
       		return $this->url_footer_content;
       		break;
       		
       	case 'has_img_header':
       	case 'has_img_header_front':
       			return $this->filename_header != '';
       			break;
       			 
       	case 'has_img_footer':
       	case 'has_img_footer_front':
       			return $this->filename_footer != '';
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
       	case 'file_path_header_server_front':
       			$this->file_path_header_server = 'var/' . $this->filename_header_path . $this->filename_header;
       			return $this->file_path_header_server;
       			break;
       			 
       	case 'file_path_footer_server':
       	case 'file_path_footer_server_front':
       			$this->file_path_footer_server = 'var/' . $this->filename_footer_path . $this->filename_footer;
       			return $this->file_path_footer_server;
       			break;
       			
       	default:
       		break;
       }
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
   			'contact_email' => array (
   					'type' => 'text',
   					'trans' => 'Contact e-mail',
   					'required' => false,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       	)),
   			'ad_targeting' => array (
   					'type' => 'text',
   					'trans' => 'DFP targeting',
   					'required' => true,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
   					)),
   			'real_address' => array(
   					'type' => 'textarea',
   					'height' => '100px',
   					'trans' => 'Real address',
   					'required' => false,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
   					)),
   			'telephone_numbers' => array(
   					'type' => 'textarea',
   					'height' => '50px',
   					'trans' => 'Telephone numbers',
   					'required' => false,
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
   			'location' => array (
   					'type' => 'location',
   					'trans' => 'Location',
   					'required' => false,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
   					)),
   			'filename_header' => array (
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
   			 'filename_footer' => array (
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
   	);
   }
   
      
   public function getModuleTranslations()
   {
       return array('name' => 'Newspapers groups');
   }
   
   public function saveThis()
   {
       erLhcoreClassAbstract::getSession()->saveOrUpdate( $this );
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_newspaper_group" );   
         
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
       if (isset($GLOBALS['CacheGlobalModelNewspaperGroup_'.$id])) return $GLOBALS['CacheGlobalModelNewspaperGroup_'.$id];         
       $GLOBALS['CacheGlobalModelNewspaperGroup_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelNewspaperGroup', (int)$id );
       
       return $GLOBALS['CacheGlobalModelNewspaperGroup_'.$id];
   }
   
   public function removeThis()
   {
   	   foreach ( erLhcoreClassModelPNNotice::getList(array('limit' => 5000,'filter' => array('newspaper_group_id' => $this->id))) as $notice) {
   	  		$notice->removeThis();
   	   }

   	   foreach ( erLhAbstractModelNewspaper::getList(array('limit' => 5000,'filter' => array('newspaper_group_id' => $this->id))) as $notice) {
   	  		$notice->removeThis();
   	   }

       erLhcoreClassAbstract::getSession()->delete($this);
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 500, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->createFindQuery( 'erLhAbstractModelNewspaperGroup' );  
       
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
      $q->orderBy('id DESC');
              
       $objects = $session->find( $q, 'erLhAbstractModelNewspaperGroup' );
         
      return $objects; 
   }
   
   public function getNewspaperGroupField(){
   		return 'id';
   }
   
   public $id = null;
   public $name = '';
   public $contact_email = '';
   public $location = '';
   public $lat = 0;
   public $lon = 0;
   public $real_address = '';
   public $telephone_numbers = '';
   public $ad_targeting = '';
   public $subdomain = '';
   public $is_iframe = 0;
         
   public $header_html = '';
   public $footer_html = '';
   public $meta_html = '';
   public $css = '';
   
   public $filename_footer = '';
   public $filename_footer_path = '';
   public $filename_header = '';
   public $filename_header_path = '';
   
   public $hide_add = true;
   public $hide_delete = true;
   
      
}

?>