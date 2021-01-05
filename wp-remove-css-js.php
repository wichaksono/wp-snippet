<?php

add_action( 'wp_print_styles', 'tn_dequeue_font_awesome_style' );
function tn_dequeue_font_awesome_style() {

	  # font-awesome id yang didaftarkan
    wp_dequeue_style( 'font-awesome' );
    wp_deregister_style( 'font-awesome' );
}
