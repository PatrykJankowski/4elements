<?php get_header(); ?>

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'paged' => $paged,
);
$posts = new WP_Query($args);
?>

<section class="top">
    <h1 class="fire">Blog</h1>
</section>

<section id="blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="row">
                    <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                    <div class="col-lg-6">
                        <?php the_post_thumbnail(array(408, 250)); ?>
                        <time class="time"><?php echo apply_filters('the_date', get_the_date()); ?></time>
                        <a href="<?php the_permalink(); ?>"><h2 class="title"><?php the_title(); ?></h2></a>
                        <div class="content"><?php echo wp_trim_words(get_the_content(), 30, '...' ); ?></div>
                        <a class="more" href="<?php the_permalink(); ?>">Czytaj więcej</a>
                    </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            <?php
                // manage pagination based on custom query
                $GLOBALS['wp_query']->max_num_pages = $posts->max_num_pages;
                the_posts_pagination(array(
                    'mid_size' => 1,
                    'prev_text' => __( '‹', 'textdomain' ),
                    'next_text' => __( '›', 'textdomain' )
                ));
            ?>
            </div>

            <div class="col-lg-3">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>


<?php get_footer(); ?>