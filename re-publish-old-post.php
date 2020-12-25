<?php
/**
 * script untuk republish artikel-artikel lama agar menjadi artikel baru
 */
 
 /** cara pakai **/
 add_action('wp_footer', function() {
   if ( is_single() ) {
       auto_re_publish();
   }
 });
 
function auto_re_publish($posts_per_page = 1)
{
  global $post;
  
	$current_date = date('Y-m-d');
	$last_action  = get_option('__last_action', '2000-01-01');

	if ( strtotime($current_date) > strtorime($last_action) ) {
		$posts = get_posts([
			'orderby' => 'date',
			'order' => 'ASC',
			'posts_per_page' => $posts_per_page
		]);

		$strtotime = strtotime("-1 days");
		foreach( $posts as $post ) {
			$strtotime += rand(10,100);
			$yesterday = date('Y-m-d H:i:s', strtotime("-1 days"));

			wp_update_post(array(
				'ID' => $post->ID,
				'post_date' => $yesterday,
				'post_date_gmt' => gmdate( $yesterday ),
			));
		}

		update_option( '__last_action', $current_date );
	}
}
