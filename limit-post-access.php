<?php

// limit author to only see their own posts in the admin
function restrict_author_post_list( $query ) {
    if ( is_admin() && $query->is_main_query() && current_user_can('author') && $query->get('post_type') === 'post' ) {
        $query->set( 'author', get_current_user_id() );
    }
}
add_action( 'pre_get_posts', 'restrict_author_post_list' );

// Fix post counts for author role in admin dashboard
function fix_post_counts_for_author( $counts, $type ) {
    if ( is_admin() && $type === 'post' && current_user_can('author') ) {
        global $wpdb;
        $user_id = get_current_user_id();

        // Hitung ulang jumlah post milik user berdasarkan status
        $results = $wpdb->get_results( $wpdb->prepare("
            SELECT post_status, COUNT(*) as count
            FROM {$wpdb->posts}
            WHERE post_type = %s
              AND post_author = %d
              AND post_status NOT IN ('auto-draft', 'trash')
            GROUP BY post_status
        ", $type, $user_id ), OBJECT_K );

        $filtered_counts = new stdClass();

        foreach ( get_post_stati() as $status ) {
            $filtered_counts->$status = isset( $results[$status] ) ? intval( $results[$status]->count ) : 0;
        }

        return $filtered_counts;
    }

    return $counts;
}
add_filter( 'wp_count_posts', 'fix_post_counts_for_author', 10, 2 );
