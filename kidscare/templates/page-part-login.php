<?php if (!is_user_logged_in() && get_theme_option('show_login')=='yes') { ?>
	<div id="user-popUp" class="user-popUp mfp-with-anim mfp-hide">
		<div class="sc_tabs">
			<ul class="loginHeadTab">
				<li><a href="#loginForm" class="loginFormTab icon"><?php _e('Log In', 'themerex'); ?></a></li>
				<li><a href="#registerForm" class="registerFormTab icon"><?php _e('Create an Account', 'themerex'); ?></a></li>
			</ul>
			
			<div id="loginForm" class="formItems loginFormBody">
				<div class="itemformLeft">
					<form action="<?php echo wp_login_url(); ?>" method="post" name="login_form" class="formValid">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr(home_url()); ?>" />
						<ul class="formList">
							<li class="icon formLogin"><input type="text" id="login" name="log" value="" placeholder="<?php _e('Login', 'themerex'); ?>"></li>
							<li class="icon formPass"><input type="password" id="password" name="pwd" value="" placeholder="<?php _e('Password', 'themerex'); ?>"></li>
							<li class="remember">
								<a href="<?php echo esc_url(wp_lostpassword_url( get_permalink() )); ?>" class="forgotPwd"><?php _e('Forgot password?', 'themerex'); ?></a>
								<input type="checkbox" value="forever" id="rememberme" name="rememberme">
								<label for="rememberme"><?php _e('Remember me', 'themerex'); ?></label>
							</li>
							<li><a href="#" class="sendEnter enter"><?php _e('Login', 'themerex'); ?></a></li>
						</ul>
					</form>
				</div>

				<div class="itemformRight">
					<ul class="formList">
						<li><?php _e('You can login using your social profile', 'themerex'); ?></li>
						<li class="loginSoc">
							<a href="#" class="iconLogin fb"></a>
							<a href="#" class="iconLogin tw"></a>
							<a href="#" class="iconLogin gg"></a>
						</li>
						<li><a href="#" class="loginProblem"><?php _e('Problem with login?', 'themerex'); ?></a></li>
					</ul>
				</div>
				<div class="result messageBlock"></div>
			</div>

			<div id="registerForm" class="formItems registerFormBody">
				<form name="register_form" method="post" class="formValid">
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr(home_url()); ?>"/>
					<div class="itemformLeft">
						<ul class="formList">
							<li class="icon formUser"><input type="text" id="registration_username" name="registration_username"  value="" placeholder="<?php _e('User name (login)', 'themerex'); ?>"></li>
							<li class="icon formLogin"><input type="text" id="registration_email" name="registration_email" value="" placeholder="<?php _e('E-mail', 'themerex'); ?>"></li>
							<li class="i-agree">
								<input type="checkbox" value="forever" id="i-agree" name="i-agree">
								<label for="i-agree"><?php _e('I agree with', 'themerex'); ?></label> <a href="<?php echo get_theme_option('footer_terms_link'); ?>"><?php _e('Terms &amp; Conditions', 'themerex'); ?></a>
							</li>
							<li><a href="" class="sendEnter enter"><?php _e('Sign Up', 'themerex'); ?></a></li>
						</ul>
					</div>
					<div class="itemformRight">
						<ul class="formList">
							<li class="icon formPass"><input type="password" id="registration_pwd" name="registration_pwd" value="" placeholder="<?php _e('Password', 'themerex'); ?>"></li>
							<li class="icon formPass"><input type="password" id="registration_pwd2" name="registration_pwd2" value="" placeholder="<?php _e('Confirm Password', 'themerex'); ?>"></li>
						</ul>
						<div class="formDescription"><?php _e('Minimum 6 characters', 'themerex'); ?></div>
					</div>
				</form>
				<div class="result messageBlock"></div>
			</div>

		</div>	<!-- /.sc_tabs -->
	</div>		<!-- /.user-popUp -->
<?php } ?>
