<?php

add_filter('wp_enqueue_scripts', 'remove_shit', PHP_INT_MAX);

if (!function_exists('remove_shit')) {
    function remove_shit() {
        if (!is_page(array(822))) {
            wp_dequeue_script('jquery');
            wp_deregister_script('jquery');
            //wp_dequeue_style('wp-block-library');
            wp_dequeue_style('contact-form-7');
        }
    }
}

add_action('init', function () {
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    // Remove wp emoji
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}, PHP_INT_MAX - 1);


add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

/*function add_google_fonts() {
    wp_enqueue_style('google_web_fonts', 'https://fonts.googleapis.com/css?family=Raleway:400,500');
}
add_action( 'wp_enqueue_scripts', 'add_google_fonts' );*/


add_action('after_setup_theme', 'setup');
function setup()
{
    load_theme_textdomain('4elements', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    global $content_width;
    if (!isset($content_width)) $content_width = 640;
    register_nav_menus( array(
        'main-menu' => __('Main Menu', '4elements' ),
        'mobile-menu' => __('Mobile Menu', '4elements' )
    ));
}


add_filter('the_title', 'title');
function title($title)
{
    if ($title == '') {
        return '&rarr;';
    } else {
        return $title;
    }
}

add_filter('wp_title', 'filter_wp_title');
function filter_wp_title($title)
{
    return $title . esc_attr(get_bloginfo('name'));
}


add_action('widgets_init', 'widgets_init');
function widgets_init()
{
    register_sidebar(array(
        'id' => 'slogan',
        'name' => __('Slogan', '4elements'),
        'description' => 'Krótki tekst w nagłówku na stronie głównej',
        'before_title' => '<h2 class="slider__title">',
        'after_title' => '</h2>',
        'before_widget' => '',
        'after_widget' => '',
    ));
    register_sidebar(array(
        'id' => 'about',
        'name' => __('O 4elements', '4elements'),
        'description' => 'Sekcja z krótkim opisem 4elements na stronie głównej',
        'before_widget' => '',
        'after_widget' => '',
    ));
    register_sidebar(array(
        'id' => 'swimming',
        'name' => __('Nauka pływania', '4elements'),
        'description' => 'Sekcja o nauce pływania - krótki opis na stronie głównej',
        'before_widget' => '',
        'after_widget' => '',
    ));
    register_sidebar(array(
        'id' => 'trainings',
        'name' => __('Treningi - opis', '4elements'),
        'description' => 'Sekcja o zajęciach ogólnorozwojowych - krótki opis na stronie głównej',
        'before_widget' => '',
        'after_widget' => '',
    ));
    register_sidebar(array(
        'id' => 'camps',
        'name' => __('Obozy - opis', '4elements'),
        'description' => 'Sekcja o obozach - krótki opis na stronie głównej',
        'before_widget' => '',
        'after_widget' => '',
    ));
}



add_filter( 'ninja_forms_submission_csv_name', 'csv_name' );

function csv_name()
{
    $name = 'formularz_rejestracyjny';
    return $name;
}


/**
 * Publish the AI-readable site guide at https://4elements.pl/llms.txt.
 * This interception does not need a server-level rewrite or a permalink flush.
 */
add_action('parse_request', 'four_elements_serve_llms_txt');
function four_elements_serve_llms_txt($wp)
{
    if (trim((string) $wp->request, '/') !== 'llms.txt') {
        return;
    }

    $file = get_template_directory() . '/llms.txt';
    if (!is_readable($file)) {
        status_header(404);
        exit;
    }

    status_header(200);
    nocache_headers();
    header('Content-Type: text/plain; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    readfile($file);
    exit;
}


/**
 * SEO and AI-agent support.
 *
 * Keep this functionality in the theme so it is deployed together with the
 * templates. Every feature below is progressive and does not require a plugin.
 */

add_action('wp_enqueue_scripts', 'four_elements_enqueue_webmcp');
function four_elements_enqueue_webmcp()
{
    if (is_admin()) {
        return;
    }

    $script_path = get_template_directory() . '/webmcp.js';
    $version = file_exists($script_path) ? (string) filemtime($script_path) : null;

    wp_enqueue_script(
        '4elements-webmcp',
        get_template_directory_uri() . '/webmcp.js',
        array(),
        $version,
        true
    );

    wp_localize_script('4elements-webmcp', 'fourElementsAgentData', array(
        'name' => get_bloginfo('name') ?: '4elements',
        'description' => get_bloginfo('description'),
        'homeUrl' => home_url('/'),
        'currentUrl' => four_elements_canonical_url(),
        'contact' => array(
            'email' => 'kontakt@4elements.pl',
            'phones' => array('798 968 416', '798 784 748'),
        ),
        'pages' => array(
            array('name' => 'Nauka pływania', 'url' => home_url('/nauka-plywania/'), 'keywords' => 'pływanie dzieci dorośli lekcje zajęcia'),
            array('name' => 'Cennik', 'url' => home_url('/nauka-plywania-cennik/'), 'keywords' => 'cena koszt płatność'),
            array('name' => 'Pływalnie', 'url' => home_url('/plywalnie-warszawa-wola/'), 'keywords' => 'basen adres lokalizacja Warszawa Wola Wilanów'),
            array('name' => 'Obozy i półkolonie', 'url' => home_url('/obozy-i-polkolonie/'), 'keywords' => 'obóz półkolonie lato zima dzieci'),
            array('name' => 'Treningi', 'url' => home_url('/treningi/'), 'keywords' => 'trening zajęcia sportowe personalne'),
            array('name' => 'Blog', 'url' => home_url('/blog/'), 'keywords' => 'artykuły poradniki aktualności'),
            array('name' => 'Kontakt', 'url' => home_url('/kontakt/'), 'keywords' => 'telefon email wiadomość'),
            array('name' => 'Formularz rejestracyjny', 'url' => home_url('/formularz-rejestracyjny/'), 'keywords' => 'zapis zapisy rejestracja'),
        ),
    ));
}


add_action('wp_head', 'four_elements_organization_schema', 20);
function four_elements_organization_schema()
{
    if (is_admin()) {
        return;
    }

    $home_url = home_url('/');
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => trailingslashit($home_url) . '#organization',
        'name' => get_bloginfo('name') ?: '4elements',
        'url' => $home_url,
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => get_template_directory_uri() . '/img/logo.png',
        ),
        'description' => get_bloginfo('description') ?: 'Nauka pływania w Warszawie, obozy i półkolonie.',
        'email' => 'mailto:kontakt@4elements.pl',
        'telephone' => '+48 798 968 416',
        'sameAs' => array('https://www.facebook.com/4elementspl'),
        'areaServed' => array(
            '@type' => 'City',
            'name' => 'Warszawa',
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'telephone' => '+48 798 968 416',
            'email' => 'kontakt@4elements.pl',
            'availableLanguage' => 'pl',
            'areaServed' => 'PL',
        ),
        'location' => array(
            array(
                '@type' => 'Place',
                'name' => 'OSiR Wola „FOKA”',
                'address' => array('@type' => 'PostalAddress', 'streetAddress' => 'ul. Esperanto 5', 'addressLocality' => 'Warszawa', 'addressCountry' => 'PL'),
            ),
            array(
                '@type' => 'Place',
                'name' => 'Aqua Spa Wilanów',
                'address' => array('@type' => 'PostalAddress', 'streetAddress' => 'ul. Sarmacka 5', 'addressLocality' => 'Warszawa', 'addressCountry' => 'PL'),
            ),
            array(
                '@type' => 'Place',
                'name' => 'Centrum Sportu Wilanów',
                'address' => array('@type' => 'PostalAddress', 'streetAddress' => 'ul. Gubinowska 28/30', 'addressLocality' => 'Warszawa', 'addressCountry' => 'PL'),
            ),
            array(
                '@type' => 'Place',
                'name' => 'Centrum Sportu Wilanów',
                'address' => array('@type' => 'PostalAddress', 'streetAddress' => 'ul. Wiertnicza 26a', 'addressLocality' => 'Warszawa', 'addressCountry' => 'PL'),
            ),
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}


/**
 * The FAQ data is shared by visible content and JSON-LD, so agents and people
 * receive precisely the same, public information.
 */
function four_elements_faq_items()
{
    return array(
        array(
            'question' => 'Jak zapisać się na zajęcia nauki pływania?',
            'answer' => 'Aby zapisać się na zajęcia, wypełnij formularz zapisu dostępny na stronie 4elements.',
            'answer_html' => 'Aby zapisać się na zajęcia, wypełnij <a href="' . esc_url(home_url('/formularz-rejestracyjny/')) . '">formularz zapisu</a>.',
        ),
        array(
            'question' => 'Ile wcześniej należy przyjść na pływalnię?',
            'answer' => 'Na pływalni należy być 15 minut przed zajęciami, aby spokojnie przygotować się do lekcji.',
        ),
        array(
            'question' => 'Ile trwają zajęcia nauki pływania?',
            'answer' => 'Lekcje trwają 30 lub 45 minut. Należy także uwzględnić czas potrzebny na przebranie się przed i po zajęciach.',
        ),
        array(
            'question' => 'Co zabrać na zajęcia na basenie?',
            'answer' => 'Na zajęcia należy zabrać kostium lub kąpielówki, czepek, ręcznik, klapki i okulary pływackie.',
        ),
        array(
            'question' => 'Czy rodzic może przebywać na płycie basenu podczas zajęć?',
            'answer' => 'Rodzic nie może przebywać na płycie basenu podczas zajęć, chyba że korzysta z pływalni z wykupionym biletem wstępu. Może wejść z dzieckiem do szatni i po zmianie obuwia odprowadzić je na basen.',
        ),
        array(
            'question' => 'Czy można odwołać i odrobić zajęcia?',
            'answer' => 'Raz w miesiącu można odwołać zajęcia bez podania przyczyny, najpóźniej 24 godziny przed lekcją. Termin odrobienia jest ustalany wspólnie z 4elements, a lekcję można odrobić do końca bieżącego semestru.',
        ),
        array(
            'question' => 'Kiedy i jak opłacić zajęcia?',
            'answer' => 'Pod koniec miesiąca 4elements wysyła e-mail z informacją o wpłatach. Opłatę należy uiścić przed pierwszymi zajęciami w danym miesiącu; można zapłacić przelewem lub osobiście gotówką.',
        ),
        array(
            'question' => 'Od jakiego wieku i w jakich grupach odbywają się zajęcia?',
            'answer' => 'Dzieci są przyjmowane na zajęcia od 4. roku życia. Zajęcia grupowe dla dzieci, młodzieży i dorosłych odbywają się w grupach od 2 do 5 osób. Dobór grupy zależy od wieku i poziomu zaawansowania.',
        ),
        array(
            'question' => 'Na jakich pływalniach odbywają się zajęcia?',
            'answer' => 'Zajęcia odbywają się w Warszawie: OSiR Wola „FOKA” przy ul. Esperanto 5, Aqua Spa Wilanów przy ul. Sarmackiej 5 oraz Centrum Sportu Wilanów przy ul. Gubinowskiej 28/30 i ul. Wiertniczej 26a.',
        ),
    );
}

add_action('wp_head', 'four_elements_faq_schema', 21);
function four_elements_faq_schema()
{
    if (!is_page('faq')) {
        return;
    }

    $questions = array();
    foreach (four_elements_faq_items() as $item) {
        $questions[] = array(
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $item['answer'],
            ),
        );
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        '@id' => trailingslashit(get_permalink()) . '#faq',
        'mainEntity' => $questions,
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}

function four_elements_render_faq()
{
    $html = '<section class="faq" aria-labelledby="faq-heading">';
    $html .= '<h2 id="faq-heading" class="faq__header header header--fire">Najczęściej zadawane pytania</h2>';
    foreach (four_elements_faq_items() as $item) {
        $answer = isset($item['answer_html']) ? $item['answer_html'] : esc_html($item['answer']);
        $html .= '<article class="faq__item">';
        $html .= '<h3>' . esc_html($item['question']) . '</h3>';
        $html .= '<p>' . $answer . '</p>';
        $html .= '</article>';
    }
    $html .= '</section>';

    return $html;
}

/**
 * Make the public FAQ page available immediately after the theme is deployed.
 * An existing /faq/ page is never changed automatically.
 */
add_action('init', 'four_elements_ensure_faq_page', 20);
function four_elements_ensure_faq_page()
{
    if (get_page_by_path('faq', OBJECT, 'page')) {
        return;
    }

    $page_id = wp_insert_post(array(
        'post_title' => 'Najczęściej zadawane pytania (FAQ)',
        'post_name' => 'faq',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_content' => '',
    ), true);

    if (!is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'page-faq.php');
    }
}


/**
 * Return a canonical URL for both singular content and WordPress listings.
 */
function four_elements_canonical_url()
{
    if (is_404()) {
        return '';
    }

    if (is_singular()) {
        $canonical = wp_get_canonical_url();
        return $canonical ?: get_permalink();
    }

    if (is_search()) {
        return get_search_link(get_search_query(false));
    }

    return get_pagenum_link(max(1, (int) get_query_var('paged')), false);
}


/**
 * Advertise canonical, pagination, sitemap and manifest relations as RFC 8288
 * Link response headers. Existing Link values added by WordPress/plugins stay intact.
 */
add_filter('wp_headers', 'four_elements_link_headers', 10, 2);
function four_elements_link_headers($headers, $wp)
{
    if (is_admin()) {
        return $headers;
    }

    $links = array();
    $canonical = four_elements_canonical_url();
    if ($canonical) {
        $links[] = four_elements_format_link_header($canonical, 'canonical');
    }

    $links[] = four_elements_format_link_header(home_url('/wp-sitemap.xml'), 'sitemap', 'application/xml');
    $links[] = four_elements_format_link_header(get_template_directory_uri() . '/manifest.json', 'manifest', 'application/manifest+json');

    if (!is_singular()) {
        $previous = get_previous_posts_page_link();
        $next = get_next_posts_page_link();
        if ($previous) {
            $links[] = four_elements_format_link_header($previous, 'prev');
        }
        if ($next) {
            $links[] = four_elements_format_link_header($next, 'next');
        }
    }

    $links = array_filter($links);
    if ($links) {
        $value = implode(', ', $links);
        $headers['Link'] = empty($headers['Link']) ? $value : $headers['Link'] . ', ' . $value;
    }

    return $headers;
}

function four_elements_format_link_header($url, $relation, $type = '')
{
    $url = esc_url_raw($url);
    if (!$url || preg_match('/[<>\r\n]/', $url)) {
        return '';
    }

    $link = '<' . $url . '>; rel="' . $relation . '"';
    if ($type) {
        $link .= '; type="' . $type . '"';
    }
    return $link;
}


/**
 * Ensure WordPress-generated images have useful alternative text. Editors can
 * still explicitly use alt="" for decorative images in post content.
 */
add_filter('wp_get_attachment_image_attributes', 'four_elements_attachment_alt', 10, 2);
function four_elements_attachment_alt($attributes, $attachment)
{
    if (!isset($attributes['alt']) || trim($attributes['alt']) === '') {
        $attributes['alt'] = four_elements_attachment_alt_text($attachment->ID);
    }
    return $attributes;
}

function four_elements_attachment_alt_text($attachment_id)
{
    $alt = trim((string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true));
    if ($alt !== '') {
        return $alt;
    }

    $caption = trim((string) wp_get_attachment_caption($attachment_id));
    if ($caption !== '') {
        return wp_strip_all_tags($caption);
    }

    $title = get_the_title($attachment_id);
    return $title ? wp_strip_all_tags($title) : 'Ilustracja 4elements';
}

add_filter('the_content', 'four_elements_add_missing_content_alts', 20);
function four_elements_add_missing_content_alts($content)
{
    if (stripos($content, '<img') === false) {
        return $content;
    }

    return preg_replace_callback('/<img\b[^>]*>/i', function ($match) {
        $tag = $match[0];
        if (preg_match('/\s+alt\s*=/i', $tag)) {
            return $tag;
        }

        $alt = '';
        if (preg_match('/\bwp-image-(\d+)\b/i', $tag, $id_match)) {
            $alt = four_elements_attachment_alt_text((int) $id_match[1]);
        }

        if ($alt === '' && preg_match('/\s+src\s*=\s*(["\'])(.*?)\1/i', $tag, $src_match)) {
            $path = parse_url(html_entity_decode($src_match[2]), PHP_URL_PATH);
            $filename = pathinfo((string) $path, PATHINFO_FILENAME);
            $filename = preg_replace('/(?:@\d+|-\d+x\d+)$/', '', $filename);
            $alt = trim(str_replace(array('-', '_'), ' ', urldecode($filename)));
        }

        if ($alt === '') {
            $alt = is_singular() ? 'Ilustracja do: ' . get_the_title() : 'Ilustracja 4elements';
        }

        return preg_replace_callback('/\s*\/?>$/', function ($ending) use ($alt) {
            return ' alt="' . esc_attr($alt) . '"' . $ending[0];
        }, $tag, 1);
    }, $content);
}
