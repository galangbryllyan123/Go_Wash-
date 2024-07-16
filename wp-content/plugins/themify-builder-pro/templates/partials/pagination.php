<?php
if ($args['pagination_option'] === 'numbers') {
    echo self::get_pagination('', '', $args['query']);
}elseif ($args['pagination_option'] === 'ajax') {
    if($args['query']->max_num_pages > $args['paged']) {
        Themify_Enqueue_Assets::loadinfiniteCss();
        $url=next_posts($args['query']->max_num_pages, false);
        echo '<p class="tf_load_more tf_textc tf_clear"><a data-url="' . $url . '" data-id="' . $args['el_id'] . '" href="' . $url . '" data-page="' . esc_attr($args['paged']) . '" class="load-more-button">' . __('Load More', 'tbp') . '</a></p>';
    }
} else {
?>
    <div class="pagenav pagenav-prev-next tf_clearfix">
        <?php
        if (Tbp_Utils::$isActive === true) {
          global $wp_query;
          $temp = clone $wp_query;
          $wp_query = $args['query'];
        }
        echo get_next_posts_link($args['next_link'], $args['query']->max_num_pages), get_previous_posts_link($args['prev_link']);
        if (Tbp_Utils::$isActive === true) {
            $wp_query = $temp;
            $temp = null;
        }
    ?>
    </div>
<?php
}
$args = null;

