<?php

// limit revision
define('WP_POST_REVISIONS', 3);

// force https
if (
    (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') 
    && (empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https')
) {
    $_SERVER['HTTPS'] = 'on';
}
