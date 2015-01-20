<?php

/*
Plugin Name: Code-Architect Popular Post
Plugin URI: http://github.com/code-architect/popular-post-widget
Description: Shows most 5 popular post in the Website, in a widget and also displays hit counter in the All Post page.
Version: 1.0
Author: Code Architect
Author URI: http://codearchitect.in/
License: GPL2
*/


/**
 * Post popularity counter
 */
 function my_popular_viewed_post($postID){
     $total_key = 'views';

     // Get current 'views' field
     $total = get_post_meta($postID, $total_key, true);

     // if current 'views' field is empty, set it to zero
     if($total == ''){
         delete_post_meta($postID, $total_key);
         add_post_meta($postID, $total_key, '0');
     }else{
         // if current 'views' field has value, add 1 to that value
         $total++;
         update_post_meta($postID,$total_key, $total);
     }

 }
/**
 * Dynamically inject counter into single posts
 */
 function my_counter_popular_posts($post_id) {

     // Check that this is a single post, and the user is a visitor
     if(!is_single()) return;

     if(!is_user_logged_in()) {
         // Get the post ID
         if(empty($post_id)){
             global $post;
             $post_id = $post->ID;
         }
         // Run post popularity counter on post
         my_popular_viewed_post($post_id);
     }
 }

add_action( 'wp_head', 'my_counter_popular_posts' );
//**************************************************************//


/**
 * Add popular  post function data to All Posts table
 * @param $defaults Array of column that are being displayed on All Posts page
 * @return mixed Return the modified column array
 */
function my_add_views_column($defaults){
    // Adding column to All Posts table page
    $defaults['post_views'] = 'View Count';
    return $defaults;
}

add_filter('manage_posts_columns', 'my_add_views_column');


function my_display_views($column_name){
    if($column_name === 'post_views'){
        echo (int) get_post_meta( get_the_ID(), 'views', true);
    }
}
add_action('manage_posts_custom_column', 'my_display_views',5,2);


/************************************************************************************
 *****************************  Popular Post Widget  ********************************
 ************************************************************************************/


/**
 * Popular Posts Widget.
 */
class Popular_Posts extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        // Localizing our human readable strings
        add_action('init', array( $this, 'widget_text_domain'));

        parent::__construct(
            'popular_posts', // Base ID
            __( 'Popular Posts', 'popular-posts' ), // Name
            array( 'description' => __( 'Displays 5 top visited posts', 'popular-posts' ), ) // Args
        );

    }



    /**
     * Back-end widget form.
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $instance = wp_parse_args(
            (array)$instance,
            array(
                'title'          => '',
                'sticky-post'    => 'yes'
            )
        );

        include(plugin_dir_path(__FILE__).'/views/admin.php');
    }



    /**
     * Sanitize widget form values as they are saved.
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $old_instance['title'] = strip_tags(stripcslashes($new_instance['title']));
        $old_instance['sticky-post'] = $new_instance['sticky-post'];
        return $old_instance;
    }



    /**
     * Front-end display of widget.
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }


        include(plugin_dir_path(__FILE__).'/views/posts_loop.php');
        echo $args['after_widget'];
    }




//------------------------------ Registering functions ------------------------------------------------//

    /**
     * Localizing strings using poedit
     */
    function widget_text_domain(){
        load_plugin_textdomain( 'popular-posts', false, plugin_dir_path(__FILE__).'/lang/' );
    }


} // class popular_posts







// register Post popularity counter widget
function register_popular_posts_widget() {
    register_widget( 'Popular_Posts' );
}
add_action( 'widgets_init', 'register_popular_posts_widget' );









