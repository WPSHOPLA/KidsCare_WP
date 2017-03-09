<?php
/* ========================= Inherited properties for categories ============================== */

// Return category's inherited property value (from parent categories)
function get_category_inherited_property($id, $prop, $defa='') {
	if ((int) $id == 0) {
		$cat = get_term_by( 'slug', $id, 'category', ARRAY_A);
		$id = $cat['term_id'];
	}
	$val = $defa;
	do {
		if ($props = category_custom_fields_get($id)) {
			if (isset($props[$prop]) && !empty($props[$prop]) && !is_inherit_option($props[$prop])) {
				$val = $props[$prop];
				break;
			}
		}
		$cat = get_term_by( 'id', $id, 'category', ARRAY_A);
		$id = $cat['parent'];
	} while ($id);
	return $val;
}

// Return all inherited category properties value (from parent categories)
function get_category_inherited_properties($id) {
	if ((int) $id == 0) {
		$cat = get_term_by( 'slug', $id, 'category', ARRAY_A);
		$id = $cat['term_id'];
	}
	$val = array('category_id'=>$id);
	do {
		if ($props = category_custom_fields_get($id)) {
			foreach($props as $prop_name=>$prop_value) {
				if (!isset($val[$prop_name]) || empty($val[$prop_name]) || is_inherit_option($val[$prop_name])) {
					$val[$prop_name] = $prop_value;
				}
			}
		}
		$cat = get_term_by( 'id', $id, 'category', ARRAY_A);
		$id = $cat['parent'];
	} while ($id);
	return $val;
}

// Return all inherited properties value (from parent categories) for list categories
function get_categories_inherited_properties($cats) {
	$cat_options = array();
	if ($cats) {
		foreach ($cats as $cat) {
			$new_options = get_category_inherited_properties($cat['term_id']);
			foreach ($new_options as $k=>$v) {
				if (!empty($v) && !is_inherit_option($v) && (!isset($cat_options[$k]) || empty($cat_options[$k]) || is_inherit_option($cat_options[$k])))
					$cat_options[$k] = $v;
			}
		}
	}
	return $cat_options;
}


/* ========================= Custom fields for categories ============================== */

// Get category custom fields
function category_custom_fields_get($tax = null) {  
	$t_id = is_object($tax) ? $tax->term_id : $tax; 					// Get the ID of the term you're editing
	if ((int) $t_id == 0) {
		$cat = get_term_by( 'slug', $t_id, 'category', OBJECT);
		$t_id = $cat!==false ? $cat->term_id : 0;
	}
	return $t_id ? get_option( "themerex_options_category_{$t_id}" ) : false;		// Do the check  
}

// Get category custom fields
function category_custom_fields_set($term_id, $term_meta) {  
	update_option( "themerex_options_category_{$term_id}", $term_meta );  
}


// Add the fields to the "category" taxonomy, using our callback function  
add_action( 'category_edit_form_fields', 'category_custom_fields_show', 10, 1 );  
add_action( 'category_add_form_fields', 'category_custom_fields_show', 10, 1 );  
function category_custom_fields_show($tax = null) {  
	global $THEMEREX_options;
?>  
	<table border="0" cellpadding="0" cellspacing="0" class="form-table">
    <tr class="form-field" valign="top">  
	    <td span="2">
	<div class="section section-info ">
		<h3 class="heading"><?php _e('Custom settings for this category (and nested):', 'themerex'); ?></h3>
		<div class="option">
			<div class="controls">
				<div class="info">
					<?php _e('Select parameters for showing posts from this category and all nested categories.', 'themerex'); ?><br />
					<?php _e('Attention: In each nested category you can override this settings.', 'themerex'); ?>
				</div>
			</div>
		</div>
	</div>
<?php 
    // Use nonce for verification
    echo '<input type="hidden" name="meta_box_category_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	$custom_options = category_custom_fields_get($tax);

	themerex_options_load_scripts();
	themerex_options_prepare_js('category');

	themerex_options_page_start(array(
		'data' => $THEMEREX_options,
		'add_inherit' => true,
		'show_page_layout' => false,
		'override' => 'category'
		));

	foreach ($THEMEREX_options as $option) { 
		if (!isset($option['override']) || !in_array('category', explode(',', $option['override']))) continue;

		$id = isset($option['id']) ? $option['id'] : '';
        $meta = isset($custom_options[$id]) ? $custom_options[$id] : '';

		themerex_options_show_field($option, $meta);
	}

	themerex_options_page_stop();
?>
		</td>
	</tr>
	</table>
<?php
} 



  
// Save the changes made on the "category" taxonomy, using our callback function  
add_action( 'edited_category', 'category_custom_fields_save', 10, 1 );
add_action( 'created_category', 'category_custom_fields_save', 10, 1 );
function category_custom_fields_save( $term_id=0 ) {  
    global $THEMEREX_options;
    
    // verify nonce
    if (!isset($_POST['meta_box_category_nonce']) || !wp_verify_nonce($_POST['meta_box_category_nonce'], basename(__FILE__))) {
        return $term_id;
    }

	$custom_options = category_custom_fields_get($term_id);

	if (themerex_options_merge_new_values($THEMEREX_options, $custom_options, $_POST, 'save', 'category')) 
		category_custom_fields_set($term_id, $custom_options);
}
?>