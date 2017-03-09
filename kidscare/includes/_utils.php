<?php
$ERROR_MSG = $SUCCESS_MSG = $NOTICE_MSG = '';

if (!function_exists('getErrorMsg')) {
	function getErrorMsg() {
		return $GLOBALS['ERROR_MSG'];
	}
}
if (!function_exists('setErrorMsg')) {
	function setErrorMsg($msg) {
		$GLOBALS['ERROR_MSG'] = ($GLOBALS['ERROR_MSG']=='' ? '' : '<br />').$msg;
	}
}
if (!function_exists('getSuccessMsg')) {
	function getSuccessMsg() {
		return $GLOBALS['SUCCESS_MSG'];
	}
}
if (!function_exists('setSuccessMsg')) {
	function setSuccessMsg($msg) {
		$GLOBALS['SUCCESS_MSG'] = ($GLOBALS['SUCCESS_MSG']=='' ? '' : '<br />').$msg;
	}
}
if (!function_exists('getNoticeMsg')) {
	function getNoticeMsg() {
		return $GLOBALS['NOTICE_MSG'];
	}
}
if (!function_exists('setNoticeMsg')) {
	function setNoticeMsg($msg) {
		$GLOBALS['NOTICE_MSG'] = ($GLOBALS['NOTICE_MSG']=='' ? '' : '<br />').$msg;
	}
}




/* ========================= Parameters section ============================== */

// Get GET, POST, SESSION value and save it (if need)
if (!function_exists('getValueGPS')) {
	function getValueGPS($name, $defa='', $page='') {
	global $_GET, $_POST, $_SESSION;
		$putToSession = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_SESSION[$name.($page!='' ? '_'.$page : '')])) {
			$rez = stripslashes(trim($_SESSION[$name.($page!='' ? '_'.$page : '')]));
			$putToSession = false;
		}
		if ($putToSession)
			setSessionValue($name, $rez, $page);
		return $rez;
	}
}

// Get GET, POST, COOKIE value and save it (if need)
if (!function_exists('getValueGPC')) {
	function getValueGPC($name, $defa='', $page='', $exp=0) {
	global $_GET, $_POST, $_COOKIE;
		$putToCookie = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_COOKIE[$name.($page!='' ? '_'.$page : '')])) {
			$rez = stripslashes(trim($_COOKIE[$name.($page!='' ? '_'.$page : '')]));
			$putToCookie = false;
		}
		if ($putToCookie)
			setcookie($name.($page!='' ? '_'.$page : ''), $rez, $exp, '/');
		return $rez;
	}
}

// Save value into session
if (!function_exists('setSessionValue')) {
	function setSessionValue($name, $value, $page='') {
	global $_SESSION;
		if (!session_id()) session_start();
		$_SESSION[$name.($page!='' ? '_'.$page : '')] = $value;
	}
}

// Save value into session
if (!function_exists('delSessionValue')) {
	function delSessionValue($name, $page='') {
	global $_SESSION;
		if (!session_id()) session_start();
		unset($_SESSION[$name.($page!='' ? '_'.$page : '')]);
	}
}






/* ========================= INI-files utilities section ============================== */

//  Get value by name from .ini-file
if (!function_exists('getIniValue')) {
	function getIniValue($file, $name, $defa='') {
		if (!is_array($file)) {
			if (file_exists($file))
				$file = themerex_fga($file);
			else
				return $defa;
		}
		$name = themerex_strtolower($name);
		$rez = $defa;
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim($file[$i]);
			if (($pos = themerex_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(themerex_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			if (themerex_strtolower(trim(chop($parts[0])))==$name) {
				$rez = trim(chop($parts[1]));
				if (themerex_substr($rez, 0, 1)=='"')
					$rez = themerex_substr($rez,1,themerex_strlen($rez)-2);
				else
					$rez *= 1;
				break;
			}
		}
		return $rez;
	}
}


//  Retrieve all values from .ini-file as assoc array
if (!function_exists('getIniValues')) {
	function getIniValues($file) {
		$rez = array();
		if (!is_array($file)) {
			if (file_exists($file))
				$file = themerex_fga($file);
			else
				return $rez;
		}
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim(chop($file[$i]));
			if (($pos = themerex_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(themerex_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			$key = trim(chop($parts[0]));
			$rez[$key] = trim($parts[1]);
			if (themerex_substr($rez[$key], 0, 1)=='"')
				$rez[$key] = themerex_substr($rez[$key],1,themerex_strlen($rez[$key])-2);
			else
				$rez[$key] *= 1;
		}
		return $rez;
	}
}



/* ========================= Number utilities section ============================== */

// Return nearest number
if (!function_exists('getNearestNumber')) {
	function getNearestNumber($num, $tonearest) {
	   return floor($num/$tonearest)*$tonearest;
	}
}


/* ========================= Array utilities section ============================== */


//  Return list <option value='id'>name</option> as string from two-dim array
if (!function_exists('getOptionsFromArray')) {
	function getOptionsFromArray($arr, $cur) {
		$rezList = "";
		foreach ($arr as $k=>$v) {
			$rezList .= "\n".'<option value="'.$k.'"'.($cur==$k ? ' selected="selected">' : '>').htmlspecialchars($v).'</option>';
		}
		return $rezList;
	}
}

//  Return 'id' by key from two-dim array
if (!function_exists('getArrayIdByKey')) {
	function getArrayIdByKey($curKey, $arr) {
		return (isset($arr[$curKey]) ? $arr[$curKey]['id'] : 0);
	}
}


//  Return key 'name' by key 'id'
if (!function_exists('getArrayNameById')) {
	function getArrayNameById($curId, $arr) {
		$rez = '';
		foreach ($arr as $k=>$v) {
			if ($arr[$k]['id']==$curId) {
				$rez = $arr[$k]['name'];
				break;
			}
		}
		return $rez;
	}
}

// Merge arrays and lists (preserve number indexes)
if (!function_exists('themerex_array_merge')) {
	function themerex_array_merge($a1, $a2) {
		if (is_array($a2) && count($a2)>0) {
			foreach($a2 as $k=>$v) {
				$a1[$k] = $v;
/*
				if (is_array($v))
					$a1[$k] = themerex_array_merge($a1[$k], $v);
				else
					$a1[$k] = $v;
*/
			}
		}
		return $a1;
	}
}



/* ========================= String utilities section ============================== */

define('MULTIBYTE', function_exists('mb_strlen') ? 'UTF-8' : false);

if (!function_exists('themerex_strlen')) {
	function themerex_strlen($text) { return MULTIBYTE ? mb_strlen($text) : strlen($text); }
}
if (!function_exists('themerex_strpos')) {
	function themerex_strpos($text, $char, $from=0) { return MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from); }
}
if (!function_exists('themerex_strrpos')) {
	function themerex_strrpos($text, $char, $from=0) { return MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from); }
}
if (!function_exists('themerex_substr')) {
	function themerex_substr($text, $from, $len=-999999) { 
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = themerex_strlen($text)-$from; 
		}
		return  MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len) ; 
	}
}
if (!function_exists('themerex_strtolower')) {
	function themerex_strtolower($text) { return MULTIBYTE ? mb_strtolower($text) : strtolower($text) ; }
}
if (!function_exists('themerex_strtoupper')) {
	function themerex_strtoupper($text) { return MULTIBYTE ? mb_strtoupper($text) : strtoupper($text) ; }
}
if (!function_exists('themerex_strtoproper')) {
	function themerex_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<themerex_strlen($text); $i++) {
			$ch = themerex_substr($text, $i, 1);
			$rez .= themerex_strpos(' .,:;?!()[]{}+=', $last)!==false ? themerex_strtoupper($ch) : themerex_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}
// Return str, repeated N times
if (!function_exists('themerex_strrepeat')) {
	function themerex_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

// Return part of $str up to $maxlength, appended with $add
if (!function_exists('getShortString')) {
	function getShortString($str, $maxlength, $add='...') {
	//	if ($add && themerex_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 1 || $maxlength >= themerex_strlen($str)) 
			return $str;
		$str = themerex_substr(strip_tags($str), 0, $maxlength - themerex_strlen($add));
		$ch = themerex_substr($str, $maxlength - themerex_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = themerex_strlen($str) - 1; $i > 0; $i--)
				if (themerex_substr($str, $i, 1) == ' ') break;
			$str = trim(themerex_substr($str, 0, $i));
		}
		if (!empty($str) && themerex_strpos(',.:;-', themerex_substr($str, -1))!==false) $str = themerex_substr($str, 0, -1);
		return $str . $add;
	}
}

// Return attrib from tag
if (!function_exists('getTagAttrib')) {
	function getTagAttrib($text, $tag, $attr) {
		$val = '';
		if (($pos_start = themerex_strpos($text, themerex_substr($tag, 0, themerex_strlen($tag)-1)))!==false) {
			$pos_end = themerex_strpos($text, themerex_substr($tag, -1, 1), $pos_start);
			$pos_attr = themerex_strpos($text, $attr.'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += themerex_strlen($attr)+2;
				$pos_quote = themerex_strpos($text, themerex_substr($text, $pos_attr-1, 1), $pos_attr);
				$val = themerex_substr($text, $pos_attr, $pos_quote-$pos_attr);
			}
		}
		return $val;
	}
}

// Set (change) attrib from tag
if (!function_exists('setTagAttrib')) {
	function setTagAttrib($text, $tag, $attr, $val) {
		if (($pos_start = themerex_strpos($text, themerex_substr($tag, 0, themerex_strlen($tag)-1)))!==false) {
			$pos_end = themerex_strpos($text, themerex_substr($tag, -1, 1), $pos_start);
			$pos_attr = themerex_strpos($text, $attr.'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += themerex_strlen($attr)+2;
				$pos_quote = themerex_strpos($text, themerex_substr($text, $pos_attr-1, 1), $pos_attr);
				$text = themerex_substr($text, 0, $pos_attr) . $val . themerex_substr($text, $pos_quote);
			} else {
				$text = themerex_substr($text, 0, $pos_end) . ' ' . $attr . '="' . $val . '"' . themerex_substr($text, $pos_end);
			}
		}
		return $text;
	}
}


// Clear string from spaces, line breaks and tags
if (!function_exists('themerex_strclear')) {
	function themerex_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '') {
				if (themerex_strpos($tags, ',') !== false)
					$tags = explode($tags, ',');
				else
					$tags = array($tags);
			}
		}
		$text = trim(chop($text));
		if (count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.$tag.'>';
				$close = '</'.$tag.'>';
				if (themerex_substr($text, 0, themerex_strlen($open))==$open) $text = themerex_substr($text, themerex_strlen($open));
				if (themerex_substr($text, -themerex_strlen($close))==$close) $text = themerex_substr($text, 0, themerex_strlen($text) - themerex_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}



/* ========================= HTML utilities section ============================== */

// Open wrapper tags and add it to stack
global $THEMEREX_WRAPPERS;
$THEMEREX_WRAPPERS = array();
if (!function_exists('startWrapper')) {
	function startWrapper($tags, $echo=true) {
		global $THEMEREX_WRAPPERS;
		if (!is_array($tags) && !empty($tags)) $tags = array($tags);
		$output = '';
		if (count($tags) > 0) {
			$cnt = 0;
			foreach ($tags as $tag) {
				$THEMEREX_WRAPPERS[] = $tag;
				$output .= "\n".str_repeat("\t", $cnt++).$tag;
			}
		}
		if ($echo) echo ($output);
		return $output;
	}
}

// Close wrapper and delete it from stack
if (!function_exists('stopWrapper')) {
	function stopWrapper($cnt=1, $echo=true) {
		global $THEMEREX_WRAPPERS;
		$output = '';
		$level = count($THEMEREX_WRAPPERS);
		$i = 0;
		while ($i < $cnt) {
			if (count($THEMEREX_WRAPPERS) == 0) break;
			$open_tag = array_pop($THEMEREX_WRAPPERS);
			$tag = explode(' ', $open_tag, 2);
			$close_tag = str_replace('<', '</', $tag[0]).'>';
			$output .= "\n".str_repeat("\t", $level-$i).$close_tag.' <!-- '.$close_tag.' '.$tag[1].' -->';
			$i++;
		}
		if ($echo) echo ($output);
		return $output;
	}
}

// Open all wrappers
if (!function_exists('openAllWrappers')) {
	function openAllWrappers($echo=true) {
		global $THEMEREX_WRAPPERS;
		$output = '';
		for ($i=0; $i<count($THEMEREX_WRAPPERS); $i++) {
			$output .= "\n".str_repeat("\t", $i).$THEMEREX_WRAPPERS[$i];
		}
		if ($echo) echo ($output);
		return $output;
	}
}

// Close all wrappers without stack clear
if (!function_exists('closeAllWrappers')) {
	function closeAllWrappers($echo=true) {
		global $THEMEREX_WRAPPERS;
		$output = '';
		for ($i=count($THEMEREX_WRAPPERS)-1; $i>=0; $i--) {
			$tag = explode(' ', $THEMEREX_WRAPPERS[$i]);
			$output .= "\n".str_repeat("\t", $i).str_replace('<', '</', $tag[0]).'>';
		}
		if ($echo) echo ($output);
		return $output;
	}
}

// Return string for the style attr
if (!function_exists('getStyleString')) {
	function getStyleString($top='',$right='',$bottom='',$left='',$width='',$height='') {
		if (!is_array($top)) {
			$top = compact('top','right','bottom','left','width','height');
		}
		$output = '';
		foreach ($top as $k=>$v) {
			$imp = themerex_substr($v, 0, 1);
			if ($imp == '!') $v = themerex_substr($v, 1);
			if ($v != '') $output .= ($k=='width' ? 'width' : ($k=='height' ? 'height' : 'margin-'.$k)) . ':' . getStyleValue($v) . ($imp=='!' ? ' !important' : '') . ';';
		}
		return $output;
	}
}

// Return value for the style attr
if (!function_exists('getStyleValue')) {
	function getStyleValue($val) {
		if ($val != '') {
			$ed = themerex_substr($val, -1);
			if ('0'<=$ed && $ed<='9') $val .= 'px';
		}
		return $val;
	}
}




/* ========================= Date utilities section ============================== */

// MySQL -> Date
if (!function_exists('SQLToDate')) {
	function SQLToDate($str) {
		return (trim($str)=='' || trim($str)=='0000-00-00' ? '' : trim(themerex_substr($str,8,2).'.'.themerex_substr($str,5,2).'.'.themerex_substr($str,0,4).' '.themerex_substr($str,11)));
	}
}

//  Date -> MySQL
if (!function_exists('DateToSQL')) {
	function DateToSQL($str) {
		if (trim($str)=='') return '';
		$str = strtr(trim($str),'/\-,','....');
		if (trim($str)=='00.00.0000' || trim($str)=='00.00.00') return '';
		$pos = themerex_strpos($str,'.');
		$d=trim(themerex_substr($str,0,$pos));
		$str=themerex_substr($str,$pos+1);
		$pos = themerex_strpos($str,'.');
		$m=trim(themerex_substr($str,0,$pos));
		$y=trim(themerex_substr($str,$pos+1));
		$y=($y<50?$y+2000:($y<1900?$y+1900:$y));
		return ''.$y.'-'.(themerex_strlen($m)<2?'0':'').$m.'-'.(themerex_strlen($d)<2?'0':'').$d;
	}
}


/* ========================= Color manipulation ============================== */
if (!function_exists('Hex2RGB')) {
	function Hex2RGB ($hex) {
		$dec = hexdec(themerex_substr($hex, 0, 1)== '#' ? themerex_substr($hex, 1) : hex);
		return array('r'=> $dec >> 16, 'g'=> ($dec & 0x00FF00) >> 8, 'b'=> $dec & 0x0000FF);
	}
}

if (!function_exists('Hex2HSB')) {
	function Hex2HSB ($hex) {
		return RGB2HSB(Hex2RGB($hex));
	}
}

if (!function_exists('RGB2HSB')) {
	function RGB2HSB ($rgb) {
		$hsb = array();
		$hsb['b'] = max(max($rgb['r'], $rgb['g']), $rgb['b']);
		$hsb['s'] = ($hsb['b'] <= 0) ? 0 : round(100*($hsb['b'] - min(min($rgb['r'], $rgb['g']), $rgb['b'])) / $hsb['b']);
		$hsb['b'] = round(($hsb['b'] /255)*100);
		if (($rgb['r']==$rgb['g']) && ($rgb['g']==$rgb['b'])) $hsb['h'] = 0;
		else if($rgb['r']>=$rgb['g'] && $rgb['g']>=$rgb['b']) $hsb['h'] = 60*($rgb['g']-$rgb['b'])/($rgb['r']-$rgb['b']);
		else if($rgb['g']>=$rgb['r'] && $rgb['r']>=$rgb['b']) $hsb['h'] = 60  + 60*($rgb['g']-$rgb['r'])/($rgb['g']-$rgb['b']);
		else if($rgb['g']>=$rgb['b'] && $rgb['b']>=$rgb['r']) $hsb['h'] = 120 + 60*($rgb['b']-$rgb['r'])/($rgb['g']-$rgb['r']);
		else if($rgb['b']>=$rgb['g'] && $rgb['g']>=$rgb['r']) $hsb['h'] = 180 + 60*($rgb['b']-$rgb['g'])/($rgb['b']-$rgb['r']);
		else if($rgb['b']>=$rgb['r'] && $rgb['r']>=$rgb['g']) $hsb['h'] = 240 + 60*($rgb['r']-$rgb['g'])/($rgb['b']-$rgb['g']);
		else if($rgb['r']>=$rgb['b'] && $rgb['b']>=$rgb['g']) $hsb['h'] = 300 + 60*($rgb['r']-$rgb['b'])/($rgb['r']-$rgb['g']);
		else $hsb['h'] = 0;
		$hsb['h'] = round($hsb['h']);
		return $hsb;
	}
}

if (!function_exists('HSB2RGB')) {
	function HSB2RGB($hsb) {
		$rgb = array();
		$h = round($hsb['h']);
		$s = round($hsb['s']*255/100);
		$v = round($hsb['b']*255/100);
		if ($s == 0) {
			$rgb['r'] = $rgb['g'] = $rgb['b'] = $v;
		} else {
			$t1 = $v;
			$t2 = (255-$s)*$v/255;
			$t3 = ($t1-$t2)*($h%60)/60;
			if ($h==360) $h = 0;
			if ($h<60) { 		$rgb['r']=$t1; $rgb['b']=$t2; $rgb['g']=$t2+$t3; }
			else if ($h<120) {	$rgb['g']=$t1; $rgb['b']=$t2; $rgb['r']=$t1-$t3; }
			else if ($h<180) {	$rgb['g']=$t1; $rgb['r']=$t2; $rgb['b']=$t2+$t3; }
			else if ($h<240) {	$rgb['b']=$t1; $rgb['r']=$t2; $rgb['g']=$t1-$t3; }
			else if ($h<300) {	$rgb['b']=$t1; $rgb['g']=$t2; $rgb['r']=$t2+$t3; }
			else if ($h<360) {	$rgb['r']=$t1; $rgb['g']=$t2; $rgb['b']=$t1-$t3; }
			else {				$rgb['r']=0;   $rgb['g']=0;   $rgb['b']=0; }
		}
		return array('r'=>round($rgb['r']), 'g'=>round($rgb['g']), 'b'=>round($rgb['b']));
	}
}

if (!function_exists('RGB2Hex')) {
	function RGB2Hex($rgb) {
		$hex = array(
			dechex($rgb['r']),
			dechex($rgb['g']),
			dechex($rgb['b'])
		);
		return '#'.(themerex_strlen($hex[0])==1 ? '0' : '').$hex[0].(themerex_strlen($hex[1])==1 ? '0' : '').$hex[1].(themerex_strlen($hex[2])==1 ? '0' : '').$hex[2];
	}
}

if (!function_exists('HSB2Hex')) {
	function HSB2Hex($hsb) {
		return RGB2Hex(HSB2RGB($hsb));
	}
}



/* ========================= Twitter API 1.1 ============================== */

// Return data from twitter for desired mode (user_timeline, home_timeline)
if (!function_exists('getTwitterData')) {
	function getTwitterData($cfg) {
		$data = get_transient("twitter_data_".$cfg['mode']);
		if (!$data) {
			require_once('tmhOAuth/tmhOAuth.php');
			$tmhOAuth = new tmhOAuth(array(
				'consumer_key'    => $cfg['consumer_key'],
				'consumer_secret' => $cfg['consumer_secret'],
				'token'           => $cfg['token'],
				'secret'          => $cfg['secret']
			));
			$code = $tmhOAuth->user_request(array(
				'url' => $tmhOAuth->url(twitter_get_mode_url($cfg['mode']))
			));
			if ($code == 200) {
				$data = json_decode($tmhOAuth->response['response'], true);
				if (isset($data['status'])) {
					$code = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url(twitter_get_mode_url($cfg['oembed'])),
						'params' => array(
							'id' => $data['status']['id_str']
						)
					));
					if ($code == 200)
						$data = json_decode($tmhOAuth->response['response'], true);
				}
				set_transient("twitter_data_".$cfg['mode'], $data, 60*60);
			}
		} else if (!is_array($data) && themerex_substr($data, 0, 2)=='a:') {
			$data = unserialize($data);
		}
		return $data;
	}
}

// Return Twitter followers count
if (!function_exists('getTwitterFollowers')) {
	function getTwitterFollowers($cfg) {
		$data = getTwitterData($cfg); 
		return $data && isset($data[0]['user']['followers_count']) ? $data[0]['user']['followers_count'] : 0;
	}
}

if (!function_exists('twitter_get_mode_url')) {
	function twitter_get_mode_url($mode) {
		$url = '/1.1/statuses/';
		if ($mode == 'user_timeline')
			$url .= $mode;
		else if ($mode == 'home_timeline')
			$url .= $mode;
		return $url;
	}
}

if (!function_exists('twitter_prepare_text')) {
	function twitter_prepare_text($tweet) {
		$text = $tweet['text'];
		if (!empty($tweet['entities']['urls']) && count($tweet['entities']['urls']) > 0) {
			foreach ($tweet['entities']['urls'] as $url) {
				$text = str_replace($url['url'], '<a href="'.$url['expanded_url'].'" target="_blank">' . $url['display_url'] . '</a>', $text);
			}
		}
		if (!empty($tweet['entities']['media']) && count($tweet['entities']['media']) > 0) {
			foreach ($tweet['entities']['media'] as $url) {
				$text = str_replace($url['url'], '<a href="'.$url['expanded_url'].'" target="_blank">' . $url['display_url'] . '</a>', $text);
			}
		}
		return $text;
	}
}

if (!function_exists('twitter_followers')) {
	function twitter_followers($account) {
		$tw = get_transient("twitterfollowers");
		if ($tw !== false) return $tw;
		$tw = '?';
		$url = 'https://twitter.com/users/show/'.$account;
		$headers = get_headers($url);
		if (themerex_strpos($headers[0], '200')) {
			$xml = themerex_fgc($url);
			preg_match('/followers_count>(.*)</', $xml, $match);
			if ($match[1] !=0 ) {
				$tw = $match[1];
				set_transient("twitterfollowers", $tw, 60*60);
			}
		}
		return $tw;
	}
}

if (!function_exists('facebook_likes')) {
	function facebook_likes($account) {
		$fb = get_transient("facebooklikes");
		if ($fb !== false) return $fb;
		$fb = '?';
		$url = 'http://graph.facebook.com/'.$account;
		$headers = get_headers($url);
		if (themerex_strpos($headers[0], '200')) {
			$json = themerex_fgc($url);
			$rez = json_decode($json, true);
			if (isset($rez['likes']) ) {
				$fb = $rez['likes'];
				set_transient("facebooklikes", $fb, 60*60);
			}
		}
		return $fb;
	}
}

if (!function_exists('feedburner_counter')) {
	function feedburner_counter($account) {
		$rss = get_transient("feedburnercounter");
		if ($rss !== false) return $rss;
		$rss = '?';
		$url = 'http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri='.$account;
		$headers = get_headers($url);
		if (themerex_strpos($headers[0], '200')) {
			$xml = themerex_fgc($url);
			preg_match('/circulation="(\d+)"/', $xml, $match);
			if ($match[1] != 0) {
				$rss = $match[1];
				set_transient("feedburnercounter", $rss, 60*60);
			}
		}
		return $rss;
	}
}



/* ========================= Other section ============================== */

// Return video player URL
if (!function_exists('getVideoPlayerURL')) {
	function getVideoPlayerURL($url, $autoplay=false) {
		$url = str_replace(
			array(
				'http://youtu.be/',
				'http://www.youtu.be/',
				'http://youtube.com/watch?v=',
				'http://www.youtube.com/watch?v=',
				'http://youtube.com/watch/?v=',
				'http://www.youtube.com/watch/?v=',
				'http://vimeo.com/',
				'http://www.vimeo.com/',
				'https://youtu.be/',
				'https://www.youtu.be/',
				'https://youtube.com/watch?v=',
				'https://www.youtube.com/watch?v=',
				'https://youtube.com/watch/?v=',
				'https://www.youtube.com/watch/?v=',
				'https://vimeo.com/',
				'https://www.vimeo.com/'
			),
			array(
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://player.vimeo.com/video/',
				'http://player.vimeo.com/video/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://player.vimeo.com/video/',
				'https://player.vimeo.com/video/'
			),
			trim(chop($url)));
		if ($autoplay && $url!='') {
			if (themerex_strpos($url, 'autoplay')===false) {
				$url .= (themerex_strpos($url, '?')===false ? '?' : '&') . 'autoplay=1';
			}
		}
		return $url;
	}
}

// Return Youtube image from video url
if (!function_exists('getVideoCoverImage')) {
	function getVideoCoverImage($url) {
		$image = '';
		if (isVideoYoutube($url)) {
			$parts = parse_url($url);
			if (!empty($parts['query'])) {
				parse_str( $parts['query'], $args );
				if (!empty($args['v']))
					$image = $args['v'];
			} else if (!empty($parts['path'])) {
				$args = explode('/', $parts['path']);
				$image = array_pop($args);
			}
			if (!empty($image)) $image = 'http://i1.ytimg.com/vi/'.$image.'/maxresdefault.jpg';
		}
		return $image;
	}
}

if (!function_exists('isVideoYoutube')) {
	function isVideoYoutube($url) {
		return themerex_strpos($url, 'youtu.be')!==false || themerex_strpos($url, 'youtube.com')!==false;
	}
}

if (!function_exists('isVideoVimeo')) {
	function isVideoVimeo($url) {
		return themerex_strpos($url, 'vimeo.com')!==false;
	}
}


// Return list folders
if (!function_exists('getListFolders')) {
	function getListFolders($folder, $only_names=true) {
		if (themerex_substr($folder, 0, 1)!='/')
			$folder = '/' . $folder;
		$list = array();
		if (is_dir(	get_stylesheet_directory() . $folder)) {
			$dir = get_stylesheet_directory() . $folder;
			$url = get_stylesheet_directory_uri() . $folder;
		} else {
			$dir = get_template_directory() . $folder;
			$url = get_template_directory_uri() . $folder;
		}
		if ( is_dir($dir) ) {
			$hdir = @opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					//$pi = pathinfo( $dir . '/' . $file );
					if ( substr($file, 0, 1) == '.' || !is_dir( $dir . '/' . $file ) )
						continue;
					$key = $file;
					$list[$key] = $only_names ? themerex_strtoproper($key) : $url . '/' . $file;
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}

// Return list files in folder
if (!function_exists('getListFiles')) {
	function getListFiles($folder, $ext='', $only_names=false) {
		if (themerex_substr($folder, 0, 1)!='/')
			$folder = '/' . $folder;
		$list = array();
		if (is_dir(	get_stylesheet_directory() . $folder)) {
			$dir = get_stylesheet_directory() . $folder;
			$url = get_stylesheet_directory_uri() . $folder;
		} else {
			$dir = get_template_directory() . $folder;
			$url = get_template_directory_uri() . $folder;
		}
		if ( is_dir($dir) ) {
			$hdir = @opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( $dir . '/' . $file );
					if ( substr($file, 0, 1) == '.' || is_dir( $dir . '/' . $file ) || (!empty($ext) && $pi['extension'] != $ext) )
						continue;
					$key = themerex_substr($file, 0, themerex_strrpos($file, '.'));
					if (themerex_substr($key, -4)=='.min') $key = themerex_substr($file, 0, themerex_strrpos($key, '.'));
					$list[$key] = $only_names ? themerex_strtoproper(str_replace('_', ' ', $key)) : $url . '/' . $file;
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}

// Return array with classes from css-file
if (!function_exists('parseIconsClasses')) {
	function parseIconsClasses($css) {
		$rez = array();
		if (!file_exists($css)) return $rez;
		$file = themerex_fga($css);
		foreach ($file as $row) {
			if (themerex_substr($row, 0, 1)!='.') continue;
			$name = '';
			for ($i=1; $i<themerex_strlen($row); $i++) {
				$ch = themerex_substr($row, $i, 1);
				if (in_array($ch, array(':', '{', '.', ' '))) break;
				$name .= $ch;
			}
			if ($name!='') $rez[] = $name;
		}
		return $rez;
	}
}

// Return accent color from css-file
if (!function_exists('getSelectorPropertyFromCSS')) {
	function getSelectorPropertyFromCSS($css, $selector, $prop) {
		$rez = '';
		if (!file_exists($css)) return $rez;
		$file = themerex_fga($css);
		foreach ($file as $row) {
			if (($pos = themerex_strpos($row, $selector))===false) continue;
			if (($pos2 = themerex_strpos($row, $prop.':', $pos))!==false && ($pos3 = themerex_strpos($row, ';', $pos2))!==false && $pos2 < $pos3) {
				$rez = trim(chop(themerex_substr($row, $pos2+themerex_strlen($prop)+1, $pos3-$pos2-themerex_strlen($prop)-1)));
				break;
			}
		}
		return $rez;
	}
}

//  Cache disable
if (!function_exists('NoCache')) {
	function NoCache() {
		Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		Header("Cache-Control: no-cache, must-revalidate");
		Header("Pragma: no-cache");
		Header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	}
}
?>