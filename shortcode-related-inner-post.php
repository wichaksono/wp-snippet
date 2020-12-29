<?php
/**
 * masukan [neon_related_posts] didalam artikel,
 * ingat harus digunakan didalam artikel.
 */
add_action('neon_related_posts', 'neon_related_posts_func');

function neon_related_posts_func()
{
    global $post;
    $render = '';
    $categories = get_the_category($post->ID);
    $cat_ids = array();
    if ( ! empty($categories) ) {
        foreach($categories as $cat) {
          $cat_ids[] = $cat->term_id;
        }
    }
    
	  if ( ! empty($cat_ids) ) {
		   $args = array(
          'cat' => $cat_ids ,
          'posts_per_page' => 3
        );

      $related = new WP_Query($args);

      if ( $related->have_posts() ) :

        $render .= '<div class="neon-related-posts"><div class="neon-related-post-title">Baca Juga</div><ul>';
        $n = 1;
        while( $related->have_posts() ) : $related->the_post();
   
          $render .='<li><a href="'. get_permalink() .'" target="_blank" title="'. get_the_title() .'">'. get_the_title().'</a></li>';
  
        endwhile;
        $render .= '</ul></div>';
        
        wp_reset_postdata();
      endif;
    }
	
  	return $render;
}
