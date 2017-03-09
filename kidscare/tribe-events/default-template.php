<?php
/*
Template Name: Tribe Events
*/

if ( !defined('ABSPATH') ) { die('-1'); }

get_header(); ?>

<section class="post tribe_events_wrapper">
	<article class="post_content">
		<div id="tribe-events-pg-template">
			<?php tribe_events_before_html(); ?>
			<?php tribe_get_view(); ?>
			<?php tribe_events_after_html(); ?>
		</div> <!-- #tribe-events-pg-template -->
	</article><!-- .post_content -->
</section>

<?php get_footer(); ?>