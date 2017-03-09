<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'widget_flickr_load' );

/**
 * Register our widget.
 * 'flickr_Widget' is the widget class used below.
 */
function widget_flickr_load() {
	register_widget( 'themerex_flickr_widget' );
}

/**
 * flickr Widget class.
 */
class themerex_flickr_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_flickr', 'description' => __('Last flickr photos.', 'themerex') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'themerex-flickr-widget' );

		/* Create the widget. */
        parent::__construct( 'themerex-flickr-widget', __('ThemeREX - Flickr photos', 'themerex'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$flickr_username = isset($instance['flickr_username']) ? $instance['flickr_username'] : '';
		$flickr_count = isset($instance['flickr_count']) ? $instance['flickr_count'] : '';
		
		
		/* Before widget (defined by themes). */			
		echo ($before_widget);

		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title) echo ($before_title . $title . $after_title);
		
		//here will be displayed widget content for Footer 1st column 
		global $THEMEREX_CURRENT_SIDEBAR;
		?>
		<div class="flickr_images">
			<?php
			if ($flickr_count <= 10) {
				// Old method - up to 10 images
				$size = $THEMEREX_CURRENT_SIDEBAR == 'top' ? 'm' : 's';
				?>
				<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo (int)$flickr_count; ?>&amp;display=random&amp;flickr_display=random&amp;size=<?php echo esc_attr($size); ?>&amp;layout=x&amp;source=user&amp;user=<?php echo esc_attr($flickr_username); ?>"></script>
				<?php
			} else {
				// New method > 10 images
				$size = $THEMEREX_CURRENT_SIDEBAR == 'top' ? 'mid' : 'square';
				?>
				<script type="text/javascript" src="http://www.flickr.com/badge_code.gne?count=<?php echo (int)$flickr_count; ?>&amp;display=random&amp;flickr_display=random&amp;size=<?php echo esc_attr($size); ?>&amp;layout=x&amp;source=user&amp;nsid=<?php echo esc_attr($flickr_username); ?>&amp;raw=1"></script>
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
		$instance['flickr_username'] = strip_tags( $new_instance['flickr_username'] );
		$instance['flickr_count'] = (int) $new_instance['flickr_count'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => '', 'count' => 6, 'description' => __('Last flickr photos', 'themerex') );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$flickr_username = isset($instance['flickr_username']) ? $instance['flickr_username'] : '';
		$flickr_count = isset($instance['flickr_count']) ? $instance['flickr_count'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e('Title:', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'flickr_username' )); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com" target="_blank">idGettr</a>):', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'flickr_username' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'flickr_username' )); ?>" value="<?php echo esc_attr($flickr_username); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'flickr_count' )); ?>"><?php _e('Number of photos:', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'flickr_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'flickr_count' )); ?>" value="<?php echo esc_attr($flickr_count); ?>" style="width:100%;" />
		</p>

	<?php
	}
}
?>