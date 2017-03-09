/* global jQuery */
jQuery(document).ready(function () {
	"use strict";
	
	// Change group
	jQuery('#emailer_group').change(function() {
		var group = jQuery(this).val();
		if (group=='none') {
			jQuery('#emailer_subscribers_update').get(0).checked = false;
			jQuery('#emailer_subscribers_update').get(0).disabled = true;
			jQuery('#emailer_subscribers_delete').get(0).checked = false;
			jQuery('#emailer_subscribers_delete').get(0).disabled = true;
			jQuery('#emailer_subscribers_clear').get(0).checked = false;
			jQuery('#emailer_subscribers_clear').get(0).disabled = true;
		} else {
			jQuery('#emailer_subscribers_update').get(0).disabled = false;
			jQuery('#emailer_subscribers_delete').get(0).disabled = false;
			// Load subscribers list
			jQuery.post(THEMEREX_EMAILER_ajax_url, {
				action: 'emailer_group_getlist',
				nonce: THEMEREX_EMAILER_ajax_nonce,
				group: group
			}).done(function(response) {
				var rez = JSON.parse(response);
				if (rez.error === '') {
					jQuery('#emailer_subscribers').val(rez.subscribers);
				}
			});
		}
	}).trigger('change');

	jQuery('#emailer_subscribers_update').change(function() {
		if (jQuery(this).get(0).checked) {
			jQuery('#emailer_subscribers_delete').get(0).checked = false;
			jQuery('#emailer_subscribers_delete').get(0).disabled = true;
			jQuery('#emailer_subscribers_clear').get(0).disabled = false;
		} else {
			jQuery('#emailer_subscribers_clear').get(0).checked = false;
			jQuery('#emailer_subscribers_clear').get(0).disabled = true;
			jQuery('#emailer_subscribers_delete').get(0).disabled = false;
		}
	});

	jQuery('#emailer_subscribers_delete').change(function() {
		if (jQuery(this).get(0).checked) {
			jQuery('#emailer_subscribers_update').get(0).checked = false;
			jQuery('#emailer_subscribers_update').get(0).disabled = true;
			jQuery('#emailer_subscribers_clear').get(0).checked = false;
			jQuery('#emailer_subscribers_clear').get(0).disabled = true;
		} else {
			jQuery('#emailer_subscribers_update').get(0).disabled = false;
		}
	});

	// Save file
	jQuery('#trx_emailer_send').click(function(e) {
		if (typeof(tinymce) != 'undefined') {
			var editor = tinymce.activeEditor;
			if ( 'mce_fullscreen' == editor.id )
				tinymce.get('content').setContent(editor.getContent({format : 'raw'}), {format : 'raw'});
			tinymce.triggerSave();
		}
		jQuery('#trx_emailer_form').get(0).submit();
		e.preventDefault();
		return false;
	});

});
