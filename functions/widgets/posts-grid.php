<?php
/**
* Adds Foo_Widget widget.
*/
class Posts_Grid extends WP_Widget {
 
  // Register the Widget
  public function __construct() {
    parent::__construct( 'posts-grid', 'Posts Grid', array( 'description' => __( 'A grid of posts', 'text_domain' ), ));
  }
  
  // Output the content of the widget
  public function widget($args, $instance) {
    
    // Variables
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
    $category = isset($instance['category']) ? $instance['category'] : '';
    $postcount = empty($instance['postcount']) ? '5' : $instance['postcount'];
    
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
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
      <?php
      endwhile; wp_reset_postdata(); ?>
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
    <label for="<?php echo $this->get_field_id('title'); ?>">Title</label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>">
    
    <!-- Category -->
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
    
    <!-- Post Count -->
    <label for="<?php echo $this->get_field_id('postcount'); ?>">Number of Posts</label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" type="text" value="<?php echo $instance['postcount']; ?>">
    
    <?php 
  }
  
  // Process options from form on save
  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field($new_instance['title']);
    $instance['category'] = absint($new_instance['category']);
    $instance['postcount'] = absint($new_instance['postcount']);
    return $instance;
  }
  
 
}

add_action( 'widgets_init', function() { register_widget( 'Posts_Grid' ); } );?>