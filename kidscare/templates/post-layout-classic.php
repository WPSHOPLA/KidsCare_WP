<?php
$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
$columns = max(1, min(4, (int) themerex_substr($opt['style'], -1)));
?>
<article class="isotopeElement <?php 
	echo 'post_format_'.$post_data['post_format'] 
		. ($opt['number']%2==0 ? ' even' : ' odd') 
		. ($opt['number']==0 ? ' first' : '') 
		. ($opt['number']==$opt['posts_on_page'] ? ' last' : '')
		. ($opt['add_view_more'] ? ' viewmore' : '') 
		. ($opt['filters']!='' 
			? ' flt_'.join(' flt_', $opt['filters']=='categories' ? $post_data['post_categories_ids'] : $post_data['post_tags_ids'])
			: '');
	?>">
	<div class="isotopePadding">
		<?php 
		if ($post_data['post_video']) {
			echo getVideoFrame($post_data['post_video'], $post_data['post_thumb'], true);
		} else if ($post_data['post_thumb'] && ($post_data['post_format']!='gallery' || !$post_data['post_gallery'] || get_custom_option('gallery_instead_image')=='no')) { 
		?>
		<div class="thumb hoverIncrease" data-image="<?php echo esc_attr($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>"><?php echo balanceTags($post_data['post_thumb']); ?></div>
		<?php
		} else if ($post_data['post_gallery']) {
			echo balanceTags($post_data['post_gallery']);
		}
		if ($show_title) {
			if (!isset($opt['links']) || $opt['links']) {
				?><h4><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h4><?php
			} else {
				?><h4><?php echo esc_html($post_data['post_title']); ?></h4><?php
			}
		}
		?>
        <p>
		<?php echo in_array($post_data['post_format'], array('quote', 'link', 'chat')) ? $post_data['post_excerpt'] : getShortString($post_data['post_excerpt'], isset($opt['descr']) ? $opt['descr'] : get_custom_option('post_excerpt_maxlength'.($columns>1 ? '_masonry' : ''))); ?>
		</p>
		<?php if (!isset($opt['info']) || $opt['info']) { ?>
		<div class="masonryInfo"><?php _e('Posted ', 'themerex'); ?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date"><?php echo balanceTags($post_data['post_date']); ?></a></div>
		<div class="masonryMore">
			<?php
			$postinfo_buttons = array('more', 'comments');
			if ($columns < 4)
				$postinfo_buttons[] = 'views';
			if ($columns < 3) {
				$postinfo_buttons[] = 'likes';
				//$postinfo_buttons[] = 'share';
				$postinfo_buttons[] = 'rating';
			}
			require(themerex_get_file_dir('/templates/page-part-postinfo.php')); 
			?>
		</div>
		<?php } ?>
		<span class="inlineShadow"></span>
	</div>
</article>
