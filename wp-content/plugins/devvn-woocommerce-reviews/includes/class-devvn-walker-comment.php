<?php

defined("ABSPATH") || exit("No script kiddies please!");
if (!class_exists("DevVN_Walker_Post_Comment")) {
    class DevVN_Walker_Post_Comment extends Walker_Comment
    {
        protected function html5_comment($comment, $depth, $args)
        {
            $tag = "div" === $args["style"] ? "div" : "li";
            $commenter = wp_get_current_commenter();
            $show_pending_links = !empty($commenter["comment_author"]);
            $comment_date = isset($comment->comment_date) ? $comment->comment_date : "";
            if ($commenter["comment_author_email"]) {
                $moderation_note = __("Your comment is awaiting moderation.");
            } else {
                $moderation_note = __("Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.");
            }
            echo "\t\t\t<";
            echo $tag;
            echo " id=\"comment-";
            comment_ID();
            echo "\" ";
            comment_class($this->has_children ? "parent" : "", $comment);
            echo ">\r\n\t\t\t<article id=\"div-comment-";
            comment_ID();
            echo "\" class=\"comment-body\">\r\n\t\t\t\t<footer class=\"comment-meta\">\r\n\t\t\t\t\t<div class=\"comment-author vcard\">\r\n\t\t\t\t\t\t";
            if (0 != $args["avatar_size"]) {
                echo get_avatar($comment, $args["avatar_size"]);
            }
            echo "\t\t\t\t\t\t";
            $comment_author = get_comment_author_link($comment);
            if ("0" == $comment->comment_approved && !$show_pending_links) {
                $comment_author = get_comment_author($comment);
            }
            echo sprintf("<b class=\"fn\">%s</b>", $comment_author);
            if (devvn_check_comment_mod($comment)) {
                echo "                            <span class=\"review_qtv\">";
                _e("Administrator", "devvn-reviews");
                echo "</span>\r\n\t\t\t\t\t\t";
            }
            echo "\t\t\t\t\t</div><!-- .comment-author -->\r\n\r\n\t\t\t\t\t";
            if ("0" == $comment->comment_approved) {
                echo "\t\t\t\t\t\t<em class=\"comment-awaiting-moderation\">";
                echo $moderation_note;
                echo "</em>\r\n\t\t\t\t\t";
            }
            echo "\t\t\t\t</footer><!-- .comment-meta -->\r\n\r\n\t\t\t\t<div class=\"comment-content\">\r\n\t\t\t\t\t";
            comment_text();
            echo "\t\t\t\t</div><!-- .comment-content -->\r\n\r\n\t\t\t\t<div class=\"comment-tool\">\r\n\t\t\t\t\t";
            if ("1" == $comment->comment_approved || $show_pending_links) {
                comment_reply_link(array_merge($args, ["add_below" => "div-comment", "depth" => 1, "max_depth" => $args["max_depth"], "before" => "<div class=\"reply\">", "after" => "</div>"]));
            }
            echo "                    <b class=\"dot\">●</b>\r\n                    <a href=\"";
            echo esc_url(get_comment_link($comment, $args));
            echo "\">\r\n                        <time datetime=\"";
            comment_time("c");
            echo "\">\r\n                            ";
            printf(__("%s ago", "devvn-reviews"), human_time_diff(strtotime($comment_date), current_time("timestamp")));
            echo "                        </time>\r\n                    </a>\r\n                    ";
            edit_comment_link(__("Edit"), "<b class=\"dot\">●</b> <span class=\"edit-link\">", "</span>");
            echo "\t\t\t\t</div>\r\n\t\t\t</article><!-- .comment-body -->\r\n\t\t\t";
        }
    }
}

?>