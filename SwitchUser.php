<?php

declare(strict_types=1);

namespace NeonWebId\WP\SwitchUser;

use WP_Admin_Bar;
use WP_User;

use function add_action;
use function add_filter;
use function add_query_arg;
use function admin_url;
use function base64_decode;
use function base64_encode;
use function current_user_can;
use function esc_html;
use function explode;
use function get_user_by;
use function get_users;
use function in_array;
use function sanitize_text_field;
use function setcookie;
use function time;
use function wp_get_current_user;
use function wp_redirect;
use function wp_set_auth_cookie;
use function wp_set_current_user;

/**
 * Class Switcher
 *
 * Handles user switching functionality in WordPress admin bar, including
 * switching to different users and switching back to the original user.
 * It also manages cookies for tracking user switch state.
 */
final class Switcher
{
    /**
     * The name of the cookie used to track user switch state.
     */
    private const COOKIE_NAME = 'neon_switch_user';

    /**
     * The current WP_User object of the logged-in user.
     *
     * @var WP_User
     */
    private WP_User $user;

    /**
     * Cached result of whether the current user is a super admin.
     *
     * @var bool|null
     */
    private ?bool $isSuperAdmin = null;

    /**
     * Cached result of the user who was switched to.
     *
     * @var array|null
     */
    private ?array $userSwitched = null;

    /**
     * Cached result of whether the user has switched.
     *
     * @var bool|null
     */
    private ?bool $isSwitched = null;

    /**
     * Constructor for initializing the Switcher class.
     *
     * Sets up actions and filters for user switching functionality,
     * including handling cookies and admin bar menus.
     */
    final public function __construct()
    {
        $this->user = wp_get_current_user();

        add_action('admin_init', [$this, 'setUserCookies']);
        add_action('wp_logout', [$this, 'clearOldUserCookie']);

        add_action('admin_bar_menu', [$this, 'switchBack'], 100);
        add_action('admin_bar_menu', [$this, 'userLists'], 100);
        add_filter( 'user_row_actions', [ $this, 'rowAction' ], 10, 2 );


        // Add the action to handle user switching requests.
        add_action('admin_post_neon_switch_user', [$this, 'switchUser']);
        add_action('admin_notices', [$this, 'displayNotification']);

        add_action('wp_footer', [$this, 'footerSwitchBack']);
    }

    /**
     * Sets a cookie for the current user if they are a super admin.
     *
     * This cookie is used to track the current user state for switching purposes.
     */
    public function setUserCookies(): void
    {
        if (empty($_COOKIE[self::COOKIE_NAME])) {
            if ($this->isSuperAdmin()) {
                $cookieValue = base64_encode(time() . ':' . $this->user->user_login . ':' . $this->user->ID);
                setcookie(self::COOKIE_NAME, $cookieValue, 0, '/');
            } else {
                $this->clearOldUserCookie();
            }
        }
    }

    /**
     * Clears the old user cookie.
     *
     * This method is called upon user logout to ensure cookies are cleared.
     */
    public function clearOldUserCookie(): void
    {
        if (isset($_COOKIE[self::COOKIE_NAME])) {
            setcookie(self::COOKIE_NAME, '', time() - 3600, '/');
        }
    }

    /**
     * Adds a "Switch Back to Original User" menu item to the admin bar if the user has switched.
     *
     * @param WP_Admin_Bar $adminBar The WP_Admin_Bar instance.
     */
    public function switchBack(WP_Admin_Bar $adminBar): void
    {
        if ($this->isUserSwitched()) {
            $adminBar->add_menu([
                'id'     => 'neon_switch_back',
                'title'  => 'Switch Back to ' . $this->userSwitched()['user_login'],
                'href'   => admin_url('admin-post.php?action=neon_switch_user&switch_back=1'),
                'parent' => 'top-secondary',
                'meta'   => ['class' => 'switch-back-menu'],
            ]);
        }
    }

    /**
     * Adds a "Switch User" menu item and a list of users to switch to in the admin bar.
     *
     * @param WP_Admin_Bar $adminBar The WP_Admin_Bar instance.
     */
    public function userLists(WP_Admin_Bar $adminBar): void
    {
        if ($this->isSuperAdmin()) {
            $adminBar->add_menu([
                'id'     => 'neon_switch_user',
                'title'  => 'Switch User',
                'href'   => '#',
                'parent' => 'top-secondary',
                'meta'   => ['class' => 'switch-user-menu']
            ]);

            $users = get_users([
                'fields'  => ['ID', 'user_login'],
                'exclude' => [$this->user->ID],
            ]);

            foreach ($users as $user) {
                $adminBar->add_menu([
                    'parent' => 'neon_switch_user',
                    'id'     => 'neon_switch_user_' . $user->ID,
                    'title'  => $user->user_login,
                    'href'   => admin_url('admin-post.php?action=neon_switch_user&switch_to=' . $user->ID),
                ]);
            }
        }
    }

    /**
     * Adds a "Switch User" link to the user row actions.
     *
     * @param array $actions An array of row actions.
     * @param WP_User $user The user object.
     * @return array An array of row actions.
     */
    public function rowAction(array $actions, WP_User $user): array
    {
        if ($this->isSuperAdmin() && !in_array('administrator', $user->roles) && !in_array('super_admin', $user->roles)) {
            $actions['neon_switch_user'] = '<a href="' . admin_url('admin-post.php?action=neon_switch_user&switch_to=' . $user->ID) . '">Switch User</a>';
        }

        return $actions;
    }

    /**
     * Handles switching to a different user or switching back to the original user.
     *
     * This method processes the switch user requests and updates the user session.
     */
    public function switchUser(): void
    {
        $user_id = 0;
        if (isset($_GET['switch_to'])) {
            $user_id = (int)sanitize_text_field($_GET['switch_to']);
            $user    = get_user_by('ID', $user_id);

            if (!$user instanceof WP_User) {
                return;
            }
        }

        if (isset($_GET['switch_back'])) {
            $user_id = $this->userSwitched()['user_id'];
            $user    = get_user_by('ID', $user_id);

            if (!$user instanceof WP_User) {
                return;
            }
        }

        if ($user_id === 0) {
            return;
        }

        wp_set_auth_cookie($user_id);
        wp_set_current_user($user_id);
        wp_redirect(add_query_arg([
            'switch_status' => 'success'
        ], admin_url()));
        exit;
    }

    /**
     * Displays a notification indicating the successful switch to a different user.
     */
    public function displayNotification(): void
    {
        $user = null;

        if (isset($_GET['switch_status'])) {
            $user = $this->user;
        }

        if ($user) {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p>Switched to: <strong>' . esc_html($user->display_name) . '</strong>.</p>';
            echo '</div>';
        }
    }

    /**
     * Adds a "Switch Back to Original User" link to the footer.
     */
    public function footerSwitchBack(): void
    {
        if ($this->isUserSwitched()) {
            echo '<div class="switch-back-footer">';
            echo '<a href="' . esc_html(admin_url('admin-post.php?action=neon_switch_user&switch_back=1')) . '">Switch Back to ' . esc_html($this->userSwitched()['user_login']) . '</a>';
            echo '</div>';
        }
    }

    /**
     * Checks if the current user is a super admin.
     *
     * @return bool True if the current user is a super admin, false otherwise.
     */
    private function isSuperAdmin(): bool
    {
        if ($this->isSuperAdmin === null) {
            $this->isSuperAdmin = current_user_can('manage_network')
                || current_user_can('manage_options')
                || $this->user->has_prop('administrator');
        }

        return $this->isSuperAdmin;
    }

    /**
     * Checks if the current user has switched from another user.
     *
     * @return bool True if the user has switched, false otherwise.
     */
    private function isUserSwitched(): bool
    {
        if ($this->isSwitched === null) {
            $this->isSwitched = false;

            if ($this->userSwitched() !== []) {
                $this->isSwitched = true;
            }
        }

        return $this->isSwitched;
    }

    /**
     * Retrieves the switched user information from cookies.
     *
     * @return array An array containing user login and user ID, or an empty array if not switched.
     */
    private function userSwitched(): array
    {
        if ($this->userSwitched === null) {
            $this->userSwitched = [];

            if (isset($_COOKIE[self::COOKIE_NAME])) {
                $cookie = base64_decode($_COOKIE[self::COOKIE_NAME]);
                $cookie = explode(':', $cookie);
                $user   = get_user_by('login', $cookie[1]);
                if ($user instanceof WP_User && $user->ID === (int)$cookie[2] && $user->ID !== $this->user->ID) {
                    $this->userSwitched = [
                        'user_login' => $user->user_login,
                        'user_id'    => $user->ID,
                    ];
                }
            }
        }

        return $this->userSwitched;
    }
}
