<?php
$post_title_tag = $opt['style']=='list' ? 'li' : 'h4';

require(themerex_get_file_dir('/templates/page-part-reviews-summary.php'));

$title = '<' . $post_title_tag 
	. ' class="sc_blogger_title sc_title'
	. (in_array($opt['style'], array('accordion_1', 'accordion_2')) ? ' sc_accordion_title' : '')
	. '">'
		. ((!isset($opt['links']) || $opt['links']) && !in_array($opt['style'], array('accordion_1', 'accordion_2')) ? '<a href="' . $post_data['post_link'] . '">' : '')
			. (themerex_substr($opt['style'], 0, 6)=='bubble' 
				? '<span class="sc_title_bubble_icon ' . ($post_data['post_icon']!='' ? ' '.$post_data['post_icon'] : '') .'"' 
					. ($post_data['bubble_color']!='' ? ' style="background-color:' . $post_data['bubble_color'] . '"' : '') . '></span>' 
				: '')
			. (in_array($opt['style'], array('accordion_1', 'accordion_2')) ? '<span class="sc_accordion_icon"></span>' : '') 
			. $post_data['post_title'] 
		. ((!isset($opt['links']) || $opt['links']) && !in_array($opt['style'], array('accordion_1', 'accordion_2')) ? '</a>' : '')
	. '</' . $post_title_tag . '>'
	. (in_array($opt['style'], array('accordion_1', 'accordion_2', 'list')) ? '' : $reviewsBlock);

if ($opt['style'] == 'list') {

	echo balanceTags($title);

} else {

	$thumb = $post_data['post_thumb'] && themerex_strpos($opt['style'], 'image')!==false
				? ('<div class="thumb">' . ($post_data['post_link']!='' ? '<a href="'.$post_data['post_link'].'">'.$post_data['post_thumb'].'</a>' : $post_data['post_thumb']) . '</div>')
				: '';

    // blogger classes changes
    if (in_array($opt['style'], array('image_classes')) && $thumb) {

        if ($opt['style']!='date' && $opt['descr'] > 0) {
            if (!in_array($post_data['post_format'], array('quote', 'link', 'chat')) && themerex_strlen($post_data['post_excerpt']) > $opt['descr']) {
                $post_data['post_excerpt'] = getShortString($post_data['post_excerpt'], $opt['descr'], $opt['readmore'] ? '' : '...');
            }
        }

        $content = '<div class="sc_blogger_content"><div class="sc_blogger_content_inner">' . balanceTags($post_data['post_excerpt']) . '</div></div>' ;
        $thumb = $post_data['post_thumb'] && themerex_strpos($opt['style'], 'image')!==false
            ? ('<div class="thumb">' . ($post_data['post_link']!='' ? '<a href="'.$post_data['post_link'].'">'.$post_data['post_thumb']. $content .'</a>' : $post_data['post_thumb']) . '</div>')
            : '';
    }

	$info = sc_param_is_on($opt['info']) ? '<div class="sc_blogger_info">'
		. (themerex_strpos($opt['style'], 'image')!==false || themerex_strpos($opt['style'], 'accordion')!==false 
			? '<div class="squareButton light ico sc_blogger_more"><a class="icon-link" title="" href="'.$post_data['post_link'].'">'.($opt['readmore'] ? $opt['readmore'] : __('More', 'themerex')).'</a></div><div class="sc_blogger_author">' . __('Posted by', 'themerex')
			: __('by', 'themerex'))
		. ' <a href="'.$post_data['post_author_url'].'" class="post_author">'.$post_data['post_author'].'</a>'
		. ($opt['counters']!='none' 
			? (' <span class="separator">|</span> '
				. ($opt['orderby']=='comments' || $opt['counters']=='comments' ? __('Comments', 'themerex') : __('Views', 'themerex'))
				. ' <span class="comments_number">' . ($opt['orderby']=='comments' || $opt['counters']=='comments' ? $post_data['post_comments'] : $post_data['post_views']) . '</span>'
			  )
			: '')
		. (themerex_strpos($opt['style'], 'image')!==false || themerex_strpos($opt['style'], 'accordion')!==false ? '</div>' : '')
		. '</div>' 
		: '';

	if ($opt['dir'] == 'horizontal' && $opt['style'] != 'date') {
		?>
		<div class="columns1_<?php echo esc_attr($opt['posts_visible']); ?> column_item_<?php echo esc_attr($opt['number']); ?><?php
			echo  ($opt['number'] % 2 == 1 ? ' odd' : ' even')
				. ($opt['number'] == 1 ? ' first' : '')
				. ($opt['number'] == $opt['posts_on_page'] ? ' columns_last' : '')
				. (sc_param_is_on($opt['scroll']) ? ' sc_scroll_slide swiper-slide' : '');
				?>">

		<?php
	}
?>
<article class="sc_blogger_item<?php
	echo  (in_array($opt['style'], array('accordion_1', 'accordion_2')) ? ' sc_accordion_item' : '')
		. ($opt['number'] == $opt['posts_on_page'] && !sc_param_is_on($opt['loadmore']) ? ' sc_blogger_item_last' : '')
		. (sc_param_is_on($opt['scroll']) && ($opt['dir'] == 'vertical' || $opt['style'] == 'date') ? ' sc_scroll_slide swiper-slide' : '');
		?>"<?php echo ($opt['dir'] == 'horizontal' && $opt['style'] == 'date' ? ' style="width:'.(100/$opt['posts_on_page']).'%"' : ''); ?>>

	<?php
	if ($opt['style'] == 'date') {
	?>
		<div class="sc_blogger_date">
			<span class="day_month"><?php echo ($post_data['post_date_part1']); ?></span>
			<span class="year"><?php echo ($post_data['post_date_part2']); ?></span>
		</div>
	<?php 
	}

	if (in_array($opt['style'], array('image_large', 'image_tiny', 'image_classes')) && $thumb) {
		echo balanceTags($thumb);
	}

	//if (get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')))
		echo balanceTags($title);

	if ($opt['style'] != 'date') {
		?>
		<div class="sc_<?php echo in_array($opt['style'], array('accordion_1', 'accordion_2')) ? 'accordion' : 'blogger'; ?>_content">
		<?php
	}
	
	if (in_array($opt['style'], array('date'))) {
		echo balanceTags($info);
	}

	if (in_array($opt['style'], array('image_small', 'image_medium')) && $thumb) {
		echo balanceTags($thumb);
	}

	if ($opt['style']!='date' && $opt['descr'] > 0) {
		if (!in_array($post_data['post_format'], array('quote', 'link', 'chat')) && themerex_strlen($post_data['post_excerpt']) > $opt['descr']) {
			$post_data['post_excerpt'] = getShortString($post_data['post_excerpt'], $opt['descr'], $opt['readmore'] ? '' : '...');
		}
        // classes blogger changes
        if (in_array($opt['style'], array('image_classes')) && $thumb) {
            //
        }
        else {
            echo balanceTags($post_data['post_excerpt']);
        }
	}

	if (in_array($opt['style'], array('accordion_1', 'accordion_2'))) {
		echo balanceTags($info);
	}

	if ($opt['style'] != 'date') {
		?>
		</div>
		<?php
	}

	if (!in_array($opt['style'], array('date', 'accordion_1', 'accordion_2'))) {
		echo balanceTags($info);
	}
	?>
</article>
	<?php
	if ($opt['style'] == 'date' && $opt['number'] == $opt['posts_on_page'] && sc_param_is_on($opt['loadmore'])) {
		?>
		<article class="load_more<?php echo sc_param_is_on($opt['scroll']) && ($opt['dir'] == 'vertical' || $opt['style'] == 'date') ? ' sc_scroll_slide swiper-slide' : ''; ?>"
			<?php echo ($opt['dir'] == 'horizontal' && $opt['style'] == 'date' ? ' style="width:'.(100/$opt['posts_on_page']).'%"' : ''); ?>></article>
	<?php
	}
	if ($opt['dir'] == 'horizontal' && $opt['style'] != 'date') {
		echo '</div>';
	}
}
?>