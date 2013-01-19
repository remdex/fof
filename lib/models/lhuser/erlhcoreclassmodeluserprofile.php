<?

class erLhcoreClassModelUserProfile {
        
   public function getState()
   {
       return array (
               'id'        => $this->id,
               'user_id'   => $this->user_id,
               'name'      => $this->name,
               'surname'   => $this->surname,
               'intro'     => $this->intro,
               'variations'     => $this->variations,
               'photo'     => $this->photo,
               'filepath'     => $this->filepath,
               'website'     => $this->website,
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public function __toString()
   {
   		return $this->username.' ('.$this->email.')';
   }
   
   public static function fetch($user_id)
   {
   	 $user = erLhcoreClassUser::getSession('slave')->load( 'erLhcoreClassModelUserProfile', (int)$user_id );
   	 return $user;
   }
   
   public function removeFile()
   {
       if ($this->photo != '') {
           if ( file_exists($this->filepath . '/' . $this->photo) ) {
               unlink($this->filepath . '/' . $this->photo);
           }

           if ( file_exists($this->filepath . '/' . 'photow_60_' . $this->photo) ) {
               unlink($this->filepath . '/' . 'photow_60_' . $this->photo);
           }
           
           if ( file_exists($this->filepath . '/' . 'photo_normal_' . $this->photo) ) {
               unlink($this->filepath . '/' . 'photo_normal_' . $this->photo);
           }
           
           $this->variations = '';
           erLhcoreClassImageConverter::removeRecursiveIfEmpty('albums/userpics/profile/',str_replace('albums/userpics/profile/','',$this->filepath));
           $this->filepath = '';
           $this->photo = '';           
           $this->updateThis();           
       }
   }
   
   public function __get($param)
   {
       switch ($param) {
  
           case 'has_photo':
       		   return $this->photo != '';
       		break;
       		
           case 'variations_photo':
       	        $this->variations_photo = array();
       	        
       	        if ($this->variations != ''){
       	            $this->variations_photo = unserialize($this->variations);
       	        }
       	        
       	        return $this->variations_photo;
       	    break;
       	    
       		case 'photow_60':
       		    $instance = erLhcoreClassSystem::instance();
       		    
       	        $variations = $this->variations_photo;
       	        $this->photow_60 = false;
       	        
       	        if (isset($variations['photow_60'])){
       	            $this->photow_60 = $instance->wwwDir() . '/' . $this->filepath . '/' . 'photow_60_'.$this->photo;
       	        } else {
       	                try {
       	                    erLhcoreClassImageConverter::getInstance()->converter->transform( 'photow_60', $this->filepath . '/' . $this->photo, $this->filepath . '/' . 'photow_60_'.$this->photo );
       	                    chmod($this->filepath . '/' . 'photow_60_' . $this->photo, erConfigClassLhConfig::getInstance()->getSetting( 'site', 'StorageFilePermissions' ));
       	                    $this->addVariantion('photow_60');
       	                    $this->photow_60 = $instance->wwwDir() . '/' . $this->filepath . '/' . 'photow_60_'.$this->photo;
       	                } catch (Exception $e) {
       	                    $this->photow_60 = false;
       	                }
       	       }
       		   return $this->photow_60;
       		break;       	
       
       		case 'photo_normal':
       		    $instance = erLhcoreClassSystem::instance();
       		    
       	        $variations = $this->variations_photo;
       	        $this->photo_normal = false;
       	        
       	        if (isset($variations['photo_normal'])){
       	            $this->photo_normal = $instance->wwwDir() . '/' . $this->filepath . '/' . 'photo_normal_'.$this->photo;
       	        } else {
       	                try {
       	                    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $this->filepath . '/' . $this->photo, $this->filepath . '/' . 'photo_normal_'.$this->photo );
       	                    chmod($this->filepath . '/' . 'photo_normal_' . $this->photo, erConfigClassLhConfig::getInstance()->getSetting( 'site', 'StorageFilePermissions' ));
       	                    $this->addVariantion('photo_normal');
       	                    $this->photo_normal = $instance->wwwDir() . '/' . $this->filepath . '/' . 'photo_normal_'.$this->photo;
       	                } catch (Exception $e) {
       	                    $this->photo_normal = false;
       	                }
       	       }
       		   return $this->photo_normal;
       		break;       	
       
       	default:
       		break;
       }
   }
   
   public function addVariantion( $variationItem )
   {
       $variation = $this->variations_photo;
       $variation[$variationItem] = true;
       $this->variations = serialize($variation);
       $this->updateThis();       
   }
   
   /**
    * @param $receiver_code sąskaitos gavėjo kodas. Sveikas skaičius.
    * @param $balance kokios balanso sumos mes norim, tai suma centais. Sveikas skaičius
    * 
    * @return Grąžinamas erLhcoreClassModelSavitarnaPayment persist objektas, kurio ID galime naudoti kaip užsakymo ID. Patvirtinti mokėjima iš banko. 
    * */
   public static function instanceProfile($user_id)
   {
       
       $items = self::getList(array('filter' => array('user_id' => $user_id)));       
       if (empty($items)) {
           $profile = new erLhcoreClassModelUserProfile();
           $profile->user_id = $user_id;
           $profile->saveThis();
       } else {
           $profile = current($items);
       }
       
       return $profile;
   }
   
   public function saveThis()
   {
       erLhcoreClassUser::getSession()->save( $this );
   }
   
   public function updateThis()
   {
       erLhcoreClassUser::getSession()->update( $this );
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassUser::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_users_profile" );   
         
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           $conditions = array();
           
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           }
           
           $q->where( 
                 $conditions   
           );
      }  
             
      $stmt = $q->prepare();       
      $stmt->execute();  
      $result = $stmt->fetchColumn(); 
            
      return $result; 
   }
   
 
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassUser::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelUserProfile' );  
       
       $conditions = array(); 
       if (!isset($paramsSearch['smart_select'])) {
             
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
                           $conditions[] = $q->expr->gt( $field,$q->bindValue( $fieldValue ));
                       } 
                  }      
                  
                  if (count($conditions) > 0)
                  {
                      $q->where( 
                                 $conditions   
                      );
                  } 
                  
                  $q->limit($params['limit'],$params['offset']);
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_users_profile' );
           
           if (isset($params['filter']) && count($params['filter']) > 0)
          {                     
               foreach ($params['filter'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->eq( $field, $q->bindValue($fieldValue ));
               }
          } 
          
          if (isset($params['filterin']) && count($params['filterin']) > 0)
          {
               foreach ($params['filterin'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->in( $field, $fieldValue );
               } 
          }
          
          if (isset($params['filterlt']) && count($params['filterlt']) > 0)
          {
               foreach ($params['filterlt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->lt( $field, $q->bindValue($fieldValue ));
               } 
          }
          
          if (isset($params['filtergt']) && count($params['filtergt']) > 0)
          {
               foreach ($params['filtergt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->gt( $field,$q->bindValue( $fieldValue) );
               } 
          }      
          
          if (count($conditions) > 0)
          {
              $q2->where( 
                         $conditions   
              );
          }
           
          $q2->limit($params['limit'],$params['offset']);
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_users_profile.id', 'items.id' );          
       }
              
       $objects = $session->find( $q );
         
      return $objects; 
   }
      
   public $id = null;
   public $user_id = null;
   public $name = '';
   public $surname = '';
   public $intro = '';
   public $photo = '';
   public $variations = '';
   public $filepath = '';
   public $website = '';
   
}

?>