<?php

class NeonAutoPageBreak {
	private $page_break = '<!--nextpage-->';
	private $page_break_at_paragraph = 3;

	public function __construct() {
		add_action('customize_register', [$this, 'pageBreakOption']);
		add_action('the_posts', [$this, 'autoPageBreak'], 10, 2);
		add_filter('the_content', [$this, 'pageBreakPagination']);
		add_filter('wp_link_pages_args', [$this, 'paginationConfig']);
		add_action('wp_head', [$this, 'pageBreakPaginationStyle']);
	}

	public function pageBreakOption($wp_customize) {
		$wp_customize->add_section('page_break_option', array(
			'title' => 'PageBreak Option',
			'description' => 'Setting Page Break',
			'priority' => 160,
		));

		$wp_customize->add_setting('use_auto_page_break', array(
			'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
		));

		$wp_customize->add_control('use_auto_page_break', array(
			'label' => 'Gunakan AutoPageBreak',
			'section' => 'page_break_option',
			'type' => 'checkbox',
		));

		$wp_customize->add_setting('page_break_at_paragraph', array(
			'default' => $this->page_break_at_paragraph,
			'sanitize_callback' => 'sanitize_text_field',
		));

		$wp_customize->add_control('page_break_at_paragraph', array(
			'label' => 'Break Pada Paragraf Ke',
			'section' => 'page_break_option',
			'type' => 'number',
			'input_attrs' => array(
				'min' => 3,
				'step' => 1,
			),
			'active_callback' => function() {
				return get_theme_mod('use_auto_page_break', false);
			},
		));
	}

	public function autoPageBreak($posts, $query) {
		if ($query->is_main_query() &&
		    ($query->is_single() || $query->is_page()) &&
		    $this->autoPageBreakIsEnabled()
		) {
			$break_at_paragraph = get_theme_mod('page_break_at_paragraph', $this->page_break_at_paragraph);
			foreach ($posts as $post) {
				$post->post_content = $this->setPageBreak($post->post_content, $break_at_paragraph);
			}
		}

		return $posts;
	}

	public function paginationConfig() {
		return $this->paginationArgs();
	}

	public function pageBreakPagination($content) {
		global $multipage, $post;
;
		if ( ! empty($_GET['show']) && $_GET['show'] == 'all' ) {
			return $post->post_content;
		}

		if (
			$multipage &&
			$this->autoPageBreakIsEnabled() &&
			! str_contains( $content, '<div class="neon-auto-page-break-pagination">' )
		) {
			$content .= wp_link_pages($this->paginationArgs());
		}

		return $content;
	}

	public function pageBreakPaginationStyle() {
		?>
		<style>
            .neon-auto-page-break-pagination {
                display: flex;
                flex-wrap: wrap;
            }

            .neon-auto-page-break-pagination div {
                flex:1 1 50%;
            }

            .neon-auto-page-break-pagination a {
                text-decoration:none;
            }

            .neon-auto-page-break-pagination .neon-show-all-page-content {
                text-align:right;
            }

            .neon-auto-page-break-pagination .current {
	            font-weight: bold;
            }

            @media (max-width: 500px) {
                .neon-auto-page-break-pagination div {
                    flex-basis: 100%;
                    text-align:center;
                }
            }
		</style>
		<?php
	}
	protected function paginationArgs() {
		global $post;

		$before = '<div class="neon-auto-page-break-pagination"><div class="pagination"><span class="prefix">Halaman: </span>';

		$link_show_all   = add_query_arg([
			'show'=> 'all'
		], get_permalink($post));

		$after  = '</div><div class="neon-show-all-page-content"><a href="'. $link_show_all .'">Show All</a></div>';

		return [
			'before'      => $before,
			'after'       => $after,
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
			'next_or_number' => 'number',
			'separator'   => ' ',
			'aria_current'     => 'page',
			'nextpagelink'=> __( 'Next page' ),
			'previouspagelink'=> __( 'Previous page' ),
			'pagelink'    => '%',
			'echo'        => false
		];
	}

	protected function setPageBreak($post_content, $break_at_paragraph = 0) {
		if ( empty($break_at_paragraph) ) {
			return $post_content;
		}

		if ( str_contains( $post_content, $this->page_break ) ) {
			return $post_content;
		}

		$paragraphs = explode('</p>', $post_content);
		$n = 0;
		$_content = '';
		foreach ($paragraphs as $paragraph) {
			$_content .= $paragraph . '</p>';
			if ( $n == $break_at_paragraph ) {
				$_content .= $this->page_break;
				$n = 0;
			}

			$n++;
		}

		return $_content;
	}

	protected function autoPageBreakIsEnabled() {
		return get_theme_mod('use_auto_page_break', true);
	}
}

new NeonAutoPageBreak();
