<?php
$post_data['post_views']++;

$avg_author = 0;
$avg_users  = 0;
if (!$post_data['post_protected'] && $opt['reviews'] && get_custom_option('show_reviews')=='yes') {
	$avg_author = $post_data['post_reviews_author'];
	$avg_users  = $post_data['post_reviews_users'];
}

$show_title = get_custom_option('show_post_title')=='yes' && (get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));

startWrapper(array(
'<div class="itemscope" itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article').'">',
'	<section class="'.join(' ', get_post_class('post post_format_'.$post_data['post_format'].' post'.$opt['post_class'].(get_custom_option("show_post_author") == 'yes' || get_custom_option("show_post_related") == 'yes' || get_custom_option("show_post_comments") == 'yes' ? ' hrShadow' : ' no_margin'))).'">',
'		<article class="post_content">'
));
			if ( get_custom_option('show_post_info')=='yes') {
			?>
			<div class="post_info infoPost">
				<?php if ($post_data['post_edit_enable'] || $post_data['post_delete_enable']) { ?>
					<span class="frontend_editor_buttons">
						<?php if ($post_data['post_edit_enable']) { ?>
						<span class="squareButton light ico"><a id="frontend_editor_icon_edit" class="icon-brush" title="<?php _e('Edit post', 'themerex'); ?>" href="#"><?php _e('Edit', 'themerex'); ?></a></span>
						<?php } ?>
						<?php if ($post_data['post_delete_enable']) { ?>
						<span class="squareButton light ico"><a id="frontend_editor_icon_delete" class="icon-trash-1" title="<?php _e('Delete post', 'themerex'); ?>" href="#"><?php _e('Delete', 'themerex'); ?></a></span>
						<?php } ?>
					</span>
				<?php } ?>
				<?php _e('Posted ', 'themerex'); ?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date date updated" itemprop="datePublished" content="<?php echo get_the_date('Y-m-d'); ?>"><?php echo balanceTags($post_data['post_date']); ?></a>
				<span class="separator">|</span>
				<?php _e('by', 'themerex'); ?> <span class="vcard" itemprop="author"><a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_author fn" rel="author"><?php echo esc_html($post_data['post_author']); ?></a></span>
				<?php if ($post_data['post_categories_links']!='') { ?>
				<span class="separator">|</span>
				<span class="post_cats"><?php _e('in', 'themerex'); ?> <?php echo balanceTags($post_data['post_categories_links']); ?></span>
				<?php } ?>
			</div>
			<?php 
			}
		
			if ($show_title && $opt['location'] == 'center') {
			?>
			<h1 itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php echo esc_html($post_data['post_title']); ?></h1>
			<?php 
			}
			if (!$post_data['post_protected']) {
				if (!empty($opt['dedicated'])) {
					echo balanceTags($opt['dedicated']);
				} else if (get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'] && $post_data['post_format']!='gallery' && $post_data['post_format']!='image') {
					?>
					<div class="sc_section columns<?php echo ($opt['location']=='center' ? '2_3' : '1_2'); ?> post_thumb thumb">
						<?php echo balanceTags($post_data['post_thumb']); ?>
					</div>
					<?php 
				}
			}
			
		
			if ($show_title && $opt['location'] != 'center') {
			?>
			<h1 itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php echo esc_html($post_data['post_title']); ?></h1>
			<?php 
			}
		
			require(themerex_get_file_dir('/templates/page-part-reviews-block.php'));
			
			startWrapper('<div class="post_text_area" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');
			
			// Post content
			if ($post_data['post_protected']) { 
				echo balanceTags($post_data['post_excerpt']);
				echo get_the_password_form(); 
			} else {
				echo sc_gap_wrapper($post_data['post_content']); 
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
						$postinfo_buttons = array('comments', 'views', 'likes', 'share', 'rating', 'markup');
						require(themerex_get_file_dir('/templates/page-part-postinfo.php'));
						?>
					</div>
					<?php } ?>
					<?php if ( get_custom_option('show_post_tags')=='yes' && $post_data['post_tags_links'] != '') { ?>
					<div class="infoPost">
						<?php _e('Tags:', 'themerex'); ?>
						<?php echo balanceTags($post_data['post_tags_links']); ?>
					</div>
					<?php } ?>
				</div>
				<?php 
			} 
			
			stopWrapper(3);
			?>
			
			<!-- </div> --><!-- .post_text_area -->
<!--
		</article>
	</section>
-->
	<?php	
	if (!$post_data['post_protected']) {
		if ($post_data['post_edit_enable']) {
			require(themerex_get_file_dir('/templates/page-part-editor-area.php'));
		}
		require(themerex_get_file_dir('/templates/page-part-author-info.php'));
		require(themerex_get_file_dir('/templates/page-part-related-posts.php'));
		require(themerex_get_file_dir('/templates/page-part-comments.php'));
	}
	?>

<?php stopWrapper(); ?><!-- </div> --><!-- .itemscope -->

<?php require(themerex_get_file_dir('/templates/page-part-views-counter.php')); ?>
