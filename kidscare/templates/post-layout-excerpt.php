<?php
/*
 * The template for displaying one article of blog streampage with style "Excerpt"
*/
$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
$post_info = '
	<div class="post_info infoPost">
		' . __('Posted', 'themerex') . ' <a href="' . $post_data['post_link'] . '" class="post_date">' . $post_data['post_date'] . '</a>
		<span class="separator">|</span>
		' . __('by', 'themerex') . ' <a href="' . $post_data['post_author_url'] . '" class="post_author">' . $post_data['post_author'] . '</a>
		' . ($post_data['post_categories_links']!='' 
			? '<span class="separator">|</span><span class="post_cats">' . __('in', 'themerex') . ' ' . $post_data['post_categories_links'] . '</span>'
			: '') . '
	</div>
	';
?>
<?php if (in_shortcode_blogger(true)) { ?>
<div class="<?php echo 'post'.$opt['post_class'] . ($opt['number'] < $opt['posts_on_page'] ? ' hrShadow marginBottom' : '') . ($opt['number']%2==0 ? ' even' : ' odd') . ($opt['number']==0 ? ' first' : '') . ($opt['number']==$opt['posts_on_page']? ' last' : '') . ($opt['add_view_more'] ? ' viewmore' : ''); ?>">
<?php } else { 
	$post_classes = get_post_class('post_format_'.$post_data['post_format'].($post_data['post_type']!='post' ? ' post' : '').' post'.$opt['post_class'].' hrShadow'.($opt['number']%2==0 ? ' even' : ' odd') . ($opt['number']==0 ? ' first' : '') . ($opt['number']==$opt['posts_on_page']? ' last' : '') . ($opt['add_view_more'] ? ' viewmore' : ''));
?>
<article class="<?php echo join(' ', $post_classes) . (!in_array('post', $post_classes) ? ' post' : ''); ?>">
<?php if ($post_data['post_flags']['sticky']) {?><span class="sticky_label"></span><?php } ?>
<?php } ?>

	<?php 
		if (!$opt['sidebar']) 
			echo balanceTags($post_info);
	?>

	<?php if ($show_title && $opt['location'] == 'center') { ?>
	<h2 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h2>
	<?php } ?>
	
	<?php
	if (!$post_data['post_protected']) {
		if (!empty($opt['dedicated'])) {
			echo balanceTags($opt['dedicated']);
		} else if ($post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video']) {
			?>
			<div class="sc_section columns<?php echo ($opt['location']=='center' && $opt['sidebar'] ? '2_3' : '1_2'); ?> post_thumb thumb">
				<?php
				if ($post_data['post_video']) {
					echo getVideoFrame($post_data['post_video'], $post_data['post_thumb'], true);
				} else if ($post_data['post_thumb'] && ($post_data['post_format']!='gallery' || !$post_data['post_gallery'] || get_custom_option('gallery_instead_image')=='no')) {
					if ($post_data['post_format']=='link' && $post_data['post_url']!='')
						echo '<a href="'.$post_data['post_url'].'"'.($post_data['post_url_target'] ? ' target="'.$post_data['post_url_target'].'"' : '').'>'.$post_data['post_thumb'].'</a>';
					else if ($post_data['post_link']!='')
						echo '<a href="'.$post_data['post_link'].'">'.$post_data['post_thumb'].'</a>';
					else
						echo balanceTags($post_data['post_thumb']);
				} else if ($post_data['post_gallery']) {
					echo balanceTags($post_data['post_gallery']);
				}
				?>
			</div>
		<?php 
		}
	}
	?>

	<?php if ($show_title && $opt['location'] != 'center') { ?>
	<h2 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h2>
	<?php } ?>

	<?php
	if ($post_data['post_protected']) {
		echo balanceTags($post_data['post_excerpt']);
	} else {
		if ($post_data['post_excerpt']) {
			?>
			<div class="post<?php echo themerex_strtoproper($post_data['post_format']); ?>">
				<?php echo in_array($post_data['post_format'], array('quote', 'link', 'chat')) ? $post_data['post_excerpt'] : getShortString($post_data['post_excerpt'], isset($opt['descr']) ? $opt['descr'] : get_custom_option('post_excerpt_maxlength')); ?>
			</div>
			<?php
		}
	}
	?>
	<?php if (!$post_data['post_protected']) { ?>
	<div class="postSharing">
		<?php 
		$postinfo_buttons = array('more', 'comments', 'views', 'likes', 'rating');	// 'share'
		require(themerex_get_file_dir('/templates/page-part-postinfo.php')); 
		?>
	</div>
	<?php if ($opt['sidebar']) echo balanceTags($post_info); ?>
	<?php } ?>
<?php if (in_shortcode_blogger(true)) { ?>
</div>
<?php } else { ?>
</article>
<?php } ?>
