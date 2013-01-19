<?


// constants for setting the $units data member
define('_UNIT_MILES', 'm');
define('_UNIT_KILOMETERS', 'k');

// constants for passing $sort to get_zips_in_range()
define('_ZIPS_SORT_BY_DISTANCE_ASC', 1);
define('_ZIPS_SORT_BY_DISTANCE_DESC', 2);
define('_ZIPS_SORT_BY_ZIP_ASC', 3);
define('_ZIPS_SORT_BY_ZIP_DESC', 4);

// constant for miles to kilometers conversion
define('_M2KM_FACTOR', 1.609344);

class erLhAbstractModelPostcode {

   public function getState()
   {
       return array(
           'id'            => $this->id,
           'postcode'      => $this->postcode,
           'paper'         => $this->paper,
           'ad_searchtext' => $this->ad_searchtext,         
           'lat'           => $this->lat,
           'lon'           => $this->lon
       );
   }

   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public function __get($var)
   {
       switch ($var) {
       	case 'left_menu':
       	       $this->left_menu = '';
       		   return $this->left_menu;
       		break;
       		
       	case 'location':
       	    return $this->postcode;
       	break;
       	
       	default:
       		break;
       }
   }
   
   public function getFields()
   {
   		return array(
   			'postcode' => array(
   					'type' => 'text',
   					'trans' => 'Post code',
   					'required' => true,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
   					)),
   			/*'ad_searchtext' => array(
   					'type' => 'textarea',
   					'trans' => 'Additional search text',
   					'height' => '100px',
   					'required' => false,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
   					)),   */
   			'lat' => array(
   					'type' => 'text',
   					'trans' => 'Latitude',
   					'required' => true,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
   					)),
   
   			'lon' => array(
   					'type' => 'text',
   					'trans' => 'Longitude',
   					'required' => true,
   					'validation_definition' => new ezcInputFormDefinitionElement(
   							ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
   			))   			   			 
   		);
   }
    
   public function getModuleTranslations()
   {
   		return array('name' => 'Post codes');
   }
   
   
   public function __toString()
   {
       return $this->postcode;
   }   
    
   public static function getCount($params = array())
   {       
       if (!isset($params['disable_sql_cache']))
       {
          $sql = CSCacheAPC::multi_implode(',',$params);
          
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('global_cache_'.$cache->getCacheVersion('site_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {
              return $result;
          }       
       }       
       
       $session = erLhcoreClassPN::getSession('');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_postcode" );   
         
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
      
      if (!isset($params['disable_sql_cache'])) {
              $cache->store($cacheKey,$result);           
      }
            
      return $result; 
   }
   
   public static function fetch($id)
   {   
       if (isset($GLOBALS['erLhcoreClassModelAdPostcode_'.$id])) return $GLOBALS['erLhcoreClassModelAdPostcode_'.$id];         
       $GLOBALS['erLhcoreClassModelAdPostcode_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhcoreClassModelAdPostcode', (int)$id );
       
       return $GLOBALS['erLhcoreClassModelAdPostcode_'.$id];
   }
   
   public static function getIDByZipCode($zip_code) {
   	   $data = explode(' ', $zip_code);
   	   
   	   if (count($data) > 1) {
		  $codes = self::getList(array('filter' => array('postcode' => $data[1]))); 
		  if (count($codes) == 0) {
		  	$codes = self::getList(array('filter' => array('postcode' => $data[0])));
		  }		 
   	   } else {
   	   	  $codes = self::getList(array('filter' => array('postcode' => $zip_code)));
   	   }
            
     

       if (count($codes) > 0){
           return array_shift($codes);         
       }
       
       return false;
   }
   
   public function removeThis()
   {
       erLhcoreClassAbstract::getSession('')->delete($this);
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 500, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       if (!isset($params['disable_sql_cache']))
       {
          $sql = CSCacheAPC::multi_implode(',',$params);
          
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('global_cache_'.$cache->getCacheVersion('site_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {
              return $result;
          }       
       }
       
       
       $session = erLhcoreClassAbstract::getSession('');
       $q = $session->createFindQuery( 'erLhAbstractModelPostcode' );  
       
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
                           $conditions[] = $q->expr->in( $field, $q->bindValue($fieldValue ));
                       } 
                  }
                  
                  if (isset($params['filterlt']) && count($params['filterlt']) > 0)
                  {
                       foreach ($params['filterlt'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue ));
                       } 
                  }
                  
                  if (isset($params['filtergt']) && count($params['filtergt']) > 0)
                  {
                       foreach ($params['filtergt'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue ));
                       } 
                  }      
                  
                  if (count($conditions) > 0)
                  {
                      $q->where( 
                                 $conditions   
                      );
                  } 
                  
                  $q->limit($params['limit'],$params['offset']);
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'postcode ASC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'erLhAbstractModelPostcode' );
           
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
                   $conditions[] = $q2->expr->in( $field, $q->bindValue($fieldValue ));
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
                   $conditions[] = $q2->expr->gt( $field, $q->bindValue($fieldValue ));
               } 
          }      
          
          if (count($conditions) > 0)
          {
              $q2->where( 
                         $conditions   
              );
          }
           
          $q2->limit($params['limit'],$params['offset']);
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'postcode ASC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_abstract_postcode.id', 'items.id' );          
       }
              
       $objects = $session->find( $q, 'erLhAbstractModelPostcode' );
       
       if (!isset($params['disable_sql_cache'])) {
              $cache->store($cacheKey,$objects);           
       }
       
      return $objects; 
   }
   
   // Funcions for distance calculations
   function get_distance($zip1, $zip2) {

      // returns the distance between to zip codes.  If there is an error, the 
      // function will return false and set the $last_error variable.
      
      $this->chronometer();         // start the clock
      
      if ($zip1 == $zip2) return 0; // same zip code means 0 miles between. :)
      
      // get details from database about each zip and exit if there is an error
      
      $details1 = $this->get_zip_point($zip1);
      $details2 = $this->get_zip_point($zip2);
      if ($details1 == false) {
         $this->last_error = "No details found for zip code: $zip1";
         return false;
      }
      if ($details2 == false) {
         $this->last_error = "No details found for zip code: $zip2";
         return false;
      }     

      // calculate the distance between the two points based on the lattitude
      // and longitude pulled out of the database.
      
      $miles = $this->calculate_mileage($details1->lat, $details2->lat, $details1->lon, $details2->lon);
      
      $this->last_time = $this->chronometer();
 
      if ($this->units == _UNIT_KILOMETERS) return round($miles * _M2KM_FACTOR, $this->decimals);
      else return round($miles, $this->decimals); // must be miles      
   }
   
   public static function get_distance_by_zip_and_cord($lat, $lon, $zip_code)
   {
       $obj = self::getIDByZipCode($zip_code);
       $miles = round($obj->calculate_mileage($lat, $obj->lat, $lon, $obj->lon),2);
       
       return $miles > 0 ?  $miles : 0;
   }
   
   function get_zip_point($zip) {
   
      // This function pulls just the lattitude and longitude from the
      // database for a given zip code.          
//      $sql = "SELECT lat, lon FROM lh_abstract_zip_code WHERE zip_code='$zip'";            
      $points = self::getList(array('filter' => array('postcode' => $zip)));
      if (count($points) > 0){
          return array_shift($points);
      }  

      return false;     
   }

   function calculate_mileage($lat1, $lat2, $lon1, $lon2) {
 
      // used internally, this function actually performs that calculation to
      // determine the mileage between 2 points defined by lattitude and
      // longitude coordinates.  This calculation is based on the code found
      // at http://www.cryptnet.net/fsp/zipdy/
       
      // Convert lattitude/longitude (degrees) to radians for calculations
      $lat1 = deg2rad($lat1);
      $lon1 = deg2rad($lon1);
      $lat2 = deg2rad($lat2);
      $lon2 = deg2rad($lon2);
      
      // Find the deltas
      $delta_lat = $lat2 - $lat1;
      $delta_lon = $lon2 - $lon1;
	
      // Find the Great Circle distance 
      $temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
      $distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));

      return $distance;
   }
   
   function get_zips_in_range($zip, $range, $sort=1, $include_base) {
       
      // returns an array of the zip codes within $range of $zip. Returns
      // an array with keys as zip codes and values as the distance from 
      // the zipcode defined in $zip.
      
      $this->chronometer();                   // start the clock
      
      $details = $this->get_zip_point($zip);  // base zip details
          
      if ($details == false) return false;
      
      // This portion of the routine  calculates the minimum and maximum lat and
      // long within a given range.  This portion of the code was written
      // by Jeff Bearer (http://www.jeffbearer.com). This significanly decreases
      // the time it takes to execute a query.  My demo took 3.2 seconds in 
      // v1.0.0 and now executes in 0.4 seconds!  Greate job Jeff!
      
      // Find Max - Min Lat / Long for Radius and zero point and query
      // only zips in that range.
      $lat_range = $range/69.172;
      $lon_range = abs($range/(cos($details->lat) * 69.172));
      $min_lat = number_format($details->lat - $lat_range, "4", ".", "");
      $max_lat = number_format($details->lat + $lat_range, "4", ".", "");
      $min_lon = number_format($details->lon - $lon_range, "4", ".", "");
      $max_lon = number_format($details->lon + $lon_range, "4", ".", "");

      $return = array();    // declared here for scope

//      $sql = "SELECT zip_code, lat, lon FROM lh_abstract_zip_code ";
     
      if (!$include_base) $sql = "postcode <> '$zip' AND ";
      else $sql = ""; 
      $sql .= "lat BETWEEN '$min_lat' AND '$max_lat' 
               AND lon BETWEEN '$min_lon' AND '$max_lon'";
                    
       $session = erLhcoreClassAd::getSession('');
       $q = $session->createFindQuery( 'erLhAbstractModelPostcode' );
       $q->where( $sql )
       ->limit( 80000 );       
       $items = $session->find( $q, 'erLhAbstractModelPostcode' );   
       
       $return = array();
       
      foreach ($items as $item)
      {
           $dist = $this->calculate_mileage($details->lat,$item->lat,$details->lon,$item->lon);
           if ($this->units == _UNIT_KILOMETERS) $dist = $dist * _M2KM_FACTOR;
           if ($dist <= $range) {
              $return[str_pad($item->postcode, 5, "0", STR_PAD_LEFT)] = round($dist, $this->decimals);
           }            
      }
             
      // sort array
      switch($sort)
      {
         case _ZIPS_SORT_BY_DISTANCE_ASC:
            asort($return);
            break;
            
         case _ZIPS_SORT_BY_DISTANCE_DESC:
            arsort($return);
            break;
            
         case _ZIPS_SORT_BY_ZIP_ASC:
            ksort($return);
            break;
            
         case _ZIPS_SORT_BY_ZIP_DESC:
            krsort($return);
            break; 
      }
      
      $this->last_time = $this->chronometer();
      
      if (empty($return)) return false;
      return $return;
   }    

   function setZipCodesInRange($zipCode,$range) {
           
       $degrees = $range * $this->milesToDegrees;

       $details = $this->get_zip_point($zipCode);  // base zip details
              
       // Set up an array to return.
       $ret_array = array ();
    
       // Lat/Lon ranges 
       $ret_array['max_lat'] = $details->lat + $degrees;
       $ret_array['max_lon'] = $details->lon + $degrees;
       $ret_array['min_lat'] = $details->lat - $degrees;
       $ret_array['min_lon'] = $details->lon - $degrees;
    
       $maxCoords = $ret_array;
             
        $query = "";
        $query.= '  (lat <= ' . $maxCoords['max_lat'] . ' ';
        $query.= '    AND ';
        $query.= '   lat >= ' . $maxCoords['min_lat'] . ') ';
        $query.= '  AND ';
        $query.= '  (lon <= ' . $maxCoords['max_lon'] . ' ';
        $query.= '    AND ';
        $query.= '   lon >= ' . $maxCoords['min_lon'] . ') ';
    
        $session = erLhcoreClassAd::getSession('');
       $q = $session->createFindQuery( 'erLhAbstractModelPostcode' );
       $q->where( $query )
       ->limit( 80000 );       
       $items = $session->find( $q, 'erLhAbstractModelPostcode' ); 
              
       $return = array();
       
       foreach ($items as $item)
       {
           $dist = $this->calculate_mileage($details->lat,$item->lat,$details->lon,$item->lon);
           if ($this->units == _UNIT_KILOMETERS) $dist = $dist * _M2KM_FACTOR;
           if ($dist <= $range) {
              $return[str_pad($item->postcode, 5, "0", STR_PAD_LEFT)] = round($dist, $this->decimals);
           }            
       }
       
      $sort = _ZIPS_SORT_BY_DISTANCE_ASC;
      // sort array
      switch($sort)
      {
         case _ZIPS_SORT_BY_DISTANCE_ASC:
            asort($return);
            break;
            
         case _ZIPS_SORT_BY_DISTANCE_DESC:
            arsort($return);
            break;
            
         case _ZIPS_SORT_BY_ZIP_ASC:
            ksort($return);
            break;
            
         case _ZIPS_SORT_BY_ZIP_DESC:
            krsort($return);
            break; 
      }
      
      $this->last_time = $this->chronometer();
      
      if (empty($return)) return false;
      return $return;
      
      
       
        
       return $return;
  }
   
   
   function chronometer()  {
 
       // chronometer function taken from the php manual.  This is used primarily
       // for debugging and anlyzing the functions while developing this class.  
      
       $now = microtime(TRUE);  // float, in _seconds_
       $now = $now + time();
       $malt = 1;
       $round = 7;
      
       if ($this->last_time > 0) {
           /* Stop the chronometer : return the amount of time since it was started,
           in ms with a precision of 3 decimal places, and reset the start time.
           We could factor the multiplication by 1000 (which converts seconds
           into milliseconds) to save memory, but considering that floats can
           reach e+308 but only carry 14 decimals, this is certainly more precise */
          
           $retElapsed = round($now * $malt - $this->last_time * $malt, $round);
          
           $this->last_time = $now;
          
           return $retElapsed;
       } else {
           // Start the chronometer : save the starting time
        
           $this->last_time = $now;
          
           return 0;
       }
   }      
   
   public static function validateZipCode($zip_code)
   {          
       $valid = ($zip_code != '' && self::getCount(array('filter' => array('postcode' => $zip_code))) > 0);
       if ($valid == true) {                         
              return $zip_code;
       } else {       
           return false;
       }
   }
   
   public static function validateZipCodeOrLocation($zip_code)
   {          
       $zip_code = erLhcoreClassCharTransform::TransformToURL($zip_code);
       
       $valid = ($zip_code != '' && self::getCount(array('filter' => array('postcode' => $zip_code))) > 0);
       if ( $valid == true ) {
           return $zip_code;
       } elseif ( $zip_code != '' && erLhAbstractModelSubRegions::getCount(array('filter' => array('aurl' => $zip_code))) ) {
           return $zip_code;
       } else {
           
           if ( $zip_code != '' ) {

               $zipCodeFormat = str_replace(array(' ','-'),array('',''),$zip_code);

               if ( strlen($zipCodeFormat) == 5 ) {
                   $zipCodeFormat = substr($zipCodeFormat,0,2);
               } elseif ( strlen($zipCodeFormat) == 6 ) {
                   $zipCodeFormat = substr($zipCodeFormat,0,3);
               } elseif ( strlen($zipCodeFormat) == 7 ) {
                   $zipCodeFormat = substr($zipCodeFormat,0,4);
               }

               if (self::getCount(array('filter' => array('postcode' => $zipCodeFormat))) > 0) {
                   return $zipCodeFormat;
               }

           } else {
                return false;
           }
       }   
   }

   public static function getLocation($location)
   {
   
       $item= self::getIDByZipCode($location);

       if ($item !== false){
           return $item;
       }

       $list = erLhAbstractModelSubRegions::getList(array('filter' => array('aurl' => $location)));

       if ( !empty($list) ) {
           return array_shift($list);
       }  
              
       $zipCodeFormat = str_replace(' ','',$location);

       if ( strlen($zipCodeFormat) == 5 ) {
           $zipCodeFormat = substr($zipCodeFormat,0,2);
       } elseif ( strlen($zipCodeFormat) == 6 ) {
           $zipCodeFormat = substr($zipCodeFormat,0,3);
       } elseif ( strlen($zipCodeFormat) == 7 ) {
           $zipCodeFormat = substr($zipCodeFormat,0,4);
       }

       $item = self::getIDByZipCode($zipCodeFormat);

       if ($item !== false){
           return $item;
       }               
       
   }

   public static function getZipInRangeStatic($zip_code,$range)
   {
       $z = new erLhcoreClassModelAdPostcode();
       
       if ($range > 0) {
            $zips = $z->get_zips_in_range($zip_code, $range, _ZIPS_SORT_BY_DISTANCE_ASC, true); 
       } else {
           $zips[$zip_code] = 0;
       }
          
       $session = erLhcoreClassCar::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "id" )->from( "lh_abstract_postcode" );
       $conditions = array();
       $conditions[] = $q->expr->in( 'postcode', array_keys($zips) );

       $q->where( 
                 $conditions   
       );

       $stmt = $q->prepare();       
       $stmt->execute();   
       $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);   
       
       return $result;
   }
      
   public static function is_valid_uk_postcode($postcode) {
       
        $postcode = strtoupper($postcode);
        
        $pattern = "/^((((A[BL]|B[ABDHLNRSTX]?|C[ABFHMORTVW]|D[ADEGHLNTY]|E[HNX]?|F[KY]|G[LUY]?|H[ADGPRSUX]|I[GMPV]|JE|K[ATWY]|L[ADELNSU]?|M[EKL]?|N[EGNPRW]?|O[LX]|P[AEHLOR]|R[GHM]|S[AEGKLMNOPRSTY]?|T[ADFNQRSW]|UB|W[ADFNRSV]|YO|ZE)[1-9]?[0-9]|((E|N|NW|SE|SW|W)1|EC[1-4]|WC[12])[A-HJKMNPR-Y]|(SW|W)([2-9]|[1-9][0-9])|EC[1-9][0-9])[0-9][ABD-HJLNP-UW-Z]{2}))$/";
     
        if (preg_match($pattern, $postcode) || self::getIDByZipCode($postcode) !== false ) {
            return true;
        }
        
        return false;
   }

   public function saveThis()
   {
       erLhcoreClassAd::getSession('')->saveOrUpdate($this);
   }
   
   public $id = null;
   public $postcode = '';
   public $paper = '';
   public $ad_searchtext = '';

   public $lat = 0;
   public $lon = 0;

     
   var $last_error = "";            // last error message set by this class
   var $last_time = 0;              // last function execution time (debug info)
   var $units = _UNIT_MILES;        // miles or kilometers
   var $decimals = 2;               // decimal places for returned distance
   
   
   var $milesToDegrees = .01445;
}



?>