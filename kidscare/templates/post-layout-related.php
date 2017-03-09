<?php
$show_title = true;	//get_custom_option('show_post_title', null, $post_data['post_id'])=='yes';
require(themerex_get_file_dir('/templates/page-part-reviews-summary.php'));
?>
<article class="columns1_<?php echo esc_attr($opt['posts_visible']); ?> column_item_<?php echo esc_attr($opt['number']); ?>">

	<?php 
	if ($post_data['post_video']) {
		echo getVideoFrame($post_data['post_video'], $post_data['post_thumb'], false);
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
		echo balanceTags($reviewsBlock);
	}

	if (!in_array($post_data['post_format'], array('quote', 'link', 'chat'))) {
		$post_data['post_excerpt'] = getShortString($post_data['post_excerpt'], isset($opt['descr']) ? $opt['descr'] : get_custom_option('post_excerpt_maxlength_masonry'));
	}
	echo themerex_substr($post_data['post_excerpt'], 0, 2)!='<p' ? '<p>'.$post_data['post_excerpt'].'</p>' : $post_data['post_excerpt'];
	?>

	<?php if (!isset($opt['info']) || $opt['info']) { ?>
		<div class="relatedInfo">
			<?php _e('Posted ', 'themerex'); ?>
			<a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date"><?php echo balanceTags($post_data['post_date']); ?></a>
			<?php if (!empty($post_data['post_tags_links'])) { ?>
			<span class="separator">|</span> <span class="infoTags"><?php echo balanceTags($post_data['post_tags_links']); ?></span>
			<?php } ?>
		</div>
	
		<div class="relatedMore">
			<?php
			$postinfo_buttons = array('more', 'comments');
			if ($opt['posts_visible'] < 3)
				$postinfo_buttons[] = 'views';
			/*
			if ($opt['posts_visible'] < 3) {
				$postinfo_buttons[] = 'likes';
				$postinfo_buttons[] = 'share';
			}
			*/
			require(themerex_get_file_dir('/templates/page-part-postinfo.php')); 
			?>
		</div>
	<?php } ?>

</article>
