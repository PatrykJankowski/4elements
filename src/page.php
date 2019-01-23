<?php get_header(); ?>

    <?php
    $post_id = get_post()->ID;
    if ($post_id === 7 || $post_id === 149 || $post_id === 144 || $post_id === 135 || $post_id === 129 || $post_id === 139) {
        $class = "water";
    } else if ($post_id === 15) {
        $class = "wind";
    } else if ($post_id === 22) {
        $class = "ground";
    } else  {
        $class = "fire";
    }
    ?>

    <?php while (have_posts()) : the_post(); ?>

    <section class="top <?php echo $class ?>">
        <h1><?php the_title(); ?></h1> <!-- todo: widget -->
    </section>

    <section>
        <div class="container">

            <?php the_content(); ?>

        </div>
    </section>


    <?php endwhile; ?>

    <script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    document.getElementById("defaultOpen").click();
</script>

</script>

<?php get_footer(); ?>