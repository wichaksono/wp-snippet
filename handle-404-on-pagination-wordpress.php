<?php

function redirect_pagination_404() {
    if (!is_404()) return;

    $url = $_SERVER['REQUEST_URI'];

    if (preg_match('/(.+)\/page\/\d+\/?$/', $url, $matches)) {
        $base_url = trailingslashit($matches[1]);
        wp_redirect(home_url($base_url), 301);
        exit;
    }
}
add_action('template_redirect', 'redirect_pagination_404');
