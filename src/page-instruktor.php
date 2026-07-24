<?php
/*
Template Name: Profil instruktora
*/

get_header();

$profile = four_elements_instructor_profile(get_post_field('post_name', get_queried_object_id()));

if (!$profile) {
    get_template_part('page');
    get_footer();
    return;
}
?>

<?php while (have_posts()) : the_post(); ?>
    <section class="top">
        <h1 class="top__heading top__heading--fire"><?php echo esc_html(get_the_title()); ?></h1>
    </section>

    <article class="instructor-profile" itemscope itemtype="https://schema.org/Person">
        <div class="container">
            <div class="instructor-profile__hero">
                <figure class="instructor-profile__photo instructor-profile__photo--<?php echo esc_attr($profile['image_position']); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/img/sandra-karolina.jpg'); ?>"
                         alt="<?php echo esc_attr($profile['image_alt']); ?>"
                         width="912" height="1200" loading="eager" itemprop="image">
                </figure>
                <div class="instructor-profile__intro">
                    <p class="instructor-profile__eyebrow">Instruktorka 4elements</p>
                    <h2 itemprop="name"><?php echo esc_html($profile['name']); ?></h2>
                    <p class="instructor-profile__role" itemprop="jobTitle"><?php echo esc_html($profile['role']); ?></p>
                    <div class="instructor-profile__bio" itemprop="description">
                        <?php foreach ($profile['bio'] as $paragraph) : ?>
                            <p><?php echo esc_html($paragraph); ?></p>
                        <?php endforeach; ?>
                    </div>
                    <a class="button button--fire" href="/formularz-rejestracyjny/">Zapisz się na zajęcia</a>
                </div>
            </div>

            <section class="instructor-profile__about" aria-labelledby="about-heading">
                <p class="instructor-profile__eyebrow">Poznaj mnie bliżej</p>
                <h2 id="about-heading">Kilka słów ode mnie</h2>
                <div class="instructor-profile__answers">
                    <?php foreach ($profile['answers'] as $answer) : ?>
                        <article class="instructor-answer">
                            <h3><?php echo esc_html($answer['question']); ?></h3>
                            <p><?php echo esc_html($answer['answer']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="instructor-profile__articles" aria-labelledby="articles-heading">
                <div class="instructor-profile__section-header">
                    <div>
                        <p class="instructor-profile__eyebrow">Blog 4elements</p>
                        <h2 id="articles-heading">Moje artykuły</h2>
                    </div>
                    <a class="instructor-profile__blog-link" href="/blog/">Wszystkie artykuły <span aria-hidden="true">→</span></a>
                </div>

                <?php $articles = four_elements_instructor_articles($profile['name']); ?>
                <?php if ($articles->have_posts()) : ?>
                    <div class="instructor-profile__articles-grid">
                        <?php while ($articles->have_posts()) : $articles->the_post(); ?>
                            <article class="instructor-article">
                                <p class="instructor-article__meta"><?php echo esc_html(get_the_date()); ?></p>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
                                <a class="instructor-article__link" href="<?php the_permalink(); ?>">Czytaj artykuł <span aria-hidden="true">→</span></a>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <p class="instructor-profile__empty">Wkrótce pojawią się tutaj artykuły tej autorki.</p>
                <?php endif; wp_reset_postdata(); ?>
            </section>

            <aside class="instructor-profile__more">
                <p>Chcesz poznać całą naszą kadrę?</p>
                <a href="/instruktorzy/">Zobacz wszystkich instruktorów <span aria-hidden="true">→</span></a>
            </aside>
        </div>
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>
