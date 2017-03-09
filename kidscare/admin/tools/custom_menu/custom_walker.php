<?php
/**
 * Custom Walker
 *
 * @access			public
 * @since			 1.0 
 * @return			void
*/
class themerex_walker extends Walker_Nav_Menu
{

	var $level_holder = array();
	var $item_data = '';
	var $thumb_placeholder = '';
	var $top_menu_view = '';

	function start_el(&$output, $item, $depth=0, $args=array(), $current_object_id=0) {
		global $wp_query;
					
		if($depth == 0) {
			$this->item_data = $item;
		}
		
		// WP date format
		$df = get_option('date_format');

		// Menu item description
		$description = $description_title = '';
		if (!empty( $item->description )) {
			if (!function_exists('get_custom_option') || get_custom_option('menu_description')=='below')
				$description = '<span class="menu_item_description'
					//. ($depth > 0 && !empty($item->item_icon_class) ? ' menu_icon_padding' : '' )
					. '">'
					. str_replace('#', '<br>', esc_attr( $item->description )).'</span>';
			else
				$description_title = ' title="'.str_replace('#', "\n", esc_attr( $item->description )).'"';
		}
		//if ($depth != 0) $description = "";
		
		$top_level_type	= (isset($item->top_level_type) ? $item->top_level_type : '');
					
		if ($top_level_type == 'auto') { // If auto method set
				
			$item_output = '';

			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			if ($depth == 0 && $item->top_menu_view != 'default') {
				$class_names .= ' ' . $item->top_menu_view . ' custom_view_item';
			}
			$class_names = ' class="menu-item-has-children '. esc_attr( $class_names ) . '"';
			$item_output .= '<li'.$class_names.'>';
				
			// Setting Defaults
			$view_type		= (isset($item->top_menu_view) ? $item->top_menu_view : '');
			$item_icon		= (isset($item->item_icon_class) ? $item->item_icon_class : '');
			$item_thumb		= (isset($item->item_thumb) ? $item->item_thumb : '');
			$item_view		= (isset($item->top_menu_view) ? $item->top_menu_view : '');
			$item_count		= (isset($item->auto_items_count) ? $item->auto_items_count : '');
			$item_sorting	= (isset($item->item_sorting_by) ? $item->item_sorting_by : '');
			$post_types		= (isset($item->post_types_list) ? $item->post_types_list : '');

			if (!empty($post_types)) {
				$post_types = explode(',', $post_types);
			}
			$query_order = 'DESC';
			if($item_sorting == 'title') {
				$query_order = 'ASC';
			}
				
			$post_cat_list = (isset($item->categories) ? $item->categories : '');
			$categories = $inner_output = $query_args = array();
			$attributes = '';
			$item_output .= $args->before;
															
			$attributes .= ! empty( $item_thumb ) 		? 'data-thumb="' . esc_attr( $item_thumb ) .'"' : '';
			$attributes .= ! empty( $item->attr_title ) ? ' title="'	. esc_attr( $item->attr_title ) .'"' : $description_title;
			$attributes .= ! empty( $item->target )		? ' target="' . esc_attr( $item->target ) .'"' : '';
			$attributes .= ! empty( $item->xfn )		? ' rel="'		. esc_attr( $item->xfn) .'"' : '';
			$attributes .= ! empty( $item->url )		? ' href="'	 . esc_attr( $item->url) .'"' : '';
				
			$item_output .= '<a ' . $attributes . '>' 
				. (!empty($item->item_icon_class) ? '<span class="menu_icon '.$item->item_icon_class.'"></span>' : '')
				. $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $description . $args->link_after . '</a>';

			/* Auto menu query */
			if(!empty($post_cat_list)) {
				$tax_names = array();
				if(count($post_types) > 0) {
					foreach ($post_types as $type) {
						$types_tax = get_object_taxonomies($type);
						foreach ($types_tax as $tax_type) {
							$tax_names[] = $tax_type;
						}
					}
				}    
				$categories = explode(',', $post_cat_list);
				$term_obj = array();
				        
				foreach ($categories as $category) {
					$category = trim(chop($category));
					foreach ($tax_names as $name) {
						
						$term_temp = get_term_by('slug', $category, $name, ARRAY_A);
						
						if($term_temp) {
							$term_obj[$category] = $term_temp;
							$term_obj[$category]['term_link'] = get_term_link($category, $name);
							break;
						}
					}
				}
				
				foreach ($term_obj as $term) {
				
					$query_args['post_type'] = $post_types;
					$query_args['tax_query'] = array();
					
					$query_args['tax_query'][] = array(
						'taxonomy' => $name,
						'field' => 'slug',
						'terms' => array($term['slug'])
					);
					
					if(!empty($item_count)) {
						$query_args['posts_per_page'] = $item_count;
					}
					$query_args['order'] = $query_order; 
					
					$the_query = new WP_Query( $query_args );
		
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						
						//Get the item data
						$item_title = get_the_title();
						$item_id = get_the_ID();
						$item_link = get_permalink($item_id);
						$comment_count = wp_count_comments($item_id);
						$post_date = get_the_date();			      
						$inner_output[$term['slug']][$item_id] = array(
							'item_title' => $item_title,
							'item_link' => $item_link
						);										
					}
					$inner_output[$term['slug']]['term_link'] = $term['term_link'];  
					$inner_output[$term['slug']]['term_name'] = $term['name'];  
				}
				wp_reset_postdata();
			}

			if (!empty($inner_output)) {


				if($view_type == 'columns') {

					$item_output .= '<ul class="menu-panel columns"><li><ul class="custom-menu-style columns sub-menu">';
					$list_num = 0;
					foreach ($inner_output as $cat => $cat_val) {
						$item_output .= '<li class="'.($list_num > 0 ? 'divided ' : '').'column_item">';
						$list_num++;
						$item_output .= '<a href="'.$cat_val['term_link'].'">'.$cat_val['term_name'].'</a><ul>';
						foreach ($cat_val as $item_id => $item_data) {
							if($item_id == 'term_link' || $item_id == 'term_name') continue;
							$item_output .= '<li><a href="'.$item_data['item_link'].'">'.$item_data['item_title'].'</a></li>';
						}
						$item_output .= '</ul></li>';
					}
					$item_output .= '</ul></li></ul>';


				} else if ($view_type == 'thumb') {


					$thumb_size = getThumbSizes(array('thumb_size'=>'widgets'));
					$thumb_full_size = getThumbSizes(array('thumb_size'=>'image_large', 'thumb_crop'=>false));
					$curr_item = '';
					$item_output .= '<ul class="menu-panel thumb"><li><ul class="custom-menu-style thumb sub-menu">';
					foreach ($inner_output as $cat => $cat_val) {
						foreach ($cat_val as $item_id => $item_data) {
							if ($item_id == 'term_link' || $item_id == 'term_name') continue;
							$item_thumb = getResizedImageTag($item_id, $thumb_size['w'], $thumb_size['h']);
							$item_thumb_full = getResizedImageUrl($item_id, $thumb_full_size['w'], $thumb_full_size['h']);
							$author_id = get_post($item_id)->post_author;
							$author_data = get_userdata($author_id);
							$item_author = $author_data->display_name;
							$item_title = get_the_title($item_id);
							$item_date = get_the_time($df, $item_id);
							//$author_url =	get_author_posts_url($author_id);
							if (empty($curr_item)) {
								$curr_item = $item_id;
								$placeholder_thumb = getResizedImageTag($curr_item, $thumb_full_size['w'], $thumb_full_size['h']);
								$placeholder_title = $item_title;
								$placeholder_link = $item_link;
								$placeholder_date  = $item_date;
								$placeholder_author = $item_author;
								//$placeholder_author_url = $author_url;
							}
							$item_output .= '<li><a href="'.$item_data['item_link'].'"
								data-author="'.$item_author.'"
								data-pubdate="'.$item_date.'"
								data-thumb="'.$item_thumb_full.'"
								data-title="'.$item_title.'"
								data-link="'.$item_link.'"
								>'.($item_thumb != '' ? $item_thumb : '<span class="img_placeholder"></span>').'</a></li>';
						}
					}
					$item_output .= '</ul>';
					$item_output .= '<div class="item_placeholder">';
						
					// Placeholder section
					if ($curr_item != '') {
						$item_output .= '
							<div class="thumb_wrap">'.$placeholder_thumb.'</div>
							<h4 class="item_title"><a href="'.$placeholder_link.'">'.$placeholder_title.'</a></h4>
							<div class="item_info">
							<div class="item_pubdate"><span>'.__( 'Posted', 'themerex' ).'</span>&nbsp;<em>'.$placeholder_date.'</em></div>
							<div class="item_author"><span>'.__( 'by', 'themerex' ).'</span>&nbsp;<em>'.$placeholder_author.'</em></div>
							</div>
						';
					}
					$item_output .= '</div></li></ul>';
			
				} else if ($view_type == 'thumb_title') {

					$thumb_size = getThumbSizes(array('thumb_size'=>'portfolio4'));
					$item_output .= '<ul class="menu-panel thumb_title"><li><ul class="custom-menu-style thumb_title sub-menu">';
					foreach ($inner_output as $cat => $cat_val) {
						$curr_item = '';
						$item_output .= '<li class="items_column"><a href="'.$cat_val['term_link'].'">'.$cat_val['term_name'].'</a><ul>';
						foreach ($cat_val as $item_id => $item_data) {
							if($item_id == 'term_link' || $item_id == 'term_name') continue;
							$item_date = get_the_time($df, $item_id);
							$item_comments = wp_count_comments($item_id);
							$item_thumb = getResizedImageUrl($item_id, $thumb_size['w'], $thumb_size['h']);
							//$author_url =	get_author_posts_url($author_id);
							if (empty($curr_item)) {
								$curr_item = $item_id;
								$placeholder_thumb = getResizedImageTag($curr_item, $thumb_size['w'], $thumb_size['h']);
								$placeholder_date  = $item_date;
								$placeholder_comments = $item_comments->approved;
							}
							$item_output .= '<li>'
								. '<a href="'.$item_data['item_link'].'"
									data-pubdate="'.$item_date.'"
									data-comments="'.$item_comments->approved.'"
									data-thumb="'.$item_thumb.'"
									>'
									.$item_data['item_title']
								.'</a>'
								.'</li>';
						}         
						$item_output .= '</ul>';
						
						// Placeholder output
						$item_output .= '<div class="item_placeholder">';
						if ($curr_item != '') {
							$item_output .= '
								<div class="thumb_wrap">'.$placeholder_thumb.'</div>
								<div class="item_info">
								<div class="item_pubdate"><span>'.__( 'Posted', 'themerex' ).'</span>&nbsp;<em>'.$placeholder_date.'</em></div>
								<div class="item_comments"><span class="icon icon-comment-1"></span>&nbsp;<em>'.$placeholder_comments.'</em></div>
								</div>';
						}
						$item_output .= '</div></li>';
					}
					$item_output .= '</ul></li></ul>';
				}
			}
						
			$item_output .= $args->after;
		
		} else {			// Manual or default method
		
			if ($depth == 0) $this->top_menu_view = $item->top_menu_view;
			          
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$class_names = $value = '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			if ($depth == 0 && $item->top_menu_view != 'default') {
				$class_names .= ' ' . $item->top_menu_view . ' custom_view_item';
			}
			$class_names = ' class="'. esc_attr( $class_names ) . '"';
			$output .= $indent .($this->top_menu_view == 'thumb' && $depth > 1 || $this->top_menu_view == 'thumb_title' && $depth > 2  ? '</li>' : ''). '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
			
			$item_thumb = '';
			
			$thumb_size = getThumbSizes(array('thumb_size'=>'widgets'));
			$width = $thumb_size['w'];
			$height = $thumb_size['h'];
			
			$item_thumb = getResizedImageUrl($item->item_thumb, $width, $height);
			if (empty($item_thumb)) {
				if ($item->type != 'custom') {
					$item_id = $item->object_id;
					$item_thumb = getResizedImageUrl($item_id, $width, $height);
				}
			}
			
			$attributes  = ! empty( $item->attr_title ) ? ' title="'	. esc_attr( $item->attr_title ) .'"' : $description_title;
			$attributes .= ! empty( $item->target )		? ' target="' . esc_attr( $item->target ) .'"' : '';
			$attributes .= ! empty( $item->xfn )		? ' rel="'		. esc_attr( $item->xfn ) .'"' : '';
			$attributes .= ! empty( $item->url )		? ' href="'	 . esc_attr( $item->url ) .'"' : '';
			
			if ($this->top_menu_view == 'thumb' || $this->top_menu_view == 'thumb_title') {
				if ($depth > 0) {
					if ($item->object != 'custom') {
					
						$post_id = $item->object_id;
						$post_title = get_the_title($post_id);
						if (empty($post_title)) {
							$post_title = $item->title;
						}
						
						$thumb_size   = getThumbSizes(array('thumb_size'=>$this->top_menu_view == 'thumb_title' ? 'portfolio4' : 'image_large'));
						$thumb_width  = $thumb_size['w'];
						$thumb_height = $thumb_size['h'];
						
						$post_pubdate = get_the_time($df, $post_id);
						$author_id = get_post($post_id)->post_author;
						$author_data = get_userdata($author_id);
						$post_author = $author_data->display_name;
						$author_url = get_author_posts_url($author_id);
						$placeholder_thumb = getResizedImageUrl($post_id, $thumb_width, $thumb_height);
						if (empty($placeholder_thumb)) {
							$placeholder_thumb = getResizedImageUrl($item->item_thumb, $thumb_width, $thumb_height);
						}
						$post_comments = wp_count_comments($post_id);
						$comment_count = $post_comments->approved;

					} else {

						$placeholder_thumb = $item->item_thumb;
						$post_title = $item->title;

					}
					
					$attributes .= !empty( $post_title ) ? ' data-title="'.$post_title.'"' : $description_title;
					$attributes .= !empty( $post_author ) ? ' data-author="'.$post_author.'"' : '';
					$attributes .= !empty( $placeholder_thumb ) ? ' data-thumb="'.$placeholder_thumb.'"' : '';
					$attributes .= !empty( $post_pubdate ) ? ' data-pubdate="'.$post_pubdate.'"' : '';
					$attributes .= !empty( $comment_count ) ? ' data-comments="'.$comment_count.'"' : '';
					
				}
			}
			
			$icon_padding = '';
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			
			//if ($this->top_menu_view == 'columns' && $depth > 1) {
				if (!empty($item->item_icon_class)) {
					$item_output .= '<span class="menu_icon '.$item->item_icon_class.'"></span>';
				}
			//}
			
			if ($this->top_menu_view == 'thumb' && $depth > 0) {
				if ($item_thumb != '') {
					$item_output .= '<img src="'.$item_thumb.'" width="'.$width.'" height="'.$height.'" alt="">';
				}
			} else {
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
			}

			$item_output .= $description . $args->link_after . '</a>'; 
			
			if ($this->top_menu_view == 'thumb_title') {
				if ($depth == 1) {
					$this->level_holder = array();
				}
			}
			$item_output .= $args->after;
			$type_depth = 2;
			
			if ($this->top_menu_view == 'thumb') {
				$type_depth = 1;
			}
			
			if (empty($this->level_holder)) {
				if($depth == $type_depth) {
					$this->level_holder = $item;
				}
			} else if ($item->current == '1') {
				if ($depth >= $type_depth) {
					$this->level_holder = $item;
				}
			}
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
		
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if(!($this->top_menu_view == 'thumb' && $depth >= 2) && !($this->top_menu_view == 'thumb_title' && $depth >= 3) || $item->top_level_type == 'auto') {
			$output .= "</li>\n";
		}
	}
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		
		$parent = $this->item_data;
		$menu_view = $parent->top_menu_view;
		$show_ul = true;
		
		if ($menu_view == 'thumb') {
			if ($depth >= 1) {
				$show_ul = false;
			}
		}
		else if ($menu_view == 'thumb_title') {
			if ($depth >= 2) {
				$show_ul = false;
			}
		}
		if ($show_ul) {
			if ($depth == 0 && $menu_view != 'default') {
				$output .= '<ul class="menu-panel '.$menu_view.'"><li>';
			}
			$output .= "\n$indent<ul class=\"".($depth == 0 && $menu_view != 'default' ? 'custom-menu-style '.$menu_view. ' ' : '')."sub-menu\">\n";
		}
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		
		$parent = $this->item_data;
		$menu_view = $parent->top_menu_view;
		$show_ul = true;

		$df = get_option('date_format');
		
		$indent = str_repeat("\t", $depth);
		    	
		if ($menu_view == 'thumb') {
			if ($depth >= 1) {
				$show_ul = false;
			}
		} else if ($menu_view == 'thumb_title') {
			if ($depth >= 2) {
				$show_ul = false;
			}
		}
		
		if ($show_ul) {
			$output .= "$indent</ul>\n";
		}
		
		$type_depth = 1;
		
		if ($menu_view == 'thumb') {
			$type_depth = 0;
		}
		$item = $this->level_holder;
		if($depth == $type_depth) {
		
			/******* Making the placeholder ********/
			if(!empty($this->level_holder)) {
				
				$item_id = $this->level_holder->object_id;
				
				$top_item = $this->item_data;
				$item = $this->level_holder;
												
				$thumb_size = getThumbSizes(array('thumb_size'=>$this->top_menu_view == 'thumb_title' ? 'portfolio4' : 'image_large'));
				$width = $thumb_size['w'];
				$height = $thumb_size['h'];
				
				$item_thumb = getResizedImageUrl($item_id, $width, $height);
				
				if(empty($item_thumb)) {
					$item_thumb_url = $this->level_holder->item_thumb;
					$item_thumb = getResizedImageUrl($item_thumb_url, $width, $height);
				}
				$item_icon = $item->item_icon_class;
				if($item->object != 'custom') {
					$post_id = $item->object_id;
					
					$item_pubdate = get_the_time($df, $post_id);
					$post_obj = get_post($post_id);
					if (is_object($post_obj)) {
						$author_id = $post_obj->post_author;
						$author_data = get_userdata($author_id);
						$author_url = get_author_posts_url($author_id);
						$author_name = $author_data->display_name;
					} else
						$author_name = '';
					$post_comments = wp_count_comments($post_id);
					$comment_count = $post_comments->approved;
					$post_title = get_the_title($post_id);
					$post_link = $item->url;
					$vis_width = $width;
					$vis_height = $height;					
				}
				if ($top_item->top_menu_view == 'thumb_title') {			
					$output .= '<div class="item_placeholder">
						<div class="thumb_wrap"><img src="'.$item_thumb.'" alt="" width="'.$vis_width.'" height="'.$vis_height.'" /></div>
						<div class="item_info">
						<div class="item_pubdate"><span>'.__( 'Posted', 'themerex' ).'</span>&nbsp;<em>'.$item_pubdate.'</em></div>
						<div class="item_comments"><span class="icon icon-comment-1"></span>&nbsp;<em>'.$comment_count.'</em></div>
						</div>
						</div>';
				} else if ($top_item->top_menu_view == 'thumb') {
					$this->thumb_placeholder = '<div class="item_placeholder">
						<div class="thumb_wrap"><img src="'.$item_thumb.'" alt="" width="'.$vis_width.'" height="'.$vis_height.'" /></div>
						<h4 class="item_title"><a href="'.$post_link.'">'.$post_title.'</a></h4>
						<div class="item_info">
						<div class="item_pubdate"><span>'.__( 'Posted', 'themerex' ).'</span>&nbsp;<em>'.$item_pubdate.'</em></div>
						' . (!empty($author_name) ? '<div class="item_author"><span>'.__( 'by', 'themerex' ).'</span>&nbsp;<em>'.$author_name.'</em></div>' : '') . '
						</div>
						</div>';
				}
			}
		}	

		if(!empty($this->thumb_placeholder)) {
			if($depth == $type_depth) {
				$output .= $this->thumb_placeholder;
			}
		}	

		if($depth == 0 && $menu_view != 'default') {
			$output .= '</li></ul>';
		}
	}
}