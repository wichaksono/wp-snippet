<?php

add_filter('the_content', 'addLinkToTags', 0);
function addLinkToTags($content) {
    $post_tags = get_the_tags();
    if ( $post_tags && is_single() ) {
        foreach($post_tags as $tag) {
            $replacement = sprintf('<a href="%s">%s</a>', get_term_link($tag), $tag->name);
            $target = $tag->name;
            $content = preg_replace('/(<p>.*?)(\b' . $target . '\b)(.*?<\/p>)/i', '$1' . $replacement . '$3', $content, 1);
        }
    }

    return $content;
}
