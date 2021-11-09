<?php
/**
 * How to remove wpautop widget shortcode
 * Put this code in functions.php
 */

/**
 * replace render_callblack only on widget shortcode
 */
add_filter('block_type_metadata_settings', function($settings, $meta) {
    if ( $meta['name'] == 'core/shortcode' ) {
        $settings['render_callback'] = 'sg_render_block_core_shortcode';
    }
    return $settings;
}, 10, 2);

# new render_callback for shortcode
function sg_render_block_core_shortcode( $attributes, $content ) {
	return $content;
}
