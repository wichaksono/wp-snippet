<?php

add_filter( 'generateblocks_query_loop_args', function( $query_args, $attributes ) {

	// Cek apakah blok ini punya class "--related-post"
	if (
		empty( $attributes['className'] ) ||
		strpos( $attributes['className'], '--related-post' ) === false
	) {
		return $query_args; // kalau tidak, lewati filter
	}

	global $post;

	// Cek apakah objek $post valid
	if ( ! isset( $post->ID ) ) {
		return $query_args;
	}

	// Buat nama transient unik berdasarkan ID post
	$transient_key = 'related_posts_tag_ids_' . $post->ID;

	// Coba ambil data tag ID dari cache transient
	$tag_ids = get_transient( $transient_key );

	// Kalau belum ada cache, ambil langsung dari database
	if ( false === $tag_ids ) {
		$tag_ids = wp_get_post_terms( $post->ID, 'post_tag', [ 'fields' => 'ids' ] );

		// Simpan hasil tag ID ke transient selama 1 jam
		set_transient( $transient_key, $tag_ids, HOUR_IN_SECONDS );
	}

	// Jika post ini punya tag
	if ( ! empty( $tag_ids ) ) {
		// Tampilkan post lain yang punya tag yang sama
		$query_args['tag__in'] = $tag_ids;

		// Kecualikan post yang sedang dibuka agar tidak muncul di daftar related
		$query_args['post__not_in'] = [ $post->ID ];

		// Abaikan sticky post
		$query_args['ignore_sticky_posts'] = true;

		// Urutkan berdasarkan tanggal terbaru
		$query_args['orderby'] = 'date';
		$query_args['order'] = 'DESC';

		// (Opsional) batasi jumlah post yang ditampilkan
		$query_args['posts_per_page'] = 4;
	}

	// Kembalikan query args yang sudah dimodifikasi
	return $query_args;
}, 10, 2 );
