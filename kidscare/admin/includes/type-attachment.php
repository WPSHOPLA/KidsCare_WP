<?php
// New taxonomy register for attachmenta
add_action( 'init', 'themerex_attachments_taxonomy_register' );
function themerex_attachments_taxonomy_register() {
	$labels = array(
		'name'              => __('Media Folders', 'themerex'),
		'singular_name'     => __('Media Folder', 'themerex'),
		'search_items'      => __('Search Media Folders', 'themerex'),
		'all_items'         => __('All Media Folders', 'themerex'),
		'parent_item'       => __('Parent Media Folder', 'themerex'),
		'parent_item_colon' => __('Parent Media Folder:', 'themerex'),
		'edit_item'         => __('Edit Media Folder', 'themerex'),
		'update_item'       => __('Update Media Folder', 'themerex'),
		'add_new_item'      => __('Add New Media Folder', 'themerex'),
		'new_item_name'     => __('New Media Folder Name', 'themerex'),
		'menu_name'         => __('Media Folders', 'themerex'),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true
	);

	register_taxonomy( 'media_folder', 'attachment', $args );
}


// Add folders in ajax query
//----------------------------------------------------------------------------------
add_filter('ajax_query_attachments_args', 'themerex_ajax_query_attachments_args');
function themerex_ajax_query_attachments_args($query) {
	if (isset($query['post_mime_type'])) {
		$v = $query['post_mime_type'];
		if (themerex_substr($v, 0, 13)=='media_folder.') {
			unset($query['post_mime_type']);
			if (themerex_strlen($v) > 13)
				$query['media_folder'] = themerex_substr($v, 13);
			else {
				$list_ids = array();
				$terms = getTermsByTaxonomy(array('media_folder'));
				if (count($terms) > 0) {
					foreach ($terms as $term) {
						$list_ids[] = $term->term_id;
					}
				}
				if (count($list_ids) > 0) {
					$query['tax_query'] = array(
						array(
							'taxonomy' => 'media_folder',
							'field' => 'id',
							'terms' => $list_ids,
							'operator' => 'NOT IN'
						)
					);
				}
			}
		}
	}
	return $query;
}

// Add folders in filters for js view
//------------------------------------------------------------------
add_filter('media_view_settings', 'themerex_media_view_filters');
function themerex_media_view_filters($settings, $post=null) {
	$taxes = array('media_folder');
	foreach ($taxes as $tax) {
		//$terms = get_terms($tax);
		$terms = getTermsByTaxonomy(array($tax));
		if (count($terms) > 0) {
			$settings['mimeTypes'][$tax.'.'] = __('Media without folders', 'themerex');
			$settings['mimeTypes'] = themerex_array_merge($settings['mimeTypes'], getTermsHierarchicalList($terms, array(
				'prefix_key' => 'media_folder.',
				'prefix_level' => '-'
				)
			));
		}
	}
	return $settings;
}

// Add folders list in js view compat area
//--------------------------------------------------------------------------
add_filter('attachment_fields_to_edit', 'themerex_media_view_compat');
function themerex_media_view_compat($form_fields, $post=null) {
	static $terms = null, $id = 0;
	if (isset($form_fields['media_folder'])) {
		$field = $form_fields['media_folder'];
		if (!$terms) {
			$terms = getTermsByTaxonomy(array('media_folder'));
			$terms = getTermsHierarchicalList($terms, array(
				'prefix_key' => 'media_folder.',
				'prefix_level' => '-'
				));
		}
		$values = array_map('trim', explode(',', $field['value']));
		$readonly = ''; //! $user_can_edit && ! empty( $field['taxonomy'] ) ? " readonly='readonly' " : '';
		$required = !empty($field['required']) ? '<span class="alignright"><abbr title="required" class="required">*</abbr></span>' : '';
		$aria_required = !empty($field['required']) ? " aria-required='true' " : '';
		$html = '';
		if (count($terms) > 0) {
			foreach ($terms as $slug=>$name) {
				$id++;
				$slug = themerex_substr($slug, 13);
				$html .= ($html ? '<br />' : '') . '<input type="checkbox" class="text" id="media_folder_'.$id.'" name="media_folder_' . esc_attr($slug) . '" value="' . esc_attr( $slug ) . '"' . (in_array($slug, $values) ? ' checked="checked"' : '' ) . ' ' . $readonly . ' ' . $aria_required . ' /><label for="media_folder_'.$id.'"> ' . $name . '</label>';
			}
		}
		$form_fields['media_folder']['input'] = 'media_folder_input';
		$form_fields['media_folder']['media_folder_input'] = '<div class="media_folder_selector">' . $html . '</div>';
	}
	return $form_fields;
}

// Prepare media folders for save
add_filter( 'attachment_fields_to_save', 'themerex_media_save_compat');
function themerex_media_save_compat($post=null, $attachment_data=null) {
	if (!empty($post['ID']) && ($id = intval($post['ID'])) > 0) {
		$folders = array();
		$from_media_library = !empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder']);
		// From AJAX query
		if (!$from_media_library) {
			foreach ($_REQUEST as $k => $v) {
				if (themerex_substr($k, 0, 12)=='media_folder')
					$folders[] = $v;
			}
		} else {
			if (count($folders)==0) {
				if (!empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder'])) {
					foreach ($_REQUEST['tax_input']['media_folder'] as $k => $v) {
						if ((int)$v > 0)
							$folders[] = $v;
					}
				}
			}
		}
		if (count($folders) > 0) {
			foreach ($folders as $k=>$v) {
				if ((int) $v > 0) {
					$term = get_term_by('id', $v, 'media_folder');
					$folders[$k] = $term->slug;
				}
			}
		} else
			$folders = null;
		// Save folders list only from AJAX
		if (!$from_media_library)
			wp_set_object_terms( $id, $folders, 'media_folder', false );
	}
	return $post;
}
?>
