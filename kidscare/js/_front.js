/* global jQuery:false */

// Max scale factor for the portfolio and other isotope elements before relayout
var THEMEREX_isotope_resize_delta = 0.3;


// Internal vars - do not change it!
var THEMEREX_ADMIN_MODE    = false;
var THEMEREX_error_msg_box = null;
var THEMEREX_VIEWMORE_BUSY = false;
var THEMEREX_video_resize_inited = false;
var THEMEREX_top_height = 0;
var THEMEREX_use_fixed_wrapper = true;

jQuery(document).ready(function () {
	"use strict";
	timelineResponsive()
	ready();
	timelineScrollFix();
	itemPageFull();
	mainMenuResponsive();
	scrollAction();
	calcMenuColumnsWidth();
	resizeVideoBackground();
	REX_parallax();
	REX_kidsCare_upToScroll();
    REX_kidsCare_animation();

	// Resize handlers
	jQuery(window).resize(function () {
		"use strict";
		timelineResponsive();
		fullSlider();
		resizeSliders();
		itemPageFull();
		mainMenuResponsive();
		scrollAction();
		resizeVideoBackground();
		REX_parallax();
	});

	// Scroll handlers
	jQuery(window).scroll(function () {
		"use strict";
		timelineScrollFix();
		scrollAction();
		REX_parallax();
		REX_kidsCare_upToScroll();
        REX_kidsCare_animation();
	});
});



function ready() {
	"use strict";

	// Show system message
	if (THEMEREX_systemMessage.message) {
		if (THEMEREX_systemMessage.status == 'success')
			themerex_message_success(THEMEREX_systemMessage.message, THEMEREX_systemMessage.header);
		else if (THEMEREX_systemMessage.status == 'info')
			themerex_message_info(THEMEREX_systemMessage.message, THEMEREX_systemMessage.header);
		else if (THEMEREX_systemMessage.status == 'error' || THEMEREX_systemMessage.status == 'warning')
			themerex_message_warning(THEMEREX_systemMessage.message, THEMEREX_systemMessage.header);
	}
	
	// Top menu height
	THEMEREX_top_height = jQuery('header .topWrap').height();
	jQuery('.topWrapFixed').css('backgroundColor', jQuery('.topWrap').css('backgroundColor'));
	THEMEREX_use_fixed_wrapper = jQuery('.topWrapFixed').parents('.fullScreenSlider').length == 0 || !jQuery('.topWrapFixed').parent().next().hasClass('sliderHomeBullets');
	
	// Close all dropdown elements
	jQuery(document).click(function (e) {
		"use strict";
		jQuery('.pageFocusBlock').slideUp();
		jQuery('.inputSubmitAnimation:not(.opened)').removeClass('sFocus rad4').addClass('radCircle', 100);
		jQuery('ul.shareDrop').slideUp().siblings('a.shareDrop').removeClass('selected');
	});

	// Calendar handlers - change months
	jQuery('.widget_calendar').on('click', '.prevMonth a, .nextMonth a', function(e) {
		"use strict";
		var calendar = jQuery(this).parents('.wp-calendar');
		var m = jQuery(this).data('month');
		var y = jQuery(this).data('year');
		var pt = jQuery(this).data('type');
		jQuery.post(THEMEREX_ajax_url, {
			action: 'calendar_change_month',
			nonce: THEMEREX_ajax_nonce,
			month: m,
			year: y,
			post_type: pt
		}).done(function(response) {
			var rez = JSON.parse(response);
			if (rez.error === '') {
				calendar.parent().fadeOut(200, function() {
					jQuery(this).empty().append(rez.data).fadeIn(200);
				});
			}
		});
		e.preventDefault();
		return false;
	});

	// Tabs for top widgets
	if (jQuery('.widgetTabs').length > 0) {

		// Collect widget's headers into tabs
		var THEMEREX_top_tabs = '';
		var THEMEREX_top_tabs_counter = 0;
		jQuery('.widgetTop .titleHide').each(function () {
			"use strict";
			THEMEREX_top_tabs_counter++;
			var id = jQuery(this).parents('.widgetTop').attr('id');
			var title = jQuery(this).text();
			if (title=='') title = '#'+THEMEREX_top_tabs_counter;
			THEMEREX_top_tabs += '<li><a href="#'+id+'"><span>'+title+'</span></a></li>';
		});
		jQuery('.widgetTabs .tabsButton ul').append(THEMEREX_top_tabs);
	
		// Break lists in top widgets on two parts
		jQuery('.widgetTop > ul:not(.tabs),.widgetTop > div > ul:not(.tabs)').each(function () {
			"use strict";
			var ul2 = jQuery(this).clone();
			var li = jQuery(this).find('>li');
			var middle = Math.ceil(li.length/2)-1;
			li.eq(middle).nextAll().remove();
			ul2.find('>li').eq(middle+1).prevAll().remove();
			jQuery(this).after(ul2);
		});
		
		// Init tabs
		jQuery('.widgetTabs').tabs({
			show: {
				effect: 'drop',
				direction: 'right',
				duration: 500
			},
			hide: {
				effect: 'drop',
				direction: 'left',
				duration: 500
			},
			activate: function (event, ui) {
				"use strict";
				initShortcodes(ui.newPanel);
			}
		});
	}

	// Add bookmarks
	if (jQuery('#tabsFavorite').length > 0) {
		jQuery('.addBookmark').click(function(e) {
			"use strict";
			var title = window.document.title.split('|')[0];
			var url = window.location.href;
			var list = jQuery.cookie('themerex_bookmarks');
			var exists = false;
			if (list) {
				list = JSON.parse(list);
				for (var i=0; i<list.length; i++) {
					if (list[i].url == url) {
						exists = true;
						break;
					}
				}
			} else
				list = new Array();
			if (!exists) {
				var THEMEREX_message_popup = themerex_message_dialog('<label for="bookmark_title">'+THEMEREX_MESSAGE_BOOKMARK_TITLE+'</label><br><input type="text" id="bookmark_title" name="bookmark_title" value="'+title+'">', THEMEREX_MESSAGE_BOOKMARK_ADD, null,
					function(btn, popup) {
						"use strict";
						if (btn != 1) return;
						title = THEMEREX_message_popup.find('#bookmark_title').val();
						list.push({title: title, url: url});
						jQuery('.listBookmarks').append('<li><a href="'+url+'">'+title+'</a><a href="#" class="delBookmark icon-cancel"></a></li>');
						jQuery.cookie('themerex_bookmarks', JSON.stringify(list), {expires: 365, path: '/'});
						if (THEMEREX_Swipers['bookmarks_scroll']!==undefined) THEMEREX_Swipers['bookmarks_scroll'].reInit();
						setTimeout(function () {themerex_message_success(THEMEREX_MESSAGE_BOOKMARK_ADDED, THEMEREX_MESSAGE_BOOKMARK_ADD);}, THEMEREX_MESSAGE_TIMEOUT/4);
					});
			} else
				themerex_message_warning(THEMEREX_MESSAGE_BOOKMARK_EXISTS, THEMEREX_MESSAGE_BOOKMARK_ADD);
			e.preventDefault();
			return false;
		});
		// Delete bookmarks
		jQuery('.listBookmarks').on('click', '.delBookmark', function(e) {
			"use strict";
			var idx = jQuery(this).parent().index();
			var list = jQuery.cookie('themerex_bookmarks');
			if (list) {
				list = JSON.parse(list);
				list.splice(idx, 1);
				jQuery.cookie('themerex_bookmarks', JSON.stringify(list), {expires: 365, path: '/'});
			}
			jQuery(this).parent().remove();
			e.preventDefault();
			return false;
		});
		// Sort bookmarks
		jQuery('.listBookmarks').sortable({
			items: "li",
			update: function(event, ui) {
				"use strict";
				var list = new Array();
				ui.item.parent().find('li').each(function() {
					var a = jQuery(this).find('a:not(.delBookmark)').eq(0);
					list.push({title: a.text(), url: a.attr('href')});
				});
				jQuery.cookie('themerex_bookmarks', JSON.stringify(list), {expires: 365, path: '/'});
			}
		}).disableSelection();
	}


	// Scroll to top
	jQuery('.upToScroll .scrollToTop, .toTheTopWrapper .scrollToTop').click(function(e) {
		"use strict";
		jQuery('html,body').animate({
			scrollTop: 0
		}, 'slow');
		e.preventDefault();
		return false;
	});


	// Decorate nested lists in widgets and sidemenu
	jQuery('.widgetWrap ul > li,.sidemenu_area ul > li,.panelmenu_area ul > li,.widgetTop ul > li').each(function () {
		if (jQuery(this).find('ul').length > 0) {
			jQuery(this).addClass('dropMenu');
		}
	});
	jQuery('.widgetWrap ul > li.dropMenu,.sidemenu_area ul > li.dropMenu,.panelmenu_area ul > li.dropMenu,.widgetTop ul > li.dropMenu').click(function (e) {
		"use strict";
		jQuery(this).toggleClass('dropOpen');
		jQuery(this).find('ul').first().slideToggle(200, function() {
			if (jQuery(this).parents('.sidemenu_area').length > 0)
				THEMEREX_Swipers['sidemenu_scroll'].reInit();
			else if (jQuery(this).parents('.panelmenu_area').length > 0)
				THEMEREX_Swipers['panelmenu_scroll'].reInit();
		});
		e.preventDefault();
		return false;
	});
	jQuery('.widgetWrap ul:not(.tabs) li > a,.sidemenu_area ul:not(.tabs) li > a,.panelmenu_area ul:not(.tabs) li > a,.widgetTop ul:not(.tabs) li > a').click(function (e) {
		"use strict";
		if (jQuery(this).attr('href')!='#') {
			e.stopImmediatePropagation();
			if (jQuery(this).parent().hasClass('menu-item-has-children') && jQuery(this).parents('.sidemenu_area,.panelmenu_area').length > 0) {
				jQuery(this).parent().trigger('click');
				e.preventDefault();
				return false;
			}
		}
	});


	// Archive widget decoration
	jQuery('.widget_archive a').each(function () {
		var val = jQuery(this).html().split(' ');
		if (val.length > 1) {
			val[val.length-1] = '<span>' + val[val.length-1] + '</span>';
			jQuery(this).html(val.join(' '))
		}
	});

	//video bg
	if (jQuery('.videoBackground').length > 0) {
		jQuery('.videoBackground').each(function () {
			var youtube = jQuery(this).data('youtube-code');
			if (youtube) {
				jQuery(this).tubular({videoId: youtube});
			//} else {
			//	resizeVideoBackground();
			}
		});
	}
	
	//isotope
	if (jQuery('.isotopeNOanim,.isotope').length > 0) {

		initIsotope();

		try {
			jQuery(window).smartresize(resizeIsotope);
		} catch (e) {
			jQuery(window).resize(resizeIsotope);
		}

		//isotope filter
		jQuery('.isotopeFiltr').on('click', 'li a', function (e) {
			"use strict";
			jQuery(this).parents('.isotopeFiltr').find('li').removeClass('active');
			jQuery(this).parent().addClass('active');
	
			var selector = jQuery(this).data('filter');
			jQuery(this).parents('.isotopeFiltr').siblings('.isotope').eq(0).isotope({
				filter: selector
			});
			
			if (selector == '*')
				jQuery('#viewmore_link').fadeIn();
			else
				jQuery('#viewmore_link').fadeOut();

			e.preventDefault();
			return false;
		});

	}

	// main Slider
	if (jQuery('.sliderBullets, .sliderHomeBullets').length > 0) {
		if (jQuery.rsCSS3Easing!=undefined && jQuery.rsCSS3Easing!=null) {
			jQuery.rsCSS3Easing.easeOutBack = 'cubic-bezier(0.175, 0.885, 0.320, 1.275)';
		}
		// Show Slider
		jQuery('.sliderHomeBullets').slideDown(200, function () {
			"use strict";
			REX_parallax();
			fullSlider();
			initShortcodes(jQuery(this));
			// Hack for the Royal Slider
			if (jQuery('body').hasClass('boxed')) { jQuery(this).trigger('resize'); }
		});
	}

	//fullScreen effect for Main Slider
	var homeSlider = jQuery('.sliderHomeBullets');
	if (homeSlider.length > 0 && homeSlider.hasClass('slider_engine_royal')) {
		var slideContent = homeSlider.find('.slideContent').eq(0);
		slideContent.addClass('sliderBGanima ' + slideContent.data('effect'));
		setTimeout(checkFullSlider, 500);
	}

	// Page Navigation
	jQuery('.pageFocusBlock').click(function (e) {
		"use strict";
		if (e.target.nodeName.toUpperCase()!='A') {
			e.preventDefault();
			return false;
		}
	});
	jQuery('.navInput').click(function (e) {
		"use strict";
		jQuery('.pageFocusBlock').slideDown(300, function () {
			initShortcodes(jQuery('.pageFocusBlock').eq(0));
		});
		e.preventDefault();
		return false;
	});


	// Responsive Show menu
	jQuery('.openResponsiveMenu').click(function(e){
		"use strict";
		jQuery('.menuTopWrap').slideToggle()
		e.preventDefault();
		return false;
	});


	// Main Menu
	initSfMenu('.menuTopWrap > ul#mainmenu, .usermenu_area ul.usermenu_list');
	// Enable click on root menu items (without submenu) in iOS
	if (isiOS()) {
		jQuery('#mainmenu li:not(.menu-item-has-children) > a').on('click touchend', function (e) {
			"use strict";
			if (jQuery(this).attr('href')!='#') {
				window.location.href = jQuery(this).attr('href');
			}
		});
		jQuery('#mainmenu li.menu-item-has-children > a').hover(
			function (e) {
				"use strict";
				if (jQuery('body').hasClass('responsive_menu')) {
					jQuery(this).trigger('click');
				}
			},
			function () {}
			);
	}
	// Submenu click handler
	jQuery('.menuTopWrap ul li a, .usermenu_area ul.usermenu_list li a').click(function(e) {
		"use strict";
		if ((THEMEREX_responsive_menu_click || isMobile()) && jQuery('body').hasClass('responsive_menu') && jQuery(this).parent().hasClass('menu-item-has-children')) {
			if (jQuery(this).siblings('ul:visible').length > 0)
				jQuery(this).siblings('ul').slideUp();
			else
				jQuery(this).siblings('ul').slideDown();
		}
		if (jQuery(this).attr('href')=='#' || (jQuery('body').hasClass('responsive_menu') && jQuery(this).parent().hasClass('menu-item-has-children'))) {
			e.preventDefault();
			return false;
		}
	});
	
	// Show table of contents for the current page
	if (THEMEREX_menu_toc!='no') {
		buildPageTOC();
	}
	// One page mode for menu links (scroll to anchor)
	jQuery('#toc, .menuTopWrap ul li, .usermenu_area ul.usermenu_list li').on('click', 'a', function(e) {
		"use strict";
		var href = jQuery(this).attr('href');
		var pos = href.indexOf('#');
		if (pos < 0 || href.length == 1) return;
		var loc = window.location.href;
		var pos2 = loc.indexOf('#');
		if (pos2 > 0) loc = loc.substring(0, pos2);
		var now = pos==0;
		if (!now) now = loc == href.substring(0, pos);
		if (now) {
			animateTo(href.substr(pos));
			setLocation(pos==0 ? loc + href : href);
			e.preventDefault();
			return false;
		}
	});

	// Open sidemenu
	jQuery('.sidemenu_wrap .sidemenu_button').click(function (e) {
		"use strict";
		jQuery('body').addClass('openMenuFix');
		if (jQuery('.sidemenu_overflow').length == 0) {
			jQuery('body').append('<div class="sidemenu_overflow"></div>')
		}
		jQuery('.sidemenu_overflow').fadeIn(400);
		e.preventDefault();
		return false;
	});

	// Close sidemenu and right panel
	jQuery(document).on('click', '.sidemenu_overflow, .sidemenu_close', function (e) {
		"use strict";
		jQuery('body').removeClass('openMenuFixRight openMenuFix');
		if (!isMobile()) jQuery('.swpRightPosButton').fadeIn(400);
		jQuery('.sidemenu_overflow').fadeOut(400);
	});

	// Demo sidemenu
	var showed = false;
	if (THEMEREX_demo_time > 0 && jQuery(window).width() > 800 && jQuery('.sidemenu_wrap .sidemenu_button').length > 0) {
		showed = jQuery.cookie('themerex_demo_sidemenu');
		if (!showed) {
			jQuery.cookie('themerex_demo_sidemenu', "1", {expires: 7, path: '/'});
			showed = 1;
			setTimeout(function () {
				jQuery('.sidemenu_wrap .sidemenu_button').trigger('click');
				setTimeout(function() { jQuery('.sidemenu_overflow').trigger('click'); }, THEMEREX_demo_time);
			}, THEMEREX_demo_time);
		}
	}
	
	// Open right menu
	jQuery('.openRightMenu,.swpRightPosButton').click(function (e) {
		"use strict";
		if (jQuery('body').hasClass('openMenuFixRight')) {
			jQuery('body').removeClass('openMenuFixRight');
			if (!isMobile()) jQuery('.swpRightPosButton').fadeIn(400);
			jQuery('.sidemenu_overflow').fadeOut(400);
		} else {
			jQuery('body').addClass('openMenuFixRight');
			if (jQuery('.sidemenu_overflow').length == 0) {
				jQuery('body').append('<div class="sidemenu_overflow"></div>')
			}
			if (!isMobile()) jQuery('.swpRightPosButton').fadeOut(400);
			jQuery('.sidemenu_overflow').fadeIn(400);
		}
		e.preventDefault();
		return false;
	});

	// Demo right panel
	if (!showed && THEMEREX_demo_time > 0 && jQuery(window).width() > 800 && jQuery('.openRightMenu,.swpRightPosButton').length > 0) {
		showed = jQuery.cookie('themerex_demo_rightpanel');
		if (!showed) {
			var btn = '';
			if (jQuery('.openRightMenu').length > 0)
				btn = '.openRightMenu';
			else if (jQuery('.swpRightPosButton').length > 0)
				btn = '.swpRightPosButton';
			if (btn) {
				jQuery.cookie('themerex_demo_rightpanel', "1", {expires: 7, path: '/'});
				setTimeout(function () {
					jQuery(btn).trigger('click');
					setTimeout(function() { jQuery('.sidemenu_overflow').trigger('click'); }, THEMEREX_demo_time);
				}, THEMEREX_demo_time);
			}	
		}
	}


	// search
	jQuery('.topWrap .search').click(function (e) {
		"use strict";
		if (jQuery(this).hasClass('searchOpen')) {
			if (e.target.nodeName.toUpperCase()!='INPUT' && e.target.nodeName.toUpperCase()!='A') {
				jQuery('.topWrap .search .searchForm').animate({'width': 'hide'}, 200);
				jQuery('.topWrap .ajaxSearchResults').fadeOut();
				jQuery('header').removeClass('topSearchShow');
				jQuery('.topWrap .search').removeClass('searchOpen');
				e.preventDefault();
				return false;
			}
		} else {
			jQuery(this).find('.searchForm').animate({'width': 'show'}, 200);//.toggle('slide',{direction: 'left'}, 200)
			jQuery('header').delay(200).addClass('topSearchShow')
			jQuery(this).delay(200).toggleClass('searchOpen');
			e.preventDefault();
			return false;
		}
	});
	jQuery('.topWrap .search').on('click', '.searchSubmit,.post_more', function (e) {
		"use strict";
		if (jQuery('.topWrap .searchField').val() != '')
			jQuery('.topWrap .searchForm form').get(0).submit();
		e.preventDefault();
		return false;
	});
	jQuery('.search-form').on('click', '.search-button a', function (e) {
		"use strict";
		if (jQuery(this).parents('.search-form').find('input[name="s"]').val() != '')
			jQuery(this).parents('.search-form').get(0).submit();
		e.preventDefault();
		return false;
	});
	// AJAX search
	if (THEMEREX_useAJAXSearch) {
		var THEMEREX_ajax_timer = null;
		jQuery('.topWrap .searchField').keyup(function (e) {
			"use strict";
			var s = jQuery(this).val();
			if (THEMEREX_ajax_timer) {
				clearTimeout(THEMEREX_ajax_timer);
				THEMEREX_ajax_timer = null;
			}
			if (s.length >= THEMEREX_AJAXSearch_min_length) {
				THEMEREX_ajax_timer = setTimeout(function () {
					jQuery.post(THEMEREX_ajax_url, {
						action: 'ajax_search',
						nonce: THEMEREX_ajax_nonce,
						text: s
					}).done(function(response) {
						clearTimeout(THEMEREX_ajax_timer);
						THEMEREX_ajax_timer = null;
						var rez = JSON.parse(response);
						if (rez.error === '') {
							jQuery('.topWrap .ajaxSearchResults').empty().append(rez.data).fadeIn();
						} else {
							themerex_message_warning(THEMEREX_MESSAGE_SEARCH_ERROR);
						}
					});
				}, THEMEREX_AJAXSearch_delay);
			}
		});
	}

	// search 404
	jQuery('.inputSubmitAnimation').click(function (e) {
		"use strict";
		e.preventDefault();
		return false;
	});
	jQuery('.inputSubmitAnimation a').click(function (e) {
		"use strict";
		var form = jQuery(this).siblings('form');
		var parent = jQuery(this).parents('.inputSubmitAnimation');
		if (parent.hasClass('sFocus')) {
			if (form.length>0 && form.find('input').val()!='') {
				if (jQuery(this).hasClass('sc_emailer_button')) {
					var group = jQuery(this).data('group');
					var email = form.find('input').val();
					var regexp = new RegExp(THEMEREX_EMAIL_MASK);
					if (!regexp.test(email)) {
						form.find('input').get(0).focus();
						themerex_message_warning(THEMEREX_EMAIL_NOT_VALID);
					} else {
						jQuery.post(THEMEREX_ajax_url, {
							action: 'emailer_submit',
							nonce: THEMEREX_ajax_nonce,
							group: group,
							email: email
						}).done(function(response) {
							var rez = JSON.parse(response);
							if (rez.error === '') {
								themerex_message_info(THEMEREX_MESSAGE_EMAIL_CONFIRM.replace('%s', email));
								form.find('input').val('');
							} else {
								themerex_message_warning(rez.error);
							}
						});
					}
				} else
					form.get(0).submit();
			} else
				jQuery(document).trigger('click');
		} else {
			parent.addClass('sFocus rad4').removeClass('radCircle');
		}
		e.preventDefault();
		return false;
	});

    //SC Emailer Focus
    jQuery('.sc_emailer.inputSubmitAnimation .sInput').on('focusin', function(){
        "use strict";
        jQuery(this).parent().parent().addClass('focused');
        console.log('focused');
    });
    jQuery('.sc_emailer.inputSubmitAnimation .sInput').on('focusout', function(){
        "use strict";
        jQuery(this).parent().parent().removeClass('focused');
        console.log('un focused');
    });


	//Portfolio item Description
	if (isMobile()) {	// if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		jQuery('.toggleButton').show();
		jQuery('.itemDescriptionWrap,.toggleButton').click(function (e) {
			"use strict";
			jQuery(this).toggleClass('descriptionShow');
			jQuery(this).find('.toggleDescription').slideToggle();
			e.preventDefault();
			return false;
		});
	} else {
		jQuery('.itemDescriptionWrap').hover(function () {
			"use strict";
			jQuery(this).toggleClass('descriptionShow');
			jQuery(this).find('.toggleDescription').slideToggle();
		});
	}

	// Save placeholder for input fields
	jQuery('.formList input[type="text"], .formList input[type="password"]')
		.focus(function () {
			"use strict";
			jQuery(this).attr('data-placeholder', jQuery(this).attr('placeholder')).attr('placeholder', '')
			jQuery(this).parent('li').addClass('iconFocus');
		})
		.blur(function () {
			"use strict";
			jQuery(this).attr('placeholder', jQuery(this).attr('data-placeholder'))
			jQuery(this).parent('li').removeClass('iconFocus');
		});

	// Hide empty pagination
	if (jQuery('#nav_pages > ul > li').length < 3) {
		jQuery('#nav_pages').remove();
	} else {
		jQuery('.theme_paginaton a').addClass('theme_button');
	}

	// View More button
	jQuery('#viewmore_link').click(function(e) {
		"use strict";
		if (!THEMEREX_VIEWMORE_BUSY) {
			jQuery(this).addClass('loading');
			THEMEREX_VIEWMORE_BUSY = true;
			jQuery.post(THEMEREX_ajax_url, {
				action: 'view_more_posts',
				nonce: THEMEREX_ajax_nonce,
				page: THEMEREX_VIEWMORE_PAGE+1,
				data: THEMEREX_VIEWMORE_DATA,
				vars: THEMEREX_VIEWMORE_VARS
			}).done(function(response) {
				"use strict";
				var rez = JSON.parse(response);
				jQuery('#viewmore_link').removeClass('loading');
				THEMEREX_VIEWMORE_BUSY = false;
				if (rez.error === '') {
					var posts_container = jQuery('.content').eq(0);
					if (posts_container.find('aside#tabBlog').length > 0)		posts_container = posts_container.find('aside#tabBlog').eq(0);
					if (posts_container.find('section.masonry').length > 0)		posts_container = posts_container.find('section.masonry').eq(0);
					if (posts_container.find('section.portfolio').length > 0)	posts_container = posts_container.find('section.portfolio').eq(0);
					if (posts_container.hasClass('masonry') || posts_container.hasClass('portfolio')) {
						posts_container.data('last-width', 0).append(rez.data);
						THEMEREX_isotopeInitCounter = 0;
						initAppendedIsotope(posts_container, rez.filters);
					} else
						jQuery('#viewmore').before(rez.data);

					timelineResponsive();
					timelineScrollFix();
					itemPageFull();
					initPostFormats();
					initShortcodes(posts_container);
					scrollAction();

					THEMEREX_VIEWMORE_PAGE++;
					if (rez.no_more_data==1) {
						jQuery('#viewmore').hide();
					}
					if (jQuery('#nav_pages ul li').length >= THEMEREX_VIEWMORE_PAGE) {
						jQuery('#nav_pages ul li').eq(THEMEREX_VIEWMORE_PAGE).toggleClass('pager_current', true);
					}
				}
			});
		}
		e.preventDefault();
		return false;
	});

	// Infinite pagination
	if (jQuery('#viewmore.pagination_infinite').length > 0) {
		jQuery(window).scroll(infiniteScroll);
	}


	// WooCommerce handlers
	jQuery('.woocommerce .mode_buttons a,.woocommerce-page .mode_buttons a').click(function(e) {
		"use strict";
		var mode = jQuery(this).hasClass('woocommerce_thumbs') ? 'thumbs' : 'list';
		jQuery.cookie('themerex_shop_mode', mode, {expires: 365, path: '/'});
		jQuery(this).siblings('input').val(mode).parents('form').get(0).submit();
		e.preventDefault();
		return false;
	});
	// Added to cart
	jQuery('body').bind('added_to_cart', function() {
		// Update amount on the cart button
		var total = jQuery('.usermenu_cart .total .amount').text()
		if (total != undefined) {
			jQuery('.cart_button .cart_total').text(total);
		}
	});
		
	// Sound effects init
	if (THEMEREX_sound_enable) {
		// Load state
		var snd = jQuery.cookie('themerex_sounds');
		if (snd != undefined) {
			try {
				snd = JSON.parse(snd);
			} catch (e) {}
			if (typeof snd == 'object') {
				THEMEREX_sound_state = snd;
			}
		}
		if (!THEMEREX_sound_state.all)			jQuery('.usermenu_sound > a').removeClass('icon-volume').addClass('icon-volume-off-1');
		if (!THEMEREX_sound_state.mainmenu)		jQuery('.usermenu_sound > ul > li > a.sound_mainmenu').removeClass('icon-check').addClass('icon-dot');
		if (!THEMEREX_sound_state.othermenu)	jQuery('.usermenu_sound > ul > li > a.sound_othermenu').removeClass('icon-check').addClass('icon-dot');
		if (!THEMEREX_sound_state.buttons)		jQuery('.usermenu_sound > ul > li > a.sound_buttons').removeClass('icon-check').addClass('icon-dot');
		if (!THEMEREX_sound_state.links)		jQuery('.usermenu_sound > ul > li > a.sound_links').removeClass('icon-check').addClass('icon-dot');
		// Init sounds
		var THEMEREX_sounds = [];
		soundManager.setup({
			// location: path to SWF files, as needed (SWF file name is appended later.)
			url: THEMEREX_sound_folder,
			
			// optional: version of SM2 flash audio API to use (8 or 9; default is 8 if omitted, OK for most use cases.)
			// flashVersion: 9,
			
			// optional: use Flash for MP3/MP4/AAC formats, even if HTML5 support present. useful if HTML5 is quirky.
			//preferFlash: true,
			
			// use soundmanager2-nodebug-jsmin.js, or disable debug mode (enabled by default) after development/testing
			debugMode: false,
			
			// good to go: the onready() callback
			onready: function() {
				"use strict";
				// SM2 has started - now you can create and play sounds!
				if (THEMEREX_sound_mainmenu) {
					THEMEREX_sounds['mainmenu'] = soundManager.createSound({
						id: 'sound_mainmenu', // optional: an id will be generated if not provided.
						url: THEMEREX_sound_mainmenu
						// onload: function() { console.log('sound loaded!', this); }
						// other options here..
					});
				}
				if (THEMEREX_sound_othermenu) {
					THEMEREX_sounds['othermenu'] = soundManager.createSound({
						id: 'sound_othermenu', // optional: an id will be generated if not provided.
						url: THEMEREX_sound_othermenu
					});
				}
				if (THEMEREX_sound_buttons) {
					THEMEREX_sounds['buttons'] = soundManager.createSound({
						id: 'sound_buttons', // optional: an id will be generated if not provided.
						url: THEMEREX_sound_buttons
					});
				}
				if (THEMEREX_sound_links) {
					THEMEREX_sounds['links'] = soundManager.createSound({
						id: 'sound_links', // optional: an id will be generated if not provided.
						url: THEMEREX_sound_links
					});
				}
			},
			
			// optional: ontimeout() callback for handling start-up failure, flash required but blocked, etc.
			ontimeout: function() {
				"use strict";
				// Hrmm, SM2 could not start. Missing SWF? Flash blocked? Show an error, etc.?
				// See the flashblock demo when you want to start getting fancy.
			}
		});
		var sounded_objects = 'a,button,.sc_accordion_title,.sc_toggles_title,.tabsButton > ul > li,.topWrap .search,.topWrap .openRightMenu';
		var last_time = 0;
		jQuery(sounded_objects).hover(
			function () {
				"use strict";
				if (!THEMEREX_sound_state.all) return;
				var dt = new Date();
				var tm = dt.getTime();
				if (tm - last_time < 50) return;
				last_time = tm;
				if (jQuery(this).parents('#mainmenu,.tabsButton').length > 0) {
					if (THEMEREX_sound_state.mainmenu && THEMEREX_sound_mainmenu && typeof THEMEREX_sounds['mainmenu'] != 'undefined')
						THEMEREX_sounds['mainmenu'].play();
				} else if (jQuery(this).parents('#panelmenu,#sidemenu,.usermenu_area').length > 0) {
					if (THEMEREX_sound_state.othermenu && THEMEREX_sound_othermenu && typeof THEMEREX_sounds['othermenu'] != 'undefined')
						THEMEREX_sounds['othermenu'].play();
				} else if (jQuery(this).parents('.squareButton,.roundButton,.flex-direction-nav,.sc_accordion,.sc_toggles,.tab_names,.topWrap,.tabsMenuHead,#co_bg_pattern_list,#co_bg_images_list,.addBookmarkArea,.socPage,.page-numbers,.upToScroll').length > 0 || jQuery(this).hasClass('button')) {
					if (THEMEREX_sound_state.buttons && THEMEREX_sound_buttons && typeof THEMEREX_sounds['buttons'] != 'undefined')
						THEMEREX_sounds['buttons'].play();
				} else {
					if (THEMEREX_sound_state.links && THEMEREX_sound_links && typeof THEMEREX_sounds['links'] != 'undefined')
						THEMEREX_sounds['links'].play();
				}
			},
			function () {}
		);
		// Main sound on/off
		jQuery('.usermenu_sound > a').click(function(e) {
			"use strict";
			THEMEREX_sound_state.all = 1-THEMEREX_sound_state.all;
			jQuery.cookie('themerex_sounds', JSON.stringify(THEMEREX_sound_state), {expires: 365, path: '/'});
			jQuery(this).removeClass(THEMEREX_sound_state.all ? 'icon-volume-off-1' : 'icon-volume').addClass(THEMEREX_sound_state.all ? 'icon-volume' : 'icon-volume-off-1');
			e.preventDefault();
			return false;
		});
		// Sound parts on/off
		jQuery('.usermenu_sound > ul > li > a').click(function(e) {
			"use strict";
			if (jQuery(this).hasClass('sound_mainmenu')) {
				THEMEREX_sound_state.mainmenu = 1 - THEMEREX_sound_state.mainmenu;
				jQuery(this).removeClass(THEMEREX_sound_state.mainmenu ? 'icon-dot' : 'icon-check').addClass(THEMEREX_sound_state.mainmenu ? 'icon-check' : 'icon-dot');
			} else if (jQuery(this).hasClass('sound_othermenu')) {
				THEMEREX_sound_state.othermenu = 1 - THEMEREX_sound_state.othermenu;
				jQuery(this).removeClass(THEMEREX_sound_state.othermenu ? 'icon-dot' : 'icon-check').addClass(THEMEREX_sound_state.othermenu ? 'icon-check' : 'icon-dot');
			} else if (jQuery(this).hasClass('sound_buttons')) {
				THEMEREX_sound_state.buttons = 1 - THEMEREX_sound_state.buttons;
				jQuery(this).removeClass(THEMEREX_sound_state.buttons ? 'icon-dot' : 'icon-check').addClass(THEMEREX_sound_state.buttons ? 'icon-check' : 'icon-dot');
			} else if (jQuery(this).hasClass('sound_links')) {
				THEMEREX_sound_state.links = 1 - THEMEREX_sound_state.links;
				jQuery(this).removeClass(THEMEREX_sound_state.links ? 'icon-dot' : 'icon-check').addClass(THEMEREX_sound_state.links ? 'icon-check' : 'icon-dot');
			}
			jQuery.cookie('themerex_sounds', JSON.stringify(THEMEREX_sound_state), {expires: 365, path: '/'});
			e.preventDefault();
			return false;
		});		
	}

	initPostFormats();
	initShortcodes(jQuery('body').eq(0));
	
} //end ready




// Init Superfish menu
function initSfMenu(selector) {
	jQuery(selector).show().each(function () {
		if (isResponsiveNeed() && jQuery(this).attr('id')=='mainmenu' && (THEMEREX_responsive_menu_click || isMobile())) return;
		jQuery(this).addClass('inited').superfish({
			delay: 500,
			animation: {
				opacity: 'show',
				height: 'show'
			},
			speed: 'fast',
			autoArrows: false,
			dropShadows: false,
			onBeforeShow: function(ul) {
				if (jQuery(this).parents("ul").length > 1){
					var w = jQuery(window).width();  
					var par_offset = jQuery(this).parents("ul").offset().left;
					var par_width  = jQuery(this).parents("ul").outerWidth();
					var ul_width   = jQuery(this).outerWidth();
					if (par_offset+par_width+ul_width > w-20 && par_offset-ul_width > 0)
						jQuery(this).addClass('submenu_left');
					else
						jQuery(this).removeClass('submenu_left');
				}
			}
		});
	});
}


// Main Menu responsive
function mainMenuResponsive() {
	if (THEMEREX_menuResponsive > 0) {
		if (isResponsiveNeed()) {
			if (!jQuery('body').hasClass('responsive_menu')) {
				jQuery('body').addClass('responsive_menu');
				jQuery('.topMenuStyleFon').removeClass('topMenuStyleFon').addClass('topMenuStyleFon2 topMenuStyleLine');
				jQuery('header').removeClass('fixedTopMenu').addClass('noFixMenu');
				if ((THEMEREX_responsive_menu_click || isMobile()) && jQuery('.menuTopWrap > ul#mainmenu').hasClass('inited')) {
					jQuery('.menuTopWrap > ul#mainmenu').removeClass('inited').superfish('destroy');
				}
			}
		} else {
			if (jQuery('body').hasClass('responsive_menu')) {
				jQuery('body').removeClass('responsive_menu');
				jQuery('.topMenuStyleFon2').removeClass('topMenuStyleFon2 topMenuStyleLine').addClass('topMenuStyleFon');
				jQuery('.menuTopWrap').show();
				if (THEMEREX_responsive_menu_click || isMobile()) {
					initSfMenu('.menuTopWrap > ul#mainmenu');
				}
				calcMenuColumnsWidth();
			}
		}
	}
}


// Make all columns (in custom menu) equal height
function calcMenuColumnsWidth() {
	"use strict";
	jQuery('#mainmenu li.custom_view_item ul.menu-panel ul.columns').each(function() {
		"use strict";
		if (jQuery('body').hasClass('responsive_menu')) return;
		jQuery(this).parents('.menu-panel').css({display:'block', visibility: 'hidden'});
		//var h = jQuery(this).height();//-parseInt(jQuery(this).css('paddingTop'))-parseInt(jQuery(this).css('paddingBottom'));
		var h = 0, w = 0;
		jQuery(this).find('>li').css('height', 'auto').each(function () {
			var li = jQuery(this);
			var mt = parseInt(li.css('marginTop')), mb = parseInt(li.css('marginBottom')), mh = li.height() + (isNaN(mt) ? 0 : mt) + (isNaN(mb) ? 0 : mb);
			if (h < mh) h = mh;
			var bl = parseInt(li.css('borderLeft')), pl = parseInt(li.css('paddingLeft')), br = parseInt(li.css('borderRight')), pr = parseInt(li.css('paddingRight'));
			w += li.width() + (isNaN(bl) ? 0: bl) + (isNaN(pl) ? 0 : pl) + (isNaN(pr) ? 0 : pr) + (isNaN(br) ? 0 : br);
		});
		jQuery(this).parents('.menu-panel').css({display:'none', visibility: 'visible'});
		if (w > jQuery('#mainmenu').width()) jQuery(this).width(w+8);
		jQuery(this).find('>li').height(h);
	});
}

// Check if responsive menu need
function isResponsiveNeed() {
	"use strict";
	var rez = false;
	if (THEMEREX_menuResponsive > 0) {
		var w = window.innerWidth;
		if (w == undefined) {
			w = jQuery(window).width()+(jQuery(window).height() < jQuery(document).height() || jQuery(window).scrollTop() > 0 ? 16 : 0);
		}
		rez = THEMEREX_menuResponsive > w;
	}
	return rez;
}


// Infinite Scroll
function infiniteScroll() {
	"use strict";
	var v = jQuery('#viewmore.pagination_infinite').offset();
	if (jQuery(this).scrollTop() + jQuery(this).height() + 100 >= v.top && !THEMEREX_VIEWMORE_BUSY) {
		jQuery('#viewmore_link').eq(0).trigger('click');
	}
}

//itemPageFull
function itemPageFull() {
	"use strict";
	var bodyHeight = jQuery(window).height();
	var st = jQuery(window).scrollTop();
	if (st > jQuery('.noFixMenu .topWrap').height()+jQuery('.topTabsWrap').height()) st = 0;
	var thumbHeight = Math.min(jQuery('.itemPageFull').width()/16*9, bodyHeight - jQuery('#wpadminbar').height() - jQuery('.noFixMenu .topWrap').height() - jQuery('.topTabsWrap').height() + st);
	jQuery('.itemPageFull').height(thumbHeight);
	var padd1 = parseInt(jQuery('.sidemenu_wrap').css('paddingTop'));
	if (isNaN(padd1)) padd1 = parseInt(jQuery('.swpRightPos').css('paddingTop'));
	if (isNaN(padd1)) padd1 = 0;
	var padd2 = parseInt(jQuery('.swpRightPos .sc_tabs .tabsMenuBody').css('paddingTop'))*2;
	if (isNaN(padd2)) padd2 = 0;
	var tabs_h = jQuery('.swpRightPos .sc_tabs .tabsMenuHead').height();
	if (isNaN(tabs_h)) tabs_h = 0;
	var butt_h = jQuery('.swpRightPos .sc_tabs .tabsMenuBody .addBookmarkArea').height();
	if (isNaN(butt_h)) butt_h = 0;
	jQuery('#sidemenu_scroll').height(bodyHeight - padd1);
	jQuery('.swpRightPos .sc_tabs .tabsMenuBody').height(bodyHeight -  - padd1 - padd2 - tabs_h);
	jQuery('#custom_options_scroll').height(bodyHeight - padd1 - padd2 - tabs_h);
	jQuery('#sidebar_panel_scroll').height(bodyHeight - padd1 - padd2 - tabs_h);
	jQuery('#panelmenu_scroll').height(bodyHeight - padd1 - padd2 - tabs_h);
	jQuery('#bookmarks_scroll').height(bodyHeight - padd1 - padd2 - tabs_h - butt_h);
}

//scroll Action
function scrollAction() {
	"use strict";

	var buttonScrollTop = jQuery('.upToScroll');
	var scrollPositions = jQuery(window).scrollTop();
	var topMenuHeight   = jQuery('header').height();
	var adminbarHeight  = jQuery('#wpadminbar').height();

	if (scrollPositions > topMenuHeight) {
		buttonScrollTop.addClass('buttonShow');
	} else {
		buttonScrollTop.removeClass('buttonShow');
	}
	
	if (!jQuery('body').hasClass('responsive_menu') && THEMEREX_menuFixed) {
		var slider_height = 0;
		if (jQuery('.top_panel_below .sliderHomeBullets').length > 0) {
			slider_height = jQuery('.top_panel_below .sliderHomeBullets').height();
			if (slider_height < 10) {
				slider_height = jQuery('.sliderHomeBullets').parents('.fullScreenSlider').length > 0 ? jQuery(window).height() : THEMEREX_slider_height;
			}
		}
		var topFixedHeight = Math.max(0, jQuery('.fixedTopMenu .topWrap').height());
		if (scrollPositions <= THEMEREX_top_height - topFixedHeight - 20 + slider_height) {
			if (jQuery('header').hasClass('fixedTopMenu')) {
				jQuery('header').removeClass('fixedTopMenu').addClass('noFixMenu');
				if (THEMEREX_use_fixed_wrapper) jQuery('.topWrapFixed').hide();
			}
		} else if (scrollPositions > THEMEREX_top_height + slider_height) {
			if (!jQuery('header').hasClass('fixedTopMenu')) {
				if (THEMEREX_use_fixed_wrapper) jQuery('.topWrapFixed').height(THEMEREX_top_height).show();
				jQuery('header').addClass('fixedTopMenu').removeClass('noFixMenu');
			}
		}
	}
	
	// TOC current items
	jQuery('#toc .toc_item').each(function () {
		"use strict";
		var id = jQuery(this).find('a').attr('href');
		var pos = id.indexOf('#');
		if (pos < 0 || id.length == 1) return;
		var loc = window.location.href;
		var pos2 = loc.indexOf('#');
		if (pos2 > 0) loc = loc.substring(0, pos2);
		var now = pos==0;
		if (!now) now = loc == href.substring(0, pos);
		if (!now) return;
		var off = jQuery(id).offset().top;
		var id_next  = jQuery(this).next().find('a').attr('href');
		var off_next = id_next ? jQuery(id_next).offset().top : 1000000;
		if (off < scrollPositions + jQuery(window).height()*0.8 && scrollPositions + topMenuHeight < off_next)
			jQuery(this).addClass('current');
		else
			jQuery(this).removeClass('current');
	});
}


// Build page TOC from the tag's id
function buildPageTOC() {
	"use strict";
	var toc = '', toc_count = 0;
	jQuery('[id^="toc_"],.sc_anchor').each(function(idx) {
		"use strict";
		var obj = jQuery(this);
		var id = obj.attr('id');
		var url = obj.data('url');
		var icon = obj.data('icon');
		if (!icon) icon = 'icon-record';
		var title = obj.attr('title');
		var description = obj.data('description');
		var separator = obj.data('separator');
		toc_count++;
		toc += '<div class="toc_item'+(separator=='yes' ? ' toc_separator' : '')+'">'
			+(description ? '<div class="toc_description">'+description+'</div>' : '')
			+'<a href="'+(url ? url : '#'+id)+'" class="toc_icon'+(title ? ' with_title' : '')+' '+icon+'">'+(title ? '<span class="toc_title">'+title+'</span>' : '')+'</a>'
			+'</div>';
	});
	if (toc_count > (THEMEREX_menu_toc_home ? 1 : 0) + (THEMEREX_menu_toc_top ? 1 : 0)) {
		if (jQuery('#toc').length > 0)
			jQuery('#toc .toc_inner').html(toc);
		else
			jQuery('body').append('<div id="toc" class="toc_'+THEMEREX_menu_toc+'"><div class="toc_inner">'+toc+'</div></div>');
	}
}

// Fullscreen slider
function fullSlider() {
	"use strict";
	var fullSlider = jQuery('.fullScreenSlider');
	if (fullSlider.length > 0) {
		var h = jQuery(window).height() - jQuery('#wpadminbar').height() - (jQuery('.top_panel_above .fullScreenSlider header').css('position')=='static' ? jQuery('.topWrap').height() : 0);
		// Slider Container
		fullSlider.find('.sliderHomeBullets').css('height', h);
		// Royal slider
		fullSlider.find('.sliderHomeBullets.slider_engine_royal > div,.sliderHomeBullets.slider_engine_royal .rsOverflow,.sliderHomeBullets.slider_engine_royal .rsContent,.sliderHomeBullets .slideContent,.sliderHomeBullets .sc_slider,.sliderHomeBullets .sc_slider .slides,.sliderHomeBullets .sc_slider .slides li').css('height', h);
		// Revolution slider
		//fullSlider.find('.sliderHomeBullets.slider_engine_revo .rev_slider_wrapper,.sliderHomeBullets.slider_engine_revo .rev_slider').css({'height': h+'px', 'maxHeight': h+'px'});
		//fullSlider.find('.sliderHomeBullets.slider_engine_revo .rev_slider > ul').css({'maxHeight': h+'px'});
		//fullSlider.find('.sliderHomeBullets.slider_engine_revo .rev_slider .defaultimg').css({'height': h+'px', 'maxWidth': 'none'});
	}  else {
       // setTimeout(SetNewH,300);
    }

    function SetNewH() {
        var slider = jQuery('.sliderHomeBullets.slider_engine_revo');
        if (slider.length > 0) {
            var h = slider.find('.rev_slider').height();
            if (slider.height() != h) slider.css('height', h);
        }
    }
}

// Animation effect on fullscreen slider (only for Royal slider)
function checkFullSlider() {
	"use strict";
	var fullSlider = jQuery('.fullScreenSlider');
	if (fullSlider.length > 0) {
		var slider = fullSlider.find('.royalSlider').data('royalSlider');
		if (slider == undefined || slider == '') {
			setTimeout(checkFullSlider, 500);
		} else {
			slider.ev.on('rsBeforeAnimStart', function (event) {
				"use strict";
				REX_parallax();
				var slideIndex = this.currSlideId;
				var slideContent = jQuery(".slider_engine_royal").find('.slideContent');
				slideContent.each(function () {
					jQuery(this).removeClass('sliderBGanima ' + jQuery(this).data('effect'));
				});
				slideContent.eq(slideIndex).addClass('sliderBGanima ' + slideContent.eq(slideIndex).data('effect'));
			});
		}
	}
}

// Resize sliders
function resizeSliders() {
	if (jQuery('.sc_slider_flex,.sc_slider_chop,.sc_slider_swiper').length > 0) {
		jQuery('.sc_slider_flex,.sc_slider_chop,.sc_slider_swiper').each(function () {
			if (jQuery(this).parents('.isotope, .isotopeNOanim').length == 0) calcSliderDimensions(jQuery(this));
		});
	}
}

//Time Line
function timelineResponsive() {
	"use strict";
	var tl = jQuery('#timeline_slider:not(.fixed)').eq(0);
	if (tl.length > 0) {
		if (jQuery(window).width() <= 1023) {
			tl.addClass('fixed');
		} else {
			var bodyHeight = jQuery(window).height();
			var tlHeight = jQuery(window).height() - tl.find('h2').height() - 150;
			tl.find('.sc_blogger').css('height', tlHeight).find('.sc_scroll').css('height', tlHeight);
		}
	}
}


//time line Scroll
function timelineScrollFix() {
	"use strict";
	var tl = jQuery('#timeline_slider:not(.fixed)').eq(0);
	if (tl.length > 0) {
		var scrollWind = jQuery(window).scrollTop();
		var headerHeight = jQuery('header').height() + jQuery('.topTabsWrap').height() - 20;
		var footerHeight = jQuery('.footerContentWrap').height();
		var footerVisible = jQuery(document).height() - footerHeight <= scrollWind + jQuery(window).height();

		if (jQuery(window).scrollTop() <= headerHeight) {
			if (parseFloat(tl.css('marginTop')) > 0) {
				tl.animate({
					marginTop: 0
				}, {
					queue: false,
					duration: 350
				});
			}
		} else {
			if (headerHeight <= scrollWind - 10 && !footerVisible) {
				tl.animate({
					marginTop: (scrollWind - headerHeight) + "px"
				}, {
					queue: false,
					duration: 350
				});
			}
		}
	}
}

// Init isotope
var THEMEREX_isotopeInitCounter = 0;
function initIsotope() {
	if (jQuery('.isotopeNOanim,.isotope').length > 0) {

		jQuery('.isotopeNOanim,.isotope').each(function () {
			"use strict";
			if (!isotopeImagesComplete(jQuery(this)) && THEMEREX_isotopeInitCounter++ < 30) {
				setTimeout(initIsotope, 200);
				return;
			}
			jQuery(this).addClass('inited').find('.isotopeElement').animate({opacity: 1}, 200, function () { jQuery(this).addClass('isotopeElementShow'); });
			var w = calcSizeIsotope(jQuery(this));
			jQuery(this).isotope({
				resizable: jQuery(this).parents('.fullscreen,.sc_gap').length > 0 && !jQuery(this).hasClass('folio1col'),
				masonry: {
					columnWidth: w	//Math.floor(jQuery('.isotope,.isotopeNOanim').width() / columns)
				},
				itemSelector: '.isotopeElement',
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
			// Init shortcodes in isotope
			initShortcodes(jQuery(this));
		});		
	}
}

function initAppendedIsotope(posts_container, filters) {
	"use strict";
	if (!isotopeImagesComplete(posts_container) && THEMEREX_isotopeInitCounter++ < 30) {
		setTimeout(function() { initAppendedIsotope(posts_container, filters); }, 200);
		return;
	}
	calcSizeIsotope(posts_container);
	var flt = posts_container.siblings('.isotopeFiltr');
	var elems = posts_container.find('.isotopeElement:not(.isotopeElementShow)').animate({opacity: 1}, 200, function () { jQuery(this).addClass('isotopeElementShow'); });
	posts_container.isotope('appended', elems);
	for (var i in filters) {
		if (flt.find('a[data-filter=".flt_'+i+'"]').length == 0) {
			flt.find('ul').append('<li class="squareButton"><a href="#" data-filter=".flt_'+i+'">'+filters[i]+'</a></li>');
		}
	}
}

function isotopeImagesComplete(cont) {
	var complete = true;
	cont.find('img').each(function() {
		if (!complete) return;
		if (!jQuery(this).get(0).complete) complete = false;
	});
	return complete;
}

function calcSizeIsotope(cont) {
	"use strict";
	var columns = Math.max(1, Number(cont.data('columns')));
	var element = cont.find('.isotopeElement:not(.isotope-item)');
	var elementWidth=0, elementWidthNew=0, elementHeight=0, elementHeightNew=0;
	if (columns > 1) {
		if (cont.data('last-width') == cont.width()) return elementWidthNew;
		var changeHeight = cont.hasClass('portfolio');
		var m1 = parseInt(cont.css('marginRight'));
		if (isNaN(m1)) m1 = 0;
		var m2 = parseInt(element.find('.isotopePadding').css('marginRight'));
		if (isNaN(m2)) m2 = 0;
		var lastWidth = cont.width() + (changeHeight ? 0 : m1+m2);
		cont.data('last-width', lastWidth);
		elementWidth = changeHeight ? element.width() : Math.max(240, Math.floor(lastWidth/columns - m2));
		cont.data('element-width', elementWidth);
		elementWidthNew = Math.floor(lastWidth / columns);
		var dir = elementWidthNew > elementWidth ? 1 : -1;
		while (dir*(elementWidthNew-elementWidth)/elementWidth > THEMEREX_isotope_resize_delta) {
			columns += dir;
			if (columns==0) break;
			//cont.data('columns', columns);
			elementWidthNew = Math.floor(lastWidth / columns);
		}
		element.css({
			width: elementWidthNew
		});
		if (changeHeight) {
			elementHeight = element.height();
			cont.data('element-height', elementHeight);
			elementHeightNew = Math.floor(elementWidthNew/elementWidth*elementHeight);
			element.css({
				height: elementHeightNew
			});
		}
	}
	//element.fadeIn();
	return elementWidthNew;
}

// Resize new Isotope elements
function resizeIsotope() {
	jQuery('.isotope, .isotopeNOanim').each(function() {
		"use strict";
		var cont = jQuery(this);
		var columns = Math.max(1, Number(cont.data('columns')));
		if (columns == 1 || cont.data('last-width') == cont.width()) return;
		var changeHeight = cont.hasClass('portfolio');
		var element = cont.find('.isotopeElement');
		var m1 = parseInt(cont.css('marginRight'));
		if (isNaN(m1)) m1 = 0;
		var m2 = parseInt(element.find('.isotopePadding').css('marginRight'));
		if (isNaN(m2)) m2 = 0;
		var lastWidth = cont.width() + (changeHeight ? 0 : m1+m2);
		cont.data('last-width', lastWidth);
		var elementWidth = parseFloat(cont.data('element-width'));
		var elementWidthNew = Math.floor(lastWidth / columns);
		var dir = elementWidthNew > elementWidth ? 1 : -1;
		while (dir*(elementWidthNew-elementWidth)/elementWidth > THEMEREX_isotope_resize_delta) {
			columns += dir;
			if (columns == 0) break;
			//jQuery(this).data('columns', columns);
			elementWidthNew = Math.floor(lastWidth / columns);
		}
		element.css({
			width: elementWidthNew
		});
		if (changeHeight) {
			var elementHeight = parseFloat(cont.data('element-height'));
			var elementHeightNew = Math.floor(elementWidthNew/elementWidth*elementHeight);
			element.css({
				height: elementHeightNew
			});
		}
		jQuery(this).isotope({
			masonry: {
				columnWidth: elementWidthNew	//Math.floor(jQuery('.isotope,.isotopeNOanim').width() / columns)
			}
		});
		cont.find('.sc_slider_flex,.sc_slider_chop,.sc_slider_swiper').each(function () {
			calcSliderDimensions(jQuery(this));
		});
	});
}

function initPostFormats() {
	"use strict";

	// MediaElement init
	initMediaElements(jQuery('body'));

	//hoverZoom img effect
	if (jQuery('.hoverIncrease:not(.inited)').length > 0) {
		jQuery('.hoverIncrease:not(.inited)')
			.addClass('inited')
			.each(function () {
				"use strict";
				var img = jQuery(this).data('image');
				var title = jQuery(this).data('title');
				if (img) {
					jQuery(this).append('<span class="hoverShadow"></span><a href="'+img+'" title="'+title+'"><span class="hoverIcon"></span></a>');
				}
			});
	}

    // Popup init
    if (THEMEREX_popupEngine == 'pretty' && typeof jQuery.prettyPhoto != 'undefined') {
	//if (THEMEREX_popupEngine == 'pretty' && typeof(prettyPhoto)) {
		jQuery("a[href$='jpg'],a[href$='jpeg'],a[href$='png'],a[href$='gif']").attr('rel', 'prettyPhoto'+(THEMEREX_popupGallery ? '[slideshow]' : ''));	//.toggleClass('prettyPhoto', true);
		jQuery("a[rel*='prettyPhoto']:not(.inited):not([rel*='magnific']):not([data-rel*='magnific'])")
			.addClass('inited')
			.prettyPhoto({
				social_tools: '',
				theme: 'facebook',
				deeplinking: false
			})
			.click(function(e) {
				"use strict";
				if (jQuery(window).width()<480)	{
					e.stopImmediatePropagation();
					window.location = jQuery(this).attr('href');
				}
				e.preventDefault();
				return false;
			});
	}
    else if (typeof jQuery.magnificPopup != 'undefined') {
    //else {
		jQuery("a[href$='jpg'],a[href$='jpeg'],a[href$='png'],a[href$='gif']").attr('rel', 'magnific');	//.toggleClass('magnific', true);
		jQuery("a[rel*='magnific']:not(.inited):not(.prettyphoto):not([rel*='pretty']):not([data-rel*='pretty'])")
			.addClass('inited')
			.magnificPopup({
				type: 'image',
				mainClass: 'mfp-img-mobile',
				closeOnContentClick: true,
				closeBtnInside: true,
				fixedContentPos: true,
				midClick: true,
				//removalDelay: 500, 
				preloader: true,
				tLoading: THEMEREX_MAGNIFIC_LOADING,
				gallery:{
					enabled:THEMEREX_popupGallery
				},
				image: {
					tError: THEMEREX_MAGNIFIC_ERROR,
					verticalFit: true
				}
			});
	}

	// Popup windows with any html content
    if (typeof jQuery.magnificPopup != 'undefined') {
	jQuery('.user-popup-link:not(.inited),a[href="#openLogin"]:not(.inited)')
		.addClass('inited')
		.magnificPopup({
			type: 'inline',
			removalDelay: 500,
			callbacks: {
				beforeOpen: function () {
					this.st.mainClass = 'mfp-zoom-in';
				},
				open: function () {
					jQuery('html').css({
						overflow: 'visible',
						margin: 0
					});
				},
				close: function () {
				}
			},
			midClick: true
		});
    }

	//textarea Autosize
	if (jQuery('textarea.textAreaSize:not(.inited)').length > 0) {
		jQuery('textarea.textAreaSize:not(.inited)')
			.addClass('inited')
			.autosize({
				append: "\n"
			});
	}

	// Share button
	if (jQuery('ul.shareDrop:not(.inited)').length > 0) {
		jQuery('ul.shareDrop:not(.inited)')
			.addClass('inited')
			.siblings('a').click(function (e) {
				"use strict";
				if (jQuery(this).hasClass('selected')) {
					jQuery(this).removeClass('selected').siblings('ul.shareDrop').slideUp();
				} else {
					jQuery(this).addClass('selected').siblings('ul.shareDrop').slideDown();
				}
				e.preventDefault();
				return false;
			}).end()
			.find('li a').click(function (e) {
				jQuery(this).parents('ul.shareDrop').slideUp().siblings('a.shareDrop').removeClass('selected');
				e.preventDefault();
				return false;
			});
	}

	// Like button
	if (jQuery('.postSharing:not(.inited),.masonryMore:not(.inited)').length > 0) {
		jQuery('.postSharing:not(.inited),.masonryMore:not(.inited)')
			.addClass('inited')
			.find('.likeButton a')
			.click(function(e) {
				var button = jQuery(this).parent();
				var inc = button.hasClass('like') ? 1 : -1;
				var post_id = button.data('postid');
				var likes = Number(button.data('likes'))+inc;
				var cookie_likes = jQuery.cookie('themerex_likes');
				if (cookie_likes === undefined) cookie_likes = '';
				jQuery.post(THEMEREX_ajax_url, {
					action: 'post_counter',
					nonce: THEMEREX_ajax_nonce,
					post_id: post_id,
					likes: likes
				}).done(function(response) {
					var rez = JSON.parse(response);
					if (rez.error === '') {
						if (inc == 1) {
							var title = button.data('title-dislike');
							button.removeClass('like').addClass('likeActive');
							cookie_likes += (cookie_likes.substr(-1)!=',' ? ',' : '') + post_id + ',';
						} else {
							var title = button.data('title-like');
							button.removeClass('likeActive').addClass('like');
							cookie_likes = cookie_likes.replace(','+post_id+',', ',');
						}
						button.data('likes', likes).find('a').attr('title', title).find('.likePost').html(likes);
						jQuery.cookie('themerex_likes', cookie_likes, {expires: 365, path: '/'});
					} else {
						themerex_message_warning(THEMEREX_MESSAGE_ERROR_LIKE);
					}
				});
				e.preventDefault();
				return false;
			});
	}

	//Hover DIR
	if (jQuery('.portfolio > .isotopeElement:not(.inited)').length > 0) {
		jQuery('.portfolio > .isotopeElement:not(.inited)')
			.addClass('inited')
			.find('> .hoverDirShow').each(function () {
				"use strict";
				jQuery(this).hoverdir();
			});
	}

	// Add video on thumb click
	if (jQuery('.sc_video_play_button:not(.inited)').length > 0) {
		jQuery('.sc_video_play_button:not(.inited)').each(function() {
			"use strict";
			var video = jQuery(this).data('video');
			var pos = video.indexOf('height=');
			if (pos > 0) {
				pos += 8;
				var pos2 = video.indexOf('"', pos);
				var h = parseInt(video.substring(pos, pos2));
				if (!isNaN(h))
					jQuery(this).find('img').height(h);
			}
			jQuery(this)
				.addClass('inited')
				.animate({opacity: 1}, 1000)
				.click(function (e) {
					"use strict";
					if (!jQuery(this).hasClass('sc_video_play_button')) return;
					var video = jQuery(this).removeClass('sc_video_play_button').data('video');
					if (video!=='') {
						jQuery(this).empty().html(video);
						videoDimensions();
						var video_tag = jQuery(this).find('video');
						var w = video_tag.width();
						var h = video_tag.height();
						initMediaElements(jQuery(this));
						// Restore WxH attributes, because Chrome broke it!
						jQuery(this).find('video').css({'width':w, 'height': h}).attr({'width':w, 'height': h});
					}
					e.preventDefault();
					return false;
				});
		});
	}

	// IFRAME width and height constrain proportions 
	if (jQuery('iframe,.sc_video_player,video.sc_video').length > 0) {
		if (!THEMEREX_video_resize_inited) {
			THEMEREX_video_resize_inited = true;
			jQuery(window).resize(function() {
				"use strict";
				videoDimensions();
			});
		}
		videoDimensions();
	}
	
	// Tribe Events buttons
	jQuery('.tribe-events-nav-previous,.tribe-events-nav-next,.tribe-events-widget-link,.tribe-events-viewmore').addClass('squareButton');
	jQuery('a.tribe-events-read-more').wrap('<span class="squareButton"></span>');
}


function initMediaElements(cont) {
	if (THEMEREX_useMediaElement && cont.find('audio,video').length > 0) {
		if (window.mejs) {
			window.mejs.MepDefaults.enableAutosize = false;
			window.mejs.MediaElementDefaults.enableAutosize = false;
			cont.find('audio:not(.wp-audio-shortcode),video:not(.wp-video-shortcode)').each(function() {
				if (jQuery(this).parents('.mejs-mediaelement').length == 0) {
					var settings = {
						enableAutosize: false,
						videoWidth: -1,		// if set, overrides <video width>
						videoHeight: -1,	// if set, overrides <video height>
						audioWidth: '100%',	// width of audio player
						audioHeight: 30		// height of audio player
					};
				
					settings.success = function (mejs) {
						var autoplay, loop;
	
						if ( 'flash' === mejs.pluginType ) {
	
							autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
							loop = mejs.attributes.loop && 'false' !== mejs.attributes.loop;
	
							autoplay && mejs.addEventListener( 'canplay', function () {
								mejs.play();
							}, false );
			
							loop && mejs.addEventListener( 'ended', function () {
								mejs.play();
							}, false );
						}
					}

					jQuery(this).mediaelementplayer(settings);
				}
			});
		} else
			setTimeout(function() { initMediaElements(cont); }, 400);
	}
}



// Fit video frames to document width
function videoDimensions() {
	jQuery('.sc_video_player').each(function() {
		"use strict";
		var player = jQuery(this).eq(0);
		var ratio = (player.data('ratio') ? player.data('ratio').split(':') : (player.find('[data-ratio]').length>0 ? player.find('[data-ratio]').data('ratio').split(':') : [16,9]));
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var cover = jQuery(this).find('.sc_video_play_button img');
		var ht = player.find('.sc_video_player_title').height();
		var w_attr = player.data('width');
		var h_attr = player.data('height');
		if (!w_attr || !h_attr) {
			return;
		}
		var percent = (''+w_attr).substr(-1)=='%';
		w_attr = parseInt(w_attr);
		h_attr = parseInt(h_attr);
		var w_real = Math.min(percent ? 10000 : w_attr, player.parents('div,article').width()), //player.width();
			h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
		if (parseInt(player.attr('data-last-width'))==w_real) return;
		if (percent) {
			player.height(h_real + (isNaN(ht) ? 0 : ht));
			if (cover.length > 0) cover.height(h_real);
		} else {
			player.css({'width': w_real+'px', 'height': h_real + (isNaN(ht) ? 0 : ht)+'px'});
			if (cover.length > 0) cover.height(h_real);
		}
		player.attr('data-last-width', w_real);
	});
	jQuery('video.sc_video').each(function() {
		"use strict";
		var video = jQuery(this).eq(0);
		var ratio = (video.data('ratio')!=undefined ? video.data('ratio').split(':') : [16,9]);
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var mejs_cont = video.parents('.mejs-video');
		var player = video.parents('.sc_video_player');
		var w_attr = player.length>0 ? player.data('width') : video.data('width');
		var h_attr = player.length>0 ? player.data('height') : video.data('height');
		if (!w_attr || !h_attr) {
			return;
		}
		var percent = (''+w_attr).substr(-1)=='%';
		w_attr = parseInt(w_attr);
		h_attr = parseInt(h_attr);
		var w_real = Math.round(mejs_cont.length > 0 ? Math.min(percent ? 10000 : w_attr, mejs_cont.parents('div,article').width()) : video.width()),
			h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
		if (parseInt(video.attr('data-last-width'))==w_real) return;
		if (mejs_cont.length > 0 && mejs) {
			setMejsPlayerDimensions(video, w_real, h_real);
		}
		if (percent) {
			video.height(h_real);
		} else {
			video.attr({'width': w_real, 'height': h_real}).css({'width': w_real+'px', 'height': h_real+'px'});
		}
		video.attr('data-last-width', w_real);
	});
	jQuery('video.sc_video_bg').each(function() {
		"use strict";
		var video = jQuery(this).eq(0);
		var ratio = (video.data('ratio')!=undefined ? video.data('ratio').split(':') : [16,9]);
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var mejs_cont = video.parents('.mejs-video');
		var container = mejs_cont.length>0 ? mejs_cont.parent() : video.parent();
		var w = container.width();
		var h = container.height();
		var w1 = Math.ceil(h*ratio);
		var h1 = Math.ceil(w/ratio);
		if (video.parents('.sc_parallax').length > 0) {
			var windowHeight = jQuery(window).height();
			var speed = Number(video.parents('.sc_parallax').data('parallax-speed'));
			var h_add = Math.ceil(Math.abs((windowHeight-h)*speed));
			if (h1 < h + h_add) {
				h1 = h + h_add;
				w1 = Math.ceil(h1 * ratio);
			}
		}
		if (h1 < h) {
			h1 = h;
			w1 = Math.ceil(h1 * ratio);
		}
		if (w1 < w) { 
			w1 = w;
			h1 = Math.ceil(w1 / ratio);
		}
		var l = Math.round((w1-w)/2);
		var t = Math.round((h1-h)/2);
		if (parseInt(video.attr('data-last-width'))==w1) return;
		if (mejs_cont.length > 0) {
			setMejsPlayerDimensions(video, w1, h1);
			mejs_cont.css({'left': -l+'px', 'top': -t+'px'});
		} else
			video.css({'left': -l+'px', 'top': -t+'px'});
		video.attr({'width': w1, 'height': h1, 'data-last-width':w1}).css({'width':w1+'px', 'height':h1+'px'});
		if (video.css('opacity')==0) video.animate({'opacity': 1}, 3000);
	});
	jQuery('iframe').each(function() {
		"use strict";
		var iframe = jQuery(this).eq(0);
		var ratio = (iframe.data('ratio')!=undefined ? iframe.data('ratio').split(':') : (iframe.find('[data-ratio]').length>0 ? iframe.find('[data-ratio]').data('ratio').split(':') : [16,9]));
		ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
		var w_attr = iframe.attr('width');
		var h_attr = iframe.attr('height');
		var player = iframe.parents('.sc_video_player');
		if (player.length > 0) {
			w_attr = player.data('width');
			h_attr = player.data('height');
		}
		if (!w_attr || !h_attr) {
			return;
		}
		var percent = (''+w_attr).substr(-1)=='%';
		w_attr = parseInt(w_attr);
		h_attr = parseInt(h_attr);
		var w_real = player.length > 0 ? player.width() : iframe.width(),
			h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
		if (parseInt(iframe.attr('data-last-width'))==w_real) return;
		iframe.css({'width': w_real+'px', 'height': h_real+'px'});
	});
}

// Resize fullscreen video background
function resizeVideoBackground() {
	var bg = jQuery('.videoBackgroundFullscreen');
	if (bg.length < 1) 
		return;
	if (THEMEREX_useMediaElement && bg.find('.mejs-video').length == 0)  {
		setTimeout(resizeVideoBackground, 100);
		return;
	}
	if (!bg.hasClass('inited')) {
		bg.addClass('inited');
	}
	var video = bg.find('video');
	var ratio = (video.data('ratio')!=undefined ? video.data('ratio').split(':') : [16,9]);
	ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
	var w = bg.width();
	var h = bg.height();
	var w1 = Math.ceil(h*ratio);
	var h1 = Math.ceil(w/ratio);
	if (h1 < h) {
		h1 = h;
		w1 = Math.ceil(h1 * ratio);
	}
	if (w1 < w) { 
		w1 = w;
		h1 = Math.ceil(w1 / ratio);
	}
	var l = Math.round((w1-w)/2);
	var t = Math.round((h1-h)/2);
	if (bg.find('.mejs-container').length > 0) {
		setMejsPlayerDimensions(bg.find('video'), w1, h1);
		bg.find('.mejs-container').css({'left': -l+'px', 'top': -t+'px'});
	} else
		bg.find('video').css({'left': -l+'px', 'top': -t+'px'});
	bg.find('video').attr({'width': w1, 'height': h1}).css({'width':w1+'px', 'height':h1+'px'});
}

// Set Media Elements player dimensions
function setMejsPlayerDimensions(video, w, h) {
	if (mejs) {
		for (var pl in mejs.players) {
			if (mejs.players[pl].media.src == video.attr('src')) {
				if (mejs.players[pl].media.setVideoSize) {
					mejs.players[pl].media.setVideoSize(w, h);
				}
				mejs.players[pl].setPlayerSize(w, h);
				mejs.players[pl].setControlsSize();
				//var mejs_cont = video.parents('.mejs-video');
				//mejs_cont.css({'width': w+'px', 'height': h+'px'}).find('.mejs-layers > div, .mejs-overlay, .mejs-poster').css({'width': w, 'height': h});
			}
		}
	}
}

// Parallax scroll
function REX_parallax(){
	jQuery('.sc_parallax').each(function(){
		var windowHeight = jQuery(window).height();
		var scrollTops = jQuery(window).scrollTop();
		var offsetPrx = Math.max(jQuery(this).offset().top, windowHeight);
		if ( offsetPrx <= scrollTops + windowHeight ) {
			var speed  = Number(jQuery(this).data('parallax-speed'));
			var xpos   = jQuery(this).data('parallax-x-pos');  
			var ypos   = Math.round((offsetPrx - scrollTops - windowHeight) * speed + (speed < 0 ? windowHeight*speed : 0));
			jQuery(this).find('.sc_parallax_content').css('backgroundPosition', xpos+' '+ypos+'px');
			// Uncomment next line if you want parallax video (else - video position is static)
			jQuery(this).find('div.sc_video_bg').css('top', ypos+'px');
		} 
	});
}

// KidsCare upToScroll
function REX_kidsCare_upToScroll(){
	if (jQuery(window).scrollTop() + jQuery(window).height() > jQuery(document).height() - 70) {
		jQuery('.upToScroll').addClass('show');
		//alert("boom!");
	} else {
		jQuery('.upToScroll').removeClass('show');
	}
}

// animation on scroll
function REX_kidsCare_animation(){

	jQuery('.animated').each(function(){
		var objPos = jQuery(this).offset().top;
		//var objHeight = jQuery(this).height(); // for reverse animation
		//var topOfWindow = jQuery(window).scrollTop(); // for reverse animation
		var bottomOfWindow = jQuery(window).scrollTop() + jQuery(window).height();
		if (objPos < bottomOfWindow - 50) {
            /* for reverse animation
			if (jQuery(this).hasClass('reverse')) {
				jQuery(this).addClass('fadeInDown');
			} else {
			*/
				jQuery(this).addClass('fadeInUp');
			/*}*/
		}
        /* for reverse animation
		if ((objPos + objHeight) < topOfWindow - 50) {
			jQuery(this).removeClass('fadeInUp').removeClass('fadeInDown').addClass('reverse');
		}
		if (objPos > bottomOfWindow + 50) {
			jQuery(this).removeClass('fadeInDown').removeClass('reverse');
		}
		*/
	});

}