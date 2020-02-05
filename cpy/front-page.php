<?php get_header(); ?>

<section class="container offer">

    <div class="row">
        <div class="c">
            <a href="/o-nas-nauka-plywania-w-warszawie/" class="fire">
                <img alt="4elements, Warszawa - ogień" src="/wp-content/themes/4elements/img/ogien.png" />
                <h4>O nas</h4>
            </a>
        </div>
        <div class="c">
            <a href="/nauka-plywania-warszawa" class="water">
                <img alt="Nauka pływania, Warszawa - woda" src="/wp-content/themes/4elements/img/woda.png" />
                <h4>Nauka pływania</h4>
            </a>
        </div>
        <div class="c">
            <a href="/treningi" class="earth">
                <img alt="Trening personalny, Warszawa - ziemia" src="/wp-content/themes/4elements/img/ziemia.png" />
                <h4>Treningi</h4>
            </a>
        </div>
        <div class="c">
            <a href="/obozy-sportowe-warszawa" class="wind">
                <img alt="Obozy sportowe, Warszawa - wiatr" src="/wp-content/themes/4elements/img/wiatr.png" />
                <h4>Obozy sportowe</h4>
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
                <img alt="Nauka pływania - Warszawa Wola" src="/wp-content/themes/4elements/img/nauka-plywania-dla-dzieci.jpg">
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
                <img alt="Trening personalny, Warszawa" src="/wp-content/themes/4elements/img/zajecia-ogolnorozwojowe.jpg">
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
                <img alt="Obozy sportowe, Warszawa" src="/wp-content/themes/4elements/img/obozy.jpg">
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
                <a href="http://bedzieladnie.com" target="_blank" rel="nofollow noopener">
                    <img alt="będzie ładnie - partner" src="/wp-content/themes/4elements/img/bedzie-ladnie.png">
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
