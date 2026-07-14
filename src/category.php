<?php get_header(); ?>

<?php
$category = get_queried_object();
$paged = max(1, (int) get_query_var('paged'));
$category_posts = new WP_Query(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'paged' => $paged,
    'cat' => (int) $category->term_id,
    'ignore_sticky_posts' => true,
));

get_template_part('template-parts/blog-listing', null, array(
    'query' => $category_posts,
    'paged' => $paged,
    'page_title' => sprintf('Kategoria: %s', single_cat_title('', false)),
    'intro_title' => sprintf('Artykuły z kategorii „%s”', single_cat_title('', false)),
    'description' => category_description((int) $category->term_id),
));

wp_reset_postdata();
?>

<?php get_footer(); ?>
