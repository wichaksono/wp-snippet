<?php
/* 
idenya sih mengambil url gambar pertama yang ada didalam suatu konten, lalu menyimpannya ke dalam metabox untuk dijadikan thumbnail
sebenarnya masih pingin nambahin upload juga biar gambarnya disave ke directory uploads bersama gambar2 lainnya. tapi ntar lah :D
*/

function get_first_image_as_featured($content, $slug = 'tes-slug', $post_id = null) 
{
  preg_match_all('/<img[^>]+>/i',$content, $result); 

  $img = array();
  foreach( $result[0] as $img_tag)  {
      preg_match_all('/(src)=("[^"]*")/i',$img_tag, $img[$img_tag]);
  }

  $image_url = '';
  foreach ($img as $value) {
    if ( ! empty($value[2][0]) ) {
      $image_url = $value[2][0];
	  $image_url = str_replace('"', '', $image_url);
	  if ( ! empty( getimagesize($image_url) ) ) {
		  break;	  
	  }
      
    }
  }
	update_post_meta($post_id, '_thumbnail_alias', $image_url);
}

add_action('wp_footer', function() {
	if ( is_single() ) {
		global $post;
		$thumbnail_alias = get_post_meta( $post->ID, '_thumbnail_alias', true);
		
		if ( empty($thumbnail_alias) || empty(getimagesize($thumbnail_alias)) ) {
			  get_first_image_as_featured( $post->post_content, $post->post_name, $post->ID);
		}
	}
});
