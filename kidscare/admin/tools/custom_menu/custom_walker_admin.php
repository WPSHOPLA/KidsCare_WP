<?php
/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {

	var $list_show = 0;
	
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl(&$output, $depth=0, $args=array()) {	
	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth=0, $args=array()) {
	}
	
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */

	function start_el(&$output, $item, $depth=0, $args=array(), $current_object_id=0) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		
	    ob_start();
	    
	    if (!$this->list_show++) {	    
			$icons_array = getIconsList(); 	
			echo '<div id="fontello_box" class="fontello_box"><ul>';
			foreach ($icons_array as $icon) { 
				echo '<li><span class="'.$icon.'"></span></li>';
			}
			echo '</ul></div>';
		}
	    
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)', 'themerex' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)', 'themerex'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;
	
	    ?>
	    <li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item', 'themerex' ); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
	                        <?php _e( 'URL', 'themerex' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Navigation Label', 'themerex' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Title Attribute', 'themerex' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php _e( 'Open link in a new window/tab', 'themerex' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'CSS Classes (optional)', 'themerex' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Link Relationship (XFN)', 'themerex' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Description', 'themerex' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'themerex'); ?></span>
	                </label>
	            </p>        

				<?php if ($depth == 0) { ?>
					<!-- Choose type of menu: auto or manual; -->
					<div class="submenu_type">
						<div class="description">
							<label for="top_level_type[<?php echo esc_attr($item_id); ?>]"><?php echo __( 'Choose menu type', 'themerex' ); ?></label>
							<select name="top_level_type[<?php echo esc_attr($item_id); ?>]" id="top_level_type[<?php echo esc_attr($item_id); ?>]" class="menu_type_select">
								<option value=""><?php echo __( 'Select an option', 'themerex' ); ?></option>
								<option value="auto" <?php if($item->top_level_type == 'auto') echo 'selected="selected"'; ?>><?php echo __( 'Auto', 'themerex' ); ?></option>
								<option value="manual" <?php if($item->top_level_type == 'manual') echo 'selected="selected"'; ?>><?php echo __( 'Manual', 'themerex' ); ?></option>
							</select>
							<!-- Auto options -->
							<p class="auto_options_panel">
								<label for="auto_item_count<?php echo esc_attr($item_id); ?>"><?php echo __( 'Number of items to show', 'themerex' ); ?>
									<input type="text" value="<?php echo esc_attr($item->auto_items_count ? $item->auto_items_count : 3); ?>" name="auto_items_count[<?php echo esc_attr($item_id); ?>]" size="4" id="auto_items_count<?php echo esc_attr($item_id); ?>" />
								</label>
								<span class="post_types_list" style="display:block;">
								<?php
									$post_types = $this->tr_post_types_list();
									foreach ($post_types as $type) {
										echo '<input
										type="checkbox"
										value="'.$type.'"
										name="'.$type.'"
										'.(strpos($item->post_types_list, $type) !== false ? 'checked="checked"' : '').'
										id="'.$type.$item_id.'" />
										
										<label for="'.$type.$item_id.'">'.$type.'</label>';
									}
								?>
								</span>
								<input type="hidden" value="<?php echo esc_attr($item->post_types_list ? $item->post_types_list : ''); ?>" id="post_types_list<?php echo esc_attr($item_id); ?>" name="post_types_list[<?php echo esc_attr($item_id); ?>]" />
								<label for="item_sorting_by[<?php echo esc_attr($item_id); ?>]" class="sorting_label">
									<?php echo __( 'Sort items by:', 'themerex' ); ?>
									<select name="item_sorting_by[<?php echo esc_attr($item_id); ?>]" id="item_sorting_by<?php echo esc_attr($item_id); ?>">
										<option value="date" <?php if($item->item_sorting_by == 'date') echo 'selected="selected"'; ?>><?php echo __( 'by date', 'themerex' ); ?></option>
										<option value="title" <?php if($item->item_sorting_by == 'title') echo 'selected="selected"'; ?>><?php echo __( 'by title', 'themerex' ); ?></option>
										<option value="rand" <?php if($item->item_sorting_by == 'rand') echo 'selected="selected"'; ?>><?php echo __( 'random', 'themerex' ); ?></option>
									</select>
								</label>
								<label for="cat_list<?php echo esc_attr($item_id); ?>" class="menu_cat_list">
									<em><?php echo __( 'Enter list of categories, separated by commas', 'themerex' ); ?></em>
									<input type="text" value="<?php echo esc_attr($item->categories); ?>" placeholder="<?php echo __( 'List of categories', 'themerex' ); ?>" name="cat_list[<?php echo esc_attr($item_id); ?>]" id="cat_list<?php echo esc_attr($item_id); ?>" />
								</label>
							</p>
							<!-- /Auto options -->
						</div>
					</div>
					
					<!-- Choose style of menu; -->
					<div class="submenu_view">
						<div class="description">
							<label for="top_menu_view[<?php echo esc_attr($item_id); ?>]"><?php echo __( 'Choose menu style','themerex' ); ?></label>
							<select name="top_menu_view[<?php echo esc_attr($item_id); ?>]" id="top_menu_view[<?php echo esc_attr($item_id); ?>]">
								<option value="default" <?php if($item->top_menu_view == 'default') echo 'selected="selected"'; ?>><?php echo __( 'Default', 'themerex' ); ?></option>
								<option value="columns" <?php if($item->top_menu_view == 'columns') echo 'selected="selected"'; ?>><?php echo __( 'Columns', 'themerex' ); ?></option>
								<option value="thumb" <?php if($item->top_menu_view == 'thumb') echo 'selected="selected"'; ?>><?php echo __( 'Page thumbnail', 'themerex' ); ?></option>
								<option value="thumb_title" <?php if($item->top_menu_view == 'thumb_title') echo 'selected="selected"'; ?>><?php echo __( 'Page thumbnail with title', 'themerex' ); ?></option>
							</select>
						</div>
					</div>
	            		
	            <?php } ?>
	            
					<div class="item_icon">
						<div class="description">
							<label for="item_icon_class"><?php _e('Choose item icon', 'themerex'); ?></label>
							<input type="text" placeholder="<?php echo __( 'Select icon for this item', 'themerex' ); ?>" name="item_icon_class[<?php echo esc_attr($item_id); ?>]" id="item_icon_class<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr($item->item_icon_class); ?>" class="item_icon_select" />
							<span class="icon_holder"></span>
						</div>
					</div>
					<div class="image_add_row">
						<label><?php _e('Choose item image', 'themerex'); ?></label>
						<?php
							wp_enqueue_media();
							echo '<a class="button mediamanager" href="#"
							data-choose="'.__('Choose an image for the menu item', 'themerex').'"
							data-linked-field="item_thumb_holder'.$item_id.'"
							onclick="showMediaManager(this);"
							style="margin-bottom: 10px;">' . __( 'Choose Image', 'themerex') . '</a>';	            
						?>
						<a class="button mediamanager_reset" href="#"><?php _e('Remove image', 'themerex'); ?></a>
						<input class="item_thumb" id="item_thumb_holder<?php echo esc_attr($item_id); ?>" name="item_thumb_holder[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->item_thumb) ?>" type="hidden" />
						<div class="item_img"><?php echo !empty($item->item_thumb) ? '<img src="'.$item->item_thumb.'" alt="">' : ''; ?></div>
					</div>                  
				
				<?php //} ?>
				
					            
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s', 'themerex'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php _e('Remove', 'themerex'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php _e('Cancel', 'themerex'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

    }
	 
	function tr_post_types_list() {
		$all_post_types = get_post_types();
		$post_types = array();
		
		foreach ($all_post_types as $post_type) {
			if(!in_array($post_type, array('attachment', 'revision', 'nav_menu_item', 'product_variation', 'shop_order', 'shop_coupon', 'parallax'))) {
				$post_types[] = $post_type;
			}
		}
		
		return $post_types;
	}

}