<?php
/*
 * The template for displaying "No search results"
*/
?>
<section class="post no_margin">
	<article class="post_content">
		<div class="page404">
			<div class="h2"><?php _e('Search Results for:', 'themerex'); ?></div>
			<div class="h1"><?php echo get_search_query(); ?></div>
			<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'themerex' ); ?></p>
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
