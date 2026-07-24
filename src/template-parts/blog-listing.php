<?php
if (!defined('ABSPATH')) {
    exit;
}

$blog_posts = isset($args['query']) && $args['query'] instanceof WP_Query ? $args['query'] : $GLOBALS['wp_query'];
$paged = isset($args['paged']) ? max(1, (int) $args['paged']) : 1;
$page_title = isset($args['page_title']) ? $args['page_title'] : 'Blog';
$intro_title = isset($args['intro_title']) ? $args['intro_title'] : 'Poradniki i aktualności 4elements';
$description = isset($args['description']) ? $args['description'] : '';
?>

<section class="top">
    <h1 class="top__heading top__heading--fire"><?php echo esc_html(wp_strip_all_tags($page_title)); ?></h1>
</section>

<section id="blog" class="blog" aria-labelledby="blog-heading">
    <div class="container">
        <header class="blog__intro">
            <h2 id="blog-heading"><?php echo esc_html($intro_title); ?></h2>
            <?php if ($description) : ?>
                <div class="blog__intro-description"><?php echo wp_kses_post($description); ?></div>
            <?php else : ?>asdasdas
                <p>Praktyczne porady o nauce pływania, bezpieczeństwie nad wodą, treningach oraz aktywnym wypoczynku dla dzieci i dorosłych.</p>
            <?php endif; ?>
        </header>

        <div class="row">
            <div class="col-lg-9">
                <?php if ($blog_posts->have_posts()) : ?>
                    <div class="row blog__grid">
                        <?php while ($blog_posts->have_posts()) : $blog_posts->the_post(); ?>
                            <article class="col-lg-6 blog-card<?php echo has_post_thumbnail() ? '' : ' blog-card--no-image'; ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a class="blog-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                        <?php the_post_thumbnail(array(408, 250), array('loading' => 'lazy')); ?>
                                    </a>
                                <?php endif; ?>

                                <div class="blog-card__body">
                                    <div class="blog-card__meta">
                                        <time class="blog-card__date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                                            <span class="screen-reader-text">Opublikowano: </span><?php echo esc_html(get_the_date()); ?>
                                        </time>
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) :
                                        ?>
                                            <a class="blog-card__category" href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>">
                                                <?php echo esc_html($categories[0]->name); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <h2 class="blog-card__title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <p class="blog-card__excerpt">
                                        <?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 30, '…')); ?>
                                    </p>
                                    <a class="blog-card__more" href="<?php the_permalink(); ?>" aria-label="Czytaj więcej: <?php echo esc_attr(get_the_title()); ?>">
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
                    <p class="blog__empty">Brak artykułów w tej kategorii.</p>
                <?php endif; ?>
            </div>

            <aside class="col-lg-3 blog__sidebar" aria-label="Dodatkowe informacje o blogu">
                <?php get_sidebar(); ?>
            </aside>
        </div>
    </div>
</section>
