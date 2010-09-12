<?php
add_filter('the_content', 'ddleetspeak_filter_the_content');
function ddleetspeak_filter_the_content($content) {
    global $DDLeetSpeak, $post;
    // Check if the there is a meta keyword for this page/post.
    if(in_array('leet', get_post_custom_keys($post->ID))) {
        $content = $DDLeetSpeak->leetize($content);
    } else {
        // Find all tagged text -- [leet][/leet]
        preg_match_all('!\[leet\].*?\[/leet\]!', $content, $matches);
        $matches = $matches[0];
        foreach($matches as $string) {
            $content = str_replace($string, htmlspecialchars($DDLeetSpeak->leetize(str_replace(array('[leet]','[/leet]'), '', $string)), ENT_COMPAT, 'UTF-8'), $content);
        }
    }
    return $content;
}