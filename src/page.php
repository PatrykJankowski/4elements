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

    <?php while (have_posts()) : the_post(); ?>

    <section class="top">
        <h1 class="<?php echo $class ?>"><?php the_title(); ?></h1>
        <?php if (has_post_thumbnail()) the_post_thumbnail(); ?>
    </section>

    <section>
        <div class="container">
            <?php the_content(); ?>
        </div>
    </section>

    <?php endwhile; ?>

<?php get_footer(); ?>
