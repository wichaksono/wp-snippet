<?php
/*
 * replace code with this
 */
public static function constants() {
  $dirname        = str_replace( '//', '/', wp_normalize_path( dirname( dirname( self::$file ) ) ) );
  $theme_dir      = str_replace( '//', '/', wp_normalize_path( get_stylesheet_directory() ) );
  $plugin_dir     = str_replace( '//', '/', wp_normalize_path( WP_PLUGIN_DIR ) );
  $plugin_dir     = str_replace( '/opt/bitnami', '/bitnami', $plugin_dir );
  $located_plugin = ( preg_match( '#'. self::sanitize_dirname( $plugin_dir ) .'#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
  $directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
  $directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_stylesheet_directory_uri();
  $foldername     = str_replace( $directory, '', $dirname );
  $protocol_uri   = ( is_ssl() ) ? 'https' : 'http';
  $directory_uri  = set_url_scheme( $directory_uri, $protocol_uri );

  self::$dir = $dirname;
  self::$url = $directory_uri . $foldername;

}
