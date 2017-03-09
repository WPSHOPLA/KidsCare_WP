/* global jQuery:false */

var THEMEREX_ADMIN_MODE = true;

// Media manager handler
var media_frame = null;
var media_link = '';
function showMediaManager(el) {
	"use strict";

	media_link = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( media_frame ) {
		media_frame.open();
		return false;
	}

	// Create the media frame.
	media_frame = wp.media({
		// Set the title of the modal.
		title: media_link.data('choose'),
		// Tell the modal to show only images.
		library: {
			type: 'image'
		},
		// Multiple choise
		multiple: media_link.data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: media_link.data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	media_frame.on( 'select', function() {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+media_link.data('linked-field')).eq(0);
		var attachment = '';
		if (media_link.data('multiple')===true) {
			media_frame.state().get('selection').map( function( att ) {
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = media_frame.state().get('selection').first().toJSON().url;
		}
		field.val(attachment);
		field.trigger('change');
	});

	// Finally, open the modal.
	media_frame.open();
	return false;
}
