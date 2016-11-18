<?php
/**
* Adds Foo_Widget widget.
*/
class Breaking_News extends WP_Widget {
 
  /**
   * Register the widget
   */
  public function __construct() {
    parent::__construct( 'breaking-news', 'Breaking News', array( 'description' => __( 'A widget for breaking news', 'text_domain' ), ));
  }
  
  /**
   * Output the content of the widget
   */
  public function widget($args, $instance) {
    
    // Variables
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? 'Breaking News' : $instance['title'], $instance, $this->id_base);
    $category = isset($instance['category']) ? $instance['category'] : '';
    $showfeatured = $instance['showfeatured'] ? '1' : '0';
    $background = $instance['background'] ? '1' : '0';
    $border = $instance['border'] ? '1' : '0';
    
    echo $before_widget;
    
    // Build Arguments for WP_Query
    $args = array('posts_per_page' => 1, 'cat' => $category);
    $widget_loop = new WP_Query($args); 
    
    ?>
    <?php
    // Display Title
    if (!empty($title)) echo $before_title . esc_attr($title) . $after_title;?>
    <section class="widget widget-breaking-news <?php if($border) echo 'widget-border '; if($background) echo 'widget-background'; if(empty($title)) echo 'widget-no-title'; ?>">
      <h2 class="breaking-news-title"><?php echo $title; ?></h2>
      <?php
      while ($widget_loop->have_posts()) : $widget_loop->the_post(); // The loop ?>
      
      <article style="background-image: url(<?php the_post_thumbnail_url('featured-image-wide')?>)">
          

              <h3 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </section>
    <?php
    echo $after_widget;
  }
  
  /**
   * Create the options form in the dashboard
   */
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
        <option value="0" <?php if (!$instance['category']) echo 'selected="selected"'; ?>>All</option>
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
    
    <!-- Show Featured Image -->
    <p><label for"<?php echo $this->get_field_id('showfeatured');?>">
      <input type="checkbox" id="<?php echo $this->get_field_id('showfeatured');?>" name="<?php echo $this->get_field_name('showfeatured'); ?>" <?php checked( $instance['showfeatured'] ); ?>>
      Show Featured Image</label>
    </p>
    
    <!-- Border -->
    <p><label for"<?php echo $this->get_field_id('border');?>">
      <input type="checkbox" id="<?php echo $this->get_field_id('border');?>" name="<?php echo $this->get_field_name('border'); ?>" <?php checked( $instance['border'] ); ?>>
      Show border</label>
      <br>
      <small>Displays a border around the widget</small>
    </p>
    
    <!-- Background -->
    <p><label for"<?php echo $this->get_field_id('background');?>">
      <input type="checkbox" id="<?php echo $this->get_field_id('background');?>" name="<?php echo $this->get_field_name('background'); ?>" <?php checked( $instance['background'] ); ?>>
      Show background</label>
      <br>
      <small>Fills the widget with a background color</small>
    </p>
    <?php 
  }
  
  /**
   * Process options from form on save
   */
  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field($new_instance['title']);
    $instance['category'] = absint($new_instance['category']);
    $instance['showfeatured'] = $new_instance['showfeatured'] ? 1 : 0;
    $instance['background'] = $new_instance['background'] ? 1 : 0;
    $instance['border'] = $new_instance['border'] ? 1 : 0;
    return $instance;
  }
  
 
}

add_action( 'widgets_init', function() { register_widget( 'Breaking_News' ); } );?>