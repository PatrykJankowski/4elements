<?php get_header(); ?>

<?php
$current_post_id = 0;
$category_ids = array();
?>

<?php while (have_posts()) : the_post(); ?>
    <?php
    $current_post_id = get_the_ID();
    $category_ids = wp_list_pluck(get_the_category(), 'term_id');
    ?>

    <div class="top">
        <h1 id="post-title" class="top__heading top__heading--fire"><?php the_title(); ?></h1>
    </div>

    <section class="single-layout" aria-labelledby="post-title">
        <div class="container">
            <div class="row">
                <article class="col-lg-9 single-article" aria-labelledby="post-title">
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="single-article__image">
                            <?php the_post_thumbnail(array(835, 460), array(
                                'fetchpriority' => 'high',
                                'loading' => 'eager',
                            )); ?>
                        </figure>
                    <?php endif; ?>

                    <header class="single-article__meta">
                        <span>
                            Opublikowano
                            <time class="single-article__date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </span>

                        <?php if ($category_ids) : ?>
                            <span class="single-article__categories">Kategorie: <?php echo wp_kses_post(get_the_category_list(', ')); ?></span>
                        <?php endif; ?>
                    </header>

                    <div class="single-article__content">
                        <?php the_content(); ?>
                    </div>

                    <?php if (has_tag()) : ?>
                        <footer class="single-article__tags" aria-label="Tematy artykułu">
                            <span>Tematy:</span> <?php the_tags('', ', '); ?>
                        </footer>
                    <?php endif; ?>
                </article>

                <aside class="col-lg-3 single-layout__sidebar" aria-label="Dodatkowe informacje o blogu">
                    <?php get_sidebar(); ?>
                </aside>
            </div>
        </div>
    </section>
<?php endwhile; ?>

<?php
$related_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'post__not_in' => array($current_post_id),
    'ignore_sticky_posts' => true,
    'no_found_rows' => true,
);

if ($category_ids) {
    $related_args['category__in'] = $category_ids;
}

$related_posts = new WP_Query($related_args);
?>

<?php if ($related_posts->have_posts()) : ?>
    <section id="featured" class="featured" aria-labelledby="related-posts-heading">
        <div class="container">
            <h2 id="related-posts-heading">Więcej artykułów z tej kategorii</h2>

            <div class="row featured__grid">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                    <article class="col-lg-3 featured-card<?php echo has_post_thumbnail() ? '' : ' featured-card--no-image'; ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <a class="featured-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                <?php the_post_thumbnail(array(408, 250), array('loading' => 'lazy')); ?>
                            </a>
                        <?php endif; ?>

                        <div class="featured-card__body">
                            <time class="featured-card__date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                                <span class="screen-reader-text">Opublikowano: </span><?php echo esc_html(get_the_date()); ?>
                            </time>
                            <h3 class="featured-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="featured-card__excerpt"><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 24, '…')); ?></p>
                            <a class="featured-card__more" href="<?php the_permalink(); ?>" aria-label="Czytaj więcej: <?php echo esc_attr(get_the_title()); ?>">
                                Czytaj więcej <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>
