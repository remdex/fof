<?php

class erLhcoreClassWord {
   
   function __construct()
   {
 
   }
   
   public static function getSession($type = 'dbmongo')
   {
       if ( $type == 'dbmongo' ) {
             self::$persistentSession = new ezcPersistentSession(
                ezcDbInstance::get($type),
                new ezcPersistentCodeManager( './pos/lhword' ),
                true 
            );
            return self::$persistentSession;
       } else {
           self::$persistentSessionMysql = new ezcPersistentSession(
                ezcDbInstance::get(''),
                new ezcPersistentCodeManager( './pos/lhword' ),
                false 
            );
            return self::$persistentSessionMysql;           
       }
   }
   
   /**
    * Auto increment functionality
    * 
    * */
   public static function getNextVal($counterName = 'word_counter'){
       
        $ret = ezcDbInstance::get('dbmongo')->dbInstance->command (        
        array(
            'findandmodify' => 'lh_word_counters',
            'query' => array('_id' => $counterName),
            'update' => array('$inc' => array('val' => 1)),
            'upsert' => true,
            'new' => true
            )
        );     
        return $ret['value']['val'];        
   }
	
   public static function preparseArray(array & $items)
   {
       $appendedArray = array();
       $arrayFetch = array();
       
       foreach ($items as $item) {
           if (!in_array((string)$item->w,$appendedArray)){
               $appendedArray[] = (string)$item->w;
               $arrayFetch[] = $item->w;
           }
           
           if (!in_array((string)$item->wl,$appendedArray)){
               $appendedArray[] = (string)$item->wl;
               $arrayFetch[] = $item->wl;           
           }
           
           if (!in_array((string)$item->wr,$appendedArray)){
               $appendedArray[] = (string)$item->wr;
               $arrayFetch[] = $item->wr;           
           }           
       }
       
       $words = erLhcoreClassModelWordWord::getList(array('filterin' => array('_id' => $arrayFetch)));
       
       foreach ($items as & $item) {
           
           if ( key_exists((string)$item->wl,$words) ) {
               $item->word_left = $words[(string)$item->wl];
           }
           
           if ( key_exists((string)$item->wr,$words) ) {
               $item->word_right = $words[(string)$item->wr];
           }
           
           if ( key_exists((string)$item->w,$words) ) {
               $item->word = $words[(string)$item->w];
           }           
       }
   }
   
   public static function splitSentence($sentence, $selectedWord = array())
   {
       $words = explode(' ',$sentence);
       
       foreach ($words as & $word)
       {
           if (mb_strlen($word) > 1){
               $selectedClass = in_array($word,$selectedWord) ? ' class="bitm" ' : '';               
               $word = "<span {$selectedClass} rel=\"".rawurlencode(mb_strtolower(str_replace(array(',','.','“','–','”','„','!','(',')','?',':','"'),'',$word)))."\">".$word."</span>";
           }
       }   
       
       return implode('',$words);     
   }
   
   public static function getStatistic($page) {
       $wordsPlain = explode(' ',trim(str_replace(array('(',')',',','.','[',']',':','?','/','„','“','”','-','?','"',' val.',' min.'),' ',mb_strtolower($page))));
       
       $words = array();
       foreach ($wordsPlain as $item) {
           if (mb_strlen(trim($item)) > 1 && is_numeric(trim($item)) == false){
               $words[] = trim($item);
           }
       }

       $stats = array();
       $stats['total_words'] = count($words);
       
       $words = array_count_values($words);       
       $ret = ezcDbInstance::get('dbmongo')->dbInstance->{erLhcoreClassWord::$wordTable}->find(array('word' => array('$in' => array_keys($words))),array('mf' => true,'ln' => true,'fof' => true,'word' => true,'wf' => true));
      
       $stats['found_words'] = 0;
       
       foreach ($ret as $doc) {
            $word = new erLhcoreClassModelWordWord();
            $word->setState($doc);            
            if ( $word->type !== false ) {                
                $stats['words'][$word->type] = isset($stats['words'][$word->type]) ? $stats['words'][$word->type] + $words[$word->word] : $words[$word->word];
                $stats['words_plain'][$word->type][] = $word->word;
                
            } else {
                $stats['words'][-1] = isset($stats['words'][-1]) ? $stats['words'][-1] + $words[$word->word] : $words[$word->word];
                $stats['words_plain'][-1][] = $word->word;
            }
            $stats['found_words'] += $words[$word->word];
       };
       
       arsort($stats['words']);
       
       arsort($words);
       $stats['words_counter'] = $words;
       
       
       return $stats;
   }
   
   // For all others
   private static $persistentSession;
   
   private static $persistentSessionMysql;
   
   public static $wordTable = "lh_word_word";

}


?>