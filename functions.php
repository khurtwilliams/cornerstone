<?php
// =============================================================================
// FUNCTIONS.PHP - Theme Setup and Features
// =============================================================================
?>

<?php
/**
 * IndieWeb Minimalist Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function indieweb_minimalist_setup() {
    // Add theme support for various features
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('custom-background');
    add_theme_support('custom-header');
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat'
    ));

    // Add custom image sizes
    add_image_size('featured-image', 1200, 600, true);
    add_image_size('related-post-thumb', 300, 200, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'indieweb-minimalist'),
        'footer' => __('Footer Menu', 'indieweb-minimalist'),
    ));

    // Load text domain for translations
    load_theme_textdomain('indieweb-minimalist', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'indieweb_minimalist_setup');

// Enqueue styles and scripts
function indieweb_minimalist_scripts() {
    wp_enqueue_style('indieweb-minimalist-style', get_stylesheet_uri(), array(), '1.0');
    wp_enqueue_script('indieweb-minimalist-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'indieweb_minimalist_scripts');

// Widget areas
function indieweb_minimalist_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'indieweb-minimalist'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in the footer.', 'indieweb-minimalist'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'indieweb-minimalist'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in the footer.', 'indieweb-minimalist'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'indieweb-minimalist'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in the footer.', 'indieweb-minimalist'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'indieweb_minimalist_widgets_init');

// Add IndieWeb rel=me links
function indieweb_minimalist_add_rel_me() {
    $social_links = get_theme_mod('social_links', array());
    foreach ($social_links as $link) {
        if (!empty($link)) {
            echo '<link rel="me" href="' . esc_url($link) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'indieweb_minimalist_add_rel_me');

// Add webmention endpoint discovery
function indieweb_minimalist_webmention_discovery() {
    if (function_exists('webmention_discovery')) {
        webmention_discovery();
    }
}
add_action('wp_head', 'indieweb_minimalist_webmention_discovery');

// Customize excerpt length
function indieweb_minimalist_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'indieweb_minimalist_excerpt_length');

// Custom excerpt more
function indieweb_minimalist_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'indieweb_minimalist_excerpt_more');

// Get related posts
function indieweb_minimalist_get_related_posts($post_id, $number = 3) {
    $related = get_posts(array(
        'category__in' => wp_get_post_categories($post_id),
        'numberposts'  => $number,
        'post__not_in' => array($post_id)
    ));
    
    if (empty($related)) {
        $related = get_posts(array(
            'numberposts'  => $number,
            'post__not_in' => array($post_id),
            'orderby'      => 'rand'
        ));
    }
    
    return $related;
}

// Theme Customizer
function indieweb_minimalist_customize_register($wp_customize) {
    // Social Links Section
    $wp_customize->add_section('social_links', array(
        'title'    => __('Social Links', 'indieweb-minimalist'),
        'priority' => 130,
    ));
    
    // Add social link fields
    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("social_link_$i", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control("social_link_$i", array(
            'label'   => sprintf(__('Social Link %d', 'indieweb-minimalist'), $i),
            'section' => 'social_links',
            'type'    => 'url',
        ));
    }
    
    // Author Bio Section
    $wp_customize->add_section('author_bio', array(
        'title'    => __('Author Bio', 'indieweb-minimalist'),
        'priority' => 140,
    ));
    
    $wp_customize->add_setting('show_author_bio', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('show_author_bio', array(
        'label'   => __('Show Author Bio', 'indieweb-minimalist'),
        'section' => 'author_bio',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'indieweb_minimalist_customize_register');
?>
