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
    <?php
    comment_form(array(
        'title_reply' => get_comments_number() == 0 ? __('Leave a Comment', 'cornerstone') : sprintf(_n('One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'cornerstone'), number_format_i18n(get_comments_number()), get_the_title()),
        'class_form' => 'comment-form h-entry',
        'comment_field' => '<div class="comment-form-comment"><label for="comment">' . __('Comment', 'cornerstone') . ' *</label><textarea id="comment" name="comment" class="e-content p-summary" cols="45" rows="8" required></textarea></div>',
    ));
    ?>
<?php endif; ?>

    <?php if (have_comments()) : ?>
        <div class="comment-list-wrapper">
            <h3 class="comments-title">
                <?php
                $comment_count = get_comments_number();
                printf(_n('One comment', '%1$s comments', $comment_count, 'cornerstone'), number_format_i18n($comment_count));
                ?>
            </h3>

            <ol class="comment-list">
                <?php
                wp_list_comments(array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 50,
                    'callback'    => 'cornerstone_comment',
                ));
                ?>
            </ol>

            <?php the_comments_pagination(array(
                'prev_text' => __('Previous Comments', 'cornerstone'),
                'next_text' => __('Next Comments', 'cornerstone'),
            )); ?>
        </div>
    <?php endif; ?>
</div>

<?php
// Custom comment callback function
function cornerstone_comment($comment, $args, $depth) {
    ?>
    <li <?php comment_class('h-entry'); ?> id="comment-<?php comment_ID(); ?>">
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-author p-author h-card vcard">
                <?php echo get_avatar($comment, 50, '', '', array('class' => 'u-photo')); ?>
                <div class="comment-metadata">
                    <span class="fn p-name"><?php echo get_comment_author_link(); ?></span>
                    <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>" class="u-url">
                        <time class="dt-published" datetime="<?php echo get_comment_date('c'); ?>">
                            <?php printf(__('%1$s at %2$s', 'cornerstone'), get_comment_date(), get_comment_time()); ?>
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
