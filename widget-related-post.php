<?php
/**
 * Related Post by Category only on single page
 */
add_shortcode('related_post_widget', 'neon_related_by_term');
function neon_related_by_term() {
	if ( is_single() ) {
		global $post;
		$categories = get_the_category();

		$cat_ids = [];
		foreach($categories as $cat) {
			$cat_ids[] = $cat->term_id;
		}

		$query = new WP_Query([
			'post_parent__not_in' => [$post->ID],
			'cat' => implode(',', $cat_ids); 
		]);

		ob_start();
		if ( $query->have_posts() ) {
			?>
			<div class="widget-related-post widget-neon-related">
    			<h2 class="widget-title widget-post-title related-post-widget-title">Related Posts</h2>
			<?php
			while( $query->have_posts() ) {
				$query->the_post();
				?>
				<div class="widget-post-list related-post-list">
			        <div class="widget-post-feature-image related-post-feature-image">
			            <a href="<?php the_permalink();?>" aria-label="Thumbnail"
			               class="widget-post-link related-post-link related-post-image"><?php the_post_thumbnail('thumbnail');?></a>
			        </div>
			        <div class="widget-post-content related-post-content">
			            <a href="<?php the_permalink();?>" aria-label="Related Post"
			               title="<?php the_title();?>"
			               class="widget-post-content-link related-post-link related-post-title"><?php the_title();?></a>
			            <div class="widget-post-meta related-meta">
			                <time datetime="<?php echo get_the_date('c');?>" class="widget-post-date related-post-date"><?php echo get_the_date();?></time>
			            </div>
			        </div>
			    </div>
				<?php
			}

			echo '</div>';

			wp_reset_postdata();
		}

		return ob_get_clean();
	}

	return "";
}
