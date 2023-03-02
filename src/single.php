<?php get_header(); ?>
    <section class="top">
        <h1 class="fire"><?php the_title(); ?></h1>

        <div class="container">
            <div class="row mt-60">
                <div class="col-lg-9">
                    <?php while (have_posts()) : the_post(); ?>
                        <article>
                            <?php if (has_post_thumbnail()) { the_post_thumbnail(array(835, 460)); } ?>
                            <time class="date"><?php echo apply_filters('the_date', get_the_date()); ?></time>
                            <h2 class="title"><?php the_title(); ?></h2>
                            <div class="content"><?php the_content(); ?></div>
                        </article>
                    <?php endwhile; ?>
                </div>
                <div class="col-lg-3">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>


    <?php $args = array();
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 4,
        'paged' => $paged,
        'category__in' =>  get_the_category()['0']->cat_ID,
        'post__not_in' => array( $post->ID ) 
    );
    $posts = new WP_Query($args);
    ?>

    <section id="featured">
        <div class="container">
            <div class="row">
                <div class="col">
                <h1>Najnowsze artykuły z tej kategorii</h1>
                    <div class="row">
                        <?php while ($posts->have_posts()) : $posts->the_post(); ?>
                        <div class="col-lg-3">
                            <?php the_post_thumbnail(array(408, 250)); ?>
                            <time class="date"><?php echo apply_filters('the_date', get_the_date()); ?></time>
                            <a href="<?php the_permalink(); ?>"><h2 class="title"><?php the_title(); ?></h2></a>
                            <div class="content"><?php echo wp_trim_words(get_the_content(), 30, '...' ); ?></div>
                            <a class="more" href="<?php the_permalink(); ?>">Czytaj więcej</a>
                        </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>