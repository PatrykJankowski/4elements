<?php get_header(); ?>

<section class="container offer">
    <div class="row">
        <div class="c">
            <a class="offer__element" href="/o-nas-nauka-plywania-w-warszawie/">
                <img class="offer__img" alt="4elements, Warszawa - ogień" src="/wp-content/themes/4elements/img/ogien.png" />
                <h4 class="offer__header offer__header--fire">O nas</h4>
            </a>
        </div>
        <div class="c">
            <a  class="offer__element" href="/nauka-plywania-warszawa">
                <img class="offer__img" alt="Nauka pływania w Warszawie - woda" src="/wp-content/themes/4elements/img/woda.png" />
                <h4 class="offer__header offer__header--water">Nauka pływania</h4>
            </a>
        </div>
        <div class="c">
            <a class="offer__element" href="/treningi">
                <img class="offer__img" alt="Trening personalny, Warszawa - ziemia" src="/wp-content/themes/4elements/img/ziemia.png" />
                <h4 class="offer__header offer__header--ground">Treningi</h4>
            </a>
        </div>
        <div class="c">
            <a class="offer__element" href="/obozy-sportowe-warszawa">
                <img class="offer__img" alt="Obozy sportowe, Warszawa - wiatr" src="/wp-content/themes/4elements/img/wiatr.png" />
                <h4 class="offer__heade offer__header--wind">Obozy sportowe</h4>
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
            <div class="c">
                <img alt="Nauka pływania – Warszawa Wola, Mokotów" src="/wp-content/themes/4elements/img/nauka-plywania-dla-dzieci.webp">
            </div>
            <div class="c">
                <div class="container__widget">
                    <?php dynamic_sidebar('swimming'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trainings">
    <div class="container">
        <div class="row">
            <div class="c">
                <img alt="Zajęcia ogólnorozwojowe, trening personalny, Warszawa" src="/wp-content/themes/4elements/img/treningi.webp">
            </div>
            <div class="c">
                <div class="container__widget container__widget--left">
                    <?php dynamic_sidebar('trainings'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="camps">
    <div class="container">
        <div class="row">
            <div class="c">
                <img alt="Obozy sportowe, Warszawa" src="/wp-content/themes/4elements/img/obozy-sportowe.webp">
            </div>
            <div class="c">
                <div class="container__widget">
                    <?php dynamic_sidebar('camps'); ?>
                </div>
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
