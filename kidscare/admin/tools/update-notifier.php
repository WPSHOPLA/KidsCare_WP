<?php
/**************************************************************
 *                                                            *
 *   Provides a notification to the user everytime            *
 *   your WordPress theme is updated                          *
 *                                                            *
 *   Author: Joao Araujo                                      *
 *   Profile: http://themeforest.net/user/unisphere           *
 *   Follow me: http://twitter.com/unispheredesign            *
 *                                                            *
 **************************************************************/
 
 

// Constants for the theme name, folder and remote XML url
define( 'NOTIFIER_THEME_NAME', 'KidsCare' ); // The theme name
define( 'NOTIFIER_THEME_FOLDER_NAME', 'kidscare' ); // The theme folder name
define( 'NOTIFIER_XML_FILE', 'http://themerex.net/!updates/updates_kidscare.xml' ); // The remote notifier XML file containing the latest version of the theme and changelog
define( 'NOTIFIER_CACHE_INTERVAL', 21600 ); // The time interval for the remote XML cache in the database (21600 seconds = 6 hours)



// Adds an update notification to the WordPress Dashboard menu
//add_action('admin_menu', 'update_notifier_menu');  
function update_notifier_menu() {  
	if (function_exists('simplexml_load_string')) { // Stop if simplexml_load_string function isn't available
	    $xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
		$theme_data = wp_get_theme();
		if( (string) $xml->latest > (string) $theme_data->get('Version') ) { // Compare current theme version with the remote XML version
			//add_dashboard_page( NOTIFIER_THEME_NAME . ' ' . __('Theme Updates', 'themerex'), NOTIFIER_THEME_NAME . ' <span class="update-plugins count-1"><span class="update-count">'.__('New Update', 'themerex').'</span></span>', 'administrator', 'theme-update-notifier', 'update_notifier');
		}
	}	
}



// Adds an update notification to the WordPress 3.1+ Admin Bar
add_action( 'admin_bar_menu', 'update_notifier_bar_menu', 1000 );
function update_notifier_bar_menu() {
	if (function_exists('simplexml_load_string')) { // Stop if simplexml_load_string funtion isn't available
		global $wp_admin_bar, $wpdb;
	
		if ( !is_super_admin() || !is_admin_bar_showing() ) // Don't display notification in admin bar if it's disabled or the current user isn't an administrator
		return;
		
		$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
		$theme_data = wp_get_theme();
		if( (string) $xml->latest > (string) $theme_data->get('Version') ) { // Compare current theme version with the remote XML version
			$wp_admin_bar->add_menu( array( 'id' => 'update_notifier', 'title' => '<span>'.NOTIFIER_THEME_NAME.' <span id="ab-updates">'.sprintf(__('New Update: %s', 'themerex'), $xml->latest).'</span></span>', 'href' => get_admin_url() . 'index.php?page=theme-update-notifier' ) );
		}
	}
}



// The notifier page
function update_notifier() { 
	$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
	$theme_data = wp_get_theme();
?>
	<style>
		.update-nag { display: none; }
		#theme_update_instructions {max-width: 670px;}
		.theme_update_changelog_title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
		.theme_update_changelog ul { list-style-type:disc; margin-left:20px;}
	</style>

	<div class="wrap">
	
		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo NOTIFIER_THEME_NAME ?> <?php _e('Theme Updates', 'themerex'); ?></h2>
	    <div id="message" class="updated below-h2"><p><strong><?php echo sprintf(__('There is a new version of the %s theme available.', 'themerex'), NOTIFIER_THEME_NAME); ?></strong> <?php echo sprintf(__('You have version %s installed. Update to version %s.', 'themerex'), $theme_data->get('Version'), $xml->latest); ?></p></div>

		<div><img alt="" style="margin: 0 0 20px 0; border: 1px solid #ddd;" src="<?php echo themerex_get_file_url('/screenshot.png'); ?>" /></div>
		
		<div id="theme_update_instructions">
		    <h3><?php _e('Update Download and Instructions', 'themerex'); ?></h3>
		    <p><?php echo sprintf(__('<strong>Please note:</strong> make a backup of the Theme inside your WordPress installation folder <strong>/wp-content/themes/%s/</strong>', 'themerex'), NOTIFIER_THEME_FOLDER_NAME); ?></p>
		    <p><?php _e('To update the Theme, login to <a href="http://www.themeforest.net/">ThemeForest</a>, head over to your <strong>downloads</strong> section and re-download the theme like you did when you bought it.' ,'themerex'); ?></p>
		    <p><?php echo sprintf(__("Extract the zip's contents, look for the extracted theme folder, and after you have all the new files upload them using FTP to the <strong>/wp-content/themes/%s/</strong> folder overwriting the old ones (this is why it's important to backup any changes you've made to the theme files).", 'themerex'), NOTIFIER_THEME_FOLDER_NAME); ?></p>
		    <p><?php _e("If you didn't make any changes to the theme files, you are free to overwrite them with the new ones without the risk of losing theme settings, pages, posts, etc, and backwards compatibility is guaranteed.", 'themerex'); ?></p>
		</div>
	    
	    <h3 class="theme_update_changelog_title"><?php _e('Changelog', 'themerex'); ?></h3>
	    <div class="theme_update_changelog"><?php echo balanceTags($xml->changelog); ?></div>

	</div>
    
<?php } 



// Get the remote XML file contents and return its data (Version and Changelog)
// Uses the cached version if available and inside the time interval defined
function get_latest_theme_version($interval) {
	$notifier_file_url = NOTIFIER_XML_FILE;	
	$db_cache_field = 'notifier-cache';
	$db_cache_field_last_updated = 'notifier-cache-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// check the cache
	if ( !$last ||  $now - $last > $interval ) {
		// cache doesn't exist, or is old, so refresh it
		$fn = array(
			'init'  => join('_', array('curl', 'init')),
			'opt'   => join('_', array('curl', 'setopt')),
			'exec'  => join('_', array('curl', 'exec')),
			'close' => join('_', array('curl', 'close'))
		);
		if( function_exists($fn['init']) ) {
			$ch = $fn['init']($notifier_file_url);
			$fn['opt']($ch, CURLOPT_RETURNTRANSFER, true);
			$fn['opt']($ch, CURLOPT_HEADER, 0);
			$fn['opt']($ch, CURLOPT_TIMEOUT, 10);
			$cache = $fn['exec']($ch);
			$fn['close']($ch);
		} else {
			$cache = themerex_fgc($notifier_file_url);
		}
		update_option( $db_cache_field_last_updated, time() );
		if ($cache) {			
			update_option( $db_cache_field, $cache );
			$notifier_data = $cache;
		}  else
			$notifier_data = get_option( $db_cache_field );
	} else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}
	
	// Let's see if the $xml data was returned as we expected it to.
	// If it didn't, use the default 1.0 as the latest version so that we don't have problems when the remote server hosting the XML file is down
	if( strpos((string)$notifier_data, '<notifier>') === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}
	
	// Load the remote XML data into a variable and return it
	$xml = simplexml_load_string($notifier_data); 
	
	return $xml;
}

?>