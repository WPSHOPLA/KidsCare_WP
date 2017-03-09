<?php
/**
 * The Header for our theme.
 */

global $THEMEREX_sidemenu, $THEMEREX_mainmenu, $THEMEREX_usermenu_show, $logo_text, $logo_slogan, $logo_icon, $logo_image, $logo_side, $logo_fixed;
// Init theme template - prepare global variables
// It will call in this place: in hook 'after_theme_setup' custom options are not yet ready!
themerex_init_template();
$body_style     = get_custom_option('body_style');
$single_style   = get_custom_option('single_style');
$slider_show	= get_custom_option('slider_show')=='yes';
$slider_fullscreen = $slider_show && get_custom_option('slider_display')=='fullscreen';
$show_top_panel = get_custom_option('show_top_panel');
$video_bg_show  = get_custom_option('show_video_bg')=='yes' && (get_custom_option('video_bg_youtube_code')!='' || get_custom_option('video_bg_url')!='');
if ((!$slider_show || $body_style!='boxed') && $show_top_panel=='over') $show_top_panel = 'above';
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php
	$favicon = get_custom_option('favicon');
	if (!$favicon) {
		$skin = themerex_escape_shell_cmd(get_custom_option('theme_skin'));
		if ( file_exists(themerex_get_file_dir('/skins/'.$skin.'/images/favicon.ico')) )
			$favicon = themerex_get_file_url('/skins/'.$skin.'/images/favicon.ico');
		if ( !$favicon && file_exists(themerex_get_file_dir('favicon.ico')) )
			$favicon = themerex_get_file_url('favicon.ico');
	}
	if ($favicon) {
	?>
		<link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>" />
    <?php
	}
	?>
	<!--[if lt IE 9]>
	<script src="<?php echo themerex_get_file_url('/js/html5.js'); ?>" type="text/javascript"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php 
	$class = $style = '';
	if ($body_style=='boxed' || get_custom_option('load_bg_image')=='always') {
		$customizer = get_theme_option('show_theme_customizer') == 'yes';
		if ($customizer && ($img = (int) getValueGPC('bg_image', 0)) > 0)
			$class = 'bg_image_'.$img;
		else if ($customizer && ($img = (int) getValueGPC('bg_pattern', 0)) > 0)
			$class = 'bg_pattern_'.$img;
		else if ($customizer && ($img = getValueGPC('bg_color', '')) != '')
			$style = 'background-color: '.$img.';';
		else {
			if (($img = get_custom_option('bg_custom_image')) != '')
				$style = 'background: url('.$img.') ' . str_replace('_', ' ', get_custom_option('bg_custom_image_position')) . ' no-repeat fixed;';
			else if (($img = get_custom_option('bg_custom_pattern')) != '')
				$style = 'background: url('.$img.') 0 0 repeat fixed;';
			else if (($img = get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'.$img;
			else if (($img = get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'.$img;
			if (($img = get_custom_option('bg_color')) != '')
				$style .= 'background-color: '.$img.';';
		}
	}
	body_class('themerex_body ' . $body_style . ' top_panel_' . $show_top_panel . ' theme_skin_' . get_custom_option('theme_skin')
		. ($THEMEREX_sidemenu ? ' with_sidemenu sidemenu_left' : '')
		. ($video_bg_show ? ' video_bg' : '')
		. ($class!='' ? ' ' . $class : '')
	);
	if ($style!='') echo ' style="'.$style.'"';
	?>
>
	<?php echo get_custom_option('gtm_code'); ?>

	<?php do_action( 'before' ); ?>

	<?php
	if (get_custom_option('menu_toc_home')=='yes') echo do_shortcode( '[trx_anchor id="toc_home" title="'.__('Home', 'themerex').'" description="'.__('{Return to Home} - |navigate to home page of the site', 'themerex').'" icon="icon-home" separator="yes" url="'.home_url().'"]' ); 
	if (get_custom_option('menu_toc_top')=='yes') echo do_shortcode( '[trx_anchor id="toc_top" title="'.__('To Top', 'themerex').'" description="'.__('{Back to top} - |scroll to top of the page', 'themerex').'" icon="icon-up" separator="yes"]' ); 
	?>

	<?php 
	if ($THEMEREX_sidemenu) { 
	?>
	<div class="sidemenu_wrap swpLeftPos">
		<div class="sidemenu_button" style="background-image:url(<?php echo esc_url($logo_icon); ?>)"></div>
		<div class="menuTranform">
			<div class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container" id="sidemenu_scroll">
				<div class="sc_scroll_wrapper swiper-wrapper">
					<div class="sc_scroll_slide swiper-slide">
						<div class="usermenu_area">
						<?php 
						$THEMEREX_usermenu_show = false;
						get_template_part('/templates/page-part-user-panel'); 
						?>
						</div>

		
						<div class="logo<?php echo ($logo_text ? ' with_text' : ''); ?>"><a href="<?php echo home_url(); ?>"><?php echo ($logo_side ? '<img src="'.$logo_side.'" class="logo_side" alt="">' : ''); ?><?php echo ($logo_text ? '<span class="logo_text">'.apply_filters('theme_logo_text', $logo_text, 'sidemenu').'</span>' : ''); ?><?php echo ($logo_slogan ? '<span class="logo_slogan">'.$logo_slogan.'</span>' : ''); ?></a></div>
		
						<nav role="navigation" class="sidemenu_area">
							<?php echo balanceTags($THEMEREX_sidemenu); ?>
						</nav>

						<?php if (get_custom_option('show_search')=='yes') { ?>
						<div class="menuSearch">
							<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<button type="submit" class="searchSubmit"></button>
							<input type="text" class="searchField" placeholder="<?php _e('Search &hellip;', 'themerex'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _e('Search for:', 'themerex'); ?>" />
							</form>
						</div>
						<?php } ?>

						<div class="sidemenu_close">x</div>

					</div>
				</div>
				<div id="sidemenu_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical sidemenu_scroll_bar"></div>
			</div>
		</div>
	</div>
	<?php } ?>

	<!--[if lt IE 9]>
	<?php echo do_shortcode("[trx_infobox style='error']<div style=\"text-align:center;\">".__("It looks like you're using an old version of Internet Explorer. For the best WordPress experience, please <a href=\"http://microsoft.com\" style=\"color:#191919\">update your browser</a> or learn how to <a href=\"http://browsehappy.com\" style=\"color:#222222\">browse happy</a>!", 'themerex')."</div>[/trx_infobox]"); ?>
	<![endif]-->
	
	
	<div class="main_content">
		<?php if ($show_top_panel=='over') { get_template_part('templates/page-part-slider'); } ?>
		<div class="boxedWrap">
			<?php 
				if (($header_custom_image = get_header_image()) != '') {
					$header_style = ' style = "background-image: url('.$header_custom_image.');"';
				} else {
					$header_style = '';
				}
			?>

			<?php
			if ($video_bg_show) {
				$youtube = get_custom_option('video_bg_youtube_code');
				$video = get_custom_option('video_bg_url');
				$overlay = get_custom_option('video_bg_overlay')=='yes';
				if (!empty($youtube)) {
					?>
					<div class="videoBackground<?php echo ($overlay ? ' overlay' : ''); ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
					<?php
				} else if (!empty($video)) {
					$info = pathinfo($video);
					$ext = !empty($info['extension']) ? $info['extension'] : 'src';
					?>
					<div class="videoBackground<?php echo ($overlay ? ' overlay' : ''); ?> videoBackgroundFullscreen"><?php
						//echo do_shortcode('[video '.$ext.'="'.$video.'" width="1280" height="720" loop="true" autoplay="true"][/video]');
					?><video class="videoBackground" width="1280" height="720" data-width="1280" data-height="720" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>">
						<source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source>
					</video></div>
					<?php
				}
				?>
				<div class="videoBackgroundOverlay">
				<?php
			}

			if ($slider_fullscreen) {
			?>
			<div class="fullScreenSlider">
			<?php
			}

			if ($show_top_panel=='below') { get_template_part('templates/page-part-slider'); }

			if ($show_top_panel!='hide') { 
			?>
			<header class="noFixMenu menu_<?php echo get_custom_option('menu_align'); ?> <?php echo get_custom_option('show_user_menu')!='yes' ? 'without' : 'with'; ?>_user_menu">
				<div class="topWrapFixed"></div>
				<div class="topWrap" <?php echo esc_html($header_style); ?>>

					<?php if (get_custom_option('show_user_menu')=='yes') { ?>
						<div class="usermenu_area">
							<div class="main">
								<div class="menuUsItem menuItemRight">
									<?php 
									$THEMEREX_usermenu_show = true;
									get_template_part('templates/page-part-user-panel'); 
									?>
								</div>
								<?php if (get_custom_option('show_contact_info')=='yes') { ?>
								<div class="menuUsItem menuItemLeft"><?php echo get_custom_option('contact_info'); ?></div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>

					<div class="mainmenu_area">
						<div class="main with_logo_<?php echo get_custom_option('logo_align'); ?>">
							<div class="logo logo_<?php echo get_custom_option('logo_align'); ?><?php echo ($logo_text ? ' with_text' : ''); ?>">
								<a href="<?php echo home_url(); ?>"><?php echo ($logo_image ? '<img src="'.$logo_image.'" class="logo_main" alt=""><img src="'.$logo_fixed.'" class="logo_fixed" alt="">' : ''); ?><?php echo ($logo_text ? '<span class="logo_text">'.apply_filters('theme_logo_text', $logo_text, 'mainmenu').'</span>' : ''); ?><?php echo ($logo_slogan ? '<span class="logo_slogan">'.$logo_slogan.'</span>' : ''); ?></a>
							</div>
			
							<?php if (get_custom_option('show_search')=='yes') { ?>
								<div class="search" title="<?php _e('Open/close search form', 'themerex'); ?>">
									<div class="searchForm">
										<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
										<button type="submit" class="searchSubmit" title="<?php _e('Start search', 'themerex'); ?>"><span class="icoSearch"></span></button>
										<input type="text" class="searchField" placeholder="<?php _e('Search &hellip;', 'themerex'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _e('Search for:', 'themerex'); ?>" />
										</form>
									</div>
									<div class="ajaxSearchResults"></div>
								</div>
							<?php } ?>
		
							<?php if (get_custom_option('show_right_panel')=='yes' && get_custom_option('right_panel_button') == 'fixed') { ?>
								<a href="#" class="openRightMenu icon-menu" title="<?php _e('Open right panel (customization, widgets, menu and favorites)', 'themerex'); ?>"></a>
							<?php } ?>
		
							<a href="#" class="openResponsiveMenu"><?php _e('Menu', 'themerex'); ?></a>

							<?php
							//show main top menu
							if ( get_custom_option('show_top_panel') != 'hide' && $THEMEREX_mainmenu)  { ?>
								<nav role="navigation" class="menuTopWrap topMenuStyle<?php echo get_custom_option('menu_style')=='line' ? 'Line' : 'Fon'; ?>">
									<?php echo balanceTags($THEMEREX_mainmenu); ?>
								</nav>
							<?php }
							if ( get_custom_option('show_top_panel') != 'hide' && !$THEMEREX_mainmenu) echo '<h6 class="sc_show_menu_error">' . __('Please choose menu or create a new one in Appearance > Menus.','themerex') . '</h6>'; ?>
						</div>
					</div>
								
				</div>
			</header>
			<?php
			}

			if (in_array($show_top_panel, array('above', 'hide'))) { 
				get_template_part('templates/page-part-slider'); 
			} else if ($slider_show && $show_top_panel == 'over') { 
				?>
				<div class="sliderHomeBulletsGap" style="height:<?php  echo max(100, round(get_custom_option('slider_height')*0.8)); ?>px;"></div>
				<?php
			}

			if ($slider_fullscreen) { 
			?>
			</div>
			<?php
			}

			$show_user_header = get_custom_option('show_user_header');
			if (!empty($show_user_header) && $show_user_header != 'none') {
				$user_header = themerex_strclear(get_custom_option('user_header_content'), 'p');
				if (!empty($user_header)) {
					$user_header = substituteAll($user_header);
					?>
					<div class="userHeaderSection <?php echo esc_attr($show_user_header); ?>">
						<?php
						//if ($show_user_header != 'custom') { startWrapper('<div class="main">'); }
						echo balanceTags($user_header);
						//if ($show_user_header != 'custom') { stopWrapper(); }
						?>
					</div>
					<?php
				}
			}
			?>

			<?php if (get_custom_option('show_top_page') == 'yes') { ?>
				<?php if (get_custom_option('show_sidebar_top') == 'yes') { ?>
				<div class="widgetTabs">
				<?php } ?>

					<div id="topOfPage" class="topTabsWrap">
						<div class="main">
							<?php if (get_custom_option('show_breadcrumbs')=='yes') { ?>
								<div class="speedBar">
									<?php if (!is_404()) showBreadcrumbs(); ?>
								</div>
							<?php } ?>
							<?php if (get_custom_option('show_page_title')=='yes') { ?>
								<h3 class="pageTitle h3"><?php echo getBlogTitle(); ?></h3>
							<?php } ?>
							<?php
							if (get_custom_option('show_sidebar_top') == 'yes') { 
							?>
							<div class="tabsButton">
								<ul>
									<li><a href="#tabBlog"><span><?php echo is_archive() ? __('Blog', 'themerex') : __('Home', 'themerex'); ?></span></a></li>
								</ul>
							</div>
							<?php } ?>
						</div>
					</div>
			<?php } ?>

			<div class="mainWrap <?php echo getSidebarClass(get_custom_option('show_sidebar_main')); ?>">
				<?php
				if ($body_style!='fullscreen' && (!is_singular() || $single_style!='single-portfolio-fullscreen')) {
					startWrapper('<div class="main" role="main">');
				}
				
				startWrapper('<div class="content">');
				
				if (get_custom_option('show_top_page') == 'yes' && get_custom_option('show_sidebar_top') == 'yes') {
					startWrapper('<aside id="tabBlog" class="blogContent">');
				}
				?>
