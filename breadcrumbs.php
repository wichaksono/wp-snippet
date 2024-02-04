<?php
class Breadcrumbs
{
    private string $homeTitle = 'Home';

    private string $blogPostsTitle = 'Blog';

    private string $separator = '<span class="separator"> • </span>';

    private ?WP_Post $post;
    private ?WP_Term $taxonomyTerm = null;

    public function __construct()
    {
        global $post;

        $this->post = $post;
    }

    public function toArray(): array
    {
        $breadcrumb = [];
        if ( ! is_front_page()) {

            $breadcrumb[0]['name'] = $this->homeTitle;
            $breadcrumb[0]['link'] = home_url();

            $index = 1;
            if (is_singular()) {
                if ( ! is_page()) {
                    /**
                     *
                     * if single & blogposts is set
                     */
                    if ($this->post->post_type === 'post') {
                        if ( ! empty(get_option('page_for_posts', 0))) {
                            $breadcrumb[$index]['name'] = $this->blogPostsTitle;
                            $breadcrumb[$index]['link'] = get_permalink(get_option('page_for_posts'));
                        }
                    } else {
                        $breadcrumb[$index]['name'] = $this->getCurrentArchiveLabel();
                        $breadcrumb[$index]['link'] = get_post_type_archive_link($this->post->post_type);
                    }

                    $index                      = $index + 1;
                    $breadcrumb[$index]['name'] = $this->taxonomyLabel();
                    $breadcrumb[$index]['link'] = $this->taxonomyLink();

                    $index = $index + 1;
                }

                $breadcrumb[$index]['name'] = get_the_title();
            }

            if (is_archive()) {

                if (is_year()) {
                    $breadcrumb[$index]['name'] = get_the_date('F Y');
                } else if (is_month()) {
                    $breadcrumb[$index]['name'] = get_the_date('F Y');
                } else if (is_author()) {
                    $author = get_queried_object();

                    $breadcrumb[$index]['name']     = 'Author';
                    $breadcrumb[$index + 1]['name'] = $author->display_name;

                } else {
                    $currentTerm = get_queried_object();
                    if (is_tax()) {
                        $taxonomy                   = get_taxonomy($currentTerm->taxonomy);
                        $breadcrumb[$index]['name'] = single_term_title("{$taxonomy->label}: ", false);
                    }

                    if (is_post_type_archive()) {
                        $breadcrumb[$index + 1]['name'] = $currentTerm->label;
                    }


                }
            }

            if (is_404()) {
                $breadcrumb[$index]['name'] = '404';
            }

            if (is_search()) {
                $breadcrumb[$index]['name'] = 'Search:' . sanitize_text_field($_GET['s']);
            }
        }

        return apply_filters('NeonWebId\WP\Zaferina\GPTravel\Breadcrumb', $breadcrumb);
    }

    public function render(): string
    {
        $render      = '';
        $breadcrumbs = $this->toArray();

        foreach ($breadcrumbs as $item) {
            if (isset($item['link'])) {
                $render .= "<a href=\"{$item['link']}\">{$item['name']}</a>";
            } else {
                $render .= $item['name'];
            }
            $render .= $this->separator;
        }

        $lastSeparatorPosition = strrpos($render, $this->separator);
        if ($lastSeparatorPosition !== false) {
            $render = substr($render, 0, $lastSeparatorPosition);
        }

        return sprintf('<div class="breadcrumb gptravel_breadcrumb">%s</div>', $render);
    }

    private function getCurrentTaxonomyName(): string
    {
        $postType        = $this->post->post_type;
        $currentTaxonomy = '';
        $taxonomies      = get_object_taxonomies($postType, 'object');
        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy->hierarchical) {
                $currentTaxonomy = $taxonomy->name;
                break;
            }
        }

        return $currentTaxonomy;
    }

    private function getTaxonomyTerm(): ?WP_Term
    {
        if ($this->taxonomyTerm === null) {
            $taxonomyName = $this->getCurrentTaxonomyName();
            $terms        = wp_get_post_terms($this->post->ID, $taxonomyName);
            if ( ! empty($terms) && ! is_wp_error($terms)) {
                // Mengambil term pertama
                $this->taxonomyTerm = $terms[0];
            }
        }

        return $this->taxonomyTerm;
    }

    private function taxonomyLabel(): string
    {
        $taxonomy = $this->getTaxonomyTerm();
        if ($taxonomy !== null) {
            return $taxonomy->name;
        }

        return '';
    }

    private function taxonomyLink()
    {
        $taxonomy = $this->getTaxonomyTerm();

        return get_term_link($taxonomy);
    }

    private function getCurrentArchiveLabel(): string
    {
        $archive = get_post_type_object($this->post->post_type);

        return $archive->label;
    }
}
