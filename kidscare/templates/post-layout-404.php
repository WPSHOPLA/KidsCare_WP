<?php
/*
 * The template for displaying "Page 404"
*/
?>
<section class="post no_margin">
	<article class="post_content">
		<div class="page404">
			<div class="titleError"><?php _e( '404', 'themerex' ); ?></div>
			<div class="h2"><?php _e('The requested page cannot be found', 'themerex'); ?></div>
			<p><?php echo sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'themerex'), home_url(), get_bloginfo()); ?>
			<br><?php _e('Please report any broken links to our team.', 'themerex'); ?></p>
			<div class="inputSubmitAnimation radCircle">
				<form class="search_form" action="<?php echo home_url(); ?>" method="get">
				<input type="text" class="sInput" name="s" value="" placeholder="<?php _e('What are you searching for?', 'themerex'); ?>">
				</form>
				<a href="#" class="searchIcon aIco search" title="<?php _e('Search', 'themerex'); ?>"></a>
			</div>
		</div>
	</article>
</section>
