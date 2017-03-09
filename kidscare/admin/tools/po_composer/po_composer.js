/* global jQuery */
jQuery(document).ready(function () {
	"use strict";
	
	po_composer_check_visibility();
	
	// Change in source list
	jQuery('.po_composer select,.po_composer textarea').change(function() {
		"use strict";
		po_composer_check_visibility();
	});

	// Save file
	jQuery('#po_save').click(function() {
		jQuery('#po_composer_form').get(0).submit();
	});
	
	// Change text field
	jQuery('#po_text').change(function() {
		jQuery('.po_composer_links').hide();
	});

	// Tab handler
	jQuery('.po_composer_editor').tabs({
		beforeActivate: function( event, ui ) {
			"use strict";
			if (ui.newTab.attr('id')=='po_composer_link_text') {
				po_composer_update_text();
				jQuery('#po_save').show();
			} else {
				po_composer_parse_text();
				po_composer_update_list();
				jQuery('#po_save').hide();
			}
		},
		activate: function( event, ui ) {
			"use strict";
			if (ui.newTab.attr('id')=='po_composer_link_text') {
				jQuery("#po_text").get(0).focus();
			} else {
				jQuery("#po_string").get(0).focus();
				jQuery("#po_string").select();
			}
		}
	});
	
	// Strings editor
	//======================================================
	jQuery('.po_composer #po_list').change(function() {
		"use strict";
		var idx = jQuery(this).val().split(',');
		var val = po_composer_get_string(po_composer_list[idx[0]]['msgstr'][idx[1]]);
		if (val == '') val = po_composer_get_string(po_composer_list[idx[0]]['msgid'][idx[1]]);
		jQuery('#po_string').data('idx', jQuery(this).val()).val(val).get(0).focus();
		jQuery('#po_string').select();
		jQuery('#po_msgid').html(po_composer_get_string(po_composer_list[idx[0]]['msgid'][idx[1]]));
	});

	jQuery('.po_composer #po_string').keydown(function(e) {
		"use strict";
		if (e.which==38 || e.which==40 || e.which==13) {	// Up, Down, Enter
			// Update strings list
			var idx = jQuery(this).data('idx').split(',');
			var val = jQuery(this).val();
			if (val == po_composer_get_string(po_composer_list[idx[0]]['msgid'][idx[1]])) val = '';
			jQuery('#list_'+idx[0]+'_'+idx[1]).toggleClass('translated', val != '');
			if (po_composer_get_string(po_composer_list[idx[0]]['msgstr'][idx[1]]) != val) {
				jQuery('#list_'+idx[0]+'_'+idx[1]).html(po_composer_get_html(val=='' ? po_composer_get_string(po_composer_list[idx[0]]['msgid'][idx[1]]) : val));
				jQuery('.po_composer_links').hide();
			}
			po_composer_list[idx[0]]['msgstr'][idx[1]] = (idx[1]==0 ? 'msgstr ' : '') + '"' + po_composer_addslashes(val) + '"';
			// Shift selected item
			var dir = e.which==38 ? -1 : 1;
			var list = jQuery('#po_list').eq(0);
			var newIdx = list.get(0).selectedIndex+dir;
			if (newIdx >=0 && newIdx < list.get(0).options.length) {
				list.get(0).selectedIndex = newIdx;
				//list.get(0).focus();
				list.trigger('change');
				e.preventDefault();
				return false;
			}
		}
	});

});


// Check fields visibility
function po_composer_check_visibility() {
	"use strict";
	var composer = jQuery('.po_composer');
	var po_src = composer.find('#po_src').val();

	// File upload field in left section
	if (po_src == 'upload_')
		composer.find('#po_file').show();
	else
		composer.find('#po_file').hide();

	// File upload field in right section
	var po_src2 = composer.find('#po_src2').val();
	if (po_src2 == 'upload_')
		composer.find('#po_file2').show();
	else
		composer.find('#po_file2').hide();
	
	// Submit button caption
	var caption = 'Update';
	if (po_src2 != '')
		caption = 'Merge';
	else if (po_src == 'upload_')
		caption = 'Upload';
	else if (po_src != 'edit_' && composer.find('#po_text').val()=='')
		caption = 'Load';
	composer.find('#po_save').html(caption);
}

// Parse text into array
var po_composer_list = [];
function po_composer_parse_text() {
	"use strict";
	po_composer_list = [];
	var text = jQuery('#po_text').val();
	if (text == '') return false;
	var data = text.split("\n");
	po_composer_list = [
			{
				'comments': [],
				'msgid': [],
				'msgstr': []
			}
		];
	var last = '';
	var idx = 0;
	var section = '';
	for (var i=0; i < data.length; i++) {
		var s = data[i];
		while (s.length > 0 && " \r\n\t".indexOf(s.charAt(s.length-1))>=0) {
			s = s.substring(0, s.length-1);
		}
		if (s != '') {
			if (s.charAt(0) == '#') {
				if (last=='') {
					idx++;
					po_composer_list[idx] = {
							'comments': [],
							'msgid': [],
							'msgstr': []
					};
				}
				po_composer_list[idx]['comments'].push(s);
			} else if (s.substr(0, 5) == 'msgid') {
				po_composer_list[idx]['msgid'].push(s);
				section = 'msgid';
			} else if (s.substr(0, 6) == 'msgstr') {
				po_composer_list[idx]['msgstr'].push(s);
				section = 'msgstr';
			} else if (s.charAt(0) == '"') {
				po_composer_list[idx][section].push(s);
			}
		}
		last = s;
	}
}

// Update list with strings for translate
function po_composer_update_list() {
	"use strict";
	var rez = '';
	for (var i=0; i < po_composer_list.length; i++) {
		var po = po_composer_list[i];
		if (po['msgid'].length > 0) {
			for (var j=0; j < po['msgid'].length; j++) {
				if (po_composer_get_string(po['msgid'][j]) == '') break;
				var tmp = po_composer_get_string(po_composer_get_html(po['msgstr'][j]));
				rez += "\n" + '<option id="list_' + i + '_' + j + '" value="' + i + ',' + j + '"' + (po['msgstr'][j]=='""' || po['msgstr'][j]=='msgstr ""' ? '' : ' class="translated"') + '>' 
					+ (tmp ? tmp : po_composer_get_string(po_composer_get_html(po['msgid'][j])))
					+ '</option>';
			}
		}
	}
	jQuery('#po_list').html(rez);
	jQuery('#po_list').get(0).selectedIndex = 0;
	jQuery('#po_list').trigger('change');
}


// Update textarea with new strings
function po_composer_update_text() {
	"use strict";
	var rez = '';
	for (var i=0; i < po_composer_list.length; i++) {
		var po = po_composer_list[i];
		if (po['comments'].length > 0) {
			for (var j=0; j < po['comments'].length; j++) {
				rez += po['comments'][j] + "\n";
			}
		}
		if (po['msgid'].length > 0) {
			for (var j=0; j < po['msgid'].length; j++) {
				rez += po['msgid'][j] + "\n";
			}
		}
		if (po['msgstr'].length > 0) {
			for (var j=0; j < po['msgstr'].length; j++) {
				rez += po['msgstr'][j] + "\n";
			}
		}
		rez += "\n";
	}
	jQuery('#po_text').val(rez).trigger('change');
}

// Return string without leading and trailing '"'
function po_composer_get_string(s) {
	"use strict";
	var start = s.indexOf('"');
	var end = s.lastIndexOf('"');
	if (start>=0 && end>start)
		s = po_composer_stripslashes(s.substr(start+1, end-start-1));
	else
		s = "";
	return s;
}

// Convert text to correct html text
function po_composer_get_html(s) {
	"use strict";
	var div = document.createElement('div');
	var text = document.createTextNode(s);
	div.appendChild(text);
	return div.innerHTML;
}

// Strip slashes 
function po_composer_stripslashes(s) {
	"use strict";
	var pos = s.indexOf('\\');
	while (pos >=0) {
		var ch = s.charAt(pos+1);
		//if ('"\'\\'.indexOf(ch)>=0)
			s = s.substring(0, pos) + s.substring(pos+1);
		pos = s.indexOf('\\', pos+1);
	}
	return s;
}

// Add slashes 
function po_composer_addslashes(s) {
	"use strict";
	var i = 0;
	while (i < s.length) {
		var ch = s.charAt(i);
		if ('"\'\\'.indexOf(ch)>=0) {
			s = s.substring(0, i) + '\\' + s.substring(i);
			i++;
		}
		i++;
	}
	return s;
}
