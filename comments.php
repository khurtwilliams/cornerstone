<?php
// =============================================================================
// COMMENTS.PHP - Comments Template
// =============================================================================
?>

<?php
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (comments_open()) : ?>
        <div id="respond" class="comment-respond">
            <h3 id="reply-title" class="comment-reply-title">
                <?php
                $comment_count = get_comments_number();
                if ($comment_count == 0) {
                    _e('Leave a Comment', 'indieweb-minimalist');
                } else {
                    printf(_n('One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $comment_count, 'indieweb-minimalist'), number_format_i18n($comment_count), get_the_title());
                }
                ?>
            </h3>

            <form action="<?php echo site_url('/wp-comments-post.php'); ?>" method="post" id="commentform" class="comment-form h-entry">
                <div class="comment-form-author">
                    <label for="author"><?php _e('Name', 'indieweb-minimalist'); ?> <?php if ($req) echo '*'; ?></label>
                    <input id="author" name="author" type="text" class="p-author h-card" value="<?php echo esc_attr($comment_author); ?>" size="30" <?php if ($req) echo 'required'; ?> />
                </div>

                <div class="comment-form-email">
                    <label for="email"><?php _e('Email', 'indieweb-minimalist'); ?> <?php if ($req) echo '*'; ?></label>
                    <input id="email" name="email" type="email" class="u-email" value="<?php echo esc_attr($comment_author_email); ?>" size="30" <?php if ($req) echo 'required'; ?> />
                </div>

                <div class="comment-form-url">
                    <label for="url"><?php _e('Website', 'indieweb-minimalist'); ?></label>
                    <input id="url" name="url" type="url" class="u-url" value="<?php echo esc_attr($comment_author_url); ?>" size="30" />
                </div>

                <div class="comment-form-comment">
                    <label for="comment"><?php _e('Comment', 'indieweb-minimalist'); ?> *</label>
                    <textarea id="comment" name="comment" class="e-content p-summary" cols="45" rows="8" required></textarea>
                </div>

                <div class="form-submit">
                    <input name="submit" type="submit" id="submit" class="submit" value="<?php _e('Post Comment', 'indieweb-minimalist'); ?>" />
                    <input type="hidden" name="comment_post_ID" value="<?php echo get_the_ID(); ?>" id="comment_post_ID" />
                    <input type="hidden" name="comment_parent" id="comment_parent" value="0" />
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if (have_comments()) : ?>
        <div class="comment-list-wrapper">
            <h3 class="comments-title">
                <?php
                $comment_count = get_comments_number();
                printf(_n('One comment', '%1$s comments', $comment_count, 'indieweb-minimalist'), number_format_i18n($comment_count));
                ?>
            </h3>

            <ol class="comment-list">
                <?php
                wp_list_comments(array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 50,
                    'callback'    => 'indieweb_minimalist_comment',
                ));
                ?>
            </ol>

            <?php the_comments_pagination(array(
                'prev_text' => __('Previous Comments', 'indieweb-minimalist'),
                'next_text' => __('Next Comments', 'indieweb-minimalist'),
            )); ?>
        </div>
    <?php endif; ?>
</div>

<?php
// Custom comment callback function
function indieweb_minimalist_comment($comment, $args, $depth) {
    ?>
    <li <?php comment_class('h-entry'); ?> id="comment-<?php comment_ID(); ?>">
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-author p-author h-card vcard">
                <?php echo get_avatar($comment, 50, '', '', array('class' => 'u-photo')); ?>
                <div class="comment-metadata">
                    <span class="fn p-name"><?php echo get_comment_author_link(); ?></span>
                    <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>" class="u-url">
                        <time class="dt-published" datetime="<?php echo get_comment_date('c'); ?>">
                            <?php printf(__('%1$s at %2$s', 'indieweb-minimalist'), get_comment_date(), get_comment_time()); ?>
                        </time>
                    </a>
                </div>
            </div>

            <div class="comment-content e-content">
                <?php comment_text(); ?>
            </div>

            <div class="comment-reply">
                <?php
                comment_reply_link(array_merge($args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth']
                )));
                ?>
            </div>
        </article>
    <?php
}
?>
