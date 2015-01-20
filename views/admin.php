<?php
/**
 * User: Code-Architect
 * Date: 20-Jan-15
 * Time: 4:29 PM
 */
?>
<div class="popular-posts">

    <p>
        <label class="widefat"><?php _e( 'Display Title', 'popular-posts' ) ?></label>
        <input
            class="widefat"
            type="text"
            id="<?php echo $this->get_field_id('title'); ?>"
            name="<?php echo $this->get_field_name('title'); ?>"
            value="<?php echo esc_attr($instance['title']); ?>"
            />
    </p>

    <p>
        <label class="widefat"><?php _e('Include sticky posts', 'popular-posts'); ?></label>
        <select
            id="<?php echo $this->get_field_id('sticky-post'); ?>"
            name="<?php echo $this->get_field_name('sticky-post'); ?>"
        >
           <option value="yes" <?php selected('yes', $instance['sticky-post'], true); ?>><?php _e('YES', 'popular-posts'); ?></option>
            <option value="no" <?php selected('no', $instance['sticky-post'], true); ?>><?php _e('NO', 'popular-posts'); ?></option>
        </select>
    </p>


</div>
