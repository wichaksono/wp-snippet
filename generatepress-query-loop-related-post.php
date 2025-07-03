<?php
/**
 * Memodifikasi query loop GenerateBlocks untuk menampilkan related posts
 * berdasarkan tag dan kategori post yang sedang dibuka.
 *
 * Cara kerja:
 * - Hanya dijalankan jika block GenerateBlocks memiliki class "--related-post" (Grid > Advanced > Additional CSS class(es))
 * - Mengambil tag dan kategori dari post saat ini
 * - Menyusun tax_query dengan relasi OR (tag OR kategori)
 * - Menghindari post duplikat (post sekarang dikecualikan)
 * - Mengabaikan sticky post
 * - Menggunakan transient untuk cache 1 jam agar lebih efisien
 *
 * Cara pakai:
 * 1. Tambahkan class `--related-post` di Query Loop block GenerateBlocks
 * 2. Tambahkan kode ini ke file `functions.php` atau plugin custom
 * 3. Pastikan post memiliki tag/kategori yang relevan agar hasil muncul
 *
 * @hook generateblocks_query_loop_args
 * @param array $query_args    Argumen query WP_Query default dari GenerateBlocks
 * @param array $attributes    Atribut block GenerateBlocks (termasuk className)
 * @return array               Argumen query yang dimodifikasi jika cocok
 */
add_filter( 'generateblocks_query_loop_args', function( $query_args, $attributes ) {

	// Jalankan hanya jika className pada Query Loop mengandung '--related-post'
	if (
		empty( $attributes['className'] ) ||
		strpos( $attributes['className'], '--related-post' ) === false
	) {
		return $query_args;
	}

	global $post;

	// Pastikan ada konteks post aktif
	if ( ! isset( $post->ID ) ) {
		return $query_args;
	}

	// Gunakan transient agar query tidak berulang dan lebih ringan
	$transient_key   = 'related_posts_tax_' . $post->ID;
	$tax_query_args  = get_transient( $transient_key );

	if ( false === $tax_query_args ) {
		// Ambil tag & kategori dari post sekarang
		$tag_ids      = wp_get_post_terms( $post->ID, 'post_tag', [ 'fields' => 'ids' ] );
		$category_ids = wp_get_post_terms( $post->ID, 'category', [ 'fields' => 'ids' ] );

		$tax_query = [];

		// Masukkan filter tag
		if ( ! empty( $tag_ids ) ) {
			$tax_query[] = [
				'taxonomy' => 'post_tag',
				'field'    => 'term_id',
				'terms'    => $tag_ids,
			];
		}

		// Masukkan filter kategori
		if ( ! empty( $category_ids ) ) {
			$tax_query[] = [
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => $category_ids,
			];
		}

		// Gunakan OR jika ada keduanya
		if ( count( $tax_query ) > 1 ) {
			$tax_query = [
				'relation' => 'OR',
				...$tax_query, // PHP 7.4+ spread operator
			];
		}

		// Simpan ke cache selama 1 jam
		$tax_query_args = $tax_query;
		set_transient( $transient_key, $tax_query_args, HOUR_IN_SECONDS );
	}

	// Jika ada hasil taksonomi, ubah query-nya
	if ( ! empty( $tax_query_args ) ) {
		$query_args['tax_query']             = $tax_query_args;
		$query_args['post__not_in']          = [ $post->ID ]; // Hindari post sendiri
		$query_args['ignore_sticky_posts']   = true;           // Abaikan sticky
		$query_args['orderby']               = 'date';         // Urut berdasarkan tanggal
		$query_args['order']                 = 'DESC';         // Tampilkan yang terbaru dulu
	}

	return $query_args;

}, 10, 2 );
