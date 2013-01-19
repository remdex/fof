<?

class erLhcoreClassModelWordWord {

   public function getState()
   {
       return array (
               'id'                 => $this->id,
               'word'               => $this->word,
               'ln'                 => $this->ln,
               'dw'                 => $this->dw,
               'ptime'              => $this->ptime,
               'fof'                => $this->fof,
               'uc'                 => $this->uc,
               'wf'                 => $this->wf,
               'mf'                 => $this->mf,
               'uw'                 => $this->uw,
               'we'                 => $this->we,
               'sn'                 => $this->sn,
               'an'                 => $this->an,
       );
   }
   
   public function __toString()
   {
       if ($this->uc == 1){
           $word = $this->word;
           $word[0] = mb_strtoupper($word[0]);
           return $word;
       } else {
           return $this->word;
       }
       
   }

   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($aid)
   {
       $Album = erLhcoreClassWord::getSession('dbmongo')->load( 'erLhcoreClassModelWordWord', new MongoId($aid) ); 
       return $Album;
   }
   
   public static function fetchByWordLanguage($language, $word)
   {
       $list = self::getList(array('filter' => array('ln' => $language, 'word' => $word)));
       
       if (!empty($list)){
           return array_pop($list);
       }
       
       return false;
   }
   
   public function removeThis($params = array())
   {
     
       // Delete self ad
       erLhcoreClassWord::getSession()->delete($this);
   }

   public function saveThis()
   {          
       erLhcoreClassWord::getSession()->saveOrUpdate($this);  
       
       if ( $this->dw == 1 ) {
           $db = ezcDbInstance::get();
           $stmt = $db->prepare('INSERT IGNORE INTO lh_word_word (word,real_word,guid,ln) VALUES (:word,:real_word,:guid,:ln)');
           $wordAlpha = mb_strtolower(erLhcoreClassCharTransform::TransformToURL($this->word));        
           $stmt->bindValue(':word',erLhcoreClassWordValidation::BuildTrigrams($wordAlpha));
           $stmt->bindValue(':real_word',trim($wordAlpha));
           $stmt->bindValue(':guid',trim($this->id));
           $stmt->bindValue(':ln',trim($this->ln));
           $stmt->execute();     
       }
   }
   
   public static function getWordID($word_string, $language)
   {
       $list = self::getList(array('filter' => array('word' => $word_string, 'ln' => $language)));
       if (!empty($list)){
           return array_shift($list);
       }
       
       $word = new erLhcoreClassModelWordWord();
       $word->word = $word_string;
       $word->ln = $language;
       $word->ptime = new MongoDate();
       $word->saveThis();
       
       return $word;
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassWord::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( erLhcoreClassWord::$wordTable );

       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $fieldValue );
           }
       }
       
       if (isset($params['filterne']) && count($params['filterne']) > 0)
       {                     
           foreach ($params['filterne'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->neq( $field, $fieldValue );
           }
       }
       
       if (isset($params['filterall']) && count($params['filterall']) > 0)
       {
           foreach ($params['filterall'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->allin( $field, $fieldValue );
           } 
       }
      
       if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
       }
       
       if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $fieldValue );
           }
       }
       
       if (isset($params['filterlike']) && count($params['filterlike']) > 0)
       {
           foreach ($params['filterlike'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->like( $field, $fieldValue );
           }
       }
       
  
      if (count($conditions) > 0)
      { 
           $q->where (
                 ezcQueryMongoDb::arrayFlatten( $conditions )
           );
      }

           
      return $q->execute()->count(); 
   }
   
   public function __get($var)
   {
       switch ($var) {
           
       	case 'dictionary':
       		   $dictionary = erLhcoreClassModelWordDictionary::getList(array('filter' => array('dc' => $this->ln)));
       		   return array_pop($dictionary);
       		break;
       		
       	case 'forms':
       		   $forms = erLhcoreClassModelWordWord::getList(array('limit' => 60, 'filter' => array('fof' => $this->id)));       		   
       		   if ( !empty($forms) ) {
       		      array_unshift($forms,$this);
       		   }       		          		   
       		   $this->forms = $forms;       		   
       		   return $this->forms;
       		break;
       		
       	case 'total_forms':
       	        $this->total_forms = erLhcoreClassModelWordWord::getCount(array('filter' => array('fof' => $this->id)));    
       	        return $this->total_forms;
       	    break;
       	    
       	case 'synonyms':
       	        $synonyms = array();
       	        if (!empty($this->sn)) {
       	           $synonyms = erLhcoreClassModelWordWord::getList(array('filterin' => array('_id' => $this->sn)));
       	        }       
       	        
       	        return $synonyms;
       	    break;
       	    
       	case 'antonyms':
       	        $antonyms = array();
       	        if (!empty($this->an)) {
       	            $antonyms = erLhcoreClassModelWordWord::getList(array('filterin' => array('_id' => $this->an)));
       	        }       
       	        
       	        return $antonyms;
       	    break;
       	    	
       	case 'base_form':
       	        $this->base_form = array();
       	        if ( is_array($this->fof) ) {       	                   	            
       	            $this->base_form = erLhcoreClassModelWordWord::getList(array('filterin' => array('_id' => $this->fof)));
       	        } elseif (is_object($this->fof)) {
       		       $this->base_form = array(erLhcoreClassModelWordWord::fetch($this->fof));
       	        }
       	        
       		   return $this->base_form;
       		break;
       	
       	case 'main_word':
       	        if ($this->mf == 1){
       	            return $this->word;
       	        } else {
       	            $word = current($this->base_form);
       	            return $word->word;
       	        }
       	    break;
       		
       			
       	case 'type': 
       	        $this->type = false;    
       	        
       	        if ($this->ln == 'lt'){
       	                   	                  	            
           	        if (isset($this->wf['p']) && is_array($this->wf['p'])){
               	        $items = array_intersect(array('D','V','I','M'),$this->wf['p']);
               	        
               	        if (!empty($items)){
               	            $this->type = self::TYPE_NOUN;
               	            $this->typeLetter = $items;
               	        } else {
               	            $items = array_intersect(array('A','Q','B'),$this->wf['p']);
               	            if (!empty($items)){
                   	            $this->type = self::TYPE_ADJECTIVE;
                   	            $this->typeLetter = $items;
               	            } else { 
               	                $items = array_intersect(array('X','P','T','E','Y'),$this->wf['p']);
               	                if (!empty($items)){
                       	            $this->type = self::TYPE_VERB;
                       	            $this->typeLetter = $items;
                   	            } else { 
                   	                $items = array_intersect(array('Z'),$this->wf['p']);
                   	                if (!empty($items)){
                           	            $this->type = self::TYPE_CONJUNCTION;
                           	            $this->typeLetter = $items;
                       	            } else { 
                       	                $items = array_intersect(array('z'),$this->wf['p']);
                       	                if (!empty($items)){
                               	            $this->type = self::TYPE_PREPOSITION;
                               	            $this->typeLetter = $items;
                           	            } else { 
                           	                $items = array_intersect(array('W'),$this->wf['p']);
                           	                if (!empty($items)){
                                   	            $this->type = self::TYPE_PARTICLE;
                                   	            $this->typeLetter = $items;
                               	            } else { 
                               	                $items = array_intersect(array('C'),$this->wf['p']);
                               	                if (!empty($items)){
                                       	            $this->type = self::TYPE_PRONOUN;
                                       	            $this->typeLetter = $items;
                                   	            } else { 
                                   	                $items = array_intersect(array('H'),$this->wf['p']);
                                   	                if (!empty($items)){
                                           	            $this->type = self::TYPE_NUMERAL;
                                           	            $this->typeLetter = $items;
                                       	            } else { 
                                       	                $items = array_intersect(array('O'),$this->wf['p']);
                                       	                if (!empty($items)){
                                               	            $this->type = self::TYPE_ADVERB;
                                               	            $this->typeLetter = $items;
                                           	            }
                                       	            }
                                   	            }
                               	            }
                           	            }
                       	            }
                   	            }
               	            }
               	        }
               	        
           	        } elseif ($this->mf == 0 || !empty($this->fof)) {
           	            $mainWords = $this->base_form;
           	                       	                       	            
           	            $letters = array();
           	            foreach ($mainWords as $word) {
           	               if (isset($word->wf['p']) && is_array($word->wf['p'])){ 
           	                    $letters = array_merge($word->wf['p'],$letters);
           	               }
           	            }
           	            
           	            $items = array_intersect(array('D','V','I','M'),$letters);
               	        
               	        if (!empty($items)){
               	            $this->type = self::TYPE_NOUN;
               	            $this->typeLetter = $items;
               	        } else {
               	            $items = array_intersect(array('A','Q','B'),$letters);
               	            if (!empty($items)){
                   	            $this->type = self::TYPE_ADJECTIVE;
                   	            $this->typeLetter = $items;
               	            } else { 
               	                $items = array_intersect(array('X','P','T','E','Y'),$letters);
               	                if (!empty($items)){
                       	            $this->type = self::TYPE_VERB;
                       	            $this->typeLetter = $items;
                   	            } else { 
                   	                $items = array_intersect(array('Z'),$letters);
                   	                if (!empty($items)){
                           	            $this->type = self::TYPE_CONJUNCTION;
                           	            $this->typeLetter = $items;
                       	            } else { 
                       	                $items = array_intersect(array('z'),$letters);
                       	                if (!empty($items)){
                               	            $this->type = self::TYPE_PREPOSITION;
                               	            $this->typeLetter = $items;
                           	            } else { 
                           	                $items = array_intersect(array('W'),$letters);
                           	                if (!empty($items)){
                                   	            $this->type = self::TYPE_PARTICLE;
                                   	            $this->typeLetter = $items;
                               	            } else { 
                               	                $items = array_intersect(array('C'),$letters);
                               	                if (!empty($items)){
                                       	            $this->type = self::TYPE_PRONOUN;
                                       	            $this->typeLetter = $items;
                                   	            } else { 
                                   	                $items = array_intersect(array('H'),$letters);
                                   	                if (!empty($items)){
                                           	            $this->type = self::TYPE_NUMERAL;
                                           	            $this->typeLetter = $items;
                                       	            } else { 
                                       	                $items = array_intersect(array('O'),$letters);
                                       	                if (!empty($items)){
                                               	            $this->type = self::TYPE_ADVERB;
                                               	            $this->typeLetter = $items;
                                           	            }
                                       	            }
                                   	            }
                               	            }
                           	            }
                       	            }
                   	            }
               	            }
               	        }      	            
           	        }  
       	        }     
       	               	       
           	    /*if (isset($this->wf['p']) && in_array('D',$this->wf['p'])  ) {
                    $this->typeLetter = 'D';           	        
           	        $this->type = self::TYPE_NOUN ;
           	    } elseif (isset($this->wf['p']) && in_array('V',$this->wf['p'])) {
           	        $this->typeLetter = 'V';
           	        $this->type = self::TYPE_NOUN ;
           	    } elseif (isset($this->wf['p']) && in_array('M',$this->wf['p'])) {
           	        $this->typeLetter = 'M';
           	        $this->type = self::TYPE_NOUN ;
           	    }*/
           	    
           	    if  ($this->type === self::TYPE_NOUN){
           	        $this->initFormEnds();
           	    }
           	    	    
           	    return $this->type;       	        
       	    break;
       	
       	case 'word_base':       	        
       	        $word_base = mb_substr($this->main_word,0,mb_strlen($this->main_word)-$this->endWordLength);
       	        return $word_base;
       	    break;
       	
       	case 'singular_forms':
           	    $forms = array();
           	    $counterOffset = 1000;
           	    foreach ($this->forms as $form) {
           	        //if (!is_object($form->formAffix) || $form->formAffix->ft == erLhcoreClassModelWordAffixFormat::TYPE_SINGULAR){ 
           	        if (is_object($form->formAffix) && $form->formAffix->ft == erLhcoreClassModelWordAffixFormat::TYPE_SINGULAR){ 
           	            if (is_object($form->formAffix) && is_object($form->formAffix->question)){
           	                $forms[(int)$form->formAffix->question->p*10][] = $form;
           	            } else {
           	                $forms[$counterOffset++][] = $form;
           	            }
           	        }
           	    }
           	    
           	    ksort($forms);
           	               	               	    
           	    return $forms;       	    
       	    break;
       	    
       	case 'pluar_forms':
           	    $forms = array();
           	    $counterOffset = 1000;
           	    foreach ($this->forms as $form) {
           	        if (is_object($form->formAffix) && $form->formAffix->ft == erLhcoreClassModelWordAffixFormat::TYPE_PLUAR){ 
           	            if (is_object($form->formAffix) && is_object($form->formAffix->question)){
           	                $forms[(int)$form->formAffix->question->p*10][] = $form;
           	            } else {
           	                $forms[$counterOffset++][] = $form;
           	            }
           	        }
           	    }
           	    
           	    ksort($forms);
           	    
           	    return $forms;       	    
       	    break;
       	    
       	case 'use_cases':
       	    
       	    $right_words = erLhcoreClassModelWordLink::getList(array('limit' => 15,'filter' => array('ln' => $this->ln, 'w' => $this->id),'sort' => array('at' => -1)));

       	    erLhcoreClassWord::preparseArray($right_words);
       	    
            $dotGen = new erLhcoreClassWordDotGenerate(array('main_word' => $this, 'words_list' => $right_words));
            $dotGen->parse();
            $DotString = $dotGen->getDotString();
                                    
            file_put_contents("var/tmpfiles/".$this->id."outputdot.dot",$DotString);
            system("dot -Tsvg -o var/tmpfiles/".$this->id."outputdot.svg var/tmpfiles/".$this->id."outputdot.dot");
            $this->use_cases = file_get_contents("var/tmpfiles/".$this->id."outputdot.svg");
            
            unlink("var/tmpfiles/".$this->id."outputdot.dot");
            unlink("var/tmpfiles/".$this->id."outputdot.svg");
            
            return $this->use_cases;
            
       	    break;
       	           
       	default:
       		break;
       }
   }
   
   function mbStringToArray ($string) {       
        $strlen = mb_strlen($string);
        while ($strlen) { 
            $array[] = mb_substr($string,0,1); 
            $string = mb_substr($string,1,$strlen); 
            $strlen = mb_strlen($string); 
        }
        return $array; 
    } 

    //http://ualgiman.dtiltas.lt/daiktavardziu_linksniuotes.html 
   public function initFormEnds()
   {  
        $letters = self::mbStringToArray($this->main_word);
              
             
        
        $letters = array_reverse($letters);
        $letterCombained = '';
        $usedFormats = array();
                        
        foreach ($letters as $letter) { 
            $letterCombained = $letter . $letterCombained;      
                                   
            $list = erLhcoreClassModelWordAffixFormat::getList(array('filterin' => array('lt' => $this->typeLetter),'filter' => array('ln' => $this->ln, 'em' => $letterCombained)));
                                           
            if ( !empty($list) ) {
                $item = current($list);       	                    
                if ( $item->isSupported($this->word) ) { // Check against first 
                    $this->endWordLength = mb_strlen($item->em);
                    $this->noun_forms_ends = $list;
                    
                    // Standartinai linksniai
                    foreach ($this->noun_forms_ends as $formend) {
                        
                        if ($this->mf == 1){
               	            foreach ($this->forms as $form) {           	                           	                
               	                if ($form->word == $this->word_base.$formend->fr && !is_object($form->formAffix)){ 
               	                    $form->formAffix = $formend; 
               	                    $usedFormats[] = (string)$formend->id;
               	                }
               	            }
                        }
                        
                        // FIX ME, can be multiple word forms for the same word
                        if ($this->word == $this->word_base.$formend->fr){                            
           	                   $this->formAffixForms[] = $formend;
           	            }
           	            
           	        }

           	        // Sutampantys linksniai          	        
           	        foreach ($this->noun_forms_ends as $formend) {       

//           	            echo  $formend->em,' - ',$formend->ft,'-',$formend->fr,"<br/>";        
 	            
           	            if (!in_array((string)$formend->id,$usedFormats)) { 

           	                if ($this->mf == 1){
               	                foreach ($this->forms as $form) { 
               	                    if ($form->word == $this->word_base.$formend->fr){ 
               	                        $formCLone = clone $form;
               	                        $formCLone->formAffix = $formend;
               	                        $this->forms[] = $formCLone;
               	                    }
               	                }  
           	                }       	                
           	            }
           	        }
           	        
                }
            }
        }

        /*echo '<pre>';
        echo   $this->main_word,"<br/>"; 
        print_r($this->formAffix);
        echo '</pre>';*/
        
//        print_r();
        
        // Visi zodziai kuriems neradom linskiu
        /*foreach ($this->forms as $form) { 
            echo $form->word,'-',$form->formAffix,'-',$form->formAffix->id,"<br/>";
            
            if (!($form->formAffix instanceof erLhcoreClassModelWordAffixFormat))
            {                
                
                $letters = self::mbStringToArray($form->word);               
                $letters = array_reverse($letters);
                $letterCombained = '';
                foreach ($letters as $letter) { 
                     $letterCombained = $letter . $letterCombained;                                   
                     $list = erLhcoreClassModelWordAffixFormat::getList(array('filter' => array('ln' => $this->ln, 'lt' => $this->typeLetter ,'em' => $letterCombained)));
                     if ( !empty($list) ) {
                        $item = current($list);       	                    
                        if ( $item->isSupported($form->word) ) {
                            
                            $this->endWordLength = mb_strlen($item->em);
                            $this->noun_forms_ends = $list;
                            
                            // Standartinai linksniai
                            foreach ($this->noun_forms_ends as $formend) {
                   	            foreach ($this->forms as $form) {           	                           	                
                   	                if ($form->word == $form->word_base.$formend->fr && !is_object($form->formAffix)){ 
                   	                    $form->formAffix = $formend; 
                   	                    $usedFormats[] = (string)$formend->id;
                   	                }
                   	            }
                   	        }
                        }
                     }
                }
            }
        }*/
        
        
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 200, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassWord::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelWordWord', isset($params['ignore_fields']) ? $params['ignore_fields'] : array() );  
       
              
       $conditions = array(); 
               
                
      if (isset($params['filter']) && count($params['filter']) > 0)
      {                     
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           }
      } 
             
      if (isset($params['filterne']) && count($params['filterne']) > 0)
      {                     
           foreach ($params['filterne'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->neq( $field, $q->bindValue($fieldValue) );
           }
      } 
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
      {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
      }
      
      if (isset($params['filterall']) && count($params['filterall']) > 0)
      {
           foreach ($params['filterall'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->allin( $field, $fieldValue );
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
      
      if (isset($params['filterlike']) && count($params['filterlike']) > 0)
      {
           foreach ($params['filterlike'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->like( $field, $fieldValue );
           }
      }
       
      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
      {
           foreach ($params['filterlte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
           }
      }

      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
      {
           foreach ($params['filtergte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gte( $field, $q->bindValue($fieldValue) );
           }
      }

      if (count($conditions) > 0)
      {
          $q->where (
                     ezcQueryMongoDb::arrayFlattenRecursiv( $conditions )  
          );
      }

      $q->limit($params['limit'],$params['offset']);

      $q->orderBy(isset($params['sort']) ? $params['sort'] : array('_id' => -1) ); 
      
      $objects = $session->find( $q );

      return $objects; 
   }
   
   const TYPE_UNKOWN = -1;
   const TYPE_NOUN = 0;
   const TYPE_ADJECTIVE = 1;
   const TYPE_VERB = 2;
   const TYPE_CONJUNCTION = 3;// Jungtukas
   const TYPE_PREPOSITION = 4;// Prielinksnis
   const TYPE_PARTICLE = 5;   // Dalelyte
   
   /*'p' => 
    array (
      '0' => 'C',
    ),
    's' => 'C',*/
   const TYPE_PRONOUN = 6;   // Įvardis
   
   
   const TYPE_NUMERAL = 7;   // Skaitvardis
   
   /*'p' => 
    array (
      '0' => 'O',
    ),
    's' => 'O'*/
   const TYPE_ADVERB = 8;   // Prieveiksmis
   
   
   public $id = null;
   public $word = null;
   public $ptime = null;
   public $dw = 0;
   public $ln = null;
   public $fof = array();
   public $uc = 0;
   public $mf = 0;
   public $uw = 0;
   public $we = '';
   public $wf = array();
   public $an = array();
   public $sn = array();
   
   // Logical attribute
   public $endWord = ''; // Zodzio galune
   public $formAffix = false; // Zodzio galune
   public $typeLetter = '';
   public $formAffixForms = array();
}
