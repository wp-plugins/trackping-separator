<?php
/*
Plugin Name: Trackping Separator
Version: 1.1.1
Plugin URI: http://mk.netgenes.org/wiki/Trackping_Separator
Description: This plugin provide useful template functions to separate 
trackbacks and pingbacks from comments. Modified from <a 
href="http://www.meyerweb.com/eric/tools/wordpress/mw_comments_trackbacks.html">MW 
Comments/Trackbacks</a>
Author: Thomas Au (MK)
Author URI:  http://mk.netgenes.org/
Update: http://mk.netgenes.org/wiki/Trackping_Separator
*/ 

//This function has the same effect as template function comments_number(), but it return only the number of comments. Track/Ping backs are not included.
function comments_only_number($no='', $one='', $many='') {
    global $wpdb, $tablecomments, $post;
    $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1'");
    $cnt = count($comments);
if (!$cnt)
    echo $no;
elseif ($cnt == 1)
    echo $one;
else
    echo str_replace("%", $cnt, $many);
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
	       $sql .= "!= ''";
    }
	$trackbacks = $wpdb->get_results($sql);
	if ('count' == $param) {
		return count($trackbacks);
	} else {
		return $trackbacks;
	}
}

//Same as comments_popup_link(), but only return the link with number of comments, Track/Ping backs are not included. (Copy and Paste from source :P)
function comments_only_popup_link($zero='No Comments', $one='1 Comment', $more='% Comments', $CSSclass='', $none='Comments Off') {
    global $id, $wpcommentspopupfile, $wpcommentsjavascript, $post, $wpdb;
    global $comment_count_cache;

	if (! is_single() && ! is_page()) {
        if ( !isset($comment_count_cache[$id]))
            $comment_count_cache[$id] = $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_post_ID = $id AND comment_approved = '1';");
    
            $number = $comment_count_cache[$id];
    
        if (0 == $number && 'closed' == $post->comment_status && 'closed' == $post->ping_status) {
            echo $none;
            return;
        } else {
            if (!empty($post->post_password)) { // if there's a password
                if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
                    echo('Enter your password to view comments');
                    return;
                }
            }
            echo '<a href="';
            if ($wpcommentsjavascript) {
                if ( empty($wpcommentspopupfile) )
                    $home = get_settings('home');
                else
                    $home = get_settings('siteurl');
                echo $home . '/' . $wpcommentspopupfile.'?comments_popup='.$id;
                echo '" onclick="wpopen(this.href); return false"';
            } else {
                // if comments_popup_script() is not in the template, display simple comment link
                comments_link();
                echo '"';
            }
            if (!empty($CSSclass)) {
                echo ' class="'.$CSSclass.'"';
            }
            echo '>';
            comments_only_number($zero, $one, $more);
            echo '</a>';
        }
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
?>
