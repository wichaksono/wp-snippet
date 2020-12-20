<?php
/**
 * HOOK Register Page Templates
 */
add_filter('theme_page_templates', function($post_template) {
    $realpath = get_template_directory() . '/spesical-pagetempalte';
    $post_template[ $realpath . 'login.php'] = 'Login Page';
    return $post_template;
});
