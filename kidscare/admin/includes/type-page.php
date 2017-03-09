<?php
//remove_post_type_support( 'page', 'comments' );
add_post_type_support( 'page', array('excerpt', 'comments') );
//register_taxonomy( 'tags', 'page' );

$THEMEREX_meta_box_page = array(
	'id' => 'my-meta-box',
	'title' => __('Page Options', 'themerex'),
	'page' => 'page',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array()
);
add_action('admin_menu', 'themerex_add_box_page');

// Add meta box
function themerex_add_box_page() {
	global $THEMEREX_meta_box_page;
	add_meta_box($THEMEREX_meta_box_page['id'], $THEMEREX_meta_box_page['title'], 'show_meta_box_page', $THEMEREX_meta_box_page['page'], $THEMEREX_meta_box_page['context'], $THEMEREX_meta_box_page['priority']);
}

// Callback function to show fields in meta box
function show_meta_box_page() {
    global $THEMEREX_meta_box_page, $post, $THEMEREX_options;

    // Use nonce for verification
    echo '<input type="hidden" name="meta_box_page_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	$custom_options = get_post_meta($post->ID, 'post_custom_options', true);

	$page_options = array_merge($THEMEREX_options, $THEMEREX_meta_box_page['fields']);
	
	themerex_options_load_scripts();
	themerex_options_prepare_js('page');

	themerex_options_page_start(array(
		'data' => $page_options,
		'add_inherit' => true,
		'show_page_layout' => false,
		'override' => 'page'
		));

	foreach ($page_options as $option) { 
		if (!isset($option['override']) || !in_array('page', explode(',', $option['override']))) continue;

		$id = isset($option['id']) ? $option['id'] : '';
        $meta = isset($custom_options[$id]) ? $custom_options[$id] : '';

		themerex_options_show_field($option, $meta);
	}

	themerex_options_page_stop();
}

// Save data from meta box
add_action('save_post', 'themerex_save_data_page');
function themerex_save_data_page($post_id) {
    global $THEMEREX_meta_box_page, $THEMEREX_options;

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    
    // verify nonce
    if (!isset($_POST['meta_box_page_nonce']) || !wp_verify_nonce($_POST['meta_box_page_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

	$custom_options = array();

	$page_options = array_merge($THEMEREX_options, $THEMEREX_meta_box_page['fields']);

	if (themerex_options_merge_new_values($page_options, $custom_options, $_POST, 'save', 'page'))
		update_post_meta($post_id, 'post_custom_options', $custom_options);
}
?>