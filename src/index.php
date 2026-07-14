<?php get_header(); ?>

<?php
$paged = max(1, (int) get_query_var('paged'));
$blog_posts = $GLOBALS['wp_query'];
$page_title = is_archive() ? get_the_archive_title() : 'Blog o nauce pływania i sportach wodnych';
$archive_description = is_archive() ? get_the_archive_description() : '';

get_template_part('template-parts/blog-listing', null, array(
    'query' => $blog_posts,
    'paged' => $paged,
    'page_title' => $page_title,
    'intro_title' => is_archive() ? 'Artykuły w wybranym archiwum' : 'Poradniki i aktualności 4elements',
    'description' => $archive_description,
));
?>

<?php get_footer(); ?>
