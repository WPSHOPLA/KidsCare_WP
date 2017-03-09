<?php
//===================================== Related posts =====================================
if (get_custom_option("show_post_related") == 'yes') {
	$args = array( 
		'numberposts' => get_custom_option('post_related_count'),
		'post_type' => is_page() ? 'page' : 'post', 
		'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
		'post__not_in' => array($post_data['post_id']) 
	);
	if ($post_data['post_categories_links']) {
		$args['category__in'] = $post_data['post_categories_ids'];
	}
	
	// Uncomment this section if you want filter related posts on post formats
	if ($post_data['post_format'] != '' && $post_data['post_format'] != 'standard') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-' . $post_data['post_format']
			)
		);
	}
	
	$args = addSortOrderInQuery($args, get_custom_option('post_related_sort'), get_custom_option('post_related_order'));
	$recent_posts = wp_get_recent_posts( $args, OBJECT );
	if (is_array($recent_posts) && count($recent_posts) > 0) {

		// magnific & pretty
		themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
		themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
		// Load PrettyPhoto if it selected in Theme Options
		if (get_theme_option('popup_engine')=='pretty') {
			themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
			themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		}
	?>
		<section class="relatedWrap<?php echo get_custom_option("show_post_comments") == 'yes' ? ' hrShadow' : ''; ?>">
			<h2><?php _e('Related posts', 'themerex'); ?></h2>
			<div class="relatedPostWrap">
				<div class="columnsWrap">
				<?php
				$i=0;
				foreach( $recent_posts as $recent ) {
					$i++;
					showPostLayout(
						array(
							'layout' => 'related',
							'number' => $i,
							'add_view_more' => false,
							'posts_on_page' => get_custom_option('post_related_count'),
							'posts_visible' => max(1, min(4, count($recent_posts))),
							'thumb_size' => 'classic' . max(1, min(4, count($recent_posts))),
							'strip_teaser' => false,
							'sidebar' => !in_array(get_custom_option('show_sidebar_main'), array('none', 'fullwidth')),
							'categories_list' => false,
							'tags_list' => true
						),
						null,
						$recent
					);
				}
				?>
			   </div>
			</div>
		</section>
		<?php
	}
}
?>
