<?php get_header(); ?>

<section class="container offer">

    <div class="row">
        <div class="c">
            <a href="#" class="fire">
                <img src="<?php echo get_template_directory_uri() . '/img/ogien.png'; ?>" />
                <h4>O nas</h4>
            </a>
        </div>
        <div class="c">
            <a href="#" class="water">
                <img src="<?php echo get_template_directory_uri() . '/img/woda.png'; ?>" />
                <h4>Nauka pływania</h4>
            </a>
        </div>
        <div class="c">
            <a href="#" class="ground">
                <img src="<?php echo get_template_directory_uri() . '/img/ziemia.png'; ?>" />
                <h4>Treningi</h4>
            </a>
        </div>
        <div class="c">
            <a href="#" class="wind">
                <img src="<?php echo get_template_directory_uri() . '/img/wiatr.png'; ?>" />
                <h4>Obozy</h4>
            </a>
        </div>
    </div>
</section>

<section class="about">
    <div class="container">
        <?php dynamic_sidebar('about'); ?>
    </div>
</section>

<section class="swimming">
    <div class="container">
        <div class="row">
            <div class="c c6">
                <img src="<?php echo get_template_directory_uri() . '/img/nauka-plywania-dla-dzieci.jpg'; ?>" alt="Nauka pływania - Warszawa Wola">
            </div>
            <div class="c c4">
                <?php dynamic_sidebar('swimming'); ?>
            </div>
        </div>
    </div>
</section>

<section class="trainings">
    <div class="container">
        <div class="row">
            <div class="c c6 inverted">
                <img src="<?php echo get_template_directory_uri() . '/img/zajecia-ogolnorozwojowe.jpg'; ?>">
            </div>
            <div class="c c4">
                <?php dynamic_sidebar('trainings'); ?>
            </div>
        </div>
    </div>
</section>

<section class="camps">
    <div class="container">
        <div class="row">
            <div class="c c6">
                <img src="<?php echo get_template_directory_uri() . '/img/obozy.jpg'; ?>">
            </div>
            <div class="c c4">
                <?php dynamic_sidebar('camps'); ?>
            </div>
        </div>
    </div>
</section>

<section class="partners">
    <div class="container">
        <div class="row">
            <div class="c">
                <h2>Nasi partnerzy</h2>
                <a href="http://bedzieladnie.com" target="_blank">
                    <img src="<?php echo get_template_directory_uri() . '/img/bedzie-ladnie.png'; ?>">
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
