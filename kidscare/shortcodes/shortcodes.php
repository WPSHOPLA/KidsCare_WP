<?php
/**
 * ThemeREX Shortcodes
*/

require_once( 'shortcodes_settings.php' );

if (is_admin() && class_exists('WPBakeryShortCode')) {
	require_once( 'shortcodes_vc.php' );
}

// ---------------------------------- [trx_accordion] ---------------------------------------

add_shortcode('trx_accordion', 'sc_accordion');

/*
[trx_accordion id="unique_id" initial="1 - num_elements"]
	[trx_accordion_item title="Et adipiscing integer, scelerisque pid"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_accordion_item]
	[trx_accordion_item title="A pulvinar ut, parturient enim porta ut sed"]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_accordion_item]
	[trx_accordion_item title="Duis sociis, elit odio dapibus nec"]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_accordion_item]
	[trx_accordion_item title="Nec purus, cras tincidunt rhoncus"]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_accordion_item]
[/trx_accordion]
*/
$THEMEREX_sc_accordion_counter = 0;
$THEMEREX_sc_accordion_large = false;
$THEMEREX_sc_accordion_show_counter = false;
function sc_accordion($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"initial" => "1",
		"style" => "1",
		"counter" => "off",
		"large" => "off",
		"shadow" => "off",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	$style = max(1, min(3, $style));
	$initial = max(0, (int) $initial);
	global $THEMEREX_sc_accordion_counter, $THEMEREX_sc_accordion_large, $THEMEREX_sc_accordion_show_counter;
	$THEMEREX_sc_accordion_counter = 0;
	$THEMEREX_sc_accordion_large = sc_param_is_on($large);
	$THEMEREX_sc_accordion_show_counter = sc_param_is_on($counter);
	themerex_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
	return '<div' . ($id ? ' id="' . $id . '"' : '') 
			. ' class="sc_accordion sc_accordion_style_' . $style 
			. (!empty($class) ? ' '.$class : '')
			. (sc_param_is_on($shadow) ? ' sc_shadow' : '') 
			. (sc_param_is_on($counter) ? ' sc_show_counter' : '') . '"'
			. (sc_param_is_on($large) ? ' sc_accordion_large' : '') 
			. ($s!='' ? ' style="'.$s.'"' : '') 
			. ' data-active="' . ($initial-1) . '"'
			. '>'
			. do_shortcode($content)
			. '</div>';
}


add_shortcode('trx_accordion_item', 'sc_accordion_item');

//[trx_accordion_item]
function sc_accordion_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"title" => ""
	), $atts));
	global $THEMEREX_sc_accordion_counter, $THEMEREX_sc_accordion_large, $THEMEREX_sc_accordion_show_counter;
	$THEMEREX_sc_accordion_counter++;
	return '<div' . ($id ? ' id="' . $id . '"' : '') 
			. ' class="sc_accordion_item' 
			. (!empty($class) ? ' '.$class : '')
			. ($THEMEREX_sc_accordion_large ? ' sc_accordion_item_large' : '') 
			. ($THEMEREX_sc_accordion_counter % 2 == 1 ? ' odd' : ' even') 
			. ($THEMEREX_sc_accordion_counter == 1 ? ' first' : '') 
			. '">'
			. '<h'.($THEMEREX_sc_accordion_large ? '3' : '4').' class="sc_accordion_title">'
			. ($THEMEREX_sc_accordion_show_counter ? '<span class="sc_items_counter">'.$THEMEREX_sc_accordion_counter.'</span>' : '')
			. $title 
			. '</h'.($THEMEREX_sc_accordion_large ? '3' : '4').'>'
			. '<div class="sc_accordion_content">'
				. do_shortcode($content) 
			. '</div>'
			. '</div>';
}

// ---------------------------------- [/trx_accordion] ---------------------------------------



// ---------------------------------- [trx_anchor] ---------------------------------------

add_shortcode("trx_anchor", "sc_anchor");
						
//[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]

function sc_anchor($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"id" => "",
		"title" => "",
		"description" => '',
		"icon" => '',
		"url" => "",
		"separator" => "no"
    ), $atts));
	return $id 
		? '<a name="' . $id . '" id="' . $id . '"'
			. ' class="sc_anchor"' 
			. ' title="' . ($title ? esc_attr($title) : '') . '"'
			. ' data-description="' . ($description ? esc_attr(str_replace(array("{", "}", "|"), array("<i>", "</i>", "<br>"), $description))   : ''). '"'
			. ' data-icon="' . ($icon ? $icon : '') . '"' 
			. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
			. ' data-separator="' . (sc_param_is_on($separator) ? 'yes' : 'no') . '"'
			. '></a>'
		: '';
}
// ---------------------------------- [/trx_anchor] ---------------------------------------



// ---------------------------------- [trx_audio] ---------------------------------------

add_shortcode("trx_audio", "sc_audio");
						
//[trx_audio id="unique_id" url="http://webglogic.com/audio/AirReview-Landmarks-02-ChasingCorporate.mp3" controls="0|1"]

function sc_audio($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"mp3" => '',
		"wav" => '',
		"src" => '',
		"url" => '',
		"controls" => "",
		"autoplay" => "",
		"width" => '100%',
		"height" => '30',
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	if ($src=='' && $url=='' && isset($atts[0])) {
		$src = $atts[0];
	}
	if ($src=='') {
		if ($url) $src = $url;
		else if ($mp3) $src = $mp3;
		else if ($wav) $src = $wav;
	}
	$s = getStyleString($top, $right, $bottom, $left);

	// Media elements library
	if (get_theme_option('use_mediaelement')=='yes') {
		if (floatval(get_bloginfo('version')) < "3.6") {
			themerex_enqueue_style(  'mediaplayer-style',  themerex_get_file_url('/js/mediaplayer/mediaplayer.css'), array(), null );
			themerex_enqueue_script( 'mediaplayer', themerex_get_file_url('/js/mediaplayer/mediaelement.min.js'), array(), null, true );
		} else {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}
	} else {
		global $wp_scripts;
		$wp_scripts->done[] = 'mediaelement';
		$wp_scripts->done[] = 'wp-mediaelement';
		$wp_styles->done[] = 'mediaelement';
		$wp_styles->done[] = 'wp-mediaelement';
	}

	return '<audio' . ($id ? ' id="' . $id . '"' : '') . (!empty($class) ? ' class="'.$class.'"' : '')
 . ' src="' . $src . '" class="sc_audio" ' . (sc_param_is_on($controls) ? ' controls="controls"' : '') . (sc_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '') . ' width="' . $width . '" height="' . $height .'"'.($s!='' ? ' style="'.$s.'"' : '').'></audio>';
}
// ---------------------------------- [/trx_audio] ---------------------------------------





// ---------------------------------- [trx_banner] ---------------------------------------


add_shortcode('trx_banner', 'sc_banner');

/*
[trx_banner id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]Banner text[/banner/
*/
function sc_banner($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"src" => "",
		"url" => "",
		"title" => "",
		"link" => "",
		"target" => "",
		"rel" => "",
		"popup" => "no",
		"align" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => "",
		"width" => "",
		"height" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left, $width, $height);
	$content = do_shortcode($content);
	$src = $src!='' ? $src : $url;
	if ($src > 0) {
		$attach = wp_get_attachment_image_src( $src, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$src = $attach[0];
	}

	// magnific & pretty
	themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
	themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
	// Load PrettyPhoto if it selected in Theme Options
	if (get_theme_option('popup_engine')=='pretty') {
		themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
		themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
	}

	return empty($src) ? '' : ('<a href="' . (empty($link) ? '#' : $link) . '"' . ($id ? ' id="' . $id . '"' : '')
		. ' class="sc_banner' . (sc_param_is_on($popup) ? ' user-popup-link' : '')
		. (!empty($class) ? ' '.$class : '')
		. '"'
		. (!empty($target) ? ' target="' . $target . '"' : '') 
		. (!empty($rel) ? ' rel="' . $rel . '"' : '')
		. ($align && $align!='none' ? ' sc_align' . $align : '')
		. ($s!='' ? ' style="'.$s.'"' : '')
		. '>'
		. '<img src="' . $src . '" class="sc_banner_image" alt="" />'
		. (trim($title) ? '<span class="sc_banner_title">' . $title . '</span>' : '') 
		. (trim($content) ? '<span class="sc_banner_content">' . $content . '</span>' : '') 
		. '</a>');
}

// ---------------------------------- [/trx_banner] ---------------------------------------



// ---------------------------------- [trx_br] ---------------------------------------

add_shortcode("trx_br", "sc_br");
						
//[trx_br clear="left|right|both"]

function sc_br($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"clear" => ""
    ), $atts));
	return '<br' . (in_array($clear, array('left', 'right', 'both')) ? ' style="clear:' . $clear . '"' : '') . ' />';
}
// ---------------------------------- [/trx_br] ---------------------------------------





// ---------------------------------- [trx_blogger] ---------------------------------------

add_shortcode('trx_blogger', 'sc_blogger');

/*
[trx_blogger id="unique_id" ids="comma_separated_list" cat="category_id" orderby="date|views|comments" order="asc|desc" count="5" descr="0" dir="horizontal|vertical" style="regular|date|image_large|image_medium|image_small|accordion|list" border="0"]
*/
$THEMEREX_sc_blogger_busy = false;
$THEMEREX_sc_blogger_counter = 0;
function sc_blogger($atts, $content=null){	
	if (in_shortcode_blogger(true)) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "regular",
		"filters" => "no",
		"ids" => "",
		"cat" => "",
		"count" => "3",
		"visible" => "",
		"offset" => "",
		"orderby" => "date",
		"order" => "desc",
		"only" => "no",
		"descr" => "0",
		"readmore" => "",
		"loadmore" => "no",
		"location" => "default",
		"dir" => "horizontal",
		"hover" => get_theme_option('hover_style'),
		"hover_dir" => get_theme_option('hover_dir'),
		"scroll" => "no",
		"controls" => "no",
		"rating" => "no",
		"info" => "yes",
		"links" => "yes",
		"date_format" => "",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));

	$s = getStyleString($top, $right, $bottom, $left, $width, $height);
	$width  = getStyleValue($width);
	$height = getStyleValue($height);
	
	global $THEMEREX_sc_blogger_busy, $THEMEREX_sc_blogger_counter, $post;

	$THEMEREX_sc_blogger_busy = true;
	$THEMEREX_sc_blogger_counter = 0;

	if (empty($id)) $id = "sc_blogger_".str_replace('.', '', mt_rand());
	
	if ($style=='date' && empty($date_format)) $date_format = 'd.m+Y';

	if (!in_array($style, array('regular','date','image_large','image_medium','image_small','image_tiny','image_classes','accordion_1','accordion_2','list','excerpt','related'))
		&& !in_array(themerex_substr($style,0,7), array('classic','masonry','portfol')))
		$style='regular';	
	if (!empty($ids)) {
		$posts = explode(',', str_replace(' ', '', $ids));
		$count = count($posts);
	}
	if (in_array($style, array('accordion_1', 'accordion_2', 'list')))
		$dir = 'vertical';
	if ($visible <= 0) $visible = min(4, $count);

	if (sc_param_is_on($scroll) && empty($id)) $id = 'sc_blogger_'.str_replace('.', '', mt_rand());
	
	$output = ($style=='list' ? '<ul' : '<div')
			. ($id ? ' id="' . $id . '"' : '') 
			. ' class="sc_blogger'
				. (!empty($class) ? ' '.$class : '')
				. ' sc_blogger_' . ($dir=='vertical' ? 'vertical' : 'horizontal')
				. ' style_' . (in_array($style, array('accordion_1', 'accordion_2')) ? 'accordion' : (themerex_strpos($style, 'image')!==false ? 'image style_' : '') . $style)
				. (in_array($style, array('accordion_1', 'accordion_2')) ? ' sc_accordion' : '')
				. ($style == 'accordion_1' ? ' sc_accordion_style_1' : '')
				. ($style == 'accordion_2' ? ' sc_accordion_style_2' : '')
				. (themerex_strpos($style, 'masonry')!==false || themerex_strpos($style, 'classic')!==false ? ' masonryWrap' : '')
				. (themerex_strpos($style, 'portfolio')!==false ? ' portfolioWrap' : '')
				. ($style=='related' ? ' relatedPostWrap' : '')
				. (sc_param_is_on($scroll) && sc_param_is_on($controls) ? ' sc_scroll_controls sc_scroll_controls_'.$dir : '')
				. ($descr == 0 ? ' no_description' : '')
				. '"'
			. ($s!='' ? ' style="'.$s.'"' : '')
		. '>'
		. ($dir!='vertical' && $style!='date' && !in_array(themerex_substr($style,0,7), array('classic','masonry','portfol', 'excerpt')) ? '<div class="columnsWrap">' : '')
		. (sc_param_is_on($scroll) 
			? '<div id="'.$id.'_scroll" class="sc_scroll sc_scroll_'.$dir.' sc_slider_noresize swiper-slider-container scroll-container"'
				. ' style="'.($dir=='vertical' ? 'height:'.($height != '' ? $height : "230px").';' : 'width:'.($width != '' ? $width.';' : "100%;")).'"'
				. '>'
				. '<div class="sc_scroll_wrapper swiper-wrapper">' 
			: '');
	if (themerex_strpos($style, 'masonry')!==false || themerex_strpos($style, 'classic')!==false) {
		if (!sc_param_is_off($filters))
			$output .= '<div class="isotopeFiltr"></div>';
		$output .= '<section class="masonry '.(!sc_param_is_off($filters) ? 'isotope' : 'isotopeNOanim').'" data-columns="'.themerex_substr($style, -1).'">';
	} else if (themerex_strpos($style, 'portfolio')!==false) {
		if (!sc_param_is_off($filters))
			$output .= '<div class="isotopeFiltr"></div>';
		$output .= '<section class="portfolio '.(!sc_param_is_off($filters) ? 'isotope' : 'isotopeNOanim').' folio'.themerex_substr($style, -1).'col" data-columns="'.themerex_substr($style, -1).'">';
	}

	$args = array(
		'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
		'posts_per_page' => $count,
		'ignore_sticky_posts' => 1,
		'order' => $order=='asc' ? 'asc' : 'desc',
		'orderby' => 'date',
	);

	if ($offset > 0 && empty($ids)) {
		$args['offset'] = $offset;
	}

	$args = addSortOrderInQuery($args, $orderby, $order);
	if (!sc_param_is_off($only)) $args = addFiltersInQuery($args, array($only));
	$args = addPostsAndCatsInQuery($args, $ids, $cat);

	$query = new WP_Query( $args );

	$flt_ids = array();

	while ( $query->have_posts() ) { $query->the_post();

		$THEMEREX_sc_blogger_counter++;

		$args = array(
			'layout' => in_array(themerex_substr($style, 0, 7), array('classic', 'masonry', 'portfol', 'excerpt', 'related')) ? themerex_substr($style, 0, 7) : 'blogger',
			'show' => false,
			'number' => $THEMEREX_sc_blogger_counter,
			'add_view_more' => false,
			'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
			// Additional options to layout generator
			"location" => $location,
			"descr" => $descr,
			"readmore" => $readmore,
			"loadmore" => $loadmore,
			"reviews" => sc_param_is_on($rating),
			"dir" => $dir,
			"scroll" => sc_param_is_on($scroll),
			"info" => sc_param_is_on($info),
			"links" => sc_param_is_on($links),
			"orderby" => $orderby,
			"posts_visible" => $visible,
			"date_format" => $date_format,
			// Get post data
			'thumb_size' => $style,
			'thumb_crop' => themerex_strpos($style, 'masonry')===false,// && $count>1 && $query->found_posts>1,
			'strip_teaser' => false,
			//"content" => !in_array($style, array('list', 'date', 'accordion_1', 'accordion_2')),
			"categories_list" => in_array($style, array('excerpt')) || $filters=='categories',
			"tags_list" => in_array(themerex_substr($style, 0, 7), array('classic', 'masonry', 'portfol', 'related')) || $filters=='tags',
			'filters' => sc_param_is_off($filters) ? '' : $filters,
			'hover' => $hover,
			'hover_dir' => $hover_dir
		);
		$post_data = getPostData($args);
		$output .= showPostLayout($args, $post_data);
	
		if (!sc_param_is_off($filters)) {
			if ($filters == 'tags') {			// Use tags as filter items
				if (count($post_data['post_tags_list']) > 0) {
					foreach ($post_data['post_tags_list'] as $tag) {
						$flt_ids[$tag->term_id] = $tag->name;
					}
				}
			}
		}

	}

	wp_reset_postdata();

	if (in_array(themerex_substr($style, 0, 7), array('classic', 'masonry', 'portfol'))) {
		if (themerex_strpos($style, 'masonry')!==false || themerex_strpos($style, 'classic')!==false) {
			$output .= '</section>';
		} else if (themerex_strpos($style, 'portfolio')!==false) {
			$output .= '</section>';
		}
		// Isotope filters list
		$filters_list = '';
		if (!sc_param_is_off($filters)) {
			if ($filters == 'categories') {			// Use categories as filter items
				$portfolio_parent = max(0, getParentCategoryByProperty($cat, 'show_filters', 'yes'));
				$args2 = array(
					'type'                     => 'post',
					'child_of'                 => $portfolio_parent,
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 1,
					'hierarchical'             => 0,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => 'category',
					'pad_counts'               => false );
				$portfolio_list = get_categories($args2);
				global $cat_id;
				$cat_id = (!$cat_id || !isset($cat_id) || $cat_id== '' ? '' : $cat_id);
				if (count($portfolio_list) > 0) {
					$filters_list .= '<li class="squareButton'.($portfolio_parent==$cat_id ? ' active' : '').'"><a href="#" data-filter="*">'.__('All', 'themerex').'</a></li>';
					foreach ($portfolio_list as $cat) {
						$filters_list .= '<li class="squareButton'.($cat->term_id==$cat_id ? ' active' : '').'"><a href="#" data-filter=".flt_'.$cat->term_id.'">'.$cat->name.'</a></li>';
					}
				}
			} else {															// Use tags as filter items
				if (count($flt_ids) > 0) {
					$filters_list .= '<li class="squareButton active"><a href="#" data-filter="*">'.__('All', 'themerex').'</a></li>';
					foreach ($flt_ids as $flt_id=>$flt_name) {
						$filters_list .= '<li class="squareButton"><a href="#" data-filter=".flt_'.$flt_id.'">'.$flt_name.'</a></li>';
					}
				}
			}
			if ($filters_list) {
				$output .= '<script type="text/javascript">'
					. 'jQuery(document).ready(function () {'
						. 'jQuery("#'.$id.' .isotopeFiltr").append("<ul>'.addslashes($filters_list).'</ul>");'
					. '});'
					. '</script>';
			}
		}

	}
	$output	.= (sc_param_is_on($scroll) 
			? '</div><div id="'.$id.'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.$dir.' '.$id.'_scroll_bar"></div></div>' 
				. (sc_param_is_on($controls) ? '<ul class="flex-direction-nav"><li><a class="flex-prev" href="#"></a></li><li><a class="flex-next" href="#"></a></li></ul>' : '')
			: '')
		. ($dir!='vertical' && $style!='date' && !in_array(themerex_substr($style,0,7), array('classic','masonry','portfol', 'excerpt')) ? '</div>' : '')
		. ($style == 'list' ? '</ul>' : '</div>');
	if (in_array($style, array('accordion_1', 'accordion_2'))) {
		themerex_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
	}

	// todo: Load Isotope
	themerex_enqueue_script( 'isotope', themerex_get_file_url('/js/jquery.isotope.min.js'), array(), null, true );

	themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
	themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

	themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'flexslider', themerex_get_file_url('/js/jquery.flexslider.min.js'), array('jquery'), null, true );

	// magnific & pretty
	themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
	themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
	// Load PrettyPhoto if it selected in Theme Options
	if (get_theme_option('popup_engine')=='pretty') {
		themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
		themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
	}
	
	$THEMEREX_sc_blogger_busy = false;
	
	return $output;
}

function in_shortcode_blogger($from_blogger = false) {
	if (!$from_blogger) return false;
	global $THEMEREX_sc_blogger_busy;
	return $THEMEREX_sc_blogger_busy;
}
// ---------------------------------- [/trx_blogger] ---------------------------------------



// ---------------------------------- [trx_button] ---------------------------------------


add_shortcode('trx_button', 'sc_button');

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/
function sc_button($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"type" => "square",
		"style" => "global",
		"size" => "medium",
		"fullsize" => "no",
		"icon" => "",
		"color" => "",
		"link" => "",
		"target" => "",
		"align" => "",
		"rel" => "",
		"popup" => "no",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);

	// magnific & pretty
	themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
	themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
	// Load PrettyPhoto if it selected in Theme Options
	if (get_theme_option('popup_engine')=='pretty') {
		themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
		themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
	}

	return '<div class="sc_button sc_button_style_' . $style . ' sc_button_size_' . $size . ($align && $align!='none' ? ' align'.$align : '') . (!empty($class) ? ' '.$class : '')
 . ' ' . $type . 'Button' . (sc_param_is_on($fullsize) ? ' fullSize' : '') . ' ' . $style . ' ' . $size . ($icon!='' ? '  ico' : '') . '"' 
		. ($s!='' ? ' style="'.$s.'"' : '') . '>'
		. '<a href="' . (empty($link) ? '#' : $link) . '"' . (!empty($target) ? ' target="' . $target . '"' : '') . (!empty($rel) ? ' rel="' . $rel . '"' : '')
		. ($id ? ' id="' . $id . '"' : '') 
		. ' class="' . ($icon ?  $icon : '') . (sc_param_is_on($popup) ? ' user-popup-link' : '') . '"'
		. ($color !== '' ? ' style="background-color:' . $color . '; border-color:'. $color .';"' : '')
		. '>' . do_shortcode($content) . '</a>'
		. '</div>';
}

// ---------------------------------- [/trx_button] ---------------------------------------





// ---------------------------------- [trx_chat] ---------------------------------------


add_shortcode('trx_chat', 'sc_chat');

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/
function sc_chat($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"title" => "",
		"link" => "",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left, $width, $height);
	$title = $title=='' ? $link : $title;
	$content = do_shortcode($content);
	if (themerex_substr($content, 0, 2)!='<p') $content = '<p>' . $content . '</p>';
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_chat' . (!empty($class) ? ' '.$class : '') . '"' . ($s ? ' style="'.$s.'"' : '') . '>'
		. $content
		. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($link!='' ? '<a href="'.$link.'">' : '') . $title . ($link!='' ? '</a>' : '') . '</p>'))
		.'</div>';
}

// ---------------------------------- [/trx_chat] ---------------------------------------




// ---------------------------------- [trx_columns] ---------------------------------------


add_shortcode('trx_columns', 'sc_columns');

/*
[trx_columns id="unique_id" count="number"]
	[trx_column_item id="unique_id" span="2 - number_columns"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_column_item]
	[trx_column_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_column_item]
	[trx_column_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_column_item]
	[trx_column_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_column_item]
[/trx_columns]
*/
$THEMEREX_sc_columns_count = 0;
$THEMEREX_sc_columns_counter = 0;
$THEMEREX_sc_columns_after_span2 = $THEMEREX_sc_columns_after_span3 = $THEMEREX_sc_columns_after_span4 = false;
function sc_columns($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"count" => "2",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left, $width, $height);
	global $THEMEREX_sc_columns_count, $THEMEREX_sc_columns_counter, $THEMEREX_sc_columns_after_span2, $THEMEREX_sc_columns_after_span3, $THEMEREX_sc_columns_after_span4;
	$THEMEREX_sc_columns_counter = 1;
	$THEMEREX_sc_columns_after_span2 = $THEMEREX_sc_columns_after_span3 = $THEMEREX_sc_columns_after_span4 = false;
	$THEMEREX_sc_columns_count = $count = max(1, min(5, (int) $count));
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="columnsWrap sc_columns sc_columns_count_' . $count . (!empty($class) ? ' '.$class : '') . '"'.($s!='' ? ' style="'.$s.'"' : '').'>' . do_shortcode($content).'</div>';
}


add_shortcode('trx_column_item', 'sc_column_item');

//[trx_column_item]
function sc_column_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"style" => "",
		"span" => "1",
		"align" => "",
		"color" => "",
		"bg_color" => "",
		"bg_image" => ""
	), $atts));
	global $THEMEREX_sc_columns_count, $THEMEREX_sc_columns_counter, $THEMEREX_sc_columns_after_span2, $THEMEREX_sc_columns_after_span3, $THEMEREX_sc_columns_after_span4;
	if ($bg_image > 0) {
		$attach = wp_get_attachment_image_src( $bg_image, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$bg_image = $attach[0];
	}
	$s = ($align !== '' ? 'text-align:' . $align . ';' : '') 
		.($color !== '' ? 'color:' . $color . ';' : '')
		.($bg_color !== '' ? 'background-color:' . $bg_color . ';' : '')
		.($bg_image !== '' ? 'background-image:url(' . $bg_image . ');' : '')
		.$style;
	$span = max(1, min(4, (int) $span));
	$output = '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="columns'.($span > 1 ? $span : 1).'_'.$THEMEREX_sc_columns_count.' sc_column_item sc_column_item_'.$THEMEREX_sc_columns_counter 
					. (!empty($class) ? ' '.$class : '')
					. ($THEMEREX_sc_columns_counter % 2 == 1 ? ' odd' : ' even') 
					. ($THEMEREX_sc_columns_counter == 1 ? ' first' : '') 
					. ($span > 1 ? ' span_'.$span : '') 
					. ($THEMEREX_sc_columns_after_span2 ? ' after_span_2' : '') 
					. ($THEMEREX_sc_columns_after_span3 ? ' after_span_3' : '') 
					. ($THEMEREX_sc_columns_after_span4 ? ' after_span_4' : '') 
					. '"'
					. ($s!='' ? ' style="'.$s.';"' : '')
					. '>' . do_shortcode($content) . '</div>';
	$THEMEREX_sc_columns_counter += $span;
	$THEMEREX_sc_columns_after_span2 = $span==2;
	$THEMEREX_sc_columns_after_span3 = $span==3;
	$THEMEREX_sc_columns_after_span4 = $span==4;
	return $output;
}

// ---------------------------------- [/trx_columns] ---------------------------------------





// ---------------------------------- [trx_contact_form] ---------------------------------------

add_shortcode("trx_contact_form", "sc_contact_form");

//[trx_contact_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna a arcu eu mus sociis? Enim nunc? Integer facilisis, et eu dictumst, adipiscing tempor ultricies, lundium urna lacus quis."]

$THEMEREX_sc_contact_form_id = '';
$THEMEREX_sc_contact_form_counter = 0;

function sc_contact_form($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"title" => "",
		"style" => "1",
		"description" => "",
		"action" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	if (empty($id)) $id = "sc_contact_form_".str_replace('.', '', mt_rand());
	$s = getStyleString($top, $right, $bottom, $left);
	themerex_enqueue_script( 'form-contact', themerex_get_file_url('/js/_form_contact.js'), array('jquery'), null, true );
	global $THEMEREX_ajax_nonce, $THEMEREX_ajax_url, $THEMEREX_sc_contact_form_counter, $THEMEREX_sc_contact_form_id;
	$THEMEREX_sc_contact_form_id = $id;
	$THEMEREX_sc_contact_form_counter = 0;
	$content = do_shortcode($content);
	return '<div ' . ($id ? ' id="' . $id . '"' : '') . 'class="sc_contact_form sc_contact_form_'.($content ? 'custom' : 'contact').(!empty($class) ? ' '.$class : '').'"'.($s!='' ? ' style="'.$s.'"' : '') .'>'
		. ($title ? '<h1 class="title">' . $title . '</h1>' : '')
		. ($description ? '<div class="description">' . $description . '</div>' : '')
		. '<form' . ($id ? ' id="' . $id . '"' : '') . ' data-formtype="'.($content ? 'custom' : 'contact').'" method="post" action="' . ($action ? $action : $THEMEREX_ajax_url) . '">'
		. ($content != '' 
			? $content 
			: '<div class="columnsWrap">'
						.'<div class="columns1_3">'
							.'<label class="required" for="sc_contact_form_username">' . __('Name', 'themerex') . '</label><input id="sc_contact_form_username" type="text" name="username">'
						.'</div>'
						.'<div class="columns1_3">'
							.'<label class="required" for="sc_contact_form_email">' . __('E-mail', 'themerex') . '</label><input id="sc_contact_form_email" type="text" name="email">'
						.'</div>'
						.'<div class="columns1_3">'
							.'<label class="required" for="sc_contact_form_subj">' . __('Subject', 'themerex') . '</label><input id="sc_contact_form_subj" type="text" name="subject">'
						.'</div>'
					.'</div>'
					.'<div class="message">'
						.'<label class="required" for="sc_contact_form_message">' . __('Your Message', 'themerex') . '</label><textarea id="sc_contact_form_message" class="textAreaSize" name="message"></textarea>'
					.'</div>'
					.'<div class="sc_contact_form_button">'
						.'<div class="squareButton ico"><a href="#" class="sc_contact_form_submit icon-comment-1">' . __('Send Message', 'themerex') . '</a></div>'
					.'</div>'
			)
		.'<div class="result sc_infobox"></div>'
		.'</form>'
		.'</div>';
}


add_shortcode('trx_form_item', 'sc_contact_form_item');

//[trx_form_item]
function sc_contact_form_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"align" => "",
		"type" => "text",
		"checked" => "",
		"name" => "",
		"value" => "",
		"label" => "",
		"label_position" => "top",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
	), $atts));
	global $THEMEREX_sc_contact_form_id, $THEMEREX_sc_contact_form_counter;
	$s = getStyleString($top, $right, $bottom, $left);
	$THEMEREX_sc_contact_form_counter += 1;
	if (empty($id)) $id = $THEMEREX_sc_contact_form_id+'_'+$THEMEREX_sc_contact_form_counter;
	$label = $label ? '<label for="' . esc_attr($id) . '"' . (sc_param_is_on($checked) ? ' class="selected"' : '') . '>' . esc_attr($label) . '</label>' : '';
	$output = '<div class="sc_contact_form_field label_'.$label_position.($class ? ' '.$class : '').($align && $align!='none' ? ' align'.$align : '').'"'.($s!='' ? ' style="'.$s.'"' : '') .'>'
		. ($label_position=='top' || $label_position=='left' ? $label : '')
		. ($type == 'textarea' 
			? '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>'
			: ($type=='button' 
				? '<div class="sc_contact_form_button"><div class="squareButton global ico"><a href="#" class="sc_contact_form_submit icon-comment-1">' . $value . '</a></div></div>'
				: '<input type="'.($type ? $type : 'text').'" id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '"' . (sc_param_is_on($checked) ? ' checked="checked"' : '') . '>'
				)
			)
		. ($label_position!='top' && $label_position!='left' ? $label : '')
		. '</div>';
	return $output;
}

// ---------------------------------- [/trx_contact_form] ---------------------------------------




// ---------------------------------- [trx_content] ---------------------------------------

add_shortcode('trx_content', 'sc_content');

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

function sc_content($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "",
		"top" => "",
		"bottom" => "",
        "size" => "",
        "line_height" => "",
    ), $atts));
	$s = getStyleString('!'.$top, '', '!'.$bottom) . $style;
    $s .= ($size != '' ? 'font-size:' . getStyleValue($size) . ';' : '');
    $s .= ($line_height != '' ? 'line-height:' . getStyleValue($line_height) . ';' : '');
	$output = '<div' . ($id ? ' id="' . $id . '"' : '')
		. ' class="sc_content main' . ($class ? ' ' . $class : '') . '"'
		. ($s!='' ? ' style="'.$s.'"' : '').'>' 
		. do_shortcode($content) 
		. '</div>';
	return $output;
}
// ---------------------------------- [/trx_content] ---------------------------------------





// ---------------------------------- [trx_countdown] ---------------------------------------

add_shortcode("trx_countdown", "sc_countdown");

//[trx_countdown date="" time=""]
function sc_countdown($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"date" => "",
		"time" => "",
		"align" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => "",
		"width" => "",
		"height" => ""
    ), $atts));
	if (date('Y-m-d H:i:s') < $date.' '.($time ? $time : '00:00:00')) {
		themerex_enqueue_style(  'flipclock-style', themerex_get_file_url('/js/flipclock/flipclock.css'), array(), null );
		themerex_enqueue_script( 'flipclock', themerex_get_file_url('/js/flipclock/flipclock.custom.js'), array(), null, true );
		$s = getStyleString($top, $right, $bottom, $left, $width, $height);
		return '<div' . ($id ? ' id="' . $id . '"' : '').' class="sc_countdown_wrapper' . ($align && $align!='none' ? ' align' . $align : '') . (!empty($class) ? ' '.$class : '') .'"'.($s ? ' style="'.$s.'"' : '').'><div class="sc_countdown" data-date="'.$date.'" data-time="'.$time.'"></div></div>';
	} else 
		return '';
}
// ---------------------------------- [/trx_countdown] ---------------------------------------



						


// ---------------------------------- [trx_dropcaps] ---------------------------------------

add_shortcode('trx_dropcaps', 'sc_dropcaps');

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]
function sc_dropcaps($atts, $content=null){
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "1"
    ), $atts));
	$style = min(6, max(1, $style));
	$content = do_shortcode($content);
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_dropcaps sc_dropcaps_style_' . $style . (!empty($class) ? ' '.$class : '') . '">' 
			. '<span class="sc_dropcap">' . themerex_substr($content, 0, 1) . '</span>' . themerex_substr($content, 1)
		. '</div>';
}
// ---------------------------------- [/trx_dropcaps] ---------------------------------------





// ---------------------------------- [trx_emailer] ---------------------------------------

add_shortcode("trx_emailer", "sc_emailer");

//[trx_emailer group=""]
function sc_emailer($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"group" => "",
		"open" => "yes",
		"align" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => "",
		"width" => "",
		"height" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left, $width, $height);
	themerex_enqueue_style( 'fontello-admin', themerex_get_file_url('/admin/css/fontello/css/fontello-admin.css'), array(), null);
	return '<div' . ($id ? ' id="' . $id . '"' : '').' class="sc_emailer inputSubmitAnimation' . ($align && $align!='none' ? ' sc_align' . $align : '') . (sc_param_is_on($open) ? ' sFocus rad4 opened' : ' radCircle') . (!empty($class) ? ' '.$class : '') . '"' . ($s ? ' style="'.$s.'"' : '') . '>'
		. '<form><input type="text" class="sInput" name="email" value="" placeholder="'.__('Please, enter you email address.', 'themerex').'" class="sInput"></form>'
		. '<a href="#" class="sc_emailer_button searchIcon aIco mail" title="'.__('Submit', 'themerex').'" data-group="'.($group ? $group : __('E-mail collector group', 'themerex')).'"></a>'
		. '</div>';
}
// ---------------------------------- [/trx_emailer] ---------------------------------------





// --------------------- [gallery] - only filter, not shortcode ------------------------

add_filter("post_gallery", "sc_gallery_filter", 10, 2);

function sc_gallery_filter($prm1, $atts) {
	if (in_shortcode_blogger()) return ' ';
	if (get_custom_option('substitute_gallery_layout')=='no') return '';
	extract(shortcode_atts(array(
		"columns" => 0,
		"order" => "asc",
		"orderby" => "",
		"link" => "attachment",
		"include" => "",
		"exclude" => "",
		"ids" => ""
    ), $atts));

	$post = get_post();

	static $instance = 0;
	$instance++;
	
	$post_id = $post ? $post->ID : 0;
	
	if (empty($orderby)) $orderby = 'post__in';
	else $orderby = sanitize_sql_orderby( $orderby );

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $post_id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if (empty($columns) || $columns<2)
		$columns = 3; //count($attachments);
	$columns = max(2, min(5, intval($columns)));

	$thumb_sizes = getThumbSizes(array(
		'thumb_size' => 'classic'.min(4, $columns),
		'thumb_crop' => true,
		'sidebar' => false
	));
	
	$output = '<div id="sc_gallery_'.$instance.'" class="sc_gallery columnsWrap">';

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$thumb = getResizedImageTag(-$id, $thumb_sizes['w'], $thumb_sizes['h']);
		$full = wp_get_attachment_url($id);
		$url = get_permalink($id);
		$output .= '
			<div class="columns1_'.$columns.'">
				<div class="galleryPic">
					' . ($link=='file'
						? '<div class="thumb hoverIncrease" data-image="'.esc_attr($full).'" data-title="'.esc_attr($attachment->post_excerpt).'">
								'.$thumb.'
							</div>'
						: '<div class="thumb">
								<a href="' . $url . '">'.$thumb.'</a>
							</div>') .'
					<h4>'.esc_attr($attachment->post_excerpt).'</h4>
				</div>
			</div>';
	}

	// magnific & pretty
	themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
	themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
	// Load PrettyPhoto if it selected in Theme Options
	if (get_theme_option('popup_engine')=='pretty') {
		themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
		themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
	}

	$output .= '</div>';

	return $output;
	
}
// ---------------------------------- [/gallery] ---------------------------------------



// ---------------------------------- [trx_gap] ---------------------------------------

add_shortcode("trx_gap", "sc_gap");
						
//[trx_gap]Fullwidth content[/trx_gap]

function sc_gap($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	return sc_gap_start() . do_shortcode($content) . sc_gap_end();
	//return closeAllWrappers(false) . do_shortcode($content) . openAllWrappers(false);
}

function sc_gap_start() {
	return '<!-- #TRX_GAP_START# -->';
}

function sc_gap_end() {
	return '<!-- #TRX_GAP_END# -->';
}

function sc_gap_wrapper($str) {
	// Move VC row and column and wrapper inside gap
	$str_new = preg_replace('/(<div\s+class="vc_row[^>]*>)[\r\n\s]*(<div\s+class="vc_col[^>]*>)[\r\n\s]*(<div\s+class="wpb_wrapper[^>]*>)[\r\n\s]*('.sc_gap_start().')/i', '\\4\\1\\2\\3', $str);
	if ($str_new != $str) $str = preg_replace('/('.sc_gap_end().')[\r\n\s]*(<\/div>)[\r\n\s]*(<\/div>)[\r\n\s]*(<\/div>)/i', '\\2\\3\\4\\1', $str_new);
	// Gap layout
	return str_replace(
			array(sc_gap_start(), sc_gap_end()),
			array(closeAllWrappers(false).'<div class="sc_gap">', '</div>'.openAllWrappers(false)),
			$str
			); 
}
// ---------------------------------- [/trx_gap] ---------------------------------------




// ---------------------------------- [trx_googlemap] ---------------------------------------

add_shortcode("trx_googlemap", "sc_google_map");

//[trx_googlemap id="unique_id" address="your_address" width="width_in_pixels_or_percent" height="height_in_pixels"]
function sc_google_map($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"address" => "",
		"latlng" => "",
		"zoom" => 16,
		"style" => '',
		"width" => "100%",
		"height" => "400",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	if ((int) $width < 100 && themerex_substr($width, -1) != '%') $width='100%';
	if ((int) $height < 50) $height='100';
	$s = getStyleString($top, $right, $bottom, $left, $width, $height);
	if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
	if (empty($address) && empty($latlng)) {
		$latlng = get_custom_option('googlemap_latlng');
		if (empty($latlng))	$address = get_custom_option('googlemap_address');
	}
	if (empty($style)) $style = get_custom_option('googlemap_style');
    $api_key = get_theme_option('api_google');
    themerex_enqueue_script( 'googlemap', themerex_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
	themerex_enqueue_script( 'googlemap_init', themerex_get_file_url('/js/_googlemap_init.js'), array(), null, true );
	return '<div id="' . $id . '" class="sc_googlemap'. (!empty($class) ? ' '.$class : '').'"'.($s!='' ? ' style="'.$s.'"' : '') 
		.' data-address="'.esc_attr($address).'"'
		.' data-latlng="'.esc_attr($latlng).'"'
		.' data-zoom="'.esc_attr($zoom).'"'
		.' data-style="'.esc_attr($style).'"'
		.' data-point="'.esc_attr(get_custom_option('googlemap_marker')).'"'
		.'></div>';
}
// ---------------------------------- [/trx_googlemap] ---------------------------------------





// ---------------------------------- [trx_hide] ---------------------------------------


add_shortcode('trx_hide', 'sc_hide');

/*
[trx_hide selector="unique_id"]
*/
function sc_hide($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"selector" => "",
		"hide" => "on",
		"delay" => 0
    ), $atts));
	$selector = trim(chop($selector));
	return $selector == '' ? '' : 
		'<script type="text/javascript">
			jQuery(document).ready(function() {
				'.($delay>0 ? 'setTimeout(function() {' : '').'
				jQuery("' . $selector . '").' . ($hide=='on' ? 'hide' : 'show') . '();
				'.($delay>0 ? '},'.$delay.');' : '').'
			});
		</script>';
}
// ---------------------------------- [/trx_hide] ---------------------------------------





// ---------------------------------- [trx_highlight] ---------------------------------------


add_shortcode('trx_highlight', 'sc_highlight');

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/
function sc_highlight($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"color" => "",
		"backcolor" => "",
		"size" => "",
        "line_height" => "",
		"style" => "",
		"type" => "1"
    ), $atts));
	$s = ($color != '' ? 'color:' . $color . ';' : '')
		.($backcolor != '' ? 'background-color:' . $backcolor . ';' : '')
		.($size != '' ? 'font-size:' . getStyleValue($size) . ';' : '')
        .($line_height != '' ? 'line-height:' . getStyleValue($line_height) . ';' : '')
		.($style != '' ? $style : '');
	return '<span' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.$type : ''). (!empty($class) ? ' '.$class : '').'"'.($s!='' ? ' style="'.$s.'"' : '').'>' . do_shortcode($content) . '</span>';
}
// ---------------------------------- [/trx_highlight] ---------------------------------------





// ---------------------------------- [trx_icon] ---------------------------------------


add_shortcode('trx_icon', 'sc_icon');

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/
function sc_icon($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"icon" => "",
		"color" => "",
		"size" => "",
		"weight" => "",
		"background" => "",
		"bg_color" => "",
		"align" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	$s2 = ($weight != '' ? 'font-weight:'. $weight.';' : '')
		. ((int) $size > 0 ? 'font-size:'.$size.'px;' : '')
		. ($color != '' ? 'color:'.$color.';' : '')
		. ($bg_color != '' ? 'background-color:'.$bg_color.';' : '')
		. ($background == 'round' && (int) $size > 0 ? ($s ? '' : 'display:inline-block;') . 'width:' . round($size*1.2) . 'px;height:' . round($size*1.2) . 'px;line-height:' . round($size*1.2) . 'px;' : '')
	;
	return $icon!='' 
		? '<span' . ($id ? ' id="' . $id . '"' : '')
			.' class="sc_icon '.$icon
			.($background && $background!='none' ? ' sc_icon_'.$background : '')
			.($align ? ' sc_align'.$align : '')
			.(!empty($class) ? ' '.$class : '')
			.'"'
			.($s || $s2 ? ' style="'.($s ? 'display:block;' : '') . $s . $s2 . '"' : '')
			.'></span>'
		: '';
}

// ---------------------------------- [/trx_icon] ---------------------------------------





// ---------------------------------- [trx_image] ---------------------------------------


add_shortcode('trx_image', 'sc_image');

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/
function sc_image($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"src" => "",
		"url" => "",
		"icon" => "",
		"title" => "",
		"align" => "",
		"shape" => "square",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => "",
		"width" => "",
		"height" => ""
    ), $atts));
	$s = getStyleString('!'.$top, '!'.$right, '!'.$bottom, '!'.$left, $width, $height);
	$src = $src!='' ? $src : $url;
	if ($src > 0) {
		$attach = wp_get_attachment_image_src( $src, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$src = $attach[0];
	}
	if (!empty($width) || !empty($height)) {
		$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
		$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
		if ($w || $h) $src = getResizedImageURL($src, $w, $h);
	}

	// magnific & pretty
	themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
	themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
	// Load PrettyPhoto if it selected in Theme Options
	if (get_theme_option('popup_engine')=='pretty') {
		themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
		themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
	}

	return empty($src) ? '' : ('<figure' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_image ' . ($align && $align!='none' ? ' sc_image_align_' . $align : '') . (!empty($shape) ? ' sc_image_shape_'.$shape : '') . (!empty($class) ? ' '.$class : '') . '"'.($s!='' ? ' style="'.$s.'"' : '').'>'
				.'<img src="' . $src . '" alt="" />'.(trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="icon '.$icon.'"' : '').'>' . $title . '</span></figcaption>' : '') 
			. '</figure>');
}

// ---------------------------------- [/trx_image] ---------------------------------------






// ---------------------------------- [trx_infobox] ---------------------------------------


add_shortcode('trx_infobox', 'sc_infobox');

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/
function sc_infobox($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "regular",
		"closeable" => "no",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_infobox sc_infobox_style_' . $style . (sc_param_is_on($closeable) ? ' sc_infobox_closeable' : '') . (!empty($class) ? ' '.$class : '') . '"'.($s!='' ? ' style="'.$s.'"' : '').'>'
			. do_shortcode($content) 
			. '</div>';
}

// ---------------------------------- [/trx_infobox] ---------------------------------------





// ---------------------------------- [trx_line] ---------------------------------------


add_shortcode('trx_line', 'sc_line');

/*
[trx_line id="unique_id" style="none|solid|dashed|dotted|double|groove|ridge|inset|outset" top="margin_in_pixels" bottom="margin_in_pixels" width="width_in_pixels_or_percent" height="line_thickness_in_pixels" color="line_color's_name_or_#rrggbb"]
*/
function sc_line($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "solid",
		"color" => "",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left, $width)
		.($height !='' ? 'border-top-width:' . $height . 'px;' : '')
		.($style != '' ? 'border-top-style:' . $style . ';' : '')
		.($color != '' ? 'border-top-color:' . $color . ';' : '');
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_line' . ($style != '' ? ' sc_line_style_' . $style : '') . (!empty($class) ? ' '.$class : '') . '"'.($s!='' ? ' style="'.$s.'"' : '').'></div>';
}

// ---------------------------------- [/trx_line] ---------------------------------------





// ---------------------------------- [trx_list] ---------------------------------------

add_shortcode('trx_list', 'sc_list');

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/
$THEMEREX_sc_list_icon = '';
$THEMEREX_sc_list_style = '';
$THEMEREX_sc_list_counter = 0;
function sc_list($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "arrows",
		"icon" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	global $THEMEREX_sc_list_counter, $THEMEREX_sc_list_icon, $THEMEREX_sc_list_style;
	if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'arrows';
	if ($style == 'arrows' && trim($icon) == '') $icon = 'icon-right-open-big';
	$THEMEREX_sc_list_counter = 0;
	$THEMEREX_sc_list_icon = $icon;
	$THEMEREX_sc_list_style = $style;
	return '<' . ($style=='ol' ? 'ol' : 'ul') . ($id ? ' id="' . $id . '"' : '') . ' class="sc_list sc_list_style_' . $style . (!empty($class) ? ' '.$class : '') . '"' . ($s!='' ? ' style="'.$s.'"' : '') . '>'
			. do_shortcode($content) 
			. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
}


add_shortcode('trx_list_item', 'sc_list_item');

//[trx_list_item]
function sc_list_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"icon" => "",
		"title" => "",
		"link" => "",
		"target" => ""
	), $atts));
	global $THEMEREX_sc_list_counter, $THEMEREX_sc_list_icon, $THEMEREX_sc_list_style;
	$THEMEREX_sc_list_counter++;
	if (trim($icon) == '' || sc_param_is_inherit($icon)) $icon = $THEMEREX_sc_list_icon;
	return '<li' . ($id ? ' id="' . $id . '"' : '') 
		. ' class="sc_list_item' . ($icon!='' ? ' '.$icon : '') 
		. (!empty($class) ? ' '.$class : '')
		. ($THEMEREX_sc_list_counter % 2 == 1 ? ' odd' : ' even') 
		. ($THEMEREX_sc_list_counter == 1 ? ' first' : '')  
		. '"' 
		. ($title ? ' title="' . $title . '"' : '') 
		. '>' 
		. (!empty($link) ? '<a href="' . $link . '"' . (!empty($target) ? ' target="' . $target . '"' : '') . '>' : '')
		. do_shortcode($content)
		. (!empty($link) ? '</a>': '')
		. '</li>';
}

// ---------------------------------- [/trx_list] ---------------------------------------



// ---------------------------------- [trx_parallax] ---------------------------------------


add_shortcode('trx_parallax', 'sc_parallax');

/*
[trx_parallax id="unique_id" style="light|dark" dir="up|down" image="" color='']Content for parallax block[/trx_parallax]
*/
function sc_parallax($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"gap" => "no",
		"style" => "light",
		"dir" => "up",
		"speed" => 0.3,
		"video" => "",
		"video_ratio" => "16:9",
		"image" => "",
		"image_x" => "",
		"image_y" => "",
		"color" => "",
		"overlay" => "",
		"texture" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => "",
		"width" => "",
		"height" => ""
    ), $atts));
	if ($video!='') {
		$info = pathinfo($video);
		$ext = !empty($info['extension']) ? $info['extension'] : 'mp4';
		$video_ratio = empty($video_ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $video_ratio);
	}
	if ($image > 0) {
		$attach = wp_get_attachment_image_src( $image, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$image = $attach[0];
	}
	$image_x = $image_x!='' ? str_replace('%', '', $image_x).'%' : "50%";
	$image_y = $image_y!='' ? str_replace('%', '', $image_y).'%' : "50%";
	$speed = ($dir=='down' ? -1 : 1) * abs($speed);
	if ($overlay > 0) {
		if ($color=='') $color = apply_filters('theme_skin_get_theme_bgcolor', '#ffffff');
		$rgb = hex2rgb($color);
	}
	$s = getStyleString($top, '!'.$right, $bottom, '!'.$left, $width, $height)
		.($color !== '' && $overlay=='' ? 'background-color:' . $color . ';' : '')
		;
	return (sc_param_is_on($gap) ? sc_gap_start() : '')
		.'<div' . ($id ? ' id="' . $id . '"' : '').' class="sc_parallax' . ($video!='' ? ' sc_parallax_with_video' : '') . ($style!='' ? ' '.$style : '') . (!empty($class) ? ' '.$class : '') . '"' 
		. ($s!='' ? ' style="'.$s.'"' : '')
		. ' data-parallax-speed="' . $speed . '"'
		. ' data-parallax-x-pos="' . $image_x . '"'
		. ' data-parallax-y-pos="' . $image_y . '"'
		. '>'
		. ($video!='' ? '<div class="sc_video_bg_wrapper"><video class="sc_video_bg" width="1280" height="720" data-width="1280" data-height="720" preload="metadata" autoplay="autoplay" loop="loop" src="'.esc_attr($video).'" data-ratio="'.esc_attr($video_ratio).'" data-frame="no"><source src="'.$video.'" type="video/'.$ext.'"></source></video></div>' : '')
		. '<div class="sc_parallax_content" style="'.($image !== '' ? 'background-image:url(' . $image . '); background-position:'.$image_x.' '.$image_y.';' : '').'">'
		. ($overlay > 0 || $texture>0 ? '<div class="sc_parallax_overlay'.($texture!='' ? ' texture_bg_'.$texture : '').'" style="'.($overlay>0 ? 'background-color:rgba('.$rgb['r'].','.$rgb['g'].','.$rgb['b'].','.min(1, max(0, $overlay)).');' : '').'">' : '')
		. do_shortcode($content)
		. ($overlay > 0 || $texture!='' ? '</div>' : '')
		. '</div>'
		. '</div>'
		. (sc_param_is_on($gap) ? sc_gap_end() : '');
}
// ---------------------------------- [/trx_parallax] ---------------------------------------




// ---------------------------------- [trx_popup] ---------------------------------------

add_shortcode('trx_popup', 'sc_popup');

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/
function sc_popup($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left) . $style;

	// magnific & pretty
	themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
	themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
	// Load PrettyPhoto if it selected in Theme Options
	if (get_theme_option('popup_engine')=='pretty') {
		themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
		themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
	}

	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_popup sc_popup_light mfp-with-anim mfp-hide' . ($class ? ' '.$class : '') . '"'.($s!='' ? ' style="'.$s.'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
}
// ---------------------------------- [/trx_popup] ---------------------------------------






// ---------------------------------- [trx_price] ---------------------------------------


add_shortcode('trx_price', 'sc_price');

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]

*/
function sc_price($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"money" => "",
		"currency" => "$",
		"period" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$output = '';
	if (!empty($money)) {
		$s = getStyleString($top, $right, $bottom, $left);
		$m = explode('.', str_replace(',', '.', $money));
		if (count($m)==1) $m[1] = '';
		$output = '
			<div' . ($id ? ' id="' . $id . '"' : '').' class="sc_price_item'. (!empty($class) ? ' '.$class : '').'">
				<span class="sc_price_currency">'.$currency.'</span>
				<div class="sc_price_money">'.$m[0].'</div>
				<div class="sc_price_info">
					<div class="sc_price_penny">'.$m[1].'</div>
					<div class="sc_price_period">'.$period.'</div>
				</div>
			</div>
		';
	}
	return $output;
}

// ---------------------------------- [/trx_price] ---------------------------------------





// ---------------------------------- [trx_price_table] ---------------------------------------

add_shortcode('trx_price_table', 'sc_price_table');

/*
[trx_price_table id="unique_id" align="left|right|center"]
	[trx_price_item id="unique_id"]
		[trx_price_data id="unique_id" type="title|price|footer|united"]Et adipiscing integer.[/trx_price_data]
		[trx_price_data id="unique_id" type="title|price|footer"]Et adipiscing integer.[/trx_price_data]
		[trx_price_data id="unique_id" type="title|price|footer"]Et adipiscing integer.[/trx_price_data]
	[/trx_price_item]
	[trx_price_item]
		[trx_price_data id="unique_id" type="title|price|footer"]Et adipiscing integer.[/trx_price_data]
		[trx_price_data id="unique_id" type="title|price|footer"]Et adipiscing integer.[/trx_price_data]
		[trx_price_data id="unique_id" type="title|price|footer"]Et adipiscing integer.[/trx_price_data]
	[/trx_price_item]
[/trx_price_table]
*/
$THEMEREX_sc_price_table_counter = 0;
function sc_price_table($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"align" => "",
		"count" => 1,
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	global $THEMEREX_sc_price_table_counter;
	$THEMEREX_sc_price_table_counter = 0;
	$count = max(1, $count);
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_pricing_table columns_' . $count . ($align && $align!='none' ? ' align'.themerex_strtoproper($align) : '') . (!empty($class) ? ' '.$class : '') . '"' . ($s!='' ? ' style="'.$s.'"' : '') . '>'
			. do_shortcode($content)
		. '</div>';
}


add_shortcode('trx_price_item', 'sc_price_item');

//[trx_price_item]
function sc_price_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"animation" => "yes"
	), $atts));
	global $THEMEREX_sc_price_table_counter;
	$THEMEREX_sc_price_table_counter++;
	return '<div class="sc_pricing_columns sc_pricing_column_'.$THEMEREX_sc_price_table_counter. (!empty($class) ? ' '.$class : '').'"><ul'.(sc_param_is_on($animation) ? ' class="columnsAnimate"' : '') . ($id ? ' id="' . $id . '"' : '') . '>'
		. do_shortcode($content) 
		. '</ul></div>';
}


add_shortcode('trx_price_data', 'sc_price_data');

//[trx_price_data]
function sc_price_data($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"type" => "",
		"image" => "",
		"money" => "",
		"currency" => "$",
		"period" => ""
	), $atts));
	if (!in_array($type, array('title', 'price', 'footer', 'united', 'image'))) $type="";
	if ($type=='price' && $money!='') {
		$m = explode('.', str_replace(',', '.', $money));
		if (count($m)==1) $m[1] = '';
		$content = '
			<div class="sc_price_item'. (!empty($class) ? ' '.$class : '').'">
				<span class="sc_price_currency">'.$currency.'</span>
				<div class="sc_price_money">'.$m[0].'</div>
				<div class="sc_price_info">
					<div class="sc_price_penny">'.$m[1].'</div>
					<div class="sc_price_period">'.$period.'</div>
				</div>
			</div>
		';
	} else if ($type=='image' && $image!='') {
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$type = 'title_img';
		$content = '<img src="' . $image . '" alt="" />';
	} else
		$content = do_shortcode($content);
	return '<li' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_pricing_data' . ($type!='' ? ' sc_pricing_'.$type : '') . (!empty($class) ? ' '.$class : '') . '">' . $content . '</li>';
}

// ---------------------------------- [/trx_price_table] ---------------------------------------




// ---------------------------------- [trx_quote] ---------------------------------------


add_shortcode('trx_quote', 'sc_quote');

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/
function sc_quote($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"title" => "",
		"cite" => "",
		"top" => "",
		"bottom" => ""
    ), $atts));
	$s = getStyleString($top, '', $bottom);
	$cite_param = $cite != '' ? ' cite="' . $cite . '"' : '';
	$title = $title=='' ? $cite : $title;
	$content = do_shortcode($content);
	if (themerex_substr($content, 0, 2)!='<p') $content = '<p>' . $content . '</p>';
	return '<blockquote' . ($id ? ' id="' . $id . '"' : '') . $cite_param . ' class="sc_quote'. (!empty($class) ? ' '.$class : '').'"' . ($s!='' ? ' style="'.$s.'"' : '') . '>'
		. $content
		. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.$cite.'">' : '') . $title . ($cite!='' ? '</a>' : '') . '</p>'))
		.'</blockquote>';
}

// ---------------------------------- [/trx_quote] ---------------------------------------




// ---------------------------------- [trx_section] and [trx_block] ---------------------------------------

add_shortcode('trx_section', 'sc_section');
add_shortcode('trx_block', 'sc_section');

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/
$THEMEREX_sc_section_dedicated = '';

function sc_section($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "",
		"align" => "none",
		"columns" => "none",
		"dedicated" => "no",
		"pan" => "no",
		"scroll" => "no",
		"dir" => "horizontal",
		"controls" => "no",
		"bg_color" => "",
		"bg_image" => "",
		"bg_tint" => "",
		"color" => "",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));

	if ($bg_image > 0) {
		$attach = wp_get_attachment_image_src( $bg_image, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$bg_image = $attach[0];
	}

	$s = getStyleString('!'.$top, '!'.$right, '!'.$bottom, '!'.$left, $width, $height)
		.($color !== '' ? 'color:' . $color . ';' : '')
		.($bg_color !== '' ? 'background-color:' . $bg_color . ';' : '')
		.($bg_image !== '' ? 'background-image:url(' . $bg_image . ');' : '')
		.(!sc_param_is_off($pan) ? 'position:relative;' : '')
		.$style;

	if ((!sc_param_is_off($scroll) || !sc_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

	$output = '<div' . ($id ? ' id="' . $id . '"' : '') 
		. ' class="sc_section' 
			. ($class ? ' ' . $class : '') 
			. ($bg_tint ? ' bg_tint_' . $bg_tint : '') 
			. ($align=='left' ? ' sc_alignleft' : ($align=='right' ? ' sc_alignright' : ($align=='center' ? ' sc_aligncenter' : ''))) 
			. (!empty($columns) && $columns!='none' ? ' columns'.$columns : '') 
			. (sc_param_is_on($scroll) && !sc_param_is_off($controls) ? ' sc_scroll_controls sc_scroll_controls_'.$dir.' sc_scroll_controls_type_'.$controls : '')
		. '"'
		. ($s!='' ? ' style="'.$s.'"' : '').'>' 
		. (sc_param_is_on($scroll) 
			? '<div id="'.$id.'_scroll" class="sc_scroll sc_scroll_'.$dir.' swiper-slider-container scroll-container"'
				. ' style="'.($dir=='vertical' ? 'height:'.($height != '' ? $height.'px' : "230px").';' : 'width:'.($width != '' ? $width.';' : "100%;")).'"'
				. '>'
				. '<div class="sc_scroll_wrapper swiper-wrapper">' 
				. '<div class="sc_scroll_slide swiper-slide">' 
			: '')
		. (sc_param_is_on($pan) ? '<div id="'.$id.'_pan" class="sc_pan sc_pan_'.$dir.'">' : '')
		. do_shortcode($content) 
		. (sc_param_is_on($pan) ? '</div>' : '')
		. (sc_param_is_on($scroll) 
			? '</div></div><div id="'.$id.'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.$dir.' '.$id.'_scroll_bar"></div></div>'
				. (!sc_param_is_off($controls) ? '<ul class="flex-direction-nav"><li><a class="flex-prev" href="#"></a></li><li><a class="flex-next" href="#"></a></li></ul>' : '')
			: '')
		. '</div>';
	if (sc_param_is_on($dedicated)) {
		global $THEMEREX_sc_section_dedicated;
		if (empty($THEMEREX_sc_section_dedicated)) {
			$THEMEREX_sc_section_dedicated = $output;
		}
		$output = '';
	}

	if(sc_param_is_on($scroll)){
		themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
		themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

		themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
		themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
	}

	return $output;
}

function clear_dedicated_content() {	
	global $THEMEREX_sc_section_dedicated;
	$THEMEREX_sc_section_dedicated = '';
}

function get_dedicated_content() {	
	global $THEMEREX_sc_section_dedicated;
	return $THEMEREX_sc_section_dedicated;
}
// ---------------------------------- [/trx_section] ---------------------------------------





// ---------------------------------- [trx_skills] ---------------------------------------


add_shortcode('trx_skills', 'sc_skills');

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" maximum="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" level="50%"]
	[trx_skills_item title="Scelerisque pid" level="50%"]
	[trx_skills_item title="Scelerisque pid" level="50%"]
[/trx_skills]
*/
$THEMEREX_sc_skills_counter = 0;
$THEMEREX_sc_skills_columns = 0;
$THEMEREX_sc_skills_height = 0;
$THEMEREX_sc_skills_max = 100;
$THEMEREX_sc_skills_dir = '';
$THEMEREX_sc_skills_type = '';
$THEMEREX_sc_skills_color = '';
$THEMEREX_sc_skills_legend = '';
$THEMEREX_sc_skills_data = '';
$THEMEREX_sc_skills_style = '';
function sc_skills($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"type" => "bar",
		"dir" => "",
		"layout" => "",
		"count" => "",
		"align" => "",
		"color" => "",
		"style" => "1",
		"maximum" => "100",
		"title" => "",
		"subtitle" => __("Skills", "themerex"),
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	global $THEMEREX_sc_skills_counter, $THEMEREX_sc_skills_columns, $THEMEREX_sc_skills_height, $THEMEREX_sc_skills_max, $THEMEREX_sc_skills_dir, $THEMEREX_sc_skills_type, $THEMEREX_sc_skills_color, $THEMEREX_sc_skills_legend, $THEMEREX_sc_skills_data, $THEMEREX_sc_skills_style;
	$THEMEREX_sc_skills_counter = 0;
	$THEMEREX_sc_skills_columns = 0;
	$THEMEREX_sc_skills_height = 0;
	$THEMEREX_sc_skills_type = $type;
	$THEMEREX_sc_skills_color = $color;
	$THEMEREX_sc_skills_legend = '';
	$THEMEREX_sc_skills_data = '';
	if ($type!='arc') {
		if ($layout=='' || ($layout=='columns' && $count<1)) $layout = 'rows';
		if ($layout=='columns') $THEMEREX_sc_skills_columns = $count;
		if ($type=='bar') {
			if ($dir=='') $dir = 'horizontal';
			if ($dir == 'vertical') {
				if ($height < 1) $height = 300;
			}
		}
	} else {
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
	}
	if ($maximum < 1) $maximum = 100;
	if ($style) $THEMEREX_sc_skills_style = $style = max(1, min(4, $style));
	$THEMEREX_sc_skills_max = $maximum;
	$THEMEREX_sc_skills_dir = $dir;
	$THEMEREX_sc_skills_height = getStyleValue($height);
	$s = getStyleString($top, $right, $bottom, $left, $width, $height)
		.($align != '' && $align != 'none' ? 'float:' . $align . ';' : '');
	$content = do_shortcode($content);

	themerex_enqueue_script( 'diagram-chart', themerex_get_file_url('/js/diagram/chart.min.js'), array(), null, true );
	themerex_enqueue_script( 'diagram-raphael', themerex_get_file_url('/js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );

	themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
	themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

	themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );

	return ($type!='arc' && $title!='' ? '<h2>'.$title.'</h2>' : '')
			. '<div' 
				. ($id ? ' id="' . $id . '"' : '') 
				. ' class="sc_skills sc_skills_' . $type . ($type=='bar' ? ' sc_skills_'.$dir : '') . (!empty($class) ? ' '.$class : '') . '"'
				. ($s!='' ? ' style="'.$s.'"' : '')
				. ' data-type="'.esc_attr($type).'"'
				. ' data-subtitle="'.esc_attr($subtitle).'"'
				. ($type=='bar' ? ' data-dir="'.$dir.'"' : '')
			. '>'
				. ($layout == 'columns' ? '<div class="columnsWrap sc_skills_'.$layout.'">' : '')
				. ($type=='arc' 
					? ('<div class="sc_skills_legend">'.($title!='' ? '<h2>'.$title.'</h2>' : '').'<ul>'.$THEMEREX_sc_skills_legend.'</ul></div>'
						. '<div id="'.$id.'_diagram" class="sc_skills_arc_canvas"></div>'
						. '<div class="sc_skills_data" style="display:none;">'
						. $THEMEREX_sc_skills_data
						. '</div>'
					  )
					: '')
				. $content
				. ($layout == 'columns' ? '</div>' : '')
			. '</div>';
}


add_shortcode('trx_skills_item', 'sc_skills_item');

//[trx_skills_item]
function sc_skills_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"title" => "",
		"level" => "",
		"color" => "",
		"style" => ""
	), $atts));
	global $THEMEREX_sc_skills_counter, $THEMEREX_sc_skills_columns, $THEMEREX_sc_skills_height, $THEMEREX_sc_skills_max, $THEMEREX_sc_skills_dir, $THEMEREX_sc_skills_type, $THEMEREX_sc_skills_color, $THEMEREX_sc_skills_legend, $THEMEREX_sc_skills_data, $THEMEREX_sc_skills_style, $THEMEREX_sc_skills_title;
	$THEMEREX_sc_skills_counter++;
	$ed = themerex_substr($level, -1)=='%' ? '%' : '';
	$level = (int) str_replace('%', '', $level);
	$percent = round($level / $THEMEREX_sc_skills_max * 100);
	$start = 0;
	$stop = $level;
	$steps = 100;
	$step = max(1, round($THEMEREX_sc_skills_max/$steps));
	$speed = mt_rand(10,40);
	$animation = round(($stop - $start) / $step * $speed);
	$title_block = '<div class="sc_skills_info">' . $title . '</div>';
	$old_color = $color;
	if (empty($color)) $color = $THEMEREX_sc_skills_color;
	if (empty($color)) $color = get_custom_option('theme_color');
	$color = apply_filters('theme_skin_get_theme_color', $color);
	if ($style) $style = max(1, min(4, $style));
	if (empty($style)) $style = $THEMEREX_sc_skills_style;
	$style = max(1, min(4, $style));
	$output = '';
	if ($THEMEREX_sc_skills_type=='arc') {
		if (empty($old_color)) {
			$rgb = Hex2RGB($color);
			$color = 'rgba('.$rgb['r'].','.$rgb['g'].','.$rgb['b'].','.(1 - 0.1*($THEMEREX_sc_skills_counter-1)).')';
		}
		$THEMEREX_sc_skills_legend .= '<li style="background-color:'.$color.'">' . $title . '</li>';
		$THEMEREX_sc_skills_data .= '<div' . ($id ? ' id="' . $id . '"' : '').' class="arc'. (!empty($class) ? ' '.$class : '').'"><input type="hidden" class="text" value="'.$title.'" /><input type="hidden" class="percent" value="'.$percent.'" /><input type="hidden" class="color" value="'.$color.'" /></div>';
	} else {
		$output .= ($THEMEREX_sc_skills_columns > 0 ? '<div class="sc_skills_column columns1_'.$THEMEREX_sc_skills_columns.'">' : '')
				. ($THEMEREX_sc_skills_type=='bar' && $THEMEREX_sc_skills_dir=='horizontal' ? $title_block : '')
				. '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.$style : '') . ($THEMEREX_sc_skills_counter % 2 == 1 ? ' odd' : ' even') . ($THEMEREX_sc_skills_counter == 1 ? ' first' : '') . '"'
					. ($THEMEREX_sc_skills_height !='' ? ' style="height: '.$THEMEREX_sc_skills_height.';"' : '')
				. '>';
		if (in_array($THEMEREX_sc_skills_type, array('bar', 'counter'))) {
			$output .= '<div class="sc_skills_count'. (!empty($class) ? ' '.$class : '').'"' . ($THEMEREX_sc_skills_type=='bar' && $color ? ' style="background-color:' . $color . '; border-color:' . $color . '"' : '') . '>'
						. '<div class="sc_skills_total"'
							. ' data-start="'.$start.'"'
							. ' data-stop="'.$stop.'"'
							. ' data-step="'.$step.'"'
							. ' data-max="'.$THEMEREX_sc_skills_max.'"'
							. ' data-speed="'.$speed.'"'
							. ' data-duration="'.$animation.'"'
							. ' data-ed="'.$ed.'">'
							. $start . $ed
						.'</div>'
					. '</div>';
		} else if ($THEMEREX_sc_skills_type=='pie') {
			if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
			$output .= '<canvas id="'.$id.'"></canvas>'
				. '<div class="sc_skills_total'. (!empty($class) ? ' '.$class : '').'"'
					. ' data-start="'.$start.'"'
					. ' data-stop="'.$stop.'"'
					. ' data-step="'.$step.'"'
					. ' data-steps="'.$steps.'"'
					. ' data-max="'.$THEMEREX_sc_skills_max.'"'
					. ' data-speed="'.$speed.'"'
					. ' data-duration="'.$animation.'"'
					. ' data-color="'.$color.'"'
					. ' data-easing="easeOutCirc"'
					. ' data-ed="'.$ed.'">'
					. $start . $ed
				.'</div>';
		}
		$output .= 
				  ($THEMEREX_sc_skills_type=='counter' ? $title_block : '')
				. '</div>'
				. ($THEMEREX_sc_skills_type=='bar' && $THEMEREX_sc_skills_dir=='vertical' || $THEMEREX_sc_skills_type == 'pie' ? $title_block : '')
				. ($THEMEREX_sc_skills_columns > 0 ? '</div>' : '');
	}
	return $output;
}

// ---------------------------------- [/trx_skills] ---------------------------------------






// ---------------------------------- [trx_slider] ---------------------------------------

add_shortcode('trx_slider', 'sc_slider');

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="category_id or slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

$THEMEREX_sc_slider_engine = '';
$THEMEREX_sc_slider_width = 0;
$THEMEREX_sc_slider_height = 0;
$THEMEREX_sc_slider_links = false;

function sc_slider($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"engine" => get_custom_option('substitute_slider_engine'),
		"chop_effect" => "",
		"alias" => "",
		"ids" => "",
		"cat" => "",
		"count" => "0",
		"offset" => "",
		"orderby" => "date",
		"order" => 'desc',
		"border" => "none",
		"controls" => "no",
		"pagination" => "no",
		"titles" => "no",
		"descriptions" => get_custom_option('slider_descriptions'),
		"links" => "no",
		"align" => "",
		"interval" => "",
		"date_format" => "",
		"crop" => "on",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));

	global $THEMEREX_sc_slider_engine, $THEMEREX_sc_slider_width, $THEMEREX_sc_slider_height, $THEMEREX_sc_slider_links;
	
	if (empty($width)) $width = "100%";
	if (empty($interval)) $interval = mt_rand(5000, 10000);
	if ($engine=='chop' && !file_exists(themerex_get_file_dir('/js/chopslider/jquery.id.chopslider-2.0.0.free.min.js'))) {
		$engine='swiper';
	}
	if ($engine=='chop' && empty($chop_effect)) {
		$effects2D = array("vertical", "horizontal", "half", "multi");
		$effects3D  = array("3DBlocks", "3DFlips");
		$chop_effect = $effects2D[min(3, mt_rand(0,3))].'|'.$effects3D[min(1, mt_rand(0,1))];
	}
	
	$THEMEREX_sc_slider_engine = $engine;
	$THEMEREX_sc_slider_width = getStyleValue($width);
	$THEMEREX_sc_slider_height = getStyleValue($height);
	$THEMEREX_sc_slider_links = sc_param_is_on($links);

	if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());
	
	$ms = getStyleString($top, $right, $bottom, $left);
	$ws = getStyleString('', '', '', '', $width);
	$hs = getStyleString('', '', '', '', '', $height);

	$s = ($border=='none' && !in_array($pagination, array('full', 'over')) ? $ms : '') . $hs . $ws;
	
	if ($border!='none' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
	if ($engine!='flex' && $engine!='chop' && $engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
	
	$output = ($border!='none' 
				? '<div class="sc_border sc_border_'.$border.($align!='' && $align!='none' ? ' sc_align'.$align : '').'"'.($ms.$hs ? ' style="'.$ms.$hs.'"' : '').'>' 
				: '')
			. (in_array($pagination, array('full', 'over')) 
				? '<div class="sc_slider_pagination_area sc_slider_pagination_'.$pagination.'"'
					.($ms.$hs ? ' style="'.$ms.$hs.'"' : '') .'>' 
				: '')
			. '<div' . ($id ? ' id="' . $id . '"' : '') 
			. ' class="sc_slider'
				. (!empty($class) ? ' '.$class : '')
				. ' sc_slider_' . $engine
				. (sc_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
				. (sc_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
				. ($border=='none' && $align!='' && $align!='none' ? ' sc_align'.$align : '')
				. ($engine=='swiper' ? ' swiper-slider-container' : '')
				. '"'
			. ((int) $interval > 0 ? ' data-interval="'.$interval.'"' : '')
			. ($engine=='chop' ? ' data-effect="'.$chop_effect.'"' : '')
			. ($s!='' ? ' style="'.$s.'"' : '')
		. '>';
	$pagination_items = '';

	if ($engine=='revo') {
		if (revslider_exists() && !empty($alias))
			$output .= do_shortcode('[rev_slider '.$alias.']');
		else
			$output = '';
	} else if ($engine=='royal') {
		if (royalslider_exists() && !empty($alias))
			$output .= do_shortcode('[[new_royalslider id="'.$alias.'"]');
		else
			$output = '';
	} else if ($engine=='flex' || $engine=='chop' || $engine=='swiper') {
		
		$imageAsBackground = $engine!='chop';
		$caption = '';
		
		$output .= '<ul class="slides'.($engine=='swiper' ? ' swiper-wrapper' : '').'">';

		$content = do_shortcode($content);
		
		if ($content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
		
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => 1,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
	
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
	
			$args = addSortOrderInQuery($args, $orderby, $order);
			$args = addFiltersInQuery($args, array('thumbs'));
			$args = addPostsAndCatsInQuery($args, $ids, $cat);

			$query = new WP_Query( $args );

			$numSlide = 0;
	
			while ( $query->have_posts() ) { 
				$query->the_post();
				$numSlide++;
				$post_id = get_the_ID();
				$post_title = get_the_title();
				$post_link = get_permalink();
				$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
				$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
				if (sc_param_is_on($crop)) {
					$post_attachment = $imageAsBackground 
						? getResizedImageURL($post_attachment, !empty($width) && themerex_strpos($width, '%')===false ? $width : null, !empty($height) && themerex_strpos($height, '%')===false ? $height : null)
						: getResizedImageTag($post_attachment, !empty($width) && themerex_strpos($width, '%')===false ? $width : null, !empty($height) && themerex_strpos($height, '%')===false ? $height : null);
				} else if (!$imageAsBackground) {
					$post_attachment = '<img src="'.$post_attachment.'" alt="">';
				}
				$post_accent_color = '';
				$post_category = '';
				$post_category_link = '';

				if (in_array($pagination, array('full', 'over'))) {
					// Get all post's tags
					$post_tags_links = '';
					if (($post_tags_list = get_the_tags()) != 0) {
						$tag_number=0;
						foreach ($post_tags_list as $tag) {
							$tag_number++;
							$post_tags_links .= '<span class="slide_tag">' . $tag->name . ($tag_number==count($post_tags_list) ? '' : ',') . '</span> ';
						}
					}
					$pagination_items .= '<li'.(empty($pagination_items) ? ' class="'.($engine=='chop' ? 'cs-active-pagination' : 'active').'"' : '').'>' 
						.'<div class="slide_pager">'
						.'<div class="slide_date">'.$post_date.'</div>'
						.'<div class="slide_info">'
						.'<h4 class="slide_title">'.$post_title.'</h4>'
						.'<div class="slide_tags">'.$post_tags_links.'</div>'
						.'</div>'
						.'</div>'
						.'</li>'
						;
				}
				$output .= '<li' 
					. ' class="'.$engine.'-slide' . ($engine=='chop' && $numSlide==1 ? ' cs-activeSlide': '') . '"'
					. ' style="'
						. ($engine=='chop' && $numSlide==1 ? 'display:block;' : '')
						. ($imageAsBackground ? 'background-image:url(' . $post_attachment . ');' : '')
						. $ws 
						. $hs
						. '"'
					. '>' 
					. (sc_param_is_on($links) ? '<a href="'.$post_link.'" title="'.htmlspecialchars($post_title).'">' : '')
					. (!$imageAsBackground ? $post_attachment : '')
					;
				$caption = $engine=='swiper' || $engine=='flex' ? '' : $caption;
				if (!sc_param_is_off($titles)) {
					$post_hover_bg  = get_custom_option('theme_color', null, $post_id);
					$post_bg = '';
					if ($post_hover_bg!='' && !is_inherit_option($post_hover_bg)) {
						$rgb = Hex2RGB($post_hover_bg);
						$post_hover_ie = str_replace('#', '', $post_hover_bg);
						$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
					}
					$caption .= ($engine=='chop' ? '<div class="sc_slider_info_item">' : '') . '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.$post_bg.'"' : '').'>';
					$post_descr = getPostDescription();
					if (get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
						// Get all post's categories
						$post_categories = getCategoriesByPostId($post_id);
						$post_categories_str = '';
						for ($i = 0; $i < count($post_categories); $i++) {
							if ($post_category=='') {
								if (get_theme_option('close_category')=='parental') {
									$parent_cat_id = 0;//(int) get_custom_option('category_id');
									$parent_cat = getParentCategory($post_categories[$i]['term_id'], $parent_cat_id);
									if ($parent_cat) {
										$post_category = $parent_cat['name'];
										$post_category_link = $parent_cat['link'];
										if ($post_accent_color=='') $post_accent_color = get_category_inherited_property($parent_cat['term_id'], 'theme_color');
									}
								} else {
									$post_category = $post_categories[$i]['name'];
									$post_category_link = $post_categories[$i]['link'];
									if ($post_accent_color=='') $post_accent_color = get_category_inherited_property($post_categories[$i]['term_id'], 'theme_color');
								}
							}
							if ($post_category!='' && $post_accent_color!='') break;
						}
						if ($post_category=='' && count($post_categories)>0) {
							$post_category = $post_categories[0]['name'];
							$post_category_link = $post_categories[0]['link'];
							if ($post_accent_color=='') $post_accent_color = get_category_inherited_property($post_categories[0]['term_id'], 'theme_color');
						}
						if ($post_category!='') {
							$caption .= '<div class="sc_slider_category"'.(themerex_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.$post_accent_color.'"' : '').'><a href="'.$post_category_link.'">'.$post_category.'</a></div>';
						}
					}
					$output_reviews = '';
					if (get_custom_option('show_reviews')=='yes' && get_custom_option('slider_reviews')=='yes') {
						$avg_author = marksToDisplay(get_post_meta($post_id, 'reviews_avg'.((get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
						if ($avg_author > 0) {
							$output_reviews .= '<div class="sc_slider_reviews reviews_summary blog_reviews' . (get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
								. '<div class="criteria_summary criteria_row">' . getReviewsSummaryStars($avg_author) . '</div>'
								. '</div>';
						}
					}
					if (get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
					$caption .= '<h2 class="sc_slider_subtitle"><a href="'.$post_link.'">'.$post_title.'</a></h2>';
					if (get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
					if ($descriptions > 0) {
						$caption .= '<div class="sc_slider_descr">'.getShortString($post_descr, $descriptions).'</div>';
					}
					$caption .= '</div>' . ($engine=='chop' ? '</div>' : '');
				}
				$output .= ($engine=='swiper' || $engine=='flex' ? $caption : '') . (sc_param_is_on($links) ? '</a>' : '' ) . '</li>';
			}
			wp_reset_postdata();
		}

		$output .= '</ul>';
		if ($engine=='swiper' || $engine=='chop') {
			if (sc_param_is_on($controls))
				$output .= '
					<ul class="flex-direction-nav">
					<li><a class="flex-prev" href="#"></a></li>
					<li><a class="flex-next" href="#"></a></li>
					</ul>';
			if (sc_param_is_on($pagination))
				$output .= '<div class="flex-control-nav"></div>';
		}
		if ($engine=='chop') {
			$output .= '
				<div class="sc_slider_info_slides">'.$caption.'</div>
				<div class="sc_slider_info_holder"></div>
				';
		}
	
	} else
		$output = '';
	
	if (!empty($output)) {
		$output .= '</div>' . ($border!='none' ? '</div>' : '');
		if ($pagination_items) {
			$output .= '
				<div class="flex-control-nav manual"'.($hs ? ' style="'.$hs.'"' : '').'>
					<div id="'.$id.'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.$hs.'"' : '').'>
						<div class="sc_scroll_wrapper swiper-wrapper">
							<div class="sc_scroll_slide swiper-slide">
								<ul>'.$pagination_items.'</ul>
							</div>
						</div>
						<div id="'.$id.'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
					</div>
				</div>';
			$output .= '</div>';
		}
	}

	themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
	themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

	themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'flexslider', themerex_get_file_url('/js/jquery.flexslider.min.js'), array('jquery'), null, true );

	return $output;
}


add_shortcode('trx_slider_item', 'sc_slider_item');

//[trx_slider_item]
function sc_slider_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"src" => "",
		"url" => ""
	), $atts));
	global $THEMEREX_sc_slider_engine, $THEMEREX_sc_slider_width, $THEMEREX_sc_slider_height, $THEMEREX_sc_slider_links;
	$src = $src!='' ? $src : $url;
	if ($src > 0) {
		$attach = wp_get_attachment_image_src( $src, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$src = $attach[0];
	}
	return empty($src) ? '' : ('<li' . ($id ? ' id="' . $id . '"' : '').' class="' . $THEMEREX_sc_slider_engine.'-slide' . (!empty($class) ? ' '.$class : '') . '"'
		. ' style="background-image:url(' . $src . ');'
			. (!empty($THEMEREX_sc_slider_width) ? 'width:' . $THEMEREX_sc_slider_width . ';' : '')
			. (!empty($THEMEREX_sc_slider_height) ? 'height:' . $THEMEREX_sc_slider_height . ';' : '')
		.'">' 
		. (sc_param_is_on($THEMEREX_sc_slider_links) ? '<a href="'.($src ? $src : $url).'"></a>' : '')
		. '</li>');
}
// ---------------------------------- [/trx_slider] ---------------------------------------





// ---------------------------------- [trx_table] ---------------------------------------


add_shortcode('trx_table', 'sc_table');

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/
function sc_table($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "1",
		"size" => "big",
		"align" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	$content = str_replace(
				array('<p><table', 'table></p>', '><br />'),
				array('<table', 'table>', '>'),
				html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_table sc_table_style_' . max(1, min(4, $style)) . ' sc_table_size_' . $size . (!empty($align) ? ' sc_table_align_'.$align : '') . (!empty($class) ? ' '.$class : '') . '"'.($s!='' ? ' style="'.$s.'"' : '') .'>' 
			. do_shortcode($content) 
			. '</div>';
}

// ---------------------------------- [/trx_table] ---------------------------------------




// ---------------------------------- [trx_tabs] ---------------------------------------

add_shortcode("trx_tabs", "sc_tabs");

/*
[trx_tabs id="unique_id" tab_names="Planning|Development|Support" style="1|2" initial="1 - num_tabs"]
	[trx_tab]Randomised words which don't look even slightly believable. If you are going to use a passage. You need to be sure there isn't anything embarrassing hidden in the middle of text established fact that a reader will be istracted by the readable content of a page when looking at its layout.[/trx_tab]
	[trx_tab]Fact reader will be distracted by the <a href="#" class="main_link">readable content</a> of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have evolved over. There are many variations of passages of Lorem Ipsum available, but the majority.[/trx_tab]
	[trx_tab]Distracted by the  readable content  of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have  evolved over.  There are many variations of passages of Lorem Ipsum available.[/trx_tab]
[/trx_tabs]
*/
$THEMEREX_sc_tab_counter = 0;
$THEMEREX_sc_tab_scroll = "no";
$THEMEREX_sc_tab_height = 0;
$THEMEREX_sc_tab_id = '';
$THEMEREX_sc_tab_titles = array();
function sc_tabs($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"tab_names" => "",
		"initial" => "1",
		"scroll" => "no",
		"style" => "1",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left, $width);
	global $THEMEREX_sc_tab_counter, $THEMEREX_sc_tab_id, $THEMEREX_sc_tab_scroll, $THEMEREX_sc_tab_height, $THEMEREX_sc_tab_titles;
	$THEMEREX_sc_tab_counter = 0;
	$THEMEREX_sc_tab_scroll = $scroll;
	$THEMEREX_sc_tab_height = getStyleValue($height);
	$THEMEREX_sc_tab_id = $id ? $id : 'sc_tabs_'.str_replace('.', '', mt_rand());
	$THEMEREX_sc_tab_titles = array();
	if (!empty($tab_names)) {
		$title_chunks = explode("|", $tab_names);
		for ($i = 0; $i < count($title_chunks); $i++) {
			$THEMEREX_sc_tab_titles[] = array(
				'id' => $THEMEREX_sc_tab_id.'_'.($i+1),
				'title' => $title_chunks[$i]
			);
		}
	}
	$content = do_shortcode($content);
	$initial = max(1, min(count($THEMEREX_sc_tab_titles), (int) $initial));
	$tabs_output = '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_tabs sc_tabs_style_'.$style. (!empty($class) ? ' '.$class : '') . '"'.($s!='' ? ' style="'.$s.'"' : '') .' data-active="' . ($initial-1) . '">'
					.'<ul class="sc_tabs_titles">';
	$titles_output = '';
	for ($i = 0; $i < count($THEMEREX_sc_tab_titles); $i++) {
		$classes = array('tab_names');
		if ($i == 0) $classes[] = 'first';
		else if ($i == count($THEMEREX_sc_tab_titles) - 1) $classes[] = 'last';
		$titles_output .= '<li class="'.join(' ', $classes).'"><a href="#'.$THEMEREX_sc_tab_titles[$i]['id'].'" class="theme_button" id="'.$THEMEREX_sc_tab_titles[$i]['id'].'_tab">' . $THEMEREX_sc_tab_titles[$i]['title'] . '</a></li>';
	}

	themerex_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);

	$tabs_output .= $titles_output
		. '</ul>' 
		. $content
		.'</div>';
	return $tabs_output;
}


add_shortcode("trx_tab", "sc_tab");

//[trx_tab id="tab_id"]
function sc_tab($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"tab_id" => "",		// get it from VC
		"title" => ""		// get it from VC
    ), $atts));
	global $THEMEREX_sc_tab_counter, $THEMEREX_sc_tab_id, $THEMEREX_sc_tab_scroll, $THEMEREX_sc_tab_height, $THEMEREX_sc_tab_titles;
	$THEMEREX_sc_tab_counter++;
	if (empty($id))
		$id = !empty($tab_id) ? $tab_id : $THEMEREX_sc_tab_id.'_'.$THEMEREX_sc_tab_counter;
	if (isset($THEMEREX_sc_tab_titles[$THEMEREX_sc_tab_counter-1])) {
		$THEMEREX_sc_tab_titles[$THEMEREX_sc_tab_counter-1]['id'] = $id;
		if (!empty($title))
			$THEMEREX_sc_tab_titles[$THEMEREX_sc_tab_counter-1]['title'] = $title;
	} else {
		$THEMEREX_sc_tab_titles[] = array(
			'id' => $id,
			'title' => $title
		);
	}
	return '<div id="' . $id . '" class="sc_tabs_content' . ($THEMEREX_sc_tab_counter % 2 == 1 ? ' odd' : ' even') . ($THEMEREX_sc_tab_counter == 1 ? ' first' : '') . (!empty($class) ? ' '.$class : '') . '">' 
		. (sc_param_is_on($THEMEREX_sc_tab_scroll) ? '<div id="'.$id.'_scroll" class="sc_scroll sc_scroll_vertical" style="height:'.($THEMEREX_sc_tab_height != '' ? $THEMEREX_sc_tab_height : '230px').';"><div class="sc_scroll_wrapper swiper-wrapper"><div class="sc_scroll_slide swiper-slide">' : '')
		. do_shortcode($content) 
		. (sc_param_is_on($THEMEREX_sc_tab_scroll) ? '</div></div><div id="'.$id.'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical '.$id.'_scroll_bar"></div></div>' : '')
		. '</div>';
}
// ---------------------------------- [/trx_tabs] ---------------------------------------






// ---------------------------------- [trx_team] ---------------------------------------


add_shortcode('trx_team', 'sc_team');

/*
[trx_team id="unique_id" style="normal|big"]
	[trx_team_item user="user_login"]
[/trx_team]
*/
$THEMEREX_sc_team_count = 0;
$THEMEREX_sc_team_counter = 0;
function sc_team($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"count" => 0,
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	global $THEMEREX_sc_team_count, $THEMEREX_sc_team_counter;
	$THEMEREX_sc_team_count = $count = max(1, min(5, $count));
	$THEMEREX_sc_team_counter = 0;
	$content = do_shortcode($content);
	return '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_team'.(!empty($class) ? ' '.$class : '').'"'.($s!='' ? ' style="'.$s.'"' : '') .'>'
				. '<div class="sc_columns columnsWrap">'
					. $content
				. '</div>'
			. '</div>';
}


add_shortcode('trx_team_item', 'sc_team_item');

//[trx_team_item]
function sc_team_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"user" => "",
		"name" => "",
		"position" => "",
		"photo" => "",
		"email" => "",
		"socials" => ""
	), $atts));
	global $THEMEREX_sc_team_counter, $THEMEREX_sc_team_count;
	$THEMEREX_sc_team_counter++;
	$descr = do_shortcode($content);
	if (!empty($user) && $user!='none' && ($user_obj = get_user_by('login', $user)) != false) {
		$meta = get_user_meta($user_obj->ID);
		if (empty($email))		$email = $user_obj->data->user_email;
		if (empty($name))		$name = $user_obj->data->display_name;
		if (empty($position))	$position = isset($meta['user_position'][0]) ? $meta['user_position'][0] : '';
		if (empty($descr))		$descr = isset($meta['description'][0]) ? $meta['description'][0] : '';
		if (empty($socials))	$socials = showUserSocialLinks(array('author_id'=>$user_obj->ID, 'echo'=>false, 'before'=>'<li>', 'after' => '</li>'));
	} else {
		//global $THEMEREX_user_social_list;
		$allowed = explode('|', $socials);
		$socials = '';
		for ($i=0; $i<count($allowed); $i++) {
			$s = explode('=', $allowed[$i]);
			if (!empty($s[1])) {	// && array_key_exists($s[0], $THEMEREX_user_social_list)) {
				$img = themerex_get_socials_url($s[0]);
				$socials .= '<li><a href="' . $s[1] . '" class="social_icons social_' . $s[0] . ' ' . $s[0] . '" target="_blank" style="background-image: url('.$img.');">'
						. '<span style="background-image: url('.$img.');"></span>'
						. '</a></li>';
			}
		}
	}
	if (empty($photo)) {
		if (!empty($email)) $photo = get_avatar($email, 370);
	} else {
		if ($photo > 0) {
			$attach = wp_get_attachment_image_src( $photo, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$photo = $attach[0];
		}
		$photo = getResizedImageTag($photo, 370, 370);
	}
	if (!empty($name) && !empty($position)) {
		return '<div class="columns1_'.$THEMEREX_sc_team_count . (!empty($class) ? ' '.$class : '').'">'
				. '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_team_item sc_team_item_' . $THEMEREX_sc_team_counter . ($THEMEREX_sc_team_counter % 2 == 1 ? ' odd' : ' even') . ($THEMEREX_sc_team_counter == 1 ? ' first' : '') . '">'
					. '<div class="sc_team_item_avatar">'
						. $photo
						. '<div class="sc_team_item_description">' . $descr . '</div>'
					. '</div>'
					. '<div class="sc_team_item_info">'
					. '<h3 class="sc_team_item_title">' . $name . '</h3>'
					. '<div class="sc_team_item_position theme_accent2">' . $position . '</div>'
					. (!empty($socials) ? '<ul class="sc_team_item_socials">' . $socials . '</ul>' : '')
					. '</div>'
				. '</div>'
			. '</div>';
	}
	return '';
}

// ---------------------------------- [/trx_team] ---------------------------------------






// ---------------------------------- [trx_testimonials] ---------------------------------------


add_shortcode('trx_testimonials', 'sc_testimonials');

/*
[trx_testimonials id="unique_id" style="1|2|3"]
	[trx_testimonials_item user="user_login"]Testimonials text[/trx_testimonials_item]
	[trx_testimonials_item email="" name="" position="" photo="photo_url" socials="twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile"]Testimonials text[/trx_testimonials]
[/trx_testimonials]
*/

$THEMEREX_sc_testimonials_count = 0;
$THEMEREX_sc_testimonials_width = 0;
$THEMEREX_sc_testimonials_height = 0;
function sc_testimonials($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"title" => "",
		"style" => "1",
		"controls" => "top",
		"interval" => "",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	$d = getStyleString('', '', '', '', $width, $height);
	global $THEMEREX_sc_testimonials_count, $THEMEREX_sc_testimonials_width, $THEMEREX_sc_testimonials_height;
	$THEMEREX_sc_testimonials_count = 0;
	$THEMEREX_sc_testimonials_width = getStyleValue($width);
	$THEMEREX_sc_testimonials_height = getStyleValue($height);
	$content = do_shortcode($content);

	themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
	themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

	themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'flexslider', themerex_get_file_url('/js/jquery.flexslider.min.js'), array('jquery'), null, true );

	return ($title && $style>1 ? '<h2 class="sc_testimonials_title">'.$title.'</h2>' : '')
		. '<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_testimonials sc_testimonials_style_'.$style.($title && $style==1 || $controls=='top' ? ' sc_testimonials_padding' : '').(sc_param_is_off($controls) ? '' : ' sc_testimonials_controls_'.$controls) . (!empty($class) ? ' '.$class : '') .'"' . ($s!='' ? ' style="'.$s.'"' : '') . '>'
			. ($title && $style==1 ? '<h2 class="sc_testimonials_title">'.$title.'</h2>' : '')
			. ($THEMEREX_sc_testimonials_count>1 ? '<div class="sc_slider sc_slider_swiper'.(sc_param_is_off($controls) ? '' : ' sc_slider_controls sc_slider_controls_'.$controls).' sc_slider_nopagination sc_slider_autoheight swiper-slider-container"'.($d ? ' style="'.$d.'"' : '') . ((int) $interval > 0 ? ' data-interval="'.$interval.'"' : '') . '>' : '')
				. '<ul class="sc_testimonials_items'.($THEMEREX_sc_testimonials_count>1 ? ' slides swiper-wrapper' : '').'">'
				. $content
				. '</ul>'
			. ($THEMEREX_sc_testimonials_count>1 ? '</div>'.(sc_param_is_off($controls) ? '' : '<ul class="flex-direction-nav"><li><a class="flex-prev" href="#"></a></li><li><a class="flex-next" href="#"></a></li></ul>') : '')
		. '</div>';
}


add_shortcode('trx_testimonials_item', 'sc_testimonials_item');

function sc_testimonials_item($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"name" => "",
		"position" => "",
		"photo" => "",
		"email" => ""
    ), $atts));
	global $THEMEREX_sc_testimonials_count, $THEMEREX_sc_testimonials_width, $THEMEREX_sc_testimonials_height;
	$THEMEREX_sc_testimonials_count++;
	if (empty($photo)) {
		if (!empty($email))
			$photo = get_avatar($email, 50);
	} else {
		if ($photo > 0) {
			$attach = wp_get_attachment_image_src( $photo, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$photo = $attach[0];
		}
		$photo = getResizedImageTag($photo, 50, 50);
	}
	if (empty($photo)) $photo = '<img src="'.themerex_get_file_url('/images/no-ava.png').'" alt="">';
	return '<li' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_testimonials_item swiper-slide'. (!empty($class) ? ' '.$class : '').'" style="'.(!empty($THEMEREX_sc_testimonials_width) ? 'width:' . $THEMEREX_sc_testimonials_width . ';' : '').(!empty($THEMEREX_sc_testimonials_height) ? 'height:' . $THEMEREX_sc_testimonials_height . ';' : '').'">'
				. '<div class="sc_testimonials_item_content">'
					. '<div class="sc_testimonials_item_quote"><div class="sc_testimonials_item_text">'.do_shortcode($content).'</div></div>'
					. '<div class="sc_testimonials_item_author">'
						. '<div class="sc_testimonials_item_avatar">'.$photo.'</div>'
						. '<div class="sc_testimonials_item_name">'.$name.'</div>'
						. '<div class="sc_testimonials_item_position">'.$position.'</div>'
					. '</div>'
					. '<div class="sc_testimonials_item_object"><div class="object"></div></div>'
				. '</div>'
			. '</li>';
}

// ---------------------------------- [/trx_testimonials] ---------------------------------------





// ---------------------------------- [trx_title] ---------------------------------------


add_shortcode('trx_title', 'sc_title');

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/
function sc_title($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"type" => "1",
		"style" => "regular",
		"background" => "none",
		"bg_color" => "",
		"color" => "",
		"icon" => "",
		"image" => "",
		"picture" => "",
		"size" => "medium",
		"position" => "top",
		"align" => "left",
		"weight" => "inherit",
		"width" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	if (empty($align))
		$position == 'top' ? 'center' : 'left';
	$s = getStyleString($top, $right, $bottom, $left, $width)
		.($align && $align!='inherit' ? 'text-align:' . $align .';' : '')
		.($color ? 'color:' . $color .';' : '')
		.($weight && $weight!='inherit' ? 'font-weight:' . $weight .';' : '');
	$type = min(6, max(1, $type));
	if ($size == 'small' && $position == 'top') $position='left';
	if ($picture > 0) {
		$attach = wp_get_attachment_image_src( $picture, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$picture = $attach[0];
	}
	$pic = $style!='iconed' 
		? '' 
		: '<'.($size!='small' ? 'div' : 'span').' class="sc_title_'.($icon ? 'icon' : 'image').' sc_title_'.$position.' sc_size_'.$size
			.($icon!='' && $icon!='none' ? ' '.$icon : '')
			.(!empty($background) && $background!='none' ? ' sc_title_bg sc_bg_'.$background : '')
			.'"'
			.(!empty($background) && $background!='none' && $bg_color!='' ? ' style="background-color:'.$bg_color.'"' : '').'>'
			.($picture ? '<img src="'.$picture.'" alt="" />' : '')
			.(empty($picture) && $image && $image!='none' ? '<img src="'.(themerex_strpos($image, 'http:')!==false ? $image : themerex_get_file_url('/images/icons/'.$image.'.png')).'" alt="" />' : '')
			.'</'.($size!='small' ? 'div' : 'span').'>';
	return ($size!='small' ? $pic : '')
		. '<h' . $type . ($id ? ' id="' . $id . '"' : '')
		. ' class="sc_title sc_title_'.$style. (!empty($class) ? ' '.$class : '').'"'
		. ($s!='' ? ' style="'.$s.'"' : '')
		. '>'
		. ($size=='small' ? $pic : '')
		. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.$color.'"' : '').'></span>' : '')
		. do_shortcode($content) 
		. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.$color.'"' : '').'></span>' : '')
		. '</h' . $type . '>';
}

// ---------------------------------- [/trx_title] ---------------------------------------






// ---------------------------------- [trx_toggles] ---------------------------------------


add_shortcode('trx_toggles', 'sc_toggles');

/*
[trx_toggles id="unique_id"]
	[trx_toggles_item title="Et adipiscing integer, scelerisque pid"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_toggles_item]
	[trx_toggles_item title="A pulvinar ut, parturient enim porta ut sed"]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_toggles_item]
	[trx_toggles_item title="Duis sociis, elit odio dapibus nec"]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_toggles_item]
	[trx_toggles_item title="Nec purus, cras tincidunt rhoncus"]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_toggles_item]
[/trx_toggles]
*/
$THEMEREX_sc_toggle_counter = 0;
$THEMEREX_sc_toggle_style = 1;
$THEMEREX_sc_toggle_large = false;
$THEMEREX_sc_toggle_show_counter = false;
function sc_toggles($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"style" => "1",
		"counter" => "off",
		"large" => "off",
		"shadow" => "off",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	global $THEMEREX_sc_toggle_counter, $THEMEREX_sc_toggle_style, $THEMEREX_sc_toggle_large, $THEMEREX_sc_toggle_show_counter;
	$THEMEREX_sc_toggle_counter = 0;
	$THEMEREX_sc_toggle_style = max(1, min(3, $style));
	$THEMEREX_sc_toggle_large = sc_param_is_on($large);
	$THEMEREX_sc_toggle_show_counter = sc_param_is_on($counter);
	themerex_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
	return '<div' . ($id ? ' id="' . $id . '"' : '') 
			. ' class="sc_toggles sc_toggles_style_' . $style
			. (sc_param_is_on($shadow) ? ' sc_shadow' : '') 
			. (sc_param_is_on($counter) ? ' sc_show_counter' : '') 
			. (sc_param_is_on($large) ? ' sc_toggles_large' : '') 
			. (!empty($class) ? ' '.$class : '')
			. '"'
			. ($s!='' ? ' style="'.$s.'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</div>';
}


add_shortcode('trx_toggles_item', 'sc_toggles_item');

//[trx_toggles_item]
function sc_toggles_item($atts, $content=null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts( array(
		"id" => "",
		"class" => "",
		"title" => "",
		"open" => ""
	), $atts));
	global $THEMEREX_sc_toggle_counter, $THEMEREX_sc_toggle_large, $THEMEREX_sc_toggle_show_counter;
	$THEMEREX_sc_toggle_counter++;
	return '<div' . ($id ? ' id="' . $id . '"' : '') 
				. ' class="sc_toggles_item'.(sc_param_is_on($open) ? ' sc_active' : '')
				. ($THEMEREX_sc_toggle_large ? ' sc_toggles_item_large' : '') 
				. ($THEMEREX_sc_toggle_counter % 2 == 1 ? ' odd' : ' even') 
				. ($THEMEREX_sc_toggle_counter == 1 ? ' first' : '')
				. (!empty($class) ? ' '.$class : '')
				. '">'
				. '<h'.($THEMEREX_sc_toggle_large ? '3' : '4').' class="sc_toggles_title">'
				. ($THEMEREX_sc_toggle_show_counter ? '<span class="sc_items_counter">'.$THEMEREX_sc_toggle_counter.'</span>' : '')
				. $title 
				. '</h'.($THEMEREX_sc_toggle_large ? '3' : '4').'>'
				. '<div class="sc_toggles_content"'.(sc_param_is_on($open) ? ' style="display:block;"' : '').'>' 
				. do_shortcode($content) 
				. '</div>'
			. '</div>';
}

// ---------------------------------- [/trx_toggles] ---------------------------------------





// ---------------------------------- [trx_tooltip] ---------------------------------------


add_shortcode('trx_tooltip', 'sc_tooltip');

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/
function sc_tooltip($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"title" => ""
    ), $atts));
	return '<span' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_tooltip_parent'. (!empty($class) ? ' '.$class : '').'">' . do_shortcode($content) . '<span class="sc_tooltip">' . $title . '</span></span>';
}
// ---------------------------------- [/trx_tooltip] ---------------------------------------






// ---------------------------------- [trx_twitter] ---------------------------------------


add_shortcode('trx_twitter', 'sc_twitter');

/*
[trx_twitter id="unique_id" user="username" consumer_key="" consumer_secret="" token_key="" token_secret=""]
*/

function sc_twitter($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"interval" => "",
		"count" => "3",
		"user" => "",
		"consumer_key" => "",
		"consumer_secret" => "",
		"token_key" => "",
		"token_secret" => "",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString($top, $right, $bottom, $left);
	$d = getStyleString('', '', '', '', $width, $height);
	$twitter_username = $user ? $user : get_theme_option('twitter_username');
	$twitter_consumer_key = $consumer_key ? $consumer_key : get_theme_option('twitter_consumer_key');
	$twitter_consumer_secret = $consumer_secret ? $consumer_secret : get_theme_option('twitter_consumer_secret');
	$twitter_token_key = $token_key ? $token_key : get_theme_option('twitter_token_key');
	$twitter_token_secret = $token_secret ? $token_secret : get_theme_option('twitter_token_secret');
	$twitter_count = max(1, $count ? $count : intval(get_theme_option('twitter_count')));
	$output = '';
	if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
		$data = getTwitterData(array(
			'mode'            => 'user_timeline',
			'consumer_key'    => $twitter_consumer_key,
			'consumer_secret' => $twitter_consumer_secret,
			'token'           => $twitter_token_key,
			'secret'          => $twitter_token_secret
			)
		);
		if ($data && isset($data[0]['text'])) {
			$output = '
				<div' . ($id ? ' id="' . $id . '"' : '').' class="sc_twitter twitBlock'.($class ? ' '.$class : '').'"'. ($s!='' ? ' style="'.$s.'"' : '').'>
					<div class="sc_slider sc_slider_swiper sc_slider_controls sc_slider_nopagination sc_slider_noresize sc_slider_autoheight swiper-slider-container"'
						. ((int) $interval > 0 ? ' data-interval="'.$interval.'"' : '')
						. ($d!='' ? ' style="'.$d.'"' : '')
						. '>
						<ul class="slides swiper-wrapper">
							';
			$cnt = 0;
			foreach ($data as $tweet) {
				if (themerex_substr($tweet['text'], 0, 1)=='@') continue;
				$output .= '<li class="sc_twitter_item swiper-slide' . ($cnt==$twitter_count-1 ? ' last' : '') . '">'
						. '<p>'
						. '<span class="twitterIco"></span>'
						. '<a href="https://twitter.com/' . $twitter_username . '" class="twitAuthor" target="_blank">@' . htmlspecialchars($tweet['user']['screen_name']) . '</a> '
						. twitter_prepare_text($tweet) 
						. '</p>'
						. '</li>';
				if (++$cnt >= $twitter_count) break;
			}
			$output .= '
						</ul>
						<ul class="flex-direction-nav">
							<li><a class="flex-prev" href="#"></a></li>
							<li><a class="flex-next" href="#"></a></li>
						</ul>
					</div>
				</div>';
		}
	}

	themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
	themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

	themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'flexslider', themerex_get_file_url('/js/jquery.flexslider.min.js'), array('jquery'), null, true );

	return $output;
}

// ---------------------------------- [/trx_twitter] ---------------------------------------




// ---------------------------------- [trx_video] ---------------------------------------

add_shortcode("trx_video", "sc_video");

//[trx_video id="unique_id" url="http://player.vimeo.com/video/20245032?title=0&amp;byline=0&amp;portrait=0" width="" height=""]
function sc_video($atts, $content = null) {
	if (in_shortcode_blogger()) return '';
	extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"url" => '',
		"src" => '',
		"image" => '',
		"title" => 'off',
		"ratio" => '16:9',
		"autoplay" => 'off',
		"width" => '100%',
		"height" => '295',
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	if ($src=='' && $url=='' && isset($atts[0])) {
		$src = $atts[0];
	}
	$ed = themerex_substr($width, -1);
	$s = getStyleString($top, $right, $bottom, $left, $width, $height!='' ? $height+(sc_param_is_on($title) ? 21 : 0) : '');
	$url = $src!='' ? $src : $url;
	if ($image!='' && sc_param_is_off($image))
		$image = '';
	else {
		if (sc_param_is_on($autoplay) && is_single())
			$image = '';
		else {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$image = getResizedImageURL(empty($image) ? get_the_ID() : $image, $ed!='%' ? $width : null, $height);
			if (empty($image)) $image = getVideoCoverImage($url);
		}
	}
	$url = getVideoPlayerURL($src!='' ? $src : $url);
	$ratio = empty($ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $ratio);
	$video = '<video' . ($id ? ' id="' . $id . '"' : '')
		. ' class="sc_video'. (!empty($class) ? ' '.$class : '').'"'
		. ' src="' . $url . '"'
		. ' width="' . $width . '" height="' . $height . '"'
		. ' data-width="' . $width . '" data-height="' . $height . '"'
		. ' data-ratio="'.esc_attr($ratio).'"'
		. ($image ? ' data-image="'.esc_attr($image).'"' : '')
		. ' data-title="'.$title.'"'
		. ($s!='' ? ' style="'.$s.'"' : '')
		. (($image && get_theme_option('substitute_video')=='yes') || (sc_param_is_on($autoplay) && is_single()) ? ' autoplay="autoplay"' : '')
		. ' controls="controls"'
		. '>'
		. '</video>';
	if (get_custom_option('substitute_video')=='no')
		$video = getVideoFrame($video, $image, sc_param_is_on($title), $s);
	if (get_theme_option('use_mediaelement')=='yes')
		themerex_enqueue_script('wp-mediaelement');

	// Media elements library
	if (get_theme_option('use_mediaelement')=='yes') {
		if (floatval(get_bloginfo('version')) < "3.6") {
			themerex_enqueue_style(  'mediaplayer-style',  themerex_get_file_url('/js/mediaplayer/mediaplayer.css'), array(), null );
			themerex_enqueue_script( 'mediaplayer', themerex_get_file_url('/js/mediaplayer/mediaelement.min.js'), array(), null, true );
		} else {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}
	} else {
		global $wp_scripts;
		$wp_scripts->done[] = 'mediaelement';
		$wp_scripts->done[] = 'wp-mediaelement';
		$wp_styles->done[] = 'mediaelement';
		$wp_styles->done[] = 'wp-mediaelement';
	}

	themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
	themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

	themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );

	return $video;
}
// ---------------------------------- [/trx_video] ---------------------------------------






// ---------------------------------- [trx_zoom] ---------------------------------------

add_shortcode('trx_zoom', 'sc_zoom');

/*
[trx_zoom id="unique_id" border="none|light|dark"]
*/
function sc_zoom($atts, $content=null){	
	if (in_shortcode_blogger()) return '';
    extract(shortcode_atts(array(
		"id" => "",
		"class" => "",
		"src" => "",
		"url" => "",
		"over" => "",
		"border" => "none",
		"align" => "",
		"width" => "",
		"height" => "",
		"top" => "",
		"bottom" => "",
		"left" => "",
		"right" => ""
    ), $atts));
	$s = getStyleString('!'.$top, '!'.$right, '!'.$bottom, '!'.$left, $width, $height);
	$width  = getStyleValue($width);
	$height = getStyleValue($height);
	if (empty($id)) $id = 'sc_zoom_'.str_replace('.', '', mt_rand());
	themerex_enqueue_script( 'elevate-zoom', themerex_get_file_url('/js/jquery.elevateZoom-3.0.4.min.js'), array(), null, true );
	$src = $src!='' ? $src : $url;
	if ($src > 0) {
		$attach = wp_get_attachment_image_src( $src, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$src = $attach[0];
	}
	if ($over > 0) {
		$attach = wp_get_attachment_image_src( $over, 'full' );
		if (isset($attach[0]) && $attach[0]!='')
			$over = $attach[0];
	}
	return empty($src) ? '' : ((!sc_param_is_off($border) ? '<div class="sc_border sc_border_'.$border.'">' : '')
				.'<div' . ($id ? ' id="' . $id . '"' : '') . ' class="sc_zoom'. (!empty($class) ? ' '.$class : '').'"'.($s!='' ? ' style="'.$s.'"' : '').'>'
					.'<img src="' . $src . '"'.($height != '' ? ' style="height:' . $height . ';"' : '').' data-zoom-image="'.$over.'" alt="" />'
				. '</div>'
			. (!sc_param_is_off($border) ? '</div>' : ''));
}
// ---------------------------------- [/trx_zoom] ---------------------------------------
?>