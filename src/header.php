<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
    <?php wp_head(); ?>

    <script>
        let navOpened = false;
        let initHeight = 491;

        function slideToggle() {

            let navMobile = document.getElementById('nav-mobile');

            if(navOpened) {
                navOpened = false;
                navMobile.style.height = '0';
                document.getElementById('toggle-button').classList.remove('open');
            }
            else {
                navOpened = true;
                navMobile.style.height = initHeight + 'px';
                document.getElementById('toggle-button').classList.add('open');
            }
        }
    </script>
</head>


<body <?php body_class(); ?>>


    <section id="nav">


        <a href="/4elements">
            <img id="logo" src="<?php echo get_template_directory_uri() . '/img/logo.png'; ?>" />
        </a>


        <nav role="navigation">
            <?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
        </nav>


        <div id="toggle-button" onclick="slideToggle()"><i></i></div>

        <div id="nav-mobile" role="navigation">
            <?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
        </div>

    </section>











    <main> <!-- closed in footer.php -->

    <?php if ( is_front_page() && !is_home() ) { ?>
        <header id="header" role="banner">
            <section id="branding">
                <?php dynamic_sidebar('slogan'); ?>
            </section>
        </header>
    <?php } ?>

