<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'themerex_social_load_widgets' );

/**
 * Register our widget.
 */
function themerex_social_load_widgets() {
	register_widget( 'themerex_social_widget' );
}

/**
 * flickr Widget class.
 */
class themerex_social_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_socials', 'description' => __('Show site logo and social links', 'themerex') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'themerex-social-widget' );

		/* Create the widget. */
        parent::__construct ( 'themerex-social-widget', __('ThemeREX - Show logo and social links', 'themerex'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$text = isset($instance['text']) ? do_shortcode($instance['text']) : '';
		$logo_image = isset($instance['logo_image']) ? $instance['logo_image'] : '';
		$logo_text = isset($instance['logo_text']) ? $instance['logo_text'] : '';
		$show_logo = isset($instance['show_logo']) ? (int) $instance['show_logo'] : 1;
		$show_icons = isset($instance['show_icons']) ? (int) $instance['show_icons'] : 1;

		/* Before widget (defined by themes). */			
		echo ($before_widget);

		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title) echo ($before_title . $title . $after_title);
		
		
		?>
		<div class="widget_inner">
            <?php
				if ($show_logo) {
					if ($logo_image=='')	$logo_image = get_custom_option('logo_image');
					if ($logo_text=='')		$logo_text  = get_custom_option('logo_text');
					if ($logo_image!='' || $logo_text!='') { 
					?>
						<div class="logo"><a href="<?php echo home_url(); ?>"><?php echo ($logo_image ? '<img src="'.$logo_image.'" alt="">' : ''); ?><?php echo ($logo_text ? '<span class="logo_text">'.str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $logo_text).'</span>' : ''); ?></a></div>
					<?php 
					} 
				}

				if (!empty($text)) {
					?>
					<div class="logo_descr"><?php echo nl2br(do_shortcode($text)); ?></div>
                    <?php
				}
				
				if ($show_icons) {
					$socials = get_theme_option('social_icons');
					?>
					<div class="logo_socials socPage">
						<ul>
						<?php
						foreach ($socials as $s) {
							if (empty($s['url'])) continue;
							$sn = basename($s['icon']);
							$sn = themerex_substr($sn, 0, themerex_strrpos($sn, '.'));
							if (($pos=themerex_strrpos($sn, '_'))!==false)
								$sn = themerex_substr($sn, 0, $pos);
							$soc = themerex_get_socials_url(basename($s['icon'])); //$s['icon'];
							?>
							<li><a class="social_icons social_<?php echo esc_attr($sn); ?>" style="background-image: url(<?php echo esc_url($soc); ?>);" target="_blank" href="<?php echo esc_url($s['url']); ?>"><span style="background-image: url(<?php echo esc_url($soc); ?>);"></span></a></li>
							<?php 
						}
						?>
						</ul>
					</div>
					<?php
				}
			?>
		</div>

		<?php
		/* After widget (defined by themes). */
		echo ($after_widget);
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = $new_instance['text'];
		$instance['logo_image'] = strip_tags( $new_instance['logo_image'] );
		$instance['logo_text'] = strip_tags( $new_instance['logo_text'] );
		$instance['show_logo'] = (int) $new_instance['show_logo'];
		$instance['show_icons'] = (int) $new_instance['show_icons'];
	
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'logo_image'=>'', 'logo_text'=>'', 'show_logo' => '1', 'show_icons' => '1', 'text'=>'', 'description' => __('Show logo and social icons', 'themerex') );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$text = isset($instance['text']) ? $instance['text'] : '';
		$show_logo = isset($instance['show_logo']) ? $instance['show_logo'] : 1;
		$show_icons = isset($instance['show_icons']) ? $instance['show_icons'] : 1;
		$logo_image = isset($instance['logo_image']) ? $instance['logo_image'] : '';
		$logo_text = isset($instance['logo_text']) ? $instance['logo_text'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php _e('Description:', 'themerex'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'text' )); ?>" style="width:100%;"><?php echo htmlspecialchars($instance['text']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>"><?php _e('Logo image:<br />(if empty - use logo from Theme Options)', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_image' )); ?>" value="<?php echo esc_attr($logo_image); ?>" style="width:100%;" onchange="jQuery(this).siblings('img').get(0).src=this.value;" />
            <?php
			echo show_custom_field(array('id'=>$this->get_field_id( 'logo_media' ), 'type'=>'mediamanager', 'media_field_id'=>$this->get_field_id( 'logo_image' )), null);
			if ($logo_image) {
			?>
	            <br /><br /><img src="<?php echo esc_url($logo_image); ?>" width="220" alt="" />
			<?php
			}
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>"><?php _e('Logo text:', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_text' )); ?>" value="<?php echo esc_attr($instance['logo_text']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php _e('Show logo:', 'themerex'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="1" <?php echo ($show_logo==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php _e('Show', 'themerex'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="0" <?php echo ($show_logo==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0"><?php _e('Hide', 'themerex'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php _e('Show social icons:', 'themerex'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="1" <?php echo ($show_icons==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php _e('Show', 'themerex'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="0" <?php echo ($show_icons==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0"><?php _e('Hide', 'themerex'); ?></label>
		</p>

	<?php
	}
}

if (is_admin()) {
	require_once(themerex_get_file_dir('/admin/theme-custom.php'));
}
?>