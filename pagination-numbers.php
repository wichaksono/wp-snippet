<?php

function neon_pagination($query =null ) {
    if ( empty($query) ) {
        global $wp_query;
        $query = $wp_query;
    }

    $total = $query->max_num_pages;

    if ( $total > 1 )  {
        $current_page = get_query_var('paged') ? : 1;
      
        

        // Structure of “format” depends on whether we’re using pretty permalinks
        $format = empty( get_option('permalink_structure') ) ? '&page=%#%' : 'page/%#%/';
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => $format,
            'current' => $current_page,
            'total' => $total,
            'prev_text'          => apply_filters('neon_prev_nav', '&laquo; Sebelumnya'),
            'next_text'          => apply_filters('neon_next_nav', 'Berikutnya &raquo;'),
            'mid_size' => 4,
            'type' => 'list'
        ));
    }
}

# user
neon_pagination();

# or
neon_pagination($custom_wp_query);
