<?php

/**
 * This code is mix of WP and phpBB :)
 * */
class erLhcoreClassBBCode
{       
    // From WP, that's why we love open source :)
    public static function _make_url_clickable_cb($matches) {
    	$url = $matches[2];
    	$suffix = '';
    
    	/** Include parentheses in the URL only if paired **/
    	while ( substr_count( $url, '(' ) < substr_count( $url, ')' ) ) {
    		$suffix = strrchr( $url, ')' ) . $suffix;
    		$url = substr( $url, 0, strrpos( $url, ')' ) );
    	}
    
    	if ( empty($url) )
    		return $matches[0];
    
    	return $matches[1] . "<a href=\"$url\" class=\"link\" target=\"_blank\">$url</a>" . $suffix;
   }

   public static function BBCode2Html($text) {
    	$text = trim($text);
        	    
    	// Smileys to find...
    	$in = array( 	 ':)', 	
    					 ':D',
    					 ':(',
    					 ':o',
    					 ':p',
    					 ';)'
    	);
    	
    	// And replace them by...
    	$out = array(	 '<img alt=":)" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_smile.png').'" />',
    	                 '<img alt=":D" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_happy.png').'" />',
    					 '<img alt=":(" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_unhappy.png').'" />',
    					 '<img alt=":o" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_surprised.png').'" />',
    					 '<img alt=":p" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_tongue.png').'" />',
    					 '<img alt=";)" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_wink.png').'" />'
    	);
    	$text = str_replace($in, $out, $text);
    	
    	// BBCode to find...
    	$in = array( 	 '/\[b\](.*?)\[\/b\]/ms',	
    					 '/\[i\](.*?)\[\/i\]/ms',
    					 '/\[u\](.*?)\[\/u\]/ms',
    					 '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
    					 '/\[list\](.*?)\[\/list\]/ms',
    					 '/\[\*\]\s?(.*?)\n/ms'
    	);
    	// And replace them by...
    	$out = array(	 '<strong>\1</strong>',
    					 '<em>\1</em>',
    					 '<u>\1</u>',
    					 '<ol start="\1">\2</ol>',
    					 '<ul>\1</ul>',
    					 '<li>\1</li>'
    	);
    	$text = preg_replace($in, $out, $text);
    		
    	
    	$text = preg_replace_callback('/\[img\](.*?)\[\/img\]/ms', "erLhcoreClassBBCode::_make_url_embed_image", $text);
    	
    	$text = preg_replace_callback('/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms', "erLhcoreClassBBCode::_make_url_embed", $text);
    	    	
    	$text = preg_replace_callback('/\[flattr\](.*?)\[\/flattr\]/ms', "erLhcoreClassBBCode::_make_flattr_embed", $text);
    	
    	
    	// Prepare quote's
    	$text = str_replace("\r\n","\n",$text);    	
    	$text = str_replace("quote]\n","quote]",$text);
    	    	
		$text = preg_replace_callback('#\[quote(?:=&quot;(.*?)&quot;)?\](.+)\[/quote\]#is', "erLhcoreClassBBCode::_make_quate", $text);
		
    	// paragraphs
    	$text = str_replace("\r", "", $text);
    	//$text = "<p>".preg_replace("/(\n){2,}/", "</p><p>", $text)."</p>";
    	$text = nl2br($text);
    	
    	// clean some tags to remain strict
    	// not very elegant, but it works. No time to do better ;)
    	if (!function_exists('removeBr')) {
    		function removeBr($s) {
    			return str_replace("<br />", "", $s[0]);
    		}
    	}
    	
    	$text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
    	$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
    	
    	$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
    	$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
    	
    	return $text;
    }
    
    //phpBB
    public static function _make_quate($matches){
        /**
		* If you change this code, make sure the cases described within the following reports are still working:
		* #3572 - [quote="[test]test"]test [ test[/quote] - (correct: parsed)
		* #14667 - [quote]test[/quote] test ] and [ test [quote]test[/quote] (correct: parsed)
		* #14770 - [quote="["]test[/quote] (correct: parsed)
		* [quote="[i]test[/i]"]test[/quote] (correct: parsed)
		* [quote="[quote]test[/quote]"]test[/quote] (correct: parsed - Username displayed as [quote]test[/quote])
		* #20735 - [quote]test[/[/b]quote] test [/quote][/quote] test - (correct: quoted: "test[/[/b]quote] test" / non-quoted: "[/quote] test" - also failed if layout distorted)
		* #40565 - [quote="a"]a[/quote][quote="a]a[/quote] (correct: first quote tag parsed, second quote tag unparsed)
		*/    
        $blockId = rand(1,9999).time();
        
        $in = $matches[0];
		$in = str_replace("\r\n", "\n", str_replace('\"', '"', trim($in)));

		if (!$in)
		{
			return '';
		}

		// To let the parser not catch tokens within quote_username quotes we encode them before we start this...
		$in = preg_replace('#quote=&quot;(.*?)&quot;\]#ie', "'quote=&quot;' . str_replace(array('[', ']', '\\\"'), array('&#91;', '&#93;', '\"'), '\$1') . '&quot;]'", $in);

		$tok = ']';
		$out = '[';

		$in = substr($in, 1);
		$close_tags = $error_ary = array();
		$buffer = '';

		do
		{
			$pos = strlen($in);
			for ($i = 0, $tok_len = strlen($tok); $i < $tok_len; ++$i)
			{
				$tmp_pos = strpos($in, $tok[$i]);
				if ($tmp_pos !== false && $tmp_pos < $pos)
				{
					$pos = $tmp_pos;
				}
			}

			$buffer .= substr($in, 0, $pos);
			$tok = $in[$pos];
			$in = substr($in, $pos + 1);

			if ($tok == ']')
			{
				if (strtolower($buffer) == '/quote' && sizeof($close_tags) && substr($out, -1, 1) == '[')
				{
					// we have found a closing tag
					$out .= array_pop($close_tags) . ']';
					$tok = '[';
					$buffer = '';

					/* Add space at the end of the closing tag if not happened before to allow following urls/smilies to be parsed correctly
					* Do not try to think for the user. :/ Do not parse urls/smilies if there is no space - is the same as with other bbcodes too.
					* Also, we won't have any spaces within $in anyway, only adding up spaces -> #10982
					if (!$in || $in[0] !== ' ')
					{
						$out .= ' ';
					}*/
				}
				else if (preg_match('#^quote(?:=&quot;(.*?)&quot;)?$#is', $buffer, $m) && substr($out, -1, 1) == '[')
				{
//					$this->parsed_items['quote']++;

					/*// the buffer holds a valid opening tag
					if ($config['max_quote_depth'] && sizeof($close_tags) >= $config['max_quote_depth'])
					{
						// there are too many nested quotes
						$error_ary['quote_depth'] = sprintf($user->lang['QUOTE_DEPTH_EXCEEDED'], $config['max_quote_depth']);

						$out .= $buffer . $tok;
						$tok = '[]';
						$buffer = '';

						continue;
					}*/

					array_push($close_tags, '/quote:'.$blockId);
//					array_push($close_tags, '</blockquote>' );

					if (isset($m[1]) && $m[1])
					{
						$username = str_replace(array('&#91;', '&#93;'), array('[', ']'), $m[1]);
						$username = preg_replace('#\[(?!b|i|u|color|url|email|/b|/i|/u|/color|/url|/email)#iU', '&#91;$1', $username);

						$end_tags = array();
						$error = false;

						preg_match_all('#\[((?:/)?(?:[a-z]+))#i', $username, $tags);
						foreach ($tags[1] as $tag)
						{
							if ($tag[0] != '/')
							{
								$end_tags[] = '/' . $tag;
							}
							else
							{
								$end_tag = array_pop($end_tags);
								$error = ($end_tag != $tag) ? true : false;
							}
						}

						if ($error)
						{
							$username = $m[1];
						}

						$out .= 'quote=&quot;' . $username . '&quot;:]';
					}
					else
					{
						$out .= 'quote:'.$blockId.']';
//						$out .= '<blockquote>';
					}

					$tok = '[';
					$buffer = '';
				}
				else if (preg_match('#^quote=&quot;(.*?)#is', $buffer, $m))
				{
					// the buffer holds an invalid opening tag
					$buffer .= ']';
				}
				else
				{
					$out .= $buffer . $tok;
					$tok = '[]';
					$buffer = '';
				}
			}
			else
			{
/**
*				Old quote code working fine, but having errors listed in bug #3572
*
*				$out .= $buffer . $tok;
*				$tok = ($tok == '[') ? ']' : '[]';
*				$buffer = '';
*/

				$out .= $buffer . $tok;

				if ($tok == '[')
				{
					// Search the text for the next tok... if an ending quote comes first, then change tok to []
					$pos1 = stripos($in, '[/quote');
					// If the token ] comes first, we change it to ]
					$pos2 = strpos($in, ']');
					// If the token [ comes first, we change it to [
					$pos3 = strpos($in, '[');

					if ($pos1 !== false && ($pos2 === false || $pos1 < $pos2) && ($pos3 === false || $pos1 < $pos3))
					{
						$tok = '[]';
					}
					else if ($pos3 !== false && ($pos2 === false || $pos3 < $pos2))
					{
						$tok = '[';
					}
					else
					{
						$tok = ']';
					}
				}
				else
				{
					$tok = '[]';
				}
				$buffer = '';
			}
		}
		while ($in);

		$out .= $buffer;

		if (sizeof($close_tags))
		{
			$out .=  implode('', $close_tags) ;
		}		

		$out = str_replace(array('[quote:'.$blockId.']','[/quote:'.$blockId.']'),array('<blockquote>','</blockquote>'),$out);		
		return $out;        
    }

    
    public static function _make_url_embed_image($matches){
        
        $in = $matches[1];
        $in = trim($in);
		$error = false;
        $forumImage = false;
        
        
		$in = str_replace(' ', '%20', $in);

	    $inline =  ')';
		$scheme = '[a-z\d+\-.]';		
		// generated with regex generation file in the develop folder
		$exp_url = "[a-z]$scheme*:/{2}(?:(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
	
		$inline = ')';
		$www_url = "www\.(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";

		// Localy uploaded photo
		$instance = erLhcoreClassSystem::instance();   
		$instance->wwwDir();		
		if (preg_match('#^'.$instance->wwwDir().'/var/forum/[a-zA-Z0-9_\-.\/\\\]*$#i', $in) ) {
			$forumImage = true;
		// Checking urls
		} elseif ( !preg_match('#^' . $exp_url . '$#i', $in) && !preg_match('#^' . $www_url . '$#i', $in)) {
		    return '[img]' . $in . '[/img]';
		}
		
		// Try to cope with a common user error... not specifying a protocol but only a subdomain
		if ($forumImage == false && !preg_match('#^[a-z0-9]+://#i', $in))
		{
			$in = 'http://' . $in;
		}				     
		
        return "<div class=\"img_embed\"><img src=\"".htmlspecialchars($in)."\" alt=\"\" /></div>";
   }  
   
   public static function _make_url_embed($matches){
        
        $in = $matches[1];
        $in = trim($in);
		$error = false;

		$in = str_replace(' ', '%20', $in);

	    $inline =  ')';
		$scheme = '[a-z\d+\-.]';		
		// generated with regex generation file in the develop folder
		$exp_url = "[a-z]$scheme*:/{2}(?:(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
	
		$inline = ')';
		$www_url = "www\.(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";

		// Checking urls
		if (!preg_match('#^' . $exp_url . '$#i', $in) && !preg_match('#^' . $www_url . '$#i', $in))
		{
		    return '[url='.$matches[1].']' . $matches[2] . '[/url]';
		}

		if (!preg_match('#^[a-z][a-z\d+\-.]*:/{2}#i', $in))
		{
			$in = 'http://' . $in;
		}

        return '<a href="'.$in.'">'.$matches[2].'</a>';
   }
   
   public static function _make_flattr_embed($matches){
        
        $in = $matches[1];
        $in = trim($in);
		$error = false;

		$in = str_replace(' ', '%20', $in);

	    $inline =  ')';
		$scheme = '[a-z\d+\-.]';		
		// generated with regex generation file in the develop folder
		$exp_url = "[a-z]$scheme*:/{2}(?:(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
	
		$inline = ')';
		$www_url = "www\.(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";

		// Checking urls
		if (!preg_match('#^' . $exp_url . '$#i', $in) && !preg_match('#^' . $www_url . '$#i', $in))
		{
		    return '[flattr]' . $matches[1] . '[/flattr]';
		}

		if (!preg_match('#^[a-z][a-z\d+\-.]*:/{2}#i', $in))
		{
			$in = 'http://' . $in;
		}

        return '<a href="'.$in.'" rel="nofollow" title="Flattr this"><img src="'.erLhcoreClassDesign::design('images/icons/flattr-badge-large.png').'"</a>';
   }

   // From WP :)
   public static function _make_web_ftp_clickable_cb($matches) {
    	$ret = '';
    	$dest = $matches[2];
    	$dest = 'http://' . $dest;
    	if ( empty($dest) )
    		return $matches[0];
    
    	// removed trailing [.,;:)] from URL
    	if ( in_array( substr($dest, -1), array('.', ',', ';', ':', ')') ) === true ) {
    		$ret = substr($dest, -1);
    		$dest = substr($dest, 0, strlen($dest)-1);
    	}
    	return $matches[1] . "<a href=\"$dest\" class=\"link\" target=\"_blank\">$dest</a>$ret";
   }

   // From WP :)
   public static function _make_email_clickable_cb($matches) {
    	$email = $matches[2] . '@' . $matches[3];
    	return $matches[1] . "<a href=\"mailto:$email\" class=\"mail\">$email</a>";
   }

   public static function _make_paypal_button($matches){
       
         if (filter_var($matches[1],FILTER_VALIDATE_EMAIL)) {            
            return '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_donations">
            <input type="hidden" name="business" value="'.$matches[1].'">
            <input type="hidden" name="lc" value="US">
            <input type="hidden" name="no_note" value="0">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
            <input type="image" title="Support an artist" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>';
        } else {
            return $matches[0];
        }
   } 

   public static function _make_youtube_block($matches) {     
         
         $data = parse_url($matches[1]);
         
         if (isset($data['query'])){
             parse_str($data['query'],$query);                           
             if (stristr($data['host'],'youtube.com') && isset($query['v']) && ($query['v'] != '')) {             
                 return '<iframe class="youtube-frame" title="YouTube video player" width="480" height="300" src="http://www.youtube.com/embed/'.urlencode($query['v']).'" frameborder="0" allowfullscreen></iframe>';             
             } else {
                 return $matches[0]; 
             }
         } else {
             return $matches[0]; 
         }
   }

   // From WP :)
   public static function make_clickable($ret) {
    	$ret = ' ' . $ret;
    	// in testing, using arrays here was found to be faster
    	$ret = preg_replace_callback('#(?<!=[\'"])(?<=[*\')+.,;:!&$\s>])(\()?([\w]+?://(?:[\w\\x80-\\xff\#%~/?@\[\]-]|[\'*(+.,;:!=&$](?![\b\)]|(\))?([\s]|$))|(?(1)\)(?![\s<.,;:]|$)|\)))+)#is', 'erLhcoreClassBBCode::_make_url_clickable_cb', $ret);
    	$ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]+)#is', 'erLhcoreClassBBCode::_make_web_ftp_clickable_cb', $ret);
    	$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', 'erLhcoreClassBBCode::_make_email_clickable_cb', $ret);

    	// this one is not in an array because we need it to run last, for cleanup of accidental links within links
    	$ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
    	
    	$ret = self::BBCode2Html($ret);
    	
    	// Paypal button
    	$ret = preg_replace_callback('#\[paypal\](.*?)\[/paypal\]#is', 'erLhcoreClassBBCode::_make_paypal_button', $ret);

    	// Youtube block
    	$ret = preg_replace_callback('#\[youtube\](.*?)\[/youtube\]#is', 'erLhcoreClassBBCode::_make_youtube_block', $ret);
   	
    	$ret = trim($ret);
    	return $ret;
   }
   
   // Makes plain text from BB code
   public static function make_plain($ret){
        $ret = ' ' . $ret;
       
        // BBCode to find...
    	$in = array( 	 '/\[b\](.*?)\[\/b\]/ms',	
    					 '/\[i\](.*?)\[\/i\]/ms',
    					 '/\[u\](.*?)\[\/u\]/ms',
    					 '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
    					 '/\[list\](.*?)\[\/list\]/ms',
    					 '/\[\*\]\s?(.*?)\n/ms',
    					 '/\[img\](.*?)\[\/img\]/ms',
    					 '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
    					 '/\[quote\]/ms',
    					 '/\[\/quote\]/ms',
    					 '/\n/ms',
    	);
    	
    	// And replace them by...
    	$out = array(	 '\1',
    					 '\1',
    					 '\1',
    					 '\2',
    					 '\1',
    					 '\1',
    					 '',
    					 '\2 \1',
    					 '',
    					 ' ',
    	);
    	
    	$ret = preg_replace($in, $out, $ret);
    	   
        $ret = trim($ret);
        return $ret;
   }

}


?>