<?php
/**
 *
 * Simple scipt to create wordpress post view count without plugin
 * You can add this script or include at file functions.php
 * in your theme
 */
class Neon_Post_View_Count {
  private $post_count_meta = '_neon_view_count';
  
  public function __construct() {
    add_action('wp_head', [$this, '_set_view_count');
  }
  
  /**
   * only count at single or singular page
   */
  public function _set_view_count() {
     if ( is_singular($this->use_at() ) {
         global $post;
         
         # get old view count and force to integer
         $count = (int) get_post_meta($post->ID, $this->post_count_meta, true);
         
         # update with new count
         update_post_meta($post->ID, $this->post_count_meta, $count+1);
     } 
  }
  
  /**
   * will adding post_types ex: post, page, or custom post types
   * @use add_filter('use_neon_post_view_count_at', '....') with return array
   */
  private function use_at() {
    return apply_filters('use_neon_post_view_count_at', ['post']);
  }
         
  public static function get_count($post_id = 0) {
    if ( empty($post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    
    return (int) get_post_meta($post_id, $this->post_count_meta, true);
    
  }
}

/**
 * how to use
 */
new Neon_Post_View_Count();
         
         
# show view count as single post or post-type
Neon_Post_View_Count::get_count();

/**
 * or use some function to create your dream
 * you can call with <?php neon_get_view_count();?>
 */
function neon_get_view_count() {
  $view_count = Neon_Post_View_Count::get_count();
  if ( ! empty($view_count) ) {
      $view_count = number_format($view_count, '', ',', '.');
      echo "Dilihat {$view_count} kali";
  } 
}
