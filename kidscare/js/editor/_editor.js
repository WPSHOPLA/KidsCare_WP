jQuery(document).ready(function () {
	"use strict";

	// Open frontend editor
	jQuery('#frontend_editor_icon_edit').click(function (e) {
		"use strict";
		if (jQuery('#frontend_editor_overflow').length == 0) {
			jQuery('body').append('<div id="frontend_editor_overflow" class="frontend_editor_overflow"></div>')
		}
		jQuery('#frontend_editor_overflow').fadeIn(400);
		jQuery('#frontend_editor_button_cancel').val(THEMEREX_EDITOR_caption_cancel);
		jQuery('#frontend_editor').slideDown();
		e.preventDefault();
		return false;
	});

	//Close frontend editor
	jQuery('#frontend_editor_button_cancel').click(function (e) {
		"use strict";
		if (jQuery(this).val() == THEMEREX_EDITOR_caption_close)
			window.location.reload();
		else {
			jQuery('#frontend_editor').slideUp();
			jQuery('#frontend_editor_overflow').fadeOut(400);
		}
		e.preventDefault();
		return false;
	});

	// Save post
	jQuery('#frontend_editor_button_save').click(function (e) {
		"use strict";
		// Save editors content
		var editor = typeof(tinymce) != 'undefined' ? tinymce.activeEditor : false;
		if ( 'mce_fullscreen' == editor.id )
			tinymce.get('content').setContent(editor.getContent({format : 'raw'}), {format : 'raw'});
		tinymce.triggerSave();
		// Prepare data
		var data = {
			action: 'frontend_editor_save',
			nonce: THEMEREX_EDITOR_ajax_nonce,
			data: jQuery("#frontend_editor form").serialize()
		};
		jQuery.post(THEMEREX_EDITOR_ajax_url, data, function(response) {
			"use strict";
			var rez = JSON.parse(response);
			if (rez.error == '') {
				themerex_message_success('', THEMEREX_SAVE_SUCCESS);
				jQuery('#frontend_editor_button_cancel').val(THEMEREX_EDITOR_caption_close);
			} else {
				themerex_message_warning(rez.error, THEMEREX_SAVE_ERROR);
			}
		});
		e.preventDefault();
		return false;
	});

	// Delete post
	//----------------------------------------------------------------
	jQuery('#frontend_editor_icon_delete').click(function (e) {
		"use strict";
		themerex_message_confirm(THEMEREX_DELETE_POST_MESSAGE, THEMEREX_DELETE_POST, function(btn) {
			"use strict";
			if (btn != 1) return;
			//themerex_message_info('', THEMEREX_OPTIONS_STRINGS_wait);
			var data = {
				action: 'frontend_editor_delete',
				post_id: jQuery("#frontend_editor form #frontend_editor_post_id").val(),
				nonce: THEMEREX_EDITOR_ajax_nonce
			};
			jQuery.post(THEMEREX_EDITOR_ajax_url, data, function(response) {
				"use strict";
				var rez = JSON.parse(response);
				if (rez.error == '') {
					themerex_message_success('', THEMEREX_DELETE_SUCCESS);
					setTimeout(function() { 
						window.location.href = THEMEREX_site_url;
						}, 1000);
				} else {
					themerex_message_warning(rez.error, THEMEREX_DELETE_ERROR);
				}
			});
			
		});
		e.preventDefault();
		return false;
	});

});