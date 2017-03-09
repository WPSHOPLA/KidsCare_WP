<?php
//===================================== Comments =====================================
if (get_custom_option("show_post_comments") == 'yes') {
	if ( comments_open() || get_comments_number() != 0 ) {
		comments_template();
	}
}
?>
