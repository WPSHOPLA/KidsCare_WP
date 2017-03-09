<?php
/**
 * Send email to subscribers from selected group
 */

class TRX_Emailer {

	var $subscribers  = array();
	var $error    = '';
	var $success  = '';
	var $nonce    = '';
	var $max_recipients_in_one_letter = 50;

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function TRX_Emailer() {
		// Setup actions handlers
		add_action('admin_menu', array($this, 'admin_menu_item'));
		add_action("admin_enqueue_scripts", array($this, 'load_scripts'));
		add_action("admin_head", array($this, 'prepare_js'));

		// Init properties
		$this->subscribers = themerex_emailer_group_getlist();
		$this->nonce = wp_create_nonce(__FILE__);
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	function admin_menu_item() {
		// In this case menu item is add in admin menu 'Appearance'
		add_theme_page(__('Emailer', 'themerex'), __('Emailer', 'themerex'), 'edit_theme_options', 'trx_emailer', array($this, 'build_page'));

		// In this case menu item is add in admin menu 'Tools'
		//add_management_page(__('Emailer', 'themerex'), __('Emailer', 'themerex'), 'manage_options', 'trx_emailer', array($this, 'build_page'));
	}


	//-----------------------------------------------------------------------------------
	// Load required styles and scripts
	//-----------------------------------------------------------------------------------
	function load_scripts() {
		if (isset($_REQUEST['page']) && $_REQUEST['page']=='trx_emailer') {
			themerex_enqueue_style('trx-emailer-style', themerex_get_file_url('/admin/tools/emailer/emailer.css'), array(), null);
		}
		if (isset($_REQUEST['page']) && $_REQUEST['page']=='trx_emailer') {
			themerex_enqueue_script('jquery-ui-core', false, array('jquery'), null, true);
			themerex_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
			themerex_enqueue_script('trx-emailer-script', themerex_get_file_url('/admin/tools/emailer/emailer.js'), array('jquery'), null, true);
		}
	}
	
	
	//-----------------------------------------------------------------------------------
	// Prepare javascripts global variables
	//-----------------------------------------------------------------------------------
	function prepare_js() { 
	?>
		<script type="text/javascript">
			var THEMEREX_EMAILER_ajax_nonce = "<?php echo wp_create_nonce('ajax_nonce'); ?>";
			var THEMEREX_EMAILER_ajax_url   = "<?php echo admin_url('admin-ajax.php'); ?>";
		</script>
	<?php 
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {
		
		$subject = $message = $attach = $group = $sender_name = $sender_email = '';
		$subscribers_update = $subscribers_delete = $subscribers_clear = false;
		$subscribers = array();
		if ( isset($_POST['emailer_subject']) ) {
			do {
				// Check nonce
				if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], __FILE__ ) ) {
					$this->error = __('Incorrect WP-nonce data! Operation canceled!', 'themerex');
					break;
				}
				// Get post data
				$subject = isset($_POST['emailer_subject']) ? $_POST['emailer_subject'] : '';
				if (empty($subject)) {
					$this->error = __('Subject can not be empty! Operation canceled!', 'themerex');
					break;
				}
				$message = isset($_POST['emailer_message']) ? $_POST['emailer_message'] : '';
				if (empty($message)) {
					$this->error = __('Message can not be empty! Operation canceled!', 'themerex');
					break;
				}
				$attach  = isset($_FILES['emailer_attachment']['tmp_name']) && file_exists($_FILES['emailer_attachment']['tmp_name']) ? $_FILES['emailer_attachment']['tmp_name'] : '';
				$group   = isset($_POST['emailer_group']) ? $_POST['emailer_group'] : '';
				$subscribers = isset($_POST['emailer_subscribers']) ? $_POST['emailer_subscribers'] : '';
				if (!empty($subscribers))
					$subscribers = explode("\n", str_replace(array(';', ','), array("\n", "\n"), $subscribers));
				else
					$subscribers = array();
				if (count($subscribers)==0) {
					$this->error = __('Subscribers lists are empty! Operation canceled!', 'themerex');
					break;
				}
				$sender_name = !empty($_POST['emailer_sender_name']) ? $_POST['emailer_sender_name'] : get_bloginfo('name');
				$sender_email = !empty($_POST['emailer_sender_email']) ? $_POST['emailer_sender_email'] : '';
				if (empty($sender_email)) $sender_email = get_theme_option('contact_email');
				if (empty($sender_email)) $sender_email = get_bloginfo('admin_email');
				if (empty($sender_email)) {
					$this->error = __('Sender email is empty! Operation canceled!', 'themerex');
					break;
				}
				$headers = 'From: ' . $sender_name.' <' . $sender_email . '>' . "\r\n";
				$subscribers_update = isset($_POST['emailer_subscribers_update']);
				$subscribers_delete = isset($_POST['emailer_subscribers_delete']);
				$subscribers_clear  = isset($_POST['emailer_subscribers_clear']);

				// Send email
				add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
				$new_list = array();
				$list = array();
				$cnt = 0;
				$mail = get_theme_option('mail_function');
				foreach ($subscribers as $email) {
					$email = trim(chop($email));
					if (empty($email)) continue;
					if (!preg_match('/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\ .A-Za-z0-9]{2,}/', $email)) continue;
					$list[] = $email;
					$cnt++;
					if ($cnt >= $this->max_recipients_in_one_letter) {
						@$mail( $list, $subject, $message, $headers, $attach );
						if ($subscribers_update && $group!='none') $new_list = array_merge($new_list, $list);
						$list = array();
						$cnt = 0;
					}
				}
				if ($cnt > 0) {
					@$mail( $list, $subject, $message, $headers, $attach );
					if ($subscribers_update && $group!='none') $new_list = array_merge($new_list, $list);
					$list = array();
					$cnt = 0;
				}
				remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
				$add_msg = '';
				if ($subscribers_update && $group!='none') {
					$rez = array();
					if (count($this->subscribers[$group]) > 0) {
						foreach ($this->subscribers[$group] as $k=>$v) {
							if (!$subscribers_clear && !empty($v))
								$rez[$k] = $v;
						}
					}
					if (count($new_list) > 0) {
						foreach ($new_list as $v) {
							$rez[$v] = '';
						}
					}
					$this->subscribers[$group] = $rez;
					update_option('emailer_subscribers', $this->subscribers);
					$add_msg = __(' The subscriber list is updated', 'themerex');
				} else if ($subscribers_delete && $group!='none') {
					unset($this->subscribers[$group]);
					update_option('emailer_subscribers', $this->subscribers);
					$add_msg = __(' The subscriber list is cleared', 'themerex');
				}
				$this->success = __('E-Mail was send successfull!', 'themerex') . $add_msg;
			} while (false);
		}

		?>
		<div class="trx_emailer">
			<h2 class="trx_emailer_title"><?php _e('ThemeREX Emailer', 'themerex'); ?></h2>
			<div class="trx_emailer_result">
				<?php if (!empty($this->error)) { ?>
				<div class="error">
					<p><?php echo balanceTags($this->error); ?></p>
				</div>
				<?php } ?>
				<?php if (!empty($this->success)) { ?>
				<div class="updated">
					<p><?php echo balanceTags($this->success); ?></p>
				</div>
				<?php } ?>
			</div>
	
			<form id="trx_emailer_form" action="#" method="post" enctype="multipart/form-data">

				<input type="hidden" value="<?php echo esc_attr($this->nonce); ?>" name="nonce" />

				<div class="trx_emailer_block">
					<fieldset class="trx_emailer_block_inner">
						<legend> <?php _e('Letter data', 'themerex'); ?> </legend>
						<div class="trx_emailer_fields">
							<div class="trx_emailer_field trx_emailer_subject">
								<label for="emailer_subject"><?php _e('Subject:', 'themerex'); ?></label>
								<input type="text" value="<?php echo esc_attr($subject); ?>" name="emailer_subject" id="emailer_subject" />
							</div>
							<div class="trx_emailer_field trx_emailer_attachment">
								<label for="emailer_attachment"><?php _e('Attachment:', 'themerex'); ?></label>
								<input type="file" name="emailer_attachment" id="emailer_attachment" />
							</div>
							<div class="trx_emailer_field trx_emailer_message">
								<?php
								wp_editor( $message, 'emailer_message', array(
									'wpautop' => false,
									'textarea_rows' => 10
								));
								?>								
							</div>
						</div>
					</fieldset>
				</div>
	
				<div class="trx_emailer_block">
					<fieldset class="trx_emailer_block_inner">
						<legend> <?php _e('Subscribers', 'themerex'); ?> </legend>
						<div class="trx_emailer_fields">
							<div class="trx_emailer_field trx_emailer_group">
								<label for="emailer_group"><?php _e('Select group:', 'themerex'); ?></label>
								<select name="emailer_group" id="emailer_group">
									<option value="none"<?php echo esc_attr($group=='none' ? ' selected="selected"' : ''); ?>><?php _e('- Select group -', 'themerex'); ?></option>
									<?php
									if (count($this->subscribers) > 0) {
										foreach ($this->subscribers as $gr=>$list) {
											echo '<option value="'.$gr.'"'.($group==$gr ? ' selected="selected"' : '').'>'.themerex_strtoproper($gr).'</option>';
										}
									}
									?>
								</select>
								<input type="checkbox" name="emailer_subscribers_update" id="emailer_subscribers_update" value="1"<?php echo esc_attr($subscribers_update ? ' checked="checked"' : ''); ?> /><label for="emailer_subscribers_update" class="inline" title="<?php _e('Update the subscribers list for selected group', 'themerex'); ?>"><?php _e('Update', 'themerex'); ?></label>
								<input type="checkbox" name="emailer_subscribers_clear" id="emailer_subscribers_clear" value="1"<?php echo esc_attr($subscribers_clear ? ' checked="checked"' : ''); ?> /><label for="emailer_subscribers_clear" class="inline" title="<?php _e('Clear this group from not confirmed emails after send', 'themerex'); ?>"><?php _e('Clear', 'themerex'); ?></label>
								<input type="checkbox" name="emailer_subscribers_delete" id="emailer_subscribers_delete" value="1"<?php echo esc_attr($subscribers_delete ? ' checked="checked"' : ''); ?> /><label for="emailer_subscribers_delete" class="inline" title="<?php _e('Delete this group after send', 'themerex'); ?>"><?php _e('Delete', 'themerex'); ?></label>
							</div>
							<div class="trx_emailer_field trx_emailer_subscribers2">
								<label for="emailer_subscribers" class="big"><?php _e('List of recipients:', 'themerex'); ?></label>
								<textarea name="emailer_subscribers" id="emailer_subscribers"><?php echo join("\n", $subscribers); ?></textarea>
							</div>
							<div class="trx_emailer_field trx_emailer_sender_name">
								<label for="emailer_sender_name"><?php _e('Sender name:', 'themerex'); ?></label>
								<input type="text" name="emailer_sender_name" id="emailer_sender_name" value="<?php echo esc_attr($sender_name); ?>" /><br />
							</div>
							<div class="trx_emailer_field trx_emailer_sender_email">
								<label for="emailer_sender_email"><?php _e('Sender email:', 'themerex'); ?></label>
								<input type="text" name="emailer_sender_email" id="emailer_sender_email" value="<?php echo esc_attr($sender_email); ?>" />
							</div>
						</div>
					</fieldset>
				</div>
	
				<div class="trx_emailer_buttons">
					<a href="#" id="trx_emailer_send"><?php echo _e('Send', 'themerex'); ?></a>
				</div>
	
			</form>
		</div>
		<?php
	}
	
	
	//==========================================================================================
	// Utilities
	//==========================================================================================

	// Set email content type
	function set_html_content_type() {
		return 'text/html';
	}
}

if (is_admin() && get_theme_option('admin_emailer')=='yes') {
	$trx_emailer = new TRX_Emailer();
}
?>