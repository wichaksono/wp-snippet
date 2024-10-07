<?php

declare(strict_types=1);

namespace NeonWebId\WPToolkit\Addons\Login;

use function add_action;
use function add_filter;
use function array_merge;
use function is_callable;
use function ltrim;
use function print_r;

/**
 * Class CustomWPLogin
 *
 * A class for customizing the WordPress login page.
 *
 * @package NeonWebId\WPToolkit\Addons\Login
 */
class CustomWPLogin
{
    private array $bodyClasses = [];
    private string $color = '';
    private string $accentColor = '';
    private string $accentColorHover = '';
    private array $backgroundStyles = [];
    private array $boxStyles = [];
    private string $logoUrl = '';
    private array $logoStyles = [];
    private string $title = '';
    private array $titleStyles = [];
    private string $titleURL = '';
    private string $subtitle = '';
    private array $subtitleStyles = [];
    private array $beforeForm = [];
    private array $afterForm = [];
    private array $preStyles = [];
    private array $styles = [];
    private array $mediaQueries = [];

    public function addBodyClasses(string $classes): void
    {
        $this->bodyClasses[] = $classes;
    }

    public function setTextColor(string $color): void
    {
        $this->color = $color;
    }

    public function setAccentColor(string $defaultColor, string $hoverColor): void
    {
        $this->accentColor      = $defaultColor;
        $this->accentColorHover = $hoverColor;
    }

    public function setBackground(array $styles): void
    {
        $this->backgroundStyles = $styles;
    }

    public function setBox(array $styles): void
    {
        $this->boxStyles = $styles;
    }

    public function setLogo(string $url, array $styles = []): void
    {
        $this->logoUrl    = $url;
        $this->logoStyles = $styles;
    }

    public function setTitle(string $title, array $styles = []): void
    {
        $this->title       = $title;
        $this->titleStyles = $styles;
    }

    public function setTitleURL(string $url): void
    {
        $this->titleURL = $url;
    }

    public function setSubtitle(callable|string $subtitle, array $styles = []): void
    {
        $this->subtitle       = $subtitle;
        $this->subtitleStyles = $styles;
    }

    public function addBeforeForm(callable|string $callable): void
    {
        if ( ! is_callable($callable)) {
            return;
        }

        $this->beforeForm[] = $callable;
    }

    public function addAfterForm(callable|string $callable): void
    {
        if ( ! is_callable($callable)) {
            return;
        }

        $this->afterForm[] = $callable;
    }

    /**
     * Add inline styles to the login page.
     *
     * @param string $selector CSS selector to which the styles should be applied.
     * @param array $styles Associative array of CSS properties and values.
     *
     * @return void
     */
    private function setStyle(string $selector, array $styles): void
    {
        $this->preStyles[$selector] = isset($this->preStyles[$selector]) ? array_merge(
            $this->preStyles[$selector],
            $styles
        ) : $styles;
    }

    public function addStyle(string $selector, array $styles): void
    {
        $this->styles[$selector] = isset($this->styles[$selector]) ? array_merge(
            $this->styles[$selector],
            $styles
        ) : $styles;
    }

    /**
     * Add media queries to the login page styles.
     *
     * @param string $mediaQuery CSS media query string.
     * @param string $selector CSS selector to which the styles should be applied within the media query.
     * @param array $styles Associative array of CSS properties and values.
     *
     * @return void
     */
    public function addMediaQuery(string $mediaQuery, string $selector, array $styles): void
    {
        $this->mediaQueries[$mediaQuery][] = [$selector => $styles];
    }

    /**
     * Render the hooks and styles for the login page.
     *
     * @return void
     */
    public function render(): void
    {

        if ($this->color) {
            $this->setStyle('.login', [
                'color' => $this->color
            ]);
        }

        if ($this->accentColor) {
            $this->setStyle('.login a', [
                'color' => $this->accentColor
            ]);

            $this->setStyle('.login a:hover, .login #backtoblog a:hover, .login #nav a:hover', [
                'color' => $this->accentColorHover
            ]);

            $this->setStyle('.login .button.wp-hide-pw .dashicons', [
                'color' => $this->accentColor,
            ]);

            $this->setStyle('#rememberme', [
                'border-color' => $this->accentColor,
            ]);

            $this->setStyle('#user_login:focus, #user_pass:focus, #rememberme:focus', [
                'border-color' => $this->accentColorHover,
                'box-shadow'   => '0 0 0 1px ' . $this->accentColorHover,
            ]);

            $this->setStyle('#rememberme:checked::before', [
                'content' => 'url("data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%2020%2020%27%3E%3Cpath%20d%3D%27M14.83%204.89l1.34.94-5.81%208.38H9.02L5.78%209.67l1.34-1.25%202.57%202.4z%27%20fill%3D%27%23'. ltrim($this->accentColorHover, '#') .'%27%2F%3E%3C%2Fsvg%3E")',
            ]);

            $this->setStyle('#wp-submit', [
                'background'   => $this->accentColor,
                'border-color' => $this->accentColor,
            ]);

            $this->setStyle('#wp-submit:hover, #wp-submit:focus, #wp-submit:active', [
                'background'   => $this->accentColorHover,
                'border-color' => $this->accentColorHover,
            ]);

            $this->setStyle('.login .message, .login .notice, .login .success', [
                'border-color' => $this->accentColor
            ]);

            $this->setStyle('.privacy-policy-link', [
                'color' => $this->accentColor,
                'text-decoration' => 'none',
            ]);

            $this->setStyle('.privacy-policy-link:hover', [
                'color' => $this->accentColorHover,
            ]);
        }

        if ($this->backgroundStyles) {
            $this->setStyle('.login', $this->backgroundStyles);
        }

        if ($this->boxStyles) {
            $this->setStyle('.login form', [
                'border'      => 'none',
                'box-shadow'  => 'none',
            ]);

            $this->setStyle('.login #login', $this->boxStyles);
        }

        if ($this->logoUrl) {
            $this->setStyle('.login h1 a', [
                'height'  => 'unset',
                'width'   => 'unset',
                'background' => 'none',
                'margin'  => '0',
            ]);

            $this->setStyle('.login h1 a:before', [
                'content'          => '""',
                'background-image' => 'url("' . $this->logoUrl . '")',
                'background-size'  => 'cover',
                'width'            => '64px',
                'height'           => '64px',
                'margin-left'      => 'auto',
                'margin-right'     => 'auto',
                'margin-bottom'    => '20px',
                'display'          => 'block',
            ]);

            if ($this->logoStyles) {
                $this->setStyle('.login h1 a', $this->logoStyles);
            }
        }

        if ($this->title) {
            add_filter('login_headertext', function () {
                return $this->title;
            });

            $this->titleStyles['text-indent'] = 'unset';
            if ($this->titleStyles) {
                $this->setStyle('.login h1 a', $this->titleStyles);
            }
        }

        if ( $this->subtitle ) {
            add_action('login_message', function () {
                echo '<div class="login-message">' . $this->subtitle . '</div>';
            });

            if ($this->subtitleStyles) {
                $this->setStyle('.login .login-message', $this->subtitleStyles);
            }
        }

        add_action('login_head', function () {
            $css = '<style>';

            // combine styles
            foreach ($this->styles as $key => $value) {
                $this->preStyles[$key] = $value;
            }

            $styles = $this->preStyles;

            foreach ($styles as $selector => $properties) {
                $css .= $selector . '{';
                foreach ($properties as $property => $value) {
                    $css .= $property . ':' . $value . ';';
                }
                $css .= '}';
            }

            // add media queries
            foreach ($this->mediaQueries as $mediaQuery => $queries) {
                $css .= '@media ' . $mediaQuery . '{';
                foreach ($queries as $query) {
                    foreach ($query as $selector => $properties) {
                        $css .= $selector . '{';
                        foreach ($properties as $property => $value) {
                            $css .= $property . ':' . $value . ';';
                        }
                        $css .= '}';
                    }
                }
                $css .= '}';
            }

            $css .= '</style>';

            echo $css;
        }, 9999);

        $this->bodyClasses[] = 'neon-login';
        add_filter('login_body_class', function ($classes) {
            return array_merge($classes, $this->bodyClasses);
        });

        if ($this->titleURL) {
            add_filter('login_headerurl', function () {
                return $this->titleURL;
            });
        }

        if ($this->beforeForm) {
            foreach ($this->beforeForm as $callable) {
                add_action('login_header', $callable);
            }
        }

        if ($this->afterForm) {
            foreach ($this->afterForm as $callable) {
                add_action('login_footer', $callable);
            }
        }
    }
}
