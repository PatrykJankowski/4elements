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
        <a href="/" title="4elements – nauka pływania, Warszawa">
            <img class="nav__logo" alt="4elements - nauka pływania, Warszawa" src="/wp-content/themes/4elements/img/logo.svg" />
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
            <div class="slider">
                <img class="slider__img" src="/wp-content/themes/4elements/img/nauka_plywania_dla_dzieci@1920.jpg">
                <img class="slider__img" src="/wp-content/themes/4elements/img/bg.jpg">


                <div class="slider__caption">
                    <?php dynamic_sidebar('slogan'); ?>
                </div>

                <div class="slider__arrows">
                    <button class="slider__arrow slider__arrow--left" onclick="plusDivs(-1)">&#10094;</button>
                    <button class="slider__arrow slider__arrow--right" onclick="plusDivs(1)">&#10095;</button>
                </div>

                <div class="slider__badges">
                    <span class="slider__badge" onclick="currentDiv(1)"></span>
                    <span class="slider__badge" onclick="currentDiv(2)"></span>
                </div>
            </div>
        </header>

    <?php } ?>

    <script>
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
      showDivs(slideIndex += n);
    }

    function currentDiv(n) {
      showDivs(slideIndex = n);
    }

    function showDivs(n) {
      let i;
      let x = document.getElementsByClassName("slider__img");
      let dots = document.getElementsByClassName("slider__badge");
      let slogan = document.getElementsByClassName("slider__slogan");

      if (n > x.length) {slideIndex = 1}
      if (n < 1) {slideIndex = x.length}

      for (i = 0; i < x.length; i++) {
        x[i].style.opacity = "0";
      }

      for (i = 0; i < slogan.length; i++) {
        slogan[i].style.opacity = "0";
      }

      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" slider__badge--active", "");
      }

      x[slideIndex-1].style.opacity = "1";
      x[slideIndex-1].style.transition = "all 3s";
      slogan[slideIndex-1].style.opacity = "1";
      slogan[slideIndex-1].style.transition = "all 3s";
      dots[slideIndex-1].className += " slider__badge--active";
    }
    </script>
