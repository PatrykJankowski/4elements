<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#67b0d6">
    <link rel="manifest" href="wp-content/themes/4elements/manifest.json">
    <link rel="stylesheet" type="text/css" href="/wp-content/themes/4elements/style.css" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133650290-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-133650290-1');
    </script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <div class="nav">
        <a href="/" title="4elements - nauka pływania, Warszawa">
            <img class="nav__logo" alt="4elements - nauka pływania, Warszawa" src="/wp-content/themes/4elements/img/logo.png" />
        </a>

        <nav class="nav__nav-desktop">
            <?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
        </nav>

        <div class="nav__toggle-button" onclick="slideToggle()">
            <div id="nav__toggle-icon" class="nav__toggle-icon"></div>
        </div>

        <div id="nav__nav-mobile" class="nav__nav-mobile">
            <?php wp_nav_menu( array( 'theme_location' => 'mobile-menu' ) ); ?>
        </div>
    </div>

    <!-- closed in footer.php -->
    <main>

    <?php if ( is_front_page() && !is_home() ) { ?>
        <header id="header">
            <section class="branding">
                <?php dynamic_sidebar('slogan'); ?>
            </section>
        </header>
    <?php } ?>
