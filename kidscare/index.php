<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header();

global $THEMEREX_only_reviews, $THEMEREX_only_video, $THEMEREX_only_audio, $THEMEREX_only_gallery;
global $wp_query, $post;

$blog_style = get_custom_option('blog_style');
$show_sidebar_main = get_custom_option('show_sidebar_main');
$ppp = (int) get_custom_option('posts_per_page');

$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
$wp_query_need_restore = false;

$args = $wp_query->query_vars;
$args['post_status'] = current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish';

if ( is_page() || isset($THEMEREX_only_reviews) || isset($THEMEREX_only_video) || isset($THEMEREX_only_audio) || isset($THEMEREX_only_gallery) ) {
    $args['post_type'] = 'post';
    unset($args['p']);
    unset($args['page_id']);
    unset($args['pagename']);
    unset($args['name']);
    $args['posts_per_page'] = $ppp;
    if ($page_number > 1) {
        $args['paged'] = $page_number;
        $args['ignore_sticky_posts'] = 1;
    }
    $filters = array();
    if (isset($THEMEREX_only_reviews)) 		$filters[] = 'reviews';
    else if (isset($THEMEREX_only_video))	$filters[] = 'video';
    else if (isset($THEMEREX_only_audio))	$filters[] = 'audio';
    else if (isset($THEMEREX_only_gallery))	$filters[] = 'gallery';
    $args = addSortOrderInQuery($args);
    $args = addFiltersInQuery($args, $filters);
    query_posts( $args );
    $wp_query_need_restore = true;
}

$per_page = count($wp_query->posts);

$post_number = 0;

$parent_cat_id = (int) get_custom_option('category_id');
$accent_color = '';

$flt_ids = array();

if (themerex_strpos($blog_style, 'masonry')!==false || themerex_strpos($blog_style, 'classic')!==false) {
    ?>
    <div class="masonryWrap">
    <?php if (get_custom_option('show_filters')=='yes') { ?>
        <div class="isotopeFiltr"></div>
    <?php } ?>
    <section class="masonry <?php echo get_custom_option('show_filters')=='yes' ? 'isotope' :  'isotopeNOanim'; ?>" data-columns="<?php echo themerex_substr($blog_style, -1); ?>">
<?php
} else if (themerex_strpos($blog_style, 'portfolio')!==false) {
    ?>
    <div class="portfolioWrap">
    <?php if (get_custom_option('show_filters')=='yes') { ?>
        <div class="isotopeFiltr"></div>
    <?php } ?>
    <section class="portfolio <?php echo get_custom_option('show_filters')=='yes' ? 'isotope' :  'isotopeNOanim'; ?> folio<?php echo themerex_substr($blog_style, -1); ?>col" data-columns="<?php echo themerex_substr($blog_style, -1); ?>">
<?php
}

while ( have_posts() ) { the_post();

    $post_number++;

    $post_args = array(
        'layout' => in_array(themerex_substr($blog_style, 0, 7), array('classic', 'masonry', 'portfol')) ? themerex_substr($blog_style, 0, 7) : $blog_style,
        'number' => $post_number,
        'add_view_more' => false,
        'posts_on_page' => $per_page,
        // Get post data
        'thumb_size' => $blog_style,
        'thumb_crop' => themerex_strpos($blog_style, 'masonry')===false,
        'strip_teaser' => false,
        'parent_cat_id' => $parent_cat_id,
        'sidebar' => !in_array($show_sidebar_main, array('none', 'fullwidth')),
        'filters' => get_custom_option('show_filters')=='yes' ? get_custom_option('filter_taxonomy') : '',
        'hover' => get_custom_option('hover_style'),
        'hover_dir' => get_custom_option('hover_dir')
    );
    $post_data = getPostData($post_args);
    showPostLayout($post_args, $post_data);

    if (get_custom_option('show_filters')=='yes') {
        if (get_custom_option('filter_taxonomy')=='tags') {			// Use tags as filter items
            if (count($post_data['post_tags_list']) > 0) {
                foreach ($post_data['post_tags_list'] as $tag) {
                    $flt_ids[$tag->term_id] = $tag->name;
                }
            }
        }
    }
}

if (!$post_number) {
    if ( is_search() ) {
        showPostLayout( array('layout' => 'no-search-results'), false );
    } else {
        showPostLayout( array('layout' => 'no-articles'), false );
    }
} else {
    // Isotope filters list
    $ppp = (int) get_custom_option('posts_per_page');
    $filters = '';
    if (get_custom_option('show_filters')=='yes') {
        if (get_custom_option('filter_taxonomy')=='categories') {			// Use categories as filter items
            $cat_id = (int) get_query_var('cat');
            $portfolio_parent = max(0, is_category() ? getParentCategoryByProperty($cat_id, 'show_filters', 'yes') : 0);
            $args2 = array(
                'type'                     => 'post',
                'child_of'                 => $portfolio_parent,
                'orderby'                  => 'name',
                'order'                    => 'ASC',
                'hide_empty'               => 1,
                'hierarchical'             => 0,
                'exclude'                  => '',
                'include'                  => '',
                'number'                   => '',
                'taxonomy'                 => 'category',
                'pad_counts'               => false );
            $portfolio_list = get_categories($args2);
            if (count($portfolio_list) > 0) {
                $filters .= '<li class="squareButton'.($portfolio_parent==$cat_id ? ' active' : '').'"><a href="#" data-filter="*">'.__('All', 'themerex').'</a></li>';
                foreach ($portfolio_list as $cat) {
                    $filters .= '<li class="squareButton'.($cat->term_id==$cat_id ? ' active' : '').'"><a href="#" data-filter=".flt_'.$cat->term_id.'">'.$cat->name.'</a></li>';
                }
            }
        } else {															// Use tags as filter items
            if (count($flt_ids) > 0) {
                $filters .= '<li class="squareButton active"><a href="#" data-filter="*">'.__('All', 'themerex').'</a></li>';
                foreach ($flt_ids as $flt_id=>$flt_name) {
                    $filters .= '<li class="squareButton"><a href="#" data-filter=".flt_'.$flt_id.'">'.$flt_name.'</a></li>';
                }
            }
        }
        if ($filters) {
            $filters = '<ul>' . $filters . '</ul>';
            ?>
            <script type="text/javascript">
                var ppp = <?php echo (int)$ppp; ?>;
                jQuery(document).ready(function () {
                    jQuery(".isotopeFiltr").append('<?php echo balanceTags($filters); ?>');
                });
            </script>
        <?php
        }
    }
}

if (themerex_strpos($blog_style, 'masonry')!==false || themerex_strpos($blog_style, 'classic')!==false || themerex_strpos($blog_style, 'portfolio')!==false) {
    // todo: Load Isotope
    themerex_enqueue_script( 'isotope', themerex_get_file_url('/js/jquery.isotope.min.js'), array(), null, true );
    ?>
    </section>
    </div>
<?php
}

// magnific & pretty
themerex_enqueue_style('magnific-style', themerex_get_file_url('/js/magnific-popup/magnific-popup.min.css'), array(), null);
themerex_enqueue_script( 'magnific', themerex_get_file_url('/js/magnific-popup/jquery.magnific-popup.min.js'), array('jquery'), null, true );
// Load PrettyPhoto if it selected in Theme Options
if (get_theme_option('popup_engine')=='pretty') {
    themerex_enqueue_style(  'prettyphoto-style', themerex_get_file_url('/js/prettyphoto/css/prettyPhoto.css'), array(), null );
    themerex_enqueue_script( 'prettyphoto', themerex_get_file_url('/js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
}

// Media elements library
if (get_theme_option('use_mediaelement')=='yes') {
    if (floatval(get_bloginfo('version')) < "3.6") {
        themerex_enqueue_style(  'mediaplayer-style',  themerex_get_file_url('/js/mediaplayer/mediaplayer.css'), array(), null );
        themerex_enqueue_script( 'mediaplayer', themerex_get_file_url('/js/mediaplayer/mediaelement.min.js'), array(), null, true );
    } else {
        wp_enqueue_style ( 'mediaelement' );
        wp_enqueue_style ( 'wp-mediaelement' );
        wp_enqueue_script( 'mediaelement' );
        wp_enqueue_script( 'wp-mediaelement' );
    }
} else {
    global $wp_scripts;
    $wp_scripts->done[] = 'mediaelement';
    $wp_scripts->done[] = 'wp-mediaelement';
    $wp_styles->done[] = 'mediaelement';
    $wp_styles->done[] = 'wp-mediaelement';
}

if ($post_number > 0) {
    // Pagination
    $pagination = get_custom_option('blog_pagination');
    if (in_array($pagination, array('viewmore', 'infinite'))) {
        if ($page_number < $wp_query->max_num_pages) {
            ?>
			<div id="viewmore" class="squareButton pagination_<?php echo esc_attr($pagination); ?>">
				<a href="#" id="viewmore_link" class="theme_button view_more_button"><span class="icon-spin3 animate-spin viewmore_loading"></span><span class="viewmore_text_1"><?php _e('View more', 'themerex'); ?></span><span class="viewmore_text_2"><?php _e('Loading ...', 'themerex'); ?></span></a>
				<script type="text/javascript">
					var THEMEREX_VIEWMORE_PAGE = <?php echo (int)$page_number; ?>;
					var THEMEREX_VIEWMORE_DATA = '<?php echo serialize($args); ?>';
					var THEMEREX_VIEWMORE_VARS = '<?php echo serialize(array(
                'blog_style' => $blog_style,
                'show_sidebar_main' => $show_sidebar_main,
                'parent_cat_id' => $parent_cat_id,
                'filters' => get_custom_option('show_filters')=='yes' ? get_custom_option('filter_taxonomy') : '',
                'hover' => get_custom_option('hover_style'),
                'hover_dir' => get_custom_option('hover_dir'),
                'ppp' => $ppp
            )); ?>';
				</script>
			</div>
			<?php
        }
    } else {
        showPagination(array(
                'class' => 'pagination',
                'style' => get_theme_option('blog_pagination_style'),
                'button_class' => 'squareButton light',
                'pages_in_group' => get_theme_option('blog_pagination_style')=='pages' ? 10 : 20
            )
        );
    }
}

if ( $wp_query_need_restore ) wp_reset_query();
wp_reset_postdata();

get_footer();
?>