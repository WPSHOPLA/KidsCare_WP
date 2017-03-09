// Login & registration link
jQuery(document).ready(function() {
	"use strict";
	

	jQuery('#loginForm .sendEnter').click(function(e){
		"use strict";
		jQuery('#loginForm form input').removeClass('msError');
		var error = formValidate(jQuery('#loginForm form'), {
			error_message_show: true,
			error_message_time: 4000,
			error_message_class: 'sc_infobox sc_infobox_style_error',
			error_fields_class: 'msError',
			exit_after_first_error: true,
			rules: [
				{
					field: "log",
					min_length: { value: 1, message: THEMEREX_LOGIN_EMPTY},
					max_length: { value: 60, message: THEMEREX_LOGIN_LONG}
				},
				{
					field: "pwd",
					min_length: { value: 4, message: THEMEREX_PASSWORD_EMPTY},
					max_length: { value: 20, message: THEMEREX_PASSWORD_LONG}
				}
			]
		});
		if (!error) {
			document.forms.login_form.submit();
		}
		e.preventDefault();
		return false;
	});
	
	jQuery('#registerForm .sendEnter').click(function(e){
		"use strict";
		jQuery('#registerForm form input').removeClass('msError');
		var error = formValidate(jQuery("#registerForm form"), {
			error_message_show: true,
			error_message_time: 4000,
			error_message_class: "sc_infobox sc_infobox_style_error",
			error_fields_class: "msError",
			exit_after_first_error: true,
			rules: [
				{
					field: "registration_username",
					min_length: { value: 1, message: THEMEREX_LOGIN_EMPTY },
					max_length: { value: 60, message: THEMEREX_LOGIN_LONG }
				},
				{
					field: "registration_email",
					min_length: { value: 7, message: THEMEREX_EMAIL_EMPTY },
					max_length: { value: 60, message: THEMEREX_EMAIL_LONG },
					mask: { value: "^([a-z0-9_\\-]+\\.)*[a-z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$", message: THEMEREX_EMAIL_NOT_VALID }
				},
				{
					field: "registration_pwd",
					min_length: { value: 4, message: THEMEREX_PASSWORD_EMPTY },
					max_length: { value: 20, message: THEMEREX_PASSWORD_LONG }
				},
				{
					field: "registration_pwd2",
					equal_to: { value: 'registration_pwd', message: THEMEREX_PASSWORD_NOT_EQUAL }
				}
			]
		});
		if (!error) {
			jQuery.post(THEMEREX_ajax_url, {
				action: 'registration_user',
				nonce: THEMEREX_ajax_nonce,
				user_name: 	jQuery('#registerForm #registration_username').val(),
				user_email: jQuery('#registerForm #registration_email').val(),
				user_pwd: 	jQuery('#registerForm #registration_pwd').val(),
			}).done(function(response) {
				var rez = JSON.parse(response);
				var result_box = jQuery('#registerForm .result');
				result_box.toggleClass('sc_infobox_style_error', false).toggleClass('sc_infobox_style_success', false);
				if (rez.error === '') {
					result_box.addClass('sc_infobox_style_success').html(THEMEREX_REGISTRATION_SUCCESS);
					setTimeout(function() { 
						jQuery('.user-popUp .loginFormTab').trigger('click'); 
						}, 2000);
				} else {
					result_box.addClass('sc_infobox_style_error').html(THEMEREX_REGISTRATION_FAILED + ' ' + rez.error);
				}
				result_box.fadeIn();
				setTimeout(function() { jQuery('#registerForm .result').fadeOut(); }, 2000);
			});
		}
		e.preventDefault();
		return false;
	});
	
});