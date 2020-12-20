<?php
/**
 * File name MUST contain comment Block @package CLASS_NAME
 */
$file = file_get_contents( __DIR__ . '/filename.php', true );

preg_match('|@package (.*)$|mi', $file, $package);
//var_dump( $package );
if ( ! empty($package[1]) && class_exist($package[1]) ) {
    new $package[1]();
}
