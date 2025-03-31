<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <h1 class="site-title">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <?php bloginfo('name'); ?>
        </a>
    </h1>
    <p class="site-description"><?php bloginfo('description'); ?></p>
</header>

<nav class="main-navigation">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_class' => 'nav-menu',
        'container' => false,
        'fallback_cb' => false
    ));
    ?>
</nav>

<main class="site-main"> 