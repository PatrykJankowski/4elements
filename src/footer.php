</main>

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

<footer id="footer" class="<?php echo $class ?>" role="contentinfo">
    <div id="info">
        <div class="container">
            <div class="row">
                <div class="c">
                    <h5>Nauka pływania, Warszawa</h5>
                    <ul>
                        <li><a href="#">Nauka pływania dla dzieci</a></li>
                        <li><a href="#">Nauka pływania dla dorosłych</a></li>
                        <li><a href="#">Pływanie korekcyjne</a></li>
                        <li><a href="#">Pływalnie i plan zajęć</a></li>
                        <li><a href="#">Cennik</a></li>
                        <li><a href="#">Kontakt</a></li>
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
                    <p>Facebook: <a class="link" href="https://www.facebook.com/4elementspl" target="_blank">4elementspl</a></p>
                    <a class="button <?php echo $class ?>" href="#">Zapisz się na zajęcia</a>
                </div>
            </div>
        </div>
    </div>
    <div id="copyright">
        <?php echo sprintf( __( '%1$s %2$s %3$s | %4$s', '4elements' ), '&copy;', date( 'Y' ), esc_html( get_bloginfo( 'name' )  ), esc_html(get_bloginfo( 'description' ))); echo sprintf( __( '<br>Wykonanie: Patryk Jankowski', '4elements' ) ); ?>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>