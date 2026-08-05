<?php
// =============================================================================
// PAGE.PHP - Page Template for Cornerstone
// =============================================================================
?>

<?php get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('h-entry'); ?>>
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
                    <h1 class="entry-title p-name"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content e-content">
                    <?php the_content(); ?>
                </div>
            </article>

            <?php
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
