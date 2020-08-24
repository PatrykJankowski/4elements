</main>

<?php
    $post_id = get_post()->ID;
    if ($post_id === 7 || $post_id === 149 || $post_id === 144 || $post_id === 135 || $post_id === 129 || $post_id === 139 || $post_id === 525) {
        $class = "water";
    } else if ($post_id === 15) {
        $class = "wind";
    } else if ($post_id === 22) {
        $class = "ground";
    } else  {
        $class = "fire";
    }
?>

<footer id="footer" class="<?php echo $class ?>">
    <div id="info">
        <div class="container">
            <div class="row">
                <div class="c">
                    <h5>4elements Warszawa – nauka pływania</h5>
                    <ul>
                        <li><a href="/nauka-plywania-dla-dzieci-warszawa">Nauka pływania dla dzieci</a></li>
                        <li><a href="/nauka-plywania-dla-doroslych-warszawa">Nauka pływania dla dorosłych</a></li>
                        <li><a href="/wazne-informacje-na-start">Ważne informacje</a></li>
                        <li><a href="/plywalnie-warszawa-wola">Nasze pływalnie</a></li>
                        <li><a href="/nauka-plywania-cennik">Cennik</a></li>
                        <li><a href="/kontakt">Kontakt</a></li>
                        <li><a href="/regulamin-zajec-nauki-plywania">Regulamin zajęć</a></li>
                        <li><a href="/rodo">RODO</a></li>
                    </ul>
                </div>
                <div class="c">
                    <h5>Nasze Pływalnie</h5>
                    <p>
                        OSiR Wola „FOKA”<br>
                        ul. Esperanto 5, Warszawa Wola
                    </p>
                    <br>
                    <p>
                        SCS Aktywna Warszawa<br>
                        ul. Inflancka 8, Warszawa Wola
                    </p>
                </div>
                <div class="c">
                    <p>Adres e-mail: <a class="link" href="mailto:kontakt@4elements.pl">kontakt@4elements.pl</a></p>
                    <p>Numer telefonu: 798 968 416 lub 798 784 748</p>
                    <p>Facebook: <a class="link" title="4elements - nauka pływania, Warszawa" href="https://www.facebook.com/4elementspl" target="_blank" rel="nofollow noopener">4elementspl</a></p>
                    <a class="button <?php echo $class ?>" href="/kontakt">Zapisz się na zajęcia</a>
                </div>
            </div>
        </div>
    </div>
    <div id="copyright">
        <?php echo sprintf( __( '%1$s %2$s %3$s | %4$s', '4elements' ), '&copy;', date( 'Y' ), esc_html( get_bloginfo( 'name' )  ), esc_html(get_bloginfo( 'description' ))); echo sprintf( __( '<p>Wykonanie: <a style="color: #fff; text-decoration: underline" href="https://softcraft.it" title="SoftCraft – projektujemy strony internetowe i aplikacje mobilne na miarę twoich potrzeb" target="_blank">SoftCraft</a></p>', '4elements' ) ); ?>
    </div>
</footer>

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

    if('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/wp-content/themes/4elements/sw.js');
    }
</script>

<?php wp_footer(); ?>

</body>
</html>
