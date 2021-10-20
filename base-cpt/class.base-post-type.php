<?php

class Base_Post_Type
{
	protected $post_type_id;
	protected $slug;
	protected $args = [];

	public function __construct($args)
	{
		$this->args = $args;
		$this->post_type_id = $args['id'];
		$this->slug = apply_filters("{$args['id']}_rewrite_slug", $args['id']);
		add_action('init', [$this, '_register']);

	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function _register()
	{
		$args = $this->args;
		$labels = array(
			'name' => _x($args['title'], 'Post type general name', 'tokoinstan'),
			'singular_name' => _x($args['title'], 'Post type singular name', 'tokoinstan'),
			'menu_name' => _x($args['title'], 'Admin Menu text', 'tokoinstan'),
			'name_admin_bar' => _x($args['title'], 'Add New on Toolbar', 'tokoinstan'),
			'add_new' => __('Add New', 'tokoinstan'),
			'add_new_item' => __('Add New ' . $args['title'], 'tokoinstan'),
			'new_item' => __('New ' . $args['title'], 'tokoinstan'),
			'edit_item' => __('Edit ' . $args['title'], 'tokoinstan'),
			'view_item' => __('View ' . $args['title'], 'tokoinstan'),
			'all_items' => 'All ' . $args['title'],
			'search_items' => __('Search ' . $args['title'], 'tokoinstan'),
			'parent_item_colon' => __('Parent ' . $args['title'] . ':', 'tokoinstan'),
			'not_found' => __('No ' . $args['title'] . ' found.', 'tokoinstan'),
			'not_found_in_trash' => __('No ' . $args['title'] . ' found in Trash.', 'tokoinstan'),
			'featured_image' => _x($args['title'] . ' Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'tokoinstan'),
			'set_featured_image' => _x('Set image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'tokoinstan'),
			'remove_featured_image' => _x('Remove image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'tokoinstan'),
			'use_featured_image' => _x('Use as image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'tokoinstan'),
			'archives' => _x($args['title'] . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'tokoinstan'),
			'insert_into_item' => _x('Insert into ' . $args['title'], 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'tokoinstan'),
			'uploaded_to_this_item' => _x('Uploaded to this ' . $args['title'], 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'tokoinstan'),
			'filter_items_list' => _x('Filter ' . $args['title'] . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'tokoinstan'),
			'items_list_navigation' => _x($args['title'] . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'tokoinstan'),
			'items_list' => _x($args['title'] . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'tokoinstan'),
		);

		$disable_in_front_page = apply_filters("{$args['id']}_publicly_queryable", true);

		$capabilities = [
			'read_post' => 'read_' . $this->post_type_id,
			'edit_post' => 'edit_' . $this->post_type_id,
			'edit_posts' => 'edit_' . $this->post_type_id . 's',
			'edit_others_posts' => 'edit_others_' . $this->post_type_id. 's',
			'publish_posts' => 'publish_' . $this->post_type_id. 's',
			'read_private_posts' => 'read_private_' . $this->post_type_id. 's',
			'delete_post' => 'delete_' . $this->post_type_id
		];

		register_post_type($args['id'],
			array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => $disable_in_front_page,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_admin_bar' => $disable_in_front_page,
				'query_var' => true,
				'rewrite' => array('slug' => $this->slug),
//				'capabilities' => $capabilities,
				'has_archive' => !isset($args['has_archive']) ? true : $args['has_archive'],
				'exclude_from_search' => apply_filters("{$args['id']}_exclude_from_search", !$disable_in_front_page),
				'hierarchical' => empty($args['hierarchical']) ? false : $args['hierarchical'],
				'menu_position' => $args['menu_position'],
				'menu_icon' => $args['menu_icon'],
				'supports' => $args['supports'],
				'show_in_rest' => !empty($args['show_in_rest']) ? $args['show_in_rest'] : false,
			)
		);

		if (!empty($args['group'])) {

			$this->taxonomies($args['id'], $args['group']);
		}
	}

	protected function taxonomies($id, $taxonomy_args)
	{
		foreach ($taxonomy_args as $group) {
			$labels = array(
				'name' => _x($group['title'], 'taxonomy general name', 'tokoinstan'),
				'singular_name' => _x($group['title'], 'taxonomy singular name', 'tokoinstan'),
				'search_items' => __('Search ' . $group['title'], 'tokoinstan'),
				'all_items' => __('All ' . $group['title'], 'tokoinstan'),
				'parent_item' => __('Parent ' . $group['title'], 'tokoinstan'),
				'parent_item_colon' => __('Parent ' . $group['title'] . ':', 'tokoinstan'),
				'edit_item' => __('Edit ' . $group['title'], 'tokoinstan'),
				'update_item' => __('Update ' . $group['title'], 'tokoinstan'),
				'add_new_item' => __('Add New ' . $group['title'], 'tokoinstan'),
				'new_item_name' => __('New ' . $group['title'] . ' Name', 'tokoinstan'),
				'menu_name' => __($group['title'], 'tokoinstan'),
			);

			$args_tax = array(
				'hierarchical' => !empty($group['hierarchical']) ? $group['hierarchical'] : false,
				'labels' => $labels,
				'show_ui' => true,
				'show_admin_column' => !empty($group['show_in_editor_page']) ? $group['show_in_editor_page'] : false,
				'show_in_rest' => !empty($this->args['show_in_rest']) ? $this->args['show_in_rest'] : false,
				'query_var' => true,
				'rewrite' => array('slug' => empty($group['rewrite']) ? $group['id'] : $group['rewrite']),
			);

			register_taxonomy($group['id'], array($id), $args_tax);
		}
	}

	public function getContent($posts_per_page = 5)
	{
		return get_posts([
			'post_type' => $this->post_type_id,
			'posts_per_page' => $posts_per_page,
		]);
	}

	public function getId()
	{
		return $this->post_type_id;
	}

}
