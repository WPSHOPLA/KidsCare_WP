<?php
/* Reviews support functions
------------------------------------------------------------------------------- */

// Get reviews criterias list from categories list (ids)
add_action('wp_ajax_check_reviews_criterias', 'themerex_callback_check_reviews_criterias');
add_action('wp_ajax_nopriv_check_reviews_criterias', 'themerex_callback_check_reviews_criterias');
if ( !function_exists( 'themerex_callback_check_reviews_criterias' ) ) {
	function themerex_callback_check_reviews_criterias() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'', 'criterias' => '');
		
		$ids = explode(',', $_REQUEST['ids']);
		if (count($ids) > 0) {
			foreach ($ids as $id) {
				$id = (int) $id;
				$prop = get_category_inherited_property($id, 'reviews_criterias');
				if (!empty($prop) && !is_inherit_option($prop)) {
					$response['criterias'] = implode(',', $prop);
					break;
				}
			}
		}
		
		echo json_encode($response);
		die();
	}
}

// Accept user's votes
add_action('wp_ajax_reviews_users_accept', 'themerex_callback_reviews_users_accept');
add_action('wp_ajax_nopriv_reviews_users_accept', 'themerex_callback_reviews_users_accept');
if ( !function_exists( 'themerex_callback_reviews_users_accept' ) ) {
	function themerex_callback_reviews_users_accept() {
		global $_REQUEST;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce' ) )
			die();
	
		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];
		if ($post_id > 0) {
			$marks = $_REQUEST['marks'];
			$users = $_REQUEST['users'];
			$avg = getReviewsRatingAverage($marks);
			update_post_meta($post_id, 'reviews_marks2', marksToSave($marks));
			update_post_meta($post_id, 'reviews_avg2', marksToSave($avg));
			update_post_meta($post_id, 'reviews_users', $users);
		} else {
			$response['error'] = __('Bad post ID', 'themerex');
		}
		
		echo json_encode($response);
		die();
	}
}

// Get average review rating
if ( !function_exists( 'getReviewsRatingAverage' ) ) {
	function getReviewsRatingAverage($marks) {
		$r = explode(',', $marks);
		$rez = 0;
		$cnt = 0;
		foreach ($r as $v) {
			$rez += (int) $v;
			$cnt++;
		}
		return $cnt > 0 ? round($rez / $cnt, 1) : 0;
	}
}

// Get word-value review rating
if ( !function_exists( 'getReviewsRatingWordValue' ) ) {
	function getReviewsRatingWordValue($r, $words = '') {
		$maxLevel = max(5, (int) get_custom_option('reviews_max_level'));
		if (trim($words) == '') $words = get_theme_option('reviews_criterias_levels');
		$words = explode(',', $words);
		$k = $maxLevel / count($words);
		$r = max(0, min(count($words)-1, floor($r/$k)));
		return isset($words[$r]) ? trim($words[$r]) : __('no rated', 'themerex');
	}
}

// Return Reviews markup html-block
if ( !function_exists( 'getReviewsMarkup' ) ) {
	function getReviewsMarkup($field, $value, $editable=false, $clear=false, $snippets=false) {
		$maxLevel = max(5, (int) get_custom_option('reviews_max_level'));
		$output = '';
		$criterias = $field['options'];
		$marks = explode(',', $value);
		if (is_array($criterias) && count($criterias) > 0) {
			$i=0;
			foreach ($criterias as $num=>$sb) { 
				if (empty($sb)) continue;
				if ($clear || !isset($marks[$i]) || $marks[$i]=='' || is_inherit_option($marks[$i])) $marks[$i] = 0;
				$output .= '<div class="ratBody">'
					. getReviewsSummaryStars($marks[$i], $editable)
					. '<span class="name">'.$sb.'</span>'
					.'</div>';
				$i++;
			}
		}
		$output .= isset($field['accept']) && $field['accept'] ? '<div class="accept"><div class="squareButton global"><a href="#" title="'.__('Accept your votes', 'themerex').'">'.__('Accept', 'themerex').'</a></div></div>' : '';
		$avg = getReviewsRatingAverage($value);
		$output .= '
            <div class="infoTotal">
                <div class="infoRat">'.(isset($field['descr']) ? $field['descr'] : '').'</div>
                <div class="totalWrap">
                    <div class="totalStar">
						' . getReviewsSummaryStars($avg, false, $snippets) . '
                    </div>
                    <div class="totalRating" data-mark="'.$avg.'">'.$avg.(themerex_strlen($avg)==1 ? '.0' : '').'</div>
                </div>
            </div>
		';
		return $output;
	}
}

// Return Reviews summary stars html-block
if ( !function_exists( 'getReviewsSummaryStars' ) ) {
	function getReviewsSummaryStars($avg, $editable=false, $snippets=false) {
		$maxLevel = max(5, (int) get_custom_option('reviews_max_level'));
		$output = '<div class="ratingItem' . ($editable ? ' ratingEdit' : '') . '"'
			. ' data-mark="'.$avg.'"'
			. ($snippets ? ' itemscope itemprop="reviewRating" itemtype="http://schema.org/Rating"' : '')
			. '>'
			. ($snippets ? '<meta itemprop="worstRating" content="0"><meta itemprop="bestRating" content="'.$maxLevel.'"><meta itemprop="ratingValue" content="'.$avg.'">' : '');
		if (!$editable && get_theme_option('reviews_style')=='text') {
			$output .= sprintf($maxLevel<100 ? __('%s / %s', 'themerex') : __('%s', 'themerex'), number_format($avg,1).($maxLevel < 100 ? '' : '%'), $maxLevel.($maxLevel < 100 ? '' : '%'));
		} else {
			$stars = '<span class="starReview"></span><span class="starReview"></span><span class="starReview"></span><span class="starReview"></span><span class="starReview"></span>';
			$output .= ($editable ? '<span class="ratBubble">' . $avg . '</span>' : '')
				. '<div class="starDefault">' . $stars . '</div>'
				. '<div class="starHover" style="width:'.($avg/$maxLevel*100).'%">' . $stars . '</div>';
		}
		if ($editable)	$output .= '<input type="hidden" name="reviews_marks[]" value="'. $avg . '" />';
		$output .= '</div>';
		return $output;
	}
}


// Prepare rating marks before first using
if ( !function_exists( 'marksPrepare' ) ) {
	function marksPrepare($marks, $cnt) {
		$m = explode(',', $marks);
		for ($i=0; $i < $cnt; $i++) {
			if (!isset($m[$i]))
				$m[$i] = 0;
			else
				$m[$i] = max(0, $m[$i]);
		}
		return implode(',', $m);
	}
}


// Prepare rating marks to save
if ( !function_exists( 'marksToSave' ) ) {
	function marksToSave($marks) {
		$maxLevel = max(5, (int) get_custom_option('reviews_max_level'));
		if ($maxLevel == 100) return $marks;
		$m = explode(',', $marks);
		$kol = count($m);
		for ($i=0; $i < $kol; $i++) {
			$m[$i] = round($m[$i] * 100 / $maxLevel, 1);
		}
		return implode(',', $m);
	}
}


// Prepare rating marks to display
if ( !function_exists( 'marksToDisplay' ) ) {
	function marksToDisplay($marks) {
		$maxLevel = max(5, (int) get_custom_option('reviews_max_level'));
		if ($maxLevel == 100) return $marks;
		$m = explode(',', $marks);
		$kol = count($m);
		for ($i=0; $i < $kol; $i++) {
			$m[$i] = round($m[$i] / 100 * $maxLevel, 1);
		}
		return implode(',', $m);
	}
}
?>