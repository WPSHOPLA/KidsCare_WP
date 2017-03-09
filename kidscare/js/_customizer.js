// Customization panel

jQuery(document).ready(function() {
	"use strict";

	if (jQuery("#custom_options").length===1) {

		// Reset custom settings to default
		jQuery("#custom_options .co_reset_to_default" ).click(function(e) {
			"use strict";
			clearCustomCookies();
			window.location = jQuery("#custom_options #co_site_url").val();
			e.preventDefault();
			return false;
		});

		// Body and Main menu style
		jQuery("#custom_options .switcher a,#custom_options .switcher2 a" ).draggable({
			axis: 'x',
			containment: 'parent',
			stop: function() {
				var left = parseInt(jQuery(this).css('left'), 10);
				var curStyle = left < 25 ? (jQuery(this).parent().hasClass('switcher') ? 'wide' : 'line') : (jQuery(this).parent().hasClass('switcher') ? 'boxed' : 'fon');
				switchBox(jQuery(this).parent(), curStyle, true);
			}
		});
		jQuery("#custom_options .switcher, #custom_options .switcher2" ).click(function(e) {
			"use strict";
			switchBox(jQuery(this));
			e.preventDefault();
			return false;
		});
		jQuery("#custom_options .co_switch_box .co_switch_label").click(function(e) {
			"use strict";
			var state = jQuery(this).hasClass('boxed') ? 'boxed' : (jQuery(this).hasClass('wide') ? 'wide' : (jQuery(this).hasClass('line') ? 'line' : 'fon'));
			switchBox(jQuery(this).siblings('div'), state);
			e.preventDefault();
			return false;
		});

		// Main theme color and Background color
		iColorPicker();
		jQuery('#custom_options .iColorPicker').click(function (e) {
			"use strict";
			iColorShow(null, jQuery(this), changeThemeColor);
		});
		
		// Background patterns
		jQuery('#custom_options #co_bg_pattern_list a').click(function(e) {
			"use strict";
			jQuery("#custom_options .co_switch_box .boxed").trigger('click');
			jQuery('#custom_options #co_bg_pattern_list .co_pattern_wrapper,#custom_options #co_bg_images_list .co_image_wrapper').removeClass('current');
			var obj = jQuery(this).addClass('current');
			var val = obj.attr('id').substr(-1);
			if (THEMEREX_remember_visitors_settings) {
				jQuery.cookie('bg_color', null, {expires: -1, path: '/'});
				jQuery.cookie('bg_image', null, {expires: -1, path: '/'});
				jQuery.cookie('bg_pattern', val, {expires: 1, path: '/'});
			}
			jQuery(document).find('body').removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3').addClass('bg_pattern_' + val);
			e.preventDefault();
			return false;
		});
		// Background images
		jQuery('#custom_options #co_bg_images_list a').click(function(e) {
			"use strict";
			jQuery("#custom_options .co_switch_box .boxed").trigger('click');
			jQuery('#custom_options #co_bg_images_list .co_image_wrapper,#custom_options #co_bg_pattern_list .co_pattern_wrapper').removeClass('current');
			var obj = jQuery(this).addClass('current');
			var val = obj.attr('id').substr(-1);
			if (THEMEREX_remember_visitors_settings) {
				jQuery.cookie('bg_color', null, {expires: -1, path: '/'});
				jQuery.cookie('bg_pattern', null, {expires: -1, path: '/'});
				jQuery.cookie('bg_image', val, {expires: 1, path: '/'});
			}
			jQuery(document).find('body').removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_pattern_6 bg_pattern_7 bg_pattern_8 bg_pattern_9 bg_pattern_0 bg_image_1 bg_image_2 bg_image_3 bg_image_4 bg_image_5 bg_image_6').addClass('bg_image_' + val);
			e.preventDefault();
			return false;
		});
		jQuery('#custom_options #co_bg_pattern_list a,#custom_options #co_bg_images_list a').hover(
			function() {
				"use strict";
				var pattern = jQuery(this).parent().attr('id')=='co_bg_pattern_list';
				jQuery(this).parent().parent().css({
					'backgroundImage': 'url('+jQuery(this).find('img').attr('src').replace('_thumb2', '_thumb')+')',
					'backgroundRepeat': pattern ? 'repeat' : 'no-repeat'
				});
			},
			function() {
				"use strict";
				jQuery(this).parent().parent().css('backgroundImage', 'none');
			}
		);
	}
});


function clearCustomCookies() {
	jQuery.cookie('theme_color', null, {expires: -1, path: '/'});
	jQuery.cookie('menu_color', null, {expires: -1, path: '/'});
	jQuery.cookie('user_menu_color', null, {expires: -1, path: '/'});
	jQuery.cookie('bg_image', null, {expires: -1, path: '/'});
	jQuery.cookie('bg_pattern', null, {expires: -1, path: '/'});
	jQuery.cookie('bg_color', null, {expires: -1, path: '/'});
	jQuery.cookie('body_style', null, {expires: -1, path: '/'});
	jQuery.cookie('menu_style', null, {expires: -1, path: '/'});
}


function switchBox(box) {
	"use strict";
	var toStyle = arguments[1] ? arguments[1] : '';
	var important = arguments[2] ? arguments[2] : false;
	var switcher = box.find('a').eq(0);
	var left = parseInt(switcher.css('left'), 10);
	var newStyle = left < 5 ? (box.hasClass('switcher') ? 'boxed' : 'fon') : (box.hasClass('switcher') ? 'wide' : 'line');
	if (toStyle==='' || important || newStyle === toStyle) {
		if (toStyle==='') {toStyle = newStyle;}
		var right = box.width() - switcher.width() - 7;
		if (toStyle === 'wide' || toStyle === 'line')
			switcher.animate({left: 0}, 200);
		else
			switcher.animate({left: right}, 200);
		if (box.hasClass('switcher')) {
			if (THEMEREX_remember_visitors_settings) jQuery.cookie('body_style', toStyle, {expires: 1, path: '/'});
			jQuery(document).find('body').removeClass(toStyle==='boxed' ? 'wide' : 'boxed').addClass(toStyle);
			jQuery(document).trigger('resize');
		} else {
			if (THEMEREX_remember_visitors_settings) jQuery.cookie('menu_style', toStyle, {expires: 1, path: '/'});
			jQuery(document).find('.menuTopWrap').removeClass(toStyle==='fon' ? 'topMenuStyleLine' : 'topMenuStyleFon').addClass('topMenuStyle'+(toStyle==='fon' ? 'Fon' : 'Line'));
			calcMenuColumnsWidth();
		}
	}
	return newStyle;
}


function changeThemeColor(fld, clr) {
	"use strict";
	fld.css('backgroundColor', clr);
	fld.siblings('input').attr('value', clr);

	if (fld.attr('id')==='co_bg_color') {
		jQuery("#custom_options .co_switch_box .boxed").trigger('click');
		jQuery('#custom_options #co_bg_pattern_list .co_pattern_wrapper,#custom_options #co_bg_images_list .co_image_wrapper').removeClass('current');
		if (THEMEREX_remember_visitors_settings) {
			jQuery.cookie('bg_image', null, {expires: -1, path: '/'});
			jQuery.cookie('bg_pattern', null, {expires: -1, path: '/'});
			jQuery.cookie('bg_color', clr, {expires: 1, path: '/'});
		}
		jQuery(document).find('body').removeClass('bg_pattern_1 bg_pattern_2 bg_pattern_3 bg_pattern_4 bg_pattern_5 bg_image_1 bg_image_2 bg_image_3').css('backgroundColor', clr);
		return;
	}

	if (THEMEREX_remember_visitors_settings) {
		if (fld.attr('id')==='co_theme_color')
			jQuery.cookie('theme_color', clr, {expires: 1, path: '/'});
		else if (fld.attr('id')==='co_menu_color')
			jQuery.cookie('menu_color', clr, {expires: 1, path: '/'});
		else if (fld.attr('id')==='co_user_menu_color')
			jQuery.cookie('user_menu_color', clr, {expires: 1, path: '/'});
	}

	// This way - with page reload
	//window.location = jQuery("#custom_options #co_site_url").val();
	// This way - without reload
	var styles = jQuery('#theme-skin-css').length > 0 ? jQuery('#theme-skin-css').next() : '';
	if (styles.length == 0 || styles.attr('type')!='text/css') styles = jQuery('#packed-styles-css').length > 0 ? jQuery('#packed-styles-css').next() : '';
	if (styles.length == 0 || styles.attr('type')!='text/css') styles = jQuery('#shortcodes-css').length > 0 ? jQuery('#shortcodes-css').next() : '';
	if (styles.length > 0 && styles.attr('type')=='text/css') {
		clr = rgb2hex(jQuery('#co_theme_color').css('backgroundColor'));
		var rgb = hex2rgb(clr);
		var css_text = 
			// Main color for site
			'a:hover,.theme_accent,.topTabsWrap .speedBar a:hover,.topWrap .topMenuStyleFon > ul li a:hover,.topWrap .topMenuStyleFon > ul > li.sfHover > a,.topWrap .topMenuStyleFon > ul > li > a.sf-with-ul:hover,.topWrap .topMenuStyleFon > ul > li ul a.sf-with-ul:after,.topWrap .topMenuStyleLine > ul > li ul li a:hover,.topMenuStyleFon ul#mainmenu .menu-panel ul.columns > li > a,.topMenuStyleFon ul#mainmenu .menu-panel ul.columns > li a:hover,.topMenuStyleFon ul#mainmenu .menu-panel ul.columns > li ul li a:hover,.topMenuStyleFon ul#mainmenu .menu-panel ul.thumb_title > li > a,.topMenuStyleFon ul#mainmenu .menu-panel ul.thumb_title > li > a:hover,.infoPost a:hover, .tabsButton ul li a:hover,.widgetWrap  ul  li:before,.popularFiltr ul li a:hover,.isotopeFiltr ul li a:hover,.widget_popular_posts article h3:before,.widgetTabs .widget_popular_posts article .post_info .post_date a:hover,.sidebar .widget_popular_posts article .post_info .post_date a:hover,.sidebar .widget_recent_posts article .post_info .post_date a:hover,.main .widgetWrap a:hover,.main .widgetWrap a:hover span,.widgetWrap a:hover span,.roundButton:hover a,input[type="submit"]:hover,input[type="button"]:hover,.squareButton > a:hover,.squareButton.border > a,.roundButton.border > a,.nav_pages_parts > a:hover,.nav_comments > a:hover,.comments_list a.comment-edit-link:hover,.widget_area ul.tabs > li.squareButtonlite.ui-state-active > a,.wp-calendar tbody td a,.wp-calendar tbody td.today a:hover,blockquote cite,blockquote cite a,.sc_quote_title,.sc_quote_title a,.postLink a,.masonry article .masonryInfo a:hover,.masonry article .masonryInfo span.infoTags a:hover,.relatedPostWrap article .relatedInfo a:hover,.relatedPostWrap article .relatedInfo span.infoTags a:hover,.infoPost span.infoTags a:hover,.page404 p a,.page404 .searchAnimation.sFocus .searchIcon,.sc_team .sc_team_item .sc_team_item_position,.copyWrap a,.comments .commBody li.commItem .replyWrap .posted a:hover,.comments .commBody li.commItem h4 a:hover,.ratingItem span:before,.reviewBlock .totalRating,.widget_area .contactInfo .fContact:before,.widget_area .widgetWrap a:hover,.widget_area .widgetWrap a:hover span,.widget_area .widgetWrap ul > li > a:hover, .widget_area .widgetWrap ul > li > a:hover span,.footerStyleLight .widget_area article .post_title:before,.footerStyleLight .widget_area article .post_info a:hover,.footerStyleLight .widget_area article .post_info .post_date a:hover,.sc_list_style_arrows li:before,.sc_list_style_arrows li a:hover,.sc_list_style_iconed li a:hover,.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title,.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:before,.sc_accordion.sc_accordion_style_2 .sc_accordion_item.sc_active .sc_accordion_title,.sc_accordion.sc_accordion_style_2 .sc_accordion_item.sc_active .sc_accordion_title:before,.sc_accordion.sc_accordion_style_3 .sc_accordion_item.sc_active .sc_accordion_title,.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title,.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:before,.sc_toggles.sc_toggles_style_2 .sc_toggles_item.sc_active .sc_toggles_title,.sc_toggles.sc_toggles_style_2 .sc_toggles_item.sc_active .sc_toggles_title:before,.sc_toggles.sc_toggles_style_3 .sc_toggles_item.sc_active .sc_toggles_title,.sc_tabs .sc_tabs_titles li a:hover,.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcap,.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcap,.sc_dropcaps.sc_dropcaps_style_5 .sc_dropcap,.sc_dropcaps.sc_dropcaps_style_6 .sc_dropcap,.sc_highlight.sc_highlight_style_2,.sc_price_item .sc_price_money,.sc_price_item .sc_price_penny,.sc_pricing_table .sc_pricing_columns ul li .sc_icon,.sc_tooltip_parent,.sc_title_icon:before,.sc_scroll_controls .flex-direction-nav a:hover:before,.sc_testimonials_style_1 .flex-direction-nav a:hover:before,.sc_testimonials_style_3 .flex-direction-nav a:hover:before,.sc_testimonials_style_3 .flex-direction-nav a:active:before,.pagination .pageLibrary > li.libPage > .pageFocusBlock .flex-direction-nav a:hover:before,.topWrap .usermenu_area ul.usermenu_list li.usermenu_currency > a:hover,.topWrap .usermenu_area ul.usermenu_list li.usermenu_currency > a,.topWrap .usermenu_area ul.usermenu_list li.usermenu_currency.sfHover > a,.topWrap .usermenu_area ul.usermenu_list li ul li a:hover,.topWrap .usermenu_area ul.usermenu_list li.usermenu_cart .widget_area ul li a:hover,.sidemenu_wrap .usermenu_area ul.usermenu_list li.usermenu_currency > a:hover,.sidemenu_wrap .usermenu_area ul.usermenu_list li.usermenu_currency > a,.sidemenu_wrap .usermenu_area ul.usermenu_list li.usermenu_currency.sfHover > a,.sidemenu_wrap .usermenu_area ul.usermenu_list li ul li a:hover,.sidemenu_wrap .usermenu_area ul.usermenu_list li.usermenu_cart .widget_area ul li a:hover,.sc_blogger a:hover,.sc_blogger.style_date .load_more:before,.sc_blogger.style_date .sc_blogger_item .sc_blogger_date .day_month,.sc_blogger.style_date .sc_blogger_item .sc_blogger_info .comments_number,.sc_blogger.style_accordion .sc_blogger_info .comments_number,.widgetTabs .widgetTop ul > li:not(.tabs):before, .widgetTabs .widgetTop ul > li:not(.tabs) > a:hover, .widgetTabs .widgetTop ul > li:not(.tabs) > a:hover span,.widgetTabs .widgetTop.widget_popular_posts article .post_title:before,.swpRightPos .tabsMenuBody a:hover,.swpRightPos .tabsMenuBody a:hover:before,.openRightMenu:hover:before,.topWrap .search:not(.searchOpen):hover:before,.user-popUp .formItems.loginFormBody .remember .forgotPwd,.user-popUp .formItems.loginFormBody .loginProblem,.user-popUp .formItems.registerFormBody .i-agree a,.sc_slider_pagination_area .flex-control-nav.manual .slide_info .slide_title,#toc .toc_item.current .toc_icon,#toc .toc_item:hover .toc_icon'

				// Main color for WooC
				+',.woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page ul.products li.product .price,.woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,.woocommerce .quantity input[type="button"]:hover, .woocommerce #content input[type="button"]:hover, .woocommerce-page .quantity input[type="button"]:hover, .woocommerce-page #content .quantity input[type="button"]:hover,.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount,.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount,.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,.woocommerce a:hover h3, .woocommerce-page a:hover h3,.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before,.widget_area .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a'
				+' { color: '+clr+'; }'
		
				// Main color for site !important
				+'.topWrap .topMenuStyleLine > ul > li ul li a:hover, .footerStyleDark .widget_area a.button:hover,.flip-clock-wrapper ul li a div div.inn,.footerStyleDark .widget_area .squareButton > a'
				+' { color: '+clr+' !important; }'
		
				// Background color for site
				+'.theme_accent_bgc,.topWrap,.sidemenu_wrap .menuTranform,.sc_video_player:active .sc_video_play_button:after,.mejs-controls .mejs-button button:active,.mejs-container .mejs-controls .mejs-time-rail .mejs-time-current,.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,input[type="submit"]:active,input[type="button"]:active,.squareButton.active > span,.squareButton.active > a,.squareButton.ui-state-active > a,.roundButton > a:active,.squareButton > a:active,.squareButton.global > a,.squareButton.dark > a:active,.squareButton.border > a:hover,.roundButton.border:hover > a,.nav_pages_parts > span.page_num,.nav_comments > span.current,ul > li.likeActive:active > a,.sc_table.sc_table_style_1 table tr:first-child th,.sc_table.sc_table_style_1 table tr:first-child td,.masonry article .status,.portfolio .isotopeElement .folioShowBlock:before,.post .postStatus,.sc_team .sc_team_item .sc_team_item_avatar:after,.itemPageFull .itemDescriptionWrap .toggleButton:active,.footerWrap .footerWidget .sc_video_player:active .sc_video_play_button:after,.topWrap .topMenuStyleLine > ul > li ul,.sidemenu_wrap .sidemenu_button,.userHeaderSection.global,.sliderLogo .elastislide-wrapper nav span:active:before,.sc_skills_bar .sc_skills_item .sc_skills_count,.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info,.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcap,.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcap,.sc_highlight.sc_highlight_style_1,.sc_pricing_table .sc_pricing_columns .sc_pricing_title,.sc_pricing_table .sc_pricing_columns:hover ul li.sc_pricing_title,.sc_tooltip_parent .sc_tooltip,.sc_tooltip_parent .sc_tooltip:before,.sc_title_bg:before,.sc_accordion.sc_accordion_style_3 .sc_accordion_item .sc_accordion_title,.sc_toggles.sc_toggles_style_3 .sc_toggles_item .sc_toggles_title,.sc_scroll_controls .flex-direction-nav a:active,.sc_testimonials_style_1 .flex-direction-nav a:active,.sc_testimonials_style_3 .sc_testimonials_items,.sc_testimonials_style_3 .flex-direction-nav li,.sc_testimonials_style_3 .flex-direction-nav a,.pagination .pageLibrary > li.libPage > .pageFocusBlock .flex-direction-nav a:active,.sc_popup_light:before,.user-popUp ul.loginHeadTab li.ui-tabs-active:before,.sc_banner:before,.global_bg,.widgetWrap .tagcloud a:hover,.widgetWrap .tagcloud a:active,.sc_scroll_bar .swiper-scrollbar-drag:before,.widgetTabs .widgetTop .tagcloud a:hover,.widgetTabs .widgetTop .tagcloud a:active,#custom_options .co_options #co_bg_images_list a.current,#custom_options .co_options #co_bg_pattern_list a.current,.fullScreenSlider.globalColor .sliderHomeBullets .rsContent:before,.fullScreenSlider .sliderHomeBullets .rsContent .slide-3 .order p span, ul.sc_list_style_disk li:before,.topMenuStyleLine > ul .menu-panel,.sc_slider_pagination_area .flex-control-nav.manual .slide_date,.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a,.sc_contact_form_custom .bubble label:hover,.sc_contact_form_custom .bubble label.selected'
				// Background color for WooC
				+',.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range'
				// Background color for TribeEvents
				+',.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"], .tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"] > a, #tribe_events_filters_wrapper input[type="submit"], .tribe-events-button, #tribe-events .tribe-events-button, .tribe-events-button.tribe-inactive, #tribe-events .tribe-events-button:hover, .tribe-events-button:hover, .tribe-events-button.tribe-active:hover'
				+' { background-color: '+clr+'; }'
				
				// Background for WooC
				+'.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle'
				// Background color for TribeEvents
				+'#tribe-bar-form .tribe-bar-submit input[type="submit"]'
				+' { background: '+clr+'; }'
				
				// Transparent background color for site
				+'.sc_slider_flex .sc_slider_info,.sc_slider_chop .sc_slider_info,.sc_slider_swiper .sc_slider_info,.sc_slider_flex .flex-direction-nav li,.sc_slider_chop .flex-direction-nav li,.sc_slider_swiper .flex-direction-nav li'
				+' { background-color: rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.8) !important; }'

				+'.top_panel_above .fullScreenSlider .topWrap,.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li ul,.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li .menu-panel'
				+' { background-color: rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.8); }'
		
				// Border color for site
				+'.sc_table.sc_table_style_1 table tr:first-child th,.sc_table.sc_table_style_1 table tr:first-child td'
				+' {border-top-color: '+clr+'; }'
				+'.sc_table.sc_table_style_1 table tr:first-child th:first-child,.sc_table.sc_table_style_1 table tr:first-child td:first-child'
				+' {border-left-color: '+clr+';}'
				+'.sc_table.sc_table_style_1 table tr:first-child th:last-child,.sc_table.sc_table_style_1 table tr:first-child td:last-child'
				+' {border-right-color: '+clr+';}'

				+'.theme_accent_border,.postSharing > ul > li > a:active,.postSharing > ul > li > span:active.mejs-controls .mejs-button button:active,.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current,.squareButton.active > span,.squareButton.active > a,.roundButton > a:active,.squareButton > a:active,.squareButton.ui-state-active > a,.squareButton.global > a,.squareButton.dark > a:active,.squareButton.border > a,.roundButton.border > a,.nav_pages_parts > span.page_num,.nav_comments > span.current,.wp-calendar thead tr + tr th,.sc_skills_bar .sc_skills_item .sc_skills_count,.itemPageFull .itemDescriptionWrap .toggleButton:active,.footerWidget .sc_video_player:active .sc_video_play_button:after,.topWrap .topMenuStyleLine > ul > li ul,.topMenuStyleLine > ul#mainmenu ul.menu-panel,.sc_scroll_controls .flex-direction-nav a:active,.sc_testimonials_style_1 .flex-direction-nav a:active,.pagination .flex-direction-nav a:active,.sliderLogo .elastislide-wrapper nav span:active:before,.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcap,.sc_dropcaps.sc_dropcaps_style_5 .sc_dropcap,.sc_dropcaps.sc_dropcaps_style_6 .sc_dropcap,.sc_accordion.sc_accordion_style_3 .sc_accordion_item,.sc_toggles.sc_toggles_style_3 .sc_toggles_item,.sc_tooltip_parent,pre.code,.widgetWrap .tagcloud a:hover,.widgetWrap .tagcloud a:active,.topWrap .openRightMenu:hover,.topWrap .search:not(.searchOpen):hover,#toc .toc_item.current,#toc .toc_item:hover'
				+' {border-color: '+clr+'; }'
				
				// Border color for WooC
				+'.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active'
				+' {border-top-color: '+clr+'; }'
		
				// Background for iHover
				+'.theme_accent_bg,.ih-item.circle.effect1.colored .info,.ih-item.circle.effect2.colored .info,.ih-item.circle.effect3.colored .info,.ih-item.circle.effect4.colored .info,.ih-item.circle.effect5.colored .info .info-back,.ih-item.circle.effect6.colored .info,.ih-item.circle.effect7.colored .info,.ih-item.circle.effect8.colored .info,.ih-item.circle.effect9.colored .info,.ih-item.circle.effect10.colored .info,.ih-item.circle.effect11.colored .info,.ih-item.circle.effect12.colored .info,.ih-item.circle.effect13.colored .info,.ih-item.circle.effect14.colored .info,.ih-item.circle.effect15.colored .info,.ih-item.circle.effect16.colored .info,.ih-item.circle.effect18.colored .info .info-back,.ih-item.circle.effect19.colored .info,.ih-item.circle.effect20.colored .info .info-back,.ih-item.square.effect1.colored .info,.ih-item.square.effect2.colored .info,.ih-item.square.effect3.colored .info,.ih-item.square.effect4.colored .mask1,.ih-item.square.effect4.colored .mask2,.ih-item.square.effect5.colored .info,.ih-item.square.effect6.colored .info,.ih-item.square.effect7.colored .info,.ih-item.square.effect8.colored .info,.ih-item.square.effect9.colored .info .info-back,.ih-item.square.effect10.colored .info,.ih-item.square.effect11.colored .info,.ih-item.square.effect12.colored .info,.ih-item.square.effect13.colored .info,.ih-item.square.effect14.colored .info,.ih-item.square.effect15.colored .info'
				+' { background:'+clr+'; }'
				
				// Background for iHover
				+'.ih-item.circle.effect1.colored .info,.ih-item.circle.effect2.colored .info,.ih-item.circle.effect5.colored .info .info-back,.ih-item.circle.effect19.colored .info,.ih-item.circle.effect20.colored .info .info-back,.ih-item.square.effect4.colored .mask1,.ih-item.square.effect4.colored .mask2,.ih-item.square.effect6.colored .info,.ih-item.square.effect7.colored .info,.ih-item.square.effect12.colored .info,.ih-item.square.effect13.colored .info,.sc_image_shape_round:hover figcaption,.post .sc_image_shape_round:hover figcaption'
				+' { background: rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.6); }'
		
				// Background for iHover
				+'.ih-item.circle.effect17.colored a:hover .img:before'
				+' { box-shadow: inset 0 0 0 110px '+clr+', inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1); box-shadow: inset 0 0 0 110px rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1); }'
				
				// Border color for iHover
				+'.ih-item.circle.effect1 .spinner'
				+' {border-right-color: '+clr+'; border-bottom-color: '+clr+'; }'
				
				// Media Elements background
				+'.mejs-container, .mejs-embed, .mejs-embed body, .mejs-container .mejs-controls'
				+' { background:'+clr+' !important; }'
				+'.mejs-controls .mejs-volume-button .mejs-volume-slider'
				+' { background: rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.7) !important; }';

		if (window.theme_skin_set_theme_color)
			css_text = theme_skin_set_theme_color(css_text, clr);


		// Main menu (Top panel) background
		clr = rgb2hex(jQuery('#co_menu_color').css('backgroundColor'));
		rgb = hex2rgb(clr);

		css_text += 
			'.topWrap,.topWrap .topMenuStyleLine > ul > li ul,.topMenuStyleLine > ul .menu-panel,.usermenu_area'
			+' { background-color: '+clr+'; }'

			+'.top_panel_above .fullScreenSlider .topWrap,.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li ul,.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li .menu-panel'
			+' { background-color: rgba('+rgb.r+','+rgb.g+','+rgb.b+',0.8); }'

			+'.topWrap .topMenuStyleLine > ul > li ul,.topMenuStyleLine > ul#mainmenu ul.menu-panel'
			+' { border-color: '+clr+'; }';

		if (window.theme_skin_set_menu_bgcolor)
			css_text = theme_skin_set_menu_bgcolor(css_text, clr);
			
		// Main menu (Top panel) colors
		var hsb = hex2hsb(clr);
		var clr01, clr02, clr04, clr05, clr07;
		if (hsb.b > 90 && ((hsb.h >= 45 && hsb.h <= 185) || hsb.s < 20)) {
			clr   = '#272727';
			clr01 = 'rgba(39,39,39,0.1)';
			clr02 = 'rgba(39,39,39,0.2)';
			clr04 = 'rgba(39,39,39,0.4)';
			clr05 = 'rgba(39,39,39,0.5)';
			clr07 = 'rgba(39,39,39,0.7)';
		} else {
			clr   = '#ffffff';
			clr01 = 'rgba(255,255,255,0.1)';
			clr02 = 'rgba(255,255,255,0.2)';
			clr04 = 'rgba(255,255,255,0.4)';
			clr05 = 'rgba(255,255,255,0.5)';
			clr07 = 'rgba(255,255,255,0.7)';
		}
		css_text += 
			'.logo, .logo_text,.logo a,.topWrap .topMenuStyleLine > ul > li > a,.topWrap .topMenuStyleLine > ul > li ul li a,.topMenuStyleLine ul#mainmenu .menu-panel .item_placeholder .item_title,.topMenuStyleLine ul#mainmenu .menu-panel .item_placeholder .item_title a,.topMenuStyleLine ul#mainmenu .menu-panel.thumb .item_placeholder .item_title,.topMenuStyleLine ul#mainmenu .menu-panel.thumb .item_placeholder .item_title a,.topMenuStyleLine ul#mainmenu .menu-panel .item_placeholder .item_info > * > span,.topMenuStyleLine ul#mainmenu .menu-panel .item_placeholder .item_info > * > em,.topMenuStyleLine ul#mainmenu .menu-panel ul.columns > li a,.topWrap .topMenuStyleFon > ul > li > a,.top_panel_above .fullScreenSlider .topWrap .topMenuStyleLine > ul > li a,.topMenuStyleLine ul#mainmenu > li ul li a .menu_icon'
			+' { color: '+clr+'; }'
			+'.topMenuStyleLine ul#mainmenu .menu-panel ul.columns > li > a,.topMenuStyleLine ul#mainmenu .menu-panel ul.thumb_title > li > a,.topMenuStyleLine ul#mainmenu .menu-panel ul.columns > li > a:hover,.topMenuStyleLine ul#mainmenu .menu-panel ul.thumb_title > li > a:hover'
			+' { color: '+clr+' !important;	}'
			+'.topWrap .topMenuStyleLine > ul > li:after'
			+' { background:'+clr+'; }'
			+'.topWrap .search:before,.topWrap .search .searchForm .searchSubmit .icoSearch:before,.openRightMenu:before'
			+' { color: '+clr04+'; }'
			+'.topWrap .usermenu_area,.topWrap .search .searchForm .searchField'
			+' { color: '+clr05+'; }'
			+'.topWrap .usermenu_area a,.menu_item_description,.logo .logo_slogan'
			+' { color: '+clr07+'; }'
			+'.topWrap .usermenu_area a:hover,.topWrap .usermenu_area ul.usermenu_list > li.sfHover > a'
			+' { color: '+clr+'; }'
			+'.topWrap .search, .openRightMenu'
			+' { border-color: '+clr04+'; }'
			+'.topMenuStyleLine ul#mainmenu .menu-panel ul.columns > li + li'
			+' { border-color: '+clr02+'; }'
			+'.openResponsiveMenu'
			+' { border-top-color: '+clr02+'; color: '+clr+'; }'
			+'.responsive_menu .menuTopWrap > ul > li'
			+' { border-bottom-color: '+clr01+'; }';

		if (window.theme_skin_set_menu_color)
			css_text = theme_skin_set_menu_color(css_text, clr);


		// User panel background
		clr = rgb2hex(jQuery('#co_user_menu_color').css('backgroundColor'));
		rgb = hex2rgb(clr);

		css_text += 
			'.usermenu_area'
			+' { background-color: '+clr+'; }';

		if (window.theme_skin_set_user_menu_bgcolor)
			css_text = theme_skin_set_user_menu_bgcolor(css_text, clr);

		// User menu color
		hsb = hex2hsb(clr);
		if (hsb.b > 90 && ((hsb.h >= 45 && hsb.h <= 185) || hsb.s < 20)) {
			clr   = '#272727';
			clr01 = 'rgba(39,39,39,0.1)';
			clr05 = 'rgba(39,39,39,0.5)';
			clr07 = 'rgba(39,39,39,0.7)';
		} else {
			clr   = '#ffffff';
			clr01 = 'rgba(255,255,255,0.1)';
			clr05 = 'rgba(255,255,255,0.5)';
			clr07 = 'rgba(255,255,255,0.7)';
		}
		css_text += 
			'.topWrap .usermenu_area'
			+' { color: '+clr05+'; }'
			+'.topWrap .usermenu_area a'
			+' { color: '+clr07+'; }'
			+'.topWrap .usermenu_area a:hover,.topWrap .usermenu_area ul.usermenu_list > li.sfHover > a'
			+' { color: '+clr+'; }';
		if (window.theme_skin_set_user_menu_color)
			css_text = theme_skin_set_user_menu_color(css_text, clr);

		// Apply styles
		styles.html(css_text);
	}
}
