</main>

  <?php
    $post_id = get_queried_object_id();
    if (is_404() || $post_id === 7 || $post_id === 149 || $post_id === 144 || $post_id === 135 || $post_id === 129 || $post_id === 139) {
        $class = "footer--water";
    } else if ($post_id === 22) {
        $class = "footer--wind";
    } else if ($post_id === 15 || $post_id === 537 || $post_id === 525 || $post_id === 877) {
        $class = "footer--ground";
    } else  {
        $class = "footer--fire";
    }
  ?>

<footer id="footer" class="footer <?php echo esc_attr($class); ?><?php echo is_404() ? ' footer--flush' : ''; ?>" role="contentinfo">
    <div class="footer__elements" aria-hidden="true">
        <span class="footer__element footer__element--fire"></span>
        <span class="footer__element footer__element--water"></span>
        <span class="footer__element footer__element--ground"></span>
        <span class="footer__element footer__element--wind"></span>
    </div>
    <div id="info" class="footer__info">
        <div class="container">
            <div class="footer__grid">
                <section class="footer__section footer__section--navigation" aria-labelledby="footer-navigation-heading">
                    <h2 id="footer-navigation-heading" class="footer__heading">4elements Warszawa</h2>
                    <p class="footer__lead"><strong>Nauka pływania dla dzieci i dorosłych</strong></p>
                    <ul class="footer__links">
                        <li><a href="/nauka-plywania-warszawa/dzieci">Nauka pływania dla dzieci</a></li>
                        <li><a href="/nauka-plywania-warszawa/dorosli">Nauka pływania dla dorosłych</a></li>
                        <li><a href="/nauka-plywania-warszawa/plywalnie-i-grafik">Nasze pływalnie</a></li>
                        <li><a href="/nauka-plywania-warszawa/cennik">Cennik</a></li>
                        <li><a href="/wazne-informacje-na-start">Ważne informacje</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/kontakt">Kontakt</a></li>
                        <li><a href="/regulamin-zajec-nauki-plywania">Regulamin zajęć i płatności</a></li>
                        <li><a href="/polityka-prywatnosci">Polityka prywatności</a></li>
                        <li><a href="/rodo">RODO</a></li>
                    </ul>
                </section>

                <section class="footer__section" aria-labelledby="footer-pools-heading">
                    <h2 id="footer-pools-heading" class="footer__heading">Nasze pływalnie</h2>
                    <div class="footer__locations">
                        <address class="footer__location">
                            <strong>Aqua Spa Wilanów</strong>
                            <span>ul. Sarmacka 5, Warszawa</span>
                        </address>
                        <address class="footer__location">
                            <strong>Centrum Sportu Wilanów</strong>
                            <span>ul. Gubinowska 28/30, Warszawa</span>
                        </address>
                        <address class="footer__location">
                            <strong>Pływalnia SGGW</strong>
                            <span>ul. Jana Ciszewskiego 10, Warszawa</span>
                        </address>
                    </div>
                </section>

                <section class="footer__section" aria-labelledby="footer-contact-heading">
                    <h2 id="footer-contact-heading" class="footer__heading">Kontakt</h2>
                    <div class="footer__contact">
                        <a class="footer__contact-link" href="mailto:kontakt@4elements.pl">kontakt@4elements.pl</a>
                        <div class="footer__phones">
                            <a class="footer__contact-link" href="tel:+48798968416">798 968 416</a>
                            <span aria-hidden="true">/</span>
                            <a class="footer__contact-link" href="tel:+48798784748">798 784 748</a>
                        </div>
                    </div>

                    <div class="footer__socials" aria-label="4elements w mediach społecznościowych">
                        <a class="footer__social" title="4elements na Facebooku" href="https://www.facebook.com/4elementspl" target="_blank" rel="noopener">
                            <span class="footer__social-name">Facebook</span>
                            <span class="footer__social-handle">@4elementspl</span>
                        </a>
                        <a class="footer__social" title="4elements na Instagramie" href="https://www.instagram.com/4elements_naukaplywania/" target="_blank" rel="noopener">
                            <span class="footer__social-name">Instagram</span>
                            <span class="footer__social-handle">@4elements_naukaplywania</span>
                        </a>
                    </div>

                    <div class="footer__payments">
                        <p class="footer__payments-title"><strong>Bezpieczne płatności online</strong></p>
                        <div class="footer__payment-logos">
                            <img class="footer__payment-logo footer__payment-logo--dotpay" alt="Dotpay" src="/wp-content/themes/4elements/img/dotpay.png" width="62" height="20">
                            <img class="footer__payment-logo" alt="Mastercard" src="/wp-content/themes/4elements/img/mastercard.svg" width="61" height="50">
                            <img class="footer__payment-logo" alt="Visa" src="/wp-content/themes/4elements/img/visa.svg" width="60" height="60">
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div id="copyright" class="footer__copyright">
        <p>&copy; <?php echo date('Y'); ?> 4elements <span aria-hidden="true">|</span> <?php echo esc_html(get_bloginfo('description')); ?></p>
        <p>Wykonanie: <a href="https://softcraft.pl" title="Softcraft – projektujemy strony internetowe i aplikacje mobilne na miarę twoich potrzeb" target="_blank" rel="noopener noreferrer">Softcraft.pl</a></p>
    </div>
</footer>

<form class="hidden-fields-container" action="<?php echo esc_url(home_url('/ask')); ?>" method="get"
      toolname="ask_4elements"
      tooldescription="Odpowiada na publiczne pytania o naukę pływania, zapisy, pływalnie i kontakt z 4elements."
      toolautosubmit
      aria-hidden="true">
    <label for="webmcp-public-query">Pytanie do 4elements</label>
    <input id="webmcp-public-query" type="search" name="query"
           toolparamdescription="Pytanie o publiczne informacje 4elements.">
</form>

<script defer>
    let navOpened = false;
    let initHeight = 408;

    function slideToggle() {

        let navMobile = document.getElementById('nav__nav-mobile');

        if (navOpened) {
            navOpened = false;
            navMobile.style.height = '0';
            document.getElementById('nav__toggle-icon').classList.remove('nav__toggle-icon--open');
        }
        else {
            navOpened = true;
            navMobile.style.height = initHeight + 'px';
            document.getElementById('nav__toggle-icon').classList.add('nav__toggle-icon--open');
        }
    }
</script>

<?php wp_footer(); ?>

</body>
</html>
