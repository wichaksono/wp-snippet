<?php

class Marketplace_Tab {

    public function __construct() {
        // Tambahkan tab di halaman produk.
        add_filter('woocommerce_product_data_tabs', array($this, 'add_marketplace_tab'));

        // Tambahkan konten tab Marketplace.
        add_action('woocommerce_product_data_panels', array($this, 'add_marketplace_tab_content'));

        // Simpan data Marketplace.
        add_action('woocommerce_process_product_meta', array($this, 'save_marketplace_data'));

        // Tampilkan tombol Marketplace di halaman produk.
        add_action('woocommerce_single_product_summary', array($this, 'display_marketplace_buttons'), 35);
    }

    public function add_marketplace_tab($tabs) {
        $tabs['marketplace_tab'] = array(
            'label'    => __('Marketplace', 'woocommerce'),
            'target'   => 'marketplace_product_data',
            'class'    => array('show_if_simple'),
            'priority' => 21,
        );
        return $tabs;
    }

    public function add_marketplace_tab_content() {
        global $post;

        echo '<div id="marketplace_product_data" class="panel woocommerce_options_panel">';
        echo '<div class="options_group">';

        // Field Shopee URL.
        woocommerce_wp_text_input(array(
            'id'          => '_mp_shopee_url',
            'label'       => __('Shopee URL', 'woocommerce'),
            'description' => __('Enter the Shopee URL.', 'woocommerce'),
            'desc_tip'    => true,
            'type'        => 'url',
        ));

        // Field Tokopedia URL.
        woocommerce_wp_text_input(array(
            'id'          => '_mp_tokopedia_url',
            'label'       => __('Tokopedia URL', 'woocommerce'),
            'description' => __('Enter the Tokopedia URL.', 'woocommerce'),
            'desc_tip'    => true,
            'type'        => 'url',
        ));

        // Field Blibli URL.
        woocommerce_wp_text_input(array(
            'id'          => '_mp_blibli_url',
            'label'       => __('Blibli URL', 'woocommerce'),
            'description' => __('Enter the Blibli URL.', 'woocommerce'),
            'desc_tip'    => true,
            'type'        => 'url',
        ));

        // Field Lazada URL.
        woocommerce_wp_text_input(array(
            'id'          => '_mp_lazada_url',
            'label'       => __('Lazada URL', 'woocommerce'),
            'description' => __('Enter the Lazada URL.', 'woocommerce'),
            'desc_tip'    => true,
            'type'        => 'url',
        ));

        // Field TikTok URL.
        woocommerce_wp_text_input(array(
            'id'          => '_mp_tiktok_url',
            'label'       => __('TikTok URL', 'woocommerce'),
            'description' => __('Enter the TikTok URL.', 'woocommerce'),
            'desc_tip'    => true,
            'type'        => 'url',
        ));

        echo '</div>';
        echo '</div>';
    }

    public function save_marketplace_data($post_id) {
        // Simpan URL untuk masing-masing marketplace.
        $marketplaces = ['shopee', 'tokopedia', 'blibli', 'lazada', 'tiktok'];
        foreach ($marketplaces as $mp) {
            $key = '_mp_' . $mp . '_url';
            $value = isset($_POST[$key]) ? esc_url($_POST[$key]) : '';
            update_post_meta($post_id, $key, $value);
        }
    }

    public function display_marketplace_buttons() {
        global $post;

        // Ambil data URL marketplace.
        $marketplaces = [
            'Shopee'    => get_post_meta($post->ID, '_mp_shopee_url', true),
            'Tokopedia' => get_post_meta($post->ID, '_mp_tokopedia_url', true),
            'Blibli'    => get_post_meta($post->ID, '_mp_blibli_url', true),
            'Lazada'    => get_post_meta($post->ID, '_mp_lazada_url', true),
            'TikTok'    => get_post_meta($post->ID, '_mp_tiktok_url', true),
        ];

        // Tampilkan tombol jika URL tersedia.
        echo '<div class="marketplace-buttons">';
        foreach ($marketplaces as $name => $url) {
            if ($url) {
                echo '<a href="' . esc_url($url) . '" class="button" target="_blank">' . esc_html($name) . '</a> ';
            }
        }
        echo '</div>';
    }
}

// Inisialisasi class.
new Marketplace_Tab();
