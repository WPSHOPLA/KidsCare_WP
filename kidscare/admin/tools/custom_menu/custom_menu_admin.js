/*
*
* Back-end scripts for custom menu plugin
*
*/

/* Behavior for menu types */
var THEMEREX_admin_icon_field = '';

jQuery(document).ready(function() {
	jQuery('.menu_type_select').each(function(){
		menu_type_select(jQuery(this));
	});

	jQuery('.menu_type_select').change(function() {
		menu_type_select(jQuery(this));
	});

	jQuery('.item_icon_select').click(function(e) {
		THEMEREX_admin_icon_field = jQuery(this).attr('id');
		jQuery('#fontello_box').slideDown();
		e.preventDefault();
		return false;
	});

	jQuery(document).click(function(){
		jQuery('#fontello_box').slideUp();
	});
	jQuery('#fontello_box').click(function(e){
		e.stopPropagation();
	});
	jQuery('#fontello_box li').click(function(e) {
		var className = jQuery(this).find('span').attr('class');
		jQuery('#'+THEMEREX_admin_icon_field).val(className).trigger('change');
		jQuery('#fontello_box').slideUp();
		e.preventDefault();
		return false;
	});

	jQuery('.item_icon_select').change(function(){
		change_icon_holder(jQuery(this));
	});

	jQuery('.item_icon_select').each(function(){
		change_icon_holder(jQuery(this));
	});

	jQuery('.post_types_list input[type=checkbox]').change(function(){
		var typesList = '';
		jQuery(this).parent().find('input').each(function() {
			if(jQuery(this).prop("checked")) 
				typesList += (typesList ? ',' : '') + jQuery(this).val();
		});
		jQuery(this).parent().next().val(typesList);
	});

	jQuery('.image_add_row .item_thumb').change(function () {
		var img = jQuery(this).val();
		if (img != '') {
			jQuery(this).siblings('.item_img').empty().html('<img alt="" src="'+img+'">');
		}
	});
	jQuery('.image_add_row .mediamanager_reset').click(function (e) {
		jQuery(this).siblings('.item_thumb').val('');
		jQuery(this).siblings('.item_img').empty();
		e.preventDefault();
		return false;
	});
});

function change_icon_holder(th) {
	var iconClass = th.val();
	th.next().html('<i class="icon_item_class '+iconClass+'"></i>');
}

function menu_type_select(th) {
	var type_val = th.val(); //get current val onload and on chage
	var panel = th.parent().find('.auto_options_panel');

	//if 'auto' we need to show panel
	if(type_val === 'auto') {
		//ta-da!
		panel.slideDown();
	}
	else {
		panel.slideUp();
	}
}
