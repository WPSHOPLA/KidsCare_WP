<?php
$post_data['post_views']++;
?>

<section id="attachment_section" <?php post_class('post itemPage'.(!$post_data['post_protected'] && get_custom_option("show_post_comments") == 'yes' ? ' hrShadow' : '')); ?>>
	<div class="thumb imgNav">
		<img alt="" src="<?php echo esc_url($post_data['post_attachment']); ?>">
		<?php
		$post = get_post();
		$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID )
				break;
		}
		if ( isset( $attachments[ $k-1 ] ) ) {
			$link = get_permalink( $attachments[ $k-1 ]->ID ).'#topOfPage';
			$desc = getShortString(!empty($attachments[ $k-1 ]->post_excerpt) ? $attachments[ $k-1 ]->post_excerpt : $attachments[ $k-1 ]->post_title, 30);
			?>
			<a class="itemPrev" href="<?php echo esc_url($link); ?>">
				<span class="itInf">
					<span class="titleItem"><?php _e('Previous item', 'themerex'); ?></span>
					<?php echo balanceTags($desc); ?>
				</span>
			</a>
			<?php
		}
		if ( isset( $attachments[ $k+1 ] ) ) {
			$link = get_permalink( $attachments[ $k+1 ]->ID ).'#topOfPage';
			$desc = getShortString(!empty($attachments[ $k+1 ]->post_excerpt) ? $attachments[ $k+1 ]->post_excerpt : $attachments[ $k+1 ]->post_title, 30);
			?>
			<a class="itemNext" href="<?php echo esc_url($link); ?>">
				<span class="itInf">
					<span class="titleItem"><?php _e('Next item', 'themerex'); ?></span>
					<?php echo balanceTags($desc); ?>
				</span>
			</a>
			<?php
		}
		?>
	</div>

	<?php if ( get_custom_option('show_post_info')=='yes') { ?>
	<div class="post_info infoPost">
		<?php _e('Posted ', 'themerex'); ?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date"><?php echo esc_html($post_data['post_date']); ?></a>
		<span class="separator">|</span>
		<?php _e('by', 'themerex'); ?> <a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_author"><?php echo esc_html($post_data['post_author']); ?></a>
		<?php if ($post_data['post_categories_links']!='') { ?>
		<span class="separator">|</span>
		<span class="post_cats"><?php _e('in', 'themerex'); ?> <?php echo esc_html($post_data['post_categories_links']); ?></span>
		<?php } ?>
	</div>
	<?php } ?>

	<h1 class="post_title"><?php echo !empty($post_data['post_excerpt']) ? strip_tags($post_data['post_excerpt']) : $post_data['post_title']; ?></h1>

	<div class="post_text_area">
		<?php
		// Post content
		if ($post_data['post_protected']) { 
			echo balanceTags($post_data['post_excerpt']);
		} else {
			echo balanceTags($post_data['post_content']);
			wp_link_pages( array( 
				'before' => '<div class="nav_pages_parts"><span class="pages">' . __( 'Pages:', 'themerex' ) . '</span>', 
				'after' => '</div>',
				'link_before' => '<span class="page_num">',
				'link_after' => '</span>'
			) ); 
			?>
			<div class="tagsWrap">
				<?php if ( get_custom_option('show_post_counters')=='yes') { ?>
				<div class="postSharing">
					<?php
					$postinfo_buttons = array('comments', 'views', 'likes', 'share', 'rating');
					require(themerex_get_file_dir('/templates/page-part-postinfo.php'));
					?>
				</div>
				<?php } ?>
			</div>
			<?php 
		} 
		?>
	
	</div><!-- .post_text_area -->

</section><!-- #attachment_section -->

<?php	
if (!$post_data['post_protected']) {
	get_template_part('templates/page-part-comments');
}
?>

<?php require(themerex_get_file_dir('/templates/page-part-views-counter.php')); ?>
