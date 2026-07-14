<?php get_header(); ?>

<?php
$paged = max(1, (int) get_query_var('paged'));
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'paged' => $paged,
);
$blog_posts = new WP_Query($args);
?>

<section class="top">
    <h1 class="fire">Blog o nauce pływania i sportach wodnych</h1>
</section>

<section id="blog" aria-labelledby="blog-heading">
    <div class="container">
        <header class="blog-intro">
            <h2 id="blog-heading">Poradniki i aktualności 4elements</h2>
            <p>Praktyczne porady o nauce pływania, bezpieczeństwie nad wodą, treningach oraz aktywnym wypoczynku dla dzieci i dorosłych.</p>
        </header>

        <div class="row">
            <div class="col-lg-9">
                <?php if ($blog_posts->have_posts()) : ?>
                    <div class="row blog-grid">
                        <?php while ($blog_posts->have_posts()) : $blog_posts->the_post(); ?>
                            <article class="col-lg-6 blog-card<?php echo has_post_thumbnail() ? '' : ' blog-card--no-image'; ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a class="blog-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                        <?php the_post_thumbnail(array(408, 250), array(
                                            'loading' => 'lazy',
                                        )); ?>
                                    </a>
                                <?php endif; ?>

                                <div class="blog-card__body">
                                    <time class="time" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                                        <span class="screen-reader-text">Opublikowano: </span><?php echo esc_html(get_the_date()); ?>
                                    </time>
                                    <h2 class="title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <p class="content">
                                        <?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 30, '…')); ?>
                                    </p>
                                    <a class="more" href="<?php the_permalink(); ?>" aria-label="Czytaj więcej: <?php echo esc_attr(get_the_title()); ?>">
                                        Czytaj więcej <span aria-hidden="true">→</span>
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    $pagination = paginate_links(array(
                        'current' => $paged,
                        'total' => (int) $blog_posts->max_num_pages,
                        'mid_size' => 1,
                        'prev_text' => '<span aria-hidden="true">←</span> Poprzednia',
                        'next_text' => 'Następna <span aria-hidden="true">→</span>',
                        'type' => 'list',
                    ));

                    if ($pagination) :
                    ?>
                        <nav class="pagination" aria-label="Paginacja artykułów">
                            <?php echo wp_kses_post($pagination); ?>
                        </nav>
                    <?php endif; ?>
                <?php else : ?>
                    <p class="blog-empty">Nie opublikowano jeszcze żadnych artykułów.</p>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
            </div>

            <aside class="col-lg-3 blog-sidebar" aria-label="Dodatkowe informacje o blogu">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</section>


<?php get_footer(); ?>
