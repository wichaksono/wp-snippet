<?php

<?php
/**
 * DISABLE COMMENTS WORDPRESS - Cara Termudah
 * Tambahkan ke functions.php
 */

// 1. Matikan comment support untuk semua post types
function disable_comments_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
    }
}
add_action('admin_init', 'disable_comments_support');

// 2. Tutup comment untuk post yang sudah ada
function disable_existing_comments() {
    return false;
}
add_filter('comments_open', 'disable_existing_comments');
add_filter('pings_open', 'disable_existing_comments');

// 3. Sembunyikan comment form
function remove_comment_form() {
    return '';
}
add_filter('comment_form_default_fields', 'remove_comment_form');
add_filter('comment_form_defaults', function() { return array(); });

// 4. Redirect halaman comment ke homepage
function redirect_comment_pages() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php' || 
        $pagenow === 'comment.php' || 
        $pagenow === 'edit-comment.php') {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'redirect_comment_pages');

// 5. Hapus menu Comments dari admin
function remove_comment_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'remove_comment_menu');

// 6. Hapus comment count dari admin bar
function remove_comment_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'remove_comment_admin_bar');

// 7. Block akses langsung ke wp-comments-post.php
function block_comment_requests() {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-comments-post.php') !== false) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('init', 'block_comment_requests');

// 8. Hapus dari XML-RPC (untuk keamanan ekstra)
function disable_xmlrpc_comments($methods) {
    unset($methods['wp.newComment']);
    return $methods;
}
add_filter('xmlrpc_methods', 'disable_xmlrpc_comments');

// 9. Clean up head (hapus comment feed links)
remove_action('wp_head', 'feed_links_extra', 3);

// 10. Hapus comment widgets dari dashboard
function remove_comment_dashboard_widgets() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'remove_comment_dashboard_widgets');
?>
