<?php
// =============================================================================
// HEADER.PHP - Site Header
// =============================================================================
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header" role="banner">
	<div class="container">

<!-- h-card and site-branding -->

<div class="site-branding">

<!-- h-card -->
<div class="h-card screen-reader-text">
    <?php
    $author = get_user_by('login', 'khurtwilliams');
    if (!$author) {
        $author = get_user_by('ID', 1);
    }
    if ($author) :
        // Get the photo URL
        $photo_url = '';
        if (has_custom_logo()) {
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($logo) {
                $photo_url = $logo[0];
            }
        }
        if (empty($photo_url)) {
            // Fallback to Gravatar
            $photo_url = get_avatar_url($author->ID, array('size' => 200));
        }
    ?>
        <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($author->display_name); ?>" class="u-photo" width="200" height="200" />

        <span class="p-name">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="u-url u-uid" rel="me">
                <?php echo esc_html($author->display_name); ?>
            </a>
        </span>

        <?php
        $bio = get_the_author_meta('description', $author->ID);
        if ($bio) : ?>
            <span class="p-note"><?php echo esc_html($bio); ?></span>
        <?php endif; ?>
    <?php endif; ?>
</div>
<!-- end h-card -->
    
    <!-- Visible branding -->
    <?php if (has_custom_logo()) : ?>
        <div class="site-logo">
            <?php the_custom_logo(); ?>
        </div>
    <?php endif; ?>
    
    <div class="site-title-wrapper">
        <h1 class="site-title">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php bloginfo('name'); ?>
            </a>
        </h1>
        
        <?php $description = get_bloginfo('description', 'display'); ?>
        <?php if ($description || is_customize_preview()) : ?>
            <p class="site-description"><?php echo $description; ?></p>
        <?php endif; ?>
    </div>
</div>
<!-- end h-card and site-branding -->

            <nav id="site-navigation" class="main-navigation" role="navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-icon"></span>
                    <span class="menu-text"><?php _e('Menu', 'cornerstone'); ?></span>
                </button>
                
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'primary-menu',
                    'fallback_cb'    => 'wp_page_menu',
                ));
                ?>
            </nav>
        </div>
    </header>
