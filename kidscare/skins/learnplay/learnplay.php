<?php
/**
 * Learn&Play skin file for theme.
 */


//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
add_filter('theme_skin_use_fonts', 'theme_skin_use_fonts_learnplay');
function theme_skin_use_fonts_learnplay($theme_fonts) {
	$theme_fonts['Fredoka One'] = 1;
	$theme_fonts['Ubuntu'] = 1;
	return $theme_fonts;
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present). To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
add_filter('theme_skin_list_fonts', 'theme_skin_list_fonts_learnplay');
function theme_skin_list_fonts_learnplay($list) {
	//$list['Advent Pro'] = array(
	//		'family'=>'sans-serif',																						// (required) font family
	//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
	//		'css'=>themerex_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
	//		);
	if (!isset($list['Fredoka One']))	$list['Fredoka One'] = array('family'=>'cursive');
	if (!isset($list['Ubuntu']))	$list['Ubuntu'] = array('family'=>'sans-serif');
	return $list;
}


//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------

// Add skin stylesheets
add_action('theme_skin_add_stylesheets', 'theme_skin_add_stylesheets_learnplay');
function theme_skin_add_stylesheets_learnplay() {
	themerex_enqueue_style( 'theme-skin', themerex_get_file_url('/skins/learnplay/learnplay.css'), array('main-style'), null );
}

// Add skin inline styles
add_filter('theme_skin_add_styles_inline', 'theme_skin_add_styles_inline_learnplay');
function theme_skin_add_styles_inline_learnplay($custom_style) {
	return $custom_style;	
}

// Add skin responsive styles
add_action('theme_skin_add_responsive', 'theme_skin_add_responsive_learnplay');
function theme_skin_add_responsive_learnplay() {
	if (file_exists(themerex_get_file_dir('/skins/learnplay/learnplay-responsive.css')))
		themerex_enqueue_style( 'theme-skin-responsive', themerex_get_file_url('/skins/learnplay/learnplay-responsive.css'), array('theme-skin'), null );
}

// Add skin responsive inline styles
add_filter('theme_skin_add_responsive_inline', 'theme_skin_add_responsive_inline_learnplay');
function theme_skin_add_responsive_inline_learnplay($custom_style) {
	return $custom_style;	
}


//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
add_action('theme_skin_add_scripts', 'theme_skin_add_scripts_learnplay');
function theme_skin_add_scripts_learnplay() {
	if (file_exists(themerex_get_file_dir('/skins/learnplay/learnplay.js')))
		themerex_enqueue_script( 'theme-skin-script', themerex_get_file_url('/skins/learnplay/learnplay.js'), array('main-style'), null );
}

// Add skin scripts inline
add_action('theme_skin_add_scripts_inline', 'theme_skin_add_scripts_inline_learnplay');
function theme_skin_add_scripts_inline_learnplay() {
	?>
	if (THEMEREX_theme_font=='') THEMEREX_theme_font = 'Sintony';

	// Add skin custom colors in custom styles
	function theme_skin_set_theme_color(custom_style, clr) {
		custom_style += 'h1, h2, h4, h5, h6,.h1, .h2, .h4, .h5, .h6,h1 > a, h2 > a, h4 > a, h5 > a, h6 > a,.h1 > a, .h2 > a, .h4 > a, .h5 > a, .h6 > a,.sc_blogger a,.relatedPostWrap article .relatedInfo a,.sc_list_style_iconed li:before,.sc_list_style_arrows li:before,.sc_team .sc_team_item .sc_team_item_title,.footerStyleLight .footerWidget .widgetWrap .title,.wp-calendar thead th span,.footerStyleLight .footerWidget .wp-calendar thead th,.wp-calendar thead tr + tr th'
+'{ color:'+clr+'; }'
+'.tabsButton ul li.ui-tabs-active a'
+'{ color:'+clr+' !important; }'
+'.sc_video_player:hover .sc_video_play_button:after,.footerStyleLight .footerWidget .sc_video_player:hover .sc_video_play_button:after,/*.copyWrap,*/.sliderHomeBullets.slider_alias_13 .textPrice,.sliderHomeBullets.slider_alias_13 .slide-2 .textPrice,.videoBackgroundOverlay .copyWrap'
+'{ background-color:'+clr+'; }';
		return custom_style;
	}

	// Add skin's main menu (top panel) back color in the custom styles
	function theme_skin_set_menu_bgcolor(custom_style, clr) {
		rgb = hex2rgb(clr);
		custom_style += 'h3, .h3, h3 > a, .h3 > a,.sc_blogger h3 > a, .sc_blogger .h3 > a,.openRightMenu:hover:before,.topWrap .search:not(.searchOpen):hover:before,.sliderHomeBullets.slider_alias_15 .order a:hover,.sc_blogger.style_date .sc_blogger_item .sc_blogger_info .post_author,.sc_blogger.style_date .sc_blogger_item .sc_blogger_info .comments_number,.wp-calendar tbody td.today,.wp-calendar tbody td.today a'
+'{ color:'+clr+'; }'
+'.topWrap .topMenuStyleLine > ul > li ul li a:hover,.topWrap .topMenuStyleLine > ul > li ul li a:hover .menu_item_description,.topWrap .topMenuStyleFon > ul > li > a:hover,.topWrap .topMenuStyleFon > ul > li.sfHover > a,.topMenuStyleFon ul#mainmenu .menu-panel ul.columns > li > a,.topWrap .topMenuStyleFon > ul > li ul li a:hover,.topWrap .topMenuStyleFon > ul > li ul li a:hover .menu_item_description'
+'{ color:'+clr+' !important; }'
+'.topWrap,.topWrap .topMenuStyleLine > ul > li ul,.sliderHomeBullets.slider_alias_15 .order a,.dark .sc_slider_pagination_area .flex-control-nav.manual li.active .slide_date,.dark .sc_slider_pagination_area .flex-control-nav.manual li.cs-active-pagination .slide_date,.dark .sc_slider_pagination_area .flex-control-nav.manual li:hover .slide_date,.sc_blogger.style_date .sc_blogger_item .sc_blogger_date,.sc_video_player .sc_video_play_button:after,.footerStyleLight .footerWidget .sc_video_player .sc_video_play_button:after,.twitBlock,.twitBlockWrap,.sliderHomeBullets.slider_alias_13 .order a:hover'
+'{ background-color:'+clr+'; }'
+'.twitBlock .sc_slider .flex-direction-nav li'
+'{ background-color:'+clr+' !important; }'
+'.topWrap .topMenuStyleLine > ul > li ul,.sliderHomeBullets.slider_alias_13 .order a:hover'
+'{ border-color:'+clr+'; }'
+'.topWrap .openRightMenu:hover,.topWrap .search:not(.searchOpen):hover'
+'{ border-color: rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.4);}';
+'.top_panel_above .fullScreenSlider .topWrap,.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li ul,.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li .menu-panel'
+'{ background-color: rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.8);}';
		return custom_style;
	}

	// Add skin's main menu (top panel) fore colors in the custom styles
	function theme_skin_set_menu_color(custom_style, clr) {
		custom_style += '.openRightMenu,.topWrap .search'
+' { border-color:transparent; }';
		return custom_style;
	}

	// Add skin's user menu (user panel) back color in the custom styles
	function theme_skin_set_user_menu_bgcolor(custom_style, clr) {
		return custom_style;
	}

	// Add skin's user menu (user panel) fore colors in the custom styles
	function theme_skin_set_user_menu_color(custom_style, clr) {
		return custom_style;
	}
	<?php	
}


//------------------------------------------------------------------------------
// Get/Set skin's main (accent) theme color
//------------------------------------------------------------------------------


// Return main theme color (if not set in the theme options)
add_filter('theme_skin_get_theme_color', 'theme_skin_get_theme_color_learnplay', 10, 1);
function theme_skin_get_theme_color_learnplay($clr) {
	return empty($clr) ? '#0f6fa9' : $clr;
}

// Return main theme bg color
add_filter('theme_skin_get_theme_bgcolor', 'theme_skin_get_theme_bgcolor_learnplay', 10, 1);
function theme_skin_get_theme_bgcolor_learnplay($clr) {
	return '#ffffff';
}

// Add skin's specific theme colors in the custom styles
add_filter('theme_skin_set_theme_color', 'theme_skin_set_theme_color_learnplay', 10, 2);
function theme_skin_set_theme_color_learnplay($custom_style, $clr) {
	$custom_style .= '
h1, h2, h4, h5, h6,
.h1, .h2, .h4, .h5, .h6,
h1 > a, h2 > a, h4 > a, h5 > a, h6 > a,
.h1 > a, .h2 > a, .h4 > a, .h5 > a, .h6 > a,
.sc_blogger a,
.relatedPostWrap article .relatedInfo a,
.sc_list_style_iconed li:before,
.sc_list_style_arrows li:before,
.sc_team .sc_team_item .sc_team_item_title,
.footerStyleLight .footerWidget .widgetWrap .title,
.wp-calendar thead th span,
.footerStyleLight .footerWidget .wp-calendar thead th,
.wp-calendar thead tr + tr th
{ color:'.$clr.'; }
.tabsButton ul li.ui-tabs-active a
{ color:'.$clr.' !important; }
.sc_video_player:hover .sc_video_play_button:after,
.footerStyleLight .footerWidget .sc_video_player:hover .sc_video_play_button:after,
/*.copyWrap,*/
.sliderHomeBullets.slider_alias_13 .textPrice,
.sliderHomeBullets.slider_alias_13 .slide-2 .textPrice/*,
.videoBackgroundOverlay .copyWrap*/
{ background-color:'.$clr.'; }
';
	return $custom_style;
}


//------------------------------------------------------------------------------
// Get/Set skin's main menu (top panel) color
//------------------------------------------------------------------------------

// Return skin's main menu (top panel) background color (if not set in the theme options)
add_filter('theme_skin_get_menu_bgcolor', 'theme_skin_get_menu_bgcolor_learnplay', 10, 1);
function theme_skin_get_menu_bgcolor_learnplay($clr) {
	return empty($clr) ? '#e33c1c' : $clr;
}

// Add skin's main menu (top panel) background color in the custom styles
add_filter('theme_skin_set_menu_bgcolor', 'theme_skin_set_menu_bgcolor_learnplay', 10, 2);
function theme_skin_set_menu_bgcolor_learnplay($custom_style, $clr) {
	$rgb = hex2rgb($clr);
	$custom_style .= '
h3, .h3, h3 > a, .h3 > a,
.sc_blogger h3 > a, .sc_blogger .h3 > a,
.openRightMenu:hover:before,
.topWrap .search:not(.searchOpen):hover:before,
.sliderHomeBullets.slider_alias_15 .order a:hover,
.sc_blogger.style_date .sc_blogger_item .sc_blogger_info .post_author,
.sc_blogger.style_date .sc_blogger_item .sc_blogger_info .comments_number,
.wp-calendar tbody td.today,
.wp-calendar tbody td.today a
{ color:'.$clr.'; }
.topWrap .topMenuStyleLine > ul > li ul li a:hover,
.topWrap .topMenuStyleLine > ul > li ul li a:hover .menu_item_description,
.topWrap .topMenuStyleFon > ul > li > a.sf-with-ul:hover,
.topWrap .topMenuStyleFon > ul > li.sfHover > a,
.topMenuStyleFon ul#mainmenu .menu-panel ul.columns > li > a,
.topWrap .topMenuStyleFon > ul > li ul li a:hover,
.topWrap .topMenuStyleFon > ul > li ul li a:hover .menu_item_description
{ color:'.$clr.' !important; }
.topWrap,
.topWrap .topMenuStyleLine > ul > li ul,
.sliderHomeBullets.slider_alias_15 .order a,
.dark .sc_slider_pagination_area .flex-control-nav.manual li.active .slide_date,
.dark .sc_slider_pagination_area .flex-control-nav.manual li.cs-active-pagination .slide_date,
.dark .sc_slider_pagination_area .flex-control-nav.manual li:hover .slide_date,
.sc_blogger.style_date .sc_blogger_item .sc_blogger_date,
.sc_video_player .sc_video_play_button:after,
.footerStyleLight .footerWidget .sc_video_player .sc_video_play_button:after,
.twitBlock,
.twitBlockWrap,
.sliderHomeBullets.slider_alias_13 .order a:hover
{ background-color:'.$clr.'; }
.twitBlock .sc_slider .flex-direction-nav li
{ background-color:'.$clr.' !important; }
.topWrap .topMenuStyleLine > ul > li ul,
.sliderHomeBullets.slider_alias_13 .order a:hover { border-color:'.$clr.'; }
.topWrap .openRightMenu:hover,
.topWrap .search:not(.searchOpen):hover
{ border-color: rgba('.$rgb['r'].','.$rgb['g'].','.$rgb['b'].',0.4); }
.top_panel_above .fullScreenSlider .topWrap,
.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li ul,
.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li .menu-panel 
{ background-color: rgba('.$rgb['r'].','.$rgb['g'].','.$rgb['b'].',0.8);}
';
	return $custom_style;
}

// Add skin's main menu (top panel) fore colors in custom styles
add_filter('theme_skin_set_menu_color', 'theme_skin_set_menu_color_learnplay', 10, 2);
function theme_skin_set_menu_color_learnplay($custom_style, $clr) {
	$custom_style .= '
.openRightMenu,.topWrap .search
{ border-color:transparent; }
';
	return $custom_style;
}


//------------------------------------------------------------------------------
// Get/Set skin's user menu (user panel) color
//------------------------------------------------------------------------------

// Return skin's user menu color (if not set in the theme options)
add_filter('theme_skin_get_user_menu_bgcolor', 'theme_skin_get_user_menu_bgcolor_learnplay', 10, 1);
function theme_skin_get_user_menu_bgcolor_learnplay($clr) {
	return empty($clr) ? '#191d1e' : $clr;
}

// Add skin's user menu (user panel) background color in the custom styles
add_filter('theme_skin_set_user_menu_bgcolor', 'theme_skin_set_user_menu_bgcolor_learnplay', 10, 2);
function theme_skin_set_user_menu_bgcolor_learnplay($custom_style, $clr) {
	return $custom_style;
}

// Add skin's user menu (user panel) fore colors in custom styles
add_filter('theme_skin_set_user_menu_color', 'theme_skin_set_user_menu_color_learnplay', 10, 2);
function theme_skin_set_user_menu_color_learnplay($custom_style, $clr) {
	return $custom_style;
}
?>