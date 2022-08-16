<?php

function disable_new_posts() {
	// Hide sidebar link
	global $submenu;
	unset($submenu['edit.php?post_type=order'][10]);

	// Hide link on listing page
	if ( isset($_GET['post_type']) && $_GET['post_type'] == 'order' ) {
		echo '<style type="text/css">
       .page-title-action{ display:none; }
        </style>';
	}
}
add_action('admin_menu', 'disable_new_posts', 999999);
