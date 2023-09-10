<?php

class WidgetInnerPost {
  
	private static $widget_html;
  
	public function __construct() {
		add_action( 'widgets_init', [$this, 'registerWidgetInnerPosts'] );
    
		add_action('wp_head', function () {
			self::$widget_html = $this->widgetInnerPosts();
		});

		add_filter( 'the_content', [$this, 'insertWidgetAfterParagraph'] );
	}

	public function registerWidgetInnerPosts() {
		register_sidebar(array(
			'name'          => esc_html__( 'Inline Related', 'app-themes' ),
			'id'            => 'inline-related',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		));
	}

	public function insertWidgetAfterParagraph( $content ) {
		if ( ! is_single() ) {
			return $content;
		}

		global $post;
		$paragraph_number = 3;

		$_content = '';
		$n        = 0;
		$blocks   = parse_blocks( $post->content );

		$widgetInlineParagraph = self::$widget_html;

		if ( $blocks ) {

			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) && $block['blockName'] == 'core/paragraph' ) {
					$n ++;
				}

				$_content .= render_block( $block );
				if ( $n == $paragraph_number ) {
					$_content .= $widgetInlineParagraph;
				}
			}

		} else {

			$paragraphs = explode('</p>', $content);

			if ( $paragraphs  ) {
				$count_paragraph = count($paragraphs);

				for ($i=0; $i < $count_paragraph; $i++) {
					$_content .= $paragraphs[$i] . '</p>';
					if ( $i+1 == $paragraph_number ) {
						$_content .= $widgetInlineParagraph;
					}
				}
			}

		}

		return $_content;
	}

	private function widgetInnerPosts() {
		if ( is_active_sidebar( 'inline-related' ) ) :
			ob_start(); ?>
			<div class="inline-related">
				<?php dynamic_sidebar( 'inline-related' );?>
			</div>
			<?php
			return ob_get_clean();
		endif;

		return '';
	}

}

new WidgetInnerPost();

