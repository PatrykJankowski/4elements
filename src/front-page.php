<?php get_header(); ?>

<section class="container front-container">
    <div class="row">
        <div class="c">
            <a class="element" href="/o-nas-nauka-plywania-w-warszawie/">
                <img class="element__img" alt="4elements, Warszawa - ogień" src="/wp-content/themes/4elements/img/ogien.png" />
                <h4 class="element__header element__header--fire">O nas</h4>
            </a>
        </div>
        <div class="c">
            <a  class="element" href="/nauka-plywania-warszawa">
                <img class="element__img" alt="Nauka pływania w Warszawie - woda" src="/wp-content/themes/4elements/img/woda.png" />
                <h4 class="element__header element__header--water">Nauka pływania</h4>
            </a>
        </div>
        <div class="c">
            <a class="element" href="/treningi">
                <img class="element__img" alt="Trening personalny, Warszawa - ziemia" src="/wp-content/themes/4elements/img/ziemia.png" />
                <h4 class="element__header element__header--earth">Treningi</h4>
            </a>
        </div>
        <div class="c">
            <a class="element" href="/obozy-sportowe-warszawa">
                <img class="element__img" alt="Obozy sportowe, Warszawa - wiatr" src="/wp-content/themes/4elements/img/wiatr.png" />
                <h4 class="element__heade element__header--wind">Obozy sportowe</h4>
            </a>
        </div>
    </div>
</section>

<section class="front-container front-container--center front-container--fire">
    <div class="container">
        <?php dynamic_sidebar('about'); ?>
    </div>
</section>

<section class="front-container front-container--water">
    <div class="container">
        <div class="row row--revert">
            <div class="c c6">
                <img class="img img--mb img--water" alt="Nauka pływania – Warszawa Wola, Mokotów" src="/wp-content/themes/4elements/img/nauka-plywania-dla-dzieci.webp">
            </div>
            <div class="c c4">
                <?php dynamic_sidebar('swimming'); ?>
            </div>
        </div>
    </div>
</section>

<section class="front-container front-container--earth">
    <div class="container">
        <div class="row">
            <div class="c c6 c6--r">
                <img class="img img--mb img--earth" alt="Zajęcia ogólnorozwojowe i treningi personalne – Warszawa" src="/wp-content/themes/4elements/img/treningi.webp">
            </div>
            <div class="c c4">
                <?php dynamic_sidebar('trainings'); ?>
            </div>
        </div>
    </div>
</section>

<section class="front-container front-container--wind">
    <div class="container">
        <div class="row row--revert">
            <div class="c c6">
                <img class="img img--mb img--wind" alt="Obozy sportowe dla dzieci – Warszawa" src="/wp-content/themes/4elements/img/obozy-sportowe.webp">
            </div>
            <div class="c c4">
                <?php dynamic_sidebar('camps'); ?>
            </div>
        </div>
    </div>
</section>

<section class="front-container front-container--center">
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
