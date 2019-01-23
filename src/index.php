<?php get_header(); ?>

    <section class="top">
        Blog
    </section>

    <section id="blog">
        <div class="container">
            <div class="row">
                <div class="c">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'entry' ); ?>
                        <?php /*comments_template(); */?>
                    <?php endwhile; endif; ?>
                    <?php get_template_part( 'nav', 'below' ); ?>
                </div>

                <div class="c">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>


<?php get_footer(); ?>