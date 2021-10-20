<?php

class Recipe extends Base_Post_Type {
	//private $meta = 'resep_meta';

	public function __construct() {
		parent::__construct( [
			'id'            => 'resep',
			'title'         => 'Resep',
			'supports'      => [ 'title', 'thumbnail' ],
			'menu_position' => 25,
			'menu_icon'     => 'dashicons-food',
			'show_in_rest'  => false,
//			'has_archive'   => false,
			'group'         => [
				[
					'id'                  => 'hashtag',
					'title'               => 'Hashtags',
					'show_in_editor_page' => true
				]
			]
		] );

		$this->attributes();
		add_filter( 'manage_' . $this->post_type_id . '_posts_columns', [ $this, 'set_columns' ] );
		add_action( 'manage_' . $this->post_type_id . '_posts_custom_column', [ $this, 'set_column' ], 10, 2 );
	}



	public function set_columns( $columns ) {
		$old_date = $columns['date'];
		unset( $columns['date'] );
		$columns['shortcode'] = 'Shortcode';
		$columns['date']      = $old_date;

		return $columns;
	}

	public function set_column( $column, $id ) {
		switch ( $column ) {
			case 'shortcode' :
				echo '<input type="text" value="[resep id='. $id .' title=\'true\']" readonly/>';
				break;
		}

		return $column;
	}
}

//new Recipe();
