<?php
function get_user_taxonomy_terms_hierarchical( $user_id, $taxonomy = 'category' ) {
    global $wpdb;

    $results = $wpdb->get_results( $wpdb->prepare("
        SELECT DISTINCT 
            t.term_id,
            t.name,
            t.slug,
            tt.parent
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type = 'post'
          AND p.post_status = 'publish'
          AND p.post_author = %d
          AND tt.taxonomy = %s
    ", $user_id, $taxonomy ), ARRAY_A );

    if ( empty( $results ) ) {
        return [];
    }

    // Susun struktur hirarki
    $terms_by_id = [];
    foreach ( $results as $term ) {
        $term['children'] = [];
        $terms_by_id[ $term['term_id'] ] = $term;
    }

    $tree = [];

    foreach ( $terms_by_id as $id => &$term ) {
        if ( $term['parent'] && isset( $terms_by_id[ $term['parent'] ] ) ) {
            $terms_by_id[ $term['parent'] ]['children'][] = &$term;
        } else {
            $tree[] = &$term;
        }
    }

    return $tree;
}
