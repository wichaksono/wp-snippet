<?php
// 1. Mengubah URL asset
function custom_asset_url($url) {
    if (strpos($url, '/wp-includes/') !== false) {
        $url = str_replace('/wp-includes/', '/public/', $url);
    }

    if (strpos($url, '/wp-content/') !== false) {
        $url = str_replace('/wp-content/', '/public/', $url);
    }

    return $url;
}

add_filter('style_loader_src', 'custom_asset_url');
add_filter('script_loader_src', 'custom_asset_url');
add_filter('wp_get_attachment_url', 'custom_asset_url');

// 2. Setup rewrite rule
add_action('init', function() {
    add_rewrite_rule('^public/(.+)$', 'index.php?public_file=$matches[1]', 'top');

    // Flush rewrite rules hanya sekali
    if (get_option('public_rewrite_flushed') != '1') {
        flush_rewrite_rules(false);
        update_option('public_rewrite_flushed', '1');
    }
});

// 3. Register query var
add_filter('query_vars', function($vars) {
    $vars[] = 'public_file';
    return $vars;
});

// 4. Handle request dan serve file
add_action('template_redirect', function() {
    $public_file = get_query_var('public_file');

    if ($public_file) {
        // Cek di wp-includes dulu
        $includes_path = ABSPATH . 'wp-includes/' . $public_file;
        if (file_exists($includes_path)) {
            serve_public_file($includes_path);
        }

        // Kalau tidak ada, cek di wp-content
        $content_path = ABSPATH . 'wp-content/' . $public_file;
        if (file_exists($content_path)) {
            serve_public_file($content_path);
        }

        // File tidak ditemukan
        status_header(404);
        exit('File not found');
    }
});

// 5. Function untuk serve file
function serve_public_file($file_path) {
    // Set proper headers
    $extension = pathinfo($file_path, PATHINFO_EXTENSION);
    $mime_types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject'
    ];

    $mime_type = $mime_types[$extension] ?? 'application/octet-stream';

    // Cache headers
    $expires = 31536000; // 1 year
    header('Content-Type: ' . $mime_type);
    header('Content-Length: ' . filesize($file_path));
    header('Cache-Control: public, max-age=' . $expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_path)) . ' GMT');

    // Output file
    readfile($file_path);
    exit;
}
