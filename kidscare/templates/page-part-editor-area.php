<?php
//====================================== Editor area ========================================
if ($post_data['post_edit_enable']) {
	require_once( themerex_get_file_dir('/admin/theme-options.php') );
	wp_register_script( 'wp-color-picker', get_site_url().'/wp-admin/js/color-picker.min.js', array('jquery'), '1.0', true);
	themerex_enqueue_style ( 'fontello-admin',        themerex_get_file_url('/admin/css/fontello/css/fontello-admin.css'), array(), null);
	themerex_enqueue_style ( 'frontend-editor-style', themerex_get_file_url('/js/editor/_editor.css'), array('main-style'), null );
	themerex_enqueue_script( 'frontend-editor',       themerex_get_file_url('/js/editor/_editor.js'),  array(), null, true );
	themerex_options_load_scripts();
	themerex_options_prepare_js($post_data['post_type']=='page' ? 'page' : 'post');
	themerex_shortcodes_load_scripts();
	themerex_shortcodes_prepare_js();
	?>
	<div id="frontend_editor">
		<div id="frontend_editor_inner">
			<form method="post">
				<label id="frontend_editor_post_title_label" for="frontend_editor_post_title"><?php _e('Title', 'themerex'); ?></label>
				<input type="text" name="frontend_editor_post_title" id="frontend_editor_post_title" value="<?php echo esc_attr($post_data['post_title']); ?>" />
				<?php
				$ajax_nonce = wp_create_nonce('themerex_editor_nonce');
				$ajax_url = admin_url('admin-ajax.php');
				wp_editor($post_data['post_content_original'], 'frontend_editor_post_content', array(
					'wpautop' => true,
					'textarea_rows' => 16
				));
				?>
				<label id="frontend_editor_post_excerpt_label" for="frontend_editor_post_excerpt"><?php _e('Excerpt', 'themerex'); ?></label>
				<textarea name="frontend_editor_post_excerpt" id="frontend_editor_post_excerpt"><?php echo htmlspecialchars($post_data['post_excerpt_original']); ?></textarea>
				<input type="button" id="frontend_editor_button_save" value="<?php echo esc_attr(__('Save', 'themerex')); ?>" />
				<input type="button" id="frontend_editor_button_cancel" value="<?php echo esc_attr(__('Cancel', 'themerex')); ?>" />
				<input type="hidden" id="frontend_editor_post_id" name="frontend_editor_post_id" value="<?php echo esc_attr($post_data['post_id']); ?>" />
			</form>
			<script type="text/javascript">
				var THEMEREX_EDITOR_ajax_nonce = "<?php echo ($ajax_nonce); ?>";
				var THEMEREX_EDITOR_ajax_url   = "<?php echo esc_url($ajax_url); ?>";
				var THEMEREX_EDITOR_caption_cancel = "<?php _e('Cancel', 'themerex'); ?>";
				var THEMEREX_EDITOR_caption_close  = "<?php _e('Close', 'themerex'); ?>";
			</script>
		</div>
	</div>
	<?php
}
?>
