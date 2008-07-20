<?php
/*
Plugin Name: Trackping Separator
Version: 2.0.1
Plugin URI: http://mk.netgenes.org/wiki/Trackping_Separator
Description: This plugin provide useful template functions to separate trackbacks and pingbacks from comments. Modified from <a href="http://www.meyerweb.com/eric/tools/wordpress/mw_comments_trackbacks.html">MW Comments/Trackbacks</a>
Author: Thomas Au (MK)
Author URI:  http://mk.netgenes.org/
Update: http://mk.netgenes.org/wiki/Trackping_Separator
*/ 

//Filter to return the number of comments only, trackbacks not included.
function get_comment_only_number() {
    global $wpdb, $tablecomments, $post;
    $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND 
comment_type NOT REGEXP '^(trackback|pingback)$' AND comment_approved = '1'");
    $cnt = count($comments);

    return $cnt;
}

//This function return the trackbacks + pingbacks. If the parameter is equal to 'count', the number is returned instead of the contents.
//Newly added second parameter: $type allow u to return specific type of backs
function trackpings($param = '', $type = 'both') {
	global $wpdb, $tablecomments, $post;
	$sql = "SELECT * FROM $wpdb->comments WHERE comment_approved = '1' AND comment_post_ID = $post->ID AND comment_type ";
	switch($type) {
	   case 'trackback':
	       $sql .= "= 'trackback'";
	       break;
	   case 'pingback':
	       $sql .= "= 'pingback'";
	       break;
           case 'comment':
               $sql .= "= ''";
	   default:
	       $sql .= "REGEXP '^(trackback|pingback)$'";
    }
	$trackbacks = $wpdb->get_results($sql);
	if ('count' == $param) {
		return count($trackbacks);
	} else {
		return $trackbacks;
	}
}

//Show a list of trackbacks/pingbacks in a page
function listtrackpings($type = 'both', $template='<li id="trackback-%id"><a href="%url">%origin</a><br />%content</li>', $dateformat='') {
    if ($dateformat == '') {
        $dateformat = get_settings('date_format');
    }
    if ($trackbacks = trackpings("", $type)) {
        foreach ($trackbacks as $trackback) {
            $pattern = array('%id', '%url', '%origin', '%content', '%date');
            $vars = array($trackback->comment_ID, $trackback->comment_author_url, $trackback->comment_author, $trackback->comment_content, mysql2date($dateformat, $trackback->comment_date));
            echo str_replace($pattern, $vars, $template) . "\n";
        }
    }
}

function isComment($comment){
    return $comment->comment_type != 'trackback' && $comment->comment_type != 'pingback';
}

function filter_trackpings($comments, $postId) {
    return array_filter($comments, 'isComment');
}

add_filter('get_comments_number', 'get_comment_only_number', 1, 0);
add_filter('comments_array', 'filter_trackpings', 1, 2);

?>

