<?php
// =============================================================================
// FOOTER.PHP - Site Footer
// =============================================================================
?>

<footer id="colophon" class="site-footer" role="contentinfo">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widget-areas">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="container">
                <div class="footer-content">
                    <p class="copyright">&copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></p>

                    <div class="rss-link">
                        <a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('RSS Feed', 'indieweb-minimalist'); ?>">
                            <?php _e('RSS', 'indieweb-minimalist'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
