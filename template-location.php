<?php
/**
 * Getting template inner plugin folder
 * PLEASE DEFINE NOEN_BASE_DIR as main directory of your plugin
 * ex : define('NEON_BASE_DIR', plugin_dir_path(__FILE__));
 * define result MUST : .../wp-content/plugins/neon-plugin/
 */
function neon_get_template_part($slug, $name = '', $args = [])
{
	$filename = [];
	$filename[] = "{$slug}.php";
	if ( ! empty($name) ) {
		$filename[] = "{$slug}-{$name}.php";
	}


	return neon_locate_template($filename, true, false, $args);
}

function neon_locate_template( $template_names, $load = false, $require_once = true, $args = array() ) {

	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name ) {
			continue;
		}
		if ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {
			$located = STYLESHEETPATH . '/' . $template_name;
			break;
		} elseif ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {
			$located = TEMPLATEPATH . '/' . $template_name;
			break;
      
      /**
       * find template on /wp-content/plugins/neon-plugin/template/
       */
		} elseif ( file_exists( NEON_BASE_DIR . '/template/' . $template_name ) ) {
			$located = NEON_BASE_DIR . '/template/' . $template_name;
			break;
		}
	}

	if ( $load && '' !== $located ) {
		load_template( $located, $require_once, $args );
	}

	return $located;
}
