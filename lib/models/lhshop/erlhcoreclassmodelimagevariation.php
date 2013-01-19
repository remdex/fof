<?

class erLhcoreClassModelShopImageVariation {
        
   public function getState()
   {
       return array(
               'id'     => $this->id,
               'width'  => $this->width,             
               'height'	=> $this->height,             
               'name'       => $this->name,             
               'credits'	=> $this->credits,             
               'position'	=> $this->position,            
               'type'		=> $this->type            
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($id)
   {
       $imageVariation = erLhcoreClassShop::getSession('slave')->load( 'erLhcoreClassModelShopImageVariation', (int)$id );     
       return $imageVariation;
   } 
   
   public function removeThis()
   {
       erLhcoreClassShop::getSession()->delete($this);  
   }

   public function getSize($image)
   {
   		$width = 0;
   		$height = 0;
   		if ($this->type == erLhcoreClassModelShopImageVariation::ORIGINAL_VARIATION){
   			$width =  $image->pwidth;
   			$height = $image->pheight;
   		}elseif ($image->pwidth > $image->pheight) {
			$ration = $this->width / $image->pwidth;			
			$width = $this->width; 
			$height = round($image->pheight * $ration);			
		} else {
			$ration = $this->height / $image->pheight;			
			$height = $this->height; 
			$width = round($image->pwidth * $ration);
		}
		
   		return $width.'x'.$height;
   }
   
   public function getFormat($image)
   {
   		$array = explode('.',$image->filename);
   		end($array);
   		return current($array);
   }
     
   public static function getImageVariationCount($params = array())
   {
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_shop_image_variation" );   
         
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
      
   public static function getImageVariation($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 30, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelShopImageVariation' );  
              
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
      
      $q->limit($params['limit'],$params['offset']);
          
      $q->orderBy( isset($paramsSearch['sort']) ? $paramsSearch['sort'] : 'position ASC' ); 
              
      $objects = $session->find( $q );         
      return $objects; 
   }
     
   public $id = null;
   public $width = 0;
   public $height = 0;
   public $name = '';
   public $credits = 0;
   public $position = 0;
   public $type = 0;
   
   const CUSTOM_VARIATION = 0;
   const ORIGINAL_VARIATION = 1;
   
}

?>