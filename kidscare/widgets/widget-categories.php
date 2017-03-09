<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'widget_categories_load' );

/**
 * Register our widget.
 */
function widget_categories_load() {
	register_widget( 'themerex_categories_widget' );
}

/**
 * Twitter Widget class.
 */
class themerex_categories_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_subcategories', 'description' => __('Subcategories list', 'themerex') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'themerex-subcategories-widget' );

		/* Create the widget. */
        parent::__construct( 'themerex-subcategories-widget', __('ThemeREX - Subcategories list', 'themerex'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );

		$c = !empty( $instance['count'] ) ? '1' : '0';
		$h = !empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = !empty( $instance['dropdown'] ) ? '1' : '0';

		$root = isset($instance['root']) ? (int) $instance['root'] : 0;

		$cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);
		if ($root > 0) $cat_args['child_of'] = $root;

		/* Before widget (defined by themes). */			
		echo ($before_widget);

		if ($title) echo ($before_title . $title . $after_title);
		?>			
		<div class="widget_subcategories_inner">
			<?php
			if ( $d ) {
				$cat_args['show_option_none'] = __('Select Category', 'themerex');
				wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args ) );
				?>
				<script type='text/javascript'>
				/* <![CDATA[ */
					jQuery('.widget_subcategories select').change(function() {
						var dropdown = jQuery(this).get(0);
						if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
							location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
						}
					});
				/* ]]> */
				</script>
	
				<?php
			} else {
				?>
				<ul>
				<?php
				$cat_args['title_li'] = '';
				wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );
				?>
				</ul>
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
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['count'] 			= !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] 	= !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] 		= !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['root'] 			= (int) $new_instance['root'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'root' => 0) );
		$title = esc_attr( $instance['title'] );
		$root = (int) $instance['root'];
		$categories = getCategoriesList(false);
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e( 'Title:', 'themerex' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('root')); ?>"><?php _e('Root category:', 'themerex'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('root')); ?>" name="<?php echo esc_attr($this->get_field_name('root')); ?>" style="width:100%;">
				<option value="0"><?php _e('-- Any category --', 'themerex'); ?></option> 
			<?php
				foreach ($categories as $cat_id => $cat_name) {
					echo '<option value="'.$cat_id.'"'.($root==$cat_id ? ' selected="selected"' : '').'>'.$cat_name.'</option>';
				}
			?>
			</select>
		</p>

		<p>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('dropdown')); ?>" name="<?php echo esc_attr($this->get_field_name('dropdown')); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo esc_attr($this->get_field_id('dropdown')); ?>"><?php _e( 'Display as dropdown', 'themerex' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo esc_attr($this->get_field_id('count')); ?>"><?php _e( 'Show post counts', 'themerex' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>" name="<?php echo esc_attr($this->get_field_name('hierarchical')); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>"><?php _e( 'Show hierarchy', 'themerex' ); ?></label>
		</p>
<?php
	}
}
?>