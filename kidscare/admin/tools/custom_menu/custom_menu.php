<?php
/**
 * Create additional attributes in WordPress menus
 */

class themerex_custom_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/	 

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );

		if (is_admin()) {
			// edit menu walker
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker'), 10, 2 );
			// save menu custom fields
			add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_nav_fields'), 10, 3 );
			// load admin scripts and styles
			add_action('admin_enqueue_scripts', array( $this, 'admin_scripts'));
		} else {
			// load frontend scripts and styles
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts') );
		}

	} // end constructor
 		
	function admin_scripts(){
		themerex_enqueue_style( 'custom-menu-admin-style',  themerex_get_file_url('/admin/tools/custom_menu/custom_menu_admin.css'), array(), null );
		themerex_enqueue_script('custom-menu-admin-script', themerex_get_file_url('/admin/tools/custom_menu/custom_menu_admin.js'),  array('jquery'), null, true );
		themerex_enqueue_script('theme-admin-script',       themerex_get_file_url('/js/_admin.js'),  array('jquery'), null, true );
	}
	
	function frontend_scripts(){
		themerex_enqueue_style(  'custom-menu-style',  themerex_get_file_url('/admin/tools/custom_menu/custom_menu.css'), array(), null );
		themerex_enqueue_script( 'custom-menu-script', themerex_get_file_url('/admin/tools/custom_menu/custom_menu.js'),  array('jquery'), null, true );
	}
	
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	*/
	function add_custom_nav_fields( $menu_item ) {
		
		$item_custom_data = get_post_meta( $menu_item->ID, '_item_custom_data', true );				

	    $menu_item->item_thumb 		=	isset($item_custom_data['item_thumb']) ? $item_custom_data['item_thumb'] : '';
	    $menu_item->top_level_type 	=	isset($item_custom_data['top_level_type']) ? $item_custom_data['top_level_type'] : '';
	    $menu_item->item_icon_class =	isset($item_custom_data['item_icon_class']) ? $item_custom_data['item_icon_class'] : '';
	    $menu_item->auto_items_count=	isset($item_custom_data['auto_items_count']) ? $item_custom_data['auto_items_count'] : '';
	    $menu_item->top_menu_view 	=	isset($item_custom_data['top_menu_view']) ? $item_custom_data['top_menu_view'] : '';
	    $menu_item->item_sorting_by =	isset($item_custom_data['item_sorting_by']) ? $item_custom_data['item_sorting_by'] : '';
	    $menu_item->post_types_list =	isset($item_custom_data['post_types_list']) ? $item_custom_data['post_types_list'] : '';
	    $menu_item->categories 		=	isset($item_custom_data['cat_list']) ? $item_custom_data['cat_list'] : '';
	    
	    return $menu_item;	    
	}
	
	function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
		
		$item_custom_data = array();
		
	    // Check if element is properly sent
	    if ( isset( $_REQUEST['item_thumb_holder'][$menu_item_db_id]) ) {
	        $item_custom_data['item_thumb'] = $_REQUEST['item_thumb_holder'][$menu_item_db_id];
	    }
	    if ( isset( $_REQUEST['top_level_type'][$menu_item_db_id]) ) {
	        $item_custom_data['top_level_type'] = $_REQUEST['top_level_type'][$menu_item_db_id];
	    }
	    if ( isset( $_REQUEST['item_icon_class'][$menu_item_db_id]) ) {
	        $item_custom_data['item_icon_class'] = $_REQUEST['item_icon_class'][$menu_item_db_id];
	    }
	    if ( isset( $_REQUEST['auto_items_count'][$menu_item_db_id]) ) {
	        $item_custom_data['auto_items_count'] = $_REQUEST['auto_items_count'][$menu_item_db_id];
	    }
	    if ( isset( $_REQUEST['top_menu_view'][$menu_item_db_id]) ) {
	        $item_custom_data['top_menu_view'] = $_REQUEST['top_menu_view'][$menu_item_db_id];
	    }
	    if ( isset( $_REQUEST['item_sorting_by'][$menu_item_db_id]) ) {
	        $item_custom_data['item_sorting_by'] = $_REQUEST['item_sorting_by'][$menu_item_db_id];
	    }
	    if ( isset( $_REQUEST['post_types_list'][$menu_item_db_id]) ) {
	        $item_custom_data['post_types_list'] = $_REQUEST['post_types_list'][$menu_item_db_id];
	    }
	    if ( isset( $_REQUEST['cat_list'][$menu_item_db_id]) ) {
	        $item_custom_data['cat_list'] = $_REQUEST['cat_list'][$menu_item_db_id];
	    }
       	update_post_meta( $menu_item_db_id, '_item_custom_data', $item_custom_data );
	    
	}
	
	function edit_walker($walker,$menu_id) {
	
	    return 'Walker_Nav_Menu_Edit_Custom';
	    
	}
}

// instantiate plugin's class
global $themerex_custom_menu;
$themerex_custom_menu = new themerex_custom_menu();

if (is_admin()) {
	require_once( 'custom_walker_admin.php' );
} else {
	require_once( 'custom_walker.php' );
}
