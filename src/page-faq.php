<?php
/*
Template Name: FAQ
*/

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <section class="top">
        <h1 class="top__heading top__heading--fire"><?php the_title(); ?></h1>
    </section>

    <section class="faq-page">
        <div class="container">
            <p class="faq-page__intro">Odpowiedzi na najczęściej zadawane pytania dotyczące zajęć nauki pływania w 4elements.</p>
            <form class="faq-page__search" role="search" action="<?php echo esc_url(get_permalink()); ?>" toolname="search_4elements_faq" tooldescription="Wyszukuje odpowiedzi w publicznym FAQ 4elements dotyczącym zajęć nauki pływania, zapisów, płatności i pływalni." toolautosubmit>
                <label for="faq-site-search">Znajdź odpowiedź</label>
                <div class="faq-page__search-controls">
                    <input id="faq-site-search" type="search" name="query" autocomplete="off" placeholder="Np. płatność, odwołanie zajęć, basen" toolparamdescription="Pytanie lub fraza dotycząca zajęć, zapisów, płatności albo pływalni." aria-controls="faq-list" />
                    <button type="submit">Szukaj</button>
                </div>
                <p class="faq-page__search-status" id="faq-search-status" aria-live="polite"></p>
            </form>
            <?php
            $content = trim(get_the_content());
            if ($content !== '') {
                the_content();
            }
            echo four_elements_render_faq();
            ?>
        </div>
    </section>
<?php endwhile; ?>

<?php get_footer(); ?>
