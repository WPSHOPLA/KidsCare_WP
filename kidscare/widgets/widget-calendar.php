<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'widget_calendar_load' );

/**
 * Register our widget.
 */
function widget_calendar_load() {
	register_widget('themerex_calendar_widget');
}

/**
 * Recent posts Widget class.
 */
class themerex_calendar_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_calendar', 'description' => __('Calendar for posts and/or Events', 'themerex'));

		/* Widget control settings. */
		$control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'themerex-calendar-widget');

		/* Create the widget. */
        parent::__construct('themerex-calendar-widget', __('ThemeREX - Posts and Events Calendar', 'themerex'), $widget_ops, $control_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget($args, $instance) {
		extract($args);

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
		$post_type = isset($instance['post_type']) ? $instance['post_type'] : 'post';
		
		$output = getThemeRexCalendar(true, 0, 0, array('post_type'=>$post_type));

		if (!empty($output)) {
	
			/* Before widget (defined by themes). */			
			echo ($before_widget);
			
			/* Display the widget title if one was input (before and after defined by themes). */
			echo ($before_title . $title . $after_title);
	
			echo ($output);
			
			/* After widget (defined by themes). */
			echo ($after_widget);
		}
	}

	/**
	 * Update the widget settings.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		/* Strip tags for title and comments count to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_type'] = isset($new_instance['post_type']) ? join(',', $new_instance['post_type']) : 'post';

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form($instance) {
 		/* Set up some default widget settings. */
		$defaults = array('title' => '', 'post_type'=>'post', 'description' => __('Posts and Events Calendar', 'themerex'));
		$instance = wp_parse_args((array) $instance, $defaults); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$post_type = isset($instance['post_type']) ? $instance['post_type'] : 'post';
		//$types = getPostsTypesList();
		$types = array('post' => __('Posts', 'themerex'));
		if (class_exists('TribeEvents')) $types['tribe_events'] = __('Events', 'themerex');
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Widget title:', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>_1"><?php _e('Post type:', 'themerex'); ?></label>
			<?php
				$i=0;
				foreach ($types as $type=>$type_title) {
					$i++;
					echo '<input type="checkbox" id="'.$this->get_field_id('post_type').'_'.$i.'" name="'.$this->get_field_name('post_type').'[]" value="'.$type.'"'.(themerex_strpos($post_type, $type)!==false ? ' checked="checked"' : '').'><label for="'.$this->get_field_id('post_type').'_'.$i.'">'.$type_title.'</label> ';
				}
			?>
			</select>
			<br><span class="description"><?php _e('Attention! If you check both post types, please check also "Include events in main blog loop" in the menu "Events - Settings"', 'themerex'); ?></span>
		</p>

	<?php
	}
}

?>