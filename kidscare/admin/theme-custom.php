<?php
// Show theme specific fields in Post (and Page) options
function show_custom_field($field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviewBlock"><div class="ratingStars">' . getReviewsMarkup($field, $value, true) . '</div></div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.$field['id'].'" class="button mediamanager"
				data-choose="'.(isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'themerex') : __( 'Choose Image', 'themerex')).'"
				data-update="'.(isset($field['multiple']) && $field['multiple'] ? __( 'Add to Gallery', 'themerex') : __( 'Choose Image', 'themerex')).'"
				data-multiple="'.(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.$field['media_field_id'].'"
				onclick="showMediaManager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? __( 'Choose Images', 'themerex') : __( 'Choose Image', 'themerex')) . '</a>';
			break;
	}
	return $output;
}
?>