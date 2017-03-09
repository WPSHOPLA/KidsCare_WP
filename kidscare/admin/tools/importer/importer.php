<?php
defined( 'ABSPATH' ) or die( 'Access denied' );

class TRX_Importer {

	// Theme specific settings
	var $options = array(
		'debug'				=> true,						// Enable debug output
		'enable_importer'	=> true,						// Show Importer section
		'enable_exporter'	=> true,						// Show Exporter section
		'data_type'			=> 'vc',						// Default dummy data type
		'file_with_content'	=> array(
			'no_vc'	=> 'dummy_data.xml',					// Name of file with demo content without VC wrappers
			'vc'	=> 'dummy_data_vc.xml'					// Name of file with demo content for Visual Composer
			),
		'file_with_options'	=> 'theme_options.txt',			// Name of file with theme options
		'file_with_postmeta'=> 'theme_postmeta.txt',		// Name of file with post meta
		'file_with_widgets'	=> 'widgets.txt',				// Name of file with widgets data
		'file_with_royal_sliders' => 'royal_sliders.txt',	// Name of file with sliders data
		'uploads_folder'	=> 'imports',					// Folder with images in demo data
		'upload_attachments'=> true,						// Upload attachments images
		'import_posts'		=> true,						// Import posts
		'import_to'			=> true,						// Import Theme Options
		'import_widgets'	=> true,						// Import widgets
		'import_sliders'	=> true,						// Import sliders
		'overwrite_content'	=> true,						// Overwrite existing content
		'show_on_front'		=> 'page',						// Reading settings
		'page_on_front'		=> 'Homepage (Babysitter)',		// Homepage title
		'page_for_posts'	=> 'Blog streampage',			// Blog streampage title
		'menus'				=> array(						// Menus locations and names
			'mainmenu'	=> 'Main menu',
			'panelmenu'	=> 'Panel menu'
		),
		'wooc_pages'		=> 	array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' => 'Featured Products', // 'Shop'
			'woocommerce_cart_page_id' => 'Cart',
			'woocommerce_checkout_page_id' => 'Checkout',
			'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' => 'Order Received',
			'woocommerce_myaccount_page_id' => 'My Account',
			'woocommerce_edit_address_page_id' => 'Edit My Address',
			'woocommerce_view_order_page_id' => 'View Order',
			'woocommerce_change_password_page_id' => 'Change Password',
			'woocommerce_logout_page_id' => 'Logout',
			'woocommerce_lost_password_page_id' => 'Lost Password'
		)
	);

	var $error    = '';
	var $success  = '';
	var $nonce    = '';
	var $export_options = '';
	var $export_postmeta = '';
	var $export_widgets = '';
	var $export_sliders = '';
	var $uploads_url = '';
	var $uploads_dir = '';
	var $import_log = '';
	var $import_last_id = 0;

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function TRX_Importer() {
		$this->nonce = wp_create_nonce(__FILE__);
		$uploads_info = wp_upload_dir();
		$this->uploads_dir = $uploads_info['basedir'];
		$this->uploads_url = $uploads_info['baseurl'];
		if ($this->options['debug']) define('IMPORT_DEBUG', true);
		$this->import_log = themerex_get_file_dir('/admin/tools/importer/importer.log');
		$this->import_last_id = (int) themerex_fgc($this->import_log);
		add_action('admin_menu', array($this, 'admin_menu_item'));
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	function admin_menu_item() {
		if ( current_user_can( 'manage_options' ) ) {
			// In this case menu item is add in admin menu 'Appearance'
			add_theme_page(__('Install Dummy Data', 'themerex'), __('Install Dummy Data', 'themerex'), 'edit_theme_options', 'trx_importer', array($this, 'build_page'));

			// In this case menu item is add in admin menu 'Tools'
			//add_management_page(__('Theme Demo', 'themerex'), __('Theme Demo', 'themerex'), 'manage_options', 'trx_importer', array($this, 'build_page'));
		}
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {
		
		do {
			if ( isset($_POST['importer_action']) ) {
				if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], __FILE__ ) ) {
					$this->error = __('Incorrect WP-nonce data! Operation canceled!', 'themerex');
					break;
				}
				if ($this->checkRequiredPlugins()) {
					$this->options['overwrite_content'] = $_POST['importer_action']=='overwrite';
					$this->options['data_type'] = $_POST['data_type']=='vc' ? 'vc' : 'no_vc';
					$this->options['upload_attachments'] = isset($_POST['importer_upload']);
					$this->options['import_posts'] = isset($_POST['importer_posts']);
					$this->options['import_to'] = isset($_POST['importer_to']);
					$this->options['import_widgets'] = isset($_POST['importer_widgets']);
					$this->options['import_sliders'] = isset($_POST['importer_sliders']);
					$this->import_last_id = (int) $_POST['last_id'];
					?>
					<div class="trx_importer_log">
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<div class="error">
							<p><?php _e('<b>Please, wait!</b> Data import can take a long time (sometimes more than 10 minutes) - please wait until the end of the procedure, do not navigate away from the page!', 'themerex'); ?></p>
						<p>&nbsp;</p>
							<p><?php _e('If this page don\'t load, and you still see this message, please refresh the page (press F5).', 'themerex'); ?></p>
							<p><?php _e('<b>Attention!</b> Do not refresh the page often - do it only if the loading indicators in the browser ceased to appear, but this message is still on the screen!', 'themerex'); ?></p>
						</div>
						<p>&nbsp;</p>
					<?php
					flush();
					$this->importer();
					?>
						<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery('.trx_importer_log').remove();
							});
						</script>
					</div>
					<?php
				}
			} else if ( isset($_POST['exporter_action']) ) {
				if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], __FILE__ ) ) {
					$this->error = __('Incorrect WP-nonce data! Operation canceled!', 'themerex');
					break;
				}
				$this->exporter();
			}
		} while (false);
		?>
		<div class="trx_importer">
			<div class="trx_importer_result">
				<?php if (!empty($this->error)) { ?>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<div class="error">
					<p><?php echo esc_html($this->error); ?></p>
				</div>
				<p>&nbsp;</p>
				<?php } ?>
				<?php if (!empty($this->success)) { ?>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<div class="updated">
					<p><?php echo esc_html($this->success); ?></p>
				</div>
				<p>&nbsp;</p>
				<?php } ?>
			</div>
	
			<?php if (empty($this->success) && $this->options['enable_importer']) { ?>
				<h2 class="trx_title"><?php _e('AxiomThemes Importer', 'themerex'); ?></h2>
				<p><b><?php _e('Attention! Important info:', 'themerex'); ?></b></p>
				<ol>
					<li><?php _e('Data import can take a long time (sometimes more than 10 minutes) - please wait until the end of the procedure, do not navigate away from the page.', 'themerex'); ?></li>
					<li><?php _e('We recommend that you select the first option to import (with the replacement of existing content) - so you get a complete copy of our demo site', 'themerex'); ?></li>
					<li><?php _e('We also encourage you to leave the enabled check box "Upload attachments" - to download the demo version of the images', 'themerex'); ?></li>
					<li><?php _e('Important! If the import is not finished (message "Congratulations! Import demo data finished successfull!" is not appear) - refresh page (press F5) or select menu "Appearance - Theme Demo" and repeat the import - click the "Import demo data"', 'themerex'); ?></li>
				</ol>

				<form id="trx_importer_form" action="#" method="post">

					<input type="hidden" value="<?php echo esc_attr($this->nonce); ?>" name="nonce" />
					<input type="hidden" value="0" name="last_id" />

					<p>
					<input type="radio" <?php echo ($this->options['overwrite_content'] ? 'checked="checked"' : ''); ?> value="overwrite" name="importer_action" id="importer_action_over" /><label for="importer_action_over"><?php _e('Overwrite existing content', 'themerex'); ?></label><br>
					<?php _e('In this case <b>all existing content will be erased</b>! But you get full copy of the our demo site <b>(recommended)</b>.', 'themerex'); ?>
					</p>

					<p>
					<input type="radio" <?php echo !$this->options['overwrite_content'] ? 'checked="checked"' : ''; ?> value="append" name="importer_action" id="importer_action_append" /><label for="importer_action_append"><?php _e('Append to existing content', 'themerex'); ?></label><br>
					<?php _e('In this case demo data append to the existing content! Warning! In many cases you do not have exact copy of the demo site.', 'themerex'); ?>
					</p>

					<p><b><?php _e('Select the data to import:', 'themerex'); ?></b></p>
					<p>
					<input type="radio" <?php echo ($this->options['data_type']=='vc' 	? 'checked="checked"' : ''); ?> value="vc" name="data_type" id="data_type_vc" /><label for="data_type_vc"><?php _e('Import data for edit in the Visual Composer', 'themerex'); ?></label><br>
					<input type="radio" <?php echo ($this->options['data_type']=='no_vc'	? 'checked="checked"' : ''); ?> value="no_vc" name="data_type" id="data_type_no_vc" /><label for="data_type_no_vc"><?php _e('Import data without Visual Composer wrappers', 'themerex'); ?></label>
					</p>
					<p>
					<input type="checkbox" <?php echo ($this->options['import_posts'] ? 'checked="checked"' : ''); ?> value="1" name="importer_posts" id="importer_posts" /> <label for="importer_posts"><?php _e('Import posts', 'themerex'); ?></label><br>
					<input type="checkbox" <?php echo ($this->options['upload_attachments'] ? 'checked="checked"' : ''); ?> value="1" name="importer_upload" id="importer_upload" /> <label for="importer_upload"><?php _e('Upload attachments', 'themerex'); ?></label>
					</p>
					<p>
					<input type="checkbox" <?php echo ($this->options['import_to'] ? 'checked="checked"' : ''); ?> value="1" name="importer_to" id="importer_to" /> <label for="importer_to"><?php _e('Import Theme Options', 'themerex'); ?></label><br>
					<input type="checkbox" <?php echo ($this->options['import_widgets'] ? 'checked="checked"' : ''); ?> value="1" name="importer_widgets" id="importer_widgets" /> <label for="importer_widgets"><?php _e('Import Widgets', 'themerex'); ?></label><br>
					<input type="checkbox" <?php echo ($this->options['import_sliders'] ? 'checked="checked"' : ''); ?> value="1" name="importer_sliders" id="importer_sliders" /> <label for="importer_sliders"><?php _e('Import Sliders', 'themerex'); ?></label>
					</p>

					<div class="trx_buttons">
						<input type="submit" value="<?php _e('Start Import', 'themerex'); ?>">
						<?php if ($this->import_last_id > 0) { ?>
						<input type="submit" value="<?php printf(__('Continue Import (from ID=%s)', 'themerex'), $this->import_last_id); ?>" onClick="this.form.last_id.value='<?php echo esc_attr($this->import_last_id); ?>'">
						<?php } ?>
					</div>

				</form>
			<?php } ?>

			<?php if (empty($this->success) && $this->options['enable_exporter']) { ?>
				<p>&nbsp;</p>
				<h2 class="trx_title"><?php _e('ThemeREX Exporter', 'themerex'); ?></h2>
				<form id="trx_exporter_form" action="#" method="post">

					<input type="hidden" value="<?php echo esc_attr($this->nonce); ?>" name="nonce" />
					<input type="hidden" value="all" name="exporter_action" />

					<div class="trx_buttons">
						<?php if ($this->export_options!='') { ?>
						<h4><?php _e('Theme options', 'themerex'); ?></h4>
						<textarea rows="10" cols="80"><?php echo esc_textarea($this->export_options); ?></textarea>
						<?php if (false) { ?>
						<h4><?php _e('Post meta', 'themerex'); ?></h4>
						<textarea rows="10" cols="80"><?php echo esc_textarea($this->export_postmeta); ?></textarea>
						<?php } ?>
						<h4><?php _e('Widgets', 'themerex'); ?></h4>
						<textarea rows="10" cols="80"><?php echo esc_textarea($this->export_widgets); ?></textarea>
						<?php } else { ?>
						<input type="submit" value="<?php _e('Export Theme Options', 'themerex'); ?>">
						<?php } ?>
					</div>

				</form>
			<?php } ?>
		</div>
		<?php
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export dummy data
	//-----------------------------------------------------------------------------------
	function exporter() {
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		// Export theme and categories options
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'themerex_options%'" );
		$options = array();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = unserialize($row->option_value);
			}
		}
		$this->export_options = base64_encode(serialize($options));

		// Export widgets
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name = 'sidebars_widgets' OR option_name LIKE 'widget_%'" );
		$options = array();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = unserialize($row->option_value);
			}
		}
		$this->export_widgets = base64_encode(serialize($options));

		// Export Royal Slider
		$options = array();
		$rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}new_royalsliders", ARRAY_A );
		$options['royal'] = $rows;
		$this->export_sliders = base64_encode(serialize($options));

		$wpdb->suppress_errors( $suppress );
	}
	
	
	//-----------------------------------------------------------------------------------
	// Import dummy data
	//-----------------------------------------------------------------------------------
	function importer() {
		// Increase time and memory limits
		set_time_limit(3600);

		// Load WP Importer class
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers
		if ( !class_exists('WP_Import') ) {
			require(themerex_get_file_dir('/admin/tools/importer/wordpress-importer.php'));
		}
	
		// If both classes present
		if ( class_exists( 'WP_Import' ) ) {

			if ($this->options['import_posts']) {
				$this->import_posts();
				//$this->import_postmeta();
				$this->setup_woocommerce_pages();
				$this->setup_menus();
			}
			//if ($this->options['import_to']) 		$this->import_theme_options();
			if ($this->options['import_to']) {
				themerex_options_reset();
				$this->import_theme_options();
			}
			if ($this->options['import_widgets'])	$this->import_widgets();
			if ($this->options['import_sliders'])	$this->import_sliders();

			if ($this->options['import_posts']) {
				// Set reading options
				$home_page = get_page_by_title( $this->options['page_on_front'] );
				$posts_page = get_page_by_title( $this->options['page_for_posts'] );
				if ($home_page->ID && $posts_page->ID) {
					update_option('show_on_front', $this->options['show_on_front']);
					update_option('page_on_front', $home_page->ID); 	// Front Page
					update_option('page_for_posts', $posts_page->ID);	// Blog Page
				}

				// Flush rules after install
				flush_rewrite_rules();
			}

			// finally redirect to success page
			$this->success = __('Congratulations! Import demo data finished successfull!', 'themerex');
		}
	}
	
	//==========================================================================================
	// Utilities
	//==========================================================================================

	// Check for required plugings
	function checkRequiredPlugins() {
		$not_installed = '';
		if ($_POST['data_type']=='vc' && !class_exists('Vc_Manager') )
			$not_installed .= '<br>Visual Composer plugin';
		if ( !class_exists('Woocommerce') )
			$not_installed .= '<br>WooCommerce plugin';
		if (!revslider_exists())
			$not_installed .= '<br>Revolution Slider plugin';
		if (!function_exists('is_plugin_inactive')) require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		if (is_plugin_inactive('wp-instagram-widget/wp-instagram-widget.php'))
			$not_installed .= '<br>Instagram Widget plugin';
		if ($not_installed) {
			$this->error = '<b>'.__('Attention! For correct installation of the demo data, you must install and activate the following plugins: ', 'themerex').'</b>'.$not_installed;
			$this->options['enable_importer'] = false;
			return false;
		}
		return true;
	}


	// Import XML file with posts data
	function import_posts() {
		if (empty($this->options['file_with_content'][$this->options['data_type']])) return;
		echo '<br><b>'.__('Import Posts (pages, menus, attachments, etc) ...', 'themerex').'</b><br>'; flush();
		$theme_xml = themerex_get_file_dir('/admin/tools/importer/data/' . $this->options['file_with_content'][$this->options['data_type']]);
		$importer = new WP_Import();
		$importer->fetch_attachments = $this->options['upload_attachments'];
		$importer->overwrite = $this->options['overwrite_content'];
		$importer->debug = $this->options['debug'];
		$importer->uploads_folder = $this->options['uploads_folder'];
		$importer->start_from_id = $this->import_last_id;
		$importer->import_log = $this->import_log;
		if ($this->import_last_id == 0) $this->clear_tables();
		$this->register_taxonomies();
		if (!$this->options['debug']) ob_start();
		$importer->import($theme_xml);
		if (!$this->options['debug']) ob_end_clean();
		themerex_fpc($this->import_log, '');
	}
	
	
	// Delete all data from tables
	function clear_tables() {
		global $wpdb;
		if ($this->options['overwrite_content']) {
			echo '<br><b>'.__('Clear tables ...', 'themerex').'</b><br>'; flush();
			if ($this->options['import_posts']) {
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->comments}");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "comments".', 'themerex' ) . ' ' . $res->get_error_message() . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->commentmeta}");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "commentmeta".', 'themerex' ) . ' ' . $res->get_error_message() . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->postmeta}");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "postmeta".', 'themerex' ) . ' ' . $res->get_error_message() . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->posts}");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "posts".', 'themerex' ) . ' ' . $res->get_error_message() . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->terms}");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "terms".', 'themerex' ) . ' ' . $res->get_error_message() . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->term_relationships}");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "term_relationships".', 'themerex' ) . ' ' . $res->get_error_message() . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE {$wpdb->term_taxonomy}");
				if ( is_wp_error( $res ) ) echo __( 'Failed truncate table "term_taxonomy".', 'themerex' ) . ' ' . $res->get_error_message() . '<br />';
			}

		}
	}

	
	// Register additional tax
	function register_taxonomies() {
		if (isset($this->options['taxonomies']) && is_array($this->options['taxonomies']) && count($this->options['taxonomies']) > 0) {
			foreach ($this->options['taxonomies'] as $type=>$tax) {
				register_taxonomy( $tax, $type, array(
					'hierarchical' => false,
					'query_var' => $tax,
					'rewrite' => true,
					'public' => false,
					'show_ui' => false,
					'show_admin_column' => false,
					'_builtin' => false
				) );
			}
		}
	}
	

	// Set WooCommerce pages
	function setup_woocommerce_pages() {
		foreach ($this->options['wooc_pages'] as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}


	// Set imported menus to registered theme locations
	function setup_menus() {
		echo '<br><b>'.__('Setup menus ...', 'themerex').'</b><br>'; flush();
		$locations = get_theme_mod( 'nav_menu_locations' );
		$menus = wp_get_nav_menus();
		if ($menus) {
			foreach ($menus as $menu) {
				foreach ($this->options['menus'] as $loc=>$name) {
					if ($menu->name == $name)
						$locations[$loc] = $menu->term_id;
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}


	// Import theme options
	function import_theme_options() {
		if (empty($this->options['file_with_options'])) return;
		echo '<br><b>'.__('Import Theme Options ...', 'themerex').'</b><br>'; flush();
		$theme_options_txt = themerex_fgc(themerex_get_file_dir('/admin/tools/importer/data/' . $this->options['file_with_options']));
		$data = unserialize( base64_decode( $theme_options_txt) );
		// Replace upload url in options
		foreach ($data as $k=>$v) {
			foreach ($v as $k1=>$v1) {
				$v[$k1] = $this->replace_uploads($v1);
			}
			update_option( $k, $v );
		}
		load_theme_options();
	}


	// Import post meta options
	function import_postmeta() {
		if (empty($this->options['file_with_postmeta'])) return;
		$theme_options_txt = themerex_fgc(themerex_get_file_dir('/admin/tools/importer/data/' . $this->options['file_with_postmeta']));
		$data = unserialize( base64_decode( $theme_options_txt) );
		// Replace upload url in options
		foreach ($data as $k=>$v) {
			foreach ($v as $k1=>$v1) {
				$v[$k1] = $this->replace_uploads($v1);
			}
			update_post_meta( $k, $v['key'], $v['value'] );
		}
	}

	
	// Replace uploads dir to new url
	function replace_uploads($str) {
		if (is_array($str)) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->replace_uploads($v);
			}
		} else if (is_string($str)) {
			while (($pos = themerex_strpos($str, "/{$this->options['uploads_folder']}/"))!==false) {
				$pos0 = $pos;
				while ($pos0) {
					if (themerex_substr($str, $pos0, 5)=='http:')
						break;
					$pos0--;
				}
				$str = ($pos0 > 0 ? themerex_substr($str, 0, $pos0) : '') . $this->uploads_url . themerex_substr($str, $pos+themerex_strlen($this->options['uploads_folder'])+1);
			}
		}
		return $str;
	}


	// Import widgets
	function import_widgets() {
		
		if (empty($this->options['file_with_widgets'])) return;

		echo '<br><b>'.__('Import Widgets ...', 'themerex').'</b><br>'; flush();

		// Register custom widgets
		$widgets = array();
		$sidebars = get_theme_option('custom_sidebars');
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$widgets['custom-sidebar-'.$i]  = $sb;
			}
		}
		themerex_widgets_init($widgets);
		
		// Import widgets
		$widgets_txt = themerex_fgc(themerex_get_file_dir('/admin/tools/importer/data/' . $this->options['file_with_widgets']));
		$data = unserialize( base64_decode( $widgets_txt ) );
		// Replace upload url in options
		foreach ($data as $k=>$v) {
			foreach ($v as $k1=>$v1) {
				if (is_array($v1)) {
					foreach ($v1 as $k2=>$v2) {
						if (is_array($v2)) {
							foreach ($v2 as $k3=>$v3) {
								$v2[$k3] = $this->replace_uploads($v3);
							}
							$v1[$k2] = $v2;
						} else
							$v1[$k2] = $this->replace_uploads($v2);
					}
					$v[$k1] = $v1;
				} else
					$v[$k1] = $this->replace_uploads($v1);
			}
			update_option( $k, $v );
		}
	}


	// Import sliders
	function import_sliders() {

		// Revolution Sliders
		if (file_exists(WP_PLUGIN_DIR.'/revslider/revslider.php')) {
			require_once(WP_PLUGIN_DIR.'/revslider/revslider.php');
			$dir = get_template_directory().'/admin/tools/importer/data/revslider';
			if ( is_dir($dir) ) {
				$hdir = @opendir( $dir );
				if ( $hdir ) {
					echo '<br><b>'.__('Import Revolution sliders ...', 'themerex').'</b><br>'; flush();
					$slider = new RevSlider();
					while (($file = readdir( $hdir ) ) !== false ) {
						$pi = pathinfo( $dir . '/' . $file );
						if ( substr($file, 0, 1) == '.' || is_dir( $dir . '/' . $file ) || $pi['extension']!='zip' )
							continue;
if ($this->debug) printf(__('Slider "%s":', 'themerex'), $file);
						if (!is_array($_FILES)) $_FILES = array();
						$_FILES["import_file"] = array("tmp_name" => $dir . '/' . $file);
						$response = $slider->importSliderFromPost();
						if ($response["success"] == false) { 
if ($this->debug) echo ' '.__('imported', 'themerex').'<br>';
						} else {
if ($this->debug) echo ' '.__('import error', 'themerex').'<br>';
						}
						flush();
					}
					@closedir( $hdir );
				}
			}
		} else {
			if ($this->debug) { printf(__('Can not locate Revo plugin: %s', 'themerex'), WP_PLUGIN_DIR.'/revslider/revslider.php<br>'); flush(); }
		}
	}
}

if (is_admin() && current_user_can('import') && get_theme_option('admin_dummy_data')=='yes') {
	$trx_importer = new TRX_Importer();
}
?>