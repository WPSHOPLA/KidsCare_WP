<?php
/**
 * Add function to widgets_init that will load our widget.
 */
add_action( 'widgets_init', 'widget_recent_reviews_load' );

/**
 * Register our widget.
 */
function widget_recent_reviews_load() {
	register_widget('themerex_recent_reviews_widget');
}

/**
 * recent_reviews Widget class.
 */
class themerex_recent_reviews_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array('classname' => 'widget_recent_reviews', 'description' => __('Recent reviews', 'themerex'));

		/* Widget control settings. */
		$control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'themerex-recent-reviews-widget');

		/* Create the widget. */
        parent::__construct('themerex-recent-reviews-widget', __('ThemeREX - Recent reviews', 'themerex'), $widget_ops, $control_ops);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget($args, $instance) {
		extract($args);

		global $wp_query, $post;
		global $THEMEREX_CURRENT_SIDEBAR;

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
		$number = isset($instance['number']) ? (int) $instance['number'] : '';
		$show_date = isset($instance['show_date']) ? (int) $instance['show_date'] : 0;
		$show_image = isset($instance['show_image']) ? (int) $instance['show_image'] : 0;
		$show_author = isset($instance['show_author']) ? (int) $instance['show_author'] : 0;
		$show_counters = isset($instance['show_counters']) ? (int) $instance['show_counters'] : 0;
		$category = isset($instance['category']) ? (int) $instance['category'] : 0;

		$show_counters = $show_counters==2 ? 'stars' : ($show_counters==1 ? 'rating' : '');

		$output = '';
		if ($THEMEREX_CURRENT_SIDEBAR == 'top') {
			$output .= '<div class="columnsWrap"><div class="columns1_2">';
		}
		
		$post_rating = 'reviews_avg'.(get_theme_option('reviews_first')=='author' ? '' : '2');
		$args = array(
			'post_type' => 'post',
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'post_password' => '',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => 1,
			'order' => 'DESC',
			'orderby' => 'date',
			'meta_query' => array(
				array(
					'key' => $post_rating,
					'value' => 0,
					'compare' => '>',
					'type' => 'NUMERIC'
				)
			)
		);
		if ($category > 0) {
			$args['cat'] = $category;
		}
		$ex = get_theme_option('exclude_cats');
		if (!empty($ex)) {
			$args['category__not_in'] = explode(',', $ex);
		}
		query_posts($args); 
			
		/* Loop posts */
		if (have_posts()) {
			$post_number = 0;
			while (have_posts()) { the_post();
					
				$post_number++;

				require(themerex_get_file_dir('/templates/page-part-widgets-posts.php'));
			
				if ($THEMEREX_CURRENT_SIDEBAR == 'top' && $post_number==round($number/2)) {
					$output .= '
						</div><div class="columns1_2">
					';
				}

				if ($post_number >= $number) break;
			}
		}
		if ($THEMEREX_CURRENT_SIDEBAR == 'top') {
			$output .= '</div></div>';
		}


		/* Restore main wp_query and current post data in the global var $post */
		wp_reset_query();
		wp_reset_postdata();

		
		if (!empty($output)) {
	
			/* Before widget (defined by themes). */			
			echo ($before_widget);
			
			/* Display the widget title if one was input (before and after defined by themes). */
			if ($title) echo ($before_title . $title . $after_title);
	
			echo '
				<div class="recent_reviews">
					' . $output . '
				</div>
			';
			
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
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = (int) $new_instance['show_date'];
		$instance['show_image'] = (int) $new_instance['show_image'];
		$instance['show_author'] = (int) $new_instance['show_author'];
		$instance['show_counters'] = (int) $new_instance['show_counters'];
		$instance['category'] = (int) $new_instance['category'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form($instance) {
 		/* Set up some default widget settings. */
		$defaults = array('title' => '', 'number' => '4', 'show_date' => '1', 'show_image' => '1', 'show_author' => '1', 'show_counters' => '1', 'category'=>'0', 'description' => __('Recent reviews from selected category', 'themerex'));
		$instance = wp_parse_args((array) $instance, $defaults); 
		$title = isset($instance['title']) ? $instance['title'] : '';
		$number = isset($instance['number']) ? $instance['number'] : '';
		$show_date = isset($instance['show_date']) ? $instance['show_date'] : '1';
		$show_image = isset($instance['show_image']) ? $instance['show_image'] : '1';
		$show_author = isset($instance['show_author']) ? $instance['show_author'] : '1';
		$show_counters = isset($instance['show_counters']) ? $instance['show_counters'] : '1';
		$category = isset($instance['category']) ? $instance['category'] : '0';
		$categories = getCategoriesList(false);
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Widget title:', 'themerex'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php _e('Category:', 'themerex'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" style="width:100%;">
				<option value="0"><?php _e('-- Any category --', 'themerex'); ?></option> 
			<?php
				foreach ($categories as $cat_id => $cat_name) {
					echo '<option value="'.$cat_id.'"'.($category==$cat_id ? ' selected="selected"' : '').'>'.$cat_name.'</option>';
				}
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number posts to show:', 'themerex'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php _e('Show post image:', 'themerex'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="1" <?php echo ($show_image==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php _e('Show', 'themerex'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="0" <?php echo ($show_image==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0"><?php _e('Hide', 'themerex'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php _e('Show post author:', 'themerex'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="1" <?php echo ($show_author==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php _e('Show', 'themerex'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="0" <?php echo ($show_author==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0"><?php _e('Hide', 'themerex'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php _e('Show post date:', 'themerex'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="1" <?php echo ($show_date==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php _e('Show', 'themerex'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="0" <?php echo ($show_date==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0"><?php _e('Hide', 'themerex'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php _e('Show post counters:', 'themerex'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_2" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="2" <?php echo ($show_counters==2 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_2"><?php _e('As stars', 'themerex'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="1" <?php echo ($show_counters==1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php _e('As icon', 'themerex'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="0" <?php echo ($show_counters==0 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0"><?php _e('Hide', 'themerex'); ?></label>
		</p>

	<?php
	}
}

?>