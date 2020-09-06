<?php get_header(); ?>

 <?php
    $post_id = get_post()->ID;
    if ($post_id === 7 || $post_id === 149 || $post_id === 144 || $post_id === 135 || $post_id === 129 || $post_id === 139) {
        $class = "water";
    } else if ($post_id === 22) {
        $class = "wind";
    } else if ($post_id === 15 || $post_id === 537 || $post_id === 525 || $post_id === 877) {
        $class = "ground";
    } else  {
        $class = "fire";
    }
 ?>


    <section class="top <?php echo $class ?>">
        <h1><?php the_title(); ?></h1> <!-- todo: widget -->
    </section>

    <section>
        <div class="container">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'entry' ); ?>
                <?php /*comments_template(); */?>
            <?php endwhile; endif; ?>
            <?php get_template_part( 'nav', 'below' ); ?>
        </div>
    </section>


<?php get_footer(); ?>







