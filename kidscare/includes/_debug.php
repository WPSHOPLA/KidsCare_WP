<?php
//=====================================
//==  DEBUG utilities
//=====================================

define('DEBUG_FILE_NAME', 'debug.log');

$MAX_DUMP_LEVEL = -1;

function setMaxDumpLevel($lvl) {
	global $MAX_DUMP_LEVEL;
	$MAX_DUMP_LEVEL = $lvl;
}

if (!function_exists('ds')) { function ds(&$var) { 	if (is_user_logged_in()) dumpScreen($var); } }
if (!function_exists('df')) { function df(&$var) { 	if (is_user_logged_in()) dumpFile($var); } }
if (!function_exists('dr')) { function dr(&$var) { 	if (is_user_logged_in()) dumpVar($var); } }
if (!function_exists('dm')) { function dm($var) {	if (is_user_logged_in()) dieMsg($var); } }
if (!function_exists('tm')) { function tm($var) {	if (is_user_logged_in()) traceMsg($var); } }
if (!function_exists('ss')) { function ss($lvl) { 	if (is_user_logged_in()) stackScreen($lvl); } }
if (!function_exists('sf')) { function sf($lvl) { 	if (is_user_logged_in()) stackFile($lvl); } }

function dieMsg($msg) {
	traceMsg($msg);
	die($msg);
}

function traceMsg($msg) {
	themerex_fpc(get_template_directory().'/'.DEBUG_FILE_NAME, date('d.m.Y H:i:s')." $msg\n", FILE_APPEND);
}

function stackScreen($level=1) {
	$s = debug_backtrace();
	global $MAX_DUMP_LEVEL;
	$oldLevel = $MAX_DUMP_LEVEL;
	$MAX_DUMP_LEVEL = $level;
	dumpScreen($s);
	$MAX_DUMP_LEVEL = $oldLevel;
}

function stackFile($level=1) {
	$s = debug_backtrace();
	global $MAX_DUMP_LEVEL;
	$oldLevel = $MAX_DUMP_LEVEL;
	$MAX_DUMP_LEVEL = $level;
	dumpFile($s);
	$MAX_DUMP_LEVEL = $oldLevel;
}


function dumpVar(&$var) {
	return textDump($var);
}

function dumpScreen(&$var) {
	if ((is_array($var) || is_object($var)) && count($var))
		echo "<pre>\n".nl2br(htmlspecialchars(textDump($var)))."</pre>\n";
	else
		echo "<tt>".nl2br(htmlspecialchars(textDump($var)))."</tt>\n";
}

function dumpFile(&$var) {
	traceMsg("\n\n".textDump($var));
}

function textDump(&$var, $level=0)  {
	global $MAX_DUMP_LEVEL;
	if (is_array($var)) $type="Array[".count($var)."]";
	else if (is_object($var)) $type="Object";
	else $type="";

	if ($type) {
		$rez = "$type\n";
		if ($MAX_DUMP_LEVEL<0 || $level < $MAX_DUMP_LEVEL) {
			for (Reset($var), $level++; list($k, $v)=each($var); ) {
				if (is_array($v) && $k==="GLOBALS") continue;
				for ($i=0; $i<$level*3; $i++) $rez .= " ";
				$rez .= $k.' => '. textDump($v, $level);
			}
		}
	} else if (is_bool($var))
		$rez = ($var ? 'true' : 'false')."\n";
	else if (is_long($var) || is_float($var) || intval($var) != 0)
		$rez = $var."\n";
	else
		$rez = '"'.$var.'"'."\n";
	return $rez;
}

function dumpWP_is($query=null) {
global $wp_query;
if (!$query) $query = $wp_query;
echo "<tt>"
	."<br>admin=".is_admin()
	."<br>main_query=".is_main_query()."  query=".$query->is_main_query()
	."<br>query->is_posts_page=".$query->is_posts_page
	."<br>home=".is_home()."  query=".$query->is_home()
	."<br>fp=".is_front_page()."  query=".$query->is_front_page()
	."<br>search=".is_search()."  query=".$query->is_search()
	."<br>category=".is_category()."  query=".$query->is_category()
	."<br>tag=".is_tag()."  query=".$query->is_tag()
	."<br>archive=".is_archive()."  query=".$query->is_archive()
	."<br>day=".is_day()."  query=".$query->is_day()
	."<br>month=".is_month()."  query=".$query->is_month()
	."<br>year=".is_year()."  query=".$query->is_year()
	."<br>author=".is_author()."  query=".$query->is_author()
	."<br>page=".is_page()."  query=".$query->is_page()
	."<br>single=".is_single()."  query=".$query->is_single()
	."<br>singular=".is_singular()."  query=".$query->is_singular()
	."<br>attachment=".is_attachment()."  query=".$query->is_attachment()
	."<br>WooCommerce=".is_woocommerce_page()
	."<br><br />"
	."</tt>";
}

?>