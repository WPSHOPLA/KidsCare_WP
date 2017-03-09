<?php
$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
$columns = max(1, min(4, (int) themerex_substr($opt['style'], -1)));
if ($columns == 1) {
?>
	<article class="isotopeElement hrShadow <?php 
		echo 'post_format_'.$post_data['post_format'] 
			. ' post'.$opt['post_class']
			. ($opt['number']%2==0 ? ' even' : ' odd') 
			. ($opt['number']==0 ? ' first' : '') 
			. ($opt['number']==$opt['posts_on_page'] ? ' last' : '')
			. ($opt['add_view_more'] ? ' viewmore' : '') 
			. ($opt['filters']!='' 
				? ' flt_'.join(' flt_', $opt['filters']=='categories' ? $post_data['post_categories_ids'] : $post_data['post_tags_ids'])
				: '');
		?>">
		<div class="shadow_wrapper">
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
		?>
		<div class="folioInfoBlock">
			<?php
			if ($show_title) {
				if (!isset($opt['links']) || $opt['links']) {
					?><h2><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h2><?php
				} else {
					?><h2><?php echo esc_html($post_data['post_title']); ?></h2><?php
				}
			}
			?>
			<p>
			<?php echo in_array($post_data['post_format'], array('quote', 'link', 'chat')) ? $post_data['post_excerpt'] : getShortString($post_data['post_excerpt'], isset($opt['descr']) ? $opt['descr'] : get_custom_option('post_excerpt_maxlength')); ?>
			</p>
			<?php if (!isset($opt['info']) || $opt['info']) { ?>
			<div class="moreWrapPortfolio">
				<div class="portfolioMore">
					<?php
					$postinfo_buttons = array('more');
					require(themerex_get_file_dir('/templates/page-part-postinfo.php')); 
					?>
				</div>
				<div class="infoPost">
					<?php _e('Posted ', 'themerex'); ?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date"><?php echo balanceTags($post_data['post_date']); ?></a>
					<?php if ($post_data['post_categories_links']!='') { ?>
					<span class="separator">|</span>
					<span class="post_cats"><?php echo balanceTags($post_data['post_categories_links']); ?></span>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	</article>

<?php
} else {
	$useAdditionalHover = themerex_strpos($opt['hover'], ' ') > 0;
	if ($opt['hover']=='square effect4') $opt['hover']='square effect5';
?>

	<article class="isotopeElement <?php 
		echo 'post_format_'.$post_data['post_format'] 
			. ' hover_'.themerex_strtoproper($useAdditionalHover ? 'shift' : $opt['hover'])
			. ($opt['hover']=='book' ? ' bookShowWrap' : '')
			. ($opt['number']%2==0 ? ' even' : ' odd') 
			. ($opt['number']==0 ? ' first' : '') 
			. ($opt['number']==$opt['posts_on_page'] ? ' last' : '')
			. ($opt['add_view_more'] ? ' viewmore' : '') 
			. ($opt['filters']!='' 
				? ' flt_'.join(' flt_', $opt['filters']=='categories' ? $post_data['post_categories_ids'] : $post_data['post_tags_ids'])
				: '');
		?>">

		<?php
		if ($useAdditionalHover) {
		?>
			<div class="ih-item colored<?php echo ($opt['hover'] ? ' '.$opt['hover'] : '') . ($opt['hover_dir'] ? ' '.$opt['hover_dir'] : ''); ?>"><a href="<?php echo !isset($opt['links']) || $opt['links'] ? $post_data['post_link'] : '#'; ?>"<?php echo !isset($opt['links']) || $opt['links'] ? '' : ' style="cursor:default;" onclick="return false;"'; ?>>
				<?php if ($opt['hover'] == 'circle effect1') { ?>
				<div class="spinner"></div>
				<?php } ?>
				<?php if ($opt['hover'] == 'square effect4') { ?>
				<div class="mask1"></div>
				<div class="mask2"></div>
				<?php } ?>
				<?php if ($opt['hover'] == 'circle effect8') { ?>
				<div class="img-container">
				<?php } ?>
				<div class="img">
				<?php 
				if ($post_data['post_thumb']) {
					echo balanceTags($post_data['post_thumb']);
				} else if ($post_data['post_gallery']) {
					echo balanceTags($post_data['post_gallery']);
				} else if ($post_data['post_video']) {
					echo getVideoFrame($post_data['post_video'], $post_data['post_thumb'], true);
				}
				?>
				</div>
				<?php if ($opt['hover'] == 'circle effect8') { ?>
				</div>
				<div class="info-container">
				<?php } ?>
				<div class="info"><div class="info-back">
					<?php if ($show_title) { ?>
					<h4><?php echo getShortString($post_data['post_title'], 25); ?></h4>
					<?php } ?>
					<p>
					<?php
					$post_data['post_excerpt'] = getShortString($post_data['post_excerpt'], min(100, isset($opt['descr']) ? $opt['descr'] : get_custom_option('post_excerpt_maxlength_masonry')));
					echo balanceTags($post_data['post_excerpt']);
					?>
					</p>
				</div>
				<?php if ($opt['hover'] == 'circle effect8') { ?>
				</div>
				<?php } ?>
				</div></a></div>

		<?php
		} else {
		?>

			<div class="hover hover<?php echo themerex_strtoproper($opt['hover']); ?>Show">
				<?php if ($post_data['post_thumb']) { ?>
					<div class="thumb"><?php echo balanceTags($post_data['post_thumb']); ?></div>
				<?php
				} else if ($post_data['post_gallery']) {
					echo balanceTags($post_data['post_gallery']);
				} else if ($post_data['post_video']) {
					echo getVideoFrame($post_data['post_video'], $post_data['post_thumb'], true);
				}
				?>
				<div class="folioShowBlock">
					<div class="folioContentAfter">
						<?php
						if ($show_title) {
							if (!isset($opt['links']) || $opt['links']) {
								?><h4><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h4><?php
							} else {
								?><h4><?php echo esc_html($post_data['post_title']); ?></h4><?php
							}
						}
						?>
						<p>
						<?php echo in_array($post_data['post_format'], array('quote', 'link', 'chat')) ? $post_data['post_excerpt'] : getShortString($post_data['post_excerpt'], min($columns==2 ? 400 : 90, isset($opt['descr']) ? $opt['descr'] : get_custom_option('post_excerpt_maxlength_masonry'))); ?>
						</p>
						<?php if (!isset($opt['info']) || $opt['info']) { ?>
						<div class="masonryInfo">
							<?php _e('Posted ', 'themerex'); ?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date"><?php echo balanceTags($post_data['post_date']); ?></a>
							<?php if ($post_data['post_categories_links']!='') { ?>
							<span class="separator">|</span>
							<span class="post_cats"><?php echo balanceTags($post_data['post_categories_links']); ?></span>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>

		<?php } ?>
	</article>
<?php } ?>
