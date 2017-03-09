<?php
// Reviews block
global $THEMEREX_REVIEWS_RATING;
$THEMEREX_REVIEWS_RATING = '';
if ($avg_author > 0 || $avg_users > 0) {
	$reviews_first_author = get_theme_option('reviews_first')=='author';
	$reviews_second_hide = get_theme_option('reviews_second')=='hide';
	$use_tabs = !$reviews_second_hide; // && $avg_author > 0 && $avg_users > 0;
	if ($use_tabs) themerex_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
	$maxLevel = max(5, (int) get_custom_option('reviews_max_level'));
	$allowUserReviews = (!$reviews_first_author || !$reviews_second_hide) && (!isset($_COOKIE['reviews_vote']) || themerex_strpos($_COOKIE['reviews_vote'], ','.$post_data['post_id'].',')===false) && (get_theme_option('reviews_can_vote')=='all' || is_user_logged_in());
	$THEMEREX_REVIEWS_RATING = '<div class="reviewBlock'.($use_tabs ? ' sc_tabs' : '').'">';
	$output = $marks = $users = '';
	if ($use_tabs) {
		$author_tab = '<li class="squareButton"><a href="#author-tabs">'.__('Author', 'themerex').'</a></li>';
		$users_tab = '<li class="squareButton"><a href="#users-tabs">'.__('Users', 'themerex').'</a></li>';
		$output .= '<div class="popularFiltr"><ul>' . ($reviews_first_author ? $author_tab . $users_tab : $users_tab . $author_tab) . '</ul></div>';
	}
	// Criterias list
	$field = array(
		"options" => get_theme_option('reviews_criterias')
	);
	if (count($post_data['post_categories_list']) > 0) {
		foreach ($post_data['post_categories_list'] as $cat) {
			$id = (int) $cat['term_id'];
			$prop = get_category_inherited_property($id, 'reviews_criterias');
			if (!empty($prop) && !is_inherit_option($prop)) {
				$field['options'] = $prop;
				break;
			}
		}
	}
	// Author marks
	if ($reviews_first_author || !$reviews_second_hide) {
		$field["id"] = "reviews_marks_author";
		$field["descr"] = strip_tags($post_data['post_excerpt']);
		$field["accept"] = false;
		$marks = marksToDisplay(marksPrepare(get_custom_option('reviews_marks'), count($field['options'])));
		$output .= '<div class="ratingStars" id="author-tabs">' . getReviewsMarkup($field, $marks, false, false, $reviews_first_author) . '</div>';
	}
	// Users marks
	if (!$reviews_first_author || !$reviews_second_hide) {
		$marks = marksToDisplay(marksPrepare(get_post_meta($post_data['post_id'], 'reviews_marks2', true), count($field['options'])));
		$users = max(0, get_post_meta($post_data['post_id'], 'reviews_users', true));
		$field["id"] = "reviews_marks_users";
		$field["descr"] = sprintf(__("Summary rating from <b>%s</b> user's marks.", 'themerex'), $users) . ' ' 
			.(!isset($_COOKIE['reviews_vote']) || themerex_strpos($_COOKIE['reviews_vote'], ','.$post_data['post_id'].',')===false
				? __('You can set own marks for this article - just click on stars above and press "Accept".', 'themerex')
				: __('Thanks for your vote!', 'themerex'));
		$field["accept"] = $allowUserReviews;
		$output .= '<div class="ratingStars" id="users-tabs"'.(!$output ? ' style="display: block;"' : '') . '>' . getReviewsMarkup($field, $marks, $allowUserReviews, false, !$reviews_first_author) . '</div>';
	}
	$THEMEREX_REVIEWS_RATING .= $output
		. '</div>';
	if ($allowUserReviews) {
		$THEMEREX_REVIEWS_RATING .= '
			<script type="text/javascript">
				var reviews_max_level = '.$maxLevel.';
				var reviews_levels = "'.get_theme_option('reviews_criterias_levels').'";
				var reviews_vote = "'.(isset($_COOKIE['reviews_vote']) ? $_COOKIE['reviews_vote'] : '').'";
				var marks = "'.$marks.'".split(",");
				var users = '.max(0, $users).';
				var post_id = '.$post_data['post_id'].';
				var allowUserReviews = '.($allowUserReviews ? 'true' : 'false').';
			</script>
		';
	}
	if (in_array(get_custom_option('show_sidebar_main'), array('none', 'fullwidth'))) {
		echo ($THEMEREX_REVIEWS_RATING);
		$THEMEREX_REVIEWS_RATING = '';
	}
}
?>
