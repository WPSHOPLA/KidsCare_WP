<?php 
global $THEMEREX_usermenu, $THEMEREX_usermenu_show;
if (empty($THEMEREX_usermenu) || !$THEMEREX_usermenu_show) {
	?>
	<ul id="usermenu" class="usermenu_list">
    <?php
} else {
	$menu = themerex_substr($THEMEREX_usermenu, 0, themerex_strlen($THEMEREX_usermenu)-5);
	$pos = themerex_strpos($menu, '<ul');
	if ($pos!==false)
		$menu = themerex_substr($menu, 0, $pos+3) . ' class="usermenu_list"' . themerex_substr($menu, $pos+3);
	echo str_replace('class=""', '', $menu);
}
?>

<?php if (is_woocommerce_page() && get_custom_option('show_currency')=='yes') { ?>
	<li class="usermenu_currency">
		<a href="#">$</a>
		<ul>
			<li><a href="#"><b>&#36;</b> <?php _e('Dollar', 'themerex'); ?></a></li>
			<li><a href="#"><b>&euro;</b> <?php _e('Euro', 'themerex'); ?></a></li>
			<li><a href="#"><b>&pound;</b> <?php _e('Pounds', 'themerex'); ?></a></li>
		</ul>
	</li>
<?php } ?>

<?php if (function_exists('is_woocommerce') && (is_woocommerce_page() && get_custom_option('show_cart')=='shop' || get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { ?>
	<li class="usermenu_cart">
		<a href="#" class="cart_button"><span><?php if ($THEMEREX_usermenu_show) _e('Cart', 'themerex'); ?></span> <b class="cart_total"><?php echo WC()->cart->get_cart_subtotal(); ?></b></a>
			<ul class="widget_area sidebar_cart sidebar"><li>
				<?php
				do_action( 'before_sidebar' );
				global $THEMEREX_CURRENT_SIDEBAR;
				$THEMEREX_CURRENT_SIDEBAR = 'cart';
				if ( ! dynamic_sidebar( 'sidebar-cart' ) ) { 
					the_widget( 'WC_Widget_Cart', 'title=&hide_if_empty=1' );
				}
				?>
			</li></ul>
	</li>
<?php } ?>

<?php if (get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
	$languages = icl_get_languages('skip_missing=1');
	if (!empty($languages)) {
		$lang_list = '';
		$lang_active = '';
		foreach ($languages as $lang) {
			$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
			if ($lang['active']) {
				$lang_active = $lang_title;
			}
			$lang_list .= "\n".'<li><a rel="alternate" hreflang="' . $lang['language_code'] . '" href="' . apply_filters('WPML_filter_link', $lang['url'], $lang) . '">'
				.'<img src="' . $lang['country_flag_url'] . '" alt="' . $lang_title . '" title="' . $lang_title . '" />'
				. $lang_title
				.'</a></li>';
		}
		?>
		<li class="usermenu_language">
			<a href="#"><span><?php echo esc_html($lang_active); ?></span></a>
			<ul><?php echo balanceTags($lang_list); ?></ul>
		</li>
<?php
	}
}
?>

<?php
	if (get_custom_option('show_login')=='yes') {

		// magnific & pretty
		themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
		themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
		// Load PrettyPhoto if it selected in Theme Options
		if (get_theme_option('popup_engine')=='pretty') {
			themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
			themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		}

		if ( !is_user_logged_in() ) {
			?>
			<li class="usermenu_login"><a href="#user-popUp" class="user-popup-link"><?php _e('Login', 'themerex'); ?></a></li>
			<?php 
		} else {
			$current_user = wp_get_current_user();
			?>
			<li class="usermenu_controlPanel">
				<a href="#"><span><?php echo esc_html($current_user->display_name); ?></span></a>
				<ul>
					<?php if (current_user_can('publish_posts')) { ?>
					<li><a href="<?php echo home_url(); ?>/wp-admin/post-new.php?post_type=post" class="icon icon-doc-inv"><?php _e('New post', 'themerex'); ?></a></li>
					<?php } ?>
					<li><a href="<?php echo get_edit_user_link(); ?>" class="icon icon-cog-1"><?php _e('Settings', 'themerex'); ?></a></li>
					<li><a href="<?php echo wp_logout_url(home_url()); ?>" class="icon icon-logout"><?php _e('Log out', 'themerex'); ?></a></li>
				</ul>
			</li>
			<?php 
		}
	}

    if (get_custom_option('sound_enable') == 'yes') {
		?>
		<li class="usermenu_sound">
			<a href="#" class="icon-volume" title="<?php _e('Sounds on/off', 'themerex'); ?>"></a>
			<ul>
				<?php if (get_custom_option('sound_mainmenu')!='') { ?>
				<li><a href="#" class="sound_mainmenu icon icon-check"><?php _e('Main Menu', 'themerex'); ?></a></li>
				<?php } ?>
				<?php if (get_custom_option('sound_othermenu')!='') { ?>
				<li><a href="#" class="sound_othermenu icon icon-check"><?php _e('Other Menus', 'themerex'); ?></a></li>
				<?php } ?>
				<?php if (get_custom_option('sound_buttons')!='') { ?>
				<li><a href="#" class="sound_buttons icon icon-check"><?php _e('Buttons', 'themerex'); ?></a></li>
				<?php } ?>
				<?php if (get_custom_option('sound_links')!='') { ?>
				<li><a href="#" class="sound_links icon icon-check"><?php _e('Regular links', 'themerex'); ?></a></li>
				<?php } ?>
			</ul>
		</li>
        <?php
    }
?>

</ul>
