<script type="text/javascript">
// Javascript String constants for translation
THEMEREX_MESSAGE_BOOKMARK_ADD   = "<?php echo addslashes(__('Add the bookmark', 'themerex')); ?>";
THEMEREX_MESSAGE_BOOKMARK_ADDED = "<?php echo addslashes(__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'themerex')); ?>";
THEMEREX_MESSAGE_BOOKMARK_TITLE = "<?php echo addslashes(__('Enter bookmark title', 'themerex')); ?>";
THEMEREX_MESSAGE_BOOKMARK_EXISTS= "<?php echo addslashes(__('Current page already exists in the bookmarks list', 'themerex')); ?>";
THEMEREX_MESSAGE_SEARCH_ERROR = "<?php echo addslashes(__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'themerex')); ?>";
THEMEREX_MESSAGE_EMAIL_CONFIRM= "<?php echo addslashes(__('On the e-mail address <b>%s</b> we sent a confirmation email.<br>Please, open it and click on the link.', 'themerex')); ?>";
THEMEREX_MESSAGE_EMAIL_ADDED  = "<?php echo addslashes(__('Your address <b>%s</b> has been successfully added to the subscription list', 'themerex')); ?>";
THEMEREX_REVIEWS_VOTE		  = "<?php echo addslashes(__('Thanks for your vote! New average rating is:', 'themerex')); ?>";
THEMEREX_REVIEWS_ERROR		  = "<?php echo addslashes(__('Error saving your vote! Please, try again later.', 'themerex')); ?>";
THEMEREX_MAGNIFIC_LOADING     = "<?php echo addslashes(__('Loading image #%curr% ...', 'themerex')); ?>";
THEMEREX_MAGNIFIC_ERROR       = "<?php echo addslashes(__('<a href="%url%">The image #%curr%</a> could not be loaded.', 'themerex')); ?>";
THEMEREX_MESSAGE_ERROR_LIKE   = "<?php echo addslashes(__('Error saving your like! Please, try again later.', 'themerex')); ?>";
THEMEREX_GLOBAL_ERROR_TEXT	  = "<?php echo addslashes(__('Global error text', 'themerex')); ?>";
THEMEREX_NAME_EMPTY			  = "<?php echo addslashes(__('The name can\'t be empty', 'themerex')); ?>";
THEMEREX_NAME_LONG 			  = "<?php echo addslashes(__('Too long name', 'themerex')); ?>";
THEMEREX_EMAIL_EMPTY 		  = "<?php echo addslashes(__('Too short (or empty) email address', 'themerex')); ?>";
THEMEREX_EMAIL_LONG			  = "<?php echo addslashes(__('Too long email address', 'themerex')); ?>";
THEMEREX_EMAIL_NOT_VALID 	  = "<?php echo addslashes(__('Invalid email address', 'themerex')); ?>";
THEMEREX_SUBJECT_EMPTY		  = "<?php echo addslashes(__('The subject can\'t be empty', 'themerex')); ?>";
THEMEREX_SUBJECT_LONG 		  = "<?php echo addslashes(__('Too long subject', 'themerex')); ?>";
THEMEREX_MESSAGE_EMPTY 		  = "<?php echo addslashes(__('The message text can\'t be empty', 'themerex')); ?>";
THEMEREX_MESSAGE_LONG 		  = "<?php echo addslashes(__('Too long message text', 'themerex')); ?>";
THEMEREX_SEND_COMPLETE 		  = "<?php echo addslashes(__("Send message complete!", 'themerex')); ?>";
THEMEREX_SEND_ERROR 		  = "<?php echo addslashes(__('Transmit failed!', 'themerex')); ?>";
THEMEREX_LOGIN_EMPTY		  = "<?php echo addslashes(__('The Login field can\'t be empty', 'themerex')); ?>";
THEMEREX_LOGIN_LONG			  = "<?php echo addslashes(__('Too long login field', 'themerex')); ?>";
THEMEREX_PASSWORD_EMPTY		  = "<?php echo addslashes(__('The password can\'t be empty and shorter then 5 characters', 'themerex')); ?>";
THEMEREX_PASSWORD_LONG		  = "<?php echo addslashes(__('Too long password', 'themerex')); ?>";
THEMEREX_PASSWORD_NOT_EQUAL   = "<?php echo addslashes(__('The passwords in both fields are not equal', 'themerex')); ?>";
THEMEREX_REGISTRATION_SUCCESS = "<?php echo addslashes(__('Registration success! Please log in!', 'themerex')); ?>";
THEMEREX_REGISTRATION_FAILED  = "<?php echo addslashes(__('Registration failed!', 'themerex')); ?>";
THEMEREX_REGISTRATION_AUTHOR  = "<?php echo addslashes(__('Your account is waiting for the site admin moderation!', 'themerex')); ?>";
THEMEREX_GEOCODE_ERROR 		  = "<?php echo addslashes(__('Geocode was not successful for the following reason:', 'wspace')); ?>";
THEMEREX_GOOGLE_MAP_NOT_AVAIL = "<?php echo addslashes(__('Google map API not available!', 'themerex')); ?>";

<?php if (get_theme_option("allow_editor")=='yes') { ?>
THEMEREX_SAVE_SUCCESS		= "<?php echo addslashes(__("Post content saved!", 'themerex')); ?>";
THEMEREX_SAVE_ERROR			= "<?php echo addslashes(__("Error saving post data!", 'themerex')); ?>";
THEMEREX_DELETE_POST_MESSAGE= "<?php echo addslashes(__("You really want to delete the current post?", 'themerex')); ?>";
THEMEREX_DELETE_POST		= "<?php echo addslashes(__("Delete post", 'themerex')); ?>";
THEMEREX_DELETE_SUCCESS		= "<?php echo addslashes(__("Post deleted!", 'themerex')); ?>";
THEMEREX_DELETE_ERROR		= "<?php echo addslashes(__("Error deleting post!", 'themerex')); ?>";
<?php } ?>

// AJAX parameters
<?php global $THEMEREX_ajax_url, $THEMEREX_ajax_nonce; ?>
var THEMEREX_ajax_url = "<?php echo esc_url($THEMEREX_ajax_url); ?>";
var THEMEREX_ajax_nonce = "<?php echo ($THEMEREX_ajax_nonce); ?>";

// Site base url
var THEMEREX_site_url = "<?php echo get_site_url(); ?>";

// Theme base font
var THEMEREX_theme_font = "<?php echo get_custom_option('typography_custom')=='yes' ? get_custom_option('typography_p_font') : ''; ?>";

// Theme skin
var THEMEREX_theme_skin = "<?php echo get_custom_option('theme_skin'); ?>";
var THEMEREX_theme_skin_bg = "<?php echo apply_filters('theme_skin_get_theme_bgcolor', '#ffffff'); ?>";

// Slider height
var THEMEREX_slider_height = "<?php echo max(100, get_custom_option('slider_height')); ?>";

// Sound Manager
var THEMEREX_sound_enable    = <?php echo get_theme_option('sound_enable')=='yes' ? 'true' : 'false'; ?>;
var THEMEREX_sound_folder    = '<?php echo (is_dir(get_stylesheet_directory().'/js/sounds/lib/swf/') ? get_stylesheet_directory_uri() : get_template_directory_uri()).'/js/sounds/lib/swf/'; ?>';
var THEMEREX_sound_mainmenu  = '<?php echo get_custom_option('sound_mainmenu'); ?>';
var THEMEREX_sound_othermenu = '<?php echo get_custom_option('sound_othermenu'); ?>';
var THEMEREX_sound_buttons   = '<?php echo get_custom_option('sound_buttons'); ?>';
var THEMEREX_sound_links     = '<?php echo get_custom_option('sound_links'); ?>';
var THEMEREX_sound_state     = { 
	all: THEMEREX_sound_enable ? 1 : 0, 
	mainmenu:	<?php echo get_custom_option('sound_mainmenu_enable')=='yes' ? '1' : '0'; ?>,
	othermenu:	<?php echo get_custom_option('sound_othermenu_enable')=='yes' ? '1' : '0'; ?>,
	buttons:	<?php echo get_custom_option('sound_buttons_enable')=='yes' ? '1' : '0'; ?>,
	links: 		<?php echo get_custom_option('sound_links_enable')=='yes' ? '1' : '0'; ?>
};

// System message
var THEMEREX_systemMessage = {<?php $msg = themerex_get_message(true); echo 'message:"'.addslashes($msg['message']).'", status: "'.addslashes($msg['status']).'", header: "'.addslashes($msg['header']).'"'; ?>};

// User logged in
var THEMEREX_userLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;

// Show table of content for the current page
var THEMEREX_menu_toc = '<?php echo get_custom_option('menu_toc'); ?>';
var THEMEREX_menu_toc_home = THEMEREX_menu_toc!='no' && <?php echo get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false'; ?>;
var THEMEREX_menu_toc_top = THEMEREX_menu_toc!='no' && <?php echo get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false'; ?>;

// Fix main menu
var THEMEREX_menuFixed = <?php echo get_theme_option('menu_position')=='fixed' ? 'true' : 'false'; ?>;

// Use responsive version for main menu
var THEMEREX_menuResponsive = <?php echo max(0, (int) get_theme_option('menu_responsive')); ?>;
var THEMEREX_responsive_menu_click = <?php echo get_theme_option('menu_responsive_open')=='click' ? 'true' : 'false'; ?>;

// Right panel demo timer
var THEMEREX_demo_time = <?php echo get_theme_option('show_right_panel')=='yes' ? max(0, (int) get_theme_option('right_panel_demo')) : 0; ?>;

// Video and Audio tag wrapper
var THEMEREX_useMediaElement = <?php echo get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false'; ?>;

// Use AJAX search
var THEMEREX_useAJAXSearch = <?php echo get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false'; ?>;
var THEMEREX_AJAXSearch_min_length = <?php echo min(3, get_theme_option('ajax_search_min_length')); ?>;
var THEMEREX_AJAXSearch_delay = <?php echo min(200, max(1000, get_theme_option('ajax_search_delay'))); ?>;

// Popup windows engine
var THEMEREX_popupEngine  = '<?php echo get_theme_option('popup_engine'); ?>';
var THEMEREX_popupGallery = <?php echo get_theme_option('popup_gallery')=='yes' ? 'true' : 'false'; ?>;

// E-mail mask
THEMEREX_EMAIL_MASK = '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';

// Messages max length
var THEMEREX_msg_maxlength_contacts = <?php echo get_theme_option('message_maxlength_contacts'); ?>;
var THEMEREX_msg_maxlength_comments = <?php echo get_theme_option('message_maxlength_comments'); ?>;

// Remember visitors settings
var THEMEREX_remember_visitors_settings = <?php echo get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false'; ?>;


<?php do_action('theme_skin_add_scripts_inline'); ?>

jQuery(document).ready(function() {
	<?php
	// Reject old browsers
	global $THEMEREX_jreject;
	if ($THEMEREX_jreject) {
	?>
		jQuery.reject({
			reject : {
				all: false, // Nothing blocked
				msie5: true, msie6: true, msie7: true, msie8: true // Covers MSIE 5-8
				/*
				 * Possibilities are endless...
				 *
				 * // MSIE Flags (Global, 5-8)
				 * msie, msie5, msie6, msie7, msie8,
				 * // Firefox Flags (Global, 1-3)
				 * firefox, firefox1, firefox2, firefox3,
				 * // Konqueror Flags (Global, 1-3)
				 * konqueror, konqueror1, konqueror2, konqueror3,
				 * // Chrome Flags (Global, 1-4)
				 * chrome, chrome1, chrome2, chrome3, chrome4,
				 * // Safari Flags (Global, 1-4)
				 * safari, safari2, safari3, safari4,
				 * // Opera Flags (Global, 7-10)
				 * opera, opera7, opera8, opera9, opera10,
				 * // Rendering Engines (Gecko, Webkit, Trident, KHTML, Presto)
				 * gecko, webkit, trident, khtml, presto,
				 * // Operating Systems (Win, Mac, Linux, Solaris, iPhone)
				 * win, mac, linux, solaris, iphone,
				 * unknown // Unknown covers everything else
				 */
			},
			imagePath: "<?php echo (is_dir(get_stylesheet_directory().'/js/jreject/images/') ? get_stylesheet_directory_uri() : get_template_directory_uri()).'/js/jreject/images/'; ?>",
			header: "<?php echo addslashes(__('Your browser is out of date', 'themerex')); ?>", // Header Text
			paragraph1: "<?php echo addslashes(__('You are currently using an unsupported browser', 'themerex')); ?>", // Paragraph 1
			paragraph2: "<?php echo addslashes(__('Please install one of the many optional browsers below to proceed', 'themerex')); ?>",
			closeMessage: "<?php echo addslashes(__('Close this window at your own demise!', 'themerex')); ?>" // Message below close window link
		});
	<?php 
	} 
	?>
});
</script>
