<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 */


/* ========================= Blog utils section ============================== */

// Return template page id
if (!function_exists('getTemplatePageId')) {
	function getTemplatePageId($name) {
		$posts = getPostsByMetaValue('_wp_page_template', $name . '.php', ARRAY_A);
		$id = 0;
		for ($i=0; $i<count($posts); $i++) {
			if ($posts[$i]['post_status'] == 'publish') {
				$id = $posts[$i]['post_id'];
				break;
			}
		}
		return $id;
	}
}

// Return any type categories objects by post id
if (!function_exists('getCategoriesByPostId')) {
	function getCategoriesByPostId($post_id = 0, $cat_types = array('category')) {
		return getTaxonomiesByPostId($post_id, $cat_types);
	}
}

// Return tags objects by post id
if (!function_exists('getTagsByPostId')) {
	function getTagsByPostId($post_id = 0) {
		return getTaxonomiesByPostId($post_id, array('post_tag'));
	}
}


// Return taxonomies objects by post id
if (!function_exists('getTaxonomiesByPostId')) {
	function getTaxonomiesByPostId($post_id = 0, $tax_types = array('post_format')) {
		global $wpdb, $wp_query;
		if (!$post_id) $post_id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$sql = "SELECT DISTINCT terms.*, tax.taxonomy, tax.parent, tax.count"
				. " FROM $wpdb->term_relationships AS rel"
				. " LEFT JOIN {$wpdb->term_taxonomy} AS tax ON rel.term_taxonomy_id=tax.term_taxonomy_id"
				. " LEFT JOIN {$wpdb->terms} AS terms ON tax.term_id=terms.term_id"
				. " WHERE rel.object_id = ".esc_sql($post_id).""
					. " AND tax.taxonomy IN ('" . join("','", esc_sql($tax_types)) . "')"
				. " ORDER BY terms.name";
		$taxes = $wpdb->get_results($sql, ARRAY_A);
		for ($i=0; $i<count($taxes); $i++) {
			$taxes[$i]['link'] = get_term_link($taxes[$i]['slug'], $taxes[$i]['taxonomy']);
		}
		return $taxes;
	}
}

// Return taxonomies objects by post id
if (!function_exists('getTermsByTaxonomy')) {
	function getTermsByTaxonomy($tax_types = array('post_format')) {
		global $wpdb, $wp_query;
		$sql = "SELECT terms.*, tax.taxonomy, tax.parent, tax.count"
				. " FROM $wpdb->term_relationships AS rel"
				. " LEFT JOIN {$wpdb->term_taxonomy} AS tax ON rel.term_taxonomy_id=tax.term_taxonomy_id"
				. " LEFT JOIN {$wpdb->terms} AS terms ON tax.term_id=terms.term_id"
				. " WHERE tax.taxonomy IN ('" . join("','", esc_sql($tax_types)) . "')"
				. " ORDER BY terms.name";
		$taxes = $wpdb->get_results($sql, OBJECT);
		for ($i=0; $i<count($taxes); $i++) {
			$taxes[$i]->link = get_term_link($taxes[$i]->slug, $taxes[$i]->taxonomy);
		}
		return $taxes;
	}
}


// Return id closest category to desired parent
if (!function_exists('getParentCategory')) {
	function getParentCategory($id, $parent_id=0) {
		$val = null;
		do {
			$cat = get_term_by( 'id', $id, 'category', ARRAY_A);
			if ($cat['parent']==$parent_id) {
				$val = $cat;
				$val['link'] = get_term_link($val['slug'], $val['taxonomy']);
				break;
			}
			$id = $cat['parent'];
		} while ($id);
		return $val;
	}
}

// Return id highest category with desired property in array values
if (!function_exists('getParentCategoryByProperty')) {
	function getParentCategoryByProperty($id, $prop, $values, $highest=true) {
		if ((int) $id == 0) {
			$cat = get_term_by( 'slug', $id, 'category', OBJECT);
			$id = $cat->term_id;
		}
		if (!is_array($values)) $values = array($values);
		$val = $id;
		do {
			if ($props = category_custom_fields_get($id)) {
				if (isset($props[$prop]) && !empty($props[$prop]) && in_array($props[$prop], $values)) {
					$val = $id;
					if (!$highest) break;
				}
			}
			$cat = get_term_by( 'id', $id, 'category', ARRAY_A);
			$id = $cat['parent'];
		} while ($id);
		return $val;
	}
}


// Return string with <select> tags for each taxonomy
if (!function_exists('getTermsFilters')) {
	function getTermsFilters($taxes) {
		$output = '';
		foreach ($taxes as $tax) {
			$list = get_transient("themerex_terms_filter_".$tax);
			if (!$list) {
				$list = '';
				$tax_obj = get_taxonomy($tax);
				$terms = getTermsHierarchicalList(getTermsByTaxonomy(array($tax)));
				if (count($terms) > 0) {
					$tax_slug = str_replace(array('post_tag'), array('tag'), $tax);
					$list .= "<select name='$tax_slug' id='$tax_slug' class='postform'>"
							.  "<option value=''>" . $tax_obj->labels->all_items . "</option>";
					foreach ($terms as $slug=>$name) {
						$list .= '<option value='. $slug . (isset($_REQUEST[$tax_slug]) && $_REQUEST[$tax_slug] == $slug || (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == $tax_slug && isset($_REQUEST['term']) && $_REQUEST['term'] == $slug) ? ' selected="selected"' : '') . '>' . $name . '</option>';
					}
					$list .=  "</select>";
				}
			}
			set_transient("themerex_terms_filter_".$tax, $list, 0);
			$output .= $list;
		}
		return $output;
	}
}


// Return terms list as hierarchical array
if (!function_exists('getTermsHierarchicalList')) {
	function getTermsHierarchicalList($terms, $opt=array()) {
		$opt = themerex_array_merge(array(
			'prefix_key' => '',
			'prefix_level' => '&nbsp;',
			'parent' => 0,
			'level' => ''
			), $opt);
		$rez = array();
		if (count($terms) > 0) {
			foreach ($terms as $term) {
				if ((is_object($term) ? $term->parent : $term['parent'])!=$opt['parent']) continue;
				$slug = is_object($term) ? $term->slug : $term['slug'];
				$name = is_object($term) ? $term->name : $term['name'];
				$count = is_object($term) ? $term->count : $term['count'];
				$rez[$opt['prefix_key'].$slug] = ($opt['level'] ? $opt['level'].' ' : '').$name.($count ? ' ('.$count.')' : '');
				$rez = themerex_array_merge($rez, getTermsHierarchicalList($terms, array(
					'prefix_key' => $opt['prefix_key'],
					'prefix_level' => $opt['prefix_level'],
					'parent' => is_object($term) ? $term->term_id : $term['term_id'],
					'level' => $opt['level'].$opt['prefix_level']
					)
				));
			}
		}
		return $rez;
	}
}


// Add sorting parameter in query arguments
if (!function_exists('addSortOrderInQuery')) {
	function addSortOrderInQuery($args, $orderby='', $order='') {
		if (empty($order)) $order = get_custom_option('blog_order');
		if (empty($orderby)) $orderby = get_custom_option('blog_sort');
		$q = array();
		$q['order'] = $order=='asc' ? 'asc' : 'desc';
		if ($orderby == 'author_rating') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'reviews_avg';
			$q['meta_query'] = array(
				   array(
					   'key' => 'reviews_avg',
					   'value' => 0,
					   'compare' => '>',
					   'type' => 'NUMERIC'
				   )
			);
		} else if ($orderby == 'users_rating') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'reviews_avg2';
			$q['meta_query'] = array(
				   array(
					   'key' => 'reviews_avg2',
					   'value' => 0,
					   'compare' => '>',
					   'type' => 'NUMERIC'
				   )
			);
		} else if ($orderby == 'views') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'post_views_count';
		} else if ($orderby == 'comments') {
			$q['orderby'] = 'comment_count';
		} else if ($orderby == 'title' || $orderby == 'alpha') {
			$q['orderby'] = 'title';
		} else if ($orderby == 'rand' || $orderby == 'random')  {
			$q['orderby'] = 'rand';
		} else {
			$q['orderby'] = 'date';
		}
		foreach ($q as $mk=>$mv) {
			if (is_array($args))
				$args[$mk] = $mv;
			else
				$args->set($mk, $mv);
		}
		return $args;
	}
}


// Add post type and posts list or categories list in query arguments
if (!function_exists('addPostsAndCatsInQuery')) {
	function addPostsAndCatsInQuery($args, $ids='', $cat='') {
		if (!empty($ids)) {
			$args['post_type'] = array('post', 'page');
			$args['post__in'] = explode(',', str_replace(' ', '', $ids));
		} else {
			$args['post_type'] = 'post';
			if (!empty($cat)) {
				$cats = explode(',', $cat);
				if (count($cats) > 1) {
					$cats_ids = array();
					foreach($cats as $c) {
						$c = trim(chop($c));
						if (empty($c)) continue;
						if ((int) $c == 0) {
							$cat_term = get_term_by( 'slug', $c, 'category', ARRAY_A);
							if ($cat_term) $c = $cat_term['term_id'];
						}
						if ($c==0) continue;
						$cats_ids[] = (int) $c;
						$children = get_categories( array(
							'type'                     => 'post',
							'child_of'                 => $c,
							'hide_empty'               => 0,
							'hierarchical'             => 0,
							'taxonomy'                 => 'category',
							'pad_counts'               => false
						));					
						foreach($children as $c) {
							if (!in_array((int) $c->term_id, $cats_ids)) $cats_ids[] = (int) $c->term_id;
						}
					}
					if (count($cats_ids) > 0) {
						$args['category__in'] = $cats_ids;
					}
				} else {
					if ((int) $cat > 0) 
						$args['cat'] = (int) $cat;
					else
						$args['category_name'] = $cat;
				}
			}
		}
		return $args;
	}
}


// Add meta parameters in query arguments
if (!function_exists('addFiltersInQuery')) {
	function addFiltersInQuery($args, $filters=false) {
		if (is_array($filters) && count($filters)>0) {
			foreach ($filters as $v) {
				$found = false;
				if (in_array($v, array('reviews', 'thumbs'))) {							// Filter with meta_query
					if (!isset($args['meta_query']))
						$args['meta_query'] = array();
					else {
						for ($i=0; $i<count($args['meta_query']); $i++) {
							if ($args['meta_query'][$i]['meta_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['meta_query']['relation'] = 'AND';
						if ($v == 'reviews') {
							$args['meta_query'][] = array(
								'meta_filter' => $v,
								'key' => 'reviews_avg',
								'value' => 0,
								'compare' => '>',
								'type' => 'NUMERIC'
							);
						} else if ($v == 'thumbs') {
							$args['meta_query'][] = array(
								'meta_filter' => $v,
								'key' => '_thumbnail_id',
								'value' => false,
								'compare' => '!='
							);
						}
					}
				} else if (in_array($v, array('video', 'audio', 'gallery'))) {			// Filter with tax_query
					if (!isset($args['tax_query']))
						$args['tax_query'] = array();
					else {
						for ($i=0; $i<count($args['tax_query']); $i++) {
							if ($args['tax_query'][$i]['tax_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['tax_query']['relation'] = 'AND';
						 if ($v == 'video') {
							$args['tax_query'][] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-video' )
							);
						} else if ($v == 'audio') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-audio' )
							);
						} else if ($v == 'gallery') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-gallery' )
							);
						}
					}
				}
			}
		}
		return $args;
	}
}


// Return breadcrumbs path
if (!function_exists('showBreadcrumbs')) {
	function showBreadcrumbs($args=array()) {
		global $wp_query, $post;
		
		$args = array_merge(array(
			'home' => __('Home', 'themerex'),		// Home page title (if empty - not showed)
			'home_url' => '',						// Home page url
			'show_all_filters' => true,				// Add "All photos" (All videos) before categories list
			'show_all_posts' => true,				// Add "All posts" at start 
			'truncate_title' => 50,					// Truncate all titles to this length (if 0 - no truncate)
			'truncate_add' => '...',				// Append truncated title with this string
			'delimiter' => ' <i class="icon-right-open-mini"></i> ',	// Delimiter between breadcrumbs items
			'max_levels' => get_theme_option('breadcrumbs_max_level'),	// Max categories in the path (0 - unlimited)
			'echo' => true							// If true - show on page, else - only return value
			), is_array($args) ? $args : array( 'home' => $args ));
	
		$rez = '';
		$rez2 = '';
		$rez_all =  '';
		$rez_level = '';
		$type = getBlogType();
		$title = getShortString(getBlogTitle(), $args['truncate_title'], $args['truncate_add']);
		$cat = '';
		$parentTax = '';
		$level = 0;
		if ($args['max_levels']<=0) $args['max_levels'] = 999;
		$args['delimiter'] = '<span class="breadcrumbs_delimiter">'.$args['delimiter'].'</span>';
		if ( !in_array($type, array('home', 'frontpage')) ) {
			$need_reset = true;
			$parent = 0;
			$post_id = 0;
			if ($type == 'page' || $type == 'attachment') {
				$pageParentID = isset($wp_query->post->post_parent) ? $wp_query->post->post_parent : 0;
				$post_id = $type == 'page' ? (isset($wp_query->post->ID) ? $wp_query->post->ID : 0) : $pageParentID;
				while ($pageParentID > 0) {
					$pageParent = get_post($pageParentID);
					$level++;
					if ($level > $args['max_levels'])
						$rez_level = '...';
					else
						$rez2 = '<a class="cat_post" href="' . get_permalink($pageParent->ID) . '">' . getShortString($pageParent->post_title, $args['truncate_title'], $args['truncate_add']) . '</a>' . (!empty($rez2) ? $args['delimiter'] : '') . $rez2;
					if (($pageParentID = $pageParent->post_parent) > 0) $post_id = $pageParentID;
				}
			} else if ($type=='single')
				$post_id =  isset($wp_query->post->ID) ? $wp_query->post->ID : 0;
			
			$depth = 0;
			$ex_cats = explode(',', get_theme_option('exclude_cats'));
			$taxonomy = themerex_strpos($type, 'woocommerce')!==false 
				? array('product_cat') 
				: (themerex_strpos($type, 'tribe')!==false 
					? array('tribe_events_cat') 
					: array('category'));
			do {
				if ($depth++ == 0) {
					if (in_array($type, array('single', 'attachment', 'woocommerce_product', 'tribe_event'))) {
						if (!in_array($type, array('woocommerce_product', 'tribe_event')) && $args['show_all_filters']) {
							$post_format = get_post_format($post_id);
							if (($tpl_id = getTemplatePageId('only-'.$post_format)) > 0) {
								$level++;
								if ($level > $args['max_levels'])
									$rez_level = '...';
								else
									$rez_all .= (!empty($rez_all) ? $args['delimiter'] : '') . '<a class="all" href="' . get_permalink($tpl_id) . '">' . sprintf(__('All %s', 'themerex'), getPostFormatName($post_format, false)) . '</a>';
							}
						}
						$cats = getCategoriesByPostId( $post_id, $taxonomy );
						$cat = $cats ? $cats[0] : false;
						if ($cat) {
							if (!in_array($cat['term_id'], $ex_cats)) {
								$cat_link = get_term_link($cat['slug'], $cat['taxonomy']);
								$level++;
								if ($level > $args['max_levels'])
									$rez_level = '...';
								else
									$rez2 = '<a class="cat_post" href="' . $cat_link . '">' . getShortString($cat['name'], $args['truncate_title'], $args['truncate_add']) . '</a>' . (!empty($rez2) ? $args['delimiter'] : '') . $rez2;
							}
						} else {
							$post_type = get_post_type($post_id);
							$parentTax = 'category' . ($post_type == 'post' ? '' : '_' . $post_type);
						}
					} else if ( $type == 'category' ) {
						$cat = get_term_by( 'id', get_query_var( 'cat' ), 'category', ARRAY_A);
					} else if ( themerex_strpos($type, 'woocommerce')!==false ) {
						if ( is_product_category() ) {
							$cat = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', ARRAY_A);
						}
					} else if ( themerex_strpos($type, 'tribe')!==false ) {
						if ( tribe_is_event_category() ) {
							$cat = get_term_by( 'slug', get_query_var( 'tribe_events_cat' ), 'tribe_events_cat', ARRAY_A);
						}
					}
					if ($cat) {
						$parent = $cat['parent'];
						$parentTax = $cat['taxonomy'];
					}
				}
				if ($parent) {
					$cat = get_term_by( 'id', $parent, $parentTax, ARRAY_A);
					if ($cat) {
						if (!in_array($cat['term_id'], $ex_cats)) {
							$cat_link = get_term_link($cat['slug'], $cat['taxonomy']);
							$level++;
							if ($level > $args['max_levels'])
								$rez_level = '...';
							else
								$rez2 = '<a class="cat_parent" href="' . $cat_link . '">' . getShortString($cat['name'], $args['truncate_title'], $args['truncate_add']) . '</a>' . (!empty($rez2) ? $args['delimiter'] : '') . $rez2;
						}
						$parent = $cat['parent'];
					}
				}
			} while ($parent);
	
			if (themerex_strpos($type, 'woocommerce')!==false && ($shop_id=get_option('woocommerce_shop_page_id'))>0 && !in_array(themerex_strtolower($title), array(themerex_strtolower($shop_title=getPostTitle($shop_id))))) {
				$rez_all = '<a class="all" href="' . get_permalink($shop_id) . '">' . $shop_title . '</a>' . (!empty($rez_all) ? $args['delimiter'] : '') .  $rez_all;
			}
			if (themerex_strpos($type, 'tribe')!==false && !in_array(themerex_strtolower($title), array(__( 'All Events', 'themerex' ), __( 'Tribe Events', 'themerex' )))) {
				$rez_all = '<a class="all" href="' . tribe_get_events_link() . '">' . __( 'All Events', 'themerex') . '</a>' . (!empty($rez_all) ? $args['delimiter'] : '') . $rez_all;
			}
			if ($args['show_all_posts'] && !in_array(themerex_strtolower($title), array(themerex_strtolower(__( 'All Posts', 'themerex' )))) && ($blog_id = getTemplatePageId('blog')) > 0) {
				$rez_all = '<a class="all" href="' . get_permalink($blog_id) . '">' . __( 'All Posts', 'themerex') . '</a>' . (!empty($rez_all) ? $args['delimiter'] : '') . $rez_all;
			}
	
			$rez3 = '';
			if ($type == 'tribe_day' && is_object($post)) {
				$rez3 .= (!empty($rez3) ? $args['delimiter'] : '') . '<a class="cat_parent" href="' . tribe_get_gridview_link(false) . '">' . date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())) . '</a>';
			} else if (themerex_strpos($type, 'woocommerce')===false && is_archive() && is_object($post)) {
				$year  = get_the_time('Y'); 
				$month = get_the_time('m'); 
				if (is_day() || is_month())
					$rez3 .= (!empty($rez3) ? $args['delimiter'] : '') . '<a class="cat_parent" href="' . get_year_link( $year ) . '">' . $year . '</a>';
				if (is_day())
					$rez3 .= (!empty($rez3) ? $args['delimiter'] : '') . '<a class="cat_parent" href="' . get_month_link( $year, $month ) . '">' . prepareDateForTranslation(get_the_date( 'F' )) . '</a>';
			}
	
	
			if (!is_front_page()) {	// && !is_home()
				$rez .= (isset($args['home']) && $args['home']!='' ? '<a class="home" href="' . ($args['home_url'] ? $args['home_url'] : home_url()) . '">' . $args['home'] . '</a>' . $args['delimiter'] : '') 
					. (!empty($rez_all) ? $rez_all . $args['delimiter'] : '')
					. (!empty($rez_level) ? $rez_level . $args['delimiter'] : '')
					. (!empty($rez2)    ? $rez2 . $args['delimiter'] : '')
					. (!empty($rez3)    ? $rez3 . $args['delimiter'] : '')
					. ($title ? '<span class="current">' . $title . '</span>' : '');
			}
		}
		if ($args['echo'] && !empty($rez)) echo balanceTags($rez);
		return $rez;
	}
}


// Return blog records type
if (!function_exists('getBlogType')) {
	function getBlogType($query=null) {
	global $wp_query;
		if ( $query===null ) $query = $wp_query;
		$page = '';
		if (is_woocommerce_page()) {
			if (is_shop()) 					$page = 'woocommerce_shop';
			else if (is_product_category())	$page = 'woocommerce_category';
			else if (is_product_tag())		$page = 'woocommerce_tag';
			else if (is_product())			$page = 'woocommerce_product';
			else if (is_cart())				$page = 'woocommerce_cart';
			else if (is_checkout())			$page = 'woocommerce_checkout';
			else if (is_account_page())		$page = 'woocommerce_account';
			else							$page = 'woocommerce';
		} else if (is_tribe_events_page()) {
			//$tribe_ecp = TribeEvents::instance();
			if (/*tribe_is_day()*/ isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='day') 			$page = 'tribe_day';
			else if (/*tribe_is_month()*/ isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='month')	$page = 'tribe_month';
			else if (is_single())																									$page = 'tribe_event';
			else if (/*tribe_is_event_venue()*/		isset($query->tribe_is_event_venue) && $query->tribe_is_event_venue)			$page = 'tribe_venue';
			else if (/*tribe_is_event_organizer()*/	isset($query->tribe_is_event_organizer) && $query->tribe_is_event_organizer)	$page = 'tribe_organizer';
			else if (/* tribe_is_event_category()*/	isset($query->tribe_is_event_category) && $query->tribe_is_event_category)		$page = 'tribe_category';
			else if (/*is_tax($tribe_ecp->get_event_taxonomy())*/ is_tag())															$page = 'tribe_tag';
			else if (isset($query->query_vars['eventDisplay']) && $query->query_vars['eventDisplay']=='upcoming')					$page = 'tribe_list';
			else																													$page = 'tribe';
		} else if (isset($query->queried_object) && isset($query->queried_object->post_type) && $query->queried_object->post_type=='page')
			$page = get_post_meta($query->queried_object_id, '_wp_page_template', true);
		else if (isset($query->query_vars['page_id']))
			$page = get_post_meta($query->query_vars['page_id'], '_wp_page_template', true);
		else if (isset($query->queried_object) && isset($query->queried_object->taxonomy))
			$page = $query->queried_object->taxonomy;
	
		if (  $page == 'blog.php')			// || is_page_template( 'blog.php' ) )
			return 'blog';
		else if ( themerex_strpos($page, 'woocommerce')!==false )			// WooCommerce
			return $page;
		else if ( themerex_strpos($page, 'tribe')!==false )					// TribeEvents
			return $page;
		else if ( $query && $query->is_404())		// || is_404() ) 					// -------------- 404 error page
			return 'error';
		else if ( $query && $query->is_search())	// || is_search() ) 				// -------------- Search results
			return 'search';
		else if ( $query && $query->is_day())		// || is_day() )					// -------------- Archives daily
			return 'archives_day';
		else if ( $query && $query->is_month())		// || is_month() ) 				// -------------- Archives monthly
			return 'archives_month';
		else if ( $query && $query->is_year())		// || is_year() )  				// -------------- Archives year
			return 'archives_year';
		else if ( $query && $query->is_category())	// || is_category() )  		// -------------- Category
			return 'category';
		else if ( $query && $query->is_tag())		// || is_tag() ) 	 				// -------------- Tag posts
			return 'tag';
		else if ( $query && $query->is_author())	// || is_author() )				// -------------- Author page
			return 'author';
		else if ( $query && $query->is_attachment())	// || is_attachment() )
			return 'attachment';
		else if ( $query && $query->is_single())	// || is_single() )				// -------------- Single post
			return 'single';
		else if ( $query && $query->is_page())		// || is_page() )
			return 'page';
		else										// -------------- Home page
			return 'home';
	}
}


// Return blog title
if (!function_exists('getBlogTitle')) {
	function getBlogTitle() {
		global $wp_query;
	
		$page = getBlogType();

		if ( themerex_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$cat = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', ARRAY_A);
				return $cat['name'];
			} else if ( $page == 'woocommerce_tag' ) {
				return sprintf( __( 'Tag: %s', 'themerex' ), single_tag_title( '', false ) );
			} else if ( $page == 'woocommerce_cart' ) {
				return __( 'Your cart', 'themerex' );
			} else if ( $page == 'woocommerce_checkout' ) {
				return __( 'Checkout', 'themerex' );
			} else if ( $page == 'woocommerce_account' ) {
				return __( 'Account', 'themerex' );
			} else if ( $page == 'woocommerce_product' ) {
				return getPostTitle();
			} else {
				if (($page_id=get_option('woocommerce_shop_page_id')) > 0)
					return getPostTitle($page_id);	//__( 'Shop', 'themerex' );
			}
		} else if ( themerex_strpos($page, 'tribe')!==false ) {
			//return tribe_get_events_title();
			if ( $page == 'tribe_category' ) {
				$cat = get_term_by( 'slug', get_query_var( 'tribe_events_cat' ), 'tribe_events_cat', ARRAY_A);
				return $cat['name'];
			} else if ( $page == 'tribe_tag' ) {
				return sprintf( __( 'Tag: %s', 'themerex' ), single_tag_title( '', false ) );
			} else if ( $page == 'tribe_venue' ) {
				return sprintf( __( 'Venue: %s', 'themerex' ), tribe_get_venue());
			} else if ( $page == 'tribe_organizer' ) {
				return sprintf( __( 'Organizer: %s', 'themerex' ), tribe_get_organizer());
			} else if ( $page == 'tribe_day' ) {
				return sprintf( __( 'Daily Events: %s', 'themerex' ), date_i18n(tribe_get_date_format(true), strtotime($wp_query->get('start_date'))) );
			} else if ( $page == 'tribe_month' ) {
				return sprintf( __( 'Monthly Events: %s', 'themerex' ), date_i18n(tribe_get_option('monthAndYearFormat', 'F Y' ), strtotime(tribe_get_month_view_date())));
			} else if ( $page == 'tribe_event' ) {
				return getPostTitle();
			} else {
				return __( 'Tribe Events', 'themerex' );
			}
		} else if ( $page == 'blog' )
			return __( 'All Posts', 'themerex' );
		else if ( $page == 'author' ) {
			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
			return sprintf(__('Author page: %s', 'themerex'), $curauth->display_name);
		} else if ( $page == 'error' )
			return __('URL not found', 'themerex');
		else if ( $page == 'search' )
			return sprintf( __( 'Search Results for: %s', 'themerex' ), get_search_query() );
		else if ( $page == 'archives_day' )
			return sprintf( __( 'Daily Archives: %s', 'themerex' ), prepareDateForTranslation(get_the_date()) );
		else if ( $page == 'archives_month' )
			return sprintf( __( 'Monthly Archives: %s', 'themerex' ), prepareDateForTranslation(get_the_date( 'F Y' )) );
		else if ( $page == 'archives_year' )
			return sprintf( __( 'Yearly Archives: %s', 'themerex' ), get_the_date( 'Y' ) );
		 else if ( $page == 'category' )
			return sprintf( __( '%s', 'themerex' ), single_cat_title( '', false ) );
		else if ( $page == 'tag' )
			return sprintf( __( 'Tag: %s', 'themerex' ), single_tag_title( '', false ) );
		else if ( $page == 'attachment' )
			return sprintf( __( 'Attachment: %s', 'themerex' ), getPostTitle());
		else if ( $page == 'single' )
			return getPostTitle();
		else if ( $page == 'page' )
			return getPostTitle();				//return $wp_query->post->post_title;
		else
			return get_bloginfo('name', 'raw');	// Unknown pages - as homepage
	}
}


// Show pages links below list or single page
if (!function_exists('showPagination')) {
	function showPagination($args=array()) {
		$args = array_merge(array(
			'offset' => 0,				// Offset to first showed record
			'id' => 'pagination',		// 'id' attribute
			'class' => 'pagination',	// 'class' attribute
			'button_class' => '',		// 'class' attribute for each page button
			'style' => 'pages',
			'show_pages' => 5,
			'pages_in_group' => 10,
			'pages_text' => '', 		//__('Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'themerex'),
			'current_text' => "%PAGE_NUMBER%",
			'page_text' => "%PAGE_NUMBER%",
			'first_text'=> __('&laquo; First', 'themerex'),
			'last_text' => __("Last &raquo;", 'themerex'),
			'prev_text' => __("&laquo; Prev", 'themerex'),
			'next_text' => __("Next &raquo;", 'themerex'),
			'dot_text' => "&hellip;",
			'before' => '',
			'after' => '',
			),  is_array($args) ? $args 
				: (is_int($args) ? array( 'offset' => $args ) 		// If send number parameter - use it as offset
					: array( 'id' => $args, 'class' => $args )));	// If send string parameter - use it as 'id' and 'class' name
		if (empty($args['before']))	$args['before'] = "<div id=\"{$args['id']}\" class=\"{$args['class']}\">";
		if (empty($args['after'])) 	$args['after'] = "</div>";
		if (!is_single()) {
			showBlogPageNav($args);
		} else {
			showSinglePageNav($args);
		}
	}
}

// Single page nav or used if no pagenavi
if (!function_exists('showSinglePageNav')) {
	function showSinglePageNav( $args ) {
		global $wp_query, $post;
		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );
			if ( ! $next && ! $previous )
				return;
		}
		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
			return;
		$nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';
		?>
		<nav role="navigation" id="<?php echo esc_attr( $args['id'] ); ?>" class="<?php echo esc_attr($args['class']); ?>">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'themerex' ); ?></h1>
			<?php if ( is_single() ) : // navigation links for single posts ?>
				<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . $args['prev_text'] . '</span> %title' ); ?>
				<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . $args['next_text'] . '</span>' ); ?>
			<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
				<?php if ( get_next_posts_link() ) : ?>
					<div class="nav-previous"><?php next_posts_link( $args['prev_text'] ); ?></div>
				<?php endif; ?>
				<?php if ( get_previous_posts_link() ) : ?>
					<div class="nav-next"><?php previous_posts_link( $args['next_text'] ); ?></div>
				<?php endif; ?>
		<?php endif; ?>
		</nav>
		<?php
	}
}

// Get pages navigation buttons
if (!function_exists('showBlogPageNav')) {
	function showBlogPageNav($opt) {
		global $wp_query;
	
		$output = '';
		
		if (!is_single()) {
			$num_posts = $wp_query->found_posts - ($opt['offset'] > 0 ? $opt['offset'] : 0);
			$posts_per_page = intval(get_query_var('posts_per_page'));
			$cur_page = intval(get_query_var('paged'));
			if ($cur_page==0) $cur_page = intval(get_query_var('page'));
			if (empty($cur_page) || $cur_page == 0) $cur_page = 1;
			$show_pages = $opt['show_pages'] > 0 ? $opt['show_pages'] : $opt['pages_in_group'];
			$show_pages_start = $cur_page - floor($show_pages/2);
			$show_pages_end = $show_pages_start + $show_pages - 1;
			$max_page = ceil($num_posts / $posts_per_page);
			$cur_group = ceil($cur_page / $opt['pages_in_group']);
	
			if ($max_page > 1) {
	
				$output .= $opt['before'] . '<ul' . ($opt['style'] == 'slider' ? ' class="pageLibrary"' : '') . '>';
	
				if ($opt['style'] == 'pages') {
					// Page XX from XXX
					if ($opt['pages_text']) {
						$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($cur_page), $opt['pages_text']);
						$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
						$output .= '<li class="pager_pages '.$opt['button_class'].'"><span>' . $pages_text . '</span></li>';
					}
					if ($cur_page > 1) {
						// First page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['first_text']);
						$output .= '<li class="pager_first '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link()).'">'.$page_text.'</a></li>';
						// Prev page
						$output .= '<li class="pager_prev '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($cur_page-1)).'">'.$opt['prev_text'].'</a></li>';
					}
					// Page buttons
					$group = 1;
					$dot1 = $dot2 = false;
					for ($i = 1; $i <= $max_page; $i++) {
						if ($i % $opt['pages_in_group'] == 1) {
							$group = ceil($i / $opt['pages_in_group']);
							if ($group != $cur_group)
								$output .= '<li class="pager_group '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($i)).'">'.$i.'-'.min($i+$opt['pages_in_group']-1, $max_page).'</a></li>';
						}
						if ($group == $cur_group) {
							if ($i < $show_pages_start) {
								if (!$dot1) {
									$output .= '<li class="pager_dot '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($show_pages_start-1)).'">'.$opt['dot_text'].'</a></li>';
									$dot1 = true;
								}
							} else if ($i > $show_pages_end) {
								if (!$dot2) {
									$output .= '<li class="pager_dot '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($show_pages_end+1)).'">'.$opt['dot_text'].'</a></li>';
									$dot2 = true;
								}
							} else if ($i == $cur_page) {
								$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['current_text']);
								$output .= '<li class="pager_current active '.$opt['button_class'].'"><span>'.$page_text.'</span></li>';
							} else {
								$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['page_text']);
								$output .= '<li class="'.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($i)).'">'.$page_text.'</a></li>';
							}
						}
					}
					if ($cur_page < $max_page) {
						// Next page
						$output .= '<li class="pager_next '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($cur_page+1)).'">'.$opt['next_text'].'</a></li>';
						// Last page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['last_text']);
						$output .= '<li class="pager_last '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($max_page)).'">'.$page_text.'</a></li>';
					}
	
				} else if ($opt['style'] == 'slider') {
	
					if ($cur_page > 1) {
						// First page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['first_text']);
						$page_text = str_replace("&laquo;", '', $page_text);
						$output .= '<li class="pager_first ico left '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link()).'">'.$page_text.'</a></li>';
						// Prev page
						$page_text = str_replace("&laquo;", '', $opt['prev_text']);
						$output .= '<li class="pager_prev ico left '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($cur_page-1)).'">'.$page_text.'</a></li>';
					}
					// Page XX from XXX
					if (empty($opt['pages_text'])) 
						$opt['pages_text'] = __('Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'themerex');
					$pages_text = str_replace("%CURRENT_PAGE%", '<input class="navInput" readonly="readonly" type="text" size="1" value="'.$cur_page.'">', $opt['pages_text']);
					$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
					$output .= '<li class="pager_pages libPage">' . $pages_text;
					// Page buttons
					$output .= '<div id="pageNavSlider" class="boxShadow pageFocusBlock navPadding">'
						. '<div class="sc_slider sc_slider_swiper sc_slider_controls sc_slider_controls_top sc_slider_nopagination sc_slider_noautoplay swiper-slider-container">'
						. '<ul class="slides swiper-wrapper" data-current-slide="'.$cur_group.'">';
					$group = 1;
					$row_opened = false;
					for ($i = 1; $i <= $max_page; $i++) {
						if ($i % $opt['pages_in_group'] == 1) {
							$group = ceil($i / $opt['pages_in_group']);
							$output .= ($i > 1 ? '</tr></table></div></li>' : '') . '<li class="swiper-slide"><div class="pageNumber"><table>';
							$row_opened = false;
						}
						if ($i % $opt['show_pages'] == 1) {
							if ($row_opened)
								$output .= '</tr>';
							$output .= '<tr>';
							$row_opened = true;
						}
						if ($i == $cur_page) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['current_text']);
							$output .= '<td><a href="#" class="active">'.$page_text.'</a></li>';
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $opt['page_text']);
							$output .= '<td><a href="'.esc_url(get_pagenum_link($i)).'">'.$page_text.'</a></td>';
						}
					}
					$output .= '</tr></table></div></li>';
					$output .= '</ul>'
						. '</div>'
						. '<ul class="flex-direction-nav"><li><a class="flex-prev" href="#"></a></li><li><a class="flex-next" href="#"></a></li></ul>'
						. '</div>'
						. '</li>';
					if ($cur_page < $max_page) {
						// Next page
						$page_text = str_replace("&raquo;", '', $opt['next_text']);
						$output .= '<li class="pager_next ico right '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($cur_page+1)).'">'.$page_text.'</a></li>';
						// Last page
						$page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $opt['last_text']);
						$page_text = str_replace("&raquo;", '', $page_text);
						$output .= '<li class="pager_last ico right '.$opt['button_class'].'"><a href="'.esc_url(get_pagenum_link($max_page)).'">'.$page_text.'</a></li>';
					}
	
				}
				$output .= '
					</ul>
				'.$opt['after'];
			}
		}
		echo balanceTags($output);
	}
}



// Get modified calendar layout
if (!function_exists('getThemeRexCalendar')) {
	function getThemeRexCalendar($onlyFirstLetter = true, $get_month = 0, $get_year = 0, $opt=array()) {
		global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

		// Check additional options
		$tmp = explode(',', !empty($opt['post_type']) ? $opt['post_type'] : 'post');
		$post_type = $post_type_full = '';
		$sql_post_type = $sql_post_type_full = '';
		$events_present = false;
		for ($i=0; $i<count($tmp); $i++) {
			$tmp[$i] = trim($tmp[$i]);
			if ($tmp[$i]=='product' && !function_exists('is_woocommerce')) continue;
			if ($tmp[$i]=='tribe_events') {
				if (!class_exists('Tribe__Events__Main')) continue;
				$events_present = true;
			} else {
				$post_type .= (!empty($post_type) ? ',' : '') . $tmp[$i];
				$sql_post_type .= (!empty($sql_post_type) ? "','" : '') . $tmp[$i];
			}
			$post_type_full .= (!empty($post_type_full) ? ',' : '') . $tmp[$i];
			$sql_post_type_full .= (!empty($sql_post_type_full) ? "','" : '') . $tmp[$i];
		}
		if (!empty($sql_post_type)) {
			$sql_post_type = "{$wpdb->posts}.post_type " . (themerex_strpos($sql_post_type, ',')!==false ? 'IN (' : '=') . "'" . $sql_post_type . "'" . (themerex_strpos($sql_post_type, ',')!==false ? ')' : '');
		}
		if (!empty($sql_post_type_full)) {
			$sql_post_type_full = "{$wpdb->posts}.post_type " . (themerex_strpos($sql_post_type_full, ',')!==false ? 'IN (' : '=') . "'" . $sql_post_type_full . "'" . (themerex_strpos($sql_post_type_full, ',')!==false ? ')' : '');
		}
		// Create Events object (if need)
		$sql_from = $sql_from_full = $wpdb->posts;
		if ($events_present) {
			$events = TribeEvents::instance();
			$sql_from = $wpdb->posts . "
				INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)
				LEFT JOIN  {$wpdb->postmeta} as tribe_event_end_date ON ({$wpdb->posts}.ID = tribe_event_end_date.post_id AND tribe_event_end_date.meta_key = '_EventEndDate')
			";
		}

		$prv = current_user_can('read_private_pages') && current_user_can('read_private_posts') ? " OR {$wpdb->posts}.post_status='private'" : '';
		$sql_status = (!empty($prv) ? '(' : '')."{$wpdb->posts}.post_status='publish'".(!empty($prv) ? $prv : '').(!empty($prv) ? ')' : '');

		// Quick check. If we have no posts at all, abort!
		if ( !$posts ) {
			$gotsome = $wpdb->get_var($wpdb->prepare("SELECT 1 as test FROM $sql_from_full WHERE $sql_post_type_full AND $sql_status LIMIT 1"));
			if ( !$gotsome ) {
				$cache[ $key ] = '';
				wp_cache_set( 'get_calendar', $cache, 'calendar' );
				return;
			}
		}

		if ( isset($_GET['w']) )
			$w = ''.intval($_GET['w']);

		// week_begins = 0 stands for Sunday
		$week_begins = intval(get_option('start_of_week'));

		// Let's figure out when we are
		if ( !empty($get_month) && !empty($get_year) ) {
			$thismonth = ''.zeroise(intval($get_month), 2);
			$thisyear = ''.intval($get_year);
		} else if ( !empty($monthnum) && !empty($year) ) {
			$thismonth = ''.zeroise(intval($monthnum), 2);
			$thisyear = ''.intval($year);
		} elseif ( !empty($w) ) {
			// We need to get the month from MySQL
			$thisyear = ''.intval(substr($m, 0, 4));
			$d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
			$thismonth = $wpdb->get_var($wpdb->prepare("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')"));
		} elseif ( !empty($m) ) {
			$thisyear = ''.intval(substr($m, 0, 4));
			if ( strlen($m) < 6 )
				$thismonth = '01';
			else
				$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
		} else {
			$thisyear = gmdate('Y', current_time('timestamp'));
			$thismonth = gmdate('m', current_time('timestamp'));
		}

		$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
		$last_day = date('t', $unixmonth);
		$last_day = esc_sql($last_day);
		$thismonth = esc_sql($thismonth);
		$thisyear = esc_sql($thisyear);

		/// translators: Calendar caption: 1: month name, 2: 4-digit year
		$calendar_caption = _x('%1$s %2$s', 'calendar caption', 'themerex');
		$calendar_output = '
		<table id="wp-calendar-'.str_replace('.', '', mt_rand()).'" class="wp-calendar">
			<thead>
				<tr>';

		// Get the previous month and year with at least one post
		$prev_month = $prev_year = 0;
		if (!empty($post_type)) {
			$previous = $wpdb->get_row("SELECT MONTH({$wpdb->posts}.post_date) AS month, YEAR({$wpdb->posts}.post_date) AS year
				FROM {$wpdb->posts}
				WHERE {$sql_status} AND ($sql_post_type AND {$wpdb->posts}.post_date < '$thisyear-$thismonth-01')
				ORDER BY {$wpdb->posts}.post_date DESC
				LIMIT 1");
			if ($previous) {
				$prev_month = $previous->month;
				$prev_year = $previous->year;
			}
		}
		if ($events_present) {
			$previous = $wpdb->get_row("SELECT MONTH(tribe_event_end_date.meta_value) AS month, YEAR(tribe_event_end_date.meta_value) AS year
				FROM {$sql_from}
				WHERE {$sql_status} AND ({$wpdb->posts}.post_type = 'tribe_events' AND tribe_event_end_date.meta_value < '$thisyear-$thismonth-01')
				ORDER BY tribe_event_end_date.meta_value DESC
				LIMIT 1");
			if ($previous && $previous->year.'-'.$previous->month > $prev_year.'-'.$prev_month) {
				$prev_month = $previous->month;
				$prev_year = $previous->year;
			}
		}
		$calendar_output .= '
					<th class="prevMonth">';
		if ( $prev_year+$prev_month > 0 ) {
			$calendar_output .= '<div class="left roundButton"><a href="#" data-type="'.esc_attr($post_type_full).'" data-year="' . $prev_year . '" data-month="' . $prev_month . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s', 'themerex'), $wp_locale->get_month($prev_month), date('Y', mktime(0, 0, 0, $prev_month, 1, $prev_year)))) . '">'
				//. '&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($prev_month))
				. '</a></div>';
		} else {
			$calendar_output .= '&nbsp;';
		}

		// Get the current month and year
		$calendar_output .= '
					</th>
					<th class="curMonth" colspan="5">
						<a href="' . (empty($post_type) && $events_present ? $events->getLink('month', $thisyear.'-'.$thismonth, null) : get_month_link($thisyear, $thismonth)) . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s', 'themerex'), $wp_locale->get_month($thismonth), date('Y', mktime(0, 0, 0, $thismonth, 1, $thisyear)))) . '">' . sprintf($calendar_caption, $wp_locale->get_month($thismonth), '<span>'.date('Y', $unixmonth).'</span>') . '</a>
					</th>
					<th class="nextMonth">';

		// Get the next month and year with at least one post
		$next_month = $next_year = 0;
		$sql_date  = $events_present && empty($post_type) ? "{$wpdb->postmeta}.meta_value" : "{$wpdb->posts}.post_date";
		if (!empty($post_type)) {
			$next = $wpdb->get_row("SELECT MONTH({$wpdb->posts}.post_date) AS month, YEAR({$wpdb->posts}.post_date) AS year
				FROM {$wpdb->posts}
				WHERE {$sql_status} AND ($sql_post_type AND {$wpdb->posts}.post_date > '$thisyear-$thismonth-{$last_day} 23:59:59')
				ORDER BY {$wpdb->posts}.post_date ASC
				LIMIT 1");
			if ($next) {
				$next_month = $next->month;
				$next_year = $next->year;
			}
		}
		if ($events_present) {
			$next = $wpdb->get_row("SELECT MONTH({$wpdb->postmeta}.meta_value) AS month, YEAR({$wpdb->postmeta}.meta_value) AS year
				FROM {$sql_from}
				WHERE {$sql_status} AND ({$wpdb->posts}.post_type = 'tribe_events' AND {$wpdb->postmeta}.meta_key = '_EventStartDate' AND {$wpdb->postmeta}.meta_value > '$thisyear-$thismonth-{$last_day} 23:59:59')
				ORDER BY {$sql_date} ASC
				LIMIT 1");
			if ($next && $next->year.'-'.$next->month > $next_year.'-'.$next_month) {
				$next_month = $next->month;
				$next_year = $next->year;
			}
		}
		if ( $next_year+$next_month > 0 ) {
			$calendar_output .= '<div class="right roundButton"><a href="#" data-type="'.esc_attr($post_type_full).'" data-year="' . $next_year . '" data-month="' . $next_month . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s', 'themerex'), $wp_locale->get_month($next_month), date('Y', mktime(0, 0 , 0, $next_month, 1, $next_year))) ) . '">'
				//. $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;'
				. '</a></div>';
		} else {
			$calendar_output .= '&nbsp;';
		}
		$calendar_output .= '
					</th>
				</tr>
				<tr>';

		// Show Week days
		$myweek = array();

		for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
			$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
		}

		foreach ( $myweek as $wd ) {
			$day_name = $onlyFirstLetter ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
			$wd = esc_attr($wd);
			$calendar_output .= "
					<th scope=\"col\" title=\"$wd\">$day_name</th>";
		}

		$calendar_output .= '
				</tr>
			</thead>

			<tbody>
				<tr>';

		// Get days with posts
		$dayswithposts = $wpdb->get_results("SELECT DISTINCT DAYOFMONTH({$sql_date})
			FROM {$sql_from}
			WHERE {$sql_status} AND
				("
			. (!empty($post_type)
				? "($sql_post_type AND {$wpdb->posts}.post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' AND {$wpdb->posts}.post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')"
				: '')
			. ($events_present
				? (!empty($post_type) ? ' OR ' : '') . "
							{$wpdb->posts}.post_type = 'tribe_events' AND {$wpdb->postmeta}.meta_key = '_EventStartDate'
							AND (
								({$wpdb->postmeta}.meta_value >= '{$thisyear}-{$thismonth}-01 00:00:00' AND {$wpdb->postmeta}.meta_value <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')
								OR (tribe_event_end_date.meta_value >= '{$thisyear}-{$thismonth}-01 00:00:00' AND tribe_event_end_date.meta_value <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')
								OR ({$wpdb->postmeta}.meta_value < '{$thisyear}-{$thismonth}-01' AND tribe_event_end_date.meta_value >= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')
								)
							"
				: '')
			. ")"
			, ARRAY_N);
		if ( $dayswithposts ) {
			foreach ( (array) $dayswithposts as $daywith ) {
				$daywithpost[] = $daywith[0];
			}
		} else {
			$daywithpost = array();
		}

		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'camino') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false)
			$ak_title_separator = "\n";
		else
			$ak_title_separator = ', ';

		$ak_titles_for_day = array();
		$ak_post_titles = $wpdb->get_results("SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title, DAYOFMONTH({$sql_date}) as dom
			FROM {$sql_from}
			WHERE {$sql_status} AND
				("
			. (!empty($post_type)
				? "($sql_post_type AND {$wpdb->posts}.post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' AND {$wpdb->posts}.post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')"
				: '')
			. ($events_present
				? (!empty($post_type) ? ' OR ' : '') . "
							{$wpdb->posts}.post_type = 'tribe_events' AND {$wpdb->postmeta}.meta_key = '_EventStartDate'
							AND (
								({$wpdb->postmeta}.meta_value >= '{$thisyear}-{$thismonth}-01 00:00:00' AND {$wpdb->postmeta}.meta_value <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')
								OR (tribe_event_end_date.meta_value >= '{$thisyear}-{$thismonth}-01 00:00:00' AND tribe_event_end_date.meta_value <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')
								OR ({$wpdb->postmeta}.meta_value < '{$thisyear}-{$thismonth}-01' AND tribe_event_end_date.meta_value >= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59')
								)
							"
				: '')
			. ")"
		);
		if ( $ak_post_titles ) {
			foreach ( (array) $ak_post_titles as $ak_post_title ) {

				/** This filter is documented in wp-includes/post-template.php */
				$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) );

				if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
					$ak_titles_for_day['day_'.$ak_post_title->dom] = '';
				if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
					$ak_titles_for_day["$ak_post_title->dom"] = $post_title;
				else
					$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
			}
		}

		// See how much we should pad in the beginning
		$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
		if ( 0 != $pad )
			$calendar_output .= '
					<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

		$daysinmonth = intval(date('t', $unixmonth));
		for ( $day = 1; $day <= $daysinmonth; ++$day ) {
			if ( isset($newrow) && $newrow )
				$calendar_output .= "
				</tr>
				<tr>";

			$newrow = false;

			if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
				$calendar_output .= '
					<td class="today">';
			else
				$calendar_output .= '
					<td>';

			if ( in_array($day, $daywithpost) ) // any posts today?
				$calendar_output .= '<a href="' . (empty($post_type) && $events_present ? $events->getLink('day', $thisyear.'-'.$thismonth.'-'.$day, null) : get_day_link( $thisyear, $thismonth, $day )) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
			else
				$calendar_output .= $day;
			$calendar_output .= '</td>';

			if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
				$newrow = true;
		}

		$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
		if ( $pad != 0 && $pad != 7 )
			$calendar_output .= '
					<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

		$calendar_output .= "
				</tr>
			</tbody>
		</table>
		";

		return $calendar_output;
	}
}



/* ========================= Post utilities section ============================== */

// Return image dimensions
if (!function_exists('getThumbSizes')) {
	function getThumbSizes($opt) {
		$opt = themerex_array_merge(array(
			'thumb_size' => 'excerpt',
			'thumb_crop' => true,
			'sidebar' => true
		), $opt);
		$thumb_sizes = array(
			// 16:9
			'fullpost'          => array('w' => 1150,'h' => $opt['thumb_crop'] ? 647 : null, 'h_crop' => 647),	//1150 x 647
			'excerpt'           => array('w' => 714, 'h' => $opt['thumb_crop'] ? 402 : null, 'h_crop' => 402),	// 550 x 309
			'related'           => array('w' => 400, 'h' => $opt['thumb_crop'] ? 225 : null, 'h_crop' => 225),	// 400 x 225
			'single-standard'   => array('w' => 1150,'h' => $opt['thumb_crop'] ? 647 : null, 'h_crop' => 647),	//1150 x 647
			'single-portfolio'  => array('w' => 1150,'h' => $opt['thumb_crop'] ? 647 : null, 'h_crop' => 647),	//1150 x 647
			'single-portfolio-fullscreen'
								=> array('w' => null,'h' => null,                            'h_crop' => null),	//Fullscreen mode - original image
			'image_tiny'        => array('w' => 250, 'h' => $opt['thumb_crop'] ? 141 : null, 'h_crop' => 141),	// 120 x  68
			'image_small'       => array('w' => 250, 'h' => $opt['thumb_crop'] ? 141 : null, 'h_crop' => 141),	// 160 x  90
			'image_medium'      => array('w' => 250, 'h' => $opt['thumb_crop'] ? 141 : null, 'h_crop' => 141),	// 200 x 113
			'image_large'       => array('w' => 400, 'h' => $opt['thumb_crop'] ? 225 : null, 'h_crop' => 225),	// 400 x 225
			'masonry2'          => array('w' => 714, 'h' => $opt['thumb_crop'] ? 402 : null, 'h_crop' => 402),	// 550 x 309
			'masonry3'          => array('w' => 400, 'h' => $opt['thumb_crop'] ? 225 : null, 'h_crop' => 225),	// 350 x 197
			'masonry4'          => array('w' => 250, 'h' => $opt['thumb_crop'] ? 141 : null, 'h_crop' => 141),	// 250 x 141
			'classic1'          => array('w' => 1150,'h' => $opt['thumb_crop'] ? 647 : null, 'h_crop' => 647),	//1150 x 647
			'classic2'          => array('w' => 714, 'h' => $opt['thumb_crop'] ? 402 : null, 'h_crop' => 402),	// 550 x 309
			'classic3'          => array('w' => 400, 'h' => $opt['thumb_crop'] ? 225 : null, 'h_crop' => 225),	// 350 x 197
			'classic4'          => array('w' => 250, 'h' => $opt['thumb_crop'] ? 141 : null, 'h_crop' => 141),	// 250 x 141
			'portfolio1'        => array('w' => 714, 'h' => $opt['thumb_crop'] ? 402 : null, 'h_crop' => 402),	// 714 x 402
			'portfolio2'        => array('w' => 714, 'h' => $opt['thumb_crop'] ? 402 : null, 'h_crop' => 402),	// 575 x 323
			// Non 16:9
			'portfolio3'   => array('w' => 383, 'h' => $opt['thumb_crop'] ? 245 : null, 'h_crop' => 245),	// 383 x 245
			'portfolio4'   => array('w' => 287, 'h' => $opt['thumb_crop'] ? 287 : null, 'h_crop' => 287),	// 287 x 287
			'widgets'      => array('w' =>  75, 'h' => $opt['thumb_crop'] ?  75 : null, 'h_crop' =>  75)	//  75 x  75
		);
		return isset($thumb_sizes[$opt['thumb_size']]) ? $thumb_sizes[$opt['thumb_size']] : $thumb_sizes['excerpt'];
	}
}

// Return merged parameters for showPostLayout and getPostData
if (!function_exists('getPostDataOptions')) {
	function getPostDataOptions($opt) {
		$opt = themerex_array_merge(array(
			'layout' => '',				// Layout name - used to include layout file '/templates/post-layout-xxx.php'
			'style' => '',				// Output style. If omitted - used 'thumb_size' name
			'show' => true,				// Show layout into browser or return it as string
			'number' => 1,				// Post's number to detect even/odd and first/last classes
			'reviews' => true,			// Include Reviews marks into output array
			'counters' => get_theme_option("blog_counters"),	// What counters use: views or comments
			'add_view_more' => false,	// Add "View more" link at end of the description
			'posts_on_page' => 1,		// How many posts queried
			'posts_visible' => '',		// How many posts output in one row
			'date_format' => '',		// PHP rule for date output. Can be split on two parts with sign '+'. For example: 'd M+Y' 
										// In the output array post_date="31 May 2014", post_date_part1="31 May", post_date_part2="2014"
			// Parameters for getPostData
			'thumb_size' => '',			// Name of the predefined thumb size. If omitted - used 'layout' name
			'thumb_crop' => true,		// Do you need to crop image or only scale it
			'sidebar' => in_array(get_custom_option('show_sidebar_main'), array('left', 'right')),	// Sidebar is visible on current page
			'more_tag' => null,			// Text for the "Read more" link
			'strip_teaser' => get_custom_option('show_text_before_readmore')!='yes',	// Strip text before <!--more--> tag in the content or show full content
			'substitute_gallery' => get_custom_option('substitute_gallery')=='yes',		// Substitute standard WP gallery on theme slider
			'substitute_video' => get_custom_option('substitute_video')=='yes',			// Substitute tag <video> on the Youtube or Vimeo player
			'substitute_audio' => get_custom_option('substitute_audio')=='yes',			// Substitute tag <audio> on the Sound Cloud's player
			'parent_cat_id' => 0,		// Now showed category ID: if in the theme options 'close_category'='parental' - we detect closest category to this ID for each post
			'dedicated' => '',			// Dedicated content from the post (created with shortcode [trx_section dedicated="yes"]...[/trx_section]
			'location' => '',			// Location of the dedicated content or featured image: left|right|top
			'post_class' => '',			// Class for location above (used in the <div> or <article> with this post
			'content' => false,			// Do you need prepare full content for this port (do shortcodes, apply filters etc.) Usually need only for the single page
			'categories_list' => true,	// Detect the full list of the post's categories
			'tags_list' => true			// Detect the full list of the post's tags
		), $opt);
		if (empty($opt['thumb_size']))		$opt['thumb_size'] = !empty($opt['layout']) ? $opt['layout'] : 'excerpt';
		if (empty($opt['style'])) 			$opt['style'] = !empty($opt['thumb_size']) ? $opt['thumb_size'] : $opt['layout'];
		if (empty($opt['posts_visible']))	$opt['posts_visible'] = $opt['posts_on_page'];
		return $opt;
	}
}

// Return post HTML-layout
if (!function_exists('showPostLayout')) {
	function showPostLayout($opt = array(), $post_data=null, $post_obj=null) {
		$opt = getPostDataOptions($opt);
		if ($post_data === null)
			$post_data = getPostData($opt, $post_obj);
		// Collect standard output
		$layout = '';
		if (!$opt['show']) ob_start();
		require(themerex_get_file_dir('/templates/post-layout-'.$opt['layout'].'.php'));
		if (!$opt['show'])  {
			$layout = ob_get_contents();
			ob_end_clean();
		}
		clear_dedicated_content();
		return $layout;
	}
}

// Return all post data as array
if (!function_exists('getPostData')) {
	function getPostData(&$opt, $post_obj=null) {
		$opt = getPostDataOptions($opt);
		if (empty($opt['layout'])) {
			$opt['layout'] = !empty($opt['thumb_size']) ? $opt['thumb_size'] : 'excerpt';
		}
		global $post, $wp_query;
		$old_post = null;
		if (!empty($post) && is_object($post)) $old_post = clone $post;
		if ($post_obj != null) { $post = $post_obj; setup_postdata($post); }
		$cur_post = clone $post;
		$post_id = get_the_ID();
		$post_protected = post_password_required();
		$post_format = get_post_format();
		if (empty($post_format)) $post_format = 'standard';
		$post_icon = getPostFormatIcon($post_format);
		$post_type = get_post_type();
		$post_flags = array(
			'sticky' => is_sticky()
		);
		$post_link = get_permalink();
		$post_comments_link = get_comments_link();
		$post_date_sql = get_the_date('Y-m-d H:i:s');
		$post_date_stamp = get_the_date('U');
		$post_date = getDateOrDifference($post_date_sql);
		if (!empty($opt['date_format'])) {
			$parts = explode('+', $opt['date_format']);
			$post_date_part1 = empty($parts[0]) ? '' : date($parts[0], $post_date_stamp);
			$post_date_part2 = empty($parts[1]) ? '' : date($parts[1], $post_date_stamp);
			if ($post_date_part1.$post_date_part2!='') {
				$post_date = $post_date_part1 . ($post_date_part2!='' ? ' '.$post_date_part2 : '');
			}
		}
	
		$post_comments = $post_views = $post_likes = 0;
		if ($opt['counters']!='none') {
			$post_comments = get_comments_number();
			$post_views = getPostViews($post_id);
			$post_likes = getPostLikes($post_id);
		}
		$post_reviews_author = $post_reviews_users = 0;
		if ($opt['reviews']) {
			$post_reviews_author = marksToDisplay(get_post_meta($post_id, 'reviews_avg', true));
			$post_reviews_users  = marksToDisplay(get_post_meta($post_id, 'reviews_avg2', true));
		}
	
		$post_author = get_the_author();
		$post_author_id = get_the_author_meta('ID');
		$post_author_url = get_author_posts_url($post_author_id, '');
	
		// Is user can edit and/or delete this post?
		$allow_editor = get_theme_option("allow_editor")=='yes';
		$post_edit_enable = $allow_editor && (
						($post_type=='post' && current_user_can('edit_posts', $post_id)) || 
						($post_type=='page' && current_user_can('edit_pages', $post_id))
						);
		$post_delete_enable = $allow_editor && (
						($post_type=='post' && current_user_can('delete_posts', $post_id)) || 
						($post_type=='page' && current_user_can('delete_pages', $post_id))
						);
	
		// Post content
		global $more;
		$old_more = $more;
		$more = -1;
		$post_content_original = trim(chop($post->post_content));
		$post_content_plain = trim(chop(get_the_content()));
		$more = $old_more;
		$post_content = trim(chop(get_the_content($opt['more_tag'], $opt['strip_teaser'])));
		// Substitute WP [gallery] shortcode
		$thumb_sizes = getThumbSizes(array(
			'thumb_size' => $opt['thumb_size'],
			'thumb_crop' => $opt['thumb_crop'],
			'sidebar' => $opt['sidebar']
		));
		if ($opt['content']) {
			if ($opt['substitute_gallery'])	$post_content = substituteGallery($post_content, $post_id, $thumb_sizes['w'], $thumb_sizes['h_crop'], 'none', true);
			$post_content = apply_filters('the_content', $post_content);
			if ($post_id != get_the_ID()) {		// Fix bug in the WPML
				$post = $cur_post;
				setup_postdata($post);
			}
			if ($opt['substitute_video']) 	$post_content = substituteVideo($post_content, $thumb_sizes['w'], $thumb_sizes['h_crop']);
			if ($opt['substitute_audio'])	$post_content = substituteAudio($post_content);
		}
	
		// Post excerpt
		$post_excerpt_original = $post->post_excerpt;
		$post_excerpt = has_excerpt() || $post_protected ? get_the_excerpt() : '';
		if (empty($post_excerpt)) {
			if (($more_pos = themerex_strpos($post_content_plain, '<span id="more-'))!==false) {
				$post_excerpt = themerex_substr($post_content_plain, 0, $more_pos);
			} else {
				$post_excerpt = in_array($post_format, array('quote', 'link')) ? $post_content : strip_shortcodes(strip_tags(get_the_excerpt()));
			}
		}
		if ($opt['substitute_gallery']) $post_excerpt = substituteGallery($post_excerpt, $post_id, $thumb_sizes['w'], $thumb_sizes['h_crop']);
		$post_excerpt = apply_filters('themerex_sc_clear_around', $post_excerpt);
		$post_excerpt = apply_filters('the_excerpt', $post_excerpt);
		$post_excerpt = apply_filters('themerex_p_clear_around', $post_excerpt);
		if ($post_id != get_the_ID()) {		// Fix bug in the WPML
			$post = $cur_post;
			setup_postdata($post);
		}
		if ($opt['substitute_video']) $post_excerpt = substituteVideo($post_excerpt, $thumb_sizes['w'], $thumb_sizes['h_crop']);
		if ($opt['substitute_audio']) $post_excerpt = substituteAudio($post_excerpt);
		$post_excerpt = trim(chop(str_replace(array('[...]', '[&hellip;]'), array('', ''), $post_excerpt)));

		// Post Title
		$post_title = $post_title_plain = trim(chop(get_the_title()));
		$post_title = apply_filters('the_title',   $post_title);
		if ($post_id != get_the_ID()) {		// Fix bug in the WPML
			$post = $cur_post;
			setup_postdata($post);
		}
	
		// Prepare dedicated content
		$opt['dedicated'] = get_dedicated_content();
		$opt['location']  = !empty($opt['location']) ? $opt['location'] : get_custom_option('dedicated_location');
		if (empty($opt['location']) || $opt['location'] == 'default') $opt['location'] = get_custom_option('dedicated_location', '', $post_id);
		if ($opt['location']=='alter' && !is_single() && (!is_page() || isset($wp_query->is_posts_page) && $wp_query->is_posts_page==1)) {
			$loc = array('center', 'right', 'left');
			$opt['location'] = $loc[($opt['number']-1)%count($loc)];
		}
		if (!empty($opt['dedicated'])) {
			$class = getTagAttrib($opt['dedicated'], '<div class="sc_section>', 'class');
			if ($opt['location']=='default') {
				if (($pos = themerex_strpos($class, 'sc_align'))!==false) {
					$pos += 8;
					$pos2 = themerex_strpos($class, ' ', $pos);
					$opt['location'] = $pos2===false ? themerex_substr($class, $pos) : themerex_substr($class, $pos, $pos2-$pos);
				}
				if ($opt['location']=='' || $opt['location']=='default') $opt['location'] = 'center';
			}
			if (!is_singular() || in_shortcode_blogger(true) || (themerex_strpos($class, 'sc_align')!==false && themerex_strpos($class, 'columns')===false)) {
				$class = str_replace(array('sc_alignright', 'sc_alignleft', 'sc_aligncenter'), array('','',''), $class) . ' sc_align' . $opt['location'];
				if ($opt['location'] == 'center' && themerex_strpos($class, 'columns2_3')===false && $opt['sidebar'])
					$class = str_replace('columns', '_columns', $class) . ' columns2_3';
				else if (($opt['location'] == 'left' || $opt['location'] == 'right') && themerex_strpos($class, 'columns1_2')===false)// && $opt['sidebar'])
					$class = str_replace('columns', '_columns', $class) . ' columns1_2';
				$opt['dedicated'] = setTagAttrib($opt['dedicated'], '<div class="sc_section>', 'class', $class);
			}
		} //else if ($opt['location']=='' || $opt['location']=='default')
			//$opt['location'] = 'center';
		//if ($opt['location']=='default') $opt['location']='center';
		$opt['post_class'] = themerex_strtoproper($opt['location']);
	
		// Substitute <video> tags to <iframe> in dedicated content
		if ($opt['substitute_video']) {
			$opt['dedicated'] = substituteVideo($opt['dedicated'], $thumb_sizes['w'], $thumb_sizes['h_crop']);
		}
		// Substitute <audio> tags with src from soundcloud to <iframe>
		if ($opt['substitute_audio']) {
			$opt['dedicated'] = substituteAudio($opt['dedicated']);
		}

		// Extract gallery, video and audio from full post content
		$post_thumb = $post_attachment = $post_gallery = $post_video = $post_audio = $post_url = $post_url_target = '';
		if (themerex_substr($opt['layout'], 0, 6)=='single')
			$post_thumb = getResizedImageTag($post_id, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
		else
			$post_thumb = getResizedImageTag($post_id, $thumb_sizes['w'], $post_type=='product' && get_theme_option('crop_product_thumb')=='no' ? null :  $thumb_sizes['h']);
		$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
		if ($post_format == 'gallery') {
			$post_gallery = buildGalleryTag(getPostGallery($post_content_plain, $post_id, max(2, get_custom_option('gallery_max_slides'))), $thumb_sizes['w'], $thumb_sizes['h_crop'], false, get_custom_option('substitute_slider_engine')!='flex' ? '' : $post_link);
		} else if ($post_format == 'video') {
			$post_video = getPostVideo($post_content_original, false);
			if ($post_video=='') {
				$src = getVideoPlayerURL(getPostVideo($post_content_original, true), $post_thumb!='');
				if ($src) $post_video = substituteVideo('<video src="'.$src.'">', $thumb_sizes['w'], round($thumb_sizes['w']/16*9), false);	//$thumb_sizes['h_crop']);							
			}
			if ($post_video!='' && $opt['substitute_video']) {
				$src = getVideoPlayerURL(getPostVideo($post_video), $post_thumb!='');
				if ($src) $post_video = substituteVideo('<video src="'.$src.'">', $thumb_sizes['w'], round($thumb_sizes['w']/16*9), false);	//$thumb_sizes['h_crop']);
			}
		} else if ($post_format == 'audio') {
			$post_audio = getPostAudio($post_content_original, false);
			if ($post_audio=='') {
				$src = getPostAudio($post_content_original, true);
				if ($src) $post_audio = substituteAudio('<audio src="'.$src.'"></audio>');
			}
			if ($post_audio!='' && $opt['substitute_audio']=='yes') {
				$src = getPostAudio($post_audio);
				if ($src) $post_audio = substituteAudio('<audio src="'.$src.'"></audio>');
			}
		}
		if ($post_format == 'image' && !$post_thumb) {
			if (($src = getPostImage($post_content_original))!='')
				$post_thumb = getResizedImageTag($src, $thumb_sizes['w'], $thumb_sizes['h_crop']);
		}
		if ($post_format == 'link') {
			$post_url_data = getPostLink($post_content_original, false);
			$post_link = $post_url = $post_url_data['url'];
			$post_url_target = $post_url_data['target'];
		}
	
		// Get all post's categories
		$post_categories_list = array();
		$post_categories_ids = array();
		$post_categories_slugs = array();
		$post_categories_links = '';
		$post_root_category = '';
		if ($opt['categories_list']) {
			$post_categories_list = getCategoriesByPostId($post_id);
			$ex_cats = explode(',', get_theme_option('exclude_cats'));
			for ($i = 0; $i < count($post_categories_list); $i++) {
				if (in_array($post_categories_list[$i]['term_id'], $ex_cats)) continue;
				if ($post_root_category=='') {
					if (get_theme_option('close_category')=='parental') {
						$parent_cat = getParentCategory($post_categories_list[$i]['term_id'], $opt['parent_cat_id']);
						if ($parent_cat) {
							$post_root_category = $parent_cat['name'];
						}
					} else {
						$post_root_category = $post_categories_list[$i]['name'];
					}
				}
				$post_categories_ids[] = $post_categories_list[$i]['term_id'];
				$post_categories_slugs[] = $post_categories_list[$i]['slug'];
				$post_categories_links .= '<a class="cat_link" href="' . $post_categories_list[$i]['link'] . '">'
					. $post_categories_list[$i]['name'] 
					. ($i < count($post_categories_list)-1 ? ',' : '')
					. '</a> ';
			}
			if ($post_root_category=='' && count($post_categories_list)>0) {
				$post_root_category = $post_categories_list[0]['name'];
			}
		}
	
		// Get all post's tags
		$post_tags_list = array();
		$post_tags_ids = array();
		$post_tags_slugs = array();
		$post_tags_links = '';
		if ($opt['tags_list']) {
			if (($post_tags_list = get_the_tags()) != 0) {
				$tag_number=0;
				foreach ($post_tags_list as $tag) {
					$tag_number++;
					$post_tags_links .= '<a class="tag_link" href="' . get_tag_link($tag->term_id) . '">' . $tag->name . ($tag_number==count($post_tags_list) ? '' : ',') . '</a> ';
					$post_tags_ids[] = $tag->term_id;
					$post_tags_slugs[] = $tag->slug;
				}
			} else if (!is_array($post_tags_list))
				$post_tags_list = array();
		}
		
		if ($old_post != null) { $post = $old_post; setup_postdata($post); }
		$post_data = compact('post_id', 'post_protected', 'post_type', 'post_format', 'post_flags', 'post_icon', 'post_link', 'post_comments_link', 'post_date_sql', 'post_date_stamp', 'post_date', 'post_date_part1', 'post_date_part2', 'post_comments', 'post_views', 'post_likes', 'post_reviews_author', 'post_reviews_users', 'post_author', 'post_author_id', 'post_author_url', 'post_title', 'post_title_plain', 'post_content_plain', 'post_content_original', 'post_content', 'post_excerpt_original', 'post_excerpt', 'post_thumb', 'post_attachment', 'post_gallery', 'post_video', 'post_audio', 'post_url', 'post_url_target', 'post_categories_list', 'post_categories_slugs', 'post_categories_ids', 'post_categories_links', 'post_root_category', 'post_tags_list', 'post_tags_ids', 'post_tags_slugs', 'post_tags_links', 'post_edit_enable', 'post_delete_enable');
	
		return apply_filters('themerex_get_post_data', $post_data, $opt, $post_obj);
	}
}


// Return custom_page_heading (if set), else - post title
if (!function_exists('getPostTitle')) {
	function getPostTitle($id = 0, $maxlength = 0, $add='...') {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$title = get_the_title($id);
		if ($maxlength > 0) $title = getShortString($title, $maxlength, $add);
		return $title;
	}
}

// Return custom_page_description (if set), else - post excerpt (if set), else - trimmed content
if (!function_exists('getPostDescription')) {
	function getPostDescription($maxlength = 0, $add='...') {
		$descr = get_the_excerpt();
		$descr = trim(str_replace(array('[...]', '[&hellip;]'), array($add, $add), $descr));
		if (!empty($descr) && themerex_strpos(',.:;-', themerex_substr($descr, -1))!==false) $descr = themerex_substr($descr, 0, -1);
		if ($maxlength > 0) $descr = getShortString($descr, $maxlength, $add);
		return $descr;
	}
}

//Return Post Views Count
if (!function_exists('getPostViews')) {
	function getPostViews($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count == ''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Views Count
if (!function_exists('setPostViews')) {
	function setPostViews($id=0, $counter=-1) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'post_views_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count == ''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 1;
		}
		$count = $counter >= 0 ? $counter : $count;
		update_post_meta($id, $count_key, $count);
	}
}

//Return Post Likes Count
if (!function_exists('getPostLikes')) {
	function getPostLikes($id=0){
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'post_likes_count';
		$count = get_post_meta($id, $count_key, true);
		if ($count == ''){
			delete_post_meta($id, $count_key);
			add_post_meta($id, $count_key, '0');
			$count = 0;
		}
		return $count;
	}
}

//Set Post Likes Count
if (!function_exists('setPostLikes')) {
	function setPostLikes($id=0, $count=0) {
		global $wp_query;
		if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
		$count_key = 'post_likes_count';
		update_post_meta($id, $count_key, $count);
	}
}

// Return posts by meta_value
if (!function_exists('getPostsByMetaValue')) {
	function getPostsByMetaValue($meta_key, $meta_value, $return_format=OBJECT) {
		global $wpdb;
		$where = array();
		if ($meta_key) $where[] = 'meta.meta_key="' . esc_sql($meta_key) . '"';
		if ($meta_value) $where[] = 'meta.meta_value="' . esc_sql($meta_value) . '"';
		$where[] = 'posts.post_status="publish"';
		$whereStr = count($where) ? 'WHERE '.join(' AND ', $where) : '';
		$posts = $wpdb->get_results("SELECT meta.post_id, posts.* FROM {$wpdb->postmeta} AS meta INNER JOIN {$wpdb->posts} AS posts ON meta.post_id=posts.ID {$whereStr}", $return_format);
		return $posts;
	}
}

// Return url from gallery, inserted in post
if (!function_exists('getPostGallery')) {
	function getPostGallery($text, $id=0, $max_slides=-1) {
		$tag = '[gallery]';
		$rez = array();
		$ids = array();
		if ($text) {
			$ids_list = getTagAttrib($text, $tag, 'ids');
			if ($ids_list!='') {
				$ids = explode(',', $ids_list);
				$orderby = getTagAttrib($text, $tag, 'orderby');
				if ($orderby=='rand' || $orderby=='random') {
					shuffle($ids);
				}
			}
		}
		if (count($ids)==0 && $id > 0) {
			$args = array(
					'numberposts' => -1,
					'order' => 'ASC',
					'orderby' => 'rand',
					'post_mime_type' => 'image',
					'post_parent' => $id,
					'post_status' => 'any',
					'post_type' => 'attachment',
				);
			$attachments = get_children( $args );
			if ( $attachments ) {
				foreach ( $attachments as $attachment )
					$ids[] = $attachment->ID;
			}
		}
		if (count($ids) > 0) {
			$cnt = 0;
			foreach ($ids as $v) {
				if ($max_slides > 0 && $cnt++ >= $max_slides) break;
				$src = wp_get_attachment_image_src( $v, 'full' );
				if (isset($src[0]) && $src[0]!='')
					$rez[] = $src[0];
			}
		}
		return $rez;
	}
}

// Return gallery tag from photos array
if (!function_exists('buildGalleryTag')) {
	function buildGalleryTag($photos, $w, $h, $zoom=false, $link='') {
		$engine = get_custom_option('substitute_slider_engine');
		$gallery_text = '';
		$gallery_items_in_bg = $engine!='chop';

		// magnific & pretty
		themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
		themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
		// Load PrettyPhoto if it selected in Theme Options
		if (get_theme_option('popup_engine')=='pretty') {
			themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
			themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		}

		themerex_enqueue_style(  'swiperslider-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.css'), array(), null );
		themerex_enqueue_style(  'swiperslider-scrollbar-style',  themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar.css'), array(), null );

		themerex_enqueue_script( 'swiperslider', themerex_get_file_url('/js/swiper/idangerous.swiper-2.7.js'), array('jquery'), null, true );
		themerex_enqueue_script( 'swiperslider-scrollbar', themerex_get_file_url('/js/swiper/idangerous.swiper.scrollbar-2.4.js'), array('jquery'), null, true );
		themerex_enqueue_script( 'flexslider', themerex_get_file_url('/js/jquery.flexslider.min.js'), array('jquery'), null, true );

		if (count($photos) > 0) {
			if ($engine == 'chop') {
				$effects2D = array("vertical", "horizontal", "half", "multi");
				$effects3D  = array("3DBlocks", "3DFlips");
				$chop_effect = $effects2D[min(3, mt_rand(0,3))].'|'.$effects3D[min(1, mt_rand(0,1))];
			}
			$id = "sc_slider_".str_replace('.', '', mt_rand());
			$interval = mt_rand(5000, 10000);
			$gallery_text = '
				<div id="'.$id.'" class="sc_slider sc_slider_'.$engine
					.($engine=='swiper' ? ' swiper-slider-container' : '')
					.' sc_slider_controls"'
					.(!empty($w) && themerex_strpos($w, '%')===false ? ' data-old-width="' . $w . '"' : '')
					.(!empty($h) && themerex_strpos($h, '%')===false ? ' data-old-height="' . $h . '"' : '')
					.($engine=='chop' ? ' data-effect="'.$chop_effect.'"' : '')
					.' data-interval="'.$interval.'"'
					.'>
					<ul class="slides'
						.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
						.($engine=='swiper' ? ' style="height:'.$h.'px;"' : '')
						.'>
					';
			$numSlide = 0;
			foreach ($photos as $photo) {
				$numSlide++;
				if ($gallery_items_in_bg) {
					$photo_min = getResizedImageURL($photo, $w, $h);
					$gallery_text .= '<li' 
						. ' class="'.$engine.'-slide"'
						. ' style="background-image:url(' . $photo_min . ');'
						. (!empty($w) ? 'width:' . $w . (themerex_strpos($w, '%')!==false ? '' : 'px').';' : '')
						. (!empty($h) ? 'height:' . $h . (themerex_strpos($h, '%')!==false ? '' : 'px').';' : '')
						. '">' 
						. ($zoom ? '<a href="'.$photo.'"></a>' : ($link ? '<a href="'.$link.'"></a>' : '')) 
						. '</li>';
				} else {
					$photo_min = getResizedImageTag($photo, $w, $h);
					$gallery_text .= '<li'
						. ' class="'.$engine.'-slide' . ($engine=='chop' && $numSlide==1 ? ' cs-activeSlide': '') . '"'
						. ' style="'.($engine=='chop' && $numSlide==1 ? 'display:block;' : '')
						. (!empty($w) ? 'width:' . $w . (themerex_strpos($w, '%')!==false ? '' : 'px').';' : '')
						. (!empty($h) ? 'height:' . $h . (themerex_strpos($h, '%')!==false ? '' : 'px').';' : '')
						. '">'
						. ($zoom ? '<a href="'. $photo . '">'.$photo_min.'</a>' 
								 : (!empty($link) ? '<a href="'. $link . '">'.$photo_min.'</a>' : $photo_min))
						. '</li>';
				}
			}
			$gallery_text .= '</ul>';
			if ($engine=='swiper' || $engine=='chop') {
				$gallery_text .= '
					<ul class="flex-direction-nav">
					<li><a class="flex-prev" href="#"></a></li>
					<li><a class="flex-next" href="#"></a></li>
					</ul>
				';
			}
			$gallery_text .= '</div>';
		}
		return $gallery_text;
	}
}

// Substitute standard Wordpress galleries
if (!function_exists('substituteGallery')) {
	function substituteGallery($post_text, $post_id, $w, $h, $a='none', $zoom=false) {
		$tag = '[gallery]';
		$post_photos = false;
		while (($pos_start = themerex_strpos($post_text, themerex_substr($tag, 0, -1)))!==false) {
			$pos_end = themerex_strpos($post_text, themerex_substr($tag, -1), $pos_start);
			$tag_text = themerex_substr($post_text, $pos_start, $pos_end-$pos_start+1);
			if (($ids = getTagAttrib($tag_text, $tag, 'ids'))!='') {
				$ids_list = explode(',', $ids);
				$photos = array();
				if (count($ids_list) > 0) {
					foreach ($ids_list as $v) {
						$src = wp_get_attachment_image_src( $v, 'full' );
						if (isset($src[0]) && $src[0]!='')
							$photos[] = $src[0];
					}
				}
			} else {
				if ($post_photos===false)
					$post_photos = getPostGallery('', $post_id);
				$photos = $post_photos;
			}
			
			$post_text = themerex_substr($post_text, 0, $pos_start) . buildGalleryTag($photos, $w, $h, $zoom) . themerex_substr($post_text, $pos_end + 1);
		}
		return $post_text;
	}
}

// Return url from audio tag or shortcode, inserted in post
if (!function_exists('getPostAudio')) {
	function getPostAudio($post_text, $get_src=true) {
		$src = '';
		$tags = array('<audio>', '[trx_audio]', '[audio]');
		for ($i=0; $i<count($tags); $i++) {
			$tag = $tags[$i];
			$tag_end = themerex_substr($tag,0,1).'/'.themerex_substr($tag,1);
			if (($pos_start = themerex_strpos($post_text, themerex_substr($tag, 0, -1).' '))!==false) {
				$pos_end = themerex_strpos($post_text, themerex_substr($tag, -1), $pos_start);
				$pos_end2 = themerex_strpos($post_text, $tag_end, $pos_end);
				$tag_text = themerex_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+7 : $pos_end)-$pos_start+1);
				if ($get_src) {
					if (($src = getTagAttrib($tag_text, $tag, 'src'))=='') {
						if (($src = getTagAttrib($tag_text, $tag, 'url'))=='' && $i==1) {
							$parts = explode(' ', $tag_text);
							$src = isset($parts[1]) ? str_replace(']', '', $parts[1]) : '';
						}
					}
				} else
					$src = $tag_text;
				if ($src!='') break;
			}
		}
		if ($src == '' && $get_src) $src = getFirstURL($post_text);
		return $src;
	}
}

// Substitute audio tags
if (!function_exists('substituteAudio')) {
	function substituteAudio($post_text) {
		$tag = '<audio>';
		$tag_end = '</audio>';
		$pos_start = -1;
		while (($pos_start = themerex_strpos($post_text, themerex_substr($tag, 0, -1).' ', $pos_start+1))!==false) {
			$pos_end = themerex_strpos($post_text, themerex_substr($tag, -1), $pos_start);
			$pos_end2 = themerex_strpos($post_text, $tag_end, $pos_end);
			$tag_text = themerex_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+7 : $pos_end)-$pos_start+1);
			if (($src = getTagAttrib($tag_text, $tag, 'src'))=='')
				$src = getTagAttrib($tag_text, $tag, 'url');
			if ($src != '') {
				$id = getTagAttrib($tag_text, $tag, 'id');
				$tag_w = getTagAttrib($tag_text, $tag, 'width');
				$tag_h = getTagAttrib($tag_text, $tag, 'height');
				$tag_a = getTagAttrib($tag_text, $tag, 'align');
				$tag_s = getTagAttrib($tag_text, $tag, 'style');
				$pos_end = $pos_end2!==false ? $pos_end2+8 : $pos_end+1;
				$container = '<div'.($id ? ' id="'.$id.'"' : '').' class="audio_container' . ($tag_a ? ' align'.$tag_a : '') . '"' . ($tag_s || $tag_w || $tag_h ? ' style="'.($tag_w!='' ? 'width:' . $tag_w . (themerex_substr($tag_w, -1)!='%' ? 'px' : '') . ';' : '').($tag_h!='' ? 'height:' . $tag_h . 'px;' : '') . $tag_s . '"' : '') . '>';
				$post_text = themerex_substr($post_text, 0, (themerex_substr($post_text, $pos_start-3, 3)=='<p>' ? $pos_start-3 : $pos_start)) 
					. $container
					. (themerex_strpos($src, 'soundcloud.com') !== false 
						? '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.esc_url($src).'"></iframe>'
						: $tag_text)
					. '</div>'
					. themerex_substr($post_text, (themerex_substr($post_text, $pos_end, 4)=='</p>' ? $pos_end+4 : $pos_end));
				if (themerex_strpos($src, 'soundcloud.com') === false) $pos_start += themerex_strlen($container)+10;
			}
		}
		return $post_text;
	}
}


// Substitute all media tags
if (!function_exists('substituteAll')) {
	function substituteAll($text, $w=275, $h=200) {
		if (get_custom_option('substitute_gallery')=='yes') {
			$text = substituteGallery($text, 0, $w, $h);
		}
		$text = do_shortcode(apply_filters('themerex_sc_clear_around', $text));
		if (get_custom_option('substitute_video')=='yes') {
			$text = substituteVideo($text, $w, $h);
		}
		if (get_custom_option('substitute_audio')=='yes') {
			$text = substituteAudio($text);
		}
		return $text;
	}
}

// Return url from video tag or shortcode, inserted in post
if (!function_exists('getPostVideo')) {
	function getPostVideo($post_text, $get_src=true) {
		$src = '';
		$tags = array('<video>', '[trx_video]', '[video]', '<iframe>');
		for ($i=0; $i<count($tags); $i++) {
			$tag = $tags[$i];
			$tag_end = themerex_substr($tag,0,1).'/'.themerex_substr($tag,1);
			if (($pos_start = themerex_strpos($post_text, themerex_substr($tag, 0, -1).' '))!==false) {
				$pos_end = themerex_strpos($post_text, themerex_substr($tag, -1), $pos_start);
				$pos_end2 = themerex_strpos($post_text, $tag_end, $pos_end);
				$tag_text = themerex_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+themerex_strlen($tag_end)-1 : $pos_end)-$pos_start+1);
				if ($get_src) {
					if (($src = getTagAttrib($tag_text, $tag, 'src'))=='')
						if (($src = getTagAttrib($tag_text, $tag, 'url'))=='' && $i==1) {
							$parts = explode(' ', $tag_text);
							$src = isset($parts[1]) ? str_replace(']', '', $parts[1]) : '';
						}
				} else
					$src = $tag_text;
				if ($src!='') break;
			}
		}
		if ($src == '' && $get_src) $src = getFirstURL($post_text);
		if (!isVideoYouTube($src) && !isVideoVimeo($src)) $src = '';
		return $src;
	}
}

// Substitute video tags and shortcodes
if (!function_exists('substituteVideo')) {
	function substituteVideo($post_text, $w, $h, $in_frame=true) {
		$tag = '<video>';
		$tag_end = '</video>';
		$pos_start = -1;
		while (($pos_start = themerex_strpos($post_text, themerex_substr($tag, 0, -1).' ', $pos_start+1))!==false) {
			$pos_end = themerex_strpos($post_text, themerex_substr($tag, -1), $pos_start);
			$pos_end2 = themerex_strpos($post_text, $tag_end, $pos_end);
			$tag_text = themerex_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+themerex_strlen($tag_end)-1 : $pos_end)-$pos_start+1);
			if (getTagAttrib($tag_text, $tag, 'data-frame')=='no') continue;
			if (($src = getTagAttrib($tag_text, $tag, 'src'))=='')
				$src = getTagAttrib($tag_text, $tag, 'url');
			if ($src != '') {
				$auto = getTagAttrib($tag_text, $tag, 'autoplay');
				$src = getVideoPlayerURL($src, $auto!=''); // && is_single());
				$id = getTagAttrib($tag_text, $tag, 'id');
				$tag_w = getTagAttrib($tag_text, $tag, 'width');
				$tag_h = getTagAttrib($tag_text, $tag, 'height');
				$tag_a = getTagAttrib($tag_text, $tag, 'align');
				$tag_s = getTagAttrib($tag_text, $tag, 'style');
				$video = '<iframe'.($id ? ' id="'.$id.'"' : '').' class="video_frame' . ($tag_a ? ' align'.$tag_a : '') . '"'
					. ' src="' . $src . '"'
					. ' width="' . ($tag_w ? $tag_w : $w) . '"'
					. ' height="' . ($tag_h ? $tag_h : $h) . '"'
					//. ($tag_s ? ' style="' . $tag_s . '"' : '')
					. ' frameborder="0" webkitAllowFullScreen="webkitAllowFullScreen" mozallowfullscreen="mozallowfullscreen" allowFullScreen="allowFullScreen"></iframe>';
				if ($in_frame) {
					$tag_image = getTagAttrib($tag_text, $tag, 'data-image');
					$tag_title = getTagAttrib($tag_text, $tag, 'data-title');
					$video = getVideoFrame($video, $tag_image, sc_param_is_on($tag_title), $tag_s);
				}
				$pos_end = $pos_end2!==false ? $pos_end2+8 : $pos_end+1;
				$post_text = themerex_substr($post_text, 0, (themerex_substr($post_text, $pos_start-3, 3)=='<p>' ? $pos_start-3 : $pos_start)) 
					. $video
					. themerex_substr($post_text, (themerex_substr($post_text, $pos_end, 4)=='</p>' ? $pos_end+4 : $pos_end));
			}
		}
		return $post_text;
	}
}


// Return video frame layout
if (!function_exists('getVideoFrame')) {
	function getVideoFrame($video, $image='', $title=false, $style='') {
		$tag = themerex_strpos($video, '<iframe')!==false ? '<iframe>' : '<video>';
		$tag_w = getTagAttrib($video, $tag, 'width');
		$tag_h = getTagAttrib($video, $tag, 'height');
		$html = '<div class="sc_video_player"' . ' data-width="'.$tag_w.'" data-height="'.$tag_h.'"'.($style ? ' style="' . $style . '"' : '') . '>'
				. ($title ? '<div class="sc_video_player_title"></div>' : '')
				. '<div class="sc_video_frame' . ($image ? ' sc_video_play_button' : '') . '"' . ($image ? ' data-video="'.esc_attr($video).'"' : '') . '>'
				. ($image ? (themerex_strpos($image, '<img')!==false ? $image : '<img alt="" src="'.$image.'">') : $video)
				. '</div>'
				. '</div>'
			;
		return $html;
	}
}


// Return url from img tag or shortcode, inserted in post
if (!function_exists('getPostImage')) {
	function getPostImage($post_text, $get_src=true) {
		$src = '';
		$tags = array('<img>', '[trx_image]', '[image]');
		for ($i=0; $i<count($tags); $i++) {
			$tag = $tags[$i];
			if (($pos_start = themerex_strpos($post_text, themerex_substr($tag, 0, -1).' '))!==false) {
				$pos_end = themerex_strpos($post_text, themerex_substr($tag, -1), $pos_start);
				$tag_text = themerex_substr($post_text, $pos_start, $pos_end-$pos_start+1);
				if ($get_src) {
					if (($src = getTagAttrib($tag_text, $tag, 'src'))=='')
						$src = getTagAttrib($tag_text, $tag, 'url');
				} else
					$src = $tag_text;
				if ($src!='') break;
			}
		}
		if ($src == '' && $get_src) $src = getFirstURL($post_text);
		return $src;
	}
}


// Return url from tag a, inserted in post
if (!function_exists('getPostLink')) {
	function getPostLink($post_text) {
		$src = '';
		$target = '';
		$tag = '<a>';
		$tag_end = '</a>';
		if (($pos_start = themerex_strpos($post_text, themerex_substr($tag, 0, -1).' '))!==false) {
			$pos_end = themerex_strpos($post_text, themerex_substr($tag, -1), $pos_start);
			$pos_end2 = themerex_strpos($post_text, $tag_end, $pos_end);
			$tag_text = themerex_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+7 : $pos_end)-$pos_start+1);
			$src = getTagAttrib($tag_text, $tag, 'href');
			$target = getTagAttrib($tag_text, $tag, 'target');
		}
		if ($src == '') $src = getFirstURL($post_text);
		return array('url'=>$src, 'target'=>$target);
	}
}


if (!function_exists('getFirstURL')) {
	function getFirstURL($post_text) {
		$src = '';
		if (($pos_start = themerex_strpos($post_text, 'http'))!==false) {
			for ($i=$pos_start; $i<themerex_strlen($post_text); $i++) {
				$ch = themerex_substr($post_text, $i, 1);
				if (themerex_strpos("< \n\"\'", $ch)!==false) break;
				$src .= $ch;
			}
		}
		return $src;
	}
}




/* ========================= Social share links ============================== */

$THEMEREX_share_social_list = array(
	'blogger' => array('url'=>'http://www.blogger.com/blog_this.pyra?t&u={link}&n={title}'),
	'bobrdobr' => array('url'=>'http://bobrdobr.ru/add.html?url={link}&title={title}&desc={descr}'),
	'delicious' => array('url'=>'http://delicious.com/save?url={link}&title={title}&note={descr}'),
	'designbump' => array('url'=>'http://designbump.com/node/add/drigg/?url={link}&title={title}'),
	'designfloat' => array('url'=>'http://www.designfloat.com/submit.php?url={link}'),
	'digg' => array('url'=>'http://digg.com/submit?url={link}'),
	'evernote' => array('url'=>'https://www.evernote.com/clip.action?url={link}&title={title}'),
	'facebook' => array('url'=>'http://www.facebook.com/sharer.php?s=100&p[url]={link}&p[title]={title}&p[summary]={descr}&p[images][0]={image}'),
	'friendfeed' => array('url'=>'http://www.friendfeed.com/share?title={title} - {link}'),
	'google' => array('url'=>'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={link}&title={title}&annotation={descr}'),
	'gplus' => array('url'=>'https://plus.google.com/share?url={link}'), 
	'identi' => array('url'=>'http://identi.ca/notice/new?status_textarea={title} - {link}'), 
	'juick' => array('url'=>'http://www.juick.com/post?body={title} - {link}'),
	'linkedin' => array('url'=>'http://www.linkedin.com/shareArticle?mini=true&url={link}&title={title}'), 
	'liveinternet' => array('url'=>'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl={link}&cntitle={title}'),
	'livejournal' => array('url'=>'http://www.livejournal.com/update.bml?event={link}&subject={title}'),
	'mail' => array('url'=>'http://connect.mail.ru/share?url={link}&title={title}&description={descr}&imageurl={image}'),
	'memori' => array('url'=>'http://memori.ru/link/?sm=1&u_data[url]={link}&u_data[name]={title}'), 
	'mister-wong' => array('url'=>'http://www.mister-wong.ru/index.php?action=addurl&bm_url={link}&bm_description={title}'), 
	'mixx' => array('url'=>'http://chime.in/chimebutton/compose/?utm_source=bookmarklet&utm_medium=compose&utm_campaign=chime&chime[url]={link}&chime[title]={title}&chime[body]={descr}'), 
	'moykrug' => array('url'=>'http://share.yandex.ru/go.xml?service=moikrug&url={link}&title={title}&description={descr}'),
	'myspace' => array('url'=>'http://www.myspace.com/Modules/PostTo/Pages/?u={link}&t={title}&c={descr}'), 
	'newsvine' => array('url'=>'http://www.newsvine.com/_tools/seed&save?u={link}&h={title}'),
	'odnoklassniki' => array('url'=>'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl={link}&title={title}'), 
	'pikabu' => array('url'=>'http://pikabu.ru/add_story.php?story_url={link}'),
	'pinterest' => array('url'=>'http://pinterest.com/pin/create/button/?url={link}&media={image}&description={title}'),
	'posterous' => array('url'=>'http://posterous.com/share?linkto={link}&title={title}'),
	'postila' => array('url'=>'http://postila.ru/publish/?url={link}&agregator=themerex'),
	'reddit' => array('url'=>'"http://reddit.com/submit?url={link}&title={title}'), 
	'rutvit' => array('url'=>'http://rutvit.ru/tools/widgets/share/popup?url={link}&title={title}'), 
	'stumbleupon' => array('url'=>'http://www.stumbleupon.com/submit?url={link}&title={title}'), 
	'surfingbird' => array('url'=>'http://surfingbird.ru/share?url={link}'), 
	'technorati' => array('url'=>'http://technorati.com/faves?add={link}&title={title}'), 
	'tumblr' => array('url'=>'http://www.tumblr.com/share?v=3&u={link}&t={title}&s={descr}'), 
	'twitter' => array('url'=>'https://twitter.com/intent/tweet?text={title}&url={link}'),
	'vk' => array('url'=>'http://vk.com/share.php?url={link}&title={title}&description={descr}'),
	'vk2' => array('url'=>'http://vk.com/share.php?url={link}&title={title}&description={descr}'),
	'webdiscover' => array('url'=>'http://webdiscover.ru/share.php?url={link}'),
	'yahoo' => array('url'=>'http://bookmarks.yahoo.com/toolbar/savebm?u={link}&t={title}&d={descr}'),
	'yandex' => array('url'=>'http://zakladki.yandex.ru/newlink.xml?url={link}&name={title}&descr={descr}'),
	'ya' => array('url'=>'http://my.ya.ru/posts_add_link.xml?URL={link}&title={title}&body={descr}'), 
	'yosmi' => array('url'=>'http://yosmi.ru/index.php?do=share&url={link}') 
);


// Return (and show) share social links
if (!function_exists('showShareSocialLinks')) {
	function showShareSocialLinks($args) {
		$args = array_merge(array(
			'post_id' => 0,						// post ID
			'post_link' => '',					// post link
			'post_title' => '',					// post title
			'post_descr' => '',					// post descr
			'post_thumb' => '',					// post featured image
			'use_icons' => false,				// use font icons or images
			'counters' => false,				// show share counters
			'direction' => 'horizontal',		// share block direction
			'style' => 'block',					// share block style: list|block|drop
			'caption' => '',					// share block caption
			'popup' => true,					// open share url in new window or in popup window
			'share' => array(),					// list of allowed socials
			'echo' => true						// if true - show on page, else - only return as string
			), $args);
		global $THEMEREX_share_social_list;
		if (count($args['share'])==0 || implode('', $args['share'][0])=='') return '';	// $args['share'] = $THEMEREX_share_social_list;
		$output = $args['style']=='block'
			? '<div class="post_info post_info_bottom"><div class="share-social share-dir-' . $args['direction'] . '">' . ($args['caption']!='' ? '<span class="share-caption">'.$args['caption'].'</span>' : '')
			: '<ul class="share-social'.($args['style']=='drop' ? ' shareDrop' : '').'">';
		foreach ($args['share'] as $s => $data) {
			if (!empty($data['icon'])) {
				if (!$args['use_icons']) {
					$s = basename($data['icon']);
					$s = themerex_substr($s, 0, themerex_strrpos($s, '.'));
					if (($pos=themerex_strrpos($s, '_'))!==false)
						$s = themerex_substr($s, 0, $pos);
				}
			}
			$link = str_replace(
				array('{id}', '{link}', '{title}', '{descr}', '{image}'),
				array(
					urlencode($args['post_id']),
					urlencode($args['post_link']),
					urlencode(strip_tags($args['post_title'])),
					urlencode(strip_tags($args['post_descr'])),
					urlencode($args['post_thumb'])
					),
				empty($data['url']) && isset($THEMEREX_share_social_list[$s]['url']) && !empty($THEMEREX_share_social_list[$s]['url']) ? $THEMEREX_share_social_list[$s]['url'] : $data['url']);
			$output .= (in_array($args['style'], array('list', 'drop')) ? '<li>' : '') 
				. '<a href="' . ($args['popup'] ? '#' : esc_attr($link)) . '" class="share-item' . ($args['use_icons'] ? ' ' . $data['icon'] : '').'"' 
					. ($args['popup'] ? ' onclick="window.open(\'' . $link .'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=480, height=400, toolbar=0, status=0\'); return false;"' : ' target="_blank"') . ($args['counters'] ? ' data-count="' . $s . '"' : '') 
				. '>' 
				. ($args['use_icons'] ? '' : '<img src="'.$data['icon'].'" alt="' . $s . '">')
				. ($args['style']=='drop' ? themerex_strtoproper($s) : '') 
				. '</a>'
				. (in_array($args['style'], array('list', 'drop')) ? '</li>' : '') 
				;
		}
		$output .= $args['style']=='block' ? '</div></div>' : '</ul>';
		if ($args['echo']) echo balanceTags($output);
		return $output;
	}
}



// Show share social links wrapper
if (!function_exists('showShareButtons')) {
	function showShareButtons($post_data) {
		$rez = '';
		if ( get_custom_option('show_share')=='yes' ) {
			$socials = get_theme_option('share_buttons');
			if (is_array($socials) && count($socials) > 0 && implode('', $socials[0])!='') {
				$rez = showShareSocialLinks(array(
					'echo'      => false,
					'post_id'   => $post_data['post_id'],
					'post_link' => $post_data['post_link'],
					'post_title' => $post_data['post_title'],
					'post_descr' => $post_data['post_descr'],
					'post_thumb' => $post_data['post_thumb'],
					'caption' => get_theme_option('share_caption'),
					'share' => $socials,
					'counters' => get_theme_option('show_share_counters')=='yes',
					'direction' => get_theme_option('share_direction'),
					'style' => !empty($post_data['style']) ? $post_data['style'] : 'block'	//'block'
				));
			}
		}
		if ($rez && !empty($post_data['echo']) && $post_data['echo']) echo ($rez);
		return $rez;
	}
}


/* ========================= User profile section ============================== */

$THEMEREX_user_social_list = array(
	'facebook' => __('Facebook', 'themerex'),
	'twitter' => __('Twitter', 'themerex'),
	'gplus' => __('Google+', 'themerex'),
	'linkedin' => __('LinkedIn', 'themerex'),
	'dribbble' => __('Dribbble', 'themerex'),
	'pinterest' => __('Pinterest', 'themerex'),
	'tumblr' => __('Tumblr', 'themerex'),
	'behance' => __('Behance', 'themerex'),
	'youtube' => __('Youtube', 'themerex'),
	'vimeo' => __('Vimeo', 'themerex'),
	'rss' => __('RSS', 'themerex'),
	);

// Return (and show) user profiles links
if (!function_exists('showUserSocialLinks')) {
	function showUserSocialLinks($args) {
		$args = array_merge(array(
			'author_id' => 0,						// author's ID
			'allowed' => array(),					// list of allowed social
			'style' => 'bg',						// style for show icons: icons|images|bg
			'before' => '',
			'after' => '',
			'echo' => true							// if true - show on page, else - only return as string
			), is_array($args) ? $args 
				: array('author_id' => $args));		// If send one number parameter - use it as author's ID
		global $THEMEREX_user_social_list;
		$output = '';
		if (count($args['allowed'])==0) $args['allowed'] = array_keys($THEMEREX_user_social_list);
		foreach ($args['allowed'] as $s) {
			if (array_key_exists($s, $THEMEREX_user_social_list)) {
				$link = get_the_author_meta('user_' . $s, $args['author_id']);
				if ($link) {
					$img = themerex_get_socials_url($s);
					$output .= $args['before']
						. '<a href="' . $link . '" class="social_icons social_' . $s . ' ' . $s . '" target="_blank"'
						. ($args['style']=='bg' ? ' style="background-image: url('.$img.');"' : '')
						. '>'
						. ($args['style']=='icons' ? '<span class="icon-' . $s . '"></span>' : ($args['style']=='images' ? '<img src="'.$img.'" alt="" />' : '<span style="background-image: url('.$img.');"></span>'))
						. '</a>'
						. $args['after'];
				}
			}
		}
		if ($args['echo']) echo balanceTags($output);
		return $output;
	}
}


// show additional fields
add_action( 'show_user_profile', 'themerex_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'themerex_show_extra_profile_fields' );
if (!function_exists('themerex_show_extra_profile_fields')) {
	function themerex_show_extra_profile_fields( $user ) { 
		global $THEMEREX_user_social_list;
	?>
		<h3>User Position</h3>
		<table class="form-table">
			<tr>
				<th><label for="user_position"><?php _e('User position', 'themerex'); ?>:</label></th>
				<td><input type="text" name="user_position" id="user_position" size="55" value="<?php echo esc_attr(get_the_author_meta('user_position', $user->ID)); ?>" />
					<span class="description"><?php _e('Please, enter your position in the company', 'themerex'); ?></span>
				</td>
			</tr>
		</table>
	
		<h3>Social links</h3>
		<table class="form-table">
		<?php
		foreach ($THEMEREX_user_social_list as $name=>$title) {
		?>
			<tr>
				<th><label for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?>:</label></th>
				<td><input type="text" name="user_<?php echo esc_attr($name); ?>" id="user_<?php echo esc_attr($name); ?>" size="55" value="<?php echo esc_attr(get_the_author_meta('user_'.$name, $user->ID)); ?>" />
					<span class="description"><?php echo sprintf(__('Please, enter your %s link', 'themerex'), $title); ?></span>
				</td>
			</tr>
		<?php
		}
		?>
		</table>
	<?php
	}
}

// Save / update additional fields
add_action( 'personal_options_update', 'themerex_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'themerex_save_extra_profile_fields' );
if (!function_exists('themerex_save_extra_profile_fields')) {
	function themerex_save_extra_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;
		update_user_meta( $user_id, 'user_position', $_POST['user_position'] );
		global $THEMEREX_user_social_list;
		foreach ($THEMEREX_user_social_list as $name=>$title)
			update_user_meta( $user_id, 'user_'.$name, $_POST['user_'.$name] );
	}
}


// Check current user (or user with specified ID) role
// For example: if (themerex_check_user_role('author')) { ... }
if (!function_exists('themerex_check_user_role')) {
	function themerex_check_user_role( $role, $user_id = null ) {
		if ( is_numeric( $user_id ) )
			$user = get_userdata( $user_id );
		else
			$user = wp_get_current_user();
		if ( empty( $user ) )
			return false;
		return in_array( $role, (array) $user->roles );
	}
}






/* ========================= Other functions section ============================== */

// Clear WP cache (all, options or categories)
if (!function_exists('themerex_clear_cache')) {
	function themerex_clear_cache($cc) {
		if ($cc == 'categories' || $cc == 'all') {
			wp_cache_delete('category_children', 'options');
			foreach ( get_taxonomies() as $tax ) {
				delete_option( "{$tax}_children" );
				_get_term_hierarchy( $tax );
			}
		} else if ($cc == 'options' || $cc == 'all')
			wp_cache_delete('alloptions', 'options');
		if ($cc == 'all')
			wp_cache_flush();
	}
}

// Add data in inline styles block
if (!function_exists('wp_style_add_data')) {
	function wp_style_add_data($css, $cond, $expr) {
		global $wp_styles;
		if (is_object($wp_styles)) {
			return $wp_styles->add_data($css, $cond, $expr);
		}
		return false;
	}
}

// Set mail content type
if (!function_exists('set_html_content_type')) {
	function set_html_content_type() {
		return 'text/html';
	}
}

// Return difference or date
if (!function_exists('getDateOrDifference')) {
	function getDateOrDifference($dt1, $dt2=null, $max_days=-1) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($max_days < 0) $max_days = get_theme_option('show_date_after', 30);
		if ($dt2 == null) $dt2 = date('Y-m-d H:i:s');
		$dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		$diff = $dt2n - $dt1n;
		$days = floor($diff / (24*3600));
		if ($days < $max_days)
			return sprintf(__('%s ago', 'themerex'), dateDifference($dt1, $dt2));
		else
			return prepareDateForTranslation(date(get_option('date_format'), $dt1n));
	}
}

// Difference between two dates
if (!function_exists('dateDifference')) {
	function dateDifference($dt1, $dt2=null, $short=true, $sec = false) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($dt2 == null) $dt2 = time();
		else $dt2 = strtotime($dt2);
		$dt2 += $gmt_offset*3600;
		$dt1 = strtotime($dt1);
		$diff = $dt2 - $dt1;
		$days = floor($diff / (24*3600));
		$diff -= $days * 24 * 3600;
		$hours = floor($diff / 3600);
		$diff -= $hours * 3600;
		$min = floor($diff / 60);
		$diff -= $min * 60;
		$rez = '';
		if ($days > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($days > 1 ? __('%s days', 'themerex') : __('%s day', 'themerex'), $days);
		if ((!$short || $rez=='') && $hours > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($hours > 1 ? __('%s hours', 'themerex') : __('%s hour', 'themerex'), $hours);
		if ((!$short || $rez=='') && $min > 0)
			$rez .= ($rez!='' ? ' ' : '') . sprintf($min > 1 ? __('%s minutes', 'themerex') : __('%s minute', 'themerex'), $min);
		if ($sec || $rez=='')
			$rez .=  $rez!='' || $sec ? (' ' . sprintf($diff > 1 ? __('%s seconds', 'themerex') : __('%s second', 'themerex'), $diff)) : __('less then minute', 'themerex');
		return $rez;
	}
}


// Prepare month names in date for translation
if (!function_exists('prepareDateForTranslation')) {
	function prepareDateForTranslation($dt) {
		return str_replace(
			array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			array(
				__('January', 'themerex'),
				__('February', 'themerex'),
				__('March', 'themerex'),
				__('April', 'themerex'),
				__('May', 'themerex'),
				__('June', 'themerex'),
				__('July', 'themerex'),
				__('August', 'themerex'),
				__('September', 'themerex'),
				__('October', 'themerex'),
				__('November', 'themerex'),
				__('December', 'themerex'),
				__('Jan', 'themerex'),
				__('Feb', 'themerex'),
				__('Mar', 'themerex'),
				__('Apr', 'themerex'),
				__('May', 'themerex'),
				__('Jun', 'themerex'),
				__('Jul', 'themerex'),
				__('Aug', 'themerex'),
				__('Sep', 'themerex'),
				__('Oct', 'themerex'),
				__('Nov', 'themerex'),
				__('Dec', 'themerex'),
			),
			$dt);
	}
}


// Decorate 'read more...' link
if (!function_exists('decorateMoreLink')) {
	function decorateMoreLink($text, $tag_start='<div class="readmore">', $tag_end='</div>') {
		//return preg_replace('/(<a[^>]+class="more-link"[^>]*>[^<]*<\\/a>)/', "{$tag_start}\${1}{$tag_end}", $text);
		$rez = $text;
		if (($pos = themerex_strpos($text, ' class="more-link"><span class="readmore">'))!==false) {
			$i = $pos-1;
			while ($i > 0) {
				if (themerex_substr($text, $i, 3) == '<a ') {
					if (($pos = themerex_strpos($text, '</span></a>', $pos))!== false) {
						$pos += 11;
						$start = themerex_substr($text, $i-4, 4) == '<p> ' ? $i-4 : (themerex_substr($text, $i-3, 3) == '<p>' ? $i-3 : $i);
						$end   = themerex_substr($text, $pos, 4) == '</p>' ? $pos+4 : $pos;
						$rez = themerex_substr($text, 0, $start) . $tag_start . themerex_substr($text, $i, $pos-$i) . $tag_end . themerex_substr($text, $end);
						break;
					}
				}
				$i--;
			}
		}
		return $rez;
	}
}







/* ========================= System messages ============================== */

if (!function_exists('themerex_set_message')) {
	function themerex_set_message($msg, $status='info', $hdr='') {
		update_option('themerex_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('themerex_get_message')) {
	function themerex_get_message($del=false) {
		$msg = get_option('themerex_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			themerex_del_message();
		return $msg;
	}
}

if (!function_exists('themerex_del_message')) {
	function themerex_del_message() {
		delete_option('themerex_message');
	}
}





/* ========================= Aqua resizer wrapper ============================== */


if (!function_exists('getResizedImageTag')) {
	function getResizedImageTag( $post, $w=null, $h=null, $c=null, $u=true, $find_thumb=false, $itemprop=false ) {
		static $mult = 0;
		if ($mult == 0) $mult = min(2, max(1, get_theme_option("retina_ready")));
		if (is_object($post))		$alt = getPostTitle( $post->ID );
		else if ((int) $post > 0) 	$alt = getPostTitle( $post );
		else						$alt = basename($post);
		$url = getResizedImageURL($post, $w ? $w*$mult : $w, $h ? $h*$mult : $h, $c, $u, $find_thumb);
		return $url!='' ? ('<img class="wp-post-image"' . ($w ? ' width="'.$w.'"' : '') . ($h ? ' height="' . $h . '"' : '') . ' alt="' . $alt . '" src="' . $url . '"' . ($itemprop ? ' itemprop="image"' : '') . '>') : '';
	}
}


if (!function_exists('getResizedImageURL')) {
	function getResizedImageURL( $post, $w=null, $h=null, $c=null, $u=true, $find_thumb=false ) {
		$url = '';
		if (is_object($post) || abs((int) $post) != 0) {
			$thumb_id = is_object($post) || $post > 0 ? get_post_thumbnail_id( is_object($post) ? $post->ID : $post ) : abs($post);
			if (!$thumb_id && $find_thumb) {
				$args = array(
						'numberposts' => 1,
						'order' => 'ASC',
						'post_mime_type' => 'image',
						'post_parent' => $post,
						'post_status' => 'any',
						'post_type' => 'attachment',
					);
				$attachments = get_children( $args );
				foreach ( $attachments as $attachment ) {
					$thumb_id = $attachment->ID;
					break;
				}
			}
			if ($thumb_id) {
				$src = wp_get_attachment_image_src( $thumb_id, 'full' );
				$url = $src[0];
			}
			if ($url == '' && $find_thumb) {
				if (($data = get_post(is_object($post) ? $post->ID : $post))!==null) {
					$url = getTagAttrib($data->post_content, '<img>', 'src');
				}
			}
		} else
			$url = trim(chop($post));
		if ($url != '') {
			if ($c === null) $c = true;	//$c = get_option('thumbnail_crop')==1;
			if ( ! ($new_url = aq_resize( $url, $w, $h, $c, true, $u)) ) {
				if (false)
					$new_url = get_the_post_thumbnail($url, array($w, $h));
				else
					$new_url = $url;
			}
		} else $new_url = '';
		return $new_url;
	}
}

if (!function_exists('getUploadsDirFromURL')) {
	function getUploadsDirFromURL($url) {
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		if (!strncmp($url,$https_prefix,themerex_strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix, $https_prefix, $upload_url);
		} else if (!strncmp($url,$http_prefix,themerex_strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix, $http_prefix, $upload_url);		
		}
	
		// Check if $img_url is local.
		if ( false === themerex_strpos( $url, $upload_url ) ) return false;
	
		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = $upload_dir . $rel_path;
		
		return $img_path;
	}
}







/* ========================= File system functions section ============================== */


if (!function_exists('themerex_fpc')) {
	function themerex_fpc($file, $content, $flag=0) {
		$fn = join('_', array('file', 'put', 'contents'));
		return @$fn($file, $content, $flag);
	}
}

if (!function_exists('themerex_fgc')) {
	function themerex_fgc($file) {
		$fn = join('_', array('file', 'get', 'contents'));
		return @$fn($file);
	}
}

if (!function_exists('themerex_fga')) {
	function themerex_fga($file) {
		return @file($file);
	}
}

if (!function_exists('themerex_escape_shell_cmd')) {
	function themerex_escape_shell_cmd($file) {
		//return function_exists('escapeshellcmd') ? @escapeshellcmd($file) : str_replace(array('~', '>', '<', '|'), '', $file);
		return str_replace(array('~', '>', '<', '|', '"', "'", '`', "\xFF", "\x0A", '#', '&', ';', ':', '*', '?', '^', '(', ')', '[', ']', '{', '}', '$', '\\'), '', $file);
	}
}








/* ========================= Sliders section ============================== */


// Return true if Revolution slider activated
if (!function_exists('revslider_exists')) {
	function revslider_exists() {
		return is_plugin_active('revslider/revslider.php'); //function_exists('putRevSlider');
	}
}

// Return true if Royal slider activated
if (!function_exists('royalslider_exists')) {
	function royalslider_exists() {
		return is_plugin_active('royalslider/newroyalslider.php') || is_plugin_active('new-royalslider/newroyalslider.php');	//function_exists('get_new_royalslider') || function_exists('get_royalslider');
	}
}







/* ========================= Enqueue functions section ============================== */

// Enqueue .min.css (if exists and filetime .min.css > filetime .css) instead .css
if (!function_exists('themerex_enqueue_style')) {
	function themerex_enqueue_style($handle, $src=false, $depts=array(), $ver=null, $media='all') {
		if (!is_array($src) && $src !== false && $src !== '') {
			global $THEMEREX_DEBUG_MODE;
			//if (empty($THEMEREX_DEBUG_MODE)) $THEMEREX_DEBUG_MODE = get_theme_option('debug_mode');
			$THEMEREX_DEBUG_MODE = false;
			$theme_dir = get_template_directory();
			$theme_url = get_template_directory_uri();
			$child_dir = get_stylesheet_directory();
			$child_url = get_stylesheet_directory_uri();
			$dir = $url = '';
			if (themerex_strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (themerex_strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if ($dir != '') {
				if ($THEMEREX_DEBUG_MODE=='no') {
					if (themerex_substr($src, -4)=='.css') {
						if (themerex_substr($src, -8)!='.min.css') {
							$src_min = themerex_substr($src, 0, themerex_strlen($src)-4).'.min.css';
							$file_src = $dir.themerex_substr($src, themerex_strlen($url));
							$file_min = $dir.themerex_substr($src_min, themerex_strlen($url));
							if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
						}
					}
				}
			}
		}
		if (is_array($src))
			wp_enqueue_style( $handle, $depts, $ver, $media );
		else
			wp_enqueue_style( $handle, $src, $depts, $ver, $media );
	}
}

// Enqueue .min.js (if exists and filetime .min.js > filetime .js) instead .js
if (!function_exists('themerex_enqueue_script')) {
	function themerex_enqueue_script($handle, $src=false, $depts=array(), $ver=null, $in_footer=false) {
		if (!is_array($src) && $src !== false && $src !== '') {
			global $THEMEREX_DEBUG_MODE;
			//if (empty($THEMEREX_DEBUG_MODE)) $THEMEREX_DEBUG_MODE = get_theme_option('debug_mode');
			$THEMEREX_DEBUG_MODE = false;
			$theme_dir = get_template_directory();
			$theme_url = get_template_directory_uri();
			$child_dir = get_stylesheet_directory();
			$child_url = get_stylesheet_directory_uri();
			$dir = $url = '';
			if (themerex_strpos($src, $child_url)===0) {
				$dir = $child_dir;
				$url = $child_url;
			} else if (themerex_strpos($src, $theme_url)===0) {
				$dir = $theme_dir;
				$url = $theme_url;
			}
			if ($dir != '') {
				if ($THEMEREX_DEBUG_MODE=='no') {
					if (themerex_substr($src, -3)=='.js') {
						if (themerex_substr($src, -7)!='.min.js') {
							$src_min = themerex_substr($src, 0, themerex_strlen($src)-3).'.min.js';
							$file_src = $dir.themerex_substr($src, themerex_strlen($url));
							$file_min = $dir.themerex_substr($src_min, themerex_strlen($url));
							if (file_exists($file_min) && filemtime($file_src) <= filemtime($file_min)) $src = $src_min;
						}
					}
				}
			}
		}
		if (is_array($src))
			wp_enqueue_script( $handle, $depts, $ver, $in_footer );
		else
			wp_enqueue_script( $handle, $src, $depts, $ver, $in_footer );
	}
}

// Detect skin version of the social icon (if exists), else return it from template images directory
if (!function_exists('themerex_get_socials_dir')) {
	function themerex_get_socials_dir($soc, $return_url=false) {
		static $skin_dir;
		$skin_dir  = '/skins/'.themerex_escape_shell_cmd(get_custom_option('theme_skin'));
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$soc = '/images/socials/'.$soc.(themerex_strpos($soc, '.')===false ? '.png' : '');
		if (file_exists($child_dir.$skin_dir.$soc))
			$img = ($return_url ? $child_url : $child_dir).$skin_dir.$soc;
		else if (file_exists($theme_dir.$skin_dir.$soc))
			$img = ($return_url ? $theme_url : $theme_dir).$skin_dir.$soc;
		else
			$img = themerex_get_file_dir($soc, $return_url);
		return $img;
	}
}
if (!function_exists('themerex_get_socials_url')) {
	function themerex_get_socials_url($soc) {
		return themerex_get_socials_dir($soc, true);
	}
}

// Detect child version and return path to file from child directory (if exists), else from template directory
if (!function_exists('themerex_get_file_dir')) {
	function themerex_get_file_dir($file, $return_url=false) {
		if ($file[0]!='/') $file = '/'.$file;
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		if (file_exists($child_dir.$file)) return ($return_url ? $child_url : $child_dir) . $file;
		else return ($return_url ? $theme_url : $theme_dir) . $file;
	}
}
if (!function_exists('themerex_get_file_url')) {
	function themerex_get_file_url($file) {
		return themerex_get_file_dir($file, true);
	}
}

// Detect child version and return path to folder from child directory (if exists), else from template directory
if (!function_exists('themerex_get_folder_dir')) {
	function themerex_get_folder_dir($folder, $return_url=false) {
		if ($folder[0]!='/') $folder = '/'.$folder;
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		if (is_dir($child_dir.$folder)) return ($return_url ? $child_url : $child_dir) . $folder;
		else return ($return_url ? $theme_url : $theme_dir) . $folder;
	}
}
if (!function_exists('themerex_get_folder_url')) {
	function themerex_get_folder_url($folder) {
		return themerex_get_folder_dir($folder, true);
	}
}

?>