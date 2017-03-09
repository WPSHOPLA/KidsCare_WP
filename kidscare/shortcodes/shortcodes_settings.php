<?php
// Current element id
$THEMEREX_shortcodes_id = array(
	"id" => "id",
	"title" => __("Element ID", "themerex"),
	"desc" => __("ID for current element", "themerex"),
	"divider" => true,
	"value" => "",
	"type" => "text"
);
// Current element class
$THEMEREX_shortcodes_class = array(
	"id" => "class",
	"title" => __("Element CSS class", "themerex"),
	"desc" => __("CSS class for current element (optional)", "themerex"),
	"value" => "",
	"type" => "text"
);
// Current element style
$THEMEREX_shortcodes_style = array(
	"id" => "style",
	"title" => __("CSS styles", "themerex"),
	"desc" => __("Any additional CSS rules (if need)", "themerex"),
	"value" => "",
	"type" => "text"
);

// Width and height params
function THEMEREX_shortcodes_width($w="") {
	return array(
		"id" => "width",
		"title" => __("Width", "themerex"),
		"divider" => true,
		"value" => $w,
		"type" => "text"
	);
}
function THEMEREX_shortcodes_height($h='') {
	return array(
		"id" => "height",
		"title" => __("Height", "themerex"),
		"desc" => __("Width (in pixels or percent) and height (only in pixels) of element", "themerex"),
		"value" => $h,
		"type" => "text"
	);
}

// Margins params
$THEMEREX_shortcodes_margin_top = array(
	"id" => "top",
	"title" => __("Top margin", "themerex"),
	"divider" => true,
	"value" => "",
	"type" => "text"
);
$THEMEREX_shortcodes_margin_bottom = array(
	"id" => "bottom",
	"title" => __("Bottom margin", "themerex"),
	"value" => "",
	"type" => "text"
);
$THEMEREX_shortcodes_margin_left = array(
	"id" => "left",
	"title" => __("Left margin", "themerex"),
	"value" => "",
	"type" => "text"
);
$THEMEREX_shortcodes_margin_right = array(
	"id" => "right",
	"title" => __("Right margin", "themerex"),
	"desc" => __("Margins around list (in pixels).", "themerex"),
	"value" => "",
	"type" => "text"
);

// List's styles
$THEMEREX_shortcodes_list_styles = array(
	'ul' => __("Unordered", 'themerex'),
	'ol' => __("Ordered", 'themerex'),
	'iconed' => __('Iconed', 'themerex'),
	'arrows' => __('Arrows', 'themerex'),
	'disk' => __('Disk', 'themerex')
);

// Switcher choises
$THEMEREX_shortcodes_yes_no 	= getYesNoList();
$THEMEREX_shortcodes_on_off 	= getOnOffList();
$THEMEREX_shortcodes_dir 		= getDirectionList();
$THEMEREX_shortcodes_align 		= getAlignmentList();
$THEMEREX_shortcodes_float 		= getFloatList();
$THEMEREX_shortcodes_show_hide 	= getShowHideList();
$THEMEREX_shortcodes_sorting 	= getSortingList();
$THEMEREX_shortcodes_ordering 	= getOrderingList();
$THEMEREX_shortcodes_sliders	= getSlidersList();
$THEMEREX_shortcodes_users		= getUsersList();
$THEMEREX_shortcodes_categories	= getCategoriesList();
$THEMEREX_shortcodes_columns	= getColumnsList();
$THEMEREX_shortcodes_images		= themerex_array_merge(array('none'=>"none"), getListFiles("/images/icons", "png"));
$THEMEREX_shortcodes_icons 		= array_merge(array("inherit", "none"), getIconsList());
$THEMEREX_shortcodes_locations	= getDedicatedLocationsList();
$THEMEREX_shortcodes_blogger_styles	= getBloggerStylesList();
$THEMEREX_shortcodes_filters	= getPortfolioFiltersList();
$THEMEREX_shortcodes_formats	= getPostFormatsFiltersList();
$THEMEREX_shortcodes_googlemap_styles = getGooglemapStylesList();
$THEMEREX_shortcodes_hovers     = getHoversList();
$THEMEREX_shortcodes_hovers_dir = getHoversDirectionsList();
$THEMEREX_shortcodes_tint		= getBgTintList();
$THEMEREX_shortcodes_field_types= getFieldTypesList();



// Shortcodes list
//------------------------------------------------------------------
$THEMEREX_shortcodes = array(
	// Accordion
	array(
		"title" => __("Accordion", "themerex"),
		"desc" => __("Accordion items", "themerex"),
		"id" => "trx_accordion",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Accordion style", "themerex"),
				"desc" => __("Select style for display accordion", "themerex"),
				"value" => 1,
				"options" => array(
					1 => __('Style 1', 'themerex'),
					2 => __('Style 2', 'themerex'),
					3 => __('Style 3', 'themerex')
				),
				"type" => "radio"
			),
			array(
				"id" => "counter",
				"title" => __("Counter", "themerex"),
				"desc" => __("Display counter before each accordion title", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			array(
				"id" => "large",
				"title" => __("Large titles", "themerex"),
				"desc" => __("Show large titles", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			array(
				"id" => "shadow",
				"title" => __("Shadow", "themerex"),
				"desc" => __("Display shadow under toggles block", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			array(
				"id" => "initial",
				"title" => __("Initially opened item", "themerex"),
				"desc" => __("Number of initially opened item", "themerex"),
				"value" => 1,
				"min" => 0,
				"type" => "spinner"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Item", "themerex"),
			"desc" => __("Accordion item", "themerex"),
			"id" => "trx_accordion_item",
			"container" => true,
			"params" => array(
				array(
					"id" => "title",
					"title" => __("Accordion item title", "themerex"),
					"desc" => __("Title for current accordion item", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "_content_",
					"title" => __("Accordion item content", "themerex"),
					"desc" => __("Current accordion item content", "themerex"),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Anchor
	array(
		"title" => __("Anchor", "themerex"),
		"desc" => __("Insert anchor for the TOC (table of content)", "themerex"),
		"id" => "trx_anchor",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "icon",
				"title" => __("Anchor's icon",  'themerex'),
				"desc" => __('Select icon for the anchor from Fontello icons set',  'themerex'),
				"value" => "",
				"type" => "icons",
				"options" => $THEMEREX_shortcodes_icons
			),
			array(
				"id" => "title",
				"title" => __("Short title", "themerex"),
				"desc" => __("Short title of the anchor (for the table of content)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "Description",
				"title" => __("Long description", "themerex"),
				"desc" => __("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "url",
				"title" => __("External URL", "themerex"),
				"desc" => __("External URL for this TOC item", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "separator",
				"title" => __("Add separator", "themerex"),
				"desc" => __("Add separator under item in the TOC", "themerex"),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			$THEMEREX_shortcodes_id
		)
	),


	// Audio
	array(
		"title" => __("Audio", "themerex"),
		"desc" => __("Insert audio player", "themerex"),
		"id" => "trx_audio",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "url",
				"title" => __("URL for audio file", "themerex"),
				"desc" => __("URL for audio file", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media",
				"before" => array(
					'title' => __('Choose audio', 'themerex'),
					'action' => 'media_upload',
					'type' => 'audio',
					'multiple' => false,
					'linked_field' => '',
					'captions' => array( 	
						'choose' => __('Choose audio file', 'themerex'),
						'update' => __('Select audio file', 'themerex')
					)
				),
				"after" => array(
					'icon' => 'icon-cancel',
					'action' => 'media_reset'
				)
			),
			array(
				"id" => "controls",
				"title" => __("Show controls", "themerex"),
				"desc" => __("Show controls in audio player", "themerex"),
				"size" => "medium",
				"value" => "show",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_show_hide
			),
			array(
				"id" => "autoplay",
				"title" => __("Autoplay audio", "themerex"),
				"desc" => __("Autoplay audio on page load", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			THEMEREX_shortcodes_width("100%"),
			THEMEREX_shortcodes_height(50),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Banner
	array(
		"title" => __("Banner", "themerex"),
		"desc" => __("Banner image with link", "themerex"),
		"id" => "trx_banner",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "src",
				"title" => __("URL (source) for image file", "themerex"),
				"desc" => __("Select or upload image or write URL from other site", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "align",
				"title" => __("Banner alignment", "themerex"),
				"desc" => __("Align banner to left, center or right", "themerex"),
				"divider" => true,
				"value" => "none",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_align
			), 
			array(
				"id" => "link",
				"title" => __("Link URL", "themerex"),
				"desc" => __("URL for link on banner click", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "target",
				"title" => __("Link target", "themerex"),
				"desc" => __("Target for link on banner click", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "popup",
				"title" => __("Open link in popup", "themerex"),
				"desc" => __("Open link target in popup window", "themerex"),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			), 
			array(
				"id" => "rel",
				"title" => __("Rel attribute", "themerex"),
				"desc" => __("Rel attribute for banner's link (if need)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "title",
				"title" => __("Title", "themerex"),
				"desc" => __("Banner's title", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "_content_",
				"title" => __("Text", "themerex"),
				"desc" => __("Banner's inner text", "themerex"),
				"value" => "",
				"type" => "text"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Block
	array(
		"title" => __("Block container", "themerex"),
		"desc" => __("Container for any block ([section] analog - to enable nesting)", "themerex"),
		"id" => "trx_block",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "color",
				"title" => __("Fore color", "themerex"),
				"desc" => __("Any color for objects in this section", "themerex"),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "bg_color",
				"title" => __("Background color", "themerex"),
				"desc" => __("Any background color for this section", "themerex"),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "bg_image",
				"title" => __("URL for background image file", "themerex"),
				"desc" => __("Select or upload image or write URL from other site for the background", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "bg_tint",
				"title" => __("Background tint", "themerex"),
				"desc" => __("Main background tint: dark or light", "themerex"),
				"value" => "",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_tint
			),
			array(
				"id" => "dedicated",
				"title" => __("Dedicated", "themerex"),
				"desc" => __("Use this block as dedicated content - show it before post title on single page", "themerex"),
				"divider" => true,
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "align",
				"title" => __("Align", "themerex"),
				"desc" => __("Select block alignment", "themerex"),
				"value" => "none",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_align
			),
			array(
				"id" => "columns",
				"title" => __("Columns emulation", "themerex"),
				"desc" => __("Select width for columns emulation", "themerex"),
				"value" => "none",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_columns
			), 
			array(
				"id" => "scroll",
				"title" => __("Use scroller", "themerex"),
				"desc" => __("Use scroller to show block content", "themerex"),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "dir",
				"title" => __("Scroll direction", "themerex"),
				"desc" => __("Scroll direction (if Use scroller = yes)", "themerex"),
				"value" => "horizontal",
				"type" => "switch",
				"size" => "big",
				"dependency" => array(
					'scroll' => array('yes')
				),
				"options" => $THEMEREX_shortcodes_dir
			),
			array(
				"id" => "_content_",
				"title" => __("Container content", "themerex"),
				"desc" => __("Content for section container", "themerex"),
				"delimiter" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class,
			$THEMEREX_shortcodes_style
		)
	),




	// Blogger
	array(
		"title" => __("Blogger", "themerex"),
		"desc" => __("Insert posts (pages) in many styles from desired categories or directly from ids", "themerex"),
		"id" => "trx_blogger",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Posts output style", "themerex"),
				"desc" => __("Select desired style for posts output", "themerex"),
				"value" => "regular",
				"type" => "select",
				"options" => $THEMEREX_shortcodes_blogger_styles
			),
			array(
				"id" => "filters",
				"title" => __("Show filters", "themerex"),
				"desc" => __("Use post's tags or categories as filter buttons", "themerex"),
				"value" => "no",
				"dir" => "horizontal",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_filters
			),
			array(
				"id" => "hover",
				"title" => __("Hover effect", "themerex"),
				"desc" => __("Select hover effect (only if style=Portfolio)", "themerex"),
				"dependency" => array(
					'style' => array('portfolio')
				),
				"value" => "",
				"type" => "select",
				"options" => $THEMEREX_shortcodes_hovers
			),
			array(
				"id" => "hover_dir",
				"title" => __("Hover direction", "themerex"),
				"desc" => __("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "themerex"),
				"dependency" => array(
					'style' => array('portfolio'),
					'hover' => array('square','circle')
				),
				"value" => "left_to_right",
				"type" => "select",
				"options" => $THEMEREX_shortcodes_hovers_dir
			),
			array(
				"id" => "ids",
				"title" => __("Post IDs list", "themerex"),
				"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "cat",
				"title" => __("Categories list", "themerex"),
				"desc" => __("Select the desired categories. If not selected - show posts from any category or from IDs list", "themerex"),
				"dependency" => array(
					'ids' => array('is_empty')
				),
				"divider" => true,
				"value" => "",
				"type" => "select",
				"style" => "list",
				"multiple" => true,
				"options" => $THEMEREX_shortcodes_categories
			),
			array(
				"id" => "count",
				"title" => __("Total posts to show", "themerex"),
				"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "themerex"),
				"dependency" => array(
					'ids' => array('is_empty')
				),
				"value" => 3,
				"min" => 1,
				"max" => 100,
				"type" => "spinner"
			),
			array(
				"id" => "visible",
				"title" => __("Number of visible posts", "themerex"),
				"desc" => __("How many posts will be visible at once? If empty or 0 - all posts are visible", "themerex"),
				"dependency" => array(
					'ids' => array('is_empty')
				),
				"value" => 3,
				"min" => 1,
				"max" => 100,
				"type" => "spinner"
			),
			array(
				"id" => "offset",
				"title" => __("Offset before select posts", "themerex"),
				"desc" => __("Skip posts before select next part.", "themerex"),
				"dependency" => array(
					'ids' => array('is_empty')
				),
				"value" => 0,
				"min" => 0,
				"max" => 100,
				"type" => "spinner"
			),
			array(
				"id" => "orderby",
				"title" => __("Post order by", "themerex"),
				"desc" => __("Select desired posts sorting method", "themerex"),
				"value" => "date",
				"type" => "select",
				"options" => $THEMEREX_shortcodes_sorting
			),
			array(
				"id" => "order",
				"title" => __("Post order", "themerex"),
				"desc" => __("Select desired posts order", "themerex"),
				"value" => "desc",
				"type" => "switch",
				"size" => "big",
				"options" => $THEMEREX_shortcodes_ordering
			),
			array(
				"id" => "only",
				"title" => __("Select posts only", "themerex"),
				"desc" => __("Select posts only with reviews, videos, audios, thumbs or galleries", "themerex"),
				"value" => "no",
				"type" => "select",
				"options" => $THEMEREX_shortcodes_formats
			),
			array(
				"id" => "scroll",
				"title" => __("Use scroller", "themerex"),
				"desc" => __("Use scroller to show all posts (if parameter 'visible' less than 'count')", "themerex"),
				"divider" => true,
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "controls",
				"title" => __("Show slider controls", "themerex"),
				"desc" => __("Show arrows to control scroll slider", "themerex"),
				"dependency" => array(
					'scroll' => array('yes')
				),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "location",
				"title" => __("Dedicated content location", "themerex"),
				"desc" => __("Select position for dedicated content (only for style=excerpt)", "themerex"),
				"divider" => true,
				"dependency" => array(
					'style' => array('excerpt')
				),
				"value" => "default",
				"type" => "select",
				"options" => $THEMEREX_shortcodes_locations
			),
			array(
				"id" => "dir",
				"title" => __("Posts direction", "themerex"),
				"desc" => __("Display posts in horizontal or vertical direction", "themerex"),
				"value" => "horizontal",
				"type" => "switch",
				"size" => "big",
				"options" => $THEMEREX_shortcodes_dir
			),
			array(
				"id" => "rating",
				"title" => __("Show rating stars", "themerex"),
				"desc" => __("Show rating stars under post's header", "themerex"),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "info",
				"title" => __("Show post info block", "themerex"),
				"desc" => __("Show post info block (author, date, tags, etc.)", "themerex"),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "links",
				"title" => __("Allow links on the post", "themerex"),
				"desc" => __("Allow links on the post from each blogger item", "themerex"),
				"value" => "yes",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "descr",
				"title" => __("Description length", "themerex"),
				"desc" => __("How many characters are displayed from post excerpt? If 0 - don't show description", "themerex"),
				"value" => 0,
				"min" => 0,
				"increment" => 10,
				"type" => "spinner"
			),
			array(
				"id" => "readmore",
				"title" => __("More link text", "themerex"),
				"desc" => __("Read more link text. If empty - show 'More', else - used as link text", "themerex"),
				"value" => "",
				"type" => "text"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),





	// Br
	array(
		"title" => __("Break", "themerex"),
		"desc" => __("Line break with clear floating (if need)", "themerex"),
		"id" => "trx_br",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "clear",
				"title" => __("Clear floating", "themerex"),
				"desc" => __("Clear floating (if need)", "themerex"),
				"value" => "",
				"type" => "checklist",
				"options" => array(
					'none' => __('None', 'themerex'),
					'left' => __('Left', 'themerex'),
					'right' => __('Right', 'themerex'),
					'both' => __('Both', 'themerex')
				)
			)
		)
	),




	// Button
	array(
		"title" => __("Button", "themerex"),
		"desc" => __("Button with link", "themerex"),
		"id" => "trx_button",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "_content_",
				"title" => __("Caption", "themerex"),
				"desc" => __("Button caption", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "type",
				"title" => __("Button's shape", "themerex"),
				"desc" => __("Select button's shape", "themerex"),
				"value" => "square",
				"type" => "switch",
				"size" => "medium",
				"options" => array(
					'square' => __('Square', 'themerex'),
					'round' => __('Round', 'themerex')
				)
			), 
			array(
				"id" => "style",
				"title" => __("Button's style", "themerex"),
				"desc" => __("Select button's style", "themerex"),
				"value" => "global",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					'global' => __('Global', 'themerex'),
					'light'  => __('Light', 'themerex'),
					'dark'   => __('Dark', 'themerex'),
					'border' => __('Border', 'themerex')
				)
			), 
			array(
				"id" => "size",
				"title" => __("Button's size", "themerex"),
				"desc" => __("Select button's size", "themerex"),
				"value" => "medium",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					'mini' => __('Small', 'themerex'),
					'medium' => __('Medium', 'themerex'),
					'big' => __('Large', 'themerex'),
					'huge' => __('Huge', 'themerex'),
					'banner' => __('Banner', 'themerex')
				)
			), 
			array(
				"id" => "fullsize",
				"title" => __("Fullsize mode", "themerex"),
				"desc" => __("Set button's width to 100%", "themerex"),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			), 
			array(
				"id" => "icon",
				"title" => __("Button's icon",  'themerex'),
				"desc" => __('Select icon for the title from Fontello icons set',  'themerex'),
				"value" => "",
				"type" => "icons",
				"options" => $THEMEREX_shortcodes_icons
			),
			array(
				"id" => "color",
				"title" => __("Button's color", "themerex"),
				"desc" => __("Any color for button background", "themerex"),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "align",
				"title" => __("Button's alignment", "themerex"),
				"desc" => __("Align button to left or right", "themerex"),
				"value" => "none",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_float
			), 
			array(
				"id" => "link",
				"title" => __("Link URL", "themerex"),
				"desc" => __("URL for link on button click", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "target",
				"title" => __("Link target", "themerex"),
				"desc" => __("Target for link on button click", "themerex"),
				"dependency" => array(
					'link' => array('not_empty')
				),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "popup",
				"title" => __("Open link in popup", "themerex"),
				"desc" => __("Open link target in popup window", "themerex"),
				"dependency" => array(
					'link' => array('not_empty')
				),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			), 
			array(
				"id" => "rel",
				"title" => __("Rel attribute", "themerex"),
				"desc" => __("Rel attribute for button's link (if need)", "themerex"),
				"dependency" => array(
					'link' => array('not_empty')
				),
				"value" => "",
				"type" => "text"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),



	// Chat
	array(
		"title" => __("Chat", "themerex"),
		"desc" => __("Chat message", "themerex"),
		"id" => "trx_chat",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "title",
				"title" => __("Item title", "themerex"),
				"desc" => __("Chat item title", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "link",
				"title" => __("Item link", "themerex"),
				"desc" => __("Chat item link", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "_content_",
				"title" => __("Chat item content", "themerex"),
				"desc" => __("Current chat item content", "themerex"),
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),


	// Columns
	array(
		"title" => __("Columns", "themerex"),
		"desc" => __("Insert up to 5 columns in your page (post)", "themerex"),
		"id" => "trx_columns",
		"decorate" => true,
		"container" => false,
		"params" => array(
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Column", "themerex"),
			"desc" => __("Column item", "themerex"),
			"id" => "trx_column_item",
			"container" => true,
			"params" => array(
				array(
					"id" => "span",
					"title" => __("Merge columns", "themerex"),
					"desc" => __("Count merged columns from current", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "align",
					"title" => __("Alignment", "themerex"),
					"desc" => __("Alignment text in the column", "themerex"),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $THEMEREX_shortcodes_align
				),
				array(
					"id" => "color",
					"title" => __("Fore color", "themerex"),
					"desc" => __("Any color for objects in this column", "themerex"),
					"value" => "",
					"type" => "color"
				),
				array(
					"id" => "bg_color",
					"title" => __("Background color", "themerex"),
					"desc" => __("Any background color for this column", "themerex"),
					"value" => "",
					"type" => "color"
				),
				array(
					"id" => "bg_image",
					"title" => __("URL for background image file", "themerex"),
					"desc" => __("Select or upload image or write URL from other site for the background", "themerex"),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				array(
					"id" => "_content_",
					"title" => __("Column item content", "themerex"),
					"desc" => __("Current column item content", "themerex"),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class,
				$THEMEREX_shortcodes_style
			)
		)
	),




	// Contact form
	array(
		"title" => __("Contact form", "themerex"),
		"desc" => __("Insert contact form", "themerex"),
		"id" => "trx_contact_form",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "title",
				"title" => __("Title", "themerex"),
				"desc" => __("Contact form title", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "description",
				"title" => __("Description", "themerex"),
				"desc" => __("Short description for contact form", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Field", "themerex"),
			"desc" => __("Custom field", "themerex"),
			"id" => "trx_form_item",
			"container" => false,
			"params" => array(
				array(
					"id" => "type",
					"title" => __("Field's type", "themerex"),
					"desc" => __("Type of the custom field", "themerex"),
					"value" => "text",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $THEMEREX_shortcodes_field_types
				), 
				array(
					"id" => "label",
					"title" => __("Field's label", "themerex"),
					"desc" => __("Label for the custom field", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "name",
					"title" => __("Field's name", "themerex"),
					"desc" => __("Name of the custom field", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "value",
					"title" => __("Default value", "themerex"),
					"desc" => __("Default value of the custom field", "themerex"),
					"value" => "",
					"type" => "text"
				),
				$THEMEREX_shortcodes_margin_top,
				$THEMEREX_shortcodes_margin_bottom,
				$THEMEREX_shortcodes_margin_left,
				$THEMEREX_shortcodes_margin_right,
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Content block on fullscreen page
	array(
		"title" => __("Content block", "themerex"),
		"desc" => __("Container for main content block with desired class and style (use it only on fullscreen pages)", "themerex"),
		"id" => "trx_content",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "_content_",
				"title" => __("Container content", "themerex"),
				"desc" => __("Content for section container", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
            array(
                "id" => "size",
                "title" => __("Font size", "themerex"),
                "desc" => __("Font size for the text (default - in pixels, allows any CSS units of measure)", "themerex"),
                "value" => "",
                "type" => "text"
            ),
            array(
                "id" => "line_height",
                "title" => __("Line height", "themerex"),
                "desc" => __("Line height for the text (default - in pixels, allows any CSS units of measure)", "themerex"),
                "value" => "",
                "type" => "text"
            ),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class,
			$THEMEREX_shortcodes_style
		)
	),





	// Countdown
	array(
		"title" => __("Countdown", "themerex"),
		"desc" => __("Insert countdown object", "themerex"),
		"id" => "trx_countdown",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "date",
				"title" => __("Date", "themerex"),
				"desc" => __("Upcoming date (format: yyyy-mm-dd)", "themerex"),
				"value" => "",
				"format" => "yy-mm-dd",
				"type" => "date"
			),
			array(
				"id" => "time",
				"title" => __("Time", "themerex"),
				"desc" => __("Upcoming time (format: HH:mm:ss)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "align",
				"title" => __("Alignment", "themerex"),
				"desc" => __("Align counter to left, center or right", "themerex"),
				"divider" => true,
				"value" => "none",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_align
			), 
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Dropcaps
	array(
		"title" => __("Dropcaps", "themerex"),
		"desc" => __("Make first letter as dropcaps", "themerex"),
		"id" => "trx_dropcaps",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Style", "themerex"),
				"desc" => __("Dropcaps style", "themerex"),
				"value" => "1",
				"type" => "checklist",
				"options" => array(
					1 => __('Style 1', 'themerex'),
					2 => __('Style 2', 'themerex'),
					3 => __('Style 3', 'themerex'),
					4 => __('Style 4', 'themerex'),
					5 => __('Style 5', 'themerex'),
					6 => __('Style 6', 'themerex')
				)
			),
			array(
				"id" => "_content_",
				"title" => __("Paragraph content", "themerex"),
				"desc" => __("Paragraph with dropcaps content", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),





	// Emailer
	array(
		"title" => __("E-mail collector", "themerex"),
		"desc" => __("Collect the e-mail address into specified group", "themerex"),
		"id" => "trx_emailer",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "group",
				"title" => __("Group", "themerex"),
				"desc" => __("The name of group to collect e-mail address", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "open",
				"title" => __("Open", "themerex"),
				"desc" => __("Initially open the input field on show object", "themerex"),
				"divider" => true,
				"value" => "yes",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "align",
				"title" => __("Alignment", "themerex"),
				"desc" => __("Align object to left, center or right", "themerex"),
				"divider" => true,
				"value" => "none",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_align
			), 
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),





	// Gap
	array(
		"title" => __("Gap", "themerex"),
		"desc" => __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "themerex"),
		"id" => "trx_gap",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "_content_",
				"title" => __("Gap content", "themerex"),
				"desc" => __("Gap inner content", "themerex"),
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			)
		)
	),





	// Google map
	array(
		"title" => __("Google map", "themerex"),
		"desc" => __("Insert Google map with desired address or coordinates", "themerex"),
		"id" => "trx_googlemap",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "address",
				"title" => __("Address", "themerex"),
				"desc" => __("Address to show in map center", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "latlng",
				"title" => __("Latitude and Longtitude", "themerex"),
				"desc" => __("Comma separated map center coorditanes (instead Address)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "zoom",
				"title" => __("Zoom", "themerex"),
				"desc" => __("Map zoom factor", "themerex"),
				"divider" => true,
				"value" => 16,
				"min" => 1,
				"max" => 20,
				"type" => "spinner"
			),
			array(
				"id" => "style",
				"title" => __("Map style", "themerex"),
				"desc" => __("Select map style", "themerex"),
				"value" => "default",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_googlemap_styles
			),
			THEMEREX_shortcodes_width('100%'),
			THEMEREX_shortcodes_height(240),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),



	// Hide or show any block
	array(
		"title" => __("Hide/Show any block", "themerex"),
		"desc" => __("Hide or Show any block with desired CSS-selector", "themerex"),
		"id" => "trx_hide",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "selector",
				"title" => __("Selector", "themerex"),
				"desc" => __("Any block's CSS-selector", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "hide",
				"title" => __("Hide or Show", "themerex"),
				"desc" => __("New state for the block: hide or show", "themerex"),
				"value" => "yes",
				"size" => "small",
				"options" => $THEMEREX_shortcodes_yes_no,
				"type" => "switch"
			)
		)
	),



	// Highlght text
	array(
		"title" => __("Highlight text", "themerex"),
		"desc" => __("Highlight text with selected color, background color and other styles", "themerex"),
		"id" => "trx_highlight",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "type",
				"title" => __("Type", "themerex"),
				"desc" => __("Highlight type", "themerex"),
				"value" => "1",
				"type" => "checklist",
				"options" => array(
					0 => __('Custom', 'themerex'),
					1 => __('Type 1', 'themerex'),
					2 => __('Type 2', 'themerex')
				)
			),
			array(
				"id" => "color",
				"title" => __("Color", "themerex"),
				"desc" => __("Color for the highlighted text", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "backcolor",
				"title" => __("Background color", "themerex"),
				"desc" => __("Background color for the highlighted text", "themerex"),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "size",
				"title" => __("Font size", "themerex"),
				"desc" => __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "themerex"),
				"value" => "",
				"type" => "text"
			),
            array(
                "id" => "line_height",
                "title" => __("Line height", "themerex"),
                "desc" => __("Line height for the highlighted text (default - in pixels, allows any CSS units of measure)", "themerex"),
                "value" => "",
                "type" => "text"
            ),
			array(
				"id" => "_content_",
				"title" => __("Highlighting content", "themerex"),
				"desc" => __("Content for highlight", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class,
			$THEMEREX_shortcodes_style
		)
	),




	// Icon
	array(
		"title" => __("Icon", "themerex"),
		"desc" => __("Insert icon", "themerex"),
		"id" => "trx_icon",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "icon",
				"title" => __('Icon',  'themerex'),
				"desc" => __('Select font icon from the Fontello icons set',  'themerex'),
				"value" => "",
				"type" => "icons",
				"options" => $THEMEREX_shortcodes_icons
			),
			array(
				"id" => "color",
				"title" => __("Icon's color", "themerex"),
				"desc" => __("Icon's color", "themerex"),
				"dependency" => array(
					'icon' => array('not_empty')
				),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "background",
				"title" => __("Background style", "themerex"),
				"desc" => __("Style of the icon background", "themerex"),
				"dependency" => array(
					'icon' => array('not_empty')
				),
				"value" => "none",
				"type" => "radio",
				"options" => array(
					'none' => __('None', 'themerex'),
					'round' => __('Round', 'themerex'),
					'square' => __('Square', 'themerex')
				)
			),
			array(
				"id" => "bg_color",
				"title" => __("Icon's background color", "themerex"),
				"desc" => __("Icon's background color", "themerex"),
				"dependency" => array(
					'icon' => array('not_empty'),
					'background' => array('round','square')
				),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "size",
				"title" => __("Font size", "themerex"),
				"desc" => __("Icon's font size", "themerex"),
				"dependency" => array(
					'icon' => array('not_empty')
				),
				"value" => "",
				"type" => "spinner",
				"min" => 8,
				"max" => 240
			),
			array(
				"id" => "weight",
				"title" => __("Font weight", "themerex"),
				"desc" => __("Icon font weight", "themerex"),
				"dependency" => array(
					'icon' => array('not_empty')
				),
				"value" => "",
				"type" => "select",
				"size" => "medium",
				"options" => array(
					'100' => __('Thin (100)', 'themerex'),
					'300' => __('Light (300)', 'themerex'),
					'400' => __('Normal (400)', 'themerex'),
					'700' => __('Bold (700)', 'themerex')
				)
			),
			array(
				"id" => "align",
				"title" => __("Alignment", "themerex"),
				"desc" => __("Icon text alignment", "themerex"),
				"dependency" => array(
					'icon' => array('not_empty')
				),
				"value" => "",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_align
			), 
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Image
	array(
		"title" => __("Image", "themerex"),
		"desc" => __("Insert image into your post (page)", "themerex"),
		"id" => "trx_image",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "url",
				"title" => __("URL for image file", "themerex"),
				"desc" => __("Select or upload image or write URL from other site", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "title",
				"title" => __("Title", "themerex"),
				"desc" => __("Image title (if need)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "icon",
				"title" => __("Icon before title",  'themerex'),
				"desc" => __('Select icon for the title from Fontello icons set',  'themerex'),
				"value" => "",
				"type" => "icons",
				"options" => $THEMEREX_shortcodes_icons
			),
			array(
				"id" => "align",
				"title" => __("Float image", "themerex"),
				"desc" => __("Float image to left or right side", "themerex"),
				"value" => "",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_float
			), 
			array(
				"id" => "shape",
				"title" => __("Image Shape", "themerex"),
				"desc" => __("Shape of the image: square (rectangle) or round", "themerex"),
				"value" => "square",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					"square" => __('Square', 'themerex'),
					"round" => __('Round', 'themerex')
				)
			), 
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),



	// Infobox
	array(
		"title" => __("Infobox", "themerex"),
		"desc" => __("Insert infobox into your post (page)", "themerex"),
		"id" => "trx_infobox",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Style", "themerex"),
				"desc" => __("Infobox style", "themerex"),
				"value" => "regular",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					'regular' => __('Regular', 'themerex'),
					'info' => __('Info', 'themerex'),
					'success' => __('Success', 'themerex'),
					'error' => __('Error', 'themerex'),
					'result' => __('Result', 'themerex')
				)
			),
			array(
				"id" => "closeable",
				"title" => __("Closeable box", "themerex"),
				"desc" => __("Create closeable box (with close button)", "themerex"),
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "_content_",
				"title" => __("Infobox content", "themerex"),
				"desc" => __("Content for infobox", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),



	// Line
	array(
		"title" => __("Line", "themerex"),
		"desc" => __("Insert Line into your post (page)", "themerex"),
		"id" => "trx_line",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Style", "themerex"),
				"desc" => __("Line style", "themerex"),
				"value" => "solid",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					'solid' => __('Solid', 'themerex'),
					'dashed' => __('Dashed', 'themerex'),
					'dotted' => __('Dotted', 'themerex'),
					'double' => __('Double', 'themerex'),
					'shadow' => __('Shadow', 'themerex'),
					'wavy' => __('Wavy', 'themerex'),
					'wavy_orange' => __('Wavy Orange', 'themerex')
				)
			),
			array(
				"id" => "color",
				"title" => __("Color", "themerex"),
				"desc" => __("Line color", "themerex"),
				"value" => "",
				"type" => "color"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// List
	array(
		"title" => __("List", "themerex"),
		"desc" => __("List items with specific bullets", "themerex"),
		"id" => "trx_list",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Bullet's style", "themerex"),
				"desc" => __("Bullet's style for each list item", "themerex"),
				"value" => "ul",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_list_styles
			), 
			array(
				"id" => "icon",
				"title" => __('List icon',  'themerex'),
				"desc" => __("Select list icon from Fontello icons set (only for style=Iconed)",  'themerex'),
				"dependency" => array(
					'style' => array('iconed')
				),
				"value" => "",
				"type" => "icons",
				"options" => $THEMEREX_shortcodes_icons
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Item", "themerex"),
			"desc" => __("List item with specific bullet", "themerex"),
			"id" => "trx_list_item",
			"decorate" => false,
			"container" => true,
			"params" => array(
				array(
					"id" => "_content_",
					"title" => __("List item content", "themerex"),
					"desc" => __("Current list item content", "themerex"),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				array(
					"id" => "icon",
					"title" => __('List icon',  'themerex'),
					"desc" => __("Select list icon from Fontello icons set (only for style=Iconed)",  'themerex'),
					"dependency" => array(
						'style' => array('iconed')
					),
					"divider" => true,
					"value" => "",
					"type" => "icons",
					"options" => $THEMEREX_shortcodes_icons
				),
				array(
					"id" => "title",
					"title" => __("List item title", "themerex"),
					"desc" => __("Current list item title (show it as tooltip)", "themerex"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Parallax
	array(
		"title" => __("Parallax", "themerex"),
		"desc" => __("Create the parallax container (with asinc background image)", "themerex"),
		"id" => "trx_parallax",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "gap",
				"title" => __("Create gap", "themerex"),
				"desc" => __("Create gap around parallax container", "themerex"),
				"value" => "no",
				"size" => "small",
				"options" => $THEMEREX_shortcodes_yes_no,
				"type" => "switch"
			), 
			array(
				"id" => "style",
				"title" => __("Style", "themerex"),
				"desc" => __("Select style of the parallax background (for correct font color choise)", "themerex"),
				"value" => "light",
				"size" => "medium",
				"options" => array(
					'light' => __('Light', 'themerex'),
					'dark' => __('Dark', 'themerex')
				),
				"type" => "switch"
			), 
			array(
				"id" => "dir",
				"title" => __("Dir", "themerex"),
				"desc" => __("Scroll direction for the parallax background", "themerex"),
				"value" => "up",
				"size" => "medium",
				"options" => array(
					'up' => __('Up', 'themerex'),
					'down' => __('Down', 'themerex')
				),
				"type" => "switch"
			), 
			array(
				"id" => "speed",
				"title" => __("Speed", "themerex"),
				"desc" => __("Image motion speed (from 0.0 to 1.0)", "themerex"),
				"min" => "0",
				"max" => "1",
				"step" => "0.1",
				"value" => "0.3",
				"type" => "range"
			),
			array(
				"id" => "color",
				"title" => __("Background color", "themerex"),
				"desc" => __("Select color for parallax background", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "overlay",
				"title" => __("Overlay", "themerex"),
				"desc" => __("Overlay color opacity (from 0.0 to 1.0)", "themerex"),
				"min" => "0",
				"max" => "1",
				"step" => "0.1",
				"value" => "0",
				"type" => "range"
			),
			array(
				"id" => "texture",
				"title" => __("Texture", "themerex"),
				"desc" => __("Texture style from 1 to 11. 0 - without texture.", "themerex"),
				"min" => "0",
				"max" => "11",
				"step" => "1",
				"value" => "0",
				"type" => "range"
			),
			array(
				"id" => "image",
				"title" => __("URL (source) for image file", "themerex"),
				"desc" => __("Select or upload image or write URL from other site for the parallax background", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "image_x",
				"title" => __("Image X position", "themerex"),
				"desc" => __("Image horizontal position (as background of the parallax block) - in percent", "themerex"),
				"min" => "0",
				"max" => "100",
				"value" => "50",
				"type" => "range"
			),
			array(
				"id" => "video",
				"title" => __("Video background", "themerex"),
				"desc" => __("Select video from media library or paste URL for video file from other site to show it as parallax background", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media",
				"before" => array(
					'title' => __('Choose video', 'themerex'),
					'action' => 'media_upload',
					'type' => 'video',
					'multiple' => false,
					'linked_field' => '',
					'captions' => array( 	
						'choose' => __('Choose video file', 'themerex'),
						'update' => __('Select video file', 'themerex')
					)
				),
				"after" => array(
					'icon' => 'icon-cancel',
					'action' => 'media_reset'
				)
			),
			array(
				"id" => "_content_",
				"title" => __("Content", "themerex"),
				"desc" => __("Content for the parallax container", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "text"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Popup
	array(
		"title" => __("Popup window", "themerex"),
		"desc" => __("Container for any html-block with desired class and style for popup window", "themerex"),
		"id" => "trx_popup",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "_content_",
				"title" => __("Container content", "themerex"),
				"desc" => __("Content for section container", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class,
			$THEMEREX_shortcodes_style
		)
	),




	// Price
	array(
		"title" => __("Price", "themerex"),
		"desc" => __("Insert price with decoration", "themerex"),
		"id" => "trx_price",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "money",
				"title" => __("Money", "themerex"),
				"desc" => __("Money value (dot or comma separated)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "currency",
				"title" => __("Currency", "themerex"),
				"desc" => __("Currency character", "themerex"),
				"value" => "$",
				"type" => "text"
			),
			array(
				"id" => "period",
				"title" => __("Period", "themerex"),
				"desc" => __("Period text (if need). For example: monthly, daily, etc.", "themerex"),
				"value" => "",
				"type" => "text"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Price_table
	array(
		"title" => __("Price table container", "themerex"),
		"desc" => __("Price table container. After insert it, move cursor inside and select shortcode Price Item", "themerex"),
		"id" => "trx_price_table",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "align",
				"title" => __("Alignment", "themerex"),
				"desc" => __("Alignment text in the table", "themerex"),
				"value" => "center",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_align
			), 
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Item", "themerex"),
			"desc" => __("Price item column", "themerex"),
			"id" => "trx_price_item",
			"decorate" => true,
			"container" => true,
			"params" => array(
				array(
					"id" => "animation",
					"title" => __("Animation", "themerex"),
					"desc" => __("Animate column on mouse hover", "themerex"),
					"value" => "yes",
					"type" => "switch",
					"options" => $THEMEREX_shortcodes_yes_no
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Price_item
	array(
		"title" => __("Price table item", "themerex"),
		"desc" => __("Price table item (column with values)", "themerex"),
		"id" => "trx_price_item",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "animation",
				"title" => __("Animation", "themerex"),
				"desc" => __("Animate column on mouse hover", "themerex"),
				"value" => "yes",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Data", "themerex"),
			"desc" => __("Price item data - title, price, footer, etc.", "themerex"),
			"id" => "trx_price_data",
			"decorate" => false,
			"container" => true,
			"params" => array(
				array(
					"id" => "_content_",
					"title" => __("Content", "themerex"),
					"desc" => __("Current cell content", "themerex"),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				array(
					"id" => "type",
					"title" => __("Cell type", "themerex"),
					"desc" => __("Select type of the price table cell", "themerex"),
					"divider" => true,
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'none' => __('Regular', 'themerex'),
						'title' => __('Title', 'themerex'),
						'image' => __('Image', 'themerex'),
						'price' => __('Price', 'themerex'),
						'footer' => __('Footer', 'themerex'),
						'united' => __('United', 'themerex')
					)
				), 
				array(
					"id" => "money",
					"title" => __("Money", "themerex"),
					"desc" => __("Money value (dot or comma separated) - only for type=price", "themerex"),
					"dependency" => array(
						'type' => array('price')
					),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "currency",
					"title" => __("Currency", "themerex"),
					"desc" => __("Currency character - only for type=price", "themerex"),
					"dependency" => array(
						'type' => array('price')
					),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "period",
					"title" => __("Period", "themerex"),
					"desc" => __("Period text (if need). For example: monthly, daily, etc. - only for type=price", "themerex"),
					"dependency" => array(
						'type' => array('price')
					),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "image",
					"title" => __("URL (source) for image file", "themerex"),
					"desc" => __("Select or upload image or write URL from other site", "themerex"),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),



	// Price_data
	array(
		"title" => __("Price table data", "themerex"),
		"desc" => __("Price item data - title, price, footer, etc.", "themerex"),
		"id" => "trx_price_data",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "_content_",
				"title" => __("Content", "themerex"),
				"desc" => __("Current cell content", "themerex"),
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			array(
				"id" => "type",
				"title" => __("Cell type", "themerex"),
				"desc" => __("Select type of the price table cell", "themerex"),
				"divider" => true,
				"value" => "regular",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					'none' => __('Regular', 'themerex'),
					'title' => __('Title', 'themerex'),
					'image' => __('Image', 'themerex'),
					'price' => __('Price', 'themerex'),
					'footer' => __('Footer', 'themerex'),
					'united' => __('United', 'themerex')
				)
			), 
			array(
				"id" => "money",
				"title" => __("Money", "themerex"),
				"desc" => __("Money value (dot or comma separated) - only for type=price", "themerex"),
				"dependency" => array(
					'type' => array('price')
				),
				"divider" => true,
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "currency",
				"title" => __("Currency", "themerex"),
				"desc" => __("Currency character - only for type=price", "themerex"),
				"dependency" => array(
					'type' => array('price')
				),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "period",
				"title" => __("Period", "themerex"),
				"desc" => __("Period text (if need). For example: monthly, daily, etc. - only for type=price", "themerex"),
				"dependency" => array(
					'type' => array('price')
				),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "image",
				"title" => __("URL (source) for image file", "themerex"),
				"desc" => __("Select or upload image or write URL from other site", "themerex"),
				"divider" => true,
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),



	// Quote
	array(
		"title" => __("Quote", "themerex"),
		"desc" => __("Quote text", "themerex"),
		"id" => "trx_quote",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "cite",
				"title" => __("Quote cite", "themerex"),
				"desc" => __("URL for quote cite", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "title",
				"title" => __("Title (author)", "themerex"),
				"desc" => __("Quote title (author name)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "_content_",
				"title" => __("Quote content", "themerex"),
				"desc" => __("Quote content", "themerex"),
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Section
	array(
		"title" => __("Section container", "themerex"),
		"desc" => __("Container for any block with desired class and style", "themerex"),
		"id" => "trx_section",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "color",
				"title" => __("Fore color", "themerex"),
				"desc" => __("Any color for objects in this section", "themerex"),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "bg_color",
				"title" => __("Background color", "themerex"),
				"desc" => __("Any background color for this section", "themerex"),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "bg_image",
				"title" => __("URL for background image file", "themerex"),
				"desc" => __("Select or upload image or write URL from other site for the background", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "bg_tint",
				"title" => __("Background tint", "themerex"),
				"desc" => __("Main background tint: dark or light", "themerex"),
				"value" => "",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_tint
			),
			array(
				"id" => "dedicated",
				"title" => __("Dedicated", "themerex"),
				"desc" => __("Use this block as dedicated content - show it before post title on single page", "themerex"),
				"divider" => true,
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "align",
				"title" => __("Align", "themerex"),
				"desc" => __("Select block alignment", "themerex"),
				"value" => "none",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					"none" => __("None", 'themerex'),
					"left" => __("Left", 'themerex'),
					"right" => __("Right", 'themerex')
				)
			),
			array(
				"id" => "columns",
				"title" => __("Columns emulation", "themerex"),
				"desc" => __("Select width for columns emulation", "themerex"),
				"value" => "none",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_columns
			), 
			array(
				"id" => "scroll",
				"title" => __("Use scroller", "themerex"),
				"desc" => __("Use scroller to show section content", "themerex"),
				"divider" => true,
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "dir",
				"title" => __("Scroll direction", "themerex"),
				"desc" => __("Scroll direction (if Use scroller = yes)", "themerex"),
				"dependency" => array(
					'scroll' => array('yes')
				),
				"value" => "horizontal",
				"type" => "switch",
				"size" => "big",
				"options" => $THEMEREX_shortcodes_dir
			),
			array(
				"id" => "_content_",
				"title" => __("Container content", "themerex"),
				"desc" => __("Content for section container", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class,
			$THEMEREX_shortcodes_style
		)
	),


	// Skills
	array(
		"title" => __("Skills", "themerex"),
		"desc" => __("Insert skills diagramm in your page (post)", "themerex"),
		"id" => "trx_skills",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "maximum",
				"title" => __("Max value", "themerex"),
				"desc" => __("Max value for skills items", "themerex"),
				"value" => 100,
				"min" => 1,
				"type" => "spinner"
			),
			array(
				"id" => "type",
				"title" => __("Skills type", "themerex"),
				"desc" => __("Select type of skills block", "themerex"),
				"divider" => true,
				"value" => "bar",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					'bar' => __('Bar', 'themerex'),
					'pie' => __('Pie chart', 'themerex'),
					'counter' => __('Counter', 'themerex'),
					'arc' => __('Arc', 'themerex')
				)
			), 
			array(
				"id" => "style",
				"title" => __("Skills style", "themerex"),
				"desc" => __("Select style of skills items (only for type=counter)", "themerex"),
				"dependency" => array(
					'type' => array('counter')
				),
				"value" => 1,
				"min" => 1,
				"max" => 4,
				"type" => "spinner"
			), 
			array(
				"id" => "dir",
				"title" => __("Direction", "themerex"),
				"desc" => __("Select direction of skills block", "themerex"),
				"dependency" => array(
					'type' => array('counter','pie','bar')
				),
				"value" => "horizontal",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_dir
			), 
			array(
				"id" => "layout",
				"title" => __("Skills layout", "themerex"),
				"desc" => __("Select layout of skills block", "themerex"),
				"dependency" => array(
					'type' => array('counter','pie','bar')
				),
				"value" => "rows",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					'rows' => __('Rows', 'themerex'),
					'columns' => __('Columns', 'themerex')
				)
			),
			array(
				"id" => "title",
				"title" => __("Skills title", "themerex"),
				"desc" => __("Skills block title", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "subtitle",
				"title" => __("Skills subtitle", "themerex"),
				"desc" => __("Skills block subtitle - text in the center (only for type=arc)", "themerex"),
				"dependency" => array(
					'type' => array('arc')
				),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "align",
				"title" => __("Align skills block", "themerex"),
				"desc" => __("Align skills block to left or right side", "themerex"),
				"value" => "",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_float
			), 
			array(
				"id" => "color",
				"title" => __("Skills items color", "themerex"),
				"desc" => __("Color for all skills items", "themerex"),
				"value" => "",
				"type" => "color"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Skill", "themerex"),
			"desc" => __("Skills item", "themerex"),
			"id" => "trx_skills_item",
			"container" => false,
			"params" => array(
				array(
					"id" => "title",
					"title" => __("Skills item title", "themerex"),
					"desc" => __("Current skills item title", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "level",
					"title" => __("Sklls item level", "themerex"),
					"desc" => __("Current skills level", "themerex"),
					"value" => 50,
					"min" => 0,
					"increment" => 1,
					"type" => "spinner"
				),
				array(
					"id" => "color",
					"title" => __("Skills item color", "themerex"),
					"desc" => __("Current skills item color", "themerex"),
					"value" => "",
					"type" => "color"
				),
				array(
					"id" => "style",
					"title" => __("Skills item style", "themerex"),
					"desc" => __("Select style for the current skills item (only for type=counter)", "themerex"),
					"dependency" => array(
						'type' => array('price')
					),
					"value" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"
				), 
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Slider
	array(
		"title" => __("Slider", "themerex"),
		"desc" => __("Insert slider into your post (page)", "themerex"),
		"id" => "trx_slider",
		"decorate" => true,
		"container" => false,
		"params" => array_merge(array(
			array(
				"id" => "engine",
				"title" => __("Slider engine", "themerex"),
				"desc" => __("Select engine for slider. Attention! Flex and Swiper are built-in engines, all other engines appears only if corresponding plugings are installed", "themerex"),
				"value" => "flex",
				"type" => "checklist",
				"options" => $THEMEREX_shortcodes_sliders
			)),
			revslider_exists() || royalslider_exists() ? array(
			array(
				"id" => "alias",
				"title" => __("Revolution slider alias or Royal Slider ID", "themerex"),
				"desc" => __("Alias for Revolution slider or Royal slider ID", "themerex"),
				"dependency" => array(
					'engine' => array('revo','royal')
				),
				"divider" => true,
				"value" => "",
				"type" => "text"
			)) : array(), array(
			array(
				"id" => "interval",
				"title" => __("Flex&Swiper: Slides change interval", "themerex"),
				"desc" => __("Slides change interval (in milliseconds: 1000ms = 1s)", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"divider" => true,
				"value" => 5000,
				"increment" => 500,
				"min" => 0,
				"type" => "spinner"
			),
			array(
				"id" => "cat",
				"title" => __("Flex&Swiper: Category list", "themerex"),
				"desc" => __("Comma separated list of category slugs. If empty - select posts from any category or from IDs list", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"divider" => true,
				"value" => "",
				"type" => "select",
				"style" => "list",
				"multiple" => true,
				"options" => $THEMEREX_shortcodes_categories
			),
			array(
				"id" => "count",
				"title" => __("Flex&Swiper: Number of posts", "themerex"),
				"desc" => __("How many posts will be displayed? If used IDs - this parameter ignored.", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"value" => 3,
				"min" => 1,
				"max" => 100,
				"type" => "spinner"
			),
			array(
				"id" => "offset",
				"title" => __("Flex&Swiper: Offset before select posts", "themerex"),
				"desc" => __("Skip posts before select next part.", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"value" => 0,
				"min" => 0,
				"type" => "spinner"
			),
			array(
				"id" => "orderby",
				"title" => __("Flex&Swiper: Post order by", "themerex"),
				"desc" => __("Select desired posts sorting method", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"value" => "date",
				"type" => "select",
				"options" => $THEMEREX_shortcodes_sorting
			),
			array(
				"id" => "order",
				"title" => __("Flex&Swiper: Post order", "themerex"),
				"desc" => __("Select desired posts order", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"value" => "desc",
				"type" => "switch",
				"size" => "big",
				"options" => $THEMEREX_shortcodes_ordering
			),
			array(
				"id" => "ids",
				"title" => __("Flex&Swiper: Post IDs list", "themerex"),
				"desc" => __("Comma separated list of posts ID. If set - parameters above are ignored!", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "controls",
				"title" => __("Flex&Swiper: Show slider controls", "themerex"),
				"desc" => __("Show arrows inside slider", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"divider" => true,
				"value" => "yes",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "pagination",
				"title" => __("Flex&Swiper: Show slider pagination", "themerex"),
				"desc" => __("Show bullets for switch slides", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"value" => "yes",
				"type" => "checklist",
				"options" => array(
					'yes'  => __('Dots', 'themerex'), 
					'full' => __('Side Titles', 'themerex'),
					'over' => __('Over Titles', 'themerex'),
					'no'   => __('None', 'themerex')
				)
			),
			array(
				"id" => "titles",
				"title" => __("Flex&Swiper: Show titles section", "themerex"),
				"desc" => __("Show section with post's title and short post's description", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"divider" => true,
				"value" => "no",
				"type" => "checklist",
				"options" => array(
					"no"    => __('Not show', 'themerex'),
					"slide" => __('Show/Hide info', 'themerex'),
					"fixed" => __('Fixed info', 'themerex')
				)
			),
			array(
				"id" => "descriptions",
				"title" => __("Flex&Swiper: Post descriptions", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"desc" => __("Show post's excerpt max length (characters)", "themerex"),
				"value" => 0,
				"min" => 0,
				"max" => 1000,
				"increment" => 10,
				"type" => "spinner"
			),
			array(
				"id" => "links",
				"title" => __("Flex&Swiper: Post's title as link", "themerex"),
				"desc" => __("Make links from post's titles", "themerex"),
				"dependency" => array(
					'engine' => array('flex','swiper')
				),
				"value" => "yes",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			array(
				"id" => "border",
				"title" => __("Show border", "themerex"),
				"desc" => __("Show border around slider", "themerex"),
				"divider" => true,
				"value" => "none",
				"type" => "checklist",
				"options" => array(
					"none" =>  __('No border', 'themerex'),
					"light" => __('Light tablet', 'themerex'),
					"dark" =>  __('Dark tablet', 'themerex')
				)
			),
			array(
				"id" => "align",
				"title" => __("Float slider", "themerex"),
				"desc" => __("Float slider to left or right side", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_float
			), 
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)),
		"children" => array(
			"title" => __("Slide", "themerex"),
			"desc" => __("Slider item", "themerex"),
			"id" => "trx_slider_item",
			"container" => false,
			"params" => array(
				array(
					"id" => "src",
					"title" => __("URL (source) for image file", "themerex"),
					"desc" => __("Select or upload image or write URL from other site for the current slide", "themerex"),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Table
	array(
		"title" => __("Table", "themerex"),
		"desc" => __("Insert a table into post (page). ", "themerex"),
		"id" => "trx_table",
		"decorate" => true,
		"container" => true,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Table style", "themerex"),
				"desc" => __("Select table style", "themerex"),
				"value" => "regular",
				"type" => "select",
				"options" => array(
					"1" => __('Style 1', 'themerex'),
					"2" => __('Style 2', 'themerex')
				)
			),
			array(
				"id" => "size",
				"title" => __("Cells padding", "themerex"),
				"desc" => __("Select padding for the table cells", "themerex"),
				"value" => "medium",
				"type" => "select",
				"options" => array(
					"small" => __('Small', 'themerex'),
					"medium" => __('Medium', 'themerex'),
					"big" => __('Big', 'themerex')
				)
			),
			array(
				"id" => "align",
				"title" => __("Content alignment", "themerex"),
				"desc" => __("Select alignment for each table cell", "themerex"),
				"value" => "none",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => array(
					"none" => __("None", 'themerex'),
					"left" => __("Left", 'themerex'),
					"center" => __("Center", 'themerex'),
					"right" => __("Right", 'themerex')
				)
			),
			array(
				"id" => "_content_",
				"title" => __("Table content", "themerex"),
				"desc" => __("Content, created with any table-generator", "themerex"),
				"divider" => true,
				"rows" => 8,
				"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),





	// Tabs
	array(
		"title" => __("Tabs", "themerex"),
		"desc" => __("Insert tabs in your page (post)", "themerex"),
		"id" => "trx_tabs",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Tabs style", "themerex"),
				"desc" => __("Select style for tabs items", "themerex"),
				"value" => 1,
				"options" => array(
					1 => __('Style 1', 'themerex'),
					2 => __('Style 2', 'themerex')
				),
				"type" => "radio"
			),
			array(
				"id" => "initial",
				"title" => __("Initially opened tab", "themerex"),
				"desc" => __("Number of initially opened tab", "themerex"),
				"divider" => true,
				"value" => 1,
				"min" => 0,
				"type" => "spinner"
			),
			array(
				"id" => "scroll",
				"title" => __("Use scroller", "themerex"),
				"desc" => __("Use scroller to show tab content (height parameter required)", "themerex"),
				"divider" => true,
				"value" => "no",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_yes_no
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Tab", "themerex"),
			"desc" => __("Tab item", "themerex"),
			"id" => "trx_tab",
			"container" => true,
			"params" => array(
				array(
					"id" => "_title_",
					"title" => __("Tab title", "themerex"),
					"desc" => __("Current tab title", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "_content_",
					"title" => __("Tab content", "themerex"),
					"desc" => __("Current tab content", "themerex"),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),





	// Team
	array(
		"title" => __("Team", "themerex"),
		"desc" => __("Insert team in your page (post)", "themerex"),
		"id" => "trx_team",
		"decorate" => true,
		"container" => false,
		"params" => array(
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Member", "themerex"),
			"desc" => __("Team member", "themerex"),
			"id" => "trx_team_item",
			"container" => true,
			"params" => array(
				array(
					"id" => "user",
					"title" => __("Team member", "themerex"),
					"desc" => __("Select one of registered users (if present) or put name, position etc. in fields below", "themerex"),
					"value" => "",
					"type" => "select",
					"options" => $THEMEREX_shortcodes_users
				),
				array(
					"id" => "name",
					"title" => __("Name", "themerex"),
					"desc" => __("Team member's name", "themerex"),
					"dependency" => array(
						'user' => array('is_empty', 'none')
					),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "position",
					"title" => __("Position", "themerex"),
					"desc" => __("Team member's position", "themerex"),
					"dependency" => array(
						'user' => array('is_empty', 'none')
					),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "email",
					"title" => __("E-mail", "themerex"),
					"desc" => __("Team member's e-mail", "themerex"),
					"dependency" => array(
						'user' => array('is_empty', 'none')
					),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "photo",
					"title" => __("Photo", "themerex"),
					"desc" => __("Team member's photo (avatar)", "themerex"),
					"dependency" => array(
						'user' => array('is_empty', 'none')
					),
					"value" => "",
					"readonly" => false,
					"type" => "media"
				),
				array(
					"id" => "socials",
					"title" => __("Socials", "themerex"),
					"desc" => __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", "themerex"),
					"dependency" => array(
						'user' => array('is_empty', 'none')
					),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "_content_",
					"title" => __("Description", "themerex"),
					"desc" => __("Team member's short description", "themerex"),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Testimonials
	array(
		"title" => __("Testimonials", "themerex"),
		"desc" => __("Insert testimonials into post (page)", "themerex"),
		"id" => "trx_testimonials",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "title",
				"title" => __("Title", "themerex"),
				"desc" => __("Title of testimonmials block", "themerex"),
				"divider" => true,
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "style",
				"title" => __("Style", "themerex"),
				"desc" => __("Testimonials style", "themerex"),
				"divider" => true,
				"value" => "1",
				"type" => "checklist",
				"options" => array(
					"1" => __('Style 1', 'themerex'),
					"2" => __('Style 2', 'themerex'),
					"3" => __('Style 3', 'themerex')
				)
			),
			array(
				"id" => "controls",
				"title" => __("Show arrows", "themerex"),
				"desc" => __("Show control buttons. Values 'top' and 'bottom' allowed only for Style 1", "themerex"),
				"divider" => true,
				"value" => "on",
				"type" => "checklist",
				"options" => array(
					"off" => __('Off', 'themerex'),
					"on" => __('Over', 'themerex'),
					"top" => __('Top', 'themerex'),
					"bottom" => __('Bottom', 'themerex')
				)
			),
			array(
				"id" => "interval",
				"title" => __("Testimonials change interval", "themerex"),
				"desc" => __("Testimonials change interval (in milliseconds: 1000ms = 1s)", "themerex"),
				"divider" => true,
				"value" => 7000,
				"increment" => 500,
				"min" => 0,
				"type" => "spinner"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Item", "themerex"),
			"desc" => __("Testimonials item", "themerex"),
			"id" => "trx_testimonials_item",
			"container" => true,
			"params" => array(
				array(
					"id" => "name",
					"title" => __("Name", "themerex"),
					"desc" => __("Name of the testimonmials author", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "position",
					"title" => __("Position", "themerex"),
					"desc" => __("Position of the testimonmials author", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "email",
					"title" => __("E-mail", "themerex"),
					"desc" => __("E-mail of the testimonmials author", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "photo",
					"title" => __("Photo", "themerex"),
					"desc" => __("Select or upload photo of testimonmials author or write URL of photo from other site", "themerex"),
					"value" => "",
					"type" => "media"
				),
				array(
					"id" => "_content_",
					"title" => __("Testimonials text", "themerex"),
					"desc" => __("Current testimonials text", "themerex"),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),




	// Title
	array(
		"title" => __("Title", "themerex"),
		"desc" => __("Create header tag (1-6 level) with many styles", "themerex"),
		"id" => "trx_title",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "_content_",
				"title" => __("Title content", "themerex"),
				"desc" => __("Title content", "themerex"),
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			array(
				"id" => "type",
				"title" => __("Title type", "themerex"),
				"desc" => __("Title type (header level)", "themerex"),
				"divider" => true,
				"value" => "1",
				"type" => "select",
				"options" => array(
					'1' => __('Header 1', 'themerex'),
					'2' => __('Header 2', 'themerex'),
					'3' => __('Header 3', 'themerex'),
					'4' => __('Header 4', 'themerex'),
					'5' => __('Header 5', 'themerex'),
					'6' => __('Header 6', 'themerex'),
				)
			),
			array(
				"id" => "style",
				"title" => __("Title style", "themerex"),
				"desc" => __("Title style", "themerex"),
				"value" => "regular",
				"type" => "select",
				"options" => array(
					'regular' => __('Regular', 'themerex'),
					'underline' => __('Underline', 'themerex'),
					'divider' => __('Divider', 'themerex'),
					'iconed' => __('With icon (image)', 'themerex')
				)
			),
			array(
				"id" => "align",
				"title" => __("Alignment", "themerex"),
				"desc" => __("Title text alignment", "themerex"),
				"value" => "",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_align
			), 
			array(
				"id" => "color",
				"title" => __("Title color", "themerex"),
				"desc" => __("Select color for the title", "themerex"),
				"value" => "",
				"type" => "color"
			),
			array(
				"id" => "weight",
				"title" => __("Font weight", "themerex"),
				"desc" => __("Title font weight", "themerex"),
				"value" => "",
				"type" => "select",
				"size" => "medium",
				"options" => array(
					'inherit' => __('Default', 'themerex'),
					'100' => __('Thin (100)', 'themerex'),
					'300' => __('Light (300)', 'themerex'),
					'400' => __('Normal (400)', 'themerex'),
					'700' => __('Bold (700)', 'themerex')
				)
			),
			array(
				"id" => "icon",
				"title" => __('Title font icon',  'themerex'),
				"desc" => __("Select font icon for the title from Fontello icons set (if style=iconed)",  'themerex'),
				"dependency" => array(
					'style' => array('iconed')
				),
				"value" => "",
				"type" => "icons",
				"options" => $THEMEREX_shortcodes_icons
			),
			array(
				"id" => "image",
				"title" => __('or image icon',  'themerex'),
				"desc" => __("Select image icon for the title instead icon above (if style=iconed)",  'themerex'),
				"dependency" => array(
					'style' => array('iconed')
				),
				"value" => "",
				"type" => "images",
				"size" => "small",
				"options" => $THEMEREX_shortcodes_images
			),
			array(
				"id" => "picture",
				"title" => __('or URL for image file', "themerex"),
				"desc" => __("Select or upload image or write URL from other site (if style=iconed)", "themerex"),
				"dependency" => array(
					'style' => array('iconed')
				),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "size",
				"title" => __('Icon (image) size', "themerex"),
				"desc" => __("Select icon (image) size (if style='iconed')", "themerex"),
				"dependency" => array(
					'style' => array('iconed')
				),
				"value" => "medium",
				"type" => "checklist",
				"options" => array(
					'small' => __('Small', 'themerex'),
					'medium' => __('Medium', 'themerex'),
					'large' => __('Large', 'themerex'),
					'huge' => __('Huge', 'themerex')
				)
			),
			array(
				"id" => "position",
				"title" => __('Icon (image) position', "themerex"),
				"desc" => __("Select icon (image) position (if style=iconed)", "themerex"),
				"dependency" => array(
					'style' => array('iconed')
				),
				"value" => "left",
				"type" => "checklist",
				"options" => array(
					'top' => __('Top', 'themerex'),
					'left' => __('Left', 'themerex'),
					'right' => __('Right', 'themerex')
				)
			),
			array(
				"id" => "background",
				"title" => __('Show background under icon', "themerex"),
				"desc" => __("Select background under icon (if style=iconed)", "themerex"),
				"dependency" => array(
					'style' => array('iconed')
				),
				"value" => "none",
				"type" => "checklist",
				"options" => array(
					'none' => __('None', 'themerex'),
					'square' => __('Square', 'themerex'),
					'circle' => __('Circle', 'themerex')
				)
			),
			array(
				"id" => "bg_color",
				"title" => __("Icon's background color", "themerex"),
				"desc" => __("Icon's background color (if style=iconed)", "themerex"),
				"dependency" => array(
					'style' => array('iconed')
				),
				"value" => "",
				"type" => "color"
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),





	// Toggles
	array(
		"title" => __("Toggles", "themerex"),
		"desc" => __("Toggles items", "themerex"),
		"id" => "trx_toggles",
		"decorate" => true,
		"container" => false,
		"params" => array(
			array(
				"id" => "style",
				"title" => __("Toggles style", "themerex"),
				"desc" => __("Select style for display toggles", "themerex"),
				"value" => 1,
				"options" => array(
					1 => __('Style 1', 'themerex'),
					2 => __('Style 2', 'themerex'),
					3 => __('Style 3', 'themerex')
				),
				"type" => "radio"
			),
			array(
				"id" => "counter",
				"title" => __("Counter", "themerex"),
				"desc" => __("Display counter before each toggles title", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			array(
				"id" => "large",
				"title" => __("Large titles", "themerex"),
				"desc" => __("Show large titles", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			array(
				"id" => "shadow",
				"title" => __("Shadow", "themerex"),
				"desc" => __("Display shadow under toggles block", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		),
		"children" => array(
			"title" => __("Toggle", "themerex"),
			"desc" => __("Toggle item", "themerex"),
			"id" => "trx_toggle_item",
			"container" => true,
			"params" => array(
				array(
					"id" => "title",
					"title" => __("Toggle item title", "themerex"),
					"desc" => __("Title for current toggle item", "themerex"),
					"value" => "",
					"type" => "text"
				),
				array(
					"id" => "open",
					"title" => __("Open on show", "themerex"),
					"desc" => __("Open current toggle item on show", "themerex"),
					"value" => "no",
					"type" => "switch",
					"options" => $THEMEREX_shortcodes_yes_no
				),
				array(
					"id" => "_content_",
					"title" => __("Toggles item content", "themerex"),
					"desc" => __("Current toggles item content", "themerex"),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				$THEMEREX_shortcodes_id,
				$THEMEREX_shortcodes_class
			)
		)
	),



	// Tooltip
	array(
		"title" => __("Tooltip", "themerex"),
		"desc" => __("Create tooltip for selected text", "themerex"),
		"id" => "trx_tooltip",
		"decorate" => false,
		"container" => true,
		"params" => array(
			array(
				"id" => "title",
				"title" => __("Title", "themerex"),
				"desc" => __("Tooltip title (required)", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "_content_",
				"title" => __("Tipped content", "themerex"),
				"desc" => __("Highlighted content with tooltip", "themerex"),
				"divider" => true,
				"rows" => 4,
				"value" => "",
				"type" => "textarea"
			),
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Twitter
	array(
		"title" => __("Twitter", "themerex"),
		"desc" => __("Insert twitter feed into post (page)", "themerex"),
		"id" => "trx_twitter",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "user",
				"title" => __("Twitter Username", "themerex"),
				"desc" => __("Your username in the twitter account. If empty - get it from Theme Options.", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "consumer_key",
				"title" => __("Consumer Key", "themerex"),
				"desc" => __("Consumer Key from the twitter account", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "consumer_secret",
				"title" => __("Consumer Secret", "themerex"),
				"desc" => __("Consumer Secret from the twitter account", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "token_key",
				"title" => __("Token Key", "themerex"),
				"desc" => __("Token Key from the twitter account", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "token_secret",
				"title" => __("Token Secret", "themerex"),
				"desc" => __("Token Secret from the twitter account", "themerex"),
				"value" => "",
				"type" => "text"
			),
			array(
				"id" => "count",
				"title" => __("Tweets number", "themerex"),
				"desc" => __("Tweets number to show", "themerex"),
				"divider" => true,
				"value" => 3,
				"max" => 20,
				"min" => 1,
				"type" => "spinner"
			),
			array(
				"id" => "interval",
				"title" => __("Tweets change interval", "themerex"),
				"desc" => __("Tweets change interval (in milliseconds: 1000ms = 1s)", "themerex"),
				"divider" => true,
				"value" => 7000,
				"increment" => 500,
				"min" => 0,
				"type" => "spinner"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),


	// Video
	array(
		"title" => __("Video", "themerex"),
		"desc" => __("Insert video player", "themerex"),
		"id" => "trx_video",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "url",
				"title" => __("URL for video file", "themerex"),
				"desc" => __("Select video from media library or paste URL for video file from other site", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media",
				"before" => array(
					'title' => __('Choose video', 'themerex'),
					'action' => 'media_upload',
					'type' => 'video',
					'multiple' => false,
					'linked_field' => '',
					'captions' => array( 	
						'choose' => __('Choose video file', 'themerex'),
						'update' => __('Select video file', 'themerex')
					)
				),
				"after" => array(
					'icon' => 'icon-cancel',
					'action' => 'media_reset'
				)
			),
			array(
				"id" => "autoplay",
				"title" => __("Autoplay video", "themerex"),
				"desc" => __("Autoplay video on page load", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			array(
				"id" => "title",
				"title" => __("Show title bar", "themerex"),
				"desc" => __("Show title bar above video frame", "themerex"),
				"value" => "off",
				"type" => "switch",
				"options" => $THEMEREX_shortcodes_on_off
			),
			array(
				"id" => "image",
				"title" => __("Cover image", "themerex"),
				"desc" => __("Select or upload image or write URL from other site for video preview", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	),




	// Zoom
	array(
		"title" => __("Zoom", "themerex"),
		"desc" => __("Insert the image with zoom/lens effect", "themerex"),
		"id" => "trx_zoom",
		"decorate" => false,
		"container" => false,
		"params" => array(
			array(
				"id" => "url",
				"title" => __("Main image", "themerex"),
				"desc" => __("Select or upload main image", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "over",
				"title" => __("Overlaping image", "themerex"),
				"desc" => __("Select or upload overlaping image", "themerex"),
				"readonly" => false,
				"value" => "",
				"type" => "media"
			),
			array(
				"id" => "border",
				"title" => __("Show border", "themerex"),
				"desc" => __("Show border around image", "themerex"),
				"value" => "none",
				"type" => "checklist",
				"options" => array(
					"none" =>  __('No border', 'themerex'),
					"light" => __('Light tablet', 'themerex'),
					"dark" =>  __('Dark tablet', 'themerex')
				)
			),
			array(
				"id" => "align",
				"title" => __("Float zoom", "themerex"),
				"desc" => __("Float zoom to left or right side", "themerex"),
				"value" => "",
				"type" => "checklist",
				"dir" => "horizontal",
				"options" => $THEMEREX_shortcodes_float
			), 
			THEMEREX_shortcodes_width(),
			THEMEREX_shortcodes_height(),
			$THEMEREX_shortcodes_margin_top,
			$THEMEREX_shortcodes_margin_bottom,
			$THEMEREX_shortcodes_margin_left,
			$THEMEREX_shortcodes_margin_right,
			$THEMEREX_shortcodes_id,
			$THEMEREX_shortcodes_class
		)
	)
	
);



// Filters for shortcodes handling
//----------------------------------------------------------------------

// Enable/Disable shortcodes in the excerpt
function sc_excerpt_shortcodes($content) {
	$content = do_shortcode($content);
	//$content = strip_shortcodes($content);
	return $content;
}


// Prepare shortcodes in content
function sc_prepare_content() {
	if (function_exists('sc_clear_around')) {
		$filters = array(
			array('themerex', 'sc', 'clear', 'around'),
			array('widget', 'text'),
			array('the', 'excerpt'),
			array('the', 'content')
		);
		if (function_exists('is_woocommerce')) {
			$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
			$filters[] = array('woocommerce', 'short', 'description');
		}
		foreach ($filters as $flt)
			add_filter(join('_', $flt), 'sc_clear_around');
	}
	if (function_exists('p_clear_around')) {
		add_filter('themerex_p_clear_around', 'p_clear_around');
	}
}


// Remove spaces and line breaks around shortcode content
function sc_clear_around($content) {
	$content = str_replace("\r\n", "\n", $content);
	//$content = preg_replace("/\](\s|\n)*\[/", "][", $content);
	$content = get_custom_option('clear_shortcodes')=='no'
		? preg_replace("/\](\s|\n)*\[/", "][", $content)
		: preg_replace(array(
			"/\](\s|\n|<p>|<\/p>|<br>|<br\/>|<br \/>)+/",
			"/(\s|\n|<p>|<\/p>|<br>|<br\/>|<br \/>)+\[/"
		),
			array(
				"] ",
				" ["
			),
			$content);
	return $content;
}


// Remove p and br around div and h1-h6
function p_clear_around($content) {
	$content = preg_replace(array(
		"/(<p>|<br[\s\/]?>(\s|\n)*)*(<div|<h[1-6])/",
		"/(<\/div>|<\/h[1-6]>)((\s|\n)*<\/p>|<br[\s\/]?>)*/"
	),
		array(
			"$3",
			"$1"
		),
		$content);
	return $content;
}


// Prepare scripts
//----------------------------------------------------------------------

// Shortcodes support scripts
add_action('admin_enqueue_scripts', 'sc_selector_load_js');
function sc_selector_load_js() {
	if (is_themerex_options_used()) {
		themerex_options_load_scripts();
		themerex_shortcodes_load_scripts();
	}
}	


// Shortcodes support scripts
add_action('admin_head', 'sc_selector_prepare_js');
function sc_selector_prepare_js() {
	if (is_themerex_options_used()) {
		themerex_options_prepare_js('general');
		themerex_shortcodes_prepare_js();
	}
}	

// ThemeREX shortcodes load scripts
function themerex_shortcodes_load_scripts() {
	themerex_enqueue_script( 'themerex-shortcodes-script', themerex_get_file_url('/shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
	themerex_enqueue_script( 'themerex-selection-script',  themerex_get_file_url('/js/jquery.selection.js'), array('jquery'), null, true );
}

// ThemeREX shortcodes prepare scripts
function themerex_shortcodes_prepare_js() {
	global $THEMEREX_shortcodes;
	?>
	<script type="text/javascript">
		var THEMEREX_shortcodes = JSON.parse('<?php echo str_replace("'", "\\'", json_encode($THEMEREX_shortcodes)); ?>');
		var THEMEREX_shortcodes_cp = '<?php echo is_admin() ? 'wp' : 'internal'; ?>';
	</script>
	<?php
}

// Show shortcodes list in admin editor
add_action('media_buttons','sc_selector_add_in_toolbar', 11);
function sc_selector_add_in_toolbar(){
	global $THEMEREX_shortcodes;
	
	$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.__('- Select Shortcode -', 'themerex').'&nbsp;</option>';

	foreach ($THEMEREX_shortcodes as $idx => $sc) {
		$shortcodes_list .= '<option value="' . $idx . '" title="' . esc_attr($sc['desc']) . '">' . esc_attr($sc['title']) . '</option>';
	}

	$shortcodes_list .= '</select>';

	echo balanceTags($shortcodes_list);
}


function sc_param_is_on($prm) {
	return $prm>0 || in_array(themerex_strtolower($prm), array('true', 'on', 'yes', 'show'));
}
function sc_param_is_off($prm) {
	return empty($prm) || $prm===0 || in_array(themerex_strtolower($prm), array('false', 'off', 'no', 'none', 'hide'));
}
function sc_param_is_inherit($prm) {
	return in_array(themerex_strtolower($prm), array('inherit', 'default'));
}
?>
