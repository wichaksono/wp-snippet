<?php
// limit access to media library
function restrict_media_library_to_user( $query ) {
    if ( is_admin() && current_user_can('author') ) {
        if ( (isset($_POST['action']) && $_POST['action'] === 'query-attachments') || $query->get('post_type') === 'attachment' ) {
            $query->set('author', get_current_user_id());
        }
    }
}
add_action( 'pre_get_posts', 'restrict_media_library_to_user' );
