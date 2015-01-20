<?php
/**
 * User: Code-Architect
 * Date: 20-Jan-15
 * Time: 4:01 PM
 */
$sticky_post_value = '';
( 'yes' == $instance['sticky-post'] )? $sticky_post_value = true : $sticky_post_value = false;

$query_args = array(
    'post_type'             => 'post',                  // We will only get posts
    'posts_per_page'        => 5,                       // 5 posts
    'meta_key'              => 'views',                 // i.e we are only looking at post that have the meta key
    'orderby'               => 'meta_value_num',       // Ordering it by numeric value of meta key field views
    'order'                 => 'DESC',
    'ignore_sticky_posts'   => $sticky_post_value
);

$query = new WP_Query($query_args);

//The loop
if($query->have_posts()) {
    echo '<ul>';
    while ($query->have_posts()) {
        $query->the_post();
        echo '<li>';
        echo '<a href="'.get_the_permalink().'" rel="bookmark">';
        echo get_the_title();
        echo ' ('.get_post_meta(get_the_ID(), 'views', true).'}';
        echo '</a>';
        echo '</li>';
    }
    echo '</ul>';
}else{
        _e('No Posts Found', 'popular-posts');
    }
// Restore original WP_Query
wp_reset_query();