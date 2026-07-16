<?php get_header(); ?>

<section class="not-found" aria-labelledby="not-found-title">
    <div class="not-found__decor not-found__decor--fire" aria-hidden="true"></div>
    <div class="not-found__decor not-found__decor--earth" aria-hidden="true"></div>

    <div class="not-found__container">
        <div class="not-found__content">
            <p class="not-found__eyebrow"><?php esc_html_e('Błąd 404', '4elements'); ?></p>
            <h1 id="not-found-title" class="not-found__title">
                <?php esc_html_e('Chyba odpłynęliśmy za daleko', '4elements'); ?>
            </h1>
            <p class="not-found__description">
                <?php esc_html_e('Ta strona nie istnieje albo zmieniła adres. Wróć na spokojne wody lub znajdź interesujące Cię zajęcia.', '4elements'); ?>
            </p>

            <div class="not-found__actions">
                <a class="not-found__button not-found__button--primary" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php esc_html_e('Wróć na stronę główną', '4elements'); ?>
                </a>
                <a class="not-found__button not-found__button--secondary" href="<?php echo esc_url(home_url('/nauka-plywania-warszawa/')); ?>">
                    <?php esc_html_e('Zobacz zajęcia', '4elements'); ?>
                </a>
            </div>

            <form class="not-found__search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <label class="not-found__search-label" for="not-found-search">
                    <?php esc_html_e('Możesz też przeszukać naszą stronę', '4elements'); ?>
                </label>
                <div class="not-found__search-row">
                    <input id="not-found-search" class="not-found__search-input" type="search" name="s"
                           value="<?php echo esc_attr(get_search_query()); ?>"
                           placeholder="<?php echo esc_attr__('Czego szukasz?', '4elements'); ?>">
                    <button class="not-found__search-button" type="submit" aria-label="<?php echo esc_attr__('Szukaj', '4elements'); ?>">
                        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false">
                            <circle cx="11" cy="11" r="6.5"></circle>
                            <path d="m16 16 4.2 4.2"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="not-found__visual" aria-hidden="true">
            <div class="not-found__code">
                <span>4</span>
                <span class="not-found__zero">
                    <svg viewBox="0 0 160 160" focusable="false">
                        <circle cx="80" cy="80" r="62"></circle>
                        <path d="M46 78c14-12 28 12 43 0s28 12 43 0"></path>
                        <path d="M45 96c14-12 28 12 43 0s28 12 43 0"></path>
                    </svg>
                </span>
                <span>4</span>
            </div>
            <div class="not-found__pool">
                <span class="not-found__lane not-found__lane--one"></span>
                <span class="not-found__lane not-found__lane--two"></span>
                <span class="not-found__lane not-found__lane--three"></span>
                <span class="not-found__swimmer"></span>
            </div>
            <p class="not-found__visual-caption"><?php esc_html_e('Tu nie ma tej strony — ale są dobre zajęcia!', '4elements'); ?></p>
        </div>
    </div>
</section>

<?php get_footer(); ?>
