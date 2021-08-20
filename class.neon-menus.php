<?php
/**
 * digunakan untuk Pengganti wp_nav_menu()
 * output Array dan HTML
 */
class Neon_Menus {

    private $menu_location;
    private $args;

    private static $instance;

    public function __construct($menu_location, $args = [])
    {
        $default = [
            'cache_menu' => true,
            'cache_prefix_name' => 'neon_menus_',
            'cache_expires' => 10, // in second
        ];

        $this->args = wp_parse_args($args, $default);
        $this->menu_location = $menu_location;
    }

    public static function set($menu_location, $args = [])
    {
        if ( ! self::$instance instanceof self ) {
            self::$instance = new self($menu_location, $args);
        }

        return self::$instance;
    }

    public function Render()
    {
        $menus = $this->Array();

    }

    public function Array()
    {
        $menu_temporary_name = $this->args['cache_prefix_name'] . $this->menu_location;

        $neon_nav_menus = get_transient($menu_temporary_name);
        if ( ! empty($neon_nav_menus) ) {
            return $neon_nav_menus;
        }

        global $wpdb;

        $main_menu = [];
        $menu_locations = get_nav_menu_locations();
        if (!empty($menu_locations[$this->menu_location])) {
            $term_taxonomy_id = $menu_locations[$this->menu_location];
            $sql = "SELECT * FROM {$wpdb->term_relationships} WHERE `term_taxonomy_id` = {$term_taxonomy_id}";

            $menus = $wpdb->get_results($sql, ARRAY_A);
            /**
             * Grouping Menu
             */
            if (!empty($menus)) {
                foreach ($menus as $menu) {

                    $menu_item_type = get_post_meta($menu['object_id'], '_menu_item_type', true);
                    $menu_item_object_id = get_post_meta($menu['object_id'], '_menu_item_object_id', true);
                    $menu_item_object = get_post_meta($menu['object_id'], '_menu_item_object', true);
                    $menu_item_menu_parent = get_post_meta($menu['object_id'], '_menu_item_menu_item_parent', true);
                    $nav_menu_item = get_post($menu['object_id']);

                    $post_title = $nav_menu_item->post_title;
                    $post_name  = $nav_menu_item->post_name;
                    $menu_item_url = get_post_meta($menu['object_id'], '_menu_item_url', true);

                    // handle menu by taxonomy
                    if ($menu_item_type == 'taxonomy') {
                        $term = get_term($menu_item_object_id, $menu_item_object);
                        $post_name = $term->slug;
                        $menu_item_url = get_term_link($term);

                        if ( empty($post_title) ) {
                            $post_title = $term->name;
                        }
                    }

                    // handle menu by post_type : page and cpt
                    if ($menu_item_type == 'post_type') {
                        $post_type = get_post($menu_item_object_id, $menu_item_object);
                        $menu_item_url = get_permalink($menu_item_object_id);
                        $post_name = $post_type->post_name;

                        if ( empty($post_title) ) {
                            $post_title = $post_type->post_title;
                        }
                    }

                    $menu_list = [
                        'ID' => $nav_menu_item->ID,
                        'menu_order' => $nav_menu_item->menu_order,
                        'post_title' => $post_title,
                        'post_name' => $post_name,
                        'menu_item_object_id' => $menu_item_object_id,
                        'menu_item_object' => $menu_item_object,
                        'menu_item_url' => $menu_item_url,
                        'menu_item_type' => $menu_item_type,
                        'menu_item_menu_item_parent' => $menu_item_menu_parent,
                        'menu_item_target' => get_post_meta($menu['object_id'], '_menu_item_target', true),
                        'menu_item_xfn' => get_post_meta($menu['object_id'], '_menu_item_xfn', true),
                        'menu_item_classes' => get_post_meta($menu['object_id'], '_menu_item_classes', true),
                    ];

                    $main_menu[$nav_menu_item->menu_order] = $menu_list;
                }

                /**
                 * Store menu by menu sub menu
                 */
                $menu_submenu = [];
                foreach ($main_menu as $index => $item) {
                    $submenu = $this->get_submenu($item['ID'], $main_menu);
                    if ( !empty($submenu) ) {
                        $item['sub_menu'] = $submenu;
                    }
                    $menu_submenu[$index] = $item;
                }

                set_transient($menu_temporary_name, $menu_submenu, $this->args['cache_expires']);
                return $menu_submenu;
            }
        }

        return [];
    }

    private function get_submenu($parent, $main_menu)
    {
        foreach ($main_menu as $nav_menu) {
            if ( $parent && $parent == $nav_menu['menu_item_menu_item_parent'] ) {
                $sub_submenu = $this->get_submenu($nav_menu['ID'], $main_menu);
                if ( ! empty($sub_submenu) ) {
                    $nav_menu['submenu'] = $sub_submenu;
                }

                return [$nav_menu['menu_order'] => $nav_menu];
            }
        }

        return [];
    }
}

# Penggunaan
# $menus = Neon_Menus::set($menu_location, $args)->Array(); // return array
# Neon_Menus::set($menu_location, $args)->Render(); // print
