<?php
/*
Template Name: Opinie
*/

get_header();

$google_reviews_url = 'https://www.google.com/maps/place/4elements/@52.1743547,21.0663937,17z/data=!4m8!3m7!1s0x47192e7360c00001:0x721697e01d854578!8m2!3d52.1743514!4d21.0689686!9m1!1b1!16s%2Fg%2F11qn41m74p?entry=ttu';
?>

<?php while (have_posts()) : the_post(); ?>
    <section class="top">
        <h1 class="top__heading top__heading--fire"><?php the_title(); ?></h1>
    </section>

    <section class="reviews-page" aria-labelledby="reviews-heading">
        <div class="container">
            <header class="reviews-page__intro">
                <p class="reviews-page__eyebrow">Opinie z Google Maps</p>
                <h2 id="reviews-heading" class="header header--fire">Co mówią o nas uczestnicy?</h2>
                <p>Poniższe opinie pochodzą z wizytówki 4elements w Google Maps. Każdy z prezentowanych autorów przyznał nam ocenę 5/5.</p>
            </header>

            <?php
            $content = trim(get_the_content());
            if ($content !== '') {
                the_content();
            }
            ?>

            <div class="reviews-page__grid">
                <?php foreach (four_elements_review_items() as $review) : ?>
                    <article class="review-card">
                        <div class="review-card__rating" aria-label="Ocena <?php echo esc_attr($review['rating']); ?> na 5 gwiazdek">
                            <span aria-hidden="true"><?php echo esc_html(str_repeat('★', (int) $review['rating'])); ?></span>
                            <strong><?php echo esc_html($review['rating']); ?>/5</strong>
                        </div>

                        <blockquote class="review-card__quote">
                            <?php echo wpautop(esc_html($review['text'])); ?>
                        </blockquote>

                        <footer class="review-card__author">
                            <?php if (!empty($review['author_url'])) : ?>
                                <a href="<?php echo esc_url($review['author_url']); ?>" target="_blank" rel="nofollow noopener noreferrer external">
                                    <?php echo esc_html($review['author']); ?>
                                    <span class="review-card__external" aria-hidden="true">↗</span>
                                    <span class="screen-reader-text"> — profil autora w Google Maps</span>
                                </a>
                            <?php else : ?>
                                <span><?php echo esc_html($review['author']); ?></span>
                            <?php endif; ?>
                        </footer>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="reviews-page__cta">
                <h2 class="header header--fire">Zobacz więcej opinii</h2>
                <p>Przejdź do wizytówki 4elements, aby przeczytać pozostałe recenzje lub podzielić się własną opinią.</p>
                <a class="button button--fire" href="<?php echo esc_url($google_reviews_url); ?>" target="_blank" rel="noopener noreferrer external">Więcej opinii w Google</a>
            </div>
        </div>
    </section>
<?php endwhile; ?>

<?php get_footer(); ?>
