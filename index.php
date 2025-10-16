<?php
// =============================================================================
// INDEX.PHP - Main Template
// =============================================================================
?>

<?php get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="posts-container">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('h-entry'); ?>>
                        <?php if (is_sticky()) : ?>
                            <div class="sticky-post-label">
                                <?php _e('Featured Post', 'cornerstone'); ?>
                            </div>
                        <?php endif; ?>

			<?php if (has_post_thumbnail()) : ?>
				<div class="post-thumbnail wp-caption">
				        <a href="<?php the_permalink(); ?>" class="u-url">
				            <?php the_post_thumbnail('featured-image', array('class' => 'u-photo')); ?>
				        </a>
				        <?php
					        $thumbnail_id = get_post_thumbnail_id();
					        $caption = wp_get_attachment_caption($thumbnail_id);

					        if ($caption) : ?>
						    <p class="wp-caption-text"><?php echo wp_kses_post($caption); ?></p>
					        <?php endif; ?>
				</div>
                        <?php endif; ?>

                        <header class="entry-header">
                            <h2 class="entry-title p-name">
                                <a href="<?php the_permalink(); ?>" class="u-url" rel="bookmark">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <div class="entry-meta">
                                <span class="posted-on">
                                    <?php _e('Posted on', 'cornerstone'); ?>
                                    <time class="dt-published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo get_the_date('l jS F Y'); ?>
                                    </time>
                                    <?php _e('By', 'cornerstone'); ?>
                                    <span class="p-author h-card">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="u-url p-name">
                                            <?php the_author(); ?>
                                        </a>
                                    </span>
                                </span>
                            </div>
                        </header>

                        <div class="entry-content e-content">
                            <?php
                            if (is_home() || is_archive()) {
                                the_excerpt();
                            } else {
                                the_content();
                            }
                            ?>
                        </div>

                        <?php if (!is_single()) : ?>
                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php _e('Read More', 'cornerstone'); ?>
                                </a>
                            </footer>
                        <?php endif; ?>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(array(
                'prev_text' => __('Previous', 'cornerstone'),
                'next_text' => __('Next', 'cornerstone'),
            )); ?>

        <?php else : ?>
            <div class="no-posts">
                <h1><?php _e('Nothing Found', 'cornerstone'); ?></h1>
                <p><?php _e('It looks like nothing was found at this location.', 'cornerstone'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
