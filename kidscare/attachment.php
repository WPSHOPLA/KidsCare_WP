<?php
/**
Template Name: Attachment page
 */
get_header(); 

$counters = get_theme_option("blog_counters");
$blog_style = 'fullpost'; //get_custom_option('blog_style');

while ( have_posts() ) { the_post();

	// Move setPostViews to the javascript - counter will work under cache system
	if (get_theme_option('use_ajax_views_counter')=='no') {
		setPostViews(get_the_ID());
	}

	showPostLayout(
		array(
			'layout' => 'attachment',
			'thumb_size' => $blog_style,
			'sidebar' => !in_array(get_custom_option('show_sidebar_main'), array('none', 'fullwidth')),
			'categories_list' => false,
			'tags_list' => false
		)
	);

}

get_footer();
?>