<?php
/*
Template Name: FAQ
*/

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <section class="top">
        <h1 class="fire"><?php the_title(); ?></h1>
    </section>

    <section class="faq-page">
        <div class="container">
            <p class="faq-page__intro">Odpowiedzi na najczęściej zadawane pytania dotyczące zajęć nauki pływania w 4elements.</p>
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
