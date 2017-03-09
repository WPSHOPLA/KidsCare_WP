<?php
/**
 * The Sidebar containing the main widget areas.
 */
?>

<?php if (in_array(get_custom_option('show_sidebar_main'), array('left', 'right'))) { ?>
	<div id="sidebar_main" class="widget_area sidebar_main sidebar" role="complementary">
		<?php
		do_action( 'before_sidebar' );
		global $THEMEREX_REVIEWS_RATING;
		echo balanceTags($THEMEREX_REVIEWS_RATING);
		global $THEMEREX_CURRENT_SIDEBAR;
		$THEMEREX_CURRENT_SIDEBAR = 'main';
		if ( ! dynamic_sidebar( get_custom_option('sidebar_main') ) ) { 
			// Put here html if user no set widgets in sidebar
		}
		?>
	</div> <!-- div#sidebar_main -->
<?php } ?>