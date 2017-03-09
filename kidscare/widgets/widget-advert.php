<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'widget_advert_load' );

/**
 * Register our widget.
 */
function widget_advert_load() {
	register_widget( 'themerex_advert_widget' );
}

/**
 * Twitter Widget class.
 */
class themerex_advert_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_advert', 'description' => __('Advertisement block', 'themerex') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'themerex-advert-widget' );

		/* Create the widget. */
        parent::__construct( 'themerex-advert-widget', __('ThemeREX - Advertisement block', 'themerex'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$advert_image = isset($instance['advert_image']) ? $instance['advert_image'] : '';
		$advert_link = isset($instance['advert_link']) ? $instance['advert_link'] : '';
		$advert_code = isset($instance['advert_code']) ? $instance['advert_code'] : '';

		/* Before widget (defined by themes). */			
		echo ($before_widget);

		if ($title) echo ($before_title . $title . $after_title);
?>			
		<div class="widget_advert_inner">
			<?php
			if ($advert_image!='') {
				echo ($advert_link!='' ? '<a href="' . $advert_link . '" class="image_wrapper">' : '') . '<img src="' . $advert_image . '" alt="' . $title . '" />' . ($advert_link!='' ? '</a>': '');
			}
			if ($advert_code!='') {
				echo substituteAll($advert_code);
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
		$instance['advert_image'] = strip_tags( $new_instance['advert_image'] );
		$instance['advert_link'] = strip_tags( $new_instance['advert_link'] );
		$instance['advert_code'] = $new_instance['advert_code'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'description' => __('Advertisement block', 'themerex') );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$advert_image = isset($instance['advert_image']) ? $instance['advert_image'] : '';
		$advert_link = isset($instance['advert_link']) ? $instance['advert_link'] : '';
		$advert_code = isset($instance['advert_code']) ? $instance['advert_code'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_image' )); ?>"><?php _e('Image source URL:<br />(leave empty if you paste advert code)', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'advert_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_image' )); ?>" value="<?php echo esc_attr($advert_image); ?>" style="width:100%;" onchange="jQuery(this).siblings('img').get(0).src=this.value;" />
            <?php
			echo show_custom_field(array('id'=>$this->get_field_id( 'advert_media' ), 'type'=>'mediamanager', 'media_field_id'=>$this->get_field_id( 'advert_image' )), null);
			if ($advert_image) {
			?>
	            <br /><br /><img src="<?php echo esc_url($advert_image); ?>" width="220" alt="" />
			<?php
			}
			?>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_link' )); ?>"><?php _e('Image link URL:<br />(leave empty if you paste advert code)', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'advert_link' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_link' )); ?>" value="<?php echo esc_attr($advert_link); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_code' )); ?>"><?php _e('or paste Advert Widget HTML Code:', 'themerex'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'advert_code' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_code' )); ?>" rows="5" style="width:100%;"><?php echo htmlspecialchars($advert_code); ?></textarea>
		</p>
	<?php
	}
}

if (is_admin()) {
	require_once(themerex_get_file_dir('/admin/theme-custom.php'));
}
?>