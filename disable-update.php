<?php
/**
 * Disable all WordPress updates
 * - Core WordPress updates
 * - Plugin updates
 * - Theme updates
 * - Automatic background updates
 */

// Disable core WordPress updates
add_filter('pre_site_transient_update_core', '__return_null');
add_filter('auto_update_core', '__return_false');

// Disable plugin updates
add_filter('pre_site_transient_update_plugins', '__return_null');

// Disable theme updates
add_filter('pre_site_transient_update_themes', '__return_null');

// Disable automatic background updates
add_filter('automatic_updater_disabled', '__return_true');
add_filter('auto_update_plugin', '__return_false');
add_filter('auto_update_theme', '__return_false');
add_filter('allow_minor_auto_core_updates', '__return_false');
add_filter('allow_major_auto_core_updates', '__return_false');
add_filter('allow_dev_auto_core_updates', '__return_false');

// Remove update notifications
function remove_update_notifications(): void
{
    remove_action('admin_notices', 'update_nag', 3);
    remove_action('network_admin_notices', 'update_nag', 3);
}

add_action('admin_init', 'remove_update_notifications');

// Remove the WordPress update menu item
function remove_update_menu_item(): void
{
    remove_submenu_page('index.php', 'update-core.php');
}

add_action('admin_menu', 'remove_update_menu_item');
