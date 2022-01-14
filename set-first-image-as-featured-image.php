<?php
add_action('wp_footer', function () {

    if (is_single()) {
        global $post;
        set_to_true_post_thumbnail($post);
    }
});

function set_post_thumbnail_this_post($post_id, $post = null)
{
    if (empty($post)) {
        $post = get_post($post_id);
    }
  
    $upload_dir = wp_upload_dir();
    $url = get_first_image_as_featured($post->post_content, $post->post_name, $post_id);
    $basename = basename($url);
	
	if ( strpos($basename, '?') !== false ) {
		$ext_arr = explode('?', $basename);
		$basename = $ext_arr[0];
	}

	$ext = phpinfo($basename, PATHINFO_EXTENSION );
	if ( empty($ext) ) {
		$ext = 'jpg';
	}

    if (!empty($url)) {
        include_once ABSPATH . 'wp-admin/includes/media.php';
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/image.php';

        $filename = $upload_dir['path'] . '/' . $post->post_name . '.' . $ext;
		$content = file_get_contents($url);
		if ( $content === false ) {
			$content = file_get_contents('http:'. $url);
		}
		
		if ( $content === false ) {
			$content = file_get_contents('https:'. $url);
		}
		
		if ($content !== false) {
			file_put_contents($filename, $content);

			$wp_filetype = wp_check_filetype(basename($filename), null);

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => $post->post_title,
				'post_content' => '',
				'post_status' => 'inherit',
				'guid' => $upload_dir['url'] . '/' . $post->post_name . '.' . $ext
			);

			$attach_id = wp_insert_attachment($attachment, $filename);
			$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
			wp_update_attachment_metadata($attach_id, $attach_data);
			wp_update_image_subsizes($attach_id);
			$set_thumbnail = set_post_thumbnail($post_id, $attach_id);

			if ($set_thumbnail != false) {
				update_post_meta($post_id, '__has_replace_thumbnail', $attach_id);
			}
		}
    }

}

function get_first_image_as_featured($content, $slug = 'tes-slug', $post_id = null)
{
    preg_match_all('/<img[^>]+>/i', $content, $result);

    $img = array();
    foreach ($result[0] as $img_tag) {
        preg_match_all('/(src)=("[^"]*")/i', $img_tag, $img[$img_tag]);
    }

    $image_url = '';
    foreach ($img as $value) {
        if (!empty($value[2][0])) {
            $image_url = $value[2][0];
            $image_url = str_replace('"', '', $image_url);
            if (!empty(getimagesize($image_url))) {
                break;
            }
			if (!empty(getimagesize('http:' . $image_url))) {
                break;
            }
			
			if (!empty(getimagesize('https:' . $image_url))) {
                break;
            }

        }
    }

    return $image_url;
}

function set_to_true_post_thumbnail($post)
{
    $featured_image_exists = has_post_thumbnail($post->ID);
    if ( !$featured_image_exists )  {
         $attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
         if ( $attached_image ) {
            foreach ( $attached_image as $attachment_id => $attachment ) {
              set_post_thumbnail($post->ID, $attachment_id);
            }
         }
    } else {
        $__has_replace_thumbnail = get_post_meta($post->ID, '__has_replace_thumbnail', true);
        $thumbnail_url = get_the_post_thumbnail_url($post);

        if (empty($__has_replace_thumbnail) || empty($thumbnail_url) ) {
            if (has_post_thumbnail($post) && $thumbnail_url && strpos($thumbnail_url, home_url()) === false) {
                update_post_meta($post->ID, '__has_replace_thumbnail', get_post_thumbnail_id($post));
            } else {
                set_post_thumbnail_this_post($post->ID, $post);
            }
        }
    }
}
