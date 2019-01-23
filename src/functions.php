<?php

add_filter( 'wp_enqueue_scripts', 'remove_shit', PHP_INT_MAX );
function remove_shit(){
    wp_dequeue_script( 'jquery');
    wp_deregister_script( 'jquery');
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'contact-form-7' );
}


add_action('init', function() {
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    // Remove wp emoji
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}, PHP_INT_MAX - 1 );


add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
    return is_array($var) ? array() : '';
}


function add_google_fonts() {
    wp_enqueue_style('google_web_fonts', 'https://fonts.googleapis.com/css?family=Raleway:400,500');
}
add_action( 'wp_enqueue_scripts', 'add_google_fonts' );





add_action( 'after_setup_theme', 'fourelements_setup' );
function fourelements_setup() {
    load_theme_textdomain( '4elements', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails' );
    global $content_width;
    if ( ! isset( $content_width ) ) $content_width = 640;
    register_nav_menus(
        array( 'main-menu' => __( 'Main Menu', '4elements' ) )
    );
}


add_filter( 'the_title', 'fourelements_title' );
function fourelements_title( $title ) {
    if ( $title == '' ) {
        return '&rarr;';
    } else {
        return $title;
    }
}

add_filter( 'wp_title', 'fourelements_filter_wp_title' );
function fourelements_filter_wp_title( $title ) {
    return $title . esc_attr( get_bloginfo( 'name' ) );
}









add_action( 'widgets_init', 'widgets_init' );
function widgets_init() {
    register_sidebar(array(
        'id'            => 'slogan',
        'name'          => __('Slogan', '4elements'),
        'description'   => 'Krótki tekst w nagłówku na stronie głównej',
        'before_title' => '<h1>',
        'after_title'  => '</h1>',
        'before_widget' => '',
        'after_widget'  => '',
    ));
    register_sidebar(array(
        'id'            => 'about',
        'name'          => __('O 4elements', '4elements'),
        'description'   => 'Sekcja z krótkim opisem 4elements na stronie głównej',
        'before_widget' => '',
        'after_widget'  => '',
    ));
    register_sidebar(array(
        'id'            => 'swimming',
        'name'          => __('Nauka pływania', '4elements'),
        'description'   => 'Sekcja o nauce pływania - krótki opis na stronie głównej',
        'before_widget' => '',
        'after_widget'  => '',
    ));
    register_sidebar(array(
        'id'            => 'trainings',
        'name'          => __('Treningi - opis', '4elements'),
        'description'   => 'Sekcja o zajęciach ogólnorozwojowych - krótki opis na stronie głównej',
        'before_widget' => '',
        'after_widget'  => '',
    ));
    register_sidebar(array(
        'id'            => 'camps',
        'name'          => __('Obozy - opis', '4elements'),
        'description'   => 'Sekcja o obozach - krótki opis na stronie głównej',
        'before_widget' => '',
        'after_widget'  => '',
    ));
}