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
'	<section class="' . join(' ', get_post_class('itemPage post post_format_'.$post_data['post_format'].' post'.$opt['post_class'] . (get_custom_option("show_post_author") == 'yes' || get_custom_option("show_post_related") == 'yes' || get_custom_option("show_post_comments") == 'yes' ? ' hrShadow' : ' no_margin'))) . '">',
'		<article class="post_content">'
));

			require(themerex_get_file_dir('/templates/page-part-prev-next-posts.php'));

			if ($show_title) {
				?>
				<h1 itemprop="<?php echo ($avg_author > 0 || $avg_users > 0 ? 'itemReviewed' : 'name'); ?>" class="post_title entry-title"><?php echo esc_html($post_data['post_title']); ?></h1>
				<?php
			}

			require(themerex_get_file_dir('/templates/page-part-reviews-block.php'));
			
			startWrapper('<div class="post_text_area" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');

			// Post content
			if ($post_data['post_protected']) { 
				echo balanceTags($post_data['post_excerpt']);
			} else {
				echo sc_gap_wrapper($post_data['post_content']); 
				wp_link_pages( array( 
					'before' => '<div class="nav_pages_parts"><span class="pages">' . __( 'Pages:', 'themerex' ) . '</span>', 
					'after' => '</div>',
					'link_before' => '<span class="page_num">',
					'link_after' => '</span>'
				) ); 
				if ( get_custom_option('show_post_info')=='yes') {
					?>
					<div class="itemInfo">
						<?php if ( get_custom_option('show_post_counters')=='yes') { ?>
							<div class="postSharing">
								<?php
								$postinfo_buttons = array('comments', 'views', 'likes', 'share', 'rating');
								require(themerex_get_file_dir('/templates/page-part-postinfo.php'));
								?>
							</div>
						<?php } ?>
						<div class="post_info infoPost">
							<?php _e('Posted ', 'themerex'); ?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date date updated" itemprop="datePublished" content="<?php echo get_the_date('Y-m-d'); ?>"><?php echo balanceTags($post_data['post_date']); ?></a>
							<?php if ($post_data['post_categories_links']!='') { ?>
								<span class="separator">|</span>
								<span class="post_cats"><?php echo balanceTags($post_data['post_categories_links']); ?></span>
							<?php } ?>
						</div>
					</div>
					<?php
				}
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
		require(themerex_get_file_dir('/templates/page-part-author-info.php'));
		require(themerex_get_file_dir('/templates/page-part-related-posts.php'));
		get_template_part('templates/page-part-comments');
	}
	?>
	
<?php stopWrapper(); ?><!-- </div> --><!-- .itemscope -->

<?php require(themerex_get_file_dir('/templates/page-part-views-counter.php')); ?>
