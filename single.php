<?php
// =============================================================================
// SINGLE.PHP - Single Post Template
// =============================================================================
?>

<?php get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('h-entry'); ?>>
                <?php if (is_sticky()) : ?>
                    <div class="sticky-post-label">
                        <?php _e('Featured Post', 'cornerstone'); ?>
                    </div>
                <?php endif; ?>

<?php if (has_post_thumbnail()) : ?>
    <div class="post-thumbnail wp-caption">
        <?php the_post_thumbnail('featured-image', array('class' => 'u-photo')); ?>
        <?php
        $thumbnail_id = get_post_thumbnail_id();
        $caption = wp_get_attachment_caption($thumbnail_id);

        if ($caption) : ?>
		<p class="wp-caption-text"><?php echo wp_kses_post($caption); ?></p> 
        <?php endif; ?>
    </div>
<?php endif; ?>

		<header class="entry-header">
		 <h1 class="entry-title p-name">
			<a href="<?php the_permalink(); ?>" class="u-url"><?php the_title(); ?></a>
		</h1>
                    
<div class="entry-meta">
    <span class="posted-on">
        <?php _e('Posted on', 'cornerstone'); ?>
        <time class="dt-published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
            <?php echo get_the_date('l jS F Y'); ?>
        </time>
        <?php _e('By', 'cornerstone'); ?>
        <span class="p-author h-card">
            <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', array('class' => 'u-photo')); ?>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="u-url p-name">
                <?php the_author(); ?>
            </a>
        </span>
    </span>
</div>
                </header>

                <div class="entry-content e-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<div class="post-categories">';
                        echo '<span>' . __('Categories:', 'cornerstone') . ' </span>';
                        foreach ($categories as $category) {
                            echo '<a href="' . get_category_link($category->term_id) . '" class="p-category" rel="category tag">' . $category->name . '</a> ';
                        }
                        echo '</div>';
                    }
                    
                    $tags = get_the_tags();
                    if (!empty($tags)) {
                        echo '<div class="post-tags">';
                        echo '<span>' . __('Tags:', 'cornerstone') . ' </span>';
                        foreach ($tags as $tag) {
                            echo '<a href="' . get_tag_link($tag->term_id) . '" class="p-category" rel="tag">' . $tag->name . '</a> ';
                        }
                        echo '</div>';
                    }
                    ?>
                </footer>
	    </article>

            <?php
            // Display ActivityPub Interactions - only on single post pages
            if (is_single()) {
                global $wpdb;
                
                $reposts = $wpdb->get_results($wpdb->prepare(
                    "SELECT comment_ID, comment_author, comment_author_url, comment_author_email 
                     FROM {$wpdb->comments} 
                     WHERE comment_post_ID = %d 
                     AND comment_type = 'repost' 
                     AND comment_approved = '1'",
                    get_the_ID()
                ));
                
                $likes = $wpdb->get_results($wpdb->prepare(
                    "SELECT comment_ID, comment_author, comment_author_url, comment_author_email 
                     FROM {$wpdb->comments} 
                     WHERE comment_post_ID = %d 
                     AND comment_type = 'like' 
                     AND comment_approved = '1'",
                    get_the_ID()
                ));
                
                if (!empty($reposts) || !empty($likes)) : ?>
                    <div class="activitypub-reactions">
                        <h3><?php _e('Fediverse Interactions', 'cornerstone'); ?></h3>
                        
                        <?php if (!empty($likes)) : ?>
                            <div class="ap-likes">
                                <strong><?php printf(_n('%d Like', '%d Likes', count($likes), 'cornerstone'), count($likes)); ?></strong>
                                <div class="reaction-avatars">
                                    <?php foreach ($likes as $like) : ?>
                                        <?php if (!empty($like->comment_author_url)) : ?>
                                            <a href="<?php echo esc_url($like->comment_author_url); ?>" title="<?php echo esc_attr($like->comment_author); ?>" target="_blank" rel="noopener">
                                                <?php echo get_avatar($like->comment_author_email, 32, '', esc_attr($like->comment_author), array('class' => 'reaction-avatar')); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo get_avatar($like->comment_author_email, 32, '', esc_attr($like->comment_author), array('class' => 'reaction-avatar')); ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($reposts)) : ?>
                            <div class="ap-boosts">
                                <strong><?php printf(_n('%d Boost', '%d Boosts', count($reposts), 'cornerstone'), count($reposts)); ?></strong>
                                <div class="reaction-avatars">
                                    <?php foreach ($reposts as $repost) : ?>
                                        <?php if (!empty($repost->comment_author_url)) : ?>
                                            <a href="<?php echo esc_url($repost->comment_author_url); ?>" title="<?php echo esc_attr($repost->comment_author); ?>" target="_blank" rel="noopener">
                                                <?php echo get_avatar($repost->comment_author_email, 32, '', esc_attr($repost->comment_author), array('class' => 'reaction-avatar')); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo get_avatar($repost->comment_author_email, 32, '', esc_attr($repost->comment_author), array('class' => 'reaction-avatar')); ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php } // end is_single() check ?>

            <?php 
            // Author Bio
            if (get_theme_mod('show_author_bio', true)) : ?>
                <div class="author-bio h-card">
                    <div class="author-avatar">
                        <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'u-photo')); ?>
                    </div>
                    <div class="author-info">
                        <h3 class="author-name p-name">
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="u-url">
                                <?php the_author(); ?>
                            </a>
                        </h3>
                        <div class="author-description p-note">
                            <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                        </div>
                        <?php 
                        // Social links from customizer
                        $social_links = array();
                        for ($i = 1; $i <= 5; $i++) {
                            $link = get_theme_mod("social_link_$i");
                            if (!empty($link)) {
                                $social_links[] = $link;
                            }
                        }
                        
                        if (!empty($social_links)) : ?>
                            <div class="author-social">
                                <?php foreach ($social_links as $link) : ?>
                                    <a href="<?php echo esc_url($link); ?>" rel="me" target="_blank"><?php echo esc_url($link); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            // Related Posts
            $related_posts = cornerstone_get_related_posts(get_the_ID());
            if (!empty($related_posts)) : ?>
                <div class="related-posts">
                    <h3><?php _e('Related Posts', 'cornerstone'); ?></h3>
                    <div class="related-posts-grid">
                        <?php foreach ($related_posts as $related) : ?>
                            <article class="related-post h-entry">
                                <?php if (has_post_thumbnail($related->ID)) : ?>
                                    <div class="related-post-thumbnail">
                                        <a href="<?php echo get_permalink($related->ID); ?>" class="u-url">
                                            <?php echo get_the_post_thumbnail($related->ID, 'related-post-thumb', array('class' => 'u-photo')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <h4 class="related-post-title p-name">
                                    <a href="<?php echo get_permalink($related->ID); ?>" class="u-url">
                                        <?php echo get_the_title($related->ID); ?>
                                    </a>
                                </h4>
                                <div class="related-post-date">
                                    <time class="dt-published" datetime="<?php echo esc_attr(get_the_date('c', $related->ID)); ?>">
                                        <?php echo get_the_date('', $related->ID); ?>
                                    </time>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            // Comments
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>

            <?php
            // Post Navigation
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            
            if ($prev_post || $next_post) : ?>
                <nav class="post-navigation" role="navigation">
                    <div class="nav-links">
                        <?php if ($prev_post) : ?>
                            <div class="nav-previous">
                                <a href="<?php echo get_permalink($prev_post->ID); ?>" rel="prev">
                                    <span class="nav-subtitle"><?php _e('Previous Post', 'cornerstone'); ?></span>
                                    <span class="nav-title"><?php echo get_the_title($prev_post->ID); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="nav-next">
                                <a href="<?php echo get_permalink($next_post->ID); ?>" rel="next">
                                    <span class="nav-subtitle"><?php _e('Next Post', 'cornerstone'); ?></span>
                                    <span class="nav-title"><?php echo get_the_title($next_post->ID); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
