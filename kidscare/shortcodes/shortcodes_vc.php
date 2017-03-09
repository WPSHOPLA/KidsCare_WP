<?php
require_once( 'shortcodes_vc_classes.php' );

// Remove standard VC shortcodes
vc_remove_element("vc_button");
vc_remove_element("vc_posts_slider");
vc_remove_element("vc_gmaps");
vc_remove_element("vc_teaser_grid");
vc_remove_element("vc_progress_bar");
vc_remove_element("vc_facebook");
vc_remove_element("vc_tweetmeme");
vc_remove_element("vc_googleplus");
vc_remove_element("vc_facebook");
vc_remove_element("vc_pinterest");
vc_remove_element("vc_message");
vc_remove_element("vc_posts_grid");
vc_remove_element("vc_carousel");
vc_remove_element("vc_flickr");
vc_remove_element("vc_tour");
vc_remove_element("vc_separator");
vc_remove_element("vc_single_image");
vc_remove_element("vc_cta_button");
vc_remove_element("vc_accordion");
vc_remove_element("vc_accordion_tab");
vc_remove_element("vc_toggle");
vc_remove_element("vc_tabs");
vc_remove_element("vc_tab");
vc_remove_element("vc_images_carousel");

// Remove standard WP widgets
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_text");

// Common arrays and strings
$THEMEREX_VC_category = __("ThemeREX shortcodes", "themerex");

// Current element id
$THEMEREX_VC_id = array(
	"param_name" => "id",
	"heading" => __("Element ID", "themerex"),
	"description" => __("ID for current element", "themerex"),
	"group" => __('Size &amp; Margins', 'themerex'),
	"value" => "",
	"type" => "textfield"
);

// Current element class
$THEMEREX_VC_class = array(
	"param_name" => "class",
	"heading" => __("Element CSS class", "themerex"),
	"description" => __("CSS class for current element", "themerex"),
	"group" => __('Size &amp; Margins', 'themerex'),
	"value" => "",
	"type" => "textfield"
);

// Current element style
$THEMEREX_VC_style = array(
	"param_name" => "style",
	"heading" => __("CSS styles", "themerex"),
	"description" => __("Any additional CSS rules (if need)", "themerex"),
	"group" => __('Size &amp; Margins', 'themerex'),
	"class" => "",
	"value" => "",
	"type" => "textfield"
);


// Width and height params
function THEMEREX_VC_width($w="") {
	return array(
		"param_name" => "width",
		"heading" => __("Width", "themerex"),
		"description" => __("Width (in pixels or percent) of the current element", "themerex"),
		"group" => __('Size &amp; Margins', 'themerex'),
		"value" => $w,
		"type" => "textfield"
	);
}
function THEMEREX_VC_height($h='') {
	return array(
		"param_name" => "height",
		"heading" => __("Height", "themerex"),
		"description" => __("Height (only in pixels) of the current element", "themerex"),
		"group" => __('Size &amp; Margins', 'themerex'),
		"value" => $h,
		"type" => "textfield"
	);
}

// Margins params
$THEMEREX_VC_margin_top = array(
	"param_name" => "top",
	"heading" => __("Top margin", "themerex"),
	"description" => __("Top margin (in pixels).", "themerex"),
	"group" => __('Size &amp; Margins', 'themerex'),
	"value" => "",
	"type" => "textfield"
);
$THEMEREX_VC_margin_bottom = array(
	"param_name" => "bottom",
	"heading" => __("Bottom margin", "themerex"),
	"description" => __("Bottom margin (in pixels).", "themerex"),
	"group" => __('Size &amp; Margins', 'themerex'),
	"value" => "",
	"type" => "textfield"
);
$THEMEREX_VC_margin_left = array(
	"param_name" => "left",
	"heading" => __("Left margin", "themerex"),
	"description" => __("Left margin (in pixels).", "themerex"),
	"group" => __('Size &amp; Margins', 'themerex'),
	"value" => "",
	"type" => "textfield"
);
$THEMEREX_VC_margin_right = array(
	"param_name" => "right",
	"heading" => __("Right margin", "themerex"),
	"description" => __("Right margin (in pixels).", "themerex"),
	"group" => __('Size &amp; Margins', 'themerex'),
	"value" => "",
	"type" => "textfield"
);







// Accordion
//-------------------------------------------------------------------------------------
vc_map( array(
	"base" => "trx_accordion",
	"name" => __("Accordion", "themerex"),
	"description" => __("Accordion items", "themerex"),
	"category" => __('Content', 'js_composer'),
	'icon' => 'icon_trx_accordion',
	"class" => "trx_sc_collection trx_sc_accordion",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Accordion style", "themerex"),
			"description" => __("Select style for display accordion", "themerex"),
			"class" => "",
			"admin_label" => true,
			"value" => array(
				__('Style 1', 'themerex') => 1,
				__('Style 2', 'themerex') => 2,
				__('Style 3', 'themerex') => 3
			),
			"type" => "dropdown"
		),
		array(
			"param_name" => "counter",
			"heading" => __("Counter", "themerex"),
			"description" => __("Display counter before each accordion title", "themerex"),
			"class" => "",
			"value" => array("Add item numbers before each element" => "on" ),
			"type" => "checkbox"
		),
		array(
			"param_name" => "large",
			"heading" => __("Large titles", "themerex"),
			"description" => __("Show large titles", "themerex"),
			"class" => "",
			"value" => array("Show large titles" => "on"),
			"type" => "checkbox"
		),
		array(
			"param_name" => "shadow",
			"heading" => __("Shadow", "themerex"),
			"description" => __("Display shadow under toggles block", "themerex"),
			"class" => "",
			"value" => array("Display shadow" => "on" ),
			"type" => "checkbox"
		),
		array(
			"param_name" => "initial",
			"heading" => __("Initially opened item", "themerex"),
			"description" => __("Number of initially opened item", "themerex"),
			"class" => "",
			"value" => 1,
			"type" => "textfield"
		),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
	),
	'default_content' => '
		[trx_accordion_item title="' . __( 'Item 1 title', 'themerex' ) . '"][/trx_accordion_item]
		[trx_accordion_item title="' . __( 'Item 2 title', 'themerex' ) . '"][/trx_accordion_item]
	',
	"custom_markup" => '
		<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
			%content%
		</div>
		<div class="tab_controls">
			<button class="add_tab" title="'.__("Add item", "themerex").'">'.__("Add item", "themerex").'</button>
		</div>
	',
	'js_view' => 'VcTrxAccordionView'
) );


vc_map( array(
	"base" => "trx_accordion_item",
	"name" => __("Accordion item", "themerex"),
	"description" => __("Inner accordion item", "themerex"),
	"show_settings_on_create" => true,
	"content_element" => true,
	"is_container" => true,
	"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
	"as_parent" => array('except' => 'trx_accordion'),
	"params" => array(
		array(
			"param_name" => "title",
			"heading" => __("Title", "themerex"),
			"description" => __("Title for current accordion item", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
		),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
	),
  'js_view' => 'VcTrxAccordionTabView'
) );
//class WPBakeryShortCode_Trx_Accordion extends WPBakeryShortCode_VC_Accordion {}
//class WPBakeryShortCode_Trx_Accordion_Item extends WPBakeryShortCode_VC_Accordion_tab {}
class WPBakeryShortCode_Trx_Accordion extends THEMEREX_VC_ShortCodeAccordion {}
class WPBakeryShortCode_Trx_Accordion_Item extends THEMEREX_VC_ShortCodeAccordionItem {}






// Anchor
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_anchor",
	"name" => __("Anchor", "themerex"),
	"description" => __("Insert anchor for the TOC (table of content)", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_anchor',
	"class" => "trx_sc_single trx_sc_anchor",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "icon",
			"heading" => __("Anchor's icon", "themerex"),
            "description" => __("Select icon for the anchor from Fontello icons set", "themerex"),
			"class" => "",
			"value" => "",
			"value" => $THEMEREX_shortcodes_icons,
			"type" => "dropdown"
        ),
		array(
			"param_name" => "title",
			"heading" => __("Short title", "themerex"),
            "description" => __("Short title of the anchor (for the table of content)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "description",
			"heading" => __("Long description", "themerex"),
            "description" => __("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "url",
			"heading" => __("External URL", "themerex"),
            "description" => __("External URL for this TOC item", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "separator",
			"heading" => __("Add separator", "themerex"),
            "description" => __("Add separator under item in the TOC", "themerex"),
			"class" => "",
			"value" => array("Add separator" => "yes" ),
			"type" => "checkbox"
        ),
		$THEMEREX_VC_id
    )
) );

//class WPBakeryShortCode_Trx_Anchor extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Anchor extends THEMEREX_VC_ShortCodeSingle {}






// Audio
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_audio",
	"name" => __("Audio", "themerex"),
	"description" => __("Insert audio player", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_audio',
	"class" => "trx_sc_single trx_sc_audio",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "url",
			"heading" => __("URL for audio file", "themerex"),
            "description" => __("Put here URL for audio file", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "controls",
			"heading" => __("Controls", "themerex"),
            "description" => __("Show/hide controls", "themerex"),
			"class" => "",
			"value" => array("Hide controls" => "hide" ),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "autoplay",
			"heading" => __("Autoplay", "themerex"),
            "description" => __("Autoplay audio on page load", "themerex"),
			"class" => "",
			"value" => array("Autoplay" => "on" ),
			"type" => "checkbox"
        ),
		THEMEREX_VC_width("100%"),
		THEMEREX_VC_height(50),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Audio extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Audio extends THEMEREX_VC_ShortCodeSingle {}







// Banner
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_banner",
	"name" => __("Banner", "themerex"),
	"description" => __("Insert banner", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_banner',
	"class" => "trx_sc_container trx_sc_banner",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "src",
			"heading" => __("URL for image file", "themerex"),
            "description" => __("Put here URL for image file", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Banner alignment", "themerex"),
            "description" => __("Align banner to left, center or right", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "title",
			"heading" => __("Title", "themerex"),
            "description" => __("Banner's title", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Text", "themerex"),
            "description" => __("Banner's inner text", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/	// Uncomment this row, if you want display content after admin labels
			"type" => "textarea_html"
        ),
		array(
			"param_name" => "link",
			"heading" => __("Link URL", "themerex"),
            "description" => __("URL for the link on banner click", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "target",
			"heading" => __("Link target", "themerex"),
            "description" => __("Target for the link on banner click", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "popup",
			"heading" => __("Open link in popup", "themerex"),
            "description" => __("Open link target in popup window", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => array(__('Open in popup', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "rel",
			"heading" => __("Rel attribute", "themerex"),
            "description" => __("Rel attribute for the banner's link (if need", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => "",
			"type" => "textfield"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'

) );

//class WPBakeryShortCode_Trx_Banner extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Banner extends THEMEREX_VC_ShortCodeContainer {}







// Block
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_block",
	"name" => __("Block container", "themerex"),
	"description" => __("Container for any block ([section] analog - to enable nesting)", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_block',
	"class" => "trx_sc_collection trx_sc_block",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "color",
			"heading" => __("Fore color", "themerex"),
            "description" => __("Any color for the text in this section", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "bg_color",
			"heading" => __("Background color", "themerex"),
            "description" => __("Any background color for this section", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "bg_image",
			"heading" => __("Background image", "themerex"),
            "description" => __("Select background image from library for this section", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "bg_tint",
			"heading" => __("Background tint", "themerex"),
            "description" => __("Main background tint: dark or light", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_tint),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "dedicated",
			"heading" => __("Dedicated", "themerex"),
            "description" => __("Use this block as dedicated content - show it before post title on single page", "themerex"),
			"class" => "",
			"value" => array(__('Use as dedicated content', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Alignment", "themerex"),
            "description" => __("Select block alignment", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "columns",
			"heading" => __("Columns emulation", "themerex"),
            "description" => __("Select width for columns emulation", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_columns),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "scroll",
			"heading" => __("Use scroller", "themerex"),
            "description" => __("Use scroller to show block content", "themerex"),
			"group" => __('Scroll', 'themerex'),
			"class" => "",
			"value" => array(__('Content scroller', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "dir",
			"heading" => __("Scroll direction", "themerex"),
            "description" => __("Scroll direction (if Use scroller = yes)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"group" => __('Scroll', 'themerex'),
			"value" => array_flip($THEMEREX_shortcodes_dir),
	        'dependency' => array(
				'element' => 'scroll',
				'not_empty' => true
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Container content", "themerex"),
            "description" => __("Content for section container", "themerex"),
			"class" => "",
			"value" => "",
			"holder" => "div",
			"type" => "textarea_html"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class,
		$THEMEREX_VC_style
    )
) );

//class WPBakeryShortCode_Trx_Block extends WPBakeryShortCodesContainer { }
class WPBakeryShortCode_Trx_Block extends THEMEREX_VC_ShortCodeCollection {}






// Blogger
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_blogger",
	"name" => __("Blogger", "themerex"),
	"description" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_blogger',
	"class" => "trx_sc_single trx_sc_blogger",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Output style", "themerex"),
            "description" => __("Select desired style for posts output", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_blogger_styles),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "filters",
			"heading" => __("Show filters", "themerex"),
            "description" => __("Use post's tags or categories as filter buttons", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_filters),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "hover",
			"heading" => __("Hover effect", "themerex"),
            "description" => __("Select hover effect (only if style=Portfolio)", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_hovers),
	        'dependency' => array(
				'element' => 'style',
				'value' => array('portfolio1','portfolio2','portfolio3','portfolio4')
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "hover_dir",
			"heading" => __("Hover direction", "themerex"),
            "description" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_hovers_dir),
	        'dependency' => array(
				'element' => 'style',
				'value' => array('portfolio1','portfolio2','portfolio3','portfolio4')
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "location",
			"heading" => __("Dedicated content location", "themerex"),
            "description" => __("Select position for dedicated content (only for style=excerpt)", "themerex"),
			"class" => "",
	        'dependency' => array(
				'element' => 'style',
				'value' => array('excerpt')
			),
			"value" => array_flip($THEMEREX_shortcodes_locations),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "dir",
			"heading" => __("Posts direction", "themerex"),
            "description" => __("Display posts in horizontal or vertical direction", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_dir),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "rating",
			"heading" => __("Show rating stars", "themerex"),
            "description" => __("Show rating stars under post's header", "themerex"),
			"group" => __('Details', 'themerex'),
			"class" => "",
			"value" => array(__('Show rating', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "info",
			"heading" => __("Show post info block", "themerex"),
            "description" => __("Show post info block (author, date, tags, etc.)", "themerex"),
			"class" => "",
			"value" => array(__('Allow links', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "descr",
			"heading" => __("Description length", "themerex"),
            "description" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "themerex"),
			"group" => __('Details', 'themerex'),
			"class" => "",
			"value" => 0,
			"type" => "textfield"
        ),
		array(
			"param_name" => "links",
			"heading" => __("Allow links to the post", "themerex"),
            "description" => __("Allow links to the post from each blogger item", "themerex"),
			"group" => __('Details', 'themerex'),
			"class" => "",
			"value" => array(__('Show info', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "readmore",
			"heading" => __("More link text", "themerex"),
            "description" => __("Read more link text. If empty - show 'More', else - used as link text", "themerex"),
			"group" => __('Details', 'themerex'),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "ids",
			"heading" => __("Post IDs list", "themerex"),
            "description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "themerex"),
			"group" => __('Query', 'themerex'),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "cat",
			"heading" => __("Categories list", "themerex"),
            "description" => __("Put here comma separated category slugs or ids. If empty - show posts from any category or from IDs list", "themerex"),
	        'dependency' => array(
				'element' => 'ids',
				'is_empty' => true
			),
			"group" => __('Query', 'themerex'),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "count",
			"heading" => __("Total posts to show", "themerex"),
            "description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "themerex"),
	        'dependency' => array(
				'element' => 'ids',
				'is_empty' => true
			),
			"admin_label" => true,
			"group" => __('Query', 'themerex'),
			"class" => "",
			"value" => 3,
			"type" => "textfield"
        ),
		array(
			"param_name" => "visible",
			"heading" => __("Number of visible posts", "themerex"),
            "description" => __("How many posts will be visible at once? If empty or 0 - all posts are visible", "themerex"),
	        'dependency' => array(
				'element' => 'ids',
				'is_empty' => true
			),
			"group" => __('Query', 'themerex'),
			"class" => "",
			"value" => 3,
			"type" => "textfield"
        ),
		array(
			"param_name" => "offset",
			"heading" => __("Offset before select posts", "themerex"),
            "description" => __("Skip posts before select next part.", "themerex"),
	        'dependency' => array(
				'element' => 'ids',
				'is_empty' => true
			),
			"group" => __('Query', 'themerex'),
			"class" => "",
			"value" => 0,
			"type" => "textfield"
        ),
		array(
			"param_name" => "orderby",
			"heading" => __("Post order by", "themerex"),
            "description" => __("Select desired posts sorting method", "themerex"),
			"class" => "",
			"group" => __('Query', 'themerex'),
			"value" => array_flip($THEMEREX_shortcodes_sorting),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "order",
			"heading" => __("Post order", "themerex"),
            "description" => __("Select desired posts order", "themerex"),
			"class" => "",
			"group" => __('Query', 'themerex'),
			"value" => array_flip($THEMEREX_shortcodes_ordering),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "only",
			"heading" => __("Select posts only", "themerex"),
            "description" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "themerex"),
			"class" => "",
			"group" => __('Query', 'themerex'),
			"value" => array_flip($THEMEREX_shortcodes_formats),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "scroll",
			"heading" => __("Use scroller", "themerex"),
            "description" => __("Use scroller to show all posts (if parameter 'visible' less than 'count')", "themerex"),
			"group" => __('Scroll', 'themerex'),
			"class" => "",
			"value" => array(__('Use scroller', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "controls",
			"heading" => __("Show slider controls", "themerex"),
            "description" => __("Show arrows to control scroll slider", "themerex"),
			"group" => __('Scroll', 'themerex'),
			"class" => "",
			"value" => array(__('Show controls', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
) );

//class WPBakeryShortCode_Trx_Blogger extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Blogger extends THEMEREX_VC_ShortCodeSingle {}






// Br
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_br",
	"name" => __("Line break", "themerex"),
	"description" => __("Line break or Clear Floating", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_br',
	"class" => "trx_sc_single trx_sc_br",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "clear",
			"heading" => __("Clear floating", "themerex"),
            "description" => __("Select clear side (if need)", "themerex"),
			"class" => "",
			"value" => "",
			"value" => array(
				__('None', 'themerex') => 'none',
				__('Left', 'themerex') => 'left',
				__('Right', 'themerex') => 'right',
				__('Both', 'themerex') => 'both'
			),
			"type" => "dropdown"
        )
    )
) );

class WPBakeryShortCode_Trx_Br extends THEMEREX_VC_ShortCodeSingle {}







// Button
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_button",
	"name" => __("Button", "themerex"),
	"description" => __("Button with link", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_button',
	"class" => "trx_sc_single trx_sc_button",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "content",
			"heading" => __("Caption", "themerex"),
            "description" => __("Button caption", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textfield"
        ),
		array(
			"param_name" => "type",
			"heading" => __("Button's shape", "themerex"),
            "description" => __("Select button's shape", "themerex"),
			"class" => "",
			"value" => array(
				__('Square', 'themerex') => 'square',
				__('Round', 'themerex') => 'round'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "style",
			"heading" => __("Button's style", "themerex"),
            "description" => __("Select button's style", "themerex"),
			"class" => "",
			"value" => array(
				__('Global', 'themerex') => 'global',
				__('Light', 'themerex')  => 'light',
				__('Dark', 'themerex')   => 'dark',
				__('Border', 'themerex') => 'border'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "size",
			"heading" => __("Button's size", "themerex"),
            "description" => __("Select button's size", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Small', 'themerex') => 'mini',
				__('Medium', 'themerex') => 'medium',
				__('Large', 'themerex') => 'big',
				__('Huge', 'themerex') => 'huge',
				__('Banner', 'themerex') => 'banner'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "fullsize",
			"heading" => __("Fullsize mode", "themerex"),
            "description" => __("Set button's width to 100%", "themerex"),
			"class" => "",
			"value" => array(__('Fullsize mode', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "icon",
			"heading" => __("Button's icon", "themerex"),
            "description" => __("Select icon for the title from Fontello icons set", "themerex"),
			"class" => "",
			"value" => "",
			"value" => $THEMEREX_shortcodes_icons,
			"type" => "dropdown"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Button's color", "themerex"),
            "description" => __("Any color for button background", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Button's alignment", "themerex"),
            "description" => __("Align button to left or right", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_float),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "link",
			"heading" => __("Link URL", "themerex"),
            "description" => __("URL for the link on button click", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "target",
			"heading" => __("Link target", "themerex"),
            "description" => __("Target for the link on button click", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "popup",
			"heading" => __("Open link in popup", "themerex"),
            "description" => __("Open link target in popup window", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => array(__('Open in popup', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "rel",
			"heading" => __("Rel attribute", "themerex"),
            "description" => __("Rel attribute for the button's link (if need", "themerex"),
			"class" => "",
			"group" => __('Link', 'themerex'),
			"value" => "",
			"type" => "textfield"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )

) );

//class WPBakeryShortCode_Trx_Button extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Button extends THEMEREX_VC_ShortCodeSingle {}







// Chat
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_chat",
	"name" => __("Chat", "themerex"),
	"description" => __("Chat message", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_chat',
	"class" => "trx_sc_container trx_sc_chat",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
            "param_name" => "title",
            "heading" => __("Item title", "themerex"),
            "description" => __("Title for current chat item", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "link",
			"heading" => __("Link URL", "themerex"),
            "description" => __("URL for the link on chat title click", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Chat item content", "themerex"),
            "description" => __("Current chat item content", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'

) );

//class WPBakeryShortCode_Trx_Chat extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Chat extends THEMEREX_VC_ShortCodeContainer {}






// Columns
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_columns",
	"name" => __("Columns", "themerex"),
	"description" => __("Insert columns with margins", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_columns',
	"class" => "trx_sc_columns",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_column_item'),
	"params" => array(
		array(
            "param_name" => "count",
            "heading" => __("Columns count", "themerex"),
            "description" => __("Number of the columns in the container.", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "2",
			"type" => "textfield"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
    'default_content' => '
	    [trx_column_item][/trx_column_item]
	    [trx_column_item][/trx_column_item]
    ',
	'js_view' => 'VcTrxColumnsView'
) );


vc_map( array(
	"base" => "trx_column_item",
	"name" => __("Column", "themerex"),
	"description" => __("Column item", "themerex"),
	"show_settings_on_create" => true,
	"class" => "trx_sc_collection trx_sc_column_item",
	"content_element" => true,
	"is_container" => true,
	"as_child" => array('only' => 'trx_columns'),
	"as_parent" => array('except' => 'trx_columns'),
	"params" => array(
		array(
            "param_name" => "span",
            "heading" => __("Merge columns", "themerex"),
            "description" => __("Count merged columns from current", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "align",
            "heading" => __("Alignment", "themerex"),
            "description" => __("Alignment text in the column", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Fore color", "themerex"),
            "description" => __("Any color for objects in this column", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "bg_color",
			"heading" => __("Background color", "themerex"),
            "description" => __("Any background color for this column", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "bg_image",
			"heading" => __("URL for background image file", "themerex"),
            "description" => __("Select or upload image or write URL from other site for the background", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Column's content", "themerex"),
            "description" => __("Content of the current column", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class,
		$THEMEREX_VC_style
    ),
	'js_view' => 'VcTrxColumnItemView'
) );

class WPBakeryShortCode_Trx_Columns extends THEMEREX_VC_ShortCodeColumns {}
class WPBakeryShortCode_Trx_Column_Item extends THEMEREX_VC_ShortCodeCollection {}







// Contact form
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_contact_form",
	"name" => __("Contact form", "themerex"),
	"description" => __("Insert contact form", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_contact_form',
	"class" => "trx_sc_collection trx_sc_contact_form",
	"content_element" => true,
	"is_container" => true,
	"as_parent" => array('only' => 'trx_form_item'),
	"show_settings_on_create" => true,
	"params" => array(
		array(
            "param_name" => "title",
            "heading" => __("Title", "themerex"),
            "description" => __("Title above contact form", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "description",
			"heading" => __("Description (under the title)", "themerex"),
            "description" => __("Contact form description", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );


vc_map( array(
	"base" => "trx_form_item",
	"name" => __("Form item (custom field)", "themerex"),
	"description" => __("Custom field for the contact form", "themerex"),
	"class" => "trx_sc_item trx_sc_form_item",
	"allowed_container_element" => 'vc_row',
	"show_settings_on_create" => true,
	"content_element" => true,
	"is_container" => false,
	"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
	"params" => array(
		array(
			"param_name" => "type",
			"heading" => __("Type", "themerex"),
            "description" => __("Select type of the custom field", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_field_types),
			"type" => "dropdown"
        ),
		array(
            "param_name" => "label",
            "heading" => __("Label", "themerex"),
            "description" => __("Label for the custom field", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "name",
            "heading" => __("Name", "themerex"),
            "description" => __("Name of the custom field", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "value",
            "heading" => __("Default value", "themerex"),
            "description" => __("Default value of the custom field", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Contact_Form extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_Form_Item extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Contact_Form extends THEMEREX_VC_ShortCodeCollection {}
class WPBakeryShortCode_Trx_Form_Item extends THEMEREX_VC_ShortCodeItem {}







// Content block on fullscreen page
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_content",
	"name" => __("Content block", "themerex"),
	"description" => __("Container for main content block (use it only on fullscreen pages)", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_content',
	"class" => "trx_sc_collection trx_sc_content",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "content",
			"heading" => __("Container content", "themerex"),
            "description" => __("Content for section container", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
        array(
            "param_name" => "size",
            "heading" => __("Font size", "themerex"),
            "description" => __("Font size for the text (default - in pixels, allows any CSS units of measure)", "themerex"),
            "admin_label" => true,
            "class" => "",
            "value" => "",
            "type" => "textfield"
        ),
        array(
            "param_name" => "line_height",
            "heading" => __("Line height", "themerex"),
            "description" => __("Line height for the text (default - in pixels, allows any CSS units of measure)", "themerex"),
            "admin_label" => true,
            "class" => "",
            "value" => "",
            "type" => "textfield"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class,
		$THEMEREX_VC_style
    )
) );

//class WPBakeryShortCode_Trx_Content extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Content extends THEMEREX_VC_ShortCodeCollection {}







// Countdown
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_countdown",
	"name" => __("Countdown", "themerex"),
	"description" => __("Insert countdown object", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_countdown',
	"class" => "trx_sc_single trx_sc_countdown",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
            "param_name" => "date",
            "heading" => __("Date", "themerex"),
            "description" => __("Upcoming date (format: yyyy-mm-dd)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "time",
            "heading" => __("Time", "themerex"),
            "description" => __("Upcoming time (format: HH:mm:ss)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Alignment", "themerex"),
            "description" => __("Align counter to left, center or right", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Countdown extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Countdown extends THEMEREX_VC_ShortCodeSingle {}







// Dropcaps
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_dropcaps",
	"name" => __("Dropcaps", "themerex"),
	"description" => __("Make first letter of the text as dropcaps", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_dropcaps',
	"class" => "trx_sc_container trx_sc_dropcaps",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Style", "themerex"),
            "description" => __("Dropcaps style", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Style 1', 'themerex') => 1,
				__('Style 2', 'themerex') => 2,
				__('Style 3', 'themerex') => 3,
				__('Style 4', 'themerex') => 4,
				__('Style 5', 'themerex') => 5,
				__('Style 6', 'themerex') => 6
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Paragraph text", "themerex"),
            "description" => __("Paragraph with dropcaps content", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'

) );

//class WPBakeryShortCode_Trx_Dropcaps extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Dropcaps extends THEMEREX_VC_ShortCodeContainer {}







// Emailer
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_emailer",
	"name" => __("E-mail collector", "themerex"),
	"description" => __("Collect e-mails into specified group", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_emailer',
	"class" => "trx_sc_single trx_sc_emailer",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
            "param_name" => "group",
            "heading" => __("Group", "themerex"),
            "description" => __("The name of group to collect e-mail address", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "open",
			"heading" => __("Opened", "themerex"),
            "description" => __("Initially open the input field on show object", "themerex"),
			"class" => "",
			"value" => array(__('Initially opened', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Alignment", "themerex"),
            "description" => __("Align field to left, center or right", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Emailer extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Emailer extends THEMEREX_VC_ShortCodeSingle {}







// Gap
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_gap",
	"name" => __("Gap", "themerex"),
	"description" => __("Insert gap (fullwidth area) in the post content", "themerex"),
	"category" => __('Structure', 'js_composer'),
    'icon' => 'icon_trx_gap',
	"class" => "trx_sc_collection trx_sc_gap",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => false,
	"params" => array(
		array(
			"param_name" => "content",
			"heading" => __("Gap content", "themerex"),
            "description" => __("Gap inner content", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        )
    )
) );

//class WPBakeryShortCode_Trx_Gap extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Gap extends THEMEREX_VC_ShortCodeCollection {}







// Googlemap
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_googlemap",
	"name" => __("Google map", "themerex"),
	"description" => __("Insert Google map with desired address or coordinates", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_googlemap',
	"class" => "trx_sc_single trx_sc_googlemap",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
            "param_name" => "address",
            "heading" => __("Address", "themerex"),
            "description" => __("Address to show in map center", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "latlng",
            "heading" => __("Latitude and Longtitude", "themerex"),
            "description" => __("Comma separated map center coorditanes (instead Address)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "zoom",
            "heading" => __("Zoom", "themerex"),
            "description" => __("Map zoom factor", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "16",
			"type" => "textfield"
        ),
		array(
			"param_name" => "style",
			"heading" => __("Style", "themerex"),
            "description" => __("Map custom style", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_googlemap_styles),
			"type" => "dropdown"
        ),
		THEMEREX_VC_width('100%'),
		THEMEREX_VC_height(240),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Googlemap extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Googlemap extends THEMEREX_VC_ShortCodeSingle {}







// Highlight
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_highlight",
	"name" => __("Highlight text", "themerex"),
	"description" => __("Highlight text with selected color, background color and other styles", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_highlight',
	"class" => "trx_sc_container trx_sc_highlight",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "type",
			"heading" => __("Type", "themerex"),
            "description" => __("Highlight type", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
					__('Custom', 'themerex') => 0,
					__('Type 1', 'themerex') => 1,
					__('Type 2', 'themerex') => 2
				),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Text color", "themerex"),
            "description" => __("Color for the highlighted text", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "backcolor",
			"heading" => __("Background color", "themerex"),
            "description" => __("Background color for the highlighted text", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
            "param_name" => "size",
            "heading" => __("Font size", "themerex"),
            "description" => __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
        array(
            "param_name" => "line_height",
            "heading" => __("Line height", "themerex"),
            "description" => __("Line height for the highlighted text (default - in pixels, allows any CSS units of measure)", "themerex"),
            "admin_label" => true,
            "class" => "",
            "value" => "",
            "type" => "textfield"
        ),
		array(
            "param_name" => "style",
            "heading" => __("CSS-styles", "themerex"),
            "description" => __("Any additional CSS rules", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Highlight text", "themerex"),
            "description" => __("Content for highlight", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'
) );

//class WPBakeryShortCode_Trx_Highlight extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Highlight extends THEMEREX_VC_ShortCodeContainer {}






// Icon
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_icon",
	"name" => __("Icon", "themerex"),
	"description" => __("Insert the icon", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_icon',
	"class" => "trx_sc_single trx_sc_icon",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "icon",
			"heading" => __("Icon", "themerex"),
            "description" => __("Select icon class from Fontello icons set", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"value" => $THEMEREX_shortcodes_icons,
			"type" => "dropdown"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Text color", "themerex"),
            "description" => __("Icon's color", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "bg_color",
			"heading" => __("Background color", "themerex"),
            "description" => __("Background color for the icon", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "background",
			"heading" => __("Background style", "themerex"),
            "description" => __("Style of the icon background", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('None', 'themerex') => 'none',
				__('Round', 'themerex') => 'round',
				__('Square', 'themerex') => 'square'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "size",
			"heading" => __("Font size", "themerex"),
            "description" => __("Icon's font size", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "weight",
			"heading" => __("Font weight", "themerex"),
            "description" => __("Icon's font weight", "themerex"),
			"class" => "",
			"value" => array(
				__('Thin (100)', 'themerex') => '100',
				__('Light (300)', 'themerex') => '300',
				__('Normal (400)', 'themerex') => '400',
				__('Bold (700)', 'themerex') => '700'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Icon's alignment", "themerex"),
            "description" => __("Align icon to left, center or right", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
) );

//class WPBakeryShortCode_Trx_Icon extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Icon extends THEMEREX_VC_ShortCodeSingle {}







// Image
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_image",
	"name" => __("Image", "themerex"),
	"description" => __("Insert image", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_image',
	"class" => "trx_sc_single trx_sc_image",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "url",
			"heading" => __("Select image", "themerex"),
            "description" => __("Select image from library", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Image alignment", "themerex"),
            "description" => __("Align image to left or right side", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_float),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "shape",
			"heading" => __("Image shape", "themerex"),
            "description" => __("Shape of the image: square or round", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Square', 'themerex') => 'square',
				__('Round', 'themerex') => 'round'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "title",
			"heading" => __("Title", "themerex"),
            "description" => __("Image's title", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "icon",
			"heading" => __("Title's icon", "themerex"),
            "description" => __("Select icon for the title from Fontello icons set", "themerex"),
			"class" => "",
			"value" => "",
			"value" => $THEMEREX_shortcodes_icons,
			"type" => "dropdown"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Image extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Image extends THEMEREX_VC_ShortCodeSingle {}







// Infobox
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_infobox",
	"name" => __("Infobox", "themerex"),
	"description" => __("Box with info or error message", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_infobox',
	"class" => "trx_sc_container trx_sc_infobox",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Style", "themerex"),
            "description" => __("Infobox style", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
					__('Regular', 'themerex') => 'regular',
					__('Info', 'themerex') => 'info',
					__('Success', 'themerex') => 'success',
					__('Error', 'themerex') => 'error',
					__('Result', 'themerex') => 'result'
				),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "closeable",
			"heading" => __("Closeable", "themerex"),
            "description" => __("Create closeable box (with close button)", "themerex"),
			"class" => "",
			"value" => array(__('Close button', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Message text", "themerex"),
            "description" => __("Message for the infobox", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'
) );

//class WPBakeryShortCode_Trx_Infobox extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Infobox extends THEMEREX_VC_ShortCodeContainer {}







// Line
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_line",
	"name" => __("Line", "themerex"),
	"description" => __("Insert line (delimiter)", "themerex"),
	"category" => __('Content', 'js_composer'),
	"class" => "trx_sc_single trx_sc_line",
    'icon' => 'icon_trx_line',
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Style", "themerex"),
            "description" => __("Line style", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
					__('Solid', 'themerex') => 'solid',
					__('Dashed', 'themerex') => 'dashed',
					__('Dotted', 'themerex') => 'dotted',
					__('Double', 'themerex') => 'double',
					__('Shadow', 'themerex') => 'shadow',
					__('Wavy', 'themerex') => 'wavy',
					__('Wavy Orange', 'themerex') => 'wavy_orange'
				),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Line color", "themerex"),
            "description" => __("Line color", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Line extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Line extends THEMEREX_VC_ShortCodeSingle {}







// List
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_list",
	"name" => __("List", "themerex"),
	"description" => __("List items with specific bullets", "themerex"),
	"category" => __('Content', 'js_composer'),
	"class" => "trx_sc_collection trx_sc_list",
    'icon' => 'icon_trx_list',
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_list_item'),
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Bullet's style", "themerex"),
            "description" => __("Bullet's style for each list item", "themerex"),
			"class" => "",
			"admin_label" => true,
			"value" => array_flip($THEMEREX_shortcodes_list_styles),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "icon",
			"heading" => __("List icon", "themerex"),
            "description" => __("Select list icon from Fontello icons set (only for style=Iconed)", "themerex"),
			"admin_label" => true,
			"class" => "",
	        'dependency' => array(
				'element' => 'style',
				'value' => array('iconed')
			),
			"value" => $THEMEREX_shortcodes_icons,
			"type" => "dropdown"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
    'default_content' => '
	    [trx_list_item title="' . __( 'Item 1', 'themerex' ) . '"][/trx_list_item]
    	[trx_list_item title="' . __( 'Item 2', 'themerex' ) . '"][/trx_list_item]
    '
) );


vc_map( array(
	"base" => "trx_list_item",
	"name" => __("List item", "themerex"),
	"description" => __("List item with specific bullet", "themerex"),
	"class" => "trx_sc_single trx_sc_list_item",
	"show_settings_on_create" => true,
	"content_element" => true,
	"is_container" => true,
	"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
	"as_parent" => array('except' => 'trx_list'),
	"params" => array(
		array(
            "param_name" => "title",
            "heading" => __("List item title", "themerex"),
            "description" => __("Title for the current list item (show it as tooltip)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "icon",
			"heading" => __("List item icon", "themerex"),
            "description" => __("Select list item icon from Fontello icons set (only for style=Iconed)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => $THEMEREX_shortcodes_icons,
			"type" => "dropdown"
        ),
		array(
			"param_name" => "content",
			"heading" => __("List item text", "themerex"),
            "description" => __("Current list item content", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextView'

) );

//class WPBakeryShortCode_Trx_List extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_List_Item extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_List extends THEMEREX_VC_ShortCodeCollection {}
class WPBakeryShortCode_Trx_List_Item extends THEMEREX_VC_ShortCodeSingle {}













// Parallax
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_parallax",
	"name" => __("Parallax", "themerex"),
	"description" => __("Create the parallax container (with asinc background image)", "themerex"),
	"category" => __('Structure', 'js_composer'),
    'icon' => 'icon_trx_parallax',
	"class" => "trx_sc_collection trx_sc_parallax",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "gap",
			"heading" => __("Create gap", "themerex"),
            "description" => __("Create gap around parallax container (not need in fullscreen pages)", "themerex"),
			"class" => "",
			"value" => array(__('Create gap', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "style",
			"heading" => __("Style", "themerex"),
            "description" => __("Select style of the parallax background (for correct font color choise)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
					__('Light', 'themerex') => 'light',
					__('Dark', 'themerex') => 'dark'
				),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "dir",
			"heading" => __("Direction", "themerex"),
            "description" => __("Scroll direction for the parallax background", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
					__('Up', 'themerex') => 'up',
					__('Down', 'themerex') => 'down'
				),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "speed",
			"heading" => __("Speed", "themerex"),
            "description" => __("Parallax background motion speed (from 0.0 to 1.0)", "themerex"),
			"class" => "",
			"value" => "0.3",
			"type" => "textfield"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Backgroud color", "themerex"),
            "description" => __("Select color for parallax background", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "overlay",
			"heading" => __("Overlay", "themerex"),
            "description" => __("Overlay color opacity (from 0.0 to 1.0)", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "texture",
			"heading" => __("Texture", "themerex"),
            "description" => __("Texture style from 1 to 11. Empty or 0 - without texture.", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "image",
			"heading" => __("Select image", "themerex"),
            "description" => __("Select or upload image or write URL from other site for the parallax background", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "image_x",
			"heading" => __("Image X position", "themerex"),
            "description" => __("Parallax background X position (in percents)", "themerex"),
			"class" => "",
			"value" => "50%",
			"type" => "textfield"
        ),
		array(
			"param_name" => "video",
			"heading" => __("Video background", "themerex"),
            "description" => __("Paste URL for video file to show it as parallax background", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Content", "themerex"),
            "description" => __("Content for the parallax container", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textarea_html"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Parallax extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Parallax extends THEMEREX_VC_ShortCodeCollection {}






// Popup
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_popup",
	"name" => __("Popup window", "themerex"),
	"description" => __("Container for any html-block with desired class and style for popup window", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_popup',
	"class" => "trx_sc_collection trx_sc_popup",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "content",
			"heading" => __("Container content", "themerex"),
            "description" => __("Content for popup container", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class,
		$THEMEREX_VC_style
    )
) );

//class WPBakeryShortCode_Trx_Popup extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Popup extends THEMEREX_VC_ShortCodeCollection {}







// Price
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_price",
	"name" => __("Price", "themerex"),
	"description" => __("Insert price with decoration", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_price',
	"class" => "trx_sc_single trx_sc_price",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "money",
			"heading" => __("Money", "themerex"),
            "description" => __("Money value (dot or comma separated)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "currency",
			"heading" => __("Currency symbol", "themerex"),
            "description" => __("Currency character", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "$",
			"type" => "textfield"
        ),
		array(
			"param_name" => "period",
			"heading" => __("Period", "themerex"),
            "description" => __("Period text (if need). For example: monthly, daily, etc.", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Price extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Price extends THEMEREX_VC_ShortCodeSingle {}







// Price table
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_price_table",
	"name" => __("Price table", "themerex"),
	"description" => __("Price table container.", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_price_table',
	"class" => "trx_sc_columns trx_sc_list_item",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_price_item'),
	"params" => array(
		array(
			"param_name" => "align",
			"heading" => __("Table text alignment", "themerex"),
            "description" => __("Alignment text in the table", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "count",
			"heading" => __("Columns count", "themerex"),
            "description" => __("Columns count (required)", "themerex"),
			"class" => "",
			"admin_label" => true,
			"value" => "2",
			"type" => "textfield"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxColumnsView'
) );


vc_map( array(
	"base" => "trx_price_item",
	"name" => __("Price item", "themerex"),
	"description" => __("Price item column", "themerex"),
    'icon' => 'icon_trx_price_item',
	"show_settings_on_create" => false,
	"class" => "trx_sc_collection trx_sc_column_item trx_sc_price_item",
	"content_element" => true,
	"is_container" => true,
	"as_parent" => array('only' => 'trx_price_data'),
	"as_child" => array('only' => 'trx_price_table'),
	"params" => array(
		array(
			"param_name" => "animation",
			"heading" => __("Animation", "themerex"),
            "description" => __("Animate column on mouse hover", "themerex"),
			"class" => "",
			"value" => array(__('Animate column', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );


vc_map( array(
	"base" => "trx_price_data",
	"name" => __("Price item data", "themerex"),
	"description" => __("Price item data - title, price, footer, etc.", "themerex"),
    'icon' => 'icon_trx_price_data',
	"show_settings_on_create" => true,
	"class" => "trx_sc_container trx_sc_price_data",
	"content_element" => true,
	"is_container" => true,
	"as_parent" => array('except' => 'trx_price_table'),
	"as_child" => array('only' => 'trx_price_item'),
	"params" => array(
		array(
			"param_name" => "type",
			"heading" => __("Cell type", "themerex"),
            "description" => __("Select type of the price table cell", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Regular', 'themerex') => 'none',
				__('Title', 'themerex') => 'title',
				__('Image', 'themerex') => 'image',
				__('Price', 'themerex') => 'price',
				__('Footer', 'themerex') => 'footer',
				__('United', 'themerex') => 'united'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Content", "themerex"),
            "description" => __("Current cell content", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		array(
			"param_name" => "money",
			"heading" => __("Money", "themerex"),
            "description" => __("Money value (dot or comma separated)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "currency",
			"heading" => __("Currency symbol", "themerex"),
            "description" => __("Currency character", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "$",
			"type" => "textfield"
        ),
		array(
			"param_name" => "period",
			"heading" => __("Period", "themerex"),
            "description" => __("Period text (if need). For example: monthly, daily, etc.", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "image",
			"heading" => __("URL for image file", "themerex"),
            "description" => __("Select image to fill cell", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'
) );

//class WPBakeryShortCode_Trx_Price_Table extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_Price_Item extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_Price_Data extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Price_Table extends THEMEREX_VC_ShortCodeColumns {}
class WPBakeryShortCode_Trx_Price_Item extends THEMEREX_VC_ShortCodeCollection {}
class WPBakeryShortCode_Trx_Price_Data extends THEMEREX_VC_ShortCodeContainer {}







// Quote
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_quote",
	"name" => __("Quote", "themerex"),
	"description" => __("Quote text", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_quote',
	"class" => "trx_sc_container trx_sc_quote",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "cite",
			"heading" => __("Quote cite", "themerex"),
            "description" => __("URL for the quote cite link", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "title",
			"heading" => __("Title (author)", "themerex"),
            "description" => __("Quote title (author name)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Quote content", "themerex"),
            "description" => __("Quote content", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'
) );

//class WPBakeryShortCode_Trx_Quote extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Quote extends THEMEREX_VC_ShortCodeContainer {}







// Section
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_section",
	"name" => __("Section container", "themerex"),
	"description" => __("Container for any block ([block] analog - to enable nesting)", "themerex"),
	"category" => __('Content', 'js_composer'),
	"class" => "trx_sc_collection trx_sc_section",
    'icon' => 'icon_trx_block',
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "color",
			"heading" => __("Fore color", "themerex"),
            "description" => __("Any color for the text in this section", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "bg_color",
			"heading" => __("Background color", "themerex"),
            "description" => __("Any background color for this section", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "bg_image",
			"heading" => __("Background image", "themerex"),
            "description" => __("Select background image from library for this section", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "bg_tint",
			"heading" => __("Background tint", "themerex"),
            "description" => __("Main background tint: dark or light", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_tint),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "dedicated",
			"heading" => __("Dedicated", "themerex"),
            "description" => __("Use this block as dedicated content - show it before post title on single page", "themerex"),
			"class" => "",
			"value" => array(__('Use as dedicated content', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Alignment", "themerex"),
            "description" => __("Select block alignment", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__("None", 'themerex') => "none",
				__("Left", 'themerex') => "left",
				__("Right", 'themerex') => "right"
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "columns",
			"heading" => __("Columns emulation", "themerex"),
            "description" => __("Select width for columns emulation", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_columns),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "scroll",
			"heading" => __("Use scroller", "themerex"),
            "description" => __("Use scroller to show block content", "themerex"),
			"group" => __('Scroll', 'themerex'),
			"class" => "",
			"value" => array(__('Content scroller', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "dir",
			"heading" => __("Scroll direction", "themerex"),
            "description" => __("Scroll direction (if Use scroller = yes)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"group" => __('Scroll', 'themerex'),
			"value" => array_flip($THEMEREX_shortcodes_dir),
	        'dependency' => array(
				'element' => 'scroll',
				'not_empty' => true
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Container content", "themerex"),
            "description" => __("Content for section container", "themerex"),
			"class" => "",
			"value" => "",
			//"holder" => "div",
			"type" => "textarea_html"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class,
		$THEMEREX_VC_style
    )
) );

//class WPBakeryShortCode_Trx_Section extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Section extends THEMEREX_VC_ShortCodeCollection {}







// Skills
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_skills",
	"name" => __("Skills", "themerex"),
	"description" => __("Insert skills diagramm", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_skills',
	"class" => "trx_sc_collection trx_sc_skills",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_skills_item'),
	"params" => array(
		array(
			"param_name" => "maximum",
			"heading" => __("Max value", "themerex"),
            "description" => __("Max value for skills items", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "100",
			"type" => "textfield"
        ),
		array(
			"param_name" => "count",
			"heading" => __("Items count", "themerex"),
            "description" => __("Skills items count (required)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "2",
			"type" => "textfield"
        ),
		array(
			"param_name" => "title",
			"heading" => __("Title", "themerex"),
            "description" => __("Title of the skills block", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "subtitle",
			"heading" => __("Subtitle", "themerex"),
            "description" => __("Default subtitle of the skills block (only if type=arc)", "themerex"),
	        'dependency' => array(
				'element' => 'type',
				'value' => array('arc')
			),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Alignment", "themerex"),
            "description" => __("Align skills block to left or right side", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_float),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Skills items color", "themerex"),
            "description" => __("Color for all skills items", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "type",
			"heading" => __("Skills type", "themerex"),
            "description" => __("Select type of skills block", "themerex"),
			"admin_label" => true,
			"group" => __('Style', 'themerex'),
			"class" => "",
			"value" => array(
				__('Bar', 'themerex') => 'bar',
				__('Pie chart', 'themerex') => 'pie',
				__('Counter', 'themerex') => 'counter',
				__('Arc', 'themerex') => 'arc'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "style",
			"heading" => __("Skills style", "themerex"),
            "description" => __("Select style of skills items (only for type=counter)", "themerex"),
			"admin_label" => true,
			"group" => __('Style', 'themerex'),
			"class" => "",
			"value" => array(
				__('Style 1', 'themerex') => '1',
				__('Style 2', 'themerex') => '2',
				__('Style 3', 'themerex') => '3',
				__('Style 4', 'themerex') => '4'
			),
	        'dependency' => array(
				'element' => 'type',
				'value' => array('counter')
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "dir",
			"heading" => __("Direction", "themerex"),
            "description" => __("Select direction of skills block", "themerex"),
			"admin_label" => true,
			"group" => __('Style', 'themerex'),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_dir),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "layout",
			"heading" => __("Skills layout", "themerex"),
            "description" => __("Select layout of skills block", "themerex"),
			"admin_label" => true,
			"group" => __('Style', 'themerex'),
	        'dependency' => array(
				'element' => 'type',
				'value' => array('counter','bar','pie')
			),
			"class" => "",
			"value" => array(
				__('Rows', 'themerex') => 'rows',
				__('Columns', 'themerex') => 'columns'
			),
			"type" => "dropdown"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );


vc_map( array(
	"base" => "trx_skills_item",
	"name" => __("Skill", "themerex"),
	"description" => __("Skills item", "themerex"),
	"show_settings_on_create" => true,
	"class" => "trx_sc_single trx_sc_skills_item",
	"content_element" => true,
	"is_container" => false,
	"as_child" => array('only' => 'trx_skills'),
	"as_parent" => array('except' => 'trx_skills'),
	"params" => array(
		array(
            "param_name" => "title",
            "heading" => __("Title", "themerex"),
            "description" => __("Title for the current skills item", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "level",
            "heading" => __("Level", "themerex"),
            "description" => __("Level value for the current skills item", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "50",
			"type" => "textfield"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Color", "themerex"),
            "description" => __("Color for current skills item", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "style",
			"heading" => __("Item style", "themerex"),
            "description" => __("Select style for the current skills item (only for type=counter)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Style 1', 'themerex') => '1',
				__('Style 2', 'themerex') => '2',
				__('Style 3', 'themerex') => '3',
				__('Style 4', 'themerex') => '4'
			),
			"type" => "dropdown"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Skills extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_Skills_Item extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Skills extends THEMEREX_VC_ShortCodeCollection {}
class WPBakeryShortCode_Trx_Skills_Item extends THEMEREX_VC_ShortCodeSingle {}







// Slider
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_slider",
	"name" => __("Slider", "themerex"),
	"description" => __("Insert slider", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_slider',
	"class" => "trx_sc_collection trx_sc_slider",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_slider_item'),
	"params" => array_merge(array(
		array(
			"param_name" => "engine",
			"heading" => __("Engine", "themerex"),
            "description" => __("Select engine for slider. Attention! Flex and Swiper are built-in engines, all other engines appears only if corresponding plugings are installed", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_sliders),
			"type" => "dropdown"
        )),
		revslider_exists() || royalslider_exists() ? array(
		array(
			"param_name" => "interval",
			"heading" => __("Flex&Swiper: Slides change interval", "themerex"),
            "description" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "themerex"),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => "5000",
			"type" => "textfield"
        ),
		array(
			"param_name" => "alias",
			"heading" => __("Revolution slider alias or Royal Slider ID", "themerex"),
            "description" => __("Alias for Revolution slider or Royal slider ID", "themerex"),
			"admin_label" => true,
			"class" => "",
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('revo','royal')
			),
			"value" => "",
			"type" => "textfield"
        )) : array(), array(
		array(
			"param_name" => "cat",
			"heading" => __("Flex&Swiper: Category list", "themerex"),
            "description" => __("Comma separated list of category slugs. If empty - select posts from any category or from IDs list", "themerex"),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "count",
			"heading" => __("Flex&Swiper: Number of posts", "themerex"),
            "description" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "themerex"),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => "3",
			"type" => "textfield"
        ),
		array(
			"param_name" => "offset",
			"heading" => __("Flex&Swiper: Offset before select posts", "themerex"),
            "description" => __("Skip posts before select next part.", "themerex"),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => "0",
			"type" => "textfield"
        ),
		array(
			"param_name" => "orderby",
			"heading" => __("Flex&Swiper: Post sorting", "themerex"),
            "description" => __("Select desired posts sorting method", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_sorting),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "order",
			"heading" => __("Flex&Swiper: Post order", "themerex"),
            "description" => __("Select desired posts order", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_ordering),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "ids",
			"heading" => __("Flex&Swiper: Post IDs list", "themerex"),
            "description" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "themerex"),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "controls",
			"heading" => __("Flex&Swiper: Show slider controls", "themerex"),
            "description" => __("Show arrows inside slider", "themerex"),
			"group" => __('Details', 'themerex'),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => array(__('Show controls', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "pagination",
			"heading" => __("Flex&Swiper: Show slider pagination", "themerex"),
            "description" => __("Show bullets or titles to switch slides", "themerex"),
			"group" => __('Details', 'themerex'),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => array(
					__('Dots', 'themerex') => 'yes', 
					__('Side Titles', 'themerex') => 'full',
					__('Over Titles', 'themerex') => 'over',
					__('None', 'themerex') => 'no'
				),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "titles",
			"heading" => __("Flex&Swiper: Show titles section", "themerex"),
            "description" => __("Show section with post's title and short post's description", "themerex"),
			"group" => __('Details', 'themerex'),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => array(
					__('Not show', 'themerex') => "no",
					__('Show/Hide info', 'themerex') => "slide",
					__('Fixed info', 'themerex') => "fixed"
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "descriptions",
			"heading" => __("Flex&Swiper: Post descriptions", "themerex"),
            "description" => __("Show post's excerpt max length (characters)", "themerex"),
			"group" => __('Details', 'themerex'),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => "0",
			"type" => "textfield"
        ),
		array(
			"param_name" => "links",
			"heading" => __("Flex&Swiper: Post's title as link", "themerex"),
            "description" => __("Make links from post's titles", "themerex"),
			"group" => __('Details', 'themerex'),
	        'dependency' => array(
				'element' => 'engine',
				'value' => array('flex','swiper')
			),
			"class" => "",
			"value" => array(__('Titles as a links', 'themerex') => 'yes'),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "border",
			"heading" => __("Show border", "themerex"),
            "description" => __("Show border around slider", "themerex"),
			"group" => __('Details', 'themerex'),
			"class" => "",
			"value" => array(
					__('No border', 'themerex') => "none",
					__('Light tablet', 'themerex') => "light",
					__('Dark tablet', 'themerex') => "dark"
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Float slider", "themerex"),
            "description" => __("Float slider to left or right side", "themerex"),
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_float),
			"type" => "dropdown"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ))
) );


vc_map( array(
	"base" => "trx_slider_item",
	"name" => __("Slide", "themerex"),
	"description" => __("Slider item - single slide", "themerex"),
	"show_settings_on_create" => true,
	"content_element" => true,
	"is_container" => false,
	"as_child" => array('only' => 'trx_slider'),
	"as_parent" => array('except' => 'trx_slider'),
	"params" => array(
		array(
			"param_name" => "src",
			"heading" => __("URL (source) for image file", "themerex"),
            "description" => __("Select or upload image or write URL from other site for the current slide", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Slider extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_Slider_Item extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Slider extends THEMEREX_VC_ShortCodeCollection {}
class WPBakeryShortCode_Trx_Slider_Item extends THEMEREX_VC_ShortCodeSingle {}







// Table
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_table",
	"name" => __("Table", "themerex"),
	"description" => __("Insert a table", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_table',
	"class" => "trx_sc_container trx_sc_table",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Table style", "themerex"),
            "description" => __("Select table style", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Style 1', 'themerex') => '1',
				__('Style 2', 'themerex') => '2'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "size",
			"heading" => __("Cells padding", "themerex"),
            "description" => __("Select padding for the table cells", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Small', 'themerex') => 'small',
				__('Medium', 'themerex') => 'medium',
				__('Big', 'themerex') => 'big'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Cells content alignment", "themerex"),
            "description" => __("Select alignment for each table cell", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('None', 'themerex') => 'none',
				__('Left', 'themerex') => 'left',
				__('Center', 'themerex') => 'center',
				__('Right', 'themerex') => 'right'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Table content", "themerex"),
            "description" => __("Content, created with any table-generator", "themerex"),
			"class" => "",
			"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'
) );

//class WPBakeryShortCode_Trx_Table extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Table extends THEMEREX_VC_ShortCodeContainer {}







// Tabs
//-------------------------------------------------------------------------------------

$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
vc_map( array(
	"base" => "trx_tabs",
	"name" => __("Tabs", "themerex"),
	"description" => __("Tabs", "themerex"),
	"category" => __('Content', 'js_composer'),
	'icon' => 'icon_trx_tabs',
	"class" => "trx_sc_collection trx_sc_tabs",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_tab'),
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Tabs style", "themerex"),
			"description" => __("Select style of tabs items", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Style 1', 'themerex') => '1',
				__('Style 2', 'themerex') => '2'
			),
			"type" => "dropdown"
		),
		array(
			"param_name" => "initial",
			"heading" => __("Initially opened tab", "themerex"),
			"description" => __("Number of initially opened tab", "themerex"),
			"class" => "",
			"value" => 1,
			"type" => "textfield"
		),
		array(
			"param_name" => "scroll",
			"heading" => __("Scroller", "themerex"),
			"description" => __("Use scroller to show tab content (height parameter required)", "themerex"),
			"class" => "",
			"value" => array("Use scroller" => "yes" ),
			"type" => "checkbox"
		),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
	),
	'default_content' => '
		[trx_tab title="' . __( 'Tab 1', 'themerex' ) . '" tab_id="'.$tab_id_1.'"][/trx_tab]
		[trx_tab title="' . __( 'Tab 2', 'themerex' ) . '" tab_id="'.$tab_id_2.'"][/trx_tab]
	',
	"custom_markup" => '
		<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
			<ul class="tabs_controls">
			</ul>
			%content%
		</div>
	',
	'js_view' => 'VcTrxTabsView'
) );


vc_map( array(
	"base" => "trx_tab",
	"name" => __("Tab item", "themerex"),
	"description" => __("Single tab item", "themerex"),
	"show_settings_on_create" => true,
	"class" => "trx_sc_collection trx_sc_tab",
	"content_element" => true,
	"is_container" => true,
	"as_child" => array('only' => 'trx_tabs'),
	"as_parent" => array('except' => 'trx_tabs'),
	"params" => array(
		array(
			"param_name" => "title",
			"heading" => __("Tab title", "themerex"),
			"description" => __("Title for current tab", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
		),
		array(
			"param_name" => "tab_id",
			"heading" => __("Tab ID", "themerex"),
			"description" => __("ID for current tab (required). Please, start it from letter.", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
		),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
	),
  'js_view' => 'VcTrxTabView'
) );
//class WPBakeryShortCode_Trx_Tabs extends WPBakeryShortCode_VC_Tabs {}
//class WPBakeryShortCode_Trx_Tab extends WPBakeryShortCode_VC_Tab {}
class WPBakeryShortCode_Trx_Tabs extends THEMEREX_VC_ShortCodeTabs {}
class WPBakeryShortCode_Trx_Tab extends THEMEREX_VC_ShortCodeTab {}




// Team
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_team",
	"name" => __("Team", "themerex"),
	"description" => __("Insert team members", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_team',
	"class" => "trx_sc_columns trx_sc_team",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_team_item'),
	"params" => array(
		array(
            "param_name" => "count",
            "heading" => __("Team members number", "themerex"),
            "description" => __("Number of the team members in this list.", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "2",
			"type" => "textfield"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
    'default_content' => '
	    [trx_team_item user="' . __( 'Member 1', 'themerex' ) . '"][/trx_team_item]
    	[trx_team_item user="' . __( 'Member 2', 'themerex' ) . '"][/trx_team_item]
    ',
	'js_view' => 'VcTrxColumnsView'
) );


vc_map( array(
	"base" => "trx_team_item",
	"name" => __("Team member", "themerex"),
	"description" => __("Team member - all data pull out from it account on your site", "themerex"),
	"show_settings_on_create" => true,
	"class" => "trx_sc_item trx_sc_column_item trx_sc_team_item",
	"content_element" => true,
	"is_container" => false,
	"as_child" => array('only' => 'trx_team'),
	"as_parent" => array('except' => 'trx_team'),
	"params" => array(
		array(
            "param_name" => "user",
            "heading" => __("Team member", "themerex"),
            "description" => __("Select one of registered users", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_users),
			"type" => "dropdown"
        ),
		array(
            "param_name" => "name",
            "heading" => __("Member's name", "themerex"),
            "description" => __("Team member's name", "themerex"),
			"admin_label" => true,
	        'dependency' => array(
				'element' => 'user',
				'value' => array('none')
			),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "position",
            "heading" => __("Position", "themerex"),
            "description" => __("Team member's position", "themerex"),
			"admin_label" => true,
	        'dependency' => array(
				'element' => 'user',
				'value' => array('none')
			),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "email",
            "heading" => __("E-mail", "themerex"),
            "description" => __("Team member's e-mail", "themerex"),
	        'dependency' => array(
				'element' => 'user',
				'value' => array('none')
			),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "photo",
			"heading" => __("Member's Photo", "themerex"),
            "description" => __("Team member's photo (avatar", "themerex"),
	        'dependency' => array(
				'element' => 'user',
				'value' => array('none')
			),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
            "param_name" => "socials",
            "heading" => __("Socials", "themerex"),
            "description" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "themerex"),
	        'dependency' => array(
				'element' => 'user',
				'value' => array('none')
			),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Team extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_Team_Item extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Team extends THEMEREX_VC_ShortCodeColumns {}
class WPBakeryShortCode_Trx_Team_Item extends THEMEREX_VC_ShortCodeItem {}







// Testimonials
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_testimonials",
	"name" => __("Testimonials", "themerex"),
	"description" => __("Insert testimonials slider", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_testimonials',
	"class" => "trx_sc_collection trx_sc_testimonials",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_testimonials_item'),
	"params" => array(
		array(
            "param_name" => "title",
            "heading" => __("Title", "themerex"),
            "description" => __("Title of testimonmials block", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "style",
			"heading" => __("Style", "themerex"),
            "description" => __("Select testimonials style", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Style 1', 'themerex') => '1',
				__('Style 2', 'themerex') => '2',
				__('Style 3', 'themerex') => '3'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "controls",
			"heading" => __("Controls", "themerex"),
            "description" => __("Show control buttons. Values 'top' and 'bottom' allowed only for Style 1", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
					__('Off', 'themerex') => "off",
					__('Over', 'themerex') => "on",
					__('Top', 'themerex') => "top",
					__('Bottom', 'themerex') => "bottom"
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "interval",
			"heading" => __("Testimonials change interval", "themerex"),
            "description" => __("Testimonials change interval (in milliseconds: 1000ms = 1s)", "themerex"),
			"class" => "",
			"value" => "7000",
			"type" => "textfield"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );


vc_map( array(
	"base" => "trx_testimonials_item",
	"name" => __("Testimonial", "themerex"),
	"description" => __("Single testimonials item", "themerex"),
	"show_settings_on_create" => true,
	"class" => "trx_sc_container trx_sc_testimonials_item",
	"content_element" => true,
	"is_container" => true,
	"as_child" => array('only' => 'trx_testimonials'),
	"as_parent" => array('except' => 'trx_testimonials'),
	"params" => array(
		array(
            "param_name" => "name",
            "heading" => __("Name", "themerex"),
            "description" => __("Name of the testimonmials author", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "position",
            "heading" => __("Position", "themerex"),
            "description" => __("Position of the testimonmials author", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
            "param_name" => "email",
            "heading" => __("E-mail", "themerex"),
            "description" => __("E-mail of the testimonmials author", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "photo",
			"heading" => __("Photo", "themerex"),
            "description" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "content",
			"heading" => __("Testimonials text", "themerex"),
            "description" => __("Current testimonials text", "themerex"),
			"class" => "",
			"value" => "",
			/*"holder" => "div",*/
			"type" => "textarea_html"
        ),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextContainerView'
) );

//class WPBakeryShortCode_Trx_Testimonials extends WPBakeryShortCodesContainer {}
//class WPBakeryShortCode_Trx_Testimonials_Item extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Testimonials extends THEMEREX_VC_ShortCodeColumns {}
class WPBakeryShortCode_Trx_Testimonials_Item extends THEMEREX_VC_ShortCodeContainer {}







// Title
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_title",
	"name" => __("Title", "themerex"),
	"description" => __("Create header tag (1-6 level) with many styles", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_title',
	"class" => "trx_sc_single trx_sc_title",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "content",
			"heading" => __("Title content", "themerex"),
            "description" => __("Title content", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textarea_html"
        ),
		array(
			"param_name" => "type",
			"heading" => __("Title type", "themerex"),
            "description" => __("Title type (header level)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Header 1', 'themerex') => '1',
				__('Header 2', 'themerex') => '2',
				__('Header 3', 'themerex') => '3',
				__('Header 4', 'themerex') => '4',
				__('Header 5', 'themerex') => '5',
				__('Header 6', 'themerex') => '6'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "style",
			"heading" => __("Title style", "themerex"),
            "description" => __("Title style: only text (regular) or with icon/image (iconed)", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
				__('Regular', 'themerex') => 'regular',
				__('Underline', 'themerex') => 'underline',
				__('Divider', 'themerex') => 'divider',
				__('With icon (image)', 'themerex') => 'iconed'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Alignment", "themerex"),
            "description" => __("Title text alignment", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_align),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "color",
			"heading" => __("Title color", "themerex"),
            "description" => __("Select color for the title", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		array(
			"param_name" => "weight",
			"heading" => __("Font weight", "themerex"),
            "description" => __("Title font weight", "themerex"),
			"class" => "",
			"value" => array(
				__('Default', 'themerex') => 'inherit',
				__('Thin (100)', 'themerex') => '100',
				__('Light (300)', 'themerex') => '300',
				__('Normal (400)', 'themerex') => '400',
				__('Bold (700)', 'themerex') => '700'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "icon",
			"heading" => __("Title font icon", "themerex"),
            "description" => __("Select font icon for the title from Fontello icons set (if style=iconed)", "themerex"),
			"class" => "",
			"group" => __('Icon &amp; Image', 'themerex'),
	        'dependency' => array(
				'element' => 'style',
				'value' => array('iconed')
			),
			"value" => "",
			"value" => $THEMEREX_shortcodes_icons,
			"type" => "dropdown"
        ),
		array(
			"param_name" => "image",
			"heading" => __("or image icon", "themerex"),
            "description" => __("Select image icon for the title instead icon above (if style=iconed)", "themerex"),
			"class" => "",
			"group" => __('Icon &amp; Image', 'themerex'),
	        'dependency' => array(
				'element' => 'style',
				'value' => array('iconed')
			),
			"value" => $THEMEREX_shortcodes_images,
			"type" => "dropdown"
        ),
		array(
			"param_name" => "picture",
			"heading" => __("or select uploaded image", "themerex"),
            "description" => __("Select or upload image or write URL from other site (if style=iconed)", "themerex"),
			"group" => __('Icon &amp; Image', 'themerex'),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "size",
			"heading" => __("Icon (image) size", "themerex"),
            "description" => __("Select icon (image) size (if style=iconed)", "themerex"),
			"group" => __('Icon &amp; Image', 'themerex'),
			"class" => "",
			"value" => array(
				__('Small', 'themerex') => 'small',
				__('Medium', 'themerex') => 'medium',
				__('Large', 'themerex') => 'large',
				__('Huge', 'themerex') => 'huge'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "position",
			"heading" => __("Icon (image) position", "themerex"),
            "description" => __("Select icon (image) position (if style=iconed)", "themerex"),
			"group" => __('Icon &amp; Image', 'themerex'),
			"class" => "",
			"value" => array(
				__('Top', 'themerex') => 'top',
				__('Left', 'themerex') => 'left',
				__('Right', 'themerex') => 'right'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "background",
			"heading" => __("Show background under icon", "themerex"),
            "description" => __("Select background under icon (if style=iconed)", "themerex"),
			"group" => __('Icon &amp; Image', 'themerex'),
			"class" => "",
	        'dependency' => array(
				'element' => 'style',
				'value' => array('iconed')
			),
			"value" => array(
				__('None', 'themerex') => 'none',
				__('Square', 'themerex') => 'square',
				__('Circle', 'themerex') => 'circle'
			),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "bg_color",
			"heading" => __("Background color", "themerex"),
            "description" => __("Icon's background color (if style=iconed)", "themerex"),
			"group" => __('Icon &amp; Image', 'themerex'),
	        'dependency' => array(
				'element' => 'style',
				'value' => array('iconed')
			),
			"class" => "",
			"value" => "",
			"type" => "colorpicker"
        ),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
	'js_view' => 'VcTrxTextView'
) );

//class WPBakeryShortCode_Trx_Title extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Title extends THEMEREX_VC_ShortCodeSingle {}







// Toggles
//-------------------------------------------------------------------------------------
	
vc_map( array(
	"base" => "trx_toggles",
	"name" => __("Toggles", "themerex"),
	"description" => __("Toggles items", "themerex"),
	"category" => __('Content', 'js_composer'),
	'icon' => 'icon_trx_toggles',
	"class" => "trx_sc_collection trx_sc_toggles",
	"content_element" => true,
	"is_container" => true,
	"show_settings_on_create" => true,
	"as_parent" => array('only' => 'trx_toggles_item'),
	"params" => array(
		array(
			"param_name" => "style",
			"heading" => __("Toggles style", "themerex"),
			"description" => __("Select style for display toggles", "themerex"),
			"class" => "",
			"admin_label" => true,
			"value" => array(
				__('Style 1', 'themerex') => 1,
				__('Style 2', 'themerex') => 2,
				__('Style 3', 'themerex') => 3
			),
			"type" => "dropdown"
		),
		array(
			"param_name" => "counter",
			"heading" => __("Counter", "themerex"),
			"description" => __("Display counter before each toggles title", "themerex"),
			"class" => "",
			"value" => array("Add item numbers before each element" => "on" ),
			"type" => "checkbox"
		),
		array(
			"param_name" => "large",
			"heading" => __("Large titles", "themerex"),
			"description" => __("Show large titles", "themerex"),
			"class" => "",
			"value" => array("Show large titles" => "on"),
			"type" => "checkbox"
		),
		array(
			"param_name" => "shadow",
			"heading" => __("Shadow", "themerex"),
			"description" => __("Display shadow under toggles block", "themerex"),
			"class" => "",
			"value" => array("Display shadow" => "on" ),
			"type" => "checkbox"
		),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
	),
	'default_content' => '
		[trx_toggles_item title="' . __( 'Item 1 title', 'themerex' ) . '"][/trx_toggles_item]
		[trx_toggles_item title="' . __( 'Item 2 title', 'themerex' ) . '"][/trx_toggles_item]
	',
	"custom_markup" => '
		<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
			%content%
		</div>
		<div class="tab_controls">
			<button class="add_tab" title="'.__("Add item", "themerex").'">'.__("Add item", "themerex").'</button>
		</div>
	',
	'js_view' => 'VcTrxTogglesView'
) );


vc_map( array(
	"base" => "trx_toggles_item",
	"name" => __("Toggles item", "themerex"),
	"description" => __("Single toggles item", "themerex"),
	"show_settings_on_create" => true,
	"content_element" => true,
	"is_container" => true,
	"as_child" => array('only' => 'trx_toggles'),
	"as_parent" => array('except' => 'trx_toggles'),
	"params" => array(
		array(
			"param_name" => "title",
			"heading" => __("Title", "themerex"),
			"description" => __("Title for current toggles item", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => "",
			"type" => "textfield"
		),
		array(
			"param_name" => "open",
			"heading" => __("Open on show", "themerex"),
			"description" => __("Open current toggle item on show", "themerex"),
			"class" => "",
			"value" => array("Opened" => "yes" ),
			"type" => "checkbox"
		),
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
	),
    'js_view' => 'VcTrxTogglesTabView'
) );
//class WPBakeryShortCode_Trx_Toggles extends WPBakeryShortCode_VC_Accordion {}
//class WPBakeryShortCode_Trx_Toggles_Item extends WPBakeryShortCode_VC_Accordion_tab {}
class WPBakeryShortCode_Trx_Toggles extends THEMEREX_VC_ShortCodeToggles {}
class WPBakeryShortCode_Trx_Toggles_Item extends THEMEREX_VC_ShortCodeTogglesItem {}






// Twitter
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_twitter",
	"name" => __("Twitter", "themerex"),
	"description" => __("Insert twitter feed into post (page)", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_twitter',
	"class" => "trx_sc_single trx_sc_twitter",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "user",
			"heading" => __("Twitter Username", "themerex"),
            "description" => __("Your username in the twitter account. If empty - get it from Theme Options.", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "consumer_key",
			"heading" => __("Consumer Key", "themerex"),
            "description" => __("Consumer Key from the twitter account", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "consumer_secret",
			"heading" => __("Consumer Secret", "themerex"),
            "description" => __("Consumer Secret from the twitter account", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "token_key",
			"heading" => __("Token Key", "themerex"),
            "description" => __("Token Key from the twitter account", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "token_secret",
			"heading" => __("Token Secret", "themerex"),
            "description" => __("Token Secret from the twitter account", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "count",
			"heading" => __("Tweets number", "themerex"),
            "description" => __("Tweets number to show", "themerex"),
			"class" => "",
			"value" => 3,
			"type" => "textfield"
        ),
		array(
			"param_name" => "interval",
			"heading" => __("Tweets change interval", "themerex"),
            "description" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "themerex"),
			"class" => "",
			"value" => 7000,
			"type" => "textfield"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    ),
) );

class WPBakeryShortCode_Trx_Twitter extends THEMEREX_VC_ShortCodeSingle {}







// Video
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_video",
	"name" => __("Video", "themerex"),
	"description" => __("Insert video player", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_video',
	"class" => "trx_sc_single trx_sc_video",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "url",
			"heading" => __("URL for video file", "themerex"),
            "description" => __("Paste URL for video file", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "textfield"
        ),
		array(
			"param_name" => "autoplay",
			"heading" => __("Autoplay video", "themerex"),
            "description" => __("Autoplay video on page load", "themerex"),
			"class" => "",
			"value" => array("Autoplay" => "on" ),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "title",
			"heading" => __("Show title bar", "themerex"),
            "description" => __("Show title bar above video frame", "themerex"),
			"class" => "",
			"value" => array("Show title bar" => "on" ),
			"type" => "checkbox"
        ),
		array(
			"param_name" => "image",
			"heading" => __("Cover image", "themerex"),
            "description" => __("Select or upload image or write URL from other site for video preview", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Video extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Video extends THEMEREX_VC_ShortCodeSingle {}







// Zoom
//-------------------------------------------------------------------------------------

vc_map( array(
	"base" => "trx_zoom",
	"name" => __("Zoom", "themerex"),
	"description" => __("Insert the image with zoom/lens effect", "themerex"),
	"category" => __('Content', 'js_composer'),
    'icon' => 'icon_trx_zoom',
	"class" => "trx_sc_single trx_sc_zoom",
	"content_element" => true,
	"is_container" => false,
	"show_settings_on_create" => true,
	"params" => array(
		array(
			"param_name" => "url",
			"heading" => __("Main image", "themerex"),
            "description" => __("Select or upload main image", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "over",
			"heading" => __("Overlaping image", "themerex"),
            "description" => __("Select or upload overlaping image", "themerex"),
			"class" => "",
			"value" => "",
			"type" => "attach_image"
        ),
		array(
			"param_name" => "border",
			"heading" => __("Show border", "themerex"),
            "description" => __("Show border around image", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array(
					__('No border', 'themerex') => "none",
					__('Light tablet', 'themerex') => "light",
					__('Dark tablet', 'themerex') => "dark"
				),
			"type" => "dropdown"
        ),
		array(
			"param_name" => "align",
			"heading" => __("Alignment", "themerex"),
            "description" => __("Float zoom to left or right side", "themerex"),
			"admin_label" => true,
			"class" => "",
			"value" => array_flip($THEMEREX_shortcodes_float),
			"type" => "dropdown"
        ),
		THEMEREX_VC_width(),
		THEMEREX_VC_height(),
		$THEMEREX_VC_margin_top,
		$THEMEREX_VC_margin_bottom,
		$THEMEREX_VC_margin_left,
		$THEMEREX_VC_margin_right,
		$THEMEREX_VC_id,
		$THEMEREX_VC_class
    )
) );

//class WPBakeryShortCode_Trx_Zoom extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_Trx_Zoom extends THEMEREX_VC_ShortCodeSingle {}




if (function_exists('is_woocommerce')) {

	// WooCommerce - Cart
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "woocommerce_cart",
		"name" => __("Cart", "themerex"),
		"description" => __("WooCommerce shortcode: show cart page", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_wooc_cart',
		"class" => "trx_sc_alone trx_sc_woocommerce_cart",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => false,
		"params" => array()
	) );
	
	class WPBakeryShortCode_Woocommerce_Cart extends THEMEREX_VC_ShortCodeAlone {}


	// WooCommerce - Checkout
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "woocommerce_checkout",
		"name" => __("Checkout", "themerex"),
		"description" => __("WooCommerce shortcode: show checkout page", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_wooc_checkout',
		"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => false,
		"params" => array()
	) );
	
	class WPBakeryShortCode_Woocommerce_Checkout extends THEMEREX_VC_ShortCodeAlone {}


	// WooCommerce - My Account
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "woocommerce_my_account",
		"name" => __("My Account", "themerex"),
		"description" => __("WooCommerce shortcode: show my account page", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_wooc_my_account',
		"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => false,
		"params" => array()
	) );
	
	class WPBakeryShortCode_Woocommerce_My_Account extends THEMEREX_VC_ShortCodeAlone {}


	// WooCommerce - Order Tracking
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "woocommerce_order_tracking",
		"name" => __("Order Tracking", "themerex"),
		"description" => __("WooCommerce shortcode: show order tracking page", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_wooc_order_tracking',
		"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => false,
		"params" => array()
	) );
	
	class WPBakeryShortCode_Woocommerce_Order_Tracking extends THEMEREX_VC_ShortCodeAlone {}


	// WooCommerce - Shop Messages
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "shop_messages",
		"name" => __("Shop Messages", "themerex"),
		"description" => __("WooCommerce shortcode: show shop messages", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_wooc_shop_messages',
		"class" => "trx_sc_alone trx_sc_shop_messages",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => false,
		"params" => array()
	) );
	
	class WPBakeryShortCode_Shop_Messages extends THEMEREX_VC_ShortCodeAlone {}


	// WooCommerce - Product Page
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "product_page",
		"name" => __("Product Page", "themerex"),
		"description" => __("WooCommerce shortcode: display single product page", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_product_page',
		"class" => "trx_sc_single trx_sc_product_page",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "sku",
				"heading" => __("SKU", "themerex"),
				"description" => __("SKU code of displayed product", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "id",
				"heading" => __("ID", "themerex"),
				"description" => __("ID of displayed product", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "posts_per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "1",
				"type" => "textfield"
			),
			array(
				"param_name" => "post_type",
				"heading" => __("Post type", "themerex"),
				"description" => __("Post type to output (leave 'product')", "themerex"),
				"class" => "",
				"value" => "product",
				"type" => "textfield"
			),
			array(
				"param_name" => "post_status",
				"heading" => __("Post status", "themerex"),
				"description" => __("Display posts only with this status", "themerex"),
				"class" => "",
				"value" => array(
					__('Published', 'themerex') => 'published',
					__('Protected', 'themerex') => 'protected',
					__('Private', 'themerex') => 'private',
					__('Pending', 'themerex') => 'pending',
					__('Draft', 'themerex') => 'draft'
				),
				"type" => "dropdown"
			)
		)
	) );
	
	class WPBakeryShortCode_Product_Page extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Product
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "product",
		"name" => __("Product", "themerex"),
		"description" => __("WooCommerce shortcode: display one product", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_product',
		"class" => "trx_sc_single trx_sc_product",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "sku",
				"heading" => __("SKU", "themerex"),
				"description" => __("Product's SKU code", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "id",
				"heading" => __("ID", "themerex"),
				"description" => __("Product's ID", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			)
		)
	) );
	
	class WPBakeryShortCode_Product extends THEMEREX_VC_ShortCodeSingle {}


	// WooCommerce - Best Selling Products
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "best_selling_products",
		"name" => __("Best Selling Products", "themerex"),
		"description" => __("WooCommerce shortcode: show best selling products", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_best_selling_products',
		"class" => "trx_sc_single trx_sc_best_selling_products",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			)
		)
	) );
	
	class WPBakeryShortCode_Best_Selling_Products extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Recent Products
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "recent_products",
		"name" => __("Recent Products", "themerex"),
		"description" => __("WooCommerce shortcode: show recent products", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_recent_products',
		"class" => "trx_sc_single trx_sc_recent_products",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			)
		)
	) );
	
	class WPBakeryShortCode_Recent_Products extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Related Products
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "related_products",
		"name" => __("Related Products", "themerex"),
		"description" => __("WooCommerce shortcode: show related products", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_related_products',
		"class" => "trx_sc_single trx_sc_related_products",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "posts_per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "rand",
				"type" => "textfield"
			)
		)
	) );
	
	class WPBakeryShortCode_Related_Products extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Featured Products
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "featured_products",
		"name" => __("Featured Products", "themerex"),
		"description" => __("WooCommerce shortcode: show featured products", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_featured_products',
		"class" => "trx_sc_single trx_sc_featured_products",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			)
		)
	) );
	
	class WPBakeryShortCode_Featured_Products extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Top Rated Products
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "top_rated_products",
		"name" => __("Top Rated Products", "themerex"),
		"description" => __("WooCommerce shortcode: show top rated products", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_top_rated_products',
		"class" => "trx_sc_single trx_sc_top_rated_products",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			)
		)
	) );
	
	class WPBakeryShortCode_Top_Rated_Products extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Sale Products
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "sale_products",
		"name" => __("Sale Products", "themerex"),
		"description" => __("WooCommerce shortcode: list products on sale", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_sale_products',
		"class" => "trx_sc_single trx_sc_sale_products",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			)
		)
	) );
	
	class WPBakeryShortCode_Sale_Products extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Product Category
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "product_category",
		"name" => __("Products from category", "themerex"),
		"description" => __("WooCommerce shortcode: list products in specified category(-ies)", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_product_category',
		"class" => "trx_sc_single trx_sc_product_category",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			),
			array(
				"param_name" => "category",
				"heading" => __("Category slugs", "themerex"),
				"description" => __("Comma separated category slugs", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "operator",
				"heading" => __("Operator", "themerex"),
				"description" => __("Slugs operator", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array(
					__('IN', 'themerex') => 'IN',
					__('NOT IN', 'themerex') => 'NOT IN',
					__('AND', 'themerex') => 'AND'
				),
				"type" => "dropdown"
			)
		)
	) );
	
	class WPBakeryShortCode_Product_Category extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Products
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "products",
		"name" => __("Products", "themerex"),
		"description" => __("WooCommerce shortcode: list all products", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_products',
		"class" => "trx_sc_single trx_sc_products",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "skus",
				"heading" => __("SKUs", "themerex"),
				"description" => __("Comma separated SKU codes of products", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "ids",
				"heading" => __("IDs", "themerex"),
				"description" => __("Comma separated ID of products", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			)
		)
	) );
	
	class WPBakeryShortCode_Products extends THEMEREX_VC_ShortCodeSingle {}




	// WooCommerce - Product Attribute
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "product_attribute",
		"name" => __("Products by Attribute", "themerex"),
		"description" => __("WooCommerce shortcode: show products with specified attribute", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_product_attribute',
		"class" => "trx_sc_single trx_sc_product_attribute",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "per_page",
				"heading" => __("Number", "themerex"),
				"description" => __("How many products showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			),
			array(
				"param_name" => "attribute",
				"heading" => __("Attribute", "themerex"),
				"description" => __("Attribute name", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "filter",
				"heading" => __("Filter", "themerex"),
				"description" => __("Attribute value", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			)
		)
	) );
	
	class WPBakeryShortCode_Product_Attribute extends THEMEREX_VC_ShortCodeSingle {}



	// WooCommerce - Products Categories
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "product_categories",
		"name" => __("Product Categories", "themerex"),
		"description" => __("WooCommerce shortcode: show categories with products", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_product_categories',
		"class" => "trx_sc_single trx_sc_product_categories",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "number",
				"heading" => __("Number", "themerex"),
				"description" => __("How many categories showed", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "columns",
				"heading" => __("Columns", "themerex"),
				"description" => __("How many columns per row use for categories output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "4",
				"type" => "textfield"
			),
			array(
				"param_name" => "orderby",
				"heading" => __("Order by", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "order",
				"heading" => __("Order", "themerex"),
				"description" => __("Sorting order for products output", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => array_flip($THEMEREX_shortcodes_ordering),
				"type" => "dropdown"
			),
			array(
				"param_name" => "hide_empty",
				"heading" => __("Hide empty", "themerex"),
				"description" => __("Hide empty categories", "themerex"),
				"class" => "",
				"value" => array("Hide empty" => "1" ),
				"type" => "checkbox"
			),
			array(
				"param_name" => "parent",
				"heading" => __("Parent", "themerex"),
				"description" => __("Parent category slug", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "date",
				"type" => "textfield"
			),
			array(
				"param_name" => "ids",
				"heading" => __("IDs", "themerex"),
				"description" => __("Comma separated ID of products", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			)
		)
	) );
	
	class WPBakeryShortCode_Products_Categories extends THEMEREX_VC_ShortCodeSingle {}

/*

	// WooCommerce - Add to cart
	//-------------------------------------------------------------------------------------
	
	vc_map( array(
		"base" => "add_to_cart",
		"name" => __("Add to cart", "themerex"),
		"description" => __("WooCommerce shortcode: Display a single product price + cart button", "themerex"),
		"category" => __('WooCommerce', 'js_composer'),
		'icon' => 'icon_trx_add_to_cart',
		"class" => "trx_sc_single trx_sc_add_to_cart",
		"content_element" => true,
		"is_container" => false,
		"show_settings_on_create" => true,
		"params" => array(
			array(
				"param_name" => "id",
				"heading" => __("ID", "themerex"),
				"description" => __("Product's ID", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "sku",
				"heading" => __("SKU", "themerex"),
				"description" => __("Product's SKU code", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "quantity",
				"heading" => __("Quantity", "themerex"),
				"description" => __("How many item add", "themerex"),
				"admin_label" => true,
				"class" => "",
				"value" => "1",
				"type" => "textfield"
			),
			array(
				"param_name" => "show_price",
				"heading" => __("Show price", "themerex"),
				"description" => __("Show price near button", "themerex"),
				"class" => "",
				"value" => array("Show price" => "true" ),
				"type" => "checkbox"
			),
			array(
				"param_name" => "class",
				"heading" => __("Class", "themerex"),
				"description" => __("CSS class", "themerex"),
				"class" => "",
				"value" => "",
				"type" => "textfield"
			),
			array(
				"param_name" => "style",
				"heading" => __("CSS style", "themerex"),
				"description" => __("CSS style for additional decoration", "themerex"),
				"class" => "",
				"value" => "",
				"type" => "textfield"
			)
		)
	) );
	
	class WPBakeryShortCode_Add_To_Cart extends THEMEREX_VC_ShortCodeSingle {}
*/

}



// Load scripts and styles for VC support
add_action( 'admin_enqueue_scripts', 'shortcodes_vc_scripts' );
if ( !function_exists( 'shortcodes_vc_scripts' ) ) {
	function shortcodes_vc_scripts() {
		// Include CSS 
		themerex_enqueue_style ( 'shortcodes_vc-style', themerex_get_file_url('/shortcodes/shortcodes_vc.css'), array(), null );
		// Include JS
		themerex_enqueue_script( 'shortcodes_vc', themerex_get_file_url('/shortcodes/shortcodes_vc.js'), array(), null, true );
	}
}
?>