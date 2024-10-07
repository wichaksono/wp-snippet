<?php

require_once __DIR__ . '/CustomWPLogin.php';

$login = new NeonWebId\WPToolkit\Addons\Login\CustomWPLogin();

$login->setBackground([
    'background-color'    => '#f1f1f1',
    'background-image'     => 'url('. get_stylesheet_directory_uri() . '/inc/neon-login/assets/img/bg.png)',
    'background-size'     => 'contain',
    'background-repeat'   => 'no-repeat',
    'background-position' => 'center',
]);

$login->setTextColor('#1E1F22');
$login->setAccentColor('#EE7C67', '#d66853');
$login->setLogo(get_stylesheet_directory_uri() . '/wp-content/uploads/2024/10/logo.png');
$login->setTitle('Neon Studio');
$login->setTitleURL(home_url());
$login->setSubtitle('Welcome to Neon Studio', [
    'text-align'    => 'center',
    'margin-bottom' => '15px',
    'font-size'     => '16px',
    'padding'       => '0 50px',
    'color'         => '#c9c0c0',
]);

/**
 * Additional Style
 */
$login->setBox([
    'background-color' => '#fff',
    'border-radius'    => '10px',
    'box-shadow'       => '0 0 10px rgba(0, 0, 0, 0.1)',
    'padding'          => '30px',
]);

$login->addStyle('.neon-login', [
    'display'         => 'flex',
    'align-items'     => 'center',
    'justify-content' => 'center',
]);

$login->addStyle('#user_login, #user_pass', [
    'background'     => '#F7F7F7',
    'border-color'  => '#EAEAEA',
]);

$login->addStyle('#user_login:focus, #user_pass:focus', [
    'background'     => '#EAEAEA',
]);

$login->addStyle('.privacy-policy-link', [
    'font-size' => '12px',
    'font-weight' => 'bold',
]);

$login->addMediaQuery('(max-width: 450px)', '.login', [
    'background-image' => 'unset',
]);

$login->addMediaQuery('(max-width: 450px)', '.login .login-message', [
    'padding' => '0',
]);

$login->addMediaQuery('(max-width: 450px)', '#login', [
    'width' => 'calc(100% - 40px)',
    'margin' => '20px',
]);

// render on functions.php
$login->render();

// render on plugin
// add_action('plugins_loaded', [$login, 'render']);
