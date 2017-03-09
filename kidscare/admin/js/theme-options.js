// ThemeREX Options scripts
jQuery(document).ready(function(){
	"use strict";

	
	// Init fields and groups
	//----------------------------------------------------------------
	themerex_options_init(jQuery('.themerex_options_body'));

		
	// Check top section for fixed position
	//----------------------------------------------------------------
	scrollMenuFix();

	// Save options
	//----------------------------------------------------------------
	jQuery('.themerex_options').on('click', '.themerex_options_button_save', function (e) {
		"use strict";
		// Save editors content
		if (typeof(tinymce) != 'undefined') {
			var editor = tinymce.activeEditor;
			if ( editor!=null && 'mce_fullscreen' == editor.id )
				tinymce.get('content').setContent(editor.getContent({format : 'raw'}), {format : 'raw'});
			tinymce.triggerSave();
		}
		// Prepare data
		var data = {
			action: 'themerex_options_save',
			nonce: THEMEREX_OPTIONS_ajax_nonce,
			data: jQuery(".themerex_options_form").serialize(),
			mode: "save"
		};
		jQuery.post(THEMEREX_OPTIONS_ajax_url, data, function(response) {
			"use strict";
			themerex_message_success('', THEMEREX_OPTIONS_STRINGS_save_options);
		});
		e.preventDefault();
		return false;
	});

	
	// Reset options
	//----------------------------------------------------------------
	jQuery('.themerex_options').on('click', '.themerex_options_button_reset', function (e) {
		"use strict";
		themerex_message_confirm(THEMEREX_OPTIONS_STRINGS_reset_options_confirm, THEMEREX_OPTIONS_STRINGS_reset_options, function(btn) {
			"use strict";
			if (btn != 1) return;
			var data = {
				action: 'themerex_options_save',
				nonce: THEMEREX_OPTIONS_ajax_nonce,
				mode: "reset"
			};
			jQuery.post(THEMEREX_OPTIONS_ajax_url, data, function(response) {
				"use strict";
				themerex_message_success('', THEMEREX_OPTIONS_STRINGS_reset_options);
			});
			
		});
		e.preventDefault();
		return false;
	});


	// Export options
	//----------------------------------------------------------------
	jQuery('.themerex_options').on('click', '.themerex_options_button_export,.themerex_options_button_import', function (e) {
		"use strict";
		var action = 'import';
		if (jQuery(this).hasClass('themerex_options_button_export')) {
			action = 'export';
			// Save editors content
			if (typeof(tinymce) != 'undefined') {
				var editor = tinymce.activeEditor;
				if ( editor!=null && 'mce_fullscreen' == editor.id )
					tinymce.get('content').setContent(editor.getContent({format : 'raw'}), {format : 'raw'});
				tinymce.triggerSave();
			}
		}
		// Prepare dialog
		var html = '<div class="themerex_options_export_set_name">'
			+'<form>'
			+(action=='import' 
				? ''
				: '<div class="themerex_options_export_name_area">'
					+'<label for="themerex_options_export_name">'+THEMEREX_OPTIONS_STRINGS_export_options_label+'</label>'
					+'<input id="themerex_options_export_name" name="themerex_options_export_name" class="themerex_options_export_name" type="text">'
					+'</div>');
		if (THEMEREX_OPTIONS_export_list.length > 0) { 
			html += '<div class="themerex_options_export_name2_area">'
				+'<label for="themerex_options_export_name2">'+(action=='import' ? THEMEREX_OPTIONS_STRINGS_export_options_label : THEMEREX_OPTIONS_STRINGS_export_options_label2)+'</label>'
				+'<select id="themerex_options_export_name2" name="themerex_options_export_name2" class="themerex_options_export_name2">'
				+'<option value="">'+THEMEREX_OPTIONS_STRINGS_export_options_select+'</option>';
			for (var i=0; i<THEMEREX_OPTIONS_export_list.length; i++) {
				html += '<option value="'+THEMEREX_OPTIONS_export_list[i]+'">'+THEMEREX_OPTIONS_export_list[i]+'</option>';
			}
			html += '</select>'
				+'</div>';
		} else if (action=='import') {
			html += '<div class="themerex_options_export_empty">'+THEMEREX_OPTIONS_STRINGS_export_empty+'</div>';
		}
		if (action=='import') {
			html += '<div class="themerex_options_export_textarea">'
				+'<label for="themerex_options_export_data">'+THEMEREX_OPTIONS_STRINGS_import_options_label+'</label>'
				+'<textarea id="themerex_options_export_data" name="themerex_options_export_data" class="themerex_options_export_data"></textarea>'
				+'</div>';
		}
		html += '</form>'
			+'</div>';

		// Show Dialog popup
		var THEMEREX_options_export_popup = themerex_message_dialog(html, action=='import' ? THEMEREX_OPTIONS_STRINGS_import_options_header : THEMEREX_OPTIONS_STRINGS_export_options_header,
			function(popup) {
				"use strict";
				// Init code
			},
			function(btn, popup) {
				"use strict";
				if (btn != 1) return;

				var val2 = THEMEREX_options_export_popup.find('#themerex_options_export_name2').val();

				if (action=='import') {			// Import settings
					
					var text = THEMEREX_options_export_popup.find('#themerex_options_export_data').val();

					if (val2=='' && text=='') {
						themerex_message_warning(THEMEREX_OPTIONS_STRINGS_import_options_error, THEMEREX_OPTIONS_STRINGS_import_options_header);
						return;
					}
					
					var data = {
						action: 'themerex_options_import',
						nonce: THEMEREX_OPTIONS_ajax_nonce,
						name2: val2,
						text: text,
						override: THEMEREX_OPTIONS_override
					};
					jQuery.post(THEMEREX_OPTIONS_ajax_url, data, function(response) {
						"use strict";
						var rez = JSON.parse(response);
						if (rez.error === '') {
							themerex_options_import_values(rez.data);
							themerex_message_success(THEMEREX_OPTIONS_STRINGS_import_options, THEMEREX_OPTIONS_STRINGS_import_options_header);
						} else {
							themerex_message_warning(THEMEREX_OPTIONS_STRINGS_import_options_failed, THEMEREX_OPTIONS_STRINGS_import_options_header);
						}
					});
					

				} else {						// Export settings

					var val = THEMEREX_options_export_popup.find('#themerex_options_export_name').val();
					if (val=='' && val2=='') {
						themerex_message_warning(THEMEREX_OPTIONS_STRINGS_export_options_error, THEMEREX_OPTIONS_STRINGS_export_options_header);
						return;
					}
					// Prepare data
					var form = null;
					if (jQuery("form.themerex_options_form").length === 1) {		// Main theme options
						form = jQuery("form.themerex_options_form");
					} else if (jQuery("form#addtag").length === 1 ) {				// Options for the category (add new)
						form = jQuery("form#addtag");
					} else if (jQuery("form#edittag").length === 1 ) {				// Options for the category (edit)
						form = jQuery("form#edittag");
					} else if (jQuery("form#post").length === 1 ) {					// Options for the post or page
						form = jQuery("form#post");
					}
					var data = {
						action: 'themerex_options_save',
						nonce: THEMEREX_OPTIONS_ajax_nonce,
						data: form.serialize(),
						name: val,
						name2: val2,
						mode: 'export',
						override: THEMEREX_OPTIONS_override
					};
					jQuery.post(THEMEREX_OPTIONS_ajax_url, data, function(response) {
						"use strict";
						var rez = JSON.parse(response);
						themerex_message_success(THEMEREX_OPTIONS_STRINGS_export_options+'<br>'+THEMEREX_OPTIONS_STRINGS_export_link.replace('%s', '<br><a target="_blank" href="'+rez.link+'">'+THEMEREX_OPTIONS_STRINGS_export_download+'</a>'), THEMEREX_OPTIONS_STRINGS_export_options_header);
						if (val!='') {
							if (val2!='') {
								for (var i=0; i<THEMEREX_OPTIONS_export_list.length; i++) {
									if (THEMEREX_OPTIONS_export_list[i]==val2) {
										THEMEREX_OPTIONS_export_list[i] = val;
										break;
									}
								}
							} else
								THEMEREX_OPTIONS_export_list.push(val);
						}
					});
				}
			});
		e.preventDefault();
		return false;
	});

});


// Init all elements
//-----------------------------------------------------------------
var THEMEREX_OPTIONS_BODY = null;
function themerex_options_init(to_body) {
	
	THEMEREX_OPTIONS_BODY = to_body;
	
	// Pretty Photo init
	//----------------------------------------------------------------
	prettyPhotoInit(to_body);

	// Check dependencies
	to_body.find('[data-param]').change(function () {
		themerex_options_check_dependency(jQuery(this).parents('.themerex_options_tab_content'));
	});

	// Tabs and partitions init
	//----------------------------------------------------------------
	to_body.find('.themerex_options_tab,.themerex_options_partition').tabs({
		// Init options, which depends from width() or height() only after open it's parent tab or partition
		create: function (e, ui) {
			if (ui.panel) {
				themerex_options_init_hidden_elements(ui.panel);
			}
		},
		activate: function (e, ui) {
			if (ui.newPanel) {
				themerex_options_init_hidden_elements(ui.newPanel);
			}
		}
	});
	to_body.find('.themerex_options_tab > ul,.themerex_options_partition > ul').sortable().disableSelection();


	// Accordion init
	//----------------------------------------------------------------
	to_body.find('.themerex_options_accordion').accordion({
		header: ".themerex_options_accordion_header",
		collapsible: true,
		heightStyle: "content",
		// Init options, which depends from width() or height() only after open it's parent accordion
		create: function (e, ui) {
			if (ui.panel) {
				themerex_options_init_hidden_elements(ui.panel);
			}
		},
		activate: function (e, ui) {
			if (ui.newPanel) {
				themerex_options_init_hidden_elements(ui.newPanel);
			}
		}
	});
	to_body.find('.themerex_options_accordion').sortable().disableSelection();


	// Toggles
	//----------------------------------------------------------------
	to_body.on('click', '.themerex_options_toggle .themerex_options_toggle_header', function () {
		"use strict";
		if (jQuery(this).hasClass('ui-state-active')) {
			jQuery(this).removeClass('ui-state-active');
			jQuery(this).siblings('div').slideUp();
		} else {
			jQuery(this).addClass('ui-state-active');
			jQuery(this).siblings('div').slideDown();
			themerex_options_init_hidden_elements(jQuery(this));
		}
	});

	// Masked input init
	//----------------------------------------------------------------
	to_body.find('.themerex_options_input_masked').each(function () {
		"use strict";
		jQuery(this).mask(''+jQuery(this).data('mask'));
	});


	// Datepicker init
	//----------------------------------------------------------------
	to_body.find('.themerex_options_input_date').each(function () {
		"use strict";
		var linked = jQuery(this).data('linked-field');
		var curDate = linked ? jQuery('#'+linked).val() : jQuery(this).val();
		jQuery(this).datepicker({
			dateFormat: jQuery(this).data('format'),
			numberOfMonths: jQuery(this).data('months'),
			gotoCurrent: true,
			changeMonth: true,
			changeYear: true,
			defaultDate: curDate,
			onSelect: function (text, ui) {
				var linked = jQuery(this).data('linked-field');
				if (!empty(linked)) {
					jQuery('#'+linked).val(text).trigger('change');
				} else {
					ui.input.trigger('change');
				}
			}
		});
	});


	// Spinner arrows click
	//----------------------------------------------------------------
	to_body.on('click', '.themerex_options_field_spinner .themerex_options_arrow_up,.themerex_options_field_spinner .themerex_options_arrow_down', function () {
		"use strict";
		var field = jQuery(this).parent().siblings('input');
		var inc = (jQuery(this).hasClass('themerex_options_arrow_up') ? 1 : -1) * Math.max(1, isNaN(field.data('increment')) ? 1 : field.data('increment'));
		var minValue = field.data('min');
		var maxValue = field.data('max');
		var newValue = isNaN(field.val()) ? 0 : Number(field.val()) + inc;
		if (!isNaN(maxValue) && newValue > maxValue) {
			newValue = maxValue;
		}
		if (!isNaN(minValue) && newValue < minValue) {
			newValue = minValue;
		}
		field.val(newValue).trigger('change');
	});

	
	// Tags
	//----------------------------------------------------------------
	to_body.find('.themerex_options_field_tags .themerex_options_field_content').sortable({
		items: "span",
		update: function(event, ui) {
			var tags = '';
			ui.item.parent().find('.themerex_options_tag').each(function() {
				tags += (tags ? THEMEREX_OPTIONS_delimiter : '') + jQuery(this).text();
			});
			ui.item.siblings('input[type="hidden"]').eq(0).val(tags).trigger('change');
		}
	}).disableSelection();
	to_body.on('keypress', '.themerex_options_field_tags input[type="text"]', function (e) {
		"use strict";
		if (e.which===44) {
			addTagInList(jQuery(this));
			e.preventDefault();
			return false;
		}
	});
	to_body.on('keydown', '.themerex_options_field_tags input[type="text"]', function (e) {
		"use strict";
		if (e.which===13) {
			addTagInList(jQuery(this));
			e.preventDefault();
			return false;
		}
	});
	function addTagInList(obj) {
		"use strict";
		if (obj.val().trim()!='') {
			var text = obj.val().trim();
			obj.before('<span class="themerex_options_tag iconadmin-cancel">'+text+'</span>');
			var tags = obj.next().val();
			obj.next().val(tags + (tags ? THEMEREX_OPTIONS_delimiter : '') + text).trigger('change');
			obj.val('');
		}
	}
	to_body.on('click', '.themerex_options_field_tags .themerex_options_field_content span', function (e) {
		"use strict";
		var text = jQuery(this).text();
		var tags = jQuery(this).siblings('input[type="hidden"]').eq(0).val()+THEMEREX_OPTIONS_delimiter;
		tags = tags.replace(text+THEMEREX_OPTIONS_delimiter, '');
		tags = tags.substring(0, tags.length-1);
		jQuery(this).siblings('input[type="hidden"]').eq(0).val(tags).trigger('change');
		jQuery(this).siblings('input[type="text"]').focus();
		jQuery(this).remove();
		e.preventDefault();
		return false;
	});
	to_body.on('click', '.themerex_options_field_tags .themerex_options_field_content', function (e) {
		"use strict";
		jQuery(this).find('input[type="text"]').focus();
		e.preventDefault();
		return false;
	});

	
	// Checkbox
	//----------------------------------------------------------------
	to_body.on('change', '.themerex_options_field_checkbox input', function (e) {
		"use strict";
		jQuery(this).next('label').eq(0).toggleClass('themerex_options_state_checked');
		if (jQuery(this).next('label').eq(0).hasClass('themerex_options_state_checked'))
			jQuery(this).attr('checked', 'checked');
		else
			jQuery(this).removeAttr('checked');
		e.preventDefault();
		return false;
	});


	// Radio button
	//----------------------------------------------------------------
	to_body.on('change', '.themerex_options_field_radio input[type="radio"]', function (e) {
		"use strict";
		jQuery(this).parent().parent().find('label').removeClass('themerex_options_state_checked').find('span').removeClass('iconadmin-dot-circled');
		jQuery(this).parent().parent().find('input:checked').next('label').eq(0).addClass('themerex_options_state_checked').find('span').addClass('iconadmin-dot-circled');
		jQuery(this).parent().parent().find('input[type="hidden"]').val(jQuery(this).parent().parent().find('input:checked').val()).trigger('change');
		e.preventDefault();
		return false;
	});


	// Switch button
	//----------------------------------------------------------------
	to_body.on('click', '.themerex_options_field_switch .themerex_options_switch_inner', function (e) {
		"use strict";
		var val = parseInt(jQuery(this).css('marginLeft'))==0 ? 2 : 1;
		var data = jQuery(this).find('span').eq(val-1).data('value');
		jQuery(this).parent().siblings('input[type="hidden"]').eq(0).val(data).trigger('change');
		jQuery(this).parent().toggleClass('themerex_options_state_off', val==2)
		e.preventDefault();
		return false;
	});


	// Checklist
	//----------------------------------------------------------------
	to_body.on('click', '.themerex_options_field_checklist .themerex_options_listitem', function (e) {
		"use strict";
		var multiple = jQuery(this).parents('.themerex_options_field_checklist').hasClass('themerex_options_multiple');
		if (!multiple) {
			jQuery(this).siblings('.themerex_options_listitem').removeClass('themerex_options_state_checked');
		}
		jQuery(this).toggleClass('themerex_options_state_checked');
		collectCheckedItems(jQuery(this).parent());
		e.preventDefault();
		return false;
	});
	to_body.find('.themerex_options_field_checklist.themerex_options_multiple .themerex_options_field_content').sortable({
		update: function(event, ui) {
			"use strict";
			collectCheckedItems(ui.item.parent());
		}
	}).disableSelection();


	// Select, list, images, icons, fonts
	//----------------------------------------------------------------
	to_body.on('click', '.themerex_options_field_select .themerex_options_input,.themerex_options_field_select .themerex_options_field_after,.themerex_options_field_images .themerex_options_caption_image,.themerex_options_field_icons .themerex_options_caption_icon', function (e) {
		"use strict";
		jQuery(this).siblings('.themerex_options_input_menu').slideToggle();
		e.preventDefault();
		return false;
	});

	to_body.on('click', '.themerex_options_field .themerex_options_menuitem', function (e) {
		"use strict";
		var multiple = jQuery(this).parents('.themerex_options_field').hasClass('themerex_options_multiple');
		if (!multiple) {
			jQuery(this).siblings('.themerex_options_menuitem').removeClass('themerex_options_state_checked');
			jQuery(this).addClass('themerex_options_state_checked');
		} else {
			jQuery(this).toggleClass('themerex_options_state_checked');
		}
		collectCheckedItems(jQuery(this).parent());
		if (!multiple && !jQuery(this).parent().hasClass('themerex_options_input_menu_list'))
			jQuery(this).parent().slideToggle();
		e.preventDefault();
		return false;
	});

	to_body.find('.themerex_options_field.themerex_options_multiple .themerex_options_input_menu').sortable({
		update: function(event, ui) {
			"use strict";
			collectCheckedItems(ui.item.parent());
		}
	}).disableSelection();

	// Collect checked items
	function collectCheckedItems(list) {
		"use strict";
		var val = '', caption = '', image = '', icon = '';
		list.find('.themerex_options_menuitem,.themerex_options_listitem').each(function() {
			"use strict";
			if (jQuery(this).hasClass('themerex_options_state_checked')) {
				val += (val ? THEMEREX_OPTIONS_delimiter : '') + jQuery(this).data('value');
				var img = jQuery(this).find('.themerex_options_input_image');
				if (img.length > 0) {
					image = img.eq(0).data('src');
				} else if (jQuery(this).parents('.themerex_options_field_icons').length > 0) {
					icon = jQuery(this).data('value');
				} else {
					caption += (caption ? THEMEREX_OPTIONS_delimiter : '') + jQuery(this).html();
				}
			}
		});
		list.parent().find('input[type="hidden"]').eq(0).val(val).trigger('change');
		if (caption != '')
			list.parent().find('input[type="text"]').eq(0).val(caption);
		if (image != '')
			list.parent().find('.themerex_options_caption_image span').eq(0).css('backgroundImage', 'url('+image+')'); //.attr('src', image);
		if (icon != '') {
			var field = list.parent().find('.themerex_options_input_socials');
			if (field.length > 0) {
				var btn = field.next();
				var cls = btn.attr('class');
				cls = (cls.indexOf(' icon') > 0 ? cls.substr(0, cls.indexOf(' icon')) : cls) + ' ' + icon;
				btn.removeClass().addClass(cls).trigger('change');
			} else
				list.parent().find('.themerex_options_caption_icon span').eq(0).removeClass().addClass(icon).trigger('change');
		}
	}



	// Color selector
	//----------------------------------------------------------------
	to_body.find('.themerex_options_input_color').each(function () {
		"use strict";
		if (jQuery(this).next().hasClass('iColorPicker')) return;
		jQuery(this).wpColorPicker({
			// you can declare a default color here,
			// or in the data-default-color attribute on the input
			//defaultColor: false,

			// a callback to fire whenever the color changes to a valid color
			change: function(e, ui){
				jQuery(e.target).val(ui.color).trigger('change');
			},

			// a callback to fire when the input is emptied or an invalid color
			clear: function(e) {
				jQuery(e.target).prev().trigger('change')
			},

			// hide the color picker controls on load
			//hide: true,

			// show a group of common colors beneath the square
			// or, supply an array of colors to customize further
			//palettes: true
		});
		//jQuery('.wp-picker-clear').css('width', '80px');
	});
	if (to_body.find('.themerex_options_input_color + .iColorPicker').length > 0) {
		iColorPicker();
		to_body.find('.themerex_options_input_color + .iColorPicker').each(function() {
			jQuery(this).click(function (e) {
				"use strict";
				iColorShow(null, jQuery(this), function(fld, clr) {
					"use strict";
					fld.css('backgroundColor', clr);
					fld.siblings('input').attr('value', clr).trigger('change');
				});
			});
			var prev_fld = jQuery(this).prev();
			var prev_val = prev_fld.val();
			if (prev_val!='') {
				jQuery(this).css('backgroundColor', prev_val);
			}
			prev_fld.change(function() {
				"use strict";
				jQuery(this).next().css('backgroundColor', jQuery(this).val());
			});
		});
	}

	// Clone buttons
	//----------------------------------------------------------------
	to_body.on('click', '.themerex_options_clone_button_add', function (e) {
		"use strict";
		var clone_area = jQuery(this).parents('.themerex_options_cloneable_area').eq(0);
		var clone_item = null;
		var max_num = 0;
		clone_area.find('.themerex_options_cloneable_item').each(function() {
			"use strict";
			var cur_item = jQuery(this);
			if (clone_item == null) 
				clone_item = cur_item;
			var num = Number(cur_item.find('input[name*="_numbers[]"]').eq(0).val());
			if (num > max_num)
				max_num = num;
		});
		var clonedObj = clone_item.clone();
		clonedObj.find('input[type="text"],textarea').val('');
		clonedObj.find('input[name*="_numbers[]"]').val(max_num+1);
		jQuery(this).before(clonedObj);
		e.preventDefault();
		return false;
	});

	to_body.on('click', '.themerex_options_clone_button_del', function (e) {
		"use strict";
		if (jQuery(this).parents('.themerex_options_cloneable_item').parent().find('.themerex_options_cloneable_item').length > 1)
			jQuery(this).parents('.themerex_options_cloneable_item').eq(0).remove();
		else
			themerex_message_warning(THEMEREX_OPTIONS_STRINGS_del_item_error, THEMEREX_OPTIONS_STRINGS_del_item);
		e.preventDefault();
		return false;
	});



	// Inherit buttons
	//----------------------------------------------------------------
	to_body.on('click', '.themerex_options_button_inherit', function (e) {
		"use strict";
		var inherit = !jQuery(this).hasClass('themerex_options_inherit_off');
		if (inherit) {
			jQuery(this).addClass('themerex_options_inherit_off');
			jQuery(this).parents('.themerex_options_field').find('.themerex_options_content_inherit').fadeOut().find('input').val('');
		} else {
			jQuery(this).removeClass('themerex_options_inherit_off');
			jQuery(this).parents('.themerex_options_field').find('.themerex_options_content_inherit').fadeIn().find('input').val('inherit');
		}
		e.preventDefault();
		return false;
	});
	to_body.on('click', '.themerex_options_content_inherit', function (e) {
		"use strict";
		jQuery(this).parents('.themerex_options_field').find('.themerex_options_button_inherit').addClass('themerex_options_inherit_off');
		jQuery(this).fadeOut().find('input').val('');
		e.preventDefault();
		return false;
	});
}


// Standard actions
//-----------------------------------------------------------------

// Open Wordpress media manager window
var themerex_options_media_frame = [];
function themerex_options_action_media_upload(obj) {
	"use strict";
	var button = jQuery(obj);
	var field  = button.data('linked-field') ? jQuery("#"+button.data('linked-field')).eq(0) : button.siblings('input');
	var fieldId = field.attr('id');
	if ( themerex_options_media_frame[fieldId] ) {
		themerex_options_media_frame[fieldId]['field'] = field;
		themerex_options_media_frame[fieldId]['frame'].open();
		return;
	}
	themerex_options_media_frame[fieldId] = [];
	themerex_options_media_frame[fieldId]['field'] = field;
	themerex_options_media_frame[fieldId]['multi'] = button.data('multiple') ? true : false;
	themerex_options_media_frame[fieldId]['frame'] = wp.media({		// = wp.media.frames.media_frame
		// Multiple choise
		multiple: themerex_options_media_frame[fieldId]['multi'] ? 'add' : false,
		// Set the title of the modal.
		title: button.data('caption-choose'),
		// Tell the modal to show only images.
		library: {
			type: button.data('type') ? button.data('type') : 'image'
		},
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: button.data('caption-update'),
			// Tell the button to close the modal
			close: true
		}
	});
	themerex_options_media_frame[fieldId]['frame'].on( 'select', function(e) {
		"use strict";
		var attachment = '', pos = -1, init = false;
		if (themerex_options_media_frame[fieldId]['multi']) {
			themerex_options_media_frame[fieldId]['frame'].state().get('selection').map( function( att ) {
				"use strict";
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = themerex_options_media_frame[fieldId]['field'].val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = themerex_options_media_frame[fieldId]['frame'].state().get('selection').first().toJSON().url;
			if (!button.data('linked-field')) {
				var output = '';
				if ((pos = attachment.lastIndexOf('.'))>=0) {
					var ext = attachment.substr(pos+1);
					output = '<a class="themerex_options_image_preview" rel="prettyPhoto" target="_blank" href="' + attachment + '">';
					if ('jpg,png,gif'.indexOf(ext)>=0) {
						output += '<img src="'+attachment+'" alt="" rel="prettyPhoto" />';
						init = true;
					} else {
						output += '<span>'+attachment.substr(attachment.lastIndexOf('/')+1)+'</span>';
					}
					output += '</a>';
				}
				button.siblings('.themerex_options_image_preview').remove();
				if (output != '') {
					button.parent().append(output);
					if (init) prettyPhotoInit(THEMEREX_OPTIONS_BODY);
				}
			}
		}
		themerex_options_media_frame[fieldId]['field'].val(attachment).trigger('change');
	});
	themerex_options_media_frame[fieldId]['frame'].open();
}

// Clear media field
function themerex_options_action_media_reset(obj) {
	"use strict";
	var button = jQuery(obj);
	var field  = button.data('linked-field') ? jQuery("#"+button.data('linked-field')).eq(0) : button.siblings('input');
	button.siblings('.themerex_options_image_preview').remove();
	field.val('').trigger('change');
}

// Select fontello icon
function themerex_options_action_select_icon(obj) {
	"use strict";
	var button = jQuery(obj);
	var field  = button.data('linked-field') ? jQuery("#"+button.data('linked-field')).eq(0) : button.siblings('input[type="hidden"]').eq(0);
	button.siblings('.themerex_options_input_menu').slideToggle();
}

// Select menu (dropdown list)
function themerex_options_action_show_menu(obj) {
}

// Import dummy data
function themerex_options_action_clear_cache(obj) {
	// Prepare data
	var data = {
		action: 'themerex_options_clear_cache',
		nonce: THEMEREX_OPTIONS_ajax_nonce
	};
	jQuery.post(THEMEREX_OPTIONS_ajax_url, data, function(response) {
		"use strict";
		themerex_message_success(THEMEREX_OPTIONS_STRINGS_clear_cache, THEMEREX_OPTIONS_STRINGS_clear_cache_header);
	});
}



// Pretty photo init
function prettyPhotoInit(to_body) {
	"use strict";
	//jQuery("a[href$='jpg'],a[href$='jpeg'],a[href$='png'],a[href$='gif']").attr('rel', 'prettyPhoto');
	to_body.find("a[rel*='prettyPhoto']:not(.inited)").each(function() {
		"use strict";
		jQuery(this).addClass('inited').prettyPhoto({
			social_tools: '',
			theme: 'facebook',
			deeplinking: false
		});
	});
}


// Init previously hidden elements
//-----------------------------------------------------------------------------------
function themerex_options_init_hidden_elements(container) {
	"use strict";
	// Fields visibility
	themerex_options_check_dependency(container);
	// Range sliders
	container.find('.themerex_options_field_range').each(function () {
		"use strict";
		var obj = jQuery(this);
		var scale = obj.find('.themerex_options_range_scale');
		//var scaleWidth = obj.width() - parseInt(scale.css('left')) - parseInt(scale.css('right'));
		var scaleWidth = scale.width();
		if (scaleWidth <= 0) return;
		var step = parseFloat(obj.find('.themerex_options_input_range').data('step'));
		var prec = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
		var field = obj.find('.themerex_options_input_range input[type="hidden"]').eq(0);
		var val = field.val().split(THEMEREX_OPTIONS_delimiter);
		var rangeMin = parseFloat(obj.find('.themerex_options_range_min').html());
		var rangeMax = parseFloat(obj.find('.themerex_options_range_max').html());
		var scaleStep = scaleWidth / ((rangeMax - rangeMin) / step);
		var i = 0;
		obj.find('.themerex_options_range_slider').each(function () {
			"use strict";
			var fill = val.length==1 || i==1 ? 'width' : 'left';
			jQuery(this).css('left', (val[i]-rangeMin)*scaleStep/step+'px');
			scale.find('span').css(fill, ((val[i]-rangeMin)*scaleStep/step-(i==1 ? (val[0]-rangeMin)*scaleStep/step : 0))+'px');
			i++;
		});
		if (!obj.hasClass('inited')) {
cl(scaleWidth+' '+scaleStep);
			obj.addClass('inited').find('.themerex_options_range_slider').draggable({
				axis: 'x',
				grid: [scaleStep, scaleStep],
				containment: '.themerex_options_input_range',
				scroll: false,
				drag: function (e, ui) {
					"use strict";
					var field = obj.find('.themerex_options_input_range input[type="hidden"]').eq(0);
					var val = field.val().split(THEMEREX_OPTIONS_delimiter);
					var slider = ui.helper;
					var idx = slider.index()-1;
					var newVal = Math.min(rangeMax, Math.max(rangeMin, Math.round(ui.position.left / scaleStep * step * prec) / prec + rangeMin));
					if (val.length==2) {
						if (idx==0 && newVal > val[1]) {
							newVal = val[1];
							ui.position.left = (newVal-rangeMin)*scaleStep/step;
						}
						if (idx==1 && newVal < val[0]) {
							newVal = val[0];
							ui.position.left = (newVal-rangeMin)*scaleStep/step;
						}
					}
					slider.find('.themerex_options_range_slider_value').html(newVal);
					val[idx] = newVal;
					field.val(val.join(THEMEREX_OPTIONS_delimiter)).trigger('change');
					if (val.length==2)
						scale.find('span').css('left', (val[0]-rangeMin)*scaleStep/step+'px');
					scale.find('span').css('width', ((val[val.length==2 ? 1 : 0]-rangeMin)*scaleStep/step-(val.length==2 ? (val[0]-rangeMin)*scaleStep/step : 0))+'px');
				}
			});
		}
	});
}


// Check dependencies
function themerex_options_check_dependency(cont) {
	"use strict";
	if (typeof THEMEREX_shortcodes == 'undefined') return;
	var sc = THEMEREX_shortcodes[THEMEREX_shortcodes_current_idx];
	var popup = cont.parents('.themerex_options_tab');
	//var cont = jQuery('.themerex_shortcodes_body');
	cont.find('[data-param]').each(function() {
		"use strict";
		var field = jQuery(this);
		var param = field.data('param');
		var value = field.attr('type') != 'checkbox' || field.get(0).checked ? field.val() : '';
		var depend = false;
		var i = '', j = '';
		for (i in sc.params) {
			if (sc.params[i].id == param) {
				if (typeof sc.params[i].dependency != 'undefined')
					depend = sc.params[i].dependency;
				break;
			}
		}
		if (depend === false && typeof sc.children != 'undefined') {
			for (i in sc.children.params) {
				if (sc.children.params[i].id == param) {
					if (typeof sc.children.params[i].dependency != 'undefined')
						depend = sc.children.params[i].dependency;
					break;
				}
			}
		}
		if (depend) {
			var dep_cnt = 0, dep_all = 0;
			var dep_cmp = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
			var fld=null, val='';
			for (i in depend) {
				if (i == 'compare') continue;
				dep_all++;
				fld = popup.find('[data-param="'+i+'"]');
				if (fld.length > 0) {
					val = fld.attr('type') != 'checkbox' || fld.get(0).checked ? fld.val() : '';
					for (j in depend[i]) {
						if ((depend[i][j]=='not_empty' && val!='') || (depend[i][j]=='is_empty' && val=='') || (val!='' && val.indexOf(depend[i][j])==0)) {
							dep_cnt++;
							break;
						}
					}
				}
				if (dep_cnt > 0 && dep_cmp == 'or')
					break;
			}
			if ((dep_cnt > 0 && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
				field.parents('.themerex_options_field').show().removeClass('themerex_options_no_use');
			} else {
				field.parents('.themerex_options_field').hide().addClass('themerex_options_no_use');
			}
		}
	});
}

// Fix header on scroll
jQuery(window).scroll(function () {
	"use strict";
	scrollMenuFix();
});

function scrollMenuFix() {
	"use strict";
	var headerOptions = jQuery('.themerex_options_form .themerex_options_header');
	if (headerOptions.length > 0) {
		if (headerOptions.data('wrap') != 1){
			headerOptions.wrap('<div class="themerex_options_header_wrap" style="height:'+headerOptions.height()+'px;"></div>' );
			headerOptions.attr('data-wrap','1')
		} 
		var scrollPosition = jQuery(window).scrollTop();
		var adminBarHeight = jQuery('#wpadminbar').height();
		var tRexBar = jQuery('.themerex_options_header').height();
		if (scrollPosition > (adminBarHeight + 20 + tRexBar)) {
			jQuery('.themerex_options_header').addClass('themerex_options_header_fixed');
		} else {
			jQuery('.themerex_options_header').removeClass('themerex_options_header_fixed');
		}
	}
}


// Import values
function themerex_options_import_values(data) {
	"use strict";
	var msg = '', res = '';
	for (var opt in data) {
		if ((res = themerex_options_set_value(opt, data[opt])) != '') {
			msg += (msg!='' ? ',<br>' : '') + res;
		}
	}
	if (msg != '') {
		themerex_message_warning(THEMEREX_OPTIONS_STRINGS_import_options_broken+'<br>'+msg, THEMEREX_OPTIONS_STRINGS_import_options_header);
	}
}

// Set new value for one field
function themerex_options_set_value(opt, val) {
	"use strict";
	var result = '';
	var suffix = (typeof val == 'object' ? '[]' : '');
	var fld = jQuery('[name="'+opt+suffix+'"]');
	if (fld.length == 0) return false;
	var parent = fld.parents('.themerex_options_field');
	var type = themerex_options_get_type(parent);
	var clone_area = fld.parents('.themerex_options_cloneable_area');
	var clone_item = null;
	if (clone_area.length > 0) {
		clone_area.find('.themerex_options_cloneable_item').each(function(idx) {
			if (idx == 0) {
				clone_item = jQuery(this);
				fld.eq(0).val('');
				jQuery(this).find('[name="'+opt+'_numbers[]"]').val(0);
				if (type=='socials') jQuery(this).find('[name="'+opt+'_icon[]"]').val('');
			} else
				jQuery(this).remove();
		});
	}
	if (typeof val != 'object' || typeof val[0] == 'undefined')
		val = [val];
	var cnt = 0;
	for (var i in val) {
		if (THEMEREX_OPTIONS_override!='general') {
			if (val[i] != 'inherit') {
				parent.find('.themerex_options_button_inherit').addClass('themerex_options_inherit_off');
				parent.find('.themerex_options_content_inherit').fadeOut().find('input').val('');
			} else {
				parent.find('.themerex_options_button_inherit').removeClass('themerex_options_inherit_off');
				parent.find('.themerex_options_content_inherit').fadeIn().find('input').val('inherit');
			}
		}
		if (cnt > 0 && clone_area.length > 0) {
			var clonedObj = clone_item.clone();
			clonedObj.find('input[name*="_numbers[]"]').val(i);
			clone_area.find('.themerex_options_clone_button_add').before(clonedObj);
			fld = jQuery('[name="'+opt+'[]"]');
		}
		if (THEMEREX_OPTIONS_override=='general' || val[i] != 'inherit') {
			if (type=='text' || type=='textarea' || type=='hidden' || type=='spinner') {
				fld.eq(cnt).val(val[i]).trigger('change');
			} else if (type=='editor') {
				fld.eq(cnt).val(val[i]).trigger('change');
				if (typeof(tinymce) != 'undefined' && typeof(tinymce.editors[opt])!='undefined') {
					tinymce.editors[opt].setContent(val[i]);
				}
			} else if (type=='date') {
				parent.datepicker( "setDate", val[i] );
				fld.eq(cnt).val(val[i]).trigger('change');
			} else if (type=='tags') {
				fld.eq(cnt).val(val[i]).trigger('change');
				fld.eq(cnt).parent().find('.themerex_options_tag').remove();
				fld.eq(cnt).prev().val('');
				var tags = val[i].split(THEMEREX_OPTIONS_delimiter);
				for (var j=0; j<tags.length; j++)
					fld.eq(cnt).prev().before('<span class="themerex_options_tag iconadmin-cancel">'+tags[j]+'</span>');
			} else if (type=='checkbox') {
				fld.eq(cnt).next('label').eq(0).toggleClass('themerex_options_state_checked', val[i]=='true');
				if (val[i]=='true')
					fld.eq(cnt).attr('checked', 'checked');
				else
					fld.eq(cnt).removeAttr('checked');
			} else if (type=='radio') {
				fld.eq(cnt).removeAttr('checked').parent().parent().find('label').removeClass('themerex_options_state_checked').find('span').removeClass('iconadmin-dot-circled');
				fld.eq(cnt).parent().parent().find('input[value="'+val[i]+'"]').attr('checked', 'checked').next('label').eq(0).addClass('themerex_options_state_checked').find('span').addClass('iconadmin-dot-circled');
				fld.eq(cnt).parent().parent().find('input[type="hidden"]').val(val[i]).trigger('change');
			} else if (type=='switch') {
				fld.eq(cnt).val(val[i]).trigger('change');
				var idx = fld.siblings('.themerex_options_switch').find('[data-value="'+val[i]+'"]').index();
				fld.eq(cnt).siblings('.themerex_options_switch').toggleClass('themerex_options_state_off', idx==1);
			} else if (type=='checklist') {
				fld.eq(cnt).val(val[i]).trigger('change');
				fld.eq(cnt).siblings('.themerex_options_listitem').removeClass('themerex_options_state_checked');
				var items = val[i].split(THEMEREX_OPTIONS_delimiter);
				for (var j=0; j<items.length; j++)
					fld.eq(cnt).siblings('.themerex_options_listitem[data-value="'+items[j]+'"]').addClass('themerex_options_state_checked');
			} else if (type=='media') {
				fld.eq(cnt).val(val[i]).trigger('change');
				fld.eq(cnt).siblings('.themerex_options_image_preview').remove();
				if (val[i]!='') {
					var file = val[i].split('/').pop();
					if (file!='') {
						var parts = file.split('.');
						var fname = parts[0];
						var ext = parts.length > 1 ? parts[1] : '';
						fld.eq(cnt).after('<a class="themerex_options_image_preview" rel="prettyPhoto" target="_blank" href="'+val[i]+'">'+('jpg,png,gif'.indexOf(ext)>=0 ? '<img src="'+val[i]+'" alt="" />' : '<span>'+fname+'</span>')+'</a>');
					}
				}
			} else if (type=='range') {
				fld.eq(cnt).val(val[i]).trigger('change');
				var scale = parent.find('.themerex_options_range_scale');
				var step = parseInt(parent.find('.themerex_options_input_range').data('step'));
				var rangeMin = parseInt(parent.find('.themerex_options_range_min').html());
				var rangeMax = parseInt(parent.find('.themerex_options_range_max').html());
				var scaleWidth = scale.width();
				var scaleStep = scaleWidth / (rangeMax - rangeMin) * step;
				var items = val[i].split(THEMEREX_OPTIONS_delimiter);
				for (var j=0; j<items.length; j++) {
					var slider = fld.eq(cnt).siblings('.themerex_options_range_slider').eq(j);
					slider.find('.themerex_options_range_slider_value').html(items[j]);
					var fill = items.length==1 || j==1 ? 'width' : 'left';
					slider.css('left', (items[j]-rangeMin)*scaleStep+'px');
					scale.find('span').css(fill, ((items[j]-rangeMin)*scaleStep-(j==1 ? (items[0]-rangeMin)*scaleStep : 0))+'px');
				}
			} else if (type=='select' || type=='images' || type=='icons') {
				fld.eq(cnt).val(val[i]).trigger('change');
				fld.eq(cnt).siblings('.themerex_options_input_menu').find('.themerex_options_menuitem').removeClass('themerex_options_state_checked');
				var items = val[i].split(THEMEREX_OPTIONS_delimiter);
				for (var j=0; j<items.length; j++) {
					fld.eq(cnt).siblings('.themerex_options_input_menu').find('.themerex_options_menuitem[data-value="'+items[j]+'"]').addClass('themerex_options_state_checked');
					if (type=='images') {
						var src = fld.eq(cnt).siblings('.themerex_options_input_menu').find('.themerex_options_menuitem[data-value="'+items[j]+'"]').find('span').data('src');
						fld.eq(cnt).siblings('.themerex_options_caption_image').find('span').css('backgroundImage', 'url('+src+')');
					} else if (type=='icons') {
						var cls = fld.eq(cnt).siblings('.themerex_options_caption_icon').find('span').attr('class');
						cls = (cls.indexOf(' icon') > 0 ? cls.substr(0, cls.indexOf(' icon')) : cls) + ' ' + items[i];
						fld.eq(cnt).siblings('.themerex_options_caption_icon').find('span').removeClass().addClass(cls);
					} else {
						var caption = fld.eq(cnt).siblings('.themerex_options_input_menu').find('.themerex_options_menuitem[data-value="'+items[j]+'"]').text();
						fld.eq(cnt).siblings('.themerex_options_input').val(caption);
					}
				}
			} else if (type=='socials') {
				fld.eq(cnt).val(val[i].url).trigger('change');
				fld.eq(cnt).siblings('[name="social_icons_icon[]"]').val(val[i].icon);
				fld.eq(cnt).siblings('.themerex_options_input_menu').find('.themerex_options_menuitem').removeClass('themerex_options_state_checked');
				fld.eq(cnt).siblings('.themerex_options_input_menu').find('.themerex_options_menuitem[data-value="'+val[i].icon+'"]').addClass('themerex_options_state_checked');
				var subtype = parent.hasClass('themerex_options_field_images') ? 'images' : 'icons';
				if (subtype=='images') {
					fld.eq(cnt).siblings('.themerex_options_caption_image').find('span').css('backgroundImage', 'url('+val[i].icon+')');
				} else if (subtype=='icons') {
					var cls = fld.eq(cnt).siblings('.themerex_options_field_after').attr('class');
					cls = (cls.indexOf(' icon') > 0 ? cls.substr(0, cls.indexOf(' icon')) : cls) + ' ' + val[i].icon;
					fld.eq(cnt).siblings('.themerex_options_field_after').removeClass().addClass(cls);
				}
			} else if (type=='color') {
				fld.eq(cnt).val(val[i]).trigger('change');
			} else {
				fld.eq(cnt).val(val[i]).trigger('change');
				if (!result) result = opt+' ('+type+') = '+val[i];
			}
		}
		cnt++;
	}
	return result;
}

// Return type of the field
function themerex_options_get_type(fld) {
	"use strict";
	var classes = fld.attr('class').split(' ');
	var type = 'text';
	for (var i=0; i < classes.length; i++) {
		if (classes[i].indexOf('themerex_options_field_')==0) {
			type = classes[i].split('_').pop();
			break;
		}	
	}
	return type;
}