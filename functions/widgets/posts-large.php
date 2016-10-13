<?php
/**
* Adds Foo_Widget widget.
*/
class Posts_Large extends WP_Widget {
 
  // Register the Widget
  public function __construct() {
    parent::__construct( 'posts-large', 'Posts Large', array( 'description' => __( 'A large post', 'text_domain' ), ));
  }
  
  // Output the content of the widget
  public function widget($args, $instance) {
    
    // Variables
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
    $category = isset($instance['category']) ? $instance['category'] : '';
    $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
    $showfeatured = empty($instance['postcount']) ? true : $instance['showfeatured'];
    
    echo $before_widget;
    
    // Display Title
    if (!empty($title)) echo $before_title . esc_attr($title) . $after_title;
    
    // Build Arguments for WP_Query
    $args = array('posts_per_page' => $postcount, 'cat' => $category,);
    
    $widget_loop = new WP_Query($args); 
    
    // Display the posts
    ?>
    <ul>
      <?php
      while ($widget_loop->have_posts()) : $widget_loop->the_post(); // The loop ?>
      <li>
        <?php
        if (has_post_thumbnail() && $showfeatured) : ?>
          <?php the_post_thumbnail('medium'); ?>
        <?php endif;?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
      </li>
      <?php endwhile; wp_reset_postdata(); ?>
    </ul>
    
    <?php
    echo $after_widget;
  }
  
  // Options form in dashboard
  public function form($instance) {
    $defaults = array('title' => '', 'category' => '', 'postcount' => '5');
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>
    
    <!-- Title -->
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title</label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>">
    </p>
    
    <!-- Category -->
    <p>
      <label for="<?php echo $this->get_field_id('category'); ?>">Select a Category</label>
      <select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
        <option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>><?php _e('All', 'mh-newsdesk-lite'); ?></option>
        <?php
        $categories = get_categories(array('type' => 'post'));
        foreach($categories as $cat) {
          echo '<option value="' . $cat->cat_ID . '"';
          if ($cat->cat_ID == $instance['category']) { echo ' selected="selected"'; }
          echo '>' . $cat->cat_name . ' (' . $cat->category_count . ')';
          echo '</option>';
        }
        ?>
      </select>
   </p>
    
    <!-- Post Count -->
    <p>
      <label for="<?php echo $this->get_field_id('postcount'); ?>">Number of Posts</label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" type="text" value="<?php echo $instance['postcount']; ?>">
      <small>How many posts do you want displayed?</small>
    </p>
    
    <!-- Show Featured Image -->
    <p>
      <input type="checkbox" id="<?php echo $this->get_field_id('showfeatured');?>" name="<?php echo $this->get_field_name('showfeatured'); ?>" value="<?php echo $instance['postcount']; ?>">
      <label for"<?php echo $this->get_field_id('showfeatured');?>">Show Featured Image</label>
    </p>
    <?php 
  }
  
  // Process options from form on save
  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field($new_instance['title']);
    $instance['category'] = absint($new_instance['category']);
    $instance['postcount'] = absint($new_instance['postcount']);
    $instance['showfeatured'] = absint($new_instance['showfeatured']);
    return $instance;
  }
  
 
}

add_action( 'widgets_init', function() { register_widget( 'Posts_Large' ); } );?>