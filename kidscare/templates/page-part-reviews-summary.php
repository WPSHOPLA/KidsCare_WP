<?php
// Reviews summary stars
$reviewsBlock = '';
if ( !in_array($opt['style'], array('accordion_1', 'accordion_2', 'list')) && $opt['reviews'] && get_custom_option('show_reviews', null, $post_data['post_id'])=='yes' ) {
    $avg_author = $post_data['post_reviews_'.(get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
    $price_header = get_custom_option('price_header_content', null, $post_data['post_id']);
    if ($opt['style'] == 'image_classes') {
        if ($avg_author > 0) {
            $reviewsBlock .= '<div class="reviews_summary blog_reviews">'
                . '<div class="classes_price">'.$price_header.'</div>'
                . '<div class="criteria_summary criteria_row">' . getReviewsSummaryStars($avg_author) . '</div>'
                . '</div>';
        }
    }
    else {
	if ($avg_author > 0) {
		$reviewsBlock .= '<div class="reviews_summary blog_reviews">'
			. '<div class="criteria_summary criteria_row">' . getReviewsSummaryStars($avg_author) . '</div>'
			. '</div>';
	}
	}
}
?>
