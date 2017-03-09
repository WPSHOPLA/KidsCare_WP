<?php
/**
 * Theme functions and definitions
 */


/* ========================= Theme setup section ============================== */

add_action( 'after_setup_theme', 'themerex_theme_setup' );
if ( !function_exists( 'themerex_theme_setup' ) ) {
	function themerex_theme_setup() {

		// Set the content width based on the theme's design and stylesheet.
		if ( ! isset( $content_width ) )
			$content_width = 1150; /* pixels */
		
		// Theme image dimensions
		// 16:9
		add_image_size( 'fullpost', 1150, 647, true );
		add_image_size( 'excerpt',   714, 402, true );
		add_image_size( 'classic3',  400, 225, true );
		add_image_size( 'classic4',  250, 141, true );
		// Non 16:9
		add_image_size( 'portfolio3',383, 245, true );
		add_image_size( 'portfolio4',287, 287, true );
		add_image_size( 'widgets',    75,  75, true );
		
		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
		
		// Add user menu
		add_theme_support('nav-menus');
		if ( function_exists( 'register_nav_menus' ) ) {
			register_nav_menus(
				array(
					'mainmenu' => 'Main Menu',
					'usermenu' => 'User Menu',
					'sidemenu' => 'Side Menu',
					'panelmenu'=> 'Panel Menu'
				)
			);
		}
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style('css/editor-style.css');	
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'themerex', (is_dir(get_stylesheet_directory() . '/languages') ? get_stylesheet_directory() : get_template_directory()) . '/languages' );

		// ---------------------------------- Actions and filters ------------------------------
		// Register theme widgets
		add_action( 'widgets_init', 'themerex_widgets_init' );
		
		// Frontend actions:

		// User login
		add_filter('authenticate', 'themerex_allow_email_login', 20, 3);
		// Enqueue scripts and styles
		add_action( 'wp_enqueue_scripts', 'themerex_scripts' );
		// Compose header scripts in one file
		add_action('wp_print_scripts', 'themerex_compose_scripts', 20);
		// Compose footer scripts in one file
		add_action('wp_print_footer_scripts', 'themerex_footer_scripts', 20);
		// Compose styles in one file
		add_action('wp_print_styles', 'themerex_compose_styles', 20);
		// PRE QUERY - posts per page selector
		add_action( 'pre_get_posts', 'themerex_posts_per_page_selector' );
		// Filter categories list - exclude unwanted cats from widget output
		add_action( 'widget_categories_args', 'themerex_categories_args_filter' );
		add_action( 'widget_categories_dropdown_args', 'themerex_categories_args_filter' );
		add_action( 'widget_posts_args', 'themerex_posts_args_filter' );
		// AJAX: Save e-mail in subscribe list
		add_action('wp_ajax_emailer_submit', 'themerex_callback_emailer_submit');
		add_action('wp_ajax_nopriv_emailer_submit', 'themerex_callback_emailer_submit');
		// AJAX: Confirm e-mail in subscribe list
		add_action('wp_ajax_emailer_confirm', 'themerex_callback_emailer_confirm');
		add_action('wp_ajax_nopriv_emailer_confirm', 'themerex_callback_emailer_confirm');
		// AJAX: Get subscribers list if group changed
		add_action('wp_ajax_emailer_group_getlist', 'themerex_callback_emailer_group_getlist');
		add_action('wp_ajax_nopriv_emailer_group_getlist', 'themerex_callback_emailer_group_getlist');
		// AJAX: Set post likes/views count
		add_action('wp_ajax_post_counter', 'themerex_callback_post_counter');
		add_action('wp_ajax_nopriv_post_counter', 'themerex_callback_post_counter');
		// AJAX: Get attachment url
		add_action('wp_ajax_get_attachment_url', 'themerex_callback_get_attachment_url');
		add_action('wp_ajax_nopriv_get_attachment_url', 'themerex_callback_get_attachment_url');
		// AJAX: Send contact form data
		add_action('wp_ajax_send_contact_form', 'themerex_callback_send_contact_form');
		add_action('wp_ajax_nopriv_send_contact_form', 'themerex_callback_send_contact_form');
		// AJAX: New user registration
		add_action('wp_ajax_registration_user', 'themerex_callback_registration_user');
		add_action('wp_ajax_nopriv_registration_user', 'themerex_callback_registration_user');
		// AJAX: Get next page on blog streampage
		add_action('wp_ajax_view_more_posts', 'themerex_callback_view_more_posts');
		add_action('wp_ajax_nopriv_view_more_posts', 'themerex_callback_view_more_posts');
		// AJAX: Incremental search
		add_action('wp_ajax_ajax_search', 'themerex_callback_ajax_search');
		add_action('wp_ajax_nopriv_ajax_search', 'themerex_callback_ajax_search');
		// AJAX: Change month in the calendar widget
		add_action('wp_ajax_calendar_change_month', 'themerex_callback_calendar_change_month');
		add_action('wp_ajax_nopriv_calendar_change_month', 'themerex_callback_calendar_change_month');
		// Frontend editor: Save post data
		add_action('wp_ajax_frontend_editor_save', 'themerex_callback_frontend_editor_save');
		add_action('wp_ajax_nopriv_frontend_editor_save', 'themerex_callback_frontend_editor_save');
		// Frontend editor: Delete post
		add_action('wp_ajax_frontend_editor_delete', 'themerex_callback_frontend_editor_delete');
		add_action('wp_ajax_nopriv_frontend_editor_delete', 'themerex_callback_frontend_editor_delete');
		// Add og:image meta tag for facebook
		add_action( 'wp_head', 'themerex_facebook_og_tags', 5 );



		// Frontend filters:

		// PRE_QUERY: - add filter to main query
		add_filter('posts_where', 'themerex_filter_where');
		// Substitute audio, video and galleries in widget text
		add_filter( 'widget_text', 'themerex_widget_text_filter' );
		// Get theme calendar
		add_filter( 'get_calendar', 'themerex_get_calendar_filter' );
		// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link
		add_filter( 'wp_page_menu_args', 'themerex_page_menu_args' );
		// Adds custom classes to the array of body classes
		add_filter( 'body_class', 'themerex_body_classes' );
		// Filters wp_title to print a neat <title> tag based on what is being viewed
		add_filter( 'wp_title', 'themerex_wp_title', 10, 2 );
		// Add main menu classes
		//add_filter('wp_nav_menu_objects', 'themerex_nav_menu_classes', 10, 2);
		// Add class "widget-number-#' for each widget
		add_filter('dynamic_sidebar_params', 'themerex_add_widget_number', 10, 1);
		// Add theme-specific vars to post_data
		add_filter('themerex_get_post_data', 'themerex_get_post_data_for_theme', 10, 3);
		// Enable/disable shortcodes in excerpt
		add_filter('the_excerpt', 'sc_excerpt_shortcodes');


		// Admin actions:
		// Enqueue scripts and styles
		add_action('admin_enqueue_scripts', 'themerex_admin_scripts');
		// Add categories (taxonomies) filter for custom posts types
		add_action( 'restrict_manage_posts', 'themerex_admin_taxonomy_filter' );
		// Add Theme Options in menu Appearance
		add_action('admin_menu', 'themerex_options_admin_menu_item');
		// Init TGM Activation Plugin
		add_action( 'tgmpa_register', 'themerex_admin_register_plugins' );
		// Clear taxonomies cache when save or delete post or category
        add_action( 'wp_ajax_themerex_options_clear_cache', 'themerex_clear_cache_all');
        add_action( 'wp_ajax_nopriv_themerex_options_clear_cache', 'themerex_clear_cache_all');
        add_action( 'save_post', 'themerex_clear_cache_all', 10, 2 );
		add_action( 'delete_post', 'themerex_clear_cache_all', 10, 2 );
		add_action( 'edit_category', 'themerex_clear_cache_categories',   10, 1 );
		add_action( 'create_category', 'themerex_clear_cache_categories', 10, 1 );
		add_action( 'delete_category', 'themerex_clear_cache_categories', 10, 1 );

		// Prepare shortcodes in the content
		if (function_exists('sc_prepare_content')) sc_prepare_content();

		// Theme filters:
		// Prepare logo text
		add_filter('theme_logo_text', 'themerex_logo_text', 10, 2);
	}
}


/**
 * Register widgetized area and update sidebar with default widgets
 */
if ( !function_exists( 'themerex_widgets_init' ) ) {
	function themerex_widgets_init($widgets=array()) {
		if ( function_exists('register_sidebar') ) {
			if (!is_array($widgets) || count($widgets) == 0) {
				$widgets = array();
				$widgets['sidebar-main']   = __( 'Main Sidebar', 'themerex' );
				$widgets['sidebar-top']    = __( 'Top Sidebar', 'themerex' );
				$widgets['sidebar-footer'] = __( 'Footer Sidebar', 'themerex' );
				$widgets['sidebar-panel']  = __( 'Panel Sidebar', 'themerex' );
				if (function_exists('is_woocommerce')) {
					$widgets['sidebar-cart']  = __( 'WooCommerce Cart Sidebar', 'themerex' );
				}
				// Custom sidebars
				$sidebars = get_theme_option('custom_sidebars');
				if (is_array($sidebars) && count($sidebars) > 0) {
					foreach ($sidebars as $i => $sb) {
						if (trim(chop($sb))=='') continue;
						$widgets['custom-sidebar-'.$i]  = $sb;
					}
				}
			}
			if (count($widgets) > 0) {
				foreach ($widgets as $id=>$name) {
					register_sidebar( array(
						'name'          => $name,
						'id'            => $id,
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h3 class="title">',
						'after_title'   => '</h3>',
					) );
				}
			}
		}
	}
}

/**
 * Enqueue scripts and styles
 */
if ( !function_exists( 'themerex_scripts' ) ) {
	function themerex_scripts() {
		global $wp_styles, $concatenate_scripts;
		$concatenate_scripts = get_theme_option('debug_mode')=='no' && get_theme_option('compose_scripts')=='yes';
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		$fonts = getThemeFontsList(false);
		$theme_fonts = array();
		// Prepare custom fonts
		if (get_custom_option('typography_custom')=='yes') {
			$selectors = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
			foreach ($selectors as $s) {
				$font = get_custom_option('typography_'.$s.'_font');
				if (!empty($font)) $theme_fonts[$font] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('theme_skin_use_fonts', $theme_fonts);
		// Link to selected fonts
		foreach ($theme_fonts as $font=>$v) {
			if (isset($fonts[$font])) {
				$font_name = ($pos=themerex_strpos($font,' ('))!==false ? themerex_substr($font, 0, $pos) : $font;
				$css = !empty($fonts[$font]['css']) 
					? $fonts[$font]['css'] 
					: 'http://fonts.googleapis.com/css?family='.(!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':100,100italic,300,300italic,400,400italic,700,700italic').'&subset=latin,latin-ext,cyrillic,cyrillic-ext';
				themerex_enqueue_style( 'theme-font-'.str_replace(' ', '-', $font_name), $css, array(), null );
			}
		}
		// Fontello styles must be loaded before main stylesheet
		themerex_enqueue_style( 'fontello',  themerex_get_file_url('/css/fontello/css/fontello.css'),  array(), null);
		themerex_enqueue_style( 'animation', themerex_get_file_url('/css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		themerex_enqueue_style( 'main-style', get_stylesheet_uri(), array(), null );
		
		// WooCommerce customizer
		if (function_exists('is_woocommerce')) {
			themerex_enqueue_style( 'woo-style',  themerex_get_file_url('/css/woo-style.css'), array('main-style'), null );
		}

		// Tribe Events
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			$wp_styles->done[] = 'tribe-events-custom-jquery-styles';
			themerex_enqueue_style( 'tribe-style',  themerex_get_file_url('/css/tribe-style.css'), array('main-style'), null );
		}
		
		// BuddyPress customizer
		if ( class_exists( 'BuddyPress' ) ) {
			themerex_enqueue_style( 'buddy-style',  themerex_get_file_url('/css/buddy-style.css'), array('main-style'), null );
		}
		// BB Press customizer
		if ( class_exists( 'bbPress' ) ) {
			themerex_enqueue_style( 'bbpress-style',  themerex_get_file_url('/css/bbpress-style.css'), array('main-style'), null );
		}
		
		if (get_theme_option('debug_mode')=='no' && get_theme_option('packed_scripts')=='yes' && file_exists(themerex_get_file_dir('/css/__packed.css'))) {
			// Load packed styles
			themerex_enqueue_style( 'packed-styles',  themerex_get_file_url('/css/__packed.css'), array('main-style'), null );
		} else {
			// Messages
			themerex_enqueue_style ( 'messages-style', themerex_get_file_url('/js/messages/_messages.css'), array('main-style'), null );
			// Additional hovers for portfolio
			themerex_enqueue_style( 'ihover-styles',  themerex_get_file_url('/css/ihover.css'), array(), null );
			// Shortcodes
			themerex_enqueue_style( 'shortcodes',  themerex_get_file_url('/shortcodes/shortcodes.css'), array('main-style'), null );
		}

		// Main slider
		if (get_custom_option('slider_show')=='yes') {
			themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
			themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );
			themerex_enqueue_style(  'main-slider-style',  themerex_get_file_url('/css/slider-style.css'), array(), null );
			themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
			themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
			themerex_enqueue_script( 'flexslider', themerex_get_file_url('/js/jquery.flexslider.min.js'), array('jquery'), null, true );
		}

		// Theme skin stylesheet
		do_action('theme_skin_add_stylesheets');

		// Custom fonts and colors
		if (get_custom_option('theme_skin')!='')
			wp_add_inline_style( 'theme-skin', prepareThemeCustomStyles() );
		else if (get_theme_option('debug_mode')=='no' && get_theme_option('packed_scripts')=='yes' && file_exists(themerex_get_file_dir('/css/__packed.css')))
			wp_add_inline_style( 'packed-styles', prepareThemeCustomStyles() );
		else
			wp_add_inline_style( 'shortcodes', prepareThemeCustomStyles() );

		// Responsive
		if (get_theme_option('responsive_layouts') == 'yes') {
			themerex_enqueue_style( 'responsive',  themerex_get_file_url('/css/responsive.css'), array('main-style'), null );
			do_action('theme_skin_add_responsive');
			if (get_custom_option('theme_skin')!='') {
				$css = apply_filters('theme_skin_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'responsive', $css );
			}
		}


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		themerex_enqueue_script( 'jquery', false, array(), null, true );
		themerex_enqueue_script( 'jquery-ui-core', false, array(), null, true );
		themerex_enqueue_script( 'jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
		themerex_enqueue_script( 'jquery-effects-core', false, array(), null, true );
		themerex_enqueue_script( 'jquery-effects-fade', false, array('jquery','jquery-effects-core'), null, true);
		if (get_custom_option('show_top_page') == 'yes' && get_custom_option('show_sidebar_top') == 'yes') {
			themerex_enqueue_script( 'jquery-effects-drop', false, array('jquery','jquery-effects-drop'), null, true);
		}
		
		if (get_theme_option('debug_mode')=='no' && get_theme_option('packed_scripts')=='yes' && file_exists(themerex_get_file_dir('/js/__packed.js'))) {
			// Load packed theme scripts
			themerex_enqueue_script( 'packed-scripts', themerex_get_file_url('/js/__packed.js'), array('jquery'), null, true);
		} else {
			// Load separate theme scripts
			themerex_enqueue_script( 'jquery-cookie', themerex_get_file_url('/js/jquery.cookie.js'), array('jquery'), null, true);
			themerex_enqueue_script( 'jquery-easing', themerex_get_file_url('/js/jquery.easing.js'), array('jquery'), null, true );
			themerex_enqueue_script( 'jquery-autosize', themerex_get_file_url('/js/jquery.autosize.js'), array('jquery'), null, true );

			themerex_enqueue_script( 'superfish', themerex_get_file_url('/js/superfish.min.js'), array('jquery'), null, true );

			themerex_enqueue_script( 'smooth-scroll', themerex_get_file_url('/js/SmoothScroll.min.js'), array('jquery'), null, true );

			themerex_enqueue_script( 'hover-dir', themerex_get_file_url('/js/hover/jquery.hoverdir.js'), array(), null, true );
			themerex_enqueue_script( 'hover-intent', themerex_get_file_url('/js/hover/hoverIntent.js'), array(), null, true );

			themerex_enqueue_script( 'messages', themerex_get_file_url('/js/messages/_messages.js'),  array(), null, true );

			themerex_enqueue_script( 'shortcodes-init', themerex_get_file_url('/shortcodes/shortcodes_init.js'), array(), null, true );

			themerex_enqueue_script( '_utils', themerex_get_file_url('/js/_utils.js'), array(), null, true );
			themerex_enqueue_script( '_front', themerex_get_file_url('/js/_front.js'), array(), null, true );
		}

		// Chop slider
		if (file_exists(themerex_get_file_dir('/js/chopslider/jquery.id.chopslider-2.0.0.free.min.js'))) {
			themerex_enqueue_script( 'chopslider', themerex_get_file_url('/js/chopslider/jquery.id.chopslider-2.0.0.free.min.js'), array('jquery'), null, true );
			themerex_enqueue_script( 'cstransitions', themerex_get_file_url('/js/chopslider/jquery.id.cstransitions-1.0.min.js'), array('jquery'), null, true );
		}
		
		// Video background
		if (get_custom_option('show_video_bg') == 'yes' && get_custom_option('video_bg_youtube_code') != '') {
			themerex_enqueue_script( 'video-bg', themerex_get_file_url('/js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		// Google map
		if ( get_custom_option('googlemap_show')=='yes' ) {
            $api_key = get_theme_option('api_google');
            themerex_enqueue_script( 'googlemap', themerex_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
			themerex_enqueue_script( 'googlemap_init', themerex_get_file_url('/js/_googlemap_init.js'), array(), null, true );
		}

		// Sound effects on mouse hover
		if (get_custom_option('sound_enable') == 'yes') {
			themerex_enqueue_script( 'sound-manager', themerex_get_file_url('/js/sounds/soundmanager2-nodebug-js.min.js'), array('jquery'), null, true );
		}
		
		// Login form
		if (get_custom_option('show_login')=='yes') {
			themerex_enqueue_script( 'form-login', themerex_get_file_url('/js/_form_login.js'), array(), null, true );
		}

		// Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			themerex_enqueue_script( 'comment-reply', false, array(), null, true );
			themerex_enqueue_script( 'form-comments', themerex_get_file_url('/js/_form_comments.js'), array(), null, true );
		}

		// Theme skin script
		do_action('theme_skin_add_scripts');
	}
}



// Compose header scripts in one file
$THEMEREX_scripts_collector = array('', '');
if ( !function_exists( 'themerex_compose_scripts' ) ) {
	function themerex_compose_scripts() {
		global $wp_scripts, $concatenate_scripts, $THEMEREX_scripts_collector;
		if (is_admin() || get_theme_option('debug_mode')=='yes' || get_theme_option('compose_scripts')!='yes' || !is_object($wp_scripts)) return;
		//$concatenate_scripts = true;
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		foreach($wp_scripts->queue as $script) {
			$dir = $url = '';
			if (themerex_strpos($wp_scripts->registered[$script]->src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (themerex_strpos($wp_scripts->registered[$script]->src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if (isset($wp_scripts->registered[$script]) && $dir!='' && themerex_strpos($wp_scripts->registered[$script]->ver, 'no-compose')===false) {
				if (file_exists($file = $dir.themerex_substr($wp_scripts->registered[$script]->src, themerex_strlen($url)))) {
					$THEMEREX_scripts_collector[isset($wp_scripts->registered[$script]->extra['group']) && $wp_scripts->registered[$script]->extra['group']==1 ? 1 : 0] .= "\n" . themerex_fgc($file) . "\n";
					$wp_scripts->done[] = $script;
				}
			}
		}
		if ($THEMEREX_scripts_collector[0]) {
			echo "\n<script type=\"text/javascript\">\n".$THEMEREX_scripts_collector[0]."\n</script>\n";
		}
	}
}


// Compose footer scripts in one file
if ( !function_exists( 'themerex_footer_scripts' ) ) {
	function themerex_footer_scripts() {
		if (is_admin() || get_theme_option('debug_mode')=='yes' || get_theme_option('compose_scripts')!='yes') return;
		global $THEMEREX_scripts_collector;
		if ($THEMEREX_scripts_collector[1]) {
			echo "\n<script type=\"text/javascript\">\n".$THEMEREX_scripts_collector[1]."\n</script>\n";
		}
	}
}

// Add parameters to URL
if (!function_exists('themerex_add_to_url')) {
    function themerex_add_to_url($url, $prm) {
        if (is_array($prm) && count($prm) > 0) {
            $separator = themerex_strpos($url, '?')===false ? '?' : '&';
            foreach ($prm as $k=>$v) {
                $url .= $separator . urlencode($k) . '=' . urlencode($v);
                $separator = '&';
            }
        }
        return $url;
    }
}
// Return current site protocol
if (!function_exists('themerex_get_protocol')) {
    function themerex_get_protocol() {
        return is_ssl() ? 'https' : 'http';
    }
}

// Compose styles in one file
$THEMEREX_styles_collector = '';
if ( !function_exists( 'themerex_compose_styles' ) ) {
	function themerex_compose_styles() {
		global $wp_styles, $concatenate_scripts, $compress_css, $THEMEREX_styles_collector;
		if (is_admin() || get_theme_option('debug_mode')=='yes' || get_theme_option('compose_scripts')!='yes' || !is_object($wp_styles)) return;
		//$concatenate_scripts = $compress_css = true;
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		foreach($wp_styles->queue as $style) {
			$dir = $url = '';
			if (themerex_strpos($wp_styles->registered[$style]->src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (themerex_strpos($wp_styles->registered[$style]->src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if (isset($wp_styles->registered[$style]) && $dir!='' && themerex_strpos($wp_styles->registered[$style]->ver, 'no-compose')===false) {
				$dir = dirname($wp_styles->registered[$style]->src).'/';
				if (file_exists($file = $dir.themerex_substr($wp_styles->registered[$style]->src, themerex_strlen($url)))) {
					$css = themerex_fgc($file);
					if (isset($wp_styles->registered[$style]->extra['after'])) {
						foreach ($wp_styles->registered[$style]->extra['after'] as $add) {
							$css .= "\n" . $add . "\n";
						}
					}
					$pos = -1;
					while (($pos=themerex_strpos($css, 'url(', $pos+1))!==false) {
						if (themerex_substr($css, $pos, 9)=='url(data:') continue;
						$shift = 0;
						if (($ch=themerex_substr($css, $pos+4, 1))=='"' || $ch=="'") {
							$shift = 1;
						}
						$css = themerex_substr($css, 0, $pos+4+$shift) . $dir . themerex_substr($css, $pos+4+$shift);
					}
					$THEMEREX_styles_collector .= "\n" . $css . "\n";
					$wp_styles->done[] = $style;
				}
			}
		}
		if ($THEMEREX_styles_collector) {
			echo "\n<style type=\"text/css\">\n".$THEMEREX_styles_collector."\n</style>\n";
		}
	}
}

// Admin side setup
if (is_admin()) {
	if ( !function_exists( 'themerex_admin_scripts' ) ) {
		function themerex_admin_scripts(){
			themerex_enqueue_script('jquery', false, array(), null, true);
			themerex_enqueue_script('jquery-ui-core', false, array('jquery'), null, true);
			themerex_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
			themerex_enqueue_script( 'jquery-cookie', themerex_get_file_url('/js/jquery.cookie.js'), array('jquery'), null, true);
	
			themerex_enqueue_style(  'wp-color-picker', array(), null );
			themerex_enqueue_script( 'wp-color-picker', false, array(), null, true );
	
			themerex_enqueue_style(  'theme-admin-style',  themerex_get_file_url('/css/admin-style.css'), array(), null );
			themerex_enqueue_style( 'fontello-admin', themerex_get_file_url('/admin/css/fontello/css/fontello-admin.css'), array(), null);
			themerex_enqueue_style( 'fontello', themerex_get_file_url('/css/fontello/css/fontello.css'), array(), null);
		
			themerex_enqueue_script( '_utils',   themerex_get_file_url('/js/_utils.js'), array(), null, true );
			themerex_enqueue_script( '_admin',   themerex_get_file_url('/js/_admin.js'), array('jquery'), null, true );	
			themerex_enqueue_script( '_reviews', themerex_get_file_url('/js/_reviews.js'), array('jquery'), null, true );
		}
	}

	// Add categories (taxonomies) filter for custom posts types
	if ( !function_exists( 'themerex_admin_taxonomy_filter' ) ) {
		function themerex_admin_taxonomy_filter() {
			if (get_theme_option('admin_add_filters')!='yes') return;
			$page = get_query_var('post_type');
			if ($page == 'post')
				$taxes = array('post_format', 'post_tag');
			else if ($page == 'attachment')
				$taxes = array('media_folder');
			else
				return;
			echo getTermsFilters($taxes);
		}
	}

	// Register optional plugins
	if ( !function_exists( 'themerex_admin_register_plugins' ) ) {
		function themerex_admin_register_plugins() {
		
			$plugins = array(
				array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				),
				array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> themerex_get_file_dir('/plugins/js_composer.zip'),
					'required' 	=> false
				),
/* Extended WPML-license don't allow to distribute plugin in the theme
				array(
					'name' 		=> 'WPML (Sitepress Multilingual CMS)',
					'slug' 		=> 'sitepress-multilingual-cms',
					'source'	=> themerex_get_file_dir('/plugins/wpml.zip'),
					'required' 	=> false
				),
*/

				array(
					'name' 		=> 'Revolution Slider',
					'slug' 		=> 'revslider',
					'source'	=> themerex_get_file_dir('/plugins/revslider.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Tribe Events Calendar',
					'slug' 		=> 'the-events-calendar',
					'source'	=> themerex_get_file_dir('/plugins/the-events-calendar.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Booking Calendar',
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> themerex_get_file_dir('/plugins/wp-booking-calendar.zip'),
					'required' 	=> false
				),
				array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'source'	=> themerex_get_file_dir('/plugins/wp-instagram-widget.zip'),
					'required' 	=> false
				)
			);
			$theme_text_domain = 'themerex';
			$config = array(

				'domain'			=> $theme_text_domain,			// Text domain - likely want to be the same as your theme.
				'default_path'		=> '',							// Default absolute path to pre-packaged plugins
				'parent_slug'	=> 'themes.php',				// Default parent menu slug
				'capability'       => 'edit_theme_options',       // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'menu'				=> 'tgmpa-install-plugins',	// Menu slug
				'has_notices'		=> true,						// Show admin notices or not
				'is_automatic'		=> true,						// Automatically activate plugins after installation or not
				'message'			=> '',							// Message to output right before the plugins table
				'strings'			=> array(
					'page_title'						=> __( 'Install Required Plugins', 'themerex' ),
					'menu_title'						=> __( 'Install Plugins', 'themerex' ),
					'installing'						=> __( 'Installing Plugin: %s', 'themerex' ), // %1$s = plugin name
					'oops'								=> __( 'Something went wrong with the plugin API.', 'themerex' ),
					'notice_can_install_required'		=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
					'notice_can_install_recommended'	=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
					'notice_cannot_install'				=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
					'notice_can_activate_required'		=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
					'notice_can_activate_recommended'	=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
					'notice_cannot_activate'			=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
					'notice_ask_to_update'				=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
					'notice_cannot_update'				=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
					'install_link'						=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
					'activate_link'						=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
					'return'							=> __( 'Return to Required Plugins Installer', 'themerex' ),
					'plugin_activated'					=> __( 'Plugin activated successfully.', 'themerex' ),
					'complete'							=> __( 'All plugins installed and activated successfully. %s', 'themerex'), // %1$s = dashboard link
					'nag_type'							=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
				)
			);
	
			tgmpa( $plugins, $config );
		}
	}
}



// Init theme template - prepare global variables
function themerex_init_template() {
	// AJAX Queries settings
	global $THEMEREX_ajax_nonce, $THEMEREX_ajax_url;
	$THEMEREX_ajax_nonce = wp_create_nonce('ajax_nonce');
	$THEMEREX_ajax_url = admin_url('admin-ajax.php');

	// Set theme params from GET
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}
	
	// Get custom options from current category / page / post / shop
	load_custom_options();
	
	// Reject old browsers support
	global $THEMEREX_jreject;
	$THEMEREX_jreject = false;
	if (!isset($_COOKIE['jreject'])) {
		themerex_enqueue_style(  'jquery_reject-style',  themerex_get_file_url('/js/jreject/css/jquery.reject.css'), array(), null );
		themerex_enqueue_script( 'jquery_reject', themerex_get_file_url('/js/jreject/jquery.reject.js'), array('jquery'), null, true );
		setcookie('jreject', 1, 0, '/');
		$THEMEREX_jreject = true;
	}

	// Main menu
	global $THEMEREX_mainmenu;
	if (get_custom_option('show_top_panel')!='hide') {
		$menu_slug = get_custom_option('menu_main');
		$args = array(
			'menu'              => empty($menu_slug) || $menu_slug=='default' || is_inherit_option($menu_slug) ? '' : $menu_slug,
			'container'         => '',
			'container_class'   => '',
			'container_id'      => '',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class'        => '',
			'menu_id'           => 'mainmenu',
			'echo'              => false,
			'fallback_cb'       => '',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'depth'             => 11,
			'theme_location'    => 'mainmenu'
		);
		if (get_theme_option('custom_menu')=='yes' && class_exists('themerex_walker')) {
			$args['walker'] = new themerex_walker;
		}
		$THEMEREX_mainmenu = wp_nav_menu($args);
	} else
		$THEMEREX_mainmenu = '';
	
	// User menu
	global $THEMEREX_usermenu;
	if (get_custom_option('show_top_panel')!='hide' && get_custom_option('show_user_menu')=='yes') {
		$menu_slug = get_custom_option('menu_user');
		$THEMEREX_usermenu = wp_nav_menu(array(
			'menu'              => empty($menu_slug) || $menu_slug=='default' || is_inherit_option($menu_slug) ? '' : $menu_slug,
			'container'         => '',
			'container_class'   => '',
			'container_id'      => '',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class'        => '',
			'menu_id'           => 'usermenu',
			'echo'              => false,
			'fallback_cb'       => '',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'depth'             => 11,
			'theme_location'    => 'usermenu'
		));
	} else
		$THEMEREX_usermenu = '';
	
	// Side menu
	global $THEMEREX_sidemenu;
	if (get_custom_option('show_left_panel')=='yes') {
		$menu_slug = get_custom_option('menu_side');
		$THEMEREX_sidemenu = wp_nav_menu(array(
			'menu'              => empty($menu_slug) || $menu_slug=='default' || is_inherit_option($menu_slug) ? '' : $menu_slug,
			'container'         => '',
			'container_class'   => '',
			'container_id'      => '',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class'        => '',
			'menu_id'           => 'sidemenu',
			'echo'              => false,
			'fallback_cb'       => '',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'depth'             => 11,
			'theme_location'    => 'sidemenu'
		));
	} else
		$THEMEREX_sidemenu = '';

	// Panel menu
	global $THEMEREX_panelmenu;
	if (get_custom_option('show_right_panel')=='yes') {
		$menu_slug = get_custom_option('menu_right');
		$THEMEREX_panelmenu = wp_nav_menu(array(
			'menu'              => empty($menu_slug) || $menu_slug=='default' || is_inherit_option($menu_slug) ? '' : $menu_slug,
			'container'         => '',
			'container_class'   => '',
			'container_id'      => '',
			'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'menu_class'        => '',
			'menu_id'           => 'panelmenu',
			'echo'              => false,
			'fallback_cb'       => '',
			'before'            => '',
			'after'             => '',
			'link_before'       => '',
			'link_after'        => '',
			'depth'             => 11,
			'theme_location'    => 'panelmenu'
		));
	} else
		$THEMEREX_panelmenu = '';

	// Include current skin
	$skin = themerex_escape_shell_cmd(get_custom_option('theme_skin'));

	if ( file_exists(themerex_get_file_dir('/skins/'.$skin.'/'.$skin.'.php')) ) {
		require_once( themerex_get_file_dir('/skins/'.$skin.'/'.$skin.'.php') );
	}

	// Logo image and icon from skin
	global $logo_text, $logo_slogan, $logo_icon, $logo_image, $logo_side, $logo_fixed, $logo_footer;
	$logo_text = get_custom_option('logo_text');
	$logo_slogan = get_custom_option('logo_slogan');
	$menu_align = get_custom_option('menu_align');

	if ($logo_slogan == '') $logo_slogan = get_bloginfo ( 'description' );
	$logo_icon = $logo_image = $logo_side = $logo_fixed = $logo_footer = '';

	if (($logo_icon = get_custom_option('logo_icon')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-icon.png')))
		$logo_icon = themerex_get_file_url('/skins/' . $skin . '/images/logo-icon.png');

	if ($menu_align=='left' || $menu_align=='center') {
		if (($logo_image = get_custom_option('logo_top')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-top.png')))
			$logo_image = themerex_get_file_url('/skins/' . $skin . '/images/logo-top.png');
	}

	if ($logo_image=='' && ($logo_image = get_custom_option('logo_image')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo.png')))
		$logo_image = themerex_get_file_url('/skins/' . $skin . '/images/logo.png');

	if (($logo_side = get_custom_option('logo_side')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-side.png')))
		$logo_side = themerex_get_file_url('/skins/' . $skin . '/images/logo-side.png');
	if ($logo_side=='') $logo_side = $logo_image;	

	if (($logo_fixed = get_custom_option('logo_fixed')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-fixed.png')))
		$logo_fixed = themerex_get_file_url('/skins/' . $skin . '/images/logo-fixed.png');
	if ($logo_fixed=='') $logo_fixed = $logo_image;	

	if (($logo_footer = get_custom_option('logo_image_footer')) == '' && file_exists(themerex_get_file_dir('/skins/' . $skin . '/images/logo-footer.png')))
		$logo_footer = themerex_get_file_url('/skins/' . $skin . '/images/logo-footer.png');
	if ($logo_footer=='') $logo_footer = $logo_image;	
	
	global $THEMEREX_shop_mode;
	$THEMEREX_shop_mode = getValueGPC('themerex_shop_mode');
	if (empty($THEMEREX_shop_mode)) $THEMEREX_shop_mode = get_custom_option('shop_mode', '');
	if (empty($THEMEREX_shop_mode) || !is_archive()) $THEMEREX_shop_mode = 'thumbs';
}




/* ========================= Filters and action handlers ============================== */

// Add facebook meta tags for post/page sharing
function themerex_facebook_og_tags() {
	global $post;
	if ( !is_singular()) return;
	if (has_post_thumbnail( $post->ID )) {
		$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>' . "\n";
	}
	//echo '<meta property="og:title" content="' . esc_attr( strip_tags( get_the_title() ) ) . '" />' . "\n"
	//	.'<meta property="og:description" content="' . esc_attr( strip_tags( strip_shortcodes( get_the_excerpt()) ) ) . '" />' . "\n"
	//	.'<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />';
}

// User login by name or email
function themerex_allow_email_login( $user, $username, $password ) {
	if ( is_email( $username ) ) {
		$user = get_user_by('email', $username );
		if ( $user ) $username = $user->user_login;
	}
	return wp_authenticate_username_password( null, $username, $password );
}

// PRE_QUERY - add filter to main query
if ( !function_exists( 'themerex_filter_where' ) ) {
	function themerex_filter_where($where = '') {
		global $wpdb, $wp_query;
		if (is_admin() || $wp_query->is_attachment()) return $where;
		if (themerex_strpos($where, 'post_status')===false && (!isset($_REQUEST['preview']) || $_REQUEST['preview']!='true') && (!isset($_REQUEST['vc_editable']) || $_REQUEST['vc_editable']!='true')) {
			$prv = current_user_can('read_private_pages') && current_user_can('read_private_posts') ? " OR ".esc_sql($wpdb->posts).".post_status='private'" : '';
			$where .= " AND ".(!empty($prv) ? '(' : '')."".esc_sql($wpdb->posts).".post_status='publish'".(!empty($prv) ? $prv : '').(!empty($prv) ? ')' : '');
		}
		return $where;
	}
}

// PRE QUERY - posts per page selector
if ( !function_exists( 'themerex_posts_per_page_selector' ) ) {
	function themerex_posts_per_page_selector($query) {
		if (is_admin() || !$query->is_main_query()) return;
		$orderby_set = true;
		$orderby = get_theme_option('blog_sort');
		$order = get_theme_option('blog_order');
		// Set posts per page
		$ppp = (int) get_theme_option('posts_per_page');
		$ppp2 = 0;
		if ( $query->is_category() ) {
			$cat = (int) $query->get('cat');
			if (empty($cat))
				$cat = $query->get('category_name');
			if (!empty($cat)) {
				//$ppp2 = (int) get_category_inherited_property($cat, 'posts_per_page', 0);
				$cat_options = get_category_inherited_properties($cat);
				if (isset($cat_options['posts_per_page']) && !empty($cat_options['posts_per_page']) && !is_inherit_option($cat_options['posts_per_page'])) $ppp2 = (int) $cat_options['posts_per_page'];
				if (isset($cat_options['blog_sort']) && !empty($cat_options['blog_sort']) && !is_inherit_option($cat_options['blog_sort'])) $orderby = $cat_options['blog_sort'];
				if (isset($cat_options['blog_order']) && !empty($cat_options['blog_order']) && !is_inherit_option($cat_options['blog_order'])) $order = $cat_options['blog_order'];
			}
		} else {
			if ($query->get('post_type')=='product' || $query->get('product_cat')!='' || $query->get('product_tag')!='') {
				$orderby_set = false;
				$page_id = get_option('woocommerce_shop_page_id');
			} else if ($query->is_archive()) {
				$page_id = getTemplatePageId('archive');
			} else if ($query->is_search()) {
				$page_id = getTemplatePageId('search');
				if (get_theme_option('use_ajax_search')=='yes') {
					$show_types = get_theme_option('ajax_search_types');
					if (!empty($show_types)) 
						$query->set('post_type', explode(',', $show_types));
				}
			} else if ($query->is_posts_page==1) {
				$page_id = isset($query->queried_object_id) ? $query->queried_object_id : getTemplatePageId('blog');
			} else {
				$page_id = 0;
			}
			if ($page_id > 0) {
				$post_options = get_post_meta($page_id, 'post_custom_options', true);
				if (isset($post_options['posts_per_page']) && !empty($post_options['posts_per_page']) && !is_inherit_option($post_options['posts_per_page'])) $ppp2 = (int) $post_options['posts_per_page'];
				if ($orderby_set) {
					if (isset($post_options['blog_sort']) && !empty($post_options['blog_sort']) && !is_inherit_option($post_options['blog_sort'])) $orderby = $post_options['blog_sort'];
					if (isset($post_options['blog_order']) && !empty($post_options['blog_order']) && !is_inherit_option($post_options['blog_order'])) $order = $post_options['blog_order'];
				}
			}
		}
		if ($ppp2 > 0)	$ppp = $ppp2;
		if ($ppp > 0) 	$query->set('posts_per_page', $ppp );
		if ($orderby_set) addSortOrderInQuery($query, $orderby, $order);
		// Exclude categories
		$ex = get_theme_option('exclude_cats');
		if (!empty($ex))
			$query->set('category__not_in', explode(',', $ex) );
	}
}

// Filter categories list - exclude unwanted cats from widget output
if ( !function_exists( 'themerex_categories_args_filter' ) ) {
	function themerex_categories_args_filter($args) {
		if (!is_admin()) {
			$ex = get_theme_option('exclude_cats');
			if (!empty($ex))
				$args['exclude'] = $ex;
		}
		return $args;
	}
}
if ( !function_exists( 'themerex_posts_args_filter' ) ) {
	function themerex_posts_args_filter($args) {
		if (!is_admin()) {
			$ex = get_theme_option('exclude_cats');
			if (!empty($ex)) {
				$args['category__not_in'] = explode(',', $ex);
			}
		}
		return $args;
	}
}

// Substitute audio, video and galleries in widget text
if ( !function_exists( 'themerex_widget_text_filter' ) ) {
	function themerex_widget_text_filter( $text ){
		return substituteAll($text);
	}
}

// Get theme calendar
if ( !function_exists( 'themerex_get_calendar_filter' ) ) {
	function themerex_get_calendar_filter( $text ){
		return getThemeRexCalendar($text);
	}
}

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
if ( !function_exists( 'themerex_page_menu_args' ) ) {
	function themerex_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
}

// Adds custom classes to the array of body classes.
if ( !function_exists( 'themerex_body_classes' ) ) {
	function themerex_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
	
		return $classes;
	}
}

// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'themerex_wp_title' ) ) {
	function themerex_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title .= " $sep $site_description";
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( __( 'Page %s', 'themerex' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'themerex_nav_menu_classes' ) ) {
	function themerex_nav_menu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && get_theme_option('menu_colored')=='yes') {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_theme = get_category_inherited_property($item->object_id, 'blog_theme');
						if (!empty($cur_theme) && !is_inherit_option($cur_theme))
							$items[$k]->classes[] = 'theme_'.$cur_theme;
					}
				}
			}
		}
		return $items;
	}
}

// Add theme-specific vars to post_data
if ( !function_exists( 'themerex_get_post_data_for_theme' ) ) {
	function themerex_get_post_data_for_theme($post_data, $opt, $post_obj) {
		$post_data['post_accent_color'] = $opt['parent_cat_id'] > 0 ? (empty($opt['accent_color']) ? get_category_inherited_property($opt['parent_cat_id'], 'theme_accent_color') : $opt['accent_color']) : '';
		if ($post_data['post_accent_color']=='') {
			$ex_cats = explode(',', get_theme_option('exclude_cats'));
			for ($i = 0; $i < count($post_data['post_categories_list']); $i++) {
				if (in_array($post_data['post_categories_list'][$i]['term_id'], $ex_cats)) continue;
				if (get_theme_option('close_category')=='parental') {
					$parent_cat = getParentCategory($post_data['post_categories_list'][$i]['term_id'], $opt['parent_cat_id']);
					if ($parent_cat) {
						$post_data['post_accent_color'] = get_category_inherited_property($parent_cat['term_id'], 'theme_accent_color');
					}
				} else {
					$post_data['post_accent_color'] = get_category_inherited_property($post_categories_list[$i]['term_id'], 'theme_accent_color');
				}
				if ($post_data['post_accent_color']!='') break;
			}
		}
		return $post_data;
	}
}

// Add class "widget-number-#' for each widget
if ( !function_exists( 'themerex_add_widget_number' ) ) {
	function themerex_add_widget_number($prm) {
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_count=0, $sidebars_widgets=array();
		global $THEMEREX_CURRENT_SIDEBAR;
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $THEMEREX_CURRENT_SIDEBAR) {
			$num = 0;
			$last_sidebar = $THEMEREX_CURRENT_SIDEBAR;
			$last_sidebar_count = count($sidebars_widgets[$prm[0]['id']]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget-number-'.$num.' ', $prm[0]['before_widget']);
		if ($last_sidebar_count > $num && $last_sidebar=='main') {
			$prm[0]['before_widget'] = str_replace(' class="', ' class="hrShadow ', $prm[0]['before_widget']);
		}
		if ($last_sidebar == 'top') {
			$prm[0]['before_widget'] = str_replace(' class="', ' class="widgetTop '.($num > 1 ? 'clr ' : ''), $prm[0]['before_widget']);
			$prm[0]['before_title'] = str_replace(' class="', ' class="titleHide ', $prm[0]['before_title']);
		} else {
			$prm[0]['before_widget'] = str_replace(' class="', ' class="widgetWrap ', $prm[0]['before_widget']);
			if ($last_sidebar == 'footer') {
				$columns = max(1, min(4, $last_sidebar_count));
				$prm[0]['before_widget'] = str_replace(' class="', ' class="columns1_'.$columns.' ', $prm[0]['before_widget']);
				if ($num==1)
					$prm[0]['before_widget'] = '<div class="columnsWrap">' . $prm[0]['before_widget'];
				if ($last_sidebar_count == $num)
					$prm[0]['after_widget'] .= '</div>';
				else if ($num % $columns == 0) {
					$prm[0]['after_widget'] .= '</div><div class="columnsWrap">';
				}
			}
		}
		return $prm;
	}
}

// Prepare logo text
if ( !function_exists( 'themerex_logo_text' ) ) {
	function themerex_logo_text($text, $where) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}




/* ========================= AJAX queries handlers ============================== */

// Save e-mail in subscribe list
if ( !function_exists( 'themerex_callback_emailer_submit' ) ) {
	function themerex_callback_emailer_submit() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'');
		
		$group = $_REQUEST['group'];
		$email = $_REQUEST['email'];

		if (preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) {
			$subscribers = themerex_emailer_group_getlist($group);
			if (isset($subscribers[$group][$email]))
				$response['error'] = __('E-mail address already in the subscribers list!', 'themerex');
			else {
				$subscribers[$group][$email] = md5(mt_rand());
				update_option('emailer_subscribers', $subscribers);
				$subj = sprintf(__('Site %s - Subscribe confirmation', 'themerex'), get_bloginfo('site_name'));
				$url = admin_url('admin-ajax.php');
				$link = $url . (themerex_strpos($url, '?')===false ? '?' : '') . 'action=emailer_confirm&nonce='.urlencode($subscribers[$group][$email]).'&email='.urlencode($email).'&group='.urlencode($group);
				$msg = sprintf(__("You or someone else added this e-mail address into our subcribtion list.\nPlease, confirm your wish to receive newsletters from our website by clicking on the link below:\n\n<a href=\"%s\">%s</a>\n\nIf you do not wiish to subscribe to our newsletters, simply ignore this message.", 'themerex'), $link, $link);
				add_filter( 'wp_mail_content_type', 'set_html_content_type' );
				$sender_name = get_bloginfo('name');
				$sender_email = get_theme_option('contact_email');
				if (empty($sender_email)) $sender_email = get_bloginfo('admin_email');
				$headers = 'From: ' . $sender_name.' <' . $sender_email . '>' . "\r\n";
				$mail = get_theme_option('mail_function');
				if (!@$mail($email, $subj, nl2br($msg), $headers)) {
					$response['error'] = __('Error send message!', 'themerex');
				}
				remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
			}
		} else
			$response['error'] = __('E-mail address is not valid!', 'themerex');
		echo json_encode($response);
		die();
	}
}

// Confirm e-mail in subscribe list
if ( !function_exists( 'themerex_callback_emailer_confirm' ) ) {
	function themerex_callback_emailer_confirm() {
		global $_REQUEST;
		
		$group = $_REQUEST['group'];
		$email = $_REQUEST['email'];
		$nonce = $_REQUEST['nonce'];
		if (preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) {
			$subscribers = themerex_emailer_group_getlist($group);
			if (isset($subscribers[$group][$email])) {
				if ($subscribers[$group][$email] == $nonce) {
					$subscribers[$group][$email] = '';
					update_option('emailer_subscribers', $subscribers);
					themerex_set_message(__('Confirmation complete! E-mail address succefully added in the subscribers list!', 'themerex'), 'success');
					header('Location: '.home_url());
				} else if ($subscribers[$group][$email] != '') {
					themerex_set_message(__('Bad confirmation code!', 'themerex'), 'error');
					header('Location: '.home_url());
				} else {
					themerex_set_message(__('E-mail address already exists in the subscribers list!', 'themerex'), 'error');
					header('Location: '.home_url());
				}
			}
		}
		die();
	}
}


// Get subscribers list if group changed
if ( !function_exists( 'themerex_callback_emailer_group_getlist' ) ) {
	function themerex_callback_emailer_group_getlist() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'', 'subscribers' => '');
		
		$group = $_REQUEST['group'];
		$subscribers = themerex_emailer_group_getlist($group);
		$list = array();
		if (isset($subscribers[$group]) && count($subscribers[$group]) > 0) {
			foreach ($subscribers[$group] as $k=>$v) {
				if (empty($v))
					$list[] = $k;
			}
		}
		$response['subscribers'] = join("\n", $list);

		echo json_encode($response);
		die();
	}
}

// Get Subscribers list
if ( !function_exists( 'themerex_emailer_group_getlist' ) ) {
	function themerex_emailer_group_getlist($group='') {
		$subscribers = get_option('emailer_subscribers', array());
		if (!is_array($subscribers))
			$subscribers = array();
		if (!empty($group) && (!isset($subscribers[$group]) || !is_array($subscribers[$group])))
			$subscribers[$group] = array();
		if (count($subscribers) > 0) {
			$need_save = false;
			foreach ($subscribers as $grp=>$list) {
				if (isset($list[0])) {	// Plain array - old format - convert it
					$rez = array();
					foreach ($list as $v) {
						$rez[$v] = '';
					}
					$subscribers[$grp] = $rez;
					$need_save = true;
				}
			}
			if ($need_save)
				update_option('emailer_subscribers', $subscribers);
		}
		return $subscribers;
	}
}


// Clear Wordpress cache
if ( !function_exists( 'themerex_callback_clear_cache' ) ) {
	function themerex_callback_clear_cache() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();

		$response = array('error'=>'');

		themerex_clear_cache('all');

		echo json_encode($response);
		die();
	}
}

// Set post likes/views count
if ( !function_exists( 'themerex_callback_post_counter' ) ) {
	function themerex_callback_post_counter() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'');
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$counter = max(0, (int) $_REQUEST['likes']);
			setPostLikes($id, $counter);
		} else if (isset($_REQUEST['views'])) {
			$counter = max(0, (int) $_REQUEST['views']);
			setPostViews($id, $counter);
		}
		echo json_encode($response);
		die();
	}
}

// Calendar change month
if ( !function_exists( 'themerex_callback_calendar_change_month' ) ) {
	function themerex_callback_calendar_change_month() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();

		$m = (int) $_REQUEST['month'];
		$y = (int) $_REQUEST['year'];
		$pt = $_REQUEST['post_type'];

		$response = array('error'=>'', 'data'=>getThemeRexCalendar(true, $m, $y, array('post_type'=>$pt)));

		echo json_encode($response);
		die();
	}
}

// Get attachment url
if ( !function_exists( 'themerex_callback_get_attachment_url' ) ) {
	function themerex_callback_get_attachment_url() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'');
		
		$id = (int) $_REQUEST['attachment_id'];
		
		$response['data'] = wp_get_attachment_url($id);
		
		echo json_encode($response);
		die();
	}
}

// Send contact form data
if ( !function_exists( 'themerex_callback_send_contact_form' ) ) {
	function themerex_callback_send_contact_form() {
		global $_REQUEST;
	
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'');
		if (!($contact_email = get_theme_option('contact_email')) && !($contact_email = get_theme_option('admin_email'))) 
			$response['error'] = __('Unknown admin email!', 'themerex');
		else {
			$type = themerex_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

			if ($type=='contact') {
				$user_name = themerex_substr($post_data['username'], 0, 20);
				$user_email = themerex_substr($post_data['email'], 0, 60);
				$user_subj = getShortString($post_data['subject'], 100);
				$user_msg = getShortString($post_data['message'], 300);
		
				$subj = sprintf(__('Site %s - Contact form message from %s', 'themerex'), get_bloginfo('site_name'), $user_name);
				$msg = "\n".__('Name:', 'themerex')   .' '.$user_name
					.  "\n".__('E-mail:', 'themerex') .' '.$user_email
					.  "\n".__('Subject:', 'themerex').' '.$user_subj
					.  "\n".__('Message:', 'themerex').' '.$user_msg;

			} else {

				$subj = sprintf(__('Site %s - Contact form message', 'themerex'), get_bloginfo('site_name'));
				$msg = '';
				foreach ($post_data as $k=>$v)
					$msg .= "\n{$k}: $v";
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . home_url() . ") ............";

			$mail = get_theme_option('mail_function');
			if (!@$mail($contact_email, $subj, $msg)) {
				$response['error'] = __('Error send message!', 'themerex');
			}
		
			echo json_encode($response);
			die();
		}
	}
}


// New user registration
if ( !function_exists( 'themerex_callback_registration_user' ) ) {
	function themerex_callback_registration_user() {
		global $_REQUEST;
	
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) ) {
			die();
		}
	
		$user_name  = themerex_substr($_REQUEST['user_name'], 0, 20);
		$user_email = themerex_substr($_REQUEST['user_email'], 0, 60);
		$user_pwd   = themerex_substr($_REQUEST['user_pwd'], 0, 20);
	
		$response = array('error' => '');
	
		$id = wp_insert_user( array ('user_login' => $user_name, 'user_pass' => $user_pwd, 'user_email' => $user_email) );
		if ( is_wp_error($id) ) {
			$response['error'] = $id->get_error_message();
		} else if (get_theme_option('notify_about_new_registration')=='yes' && (($contact_email = get_theme_option('contact_email')) || ($contact_email = get_theme_option('admin_email')))) {
			$subj = sprintf(__('Site %s - New user registration: %s', 'themerex'), get_bloginfo('site_name'), $user_name);
			$msg = "
	".__('New registration:', 'themerex')."
	".__('Name:', 'themerex')." $user_name
	".__('E-mail:', 'themerex')." $user_email
	
	............ " . get_bloginfo('site_name') . " (" . home_url() . ") ............";
	
			$head = "Content-Type: text/plain; charset=\"utf-8\"\n"
				. "X-Mailer: PHP/" . phpversion() . "\n"
				. "Reply-To: $user_email\n"
				. "To: $contact_email\n"
				. "From: $user_email\n"
				. "Subject: $subj\n";
	
			$mail = get_theme_option('mail_function');
			@$mail($contact_email, $subj, $msg, $head);
		}
	
		echo json_encode($response);
		die();
	}
}

// Get next page on blog streampage
if ( !function_exists( 'themerex_callback_view_more_posts' ) ) {
	function themerex_callback_view_more_posts() {
		global $_REQUEST, $post, $wp_query;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'', 'data' => '', 'no_more_data' => 0);
		
		$page = $_REQUEST['page'];
		$args = unserialize(stripslashes($_REQUEST['data']));
		$vars = unserialize(stripslashes($_REQUEST['vars']));
	
		if ($page > 0 && is_array($args) && is_array($vars)) {
			extract($vars);
			$args['page'] = $page;
			$args['paged'] = $page;
			$args['ignore_sticky_posts'] = 1;
			if (!isset($wp_query))
				$wp_query = new WP_Query( $args );
			else
				query_posts($args);
			$per_page = count($wp_query->posts);
			$response['no_more_data'] = $page>=$wp_query->max_num_pages;	//$per_page < $ppp;
			$post_number = 0;
			$accent_color = '';
			$response['data'] = '';
			$flt_ids = array();
			while ( have_posts() ) { the_post(); 
				$post_number++;
			
				$post_args = array(
					'layout' => in_array(themerex_substr($vars['blog_style'], 0, 7), array('classic', 'masonry', 'portfol')) ? themerex_substr($vars['blog_style'], 0, 7) : $vars['blog_style'],
					'number' => $post_number,
					'add_view_more' => false,
					'posts_on_page' => $per_page,
					// Get post data
					'thumb_size' => $vars['blog_style'],
					'thumb_crop' => themerex_strpos($vars['blog_style'], 'masonry')===false,
					'strip_teaser' => false,
					'parent_cat_id' => $vars['parent_cat_id'],
					'sidebar' => !in_array($vars['show_sidebar_main'], array('none', 'fullwidth')),
					'filters' => $vars['filters'],
					'hover' => $vars['hover'],
					'show' => false
				);
				$post_data = getPostData($post_args);
				$response['data'] .= showPostLayout($post_args, $post_data);
				if ($vars['filters']=='tags') {
					if (count($post_data['post_tags_list']) > 0) {
						foreach ($post_data['post_tags_list'] as $tag) {
							$flt_ids[$tag->term_id] = $tag->name;
						}
					}
				}
			}
			$response['filters'] = $flt_ids;
		} else {
			$response['error'] = __('Wrong query arguments', 'themerex');
		}
		
		echo json_encode($response);
		die();
	}
}

// Incremental search
if ( !function_exists( 'themerex_callback_ajax_search' ) ) {
	function themerex_callback_ajax_search() {
		global $_REQUEST, $post, $wp_query;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'', 'data' => '');
		
		$s = $_REQUEST['text'];
	
		if (!empty($s)) {

			$show_types = get_theme_option('ajax_search_types');
			$show_date = get_theme_option('ajax_search_posts_date')=='yes' ? 1 : 0;
			$show_image = get_theme_option('ajax_search_posts_image')=='yes' ? 1 : 0;
			$show_author = get_theme_option('ajax_search_posts_author')=='yes' ? 1 : 0;
			$show_counters = get_theme_option('ajax_search_posts_counters')=='yes' ? get_theme_option('blog_counters') : '';
			$args = array(
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'desc', 
				'posts_per_page' => max(1, min(10, get_theme_option('ajax_search_posts_count'))),
				's' => esc_html($s),
				);
			// Filter post types
			if (!empty($show_types)) $args['post_type'] = explode(',', $show_types);
			// Exclude categories
			$ex = get_theme_option('exclude_cats');
			if (!empty($ex))
				$args['category__not_in'] = explode(',', $ex);

			$args = apply_filters( 'ajax_search_query', $args);	

			$post_number = 0;
			$output = '';

			if (!isset($wp_query))
				$wp_query = new WP_Query( $args );
			else
				query_posts($args);
			while ( have_posts() ) { the_post();
				$post_number++;
				require(themerex_get_file_dir('/templates/page-part-widgets-posts.php'));
			}
			if (empty($output)) {
				$output .= '<article class="post_item">' . __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'themerex') . '</article>';
			} else {
				$output .= '<article class="post_item"><a href="#" class="post_more">' . __('More results &hellip;', 'themerex') . '</a></article>';
			}
			$response['data'] = $output;
		} else {
			$response['error'] = __('The query string is empty!', 'themerex');
		}
		
		echo json_encode($response);
		die();
	}
}




/* ========================= Taxonomies cache ============================== */
// Clear all cache (when save or delete post)
if ( !function_exists( 'themerex_clear_cache_all' ) ) {
	function themerex_clear_cache_all($post_id=0, $post_obj=null) {
        delete_transient("themerex_terms_filter_media_folder");
        delete_transient("themerex_terms_filter_category");
		delete_transient("themerex_terms_filter_post_tag");
		delete_transient("themerex_terms_filter_post_format");
	}
}

// Clear categories cache (when create, edit or delete category)
if ( !function_exists( 'themerex_clear_cache_categories' ) ) {
	function themerex_clear_cache_categories($term_id=0) {
		delete_transient("themerex_terms_filter_category");
	}
}





/* ========================= Frontend Editor ============================== */

// Save post data from frontend editor
if ( !function_exists( 'themerex_callback_frontend_editor_save' ) ) {
	function themerex_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'themerex_editor_nonce' ) )
			die();

		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = __('Post update error!', 'themerex');
			} else {
				$response['error'] = __('Post update error!', 'themerex');
			}
		} else
			$response['error'] = __('Post update denied!', 'themerex');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'themerex_callback_frontend_editor_delete' ) ) {
	function themerex_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'themerex_editor_nonce' ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = __('Post delete error!', 'themerex');
			} else {
				$response['error'] = __('Post delete error!', 'themerex');
			}
		} else
			$response['error'] = __('Post delete denied!', 'themerex');

		echo json_encode($response);
		die();
	}
}




/* ========================= Include required files ============================== */


require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

require_once( (file_exists(get_stylesheet_directory().'/includes/_utils.php')    ? get_stylesheet_directory() : get_template_directory()) . '/includes/_utils.php' );
require_once( (file_exists(get_stylesheet_directory().'/includes/_wp_utils.php') ? get_stylesheet_directory() : get_template_directory()) . '/includes/_wp_utils.php' );
require_once( themerex_get_file_dir('/includes/_debug.php') );

require_once( themerex_get_file_dir('/includes/theme-lists.php') );
require_once( themerex_get_file_dir('/admin/theme-settings.php') );
if ( is_themerex_options_used() ) {
	require_once( themerex_get_file_dir('/admin/theme-options.php') );
}

require_once( themerex_get_file_dir('/includes/theme-customizer.php') );

require_once( themerex_get_file_dir('/includes/aq_resizer.php') );

require_once( themerex_get_file_dir('/admin/includes/type-attachment.php') );
require_once( themerex_get_file_dir('/admin/includes/type-category.php') );
require_once( themerex_get_file_dir('/admin/includes/type-post.php') );
require_once( themerex_get_file_dir('/admin/includes/type-page.php') );
require_once( themerex_get_file_dir('/admin/includes/type-reviews.php') );
require_once( themerex_get_file_dir('/admin/includes/type-woocommerce.php') );
require_once( themerex_get_file_dir('/admin/includes/type-tribe-events.php') );

if ( get_theme_option('custom_menu')=='yes' ) {
	require_once( themerex_get_file_dir('/admin/tools/custom_menu/custom_menu.php') );
}

require_once( themerex_get_file_dir('/shortcodes/shortcodes.php') );

require_once( themerex_get_file_dir('/widgets/widget-top10.php') );
require_once( themerex_get_file_dir('/widgets/widget-popular-posts.php') );
require_once( themerex_get_file_dir('/widgets/widget-recent-posts.php') );
require_once( themerex_get_file_dir('/widgets/widget-recent-reviews.php') );
require_once( themerex_get_file_dir('/widgets/widget-flickr.php') );
require_once( themerex_get_file_dir('/widgets/widget-twitter2.php') );
require_once( themerex_get_file_dir('/widgets/widget-advert.php') );
require_once( themerex_get_file_dir('/widgets/widget-socials.php') );
require_once( themerex_get_file_dir('/widgets/widget-categories.php') );
require_once( themerex_get_file_dir('/widgets/widget-calendar.php') );
require_once( themerex_get_file_dir('/widgets/qrcode/widget-qrcode.php') );

if ( is_admin() ) {
	if ( get_theme_option('admin_update_notifier')=='yes' ) {
		require_once( themerex_get_file_dir('/admin/tools/update-notifier.php') );
	}
	if ( get_theme_option('admin_emailer')=='yes' ) {
		require_once( themerex_get_file_dir('/admin/tools/emailer/emailer.php') );
	}
	if ( get_theme_option('admin_po_composer')=='yes' ) {
		require_once( themerex_get_file_dir('/admin/tools/po_composer/po_composer.php') );
	}
	if ( get_theme_option('admin_dummy_data')=='yes' && file_exists(themerex_get_file_dir('/admin/tools/importer/importer.php')) ) {
		require_once( themerex_get_file_dir('/admin/tools/importer/importer.php') );
	}
	require_once( themerex_get_file_dir('/admin/tools/tgm/class-tgm-plugin-activation.php') );
}
?>