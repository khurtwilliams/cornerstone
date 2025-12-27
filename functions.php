<?php
// =============================================================================
// FUNCTIONS.PHP - Theme Setup and Features
// =============================================================================
?>

<?php
/**
 * Cornerstone Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function cornerstone_setup() {
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
    /// add_image_size('featured-image', 1200, 600, true);
    add_image_size('featured-image', 1200, 9999, false);
    add_image_size('related-post-thumb', 300, 200, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'cornerstone'),
        'footer' => __('Footer Menu', 'cornerstone'),
    ));

    // Load text domain for translations
    load_theme_textdomain('cornerstone', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'cornerstone_setup');

// Enqueue styles and scripts
function cornerstone_scripts() {
    $theme = wp_get_theme();
    wp_enqueue_style('cornerstone-style', get_stylesheet_uri(), array(), $theme->get('Version'));
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css', array(), '6.6.0');
    wp_enqueue_script('cornerstone-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'cornerstone_scripts');

// Widget areas
function cornerstone_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'cornerstone'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in the footer.', 'cornerstone'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'cornerstone'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in the footer.', 'cornerstone'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'cornerstone'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in the footer.', 'cornerstone'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'cornerstone_widgets_init');

// Add IndieWeb rel=me links
function cornerstone_add_rel_me() {
    $social_links = get_theme_mod('social_links', array());
    foreach ($social_links as $link) {
        if (!empty($link)) {
            echo '<link rel="me" href="' . esc_url($link) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'cornerstone_add_rel_me');

// Add webmention endpoint discovery
function cornerstone_webmention_discovery() {
    if (function_exists('webmention_discovery')) {
        webmention_discovery();
    }
}
add_action('wp_head', 'cornerstone_webmention_discovery');

// Customize excerpt length
function cornerstone_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'cornerstone_excerpt_length');

// Custom excerpt more
function cornerstone_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'cornerstone_excerpt_more');

// Get related posts
function cornerstone_get_related_posts($post_id, $number = 3) {
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

// Get social media platform from URL
function cornerstone_get_social_platform($url) {
    $url = strtolower($url);

    $platforms = array(
        'mastodon' => 'Mastodon',
        'twitter.com' => 'Twitter',
        'x.com' => 'X',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'youtube' => 'YouTube',
        'tiktok' => 'TikTok',
        'threads' => 'Threads',
        'bluesky' => 'Bluesky',
        'pixelfed' => 'Pixelfed',
        'peertube' => 'PeerTube',
        'lastfm' => 'Last.fm',
        'spotify' => 'Spotify',
        'flickr' => 'Flickr',
        'unsplash' => 'Unsplash',
        'reddit' => 'Reddit',
        'tumblr' => 'Tumblr',
        'medium' => 'Medium',
        'dev.to' => 'Dev.to',
        'ko-fi' => 'Ko-fi',
        'patreon' => 'Patreon',
    );

    foreach ($platforms as $domain => $name) {
        if (strpos($url, $domain) !== false) {
            return $name;
        }
    }

    // Fallback: extract domain from URL
    $parsed = parse_url($url);
    if (isset($parsed['host'])) {
        return str_replace('www.', '', $parsed['host']);
    }

    return 'Social Profile';
}

// Get Font Awesome icon class for social platform
function cornerstone_get_social_icon($url) {
    $url = strtolower($url);

    $icons = array(
        'mastodon.social' => 'fab fa-mastodon',
        'fosstodon.org' => 'fab fa-mastodon',
        'indieweb.social' => 'fab fa-mastodon',
        'bsky.app' => 'fab fa-bluesky',
        'twitter.com' => 'fab fa-twitter',
        'x.com' => 'fab fa-x-twitter',
        'linkedin' => 'fab fa-linkedin',
        'facebook' => 'fab fa-facebook',
        'instagram' => 'fab fa-instagram',
        'github' => 'fab fa-github',
        'youtube' => 'fab fa-youtube',
        'tiktok' => 'fab fa-tiktok',
        'threads' => 'fab fa-threads',
        'pixelfed' => 'fab fa-pixelfed',
        'peertube' => 'fab fa-peertube',
        'lastfm' => 'fab fa-lastfm',
        'spotify' => 'fab fa-spotify',
        'flickr' => 'fab fa-flickr',
        'unsplash' => 'fab fa-unsplash',
        'reddit' => 'fab fa-reddit',
        'tumblr' => 'fab fa-tumblr',
        'medium' => 'fab fa-medium',
        'dev.to' => 'fab fa-dev',
        'ko-fi' => 'fab fa-kofi',
	'patreon' => 'fab fa-patreon',
	'.social' => 'fab fa-mastodon',
    );

    foreach ($icons as $domain => $icon) {
        if (strpos($url, $domain) !== false) {
            return $icon;
        }
    }

    // Fallback icon
    return 'fas fa-link';
}

/**
 * Get ActivityPub avatar URL for a comment.
 * 
 * This function retrieves the Fediverse avatar from the ActivityPub plugin's
 * stored data. It checks multiple possible meta keys where the avatar URL
 * might be stored, and falls back to Gravatar if none are found.
 *
 * @param int    $comment_id    The comment ID.
 * @param string $email         The comment author email (for Gravatar fallback).
 * @param int    $size          The avatar size in pixels.
 * @return string               The avatar URL.
 */
function cornerstone_get_activitypub_avatar($comment_id, $email, $size = 32) {
    // Try to get the avatar from ActivityPub plugin's comment meta
    // The ActivityPub plugin may store avatar URLs in different meta keys
    $avatar_url = '';
    
    // Check for ActivityPub actor icon (newer versions of the plugin)
    $actor_icon = get_comment_meta($comment_id, 'activitypub_actor_icon', true);
    if (!empty($actor_icon)) {
        // The icon might be stored as an array with 'url' key or as a direct URL
        if (is_array($actor_icon) && isset($actor_icon['url'])) {
            $avatar_url = $actor_icon['url'];
        } elseif (is_string($actor_icon)) {
            $avatar_url = $actor_icon;
        }
    }
    
    // Check for avatar_url meta (alternative storage)
    if (empty($avatar_url)) {
        $avatar_url = get_comment_meta($comment_id, 'avatar_url', true);
    }
    
    // Check for protocol meta that might contain avatar info
    if (empty($avatar_url)) {
        $protocol = get_comment_meta($comment_id, 'protocol', true);
        if ($protocol === 'activitypub') {
            // Try to get avatar from actor data
            $actor = get_comment_meta($comment_id, 'activitypub_actor', true);
            if (!empty($actor) && is_array($actor)) {
                if (isset($actor['icon']['url'])) {
                    $avatar_url = $actor['icon']['url'];
                } elseif (isset($actor['icon']) && is_string($actor['icon'])) {
                    $avatar_url = $actor['icon'];
                }
            }
        }
    }
    
    // Check for webmention source avatar (if using Webmention plugin together)
    if (empty($avatar_url)) {
        $avatar_url = get_comment_meta($comment_id, 'semantic_linkbacks_avatar', true);
    }
    
    // If still no avatar found, try using the comment object with get_avatar_url
    // This allows the ActivityPub plugin's filters to work
    if (empty($avatar_url)) {
        $comment = get_comment($comment_id);
        if ($comment) {
            // Use get_avatar_url which goes through WordPress filters
            // The ActivityPub plugin hooks into 'pre_get_avatar_data'
            $avatar_url = get_avatar_url($comment, array('size' => $size));
        }
    }
    
    // Final fallback to Gravatar using email
    if (empty($avatar_url) || strpos($avatar_url, 'gravatar.com/avatar/?') !== false) {
        // Check if it's a mystery person/blank gravatar, use a better default
        $hash = md5(strtolower(trim($email)));
        $avatar_url = 'https://www.gravatar.com/avatar/' . $hash . '?s=' . $size . '&d=mp';
    }
    
    return $avatar_url;
}

// Theme Customizer
function cornerstone_customize_register($wp_customize) {
    // Social Links Section
    $wp_customize->add_section('social_links', array(
        'title'    => __('Social Links', 'cornerstone'),
        'priority' => 130,
    ));
    
    // Add social link fields
    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("social_link_$i", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control("social_link_$i", array(
            'label'   => sprintf(__('Social Link %d', 'cornerstone'), $i),
            'section' => 'social_links',
            'type'    => 'url',
        ));
    }
    
    // Author Bio Section
    $wp_customize->add_section('author_bio', array(
        'title'    => __('Author Bio', 'cornerstone'),
        'priority' => 140,
    ));
    
    $wp_customize->add_setting('show_author_bio', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('show_author_bio', array(
        'label'   => __('Show Author Bio', 'cornerstone'),
        'section' => 'author_bio',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'cornerstone_customize_register');
?>

