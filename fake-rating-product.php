<?php
add_action('wp_head', function() {
	if ( is_singular('produk') ) {
        $product = neon_get_product();

		$price = $product->get_price();
		$description = $product->post()->post_content;

		$reviewCount = get_post_meta($product->get_id(), 'reviewCount', true);
		if ( empty($reviewCount) ) {
			$reviewCount = rand(50, 150);
			update_post_meta($product->get_id(), 'reviewCount', $reviewCount);
		}

		$structure_data = [];

		$structure_data['@context'] = "http://schema.org/";
		$structure_data['@type'] = "Product";
		$structure_data['name'] = $product->get_title();
		$structure_data['url'] = $product->get_link();
		$structure_data['image'] = get_the_post_thumbnail_url($product->get_id(), 'full');
		$structure_data['description'] = $description;

		$structure_data['brand']['@type'] = 'Thing';
		$structure_data['brand']['name'] = $product->get_brand();

		$structure_data['aggregateRating']['@type'] = 'AggregateRating';
		$structure_data['aggregateRating']['ratingValue'] = 5;
		$structure_data['aggregateRating']['reviewCount'] = $reviewCount;

		$structure_data['offers']['@type'] = 'Offer';
		$structure_data['offers']['priceCurrency'] = 'IDR';
		$structure_data['offers']['price'] = $price?: 0;
		$structure_data['offers']['seller'] = 'Dunpack Mitra Industri';

		?>
		<script type='application/ld+json'><?php echo json_encode($structure_data, 128);?></script>
		<?php

	}
});
