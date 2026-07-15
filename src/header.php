<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#67b0d6">
    <link rel="manifest" href="<?php echo esc_url(get_template_directory_uri() . '/manifest.json'); ?>">
    <link rel="stylesheet" type="text/css" href="/wp-content/themes/4elements/style.css" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133650290-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-133650290-1');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '523605829185138');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" alt="" role="presentation" src="https://www.facebook.com/tr?id=523605829185138&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <div class="nav">
        <a href="/" title="4elements – nauka pływania, Warszawa">
            <img class="nav__logo" alt="4elements - nauka pływania, Warszawa" src="/wp-content/themes/4elements/img/logo.svg" width="146" height="60" />
        </a>

        <nav class="nav__nav-desktop">
            <?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
        </nav>

        <div class="nav__toggle-button" onclick="slideToggle()">
            <div id="nav__toggle-icon" class="nav__toggle-icon"></div>
        </div>

        <div id="nav__nav-mobile" class="nav__nav-mobile">
            <?php wp_nav_menu(array('theme_location' => 'mobile-menu')); ?>
        </div>
    </div>

    <!-- closed in footer.php -->
    <main>

    <?php if (is_front_page() && !is_home()) { ?>
        <header id="header">
            <h1 class="screen-reader-text">Nauka pływania dla dzieci i dorosłych w Warszawie</h1>
            <div class="slider">
                <img class="slider__img" alt="Nauka pływania dla dzieci w Warszawie" src="/wp-content/themes/4elements/img/nauka_plywania_dla_dzieci@1920.jpg" fetchpriority="high">
                <img class="slider__img" alt="Zimowy obóz sportowy dla dzieci" src="/wp-content/themes/4elements/img/obozy_zimowe@1920.jpg">

                <div class="slider__caption">
                    <?php dynamic_sidebar('slogan'); ?>
                </div>

                <div class="slider__arrows" aria-label="Sterowanie slajderem">
                    <button type="button" class="slider__arrow slider__arrow--left" onclick="plusDivs(-1)" aria-label="Poprzedni slajd">&#10094;</button>
                    <button type="button" class="slider__arrow slider__arrow--right" onclick="plusDivs(1)" aria-label="Następny slajd">&#10095;</button>
                </div>

                <div class="slider__badges" aria-label="Wybierz slajd">
                    <button type="button" class="slider__badge slider__badge--active" onclick="currentDiv(1)" aria-label="Slajd 1: nauka pływania"></button>
                    <button type="button" class="slider__badge" onclick="currentDiv(2)" aria-label="Slajd 2: obóz sportowy"></button>
                </div>
            </div>
        </header>

    <?php } ?>
