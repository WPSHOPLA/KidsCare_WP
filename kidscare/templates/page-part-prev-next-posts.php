<?php
if (!$post_data['post_protected']) {
	if (!empty($opt['dedicated'])) {
		echo ($opt['dedicated']);
	} else if ($post_data['post_thumb']) {
		$page_style = get_custom_option('single_style');
		?>
		<div class="thumb imgNav"<?php echo ($page_style=='single-portfolio-fullscreen' ? ' style="background-image:url('.$post_data['post_attachment'].');"' : ''); ?>>
			<?php 
			if ($page_style!='single-portfolio-fullscreen') { 
			?>
				<img alt="" src="<?php echo esc_url($post_data['post_attachment']); ?>">
			<?php
			}
			$cur = get_post();
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
				'ignore_sticky_posts' => 1
			);
			$args = addPostsAndCatsInQuery($args, '', join(',', $post_data['post_categories_ids']));
			$args = addSortOrderInQuery($args);
			query_posts( $args );
			$prev = $next = null;
			$found = false;
			while ( have_posts() ) { the_post();
				if (!$found) {
					if ($cur->ID == get_the_ID())
						$found = true;
					else
						$prev = get_post();
				} else {
					$next = get_post();
					break;
				}
			}
			wp_reset_query();
			wp_reset_postdata();

			if ( $prev ) {
				$link = get_permalink($prev->ID).'#topOfPage';
				$desc = getShortString($prev->post_title, 30);
				?>
				<a class="itemPrev" href="<?php echo esc_url($link); ?>">
					<span class="itInf">
						<span class="titleItem"><?php _e('Previous item', 'themerex'); ?></span>
						<?php echo esc_html($desc); ?>
					</span>
				</a>
				<?php
			}
			if ( $next ) {
				$link = get_permalink( $next->ID ).'#topOfPage';
				$desc = getShortString($next->post_title, 30);
				?>
				<a class="itemNext" href="<?php echo esc_url($link); ?>">
					<span class="itInf">
						<span class="titleItem"><?php _e('Next item', 'themerex'); ?></span>
						<?php echo esc_html($desc); ?>
					</span>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
}
?>
