<?php
if (get_custom_option('slider_show')=='yes') { 
	$slider = get_custom_option('slider_engine');
	$slider_alias = $slider_ids = '';
	if ($slider == 'revo' && revslider_exists())
		$slider_alias = get_custom_option('slider_alias');
	else if ($slider == 'royal' && royalslider_exists())
		$slider_alias = get_custom_option('slider_alias');
	else if ($slider == 'flex' || $slider == 'chop' || $slider == 'swiper') {
		$slider_pagination = get_custom_option("slider_pagination");
		$slider_alias = get_custom_option("slider_category");
		$slider_orderby = get_custom_option("slider_orderby");
		$slider_order = get_custom_option("slider_order");
		$slider_count = $slider_ids = get_custom_option("slider_posts");
		if (themerex_strpos($slider_ids, ',')!==false) {
			$slider_alias = '';
			$slider_count = 0;
		} else {
			$slider_ids = '';
			if (empty($slider_count)) $slider_count = 3;
		}
		$slider_info_box = get_custom_option("slider_info_box");
		$slider_info_fixed = get_custom_option("slider_info_fixed");
		$slider_interval = get_custom_option("slider_interval");
	}

	// If slider exists
	if (!empty($slider_alias) || !empty($slider_ids)) {
		?>
		<div class="sliderHomeBullets <?php echo get_custom_option('slider_display')=='fullscreen' ? '' : 'staticSlider'; ?> slider_engine_<?php echo esc_attr($slider); ?> slider_alias_<?php echo esc_attr($slider_alias); ?>">
			<?php
				if ($slider == 'revo') {
					//putRevSlider($slider_alias);
					echo do_shortcode('[rev_slider '.$slider_alias.']');

				} else if ($slider == 'royal') {
					//register_new_royalslider_files($slider_alias);
					themerex_enqueue_style(  'new-royalslider-core-css', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/royalslider.css', array(), null );
					themerex_enqueue_script( 'new-royalslider-main-js', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/jquery.royalslider.min.js', array('jquery'), NEW_ROYALSLIDER_WP_VERSION, true );
					echo get_new_royalslider($slider_alias);
	
				} else if ($slider == 'flex' || $slider == 'chop' || $slider == 'swiper') {
	
					if ($slider_count>0 || !empty($slider_ids)) {
						echo do_shortcode('[trx_slider engine="'.$slider.'" controls="0" crop="off" height="'.max(100, get_custom_option('slider_height')).'"' 
							. ($slider_interval ? ' interval="'.$slider_interval.'"' : '') 
							. ($slider_alias ? ' cat="'.$slider_alias.'"' : '') 
							. ($slider_ids   ? ' ids="'.$slider_ids.'"' : '') 
							. ($slider_count ? ' count="'.$slider_count.'"' : '') 
							. ($slider_orderby ? ' orderby="'.$slider_orderby.'"' : '') 
							. ($slider_order ? ' order="'.$slider_order.'"' : '') 
							. ($slider_pagination ? ' pagination="'.$slider_pagination.'"' : '') 
							. ' titles="'.($slider_info_box=='yes' ? ($slider_info_fixed=='yes' ? 'fixed' : 'slide') : 'no')  .'"'
							. ']');
					}
				}
			?>
		</div>
		<?php 
	}
}
?>