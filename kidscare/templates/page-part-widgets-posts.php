<?php
global $post;
$post_id = $post->ID;
$post_date = getDateOrDifference($post->post_date);
$post_title = $post->post_title;
$post_link = get_permalink($post_id);

$output .= '
	<article class="post_item' . ($show_image == 0 ? ' no_thumb' : ' with_thumb') . ($post_number==1 ? ' first' : '') . '">
';

if ($show_image) {
	$post_thumb = getResizedImageTag($post_id, 75, 75);
	if ($post_thumb) {
		$output .= '
			<div class="post_thumb">' . $post_thumb . '</div>
		';
	}
}
$output .= '
			<h5 class="post_title"><a href="' . $post_link . '">' . $post_title . '</a></h5>
';
if ($show_counters) {
	if ($show_counters=='views') {
		$post_counters = getPostViews($post_id);
		$post_counters_icon = 'eye';
	} else if ($show_counters=='likes')	{
		$post_counters = getPostLikes($post_id);
		$post_counters_icon = 'heart-1';
	} else if ($show_counters=='stars' || $show_counters=='rating') {
		$post_counters = marksToDisplay(get_post_meta($post_id, $post_rating, true));
		$post_counters_icon = 'star-1';
	} else {
		$post_counters = get_comments_number($post_id);
		$post_counters_icon = 'comment-1';
	}

	if ($show_counters=='stars' && $post_counters > 0) {
		if (themerex_strpos($post_counters, '.')===false) 
			$post_counters .= '.0';
		if (get_custom_option('show_reviews')=='yes') {
			$output .= '<div class="summaryRating">' . getReviewsSummaryStars($post_counters, false, false) . '</div>';
		}
	}
}
if ($show_date || $show_counters || $show_author) {
	$output .= '
			<div class="post_info">
	';
	if ($show_date) {
		$output .= '
				<span class="post_date">' . $post_date . '</span>
				';
	}
	if ($show_author) {
		$post_author_id   = $post->post_author;
		$post_author_name = get_the_author_meta('display_name', $post_author_id);
		$post_author_url  = get_author_posts_url($post_author_id, '');
		$output .= '
				<span class="post_author">' . __('by', 'themerex') . ' <a href="' . $post_author_url . '">' . $post_author_name . '</a></span>
				';
	}
	if ($show_counters && $show_counters!='stars') {
		$post_counters_link = $show_counters=='comments' ? get_comments_link( $post_id ) : $post_link;
		$output .= '
				<span class="post_comments">
					<a href="'.$post_counters_link.'">
						<span class="comments_icon icon-' . $post_counters_icon . '"></span>
						<span class="post_comments_number">' . $post_counters . '</span>
					</a>
				</span>
				';
	}
}
$output .= '
			</div>
		</article>
';
?>
