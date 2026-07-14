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


/**
 * Keep one canonical query for blog listings. WordPress applies category,
 * tag, author and date constraints to this query before index.php renders it.
 */
add_action('pre_get_posts', 'four_elements_blog_posts_per_page');
function four_elements_blog_posts_per_page($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->is_home() || $query->is_archive()) {
        $query->set('posts_per_page', 4);
    }
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

    if (is_page('faq')) {
        $faq_script_path = get_template_directory() . '/faq.js';
        wp_enqueue_script(
            '4elements-faq-search',
            get_template_directory_uri() . '/faq.js',
            array(),
            file_exists($faq_script_path) ? (string) filemtime($faq_script_path) : null,
            true
        );
    }

    wp_localize_script('4elements-webmcp', 'fourElementsAgentData', array(
        'name' => get_bloginfo('name') ?: '4elements',
        'description' => get_bloginfo('description'),
        'homeUrl' => home_url('/'),
        'currentUrl' => four_elements_canonical_url(),
        'registrationUrl' => home_url('/formularz-rejestracyjny/'),
        'isRegistrationPage' => is_page(array('formularz-rejestracyjny', 'zapisz-sie')),
        'isContactPage' => is_page('kontakt'),
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


function four_elements_offer_catalog_schema()
{
    return array(
        '@type' => 'OfferCatalog',
        'name' => 'Usługi 4elements',
        'itemListElement' => array(
            array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Nauka pływania dla dzieci', 'url' => home_url('/nauka-plywania-dla-dzieci-warszawa/'), 'areaServed' => 'Warszawa')),
            array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Nauka pływania dla dorosłych', 'url' => home_url('/nauka-plywania-dla-doroslych-warszawa/'), 'areaServed' => 'Warszawa')),
            array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Obozy i półkolonie dla dzieci', 'url' => home_url('/obozy-i-polkolonie/'), 'areaServed' => 'Polska')),
        ),
    );
}


add_action('wp_head', 'four_elements_organization_schema', 20);
function four_elements_organization_schema()
{
    if (is_admin() || defined('WPSEO_VERSION')) {
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
        'openingHours' => 'Mo-Su 08:00-20:00',
        'openingHoursSpecification' => array(
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
            'opens' => '08:00',
            'closes' => '20:00',
        ),
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
            'hoursAvailable' => array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                'opens' => '08:00',
                'closes' => '20:00',
            ),
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

    if (is_front_page()) {
        $schema['hasOfferCatalog'] = four_elements_offer_catalog_schema();
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}


/**
 * Keep business details and the public offer inside Yoast's connected entity
 * graph. The nested Service nodes are exposed on the homepage only, not on
 * unrelated pages such as the blog.
 */
add_filter('wpseo_schema_organization', 'four_elements_yoast_organization_schema');
function four_elements_yoast_organization_schema($data)
{
    $data['@type'] = array('Organization', 'SportsActivityLocation');
    $data['email'] = 'mailto:kontakt@4elements.pl';
    $data['telephone'] = '+48 798 968 416';
    $data['openingHours'] = 'Mo-Su 08:00-20:00';
    $data['openingHoursSpecification'] = array(
        '@type' => 'OpeningHoursSpecification',
        'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
        'opens' => '08:00',
        'closes' => '20:00',
    );
    $data['areaServed'] = array('@type' => 'City', 'name' => 'Warszawa');
    $data['contactPoint'] = array(
        '@type' => 'ContactPoint',
        'contactType' => 'customer service',
        'telephone' => '+48 798 968 416',
        'email' => 'kontakt@4elements.pl',
        'availableLanguage' => 'pl',
    );

    if (is_front_page()) {
        $data['hasOfferCatalog'] = four_elements_offer_catalog_schema();
    }

    return $data;
}

/**
 * Describe only the service represented by the current offer page.
 * Service entities do not belong on unrelated pages such as the blog index.
 */
add_action('wp_head', 'four_elements_service_schema', 22);
function four_elements_service_schema()
{
    if (is_admin() || !is_page()) {
        return;
    }

    $organization_id = trailingslashit(home_url('/')) . '#organization';
    $services = array(
        'nauka-plywania-dla-dzieci-warszawa' => array('name' => 'Nauka pływania dla dzieci', 'description' => 'Zajęcia nauki i doskonalenia pływania dla dzieci w Warszawie.'),
        'nauka-plywania-dla-doroslych-warszawa' => array('name' => 'Nauka pływania dla dorosłych', 'description' => 'Zajęcia nauki i doskonalenia pływania dla dorosłych w Warszawie.'),
        'nauka-plywania' => array('name' => 'Indywidualna nauka pływania', 'description' => 'Indywidualne zajęcia nauki pływania dopasowane do poziomu uczestnika.'),
        'obozy-i-polkolonie' => array('name' => 'Obozy i półkolonie', 'description' => 'Obozy sportowe i półkolonie dla dzieci.'),
        'treningi' => array('name' => 'Treningi sportowe', 'description' => 'Zajęcia ogólnorozwojowe i treningi sportowe.'),
    );

    $page_slug = get_post_field('post_name', get_queried_object_id());
    if (!isset($services[$page_slug])) {
        return;
    }

    $service = $services[$page_slug];
    $service_url = get_permalink();
    $service_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        '@id' => trailingslashit($service_url) . '#service',
        'name' => $service['name'],
        'description' => $service['description'],
        'url' => $service_url,
        'provider' => array('@id' => $organization_id),
        'areaServed' => array('@type' => 'City', 'name' => 'Warszawa'),
        'availableLanguage' => 'pl',
    );

    echo '<script type="application/ld+json">' . wp_json_encode($service_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}


/**
 * Keep the Yoast WebPage node explicit on the posts index. Yoast normally
 * detects a blog homepage as CollectionPage; the filter protects that type
 * from settings or theme-specific query changes without creating a duplicate
 * JSON-LD graph.
 */
add_filter('wpseo_schema_webpage_type', 'four_elements_blog_schema_type');
function four_elements_blog_schema_type($type)
{
    return is_home() ? 'CollectionPage' : $type;
}


/**
 * A standard WordPress post in this theme is a blog article. Keep that
 * meaning inside Yoast's connected graph instead of outputting separate JSON-LD.
 */
add_filter('wpseo_schema_article', 'four_elements_blog_post_schema');
function four_elements_blog_post_schema($data)
{
    if (is_singular('post')) {
        $data['@type'] = 'BlogPosting';
    }

    return $data;
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
            'answer' => 'Aby zapisać się na zajęcia nauki pływania w 4elements, należy wypełnić formularz rejestracyjny lub skontaktować się telefonicznie. Przy doborze zajęć uwzględniane są wiek, poziom uczestnika, preferowana pływalnia oraz aktualna dostępność. Wysłanie formularza jest zgłoszeniem i nie oznacza automatycznego potwierdzenia miejsca.',
            'more_html' => '<a href="' . esc_url(home_url('/nauka-plywania-warszawa/zapisz-sie/')) . '">Przejdź do formularza zapisu</a>.',
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
        array(
            'question' => 'Czy dorosły może nauczyć się pływać od zera?',
            'answer' => 'Dorosły może rozpocząć naukę pływania od zera w każdym wieku. Pierwsze kroki obejmują oswojenie z wodą, naukę spokojnego oddechu i wyporności, a następnie ćwiczenie prostych ruchów pod opieką instruktora.',
        ),
        array(
            'question' => 'Ile czasu trwa nauka pływania?',
            'answer' => 'Tempo nauki jest indywidualne. Zależy między innymi od wcześniejszych doświadczeń z wodą, regularności zajęć, celu nauki i częstotliwości samodzielnych ćwiczeń. Warto mierzyć postęp kolejnymi umiejętnościami, a nie liczbą lekcji.',
        ),
        array(
            'question' => 'Jak często warto chodzić na naukę pływania?',
            'answer' => 'W nauce pływania najważniejsza jest regularność. Stałe zajęcia pozwalają utrwalać oddech, pracę nóg i koordynację. Częstotliwość należy dobrać do wieku, poziomu zaawansowania oraz czasu potrzebnego na regenerację.',
        ),
        array(
            'question' => 'Od czego zaczyna się naukę pływania?',
            'answer' => 'Naukę pływania zaczyna się zwykle od bezpiecznego oswojenia z wodą: zanurzania twarzy, wydechu do wody, unoszenia się na wodzie i poślizgu. Dopiero potem wprowadza się pracę nóg, rąk i łączenie ruchów z oddechem.',
        ),
        array(
            'question' => 'Czy można nauczyć się pływać, gdy boję się wody?',
            'answer' => 'Osoba, która boi się wody, może nauczyć się pływać, zaczynając spokojnie i informując instruktora o swoich obawach. Ćwiczenia powinny odbywać się stopniowo, w bezpiecznych warunkach i bez presji na szybkie przechodzenie do trudniejszych elementów.',
        ),
        array(
            'question' => 'Jak prawidłowo oddychać podczas pływania?',
            'answer' => 'Podstawą jest spokojny, długi wydech do wody oraz krótki wdech ustami, gdy twarz znajduje się nad powierzchnią. Ćwiczenie oddechu osobno, przy brzegu lub z deską, ułatwia później naukę stylów pływackich.',
        ),
        array(
            'question' => 'Czy do nauki pływania potrzebne są okulary pływackie?',
            'answer' => 'Okulary pływackie nie są obowiązkowe podczas nauki pływania, ale ułatwiają otwieranie oczu pod wodą i koncentrację na ćwiczeniu. Nie powinny być zbyt ciasne ani zastępować nauki swobodnego kontaktu z wodą.',
        ),
        array(
            'question' => 'Lepiej wybrać indywidualną czy grupową naukę pływania?',
            'answer' => 'Zajęcia indywidualne pozwalają dopasować tempo i ćwiczenia do jednej osoby. Zajęcia grupowe mogą wspierać regularność i motywację. Najlepszy wybór zależy od celu, wieku, poziomu i preferowanego sposobu pracy.',
        ),
        array(
            'question' => 'Czy można nauczyć się pływać samemu?',
            'answer' => 'Samodzielna nauka podstaw pływania wymaga bezpiecznego miejsca i nadzoru; osoba początkująca nie powinna wchodzić do wody sama. Instruktor pomaga szybciej wychwycić błędy techniczne i dobrać ćwiczenia do poziomu uczestnika.',
        ),
        array(
            'question' => 'Jak pomóc dziecku polubić naukę pływania?',
            'answer' => 'Dziecku w polubieniu nauki pływania pomaga spokojne oswajanie z wodą, zabawa dostosowana do wieku i docenianie małych postępów. Warto unikać porównywania dziecka z innymi oraz dać mu czas na zbudowanie zaufania do wody i instruktora.',
        ),
        array(
            'question' => 'Jak poprawić technikę pływania?',
            'answer' => 'Technikę pływania najlepiej poprawiać, pracując nad jednym elementem naraz: ułożeniem ciała, oddechem, pracą nóg albo rąk. Regularna informacja zwrotna od instruktora i krótkie, powtarzalne ćwiczenia pomagają utrwalić prawidłowy ruch.',
        ),
    );
}

function four_elements_answer_capsule_items()
{
    return array(
        array(
            'question' => 'Jak zapisać się na zajęcia nauki pływania?',
            'answer' => 'Aby zapisać się na zajęcia nauki pływania w 4elements, należy wypełnić formularz rejestracyjny lub skontaktować się telefonicznie. Przy doborze zajęć uwzględniane są wiek, poziom uczestnika, preferowana pływalnia oraz aktualna dostępność. Samo wysłanie formularza jest zgłoszeniem i nie oznacza automatycznego potwierdzenia miejsca.',
        ),
        array(
            'question' => 'Dla kogo przeznaczone są zajęcia pływackie?',
            'answer' => '4elements prowadzi w Warszawie naukę i doskonalenie pływania dla dzieci od 4. roku życia, młodzieży oraz dorosłych. Dostępne są zajęcia indywidualne i grupowe, a grupy liczą od 2 do 5 osób. Uczestnicy są dobierani przede wszystkim według wieku i poziomu umiejętności.',
        ),
        array(
            'question' => 'Gdzie odbywają się zajęcia nauki pływania?',
            'answer' => 'Zajęcia odbywają się na warszawskich pływalniach: OSiR Wola „FOKA” przy ul. Esperanto 5, Aqua Spa Wilanów przy ul. Sarmackiej 5 oraz w obiektach Centrum Sportu Wilanów przy ul. Gubinowskiej 28/30 i ul. Wiertniczej 26a. Dostępność terminów zależy od wybranej lokalizacji.',
        ),
    );
}

function four_elements_faq_schema_questions($items = null)
{
    $items = is_array($items) ? $items : four_elements_faq_items();
    $questions = array();
    foreach ($items as $item) {
        $questions[] = array(
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $item['answer'],
            ),
        );
    }

    return $questions;
}


/**
 * Add FAQPage to Yoast's connected WebPage node. This is easier for agents
 * and validators to consume than a second, disconnected JSON-LD graph.
 */
add_filter('wpseo_schema_webpage', 'four_elements_faq_yoast_schema');
function four_elements_faq_yoast_schema($data)
{
    if (!is_page('faq')) {
        return $data;
    }

    $types = isset($data['@type']) ? (array) $data['@type'] : array('WebPage');
    $types[] = 'FAQPage';
    $data['@type'] = array_values(array_unique($types));
    $data['mainEntity'] = four_elements_faq_schema_questions();

    return $data;
}


/**
 * Fallback for installations where Yoast SEO is not active.
 */
add_action('wp_head', 'four_elements_faq_schema', 21);
function four_elements_faq_schema()
{
    if (!is_page('faq') || defined('WPSEO_VERSION')) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        '@id' => trailingslashit(get_permalink()) . '#faq',
        'mainEntity' => four_elements_faq_schema_questions(),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}

function four_elements_render_faq()
{
    $html = '<section class="faq" id="faq-list" aria-labelledby="faq-heading">';
    $html .= '<p id="faq-heading" class="faq__header header header--fire">Najczęściej zadawane pytania</p>';
    foreach (four_elements_faq_items() as $item) {
        $search_text = wp_strip_all_tags($item['question'] . ' ' . $item['answer']);
        $html .= '<article class="faq__item" data-faq-item data-faq-search="' . esc_attr($search_text) . '">';
        $html .= '<h2>' . esc_html($item['question']) . '</h2>';
        $html .= '<p class="faq__answer-capsule">' . esc_html($item['answer']) . '</p>';
        if (isset($item['more_html'])) {
            $html .= '<p class="faq__more">' . wp_kses_post($item['more_html']) . '</p>';
        }
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
 * Google reviews reproduced with the reviewers' names and original wording.
 * The source links are included where they were provided by the reviewer.
 */
function four_elements_review_items()
{
    return array(
        array(
            'author' => 'Kuba Kowalski',
            'author_url' => 'https://www.google.com/maps/contrib/117855336974771606367/reviews?hl=pl-PL',
            'rating' => 5,
            'text' => "Ta szkoła pływania to absolutny strzał w dziesiątkę! Instruktorka ma genialne podejście, jest super nauczycielem i potrafi świetnie złapać kontakt z dzieckiem. Jakość zajęć jest na najwyższym poziomie, dzięki czemu nasza pociecha zrobiła mega progres. Jedziemy na wakacje z pełnym spokojem, bo wiemy, że dziecko świetnie poradzi sobie w wodzie.\n\nBardzo doceniamy też bezproblemowy kontakt i pomoc z zaświadczeniem, które pomogło podbić ocenę z WF-u na upragnioną szóstkę!\n\nDo tego dochodzi wygodna aplikacja do odwoływania i odrabiania zajęć, co przy dzieciakach jest po prostu zbawieniem.\n\nPełna polecajka!",
        ),
        array(
            'author' => 'Patrycja Mazińska',
            'author_url' => 'https://www.google.com/maps/contrib/101208810727826357727/reviews?hl=pl-PL',
            'rating' => 5,
            'text' => "Bardzo serdecznie polecam 4elements!\n\nDwie moje córeczki uczestniczyły w „Moim pierwszym obozie” organizowanym przez 4elements i wróciły zachwycone.\n\nPierwszy obóz dziecka to przeżycie i dla dzieci i dla rodziców, jednak widząc codzienną relację z obozu, roześmiane oczy dzieci, uśmiechy, a potem słuchając niekończących się relacji co dziewczyny robiły na obozie mogę śmiało stwierdzić, że to była bardzo dobra decyzja, by wysłać je na ten obóz.\n\nWspaniała, ciepła kadra, piękne, sielankowe miejsce z dala od miasta i kameralna grupa. Wszystko bardzo dobrze przemyślane, a codzienne atrakcje bardzo różnorodne!\n\nPolecam z całego serca i bardzo dziękuję, za zaangażowanie i serce włożone w przygotowanie obozu ❤️",
        ),
        array(
            'author' => 'Patrick Ney',
            'author_url' => 'https://www.google.com/maps/contrib/117908510122048222246/reviews?hl=pl-PL',
            'rating' => 5,
            'text' => 'Miła obsługa. Dzieci są zachwycone lekcjami. Widać ogromny postęp. Jesteśmy bardzo zadowoleni. Dodam, że córka była na letnich koloniach i też 5/5 gwiazd. Szacun!',
        ),
        array(
            'author' => 'Sławomir Gąsiorowski',
            'author_url' => 'https://www.google.com/maps/contrib/114521734817097165315/reviews?hl=pl-PL',
            'rating' => 5,
            'text' => 'Nasza Janinka uwielbia te zajęcia i widzimy bardzo duży progres w umiejętnościach Jasi. Brawo Wy!',
        ),
        array(
            'author' => 'Edyta B',
            'author_url' => 'https://www.google.com/maps/contrib/111519970837561715241/reviews?hl=pl-PL',
            'rating' => 5,
            'text' => 'Dzięki za super przygodę! Bardzo fajne zajęcia z pływania! Trener Adrian wymiata! Polecam bardzo!!!!',
        ),
        array(
            'author' => 'Aleksandra Nowicka',
            'author_url' => 'https://www.google.com/maps/contrib/102849227915569490595/reviews?hl=pl-PL',
            'rating' => 5,
            'text' => 'Bardzo polecam zajęcia dla dorosłych na Wiertniczej 🙂 Super instruktorzy, świetna atmosfera i można się wiele nauczyć 🙂',
        ),
    );
}


/**
 * Create the reviews page once. Existing content under /opinie/ is untouched.
 */
add_action('init', 'four_elements_ensure_reviews_page', 21);
function four_elements_ensure_reviews_page()
{
    if (get_page_by_path('opinie', OBJECT, 'page')) {
        return;
    }

    $page_id = wp_insert_post(array(
        'post_title' => 'Opinie o 4elements',
        'post_name' => 'opinie',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_content' => '',
    ), true);

    if (!is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'page-opinie.php');
    }
}


add_filter('wpseo_schema_webpage', 'four_elements_reviews_collection_schema');
function four_elements_reviews_collection_schema($data)
{
    if (!is_page('opinie')) {
        return $data;
    }

    $types = isset($data['@type']) ? (array) $data['@type'] : array('WebPage');
    $types[] = 'CollectionPage';
    $data['@type'] = array_values(array_unique($types));

    return $data;
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
    $links[] = four_elements_format_link_header(home_url('/openapi.json'), 'service-desc', 'application/vnd.oai.openapi+json;version=3.1');
    $links[] = four_elements_format_link_header(home_url('/.well-known/agents.json'), 'api-catalog', 'application/json');

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


/**
 * Public, read-only agent endpoints.
 *
 * The cards below are backed by small JSON-RPC implementations instead of
 * advertising a server that does not exist. They deliberately expose only
 * information already public on the website.
 */
add_action('parse_request', 'four_elements_agent_endpoints', 1);
function four_elements_agent_endpoints($wp)
{
    $request = trim((string) $wp->request, '/');
    $aliases = array(
        '.well-known/agent.json' => 'a2a-card',
        '.well-known/agent-card.json' => 'a2a-card',
        'agent.json' => 'agent-web-card',
        '.well-known/agents.json' => 'agents-card',
        'agents.json' => 'agents-card',
        '.well-known/mcp.json' => 'mcp-card',
        '.well-known/mcp/server-card.json' => 'mcp-card',
        '.well-known/mcp-server-card.json' => 'mcp-card',
    );

    if (isset($aliases[$request])) {
        four_elements_send_agent_json(four_elements_agent_document($aliases[$request]));
    }

    if ($request === 'openapi.json') {
        four_elements_send_agent_json(four_elements_openapi_document());
    }

    if ($request === 'mcp') {
        four_elements_handle_mcp_request();
    }

    if ($request === 'a2a') {
        four_elements_handle_a2a_request();
    }

    if ($request === 'ask' || $request === 'api/ask') {
        four_elements_handle_nlweb_request();
    }
}

function four_elements_agent_document($type)
{
    $home = home_url('/');
    $mcp_url = home_url('/mcp');
    $a2a_url = home_url('/a2a');

    if ($type === 'a2a-card') {
        return array(
            'protocolVersion' => '0.3.0',
            'name' => '4elements Public Information Agent',
            'description' => 'Read-only assistant for public information about 4elements: swimming lessons, locations, registration and contact.',
            'url' => $a2a_url,
            'preferredTransport' => 'JSONRPC',
            'capabilities' => array('streaming' => false, 'pushNotifications' => false, 'stateTransitionHistory' => false),
            'defaultInputModes' => array('text/plain'),
            'defaultOutputModes' => array('text/plain'),
            'skills' => array(
                array('id' => 'site-information', 'name' => 'Informacje o 4elements', 'description' => 'Wskazuje publiczne strony o ofercie, zapisach, pływalniach i kontakcie.', 'tags' => array('pływanie', 'Warszawa', 'zapisy', 'kontakt')),
                array('id' => 'faq', 'name' => 'Najczęściej zadawane pytania', 'description' => 'Pomaga znaleźć odpowiedzi z publicznej strony FAQ.', 'tags' => array('faq', 'zajęcia', 'pływalnia')),
            ),
        );
    }

    if ($type === 'agent-web-card') {
        return array(
            'awp_version' => '0.2',
            'domain' => wp_parse_url($home, PHP_URL_HOST),
            'intent' => 'Public information discovery for 4elements.',
            'protocols' => array(
                'mcp' => array('version' => '2025-06-18', 'endpoint' => $mcp_url, 'transport' => 'http'),
                'a2a' => array('version' => '0.3.0', 'endpoint' => $a2a_url),
            ),
            'capabilities' => array('public_information', 'site_search', 'faq', 'nlweb'),
            'auth' => array('required' => false),
            'actions' => array(
                array('name' => 'find_public_information', 'description' => 'Finds public pages and answers about 4elements.', 'endpoint' => $a2a_url, 'method' => 'POST'),
                array('name' => 'ask_public_information', 'description' => 'Answers natural-language questions from the public 4elements knowledge base.', 'endpoint' => home_url('/ask'), 'method' => 'GET, POST'),
            ),
        );
    }

    if ($type === 'agents-card') {
        return array(
            'version' => '0.1.0',
            'name' => '4elements agent API',
            'description' => 'Discovery document for the public, read-only 4elements agent interfaces.',
            'openapi' => home_url('/openapi.json'),
            'agents' => array(
                array('name' => '4elements Public Information Agent', 'card' => home_url('/.well-known/agent-card.json'), 'endpoint' => $a2a_url),
            ),
        );
    }

    return array(
        '$schema' => 'https://modelcontextprotocol.io/schemas/server-card.json',
        'version' => '1.0',
        'protocolVersion' => '2025-06-18',
        'serverInfo' => array('name' => '4elements-public-information', 'version' => '1.0.0'),
        'description' => 'Read-only MCP server with public 4elements information, links, FAQ and contact details.',
        'transport' => array('type' => 'streamable-http', 'endpoint' => $mcp_url),
        'capabilities' => array('tools' => array('listChanged' => false)),
        'tools' => four_elements_mcp_tools(),
    );
}

/**
 * Browser-readable discovery links for the public agent manifests.
 * WebMCP itself is declared on the FAQ search form with toolname and
 * tooldescription; it has no separate manifest format.
 */
add_action('wp_head', 'four_elements_agent_discovery_links', 5);
function four_elements_agent_discovery_links()
{
    if (is_admin()) {
        return;
    }

    echo '<link rel="alternate" type="application/json" title="A2A Agent Card" href="' . esc_url(home_url('/.well-known/agent-card.json')) . '">' . "\n";
    echo '<link rel="alternate" type="application/json" title="MCP Server Card" href="' . esc_url(home_url('/.well-known/mcp/server-card.json')) . '">' . "\n";
    echo '<link rel="alternate" type="application/json" title="Agents Manifest" href="' . esc_url(home_url('/.well-known/agents.json')) . '">' . "\n";
    echo '<link rel="alternate" type="application/json" title="NLWeb Ask API" href="' . esc_url(home_url('/ask')) . '">' . "\n";
}

function four_elements_openapi_document()
{
    return array(
        'openapi' => '3.1.0',
        'info' => array('title' => '4elements Public Agent API', 'version' => '1.0.0', 'description' => 'Read-only MCP and A2A endpoints for public site information.'),
        'servers' => array(array('url' => home_url('/'))),
        'paths' => array(
            '/mcp' => array('post' => array('summary' => 'MCP Streamable HTTP JSON-RPC endpoint', 'responses' => array('200' => array('description' => 'MCP response')))),
            '/a2a' => array('post' => array('summary' => 'A2A JSON-RPC endpoint', 'responses' => array('200' => array('description' => 'A2A task response')))),
            '/ask' => array(
                'get' => array('summary' => 'NLWeb-compatible public information query', 'parameters' => array(array('name' => 'query', 'in' => 'query', 'required' => true, 'schema' => array('type' => 'string'))), 'responses' => array('200' => array('description' => 'Public information answer'))),
                'post' => array('summary' => 'NLWeb-compatible public information query', 'responses' => array('200' => array('description' => 'Public information answer'))),
            ),
        ),
    );
}

function four_elements_send_agent_json($document, $status = 200)
{
    status_header($status);
    nocache_headers();
    header('Content-Type: application/json; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    header('Access-Control-Allow-Origin: *');
    echo wp_json_encode($document, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function four_elements_agent_request_body()
{
    $body = json_decode((string) file_get_contents('php://input'), true);
    return is_array($body) ? $body : null;
}

function four_elements_agent_cors_headers()
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, MCP-Protocol-Version');
}

function four_elements_handle_mcp_request()
{
    four_elements_agent_cors_headers();
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(204);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST, OPTIONS');
        four_elements_send_agent_json(array('error' => 'Use POST for MCP JSON-RPC.'), 405);
    }

    $request = four_elements_agent_request_body();
    if (!$request || empty($request['method'])) {
        four_elements_send_agent_json(four_elements_jsonrpc_error(null, -32600, 'Invalid JSON-RPC request.'), 400);
    }

    $id = isset($request['id']) ? $request['id'] : null;
    $method = $request['method'];
    $params = isset($request['params']) && is_array($request['params']) ? $request['params'] : array();
    if ($method === 'initialize') {
        four_elements_send_agent_json(array('jsonrpc' => '2.0', 'id' => $id, 'result' => array(
            'protocolVersion' => '2025-06-18',
            'capabilities' => array('tools' => array('listChanged' => false)),
            'serverInfo' => array('name' => '4elements-public-information', 'version' => '1.0.0'),
        )));
    }
    if ($method === 'notifications/initialized') {
        status_header(204);
        exit;
    }
    if ($method === 'ping') {
        four_elements_send_agent_json(array('jsonrpc' => '2.0', 'id' => $id, 'result' => array()));
    }
    if ($method === 'tools/list') {
        four_elements_send_agent_json(array('jsonrpc' => '2.0', 'id' => $id, 'result' => array('tools' => four_elements_mcp_tools())));
    }
    if ($method === 'tools/call') {
        $result = four_elements_mcp_tool_result(isset($params['name']) ? $params['name'] : '', isset($params['arguments']) && is_array($params['arguments']) ? $params['arguments'] : array());
        four_elements_send_agent_json(array('jsonrpc' => '2.0', 'id' => $id, 'result' => $result));
    }

    four_elements_send_agent_json(four_elements_jsonrpc_error($id, -32601, 'Method not found.'), 404);
}

function four_elements_jsonrpc_error($id, $code, $message)
{
    return array('jsonrpc' => '2.0', 'id' => $id, 'error' => array('code' => $code, 'message' => $message));
}

function four_elements_mcp_tools()
{
    return array(
        array('name' => 'get_site_information', 'description' => 'Returns public basic information about 4elements.', 'inputSchema' => array('type' => 'object', 'properties' => array(), 'additionalProperties' => false)),
        array('name' => 'list_services_and_pages', 'description' => 'Lists important public 4elements pages and services.', 'inputSchema' => array('type' => 'object', 'properties' => array(), 'additionalProperties' => false)),
        array('name' => 'find_site_page', 'description' => 'Finds public 4elements pages matching a phrase.', 'inputSchema' => array('type' => 'object', 'properties' => array('query' => array('type' => 'string', 'minLength' => 2)), 'required' => array('query'), 'additionalProperties' => false)),
        array('name' => 'get_contact_details', 'description' => 'Returns public contact details for 4elements.', 'inputSchema' => array('type' => 'object', 'properties' => array(), 'additionalProperties' => false)),
        array('name' => 'get_faq', 'description' => 'Returns public FAQ answers about swimming lessons.', 'inputSchema' => array('type' => 'object', 'properties' => array(), 'additionalProperties' => false)),
    );
}

function four_elements_public_pages()
{
    return array(
        array('name' => 'Nauka pływania', 'url' => home_url('/nauka-plywania/'), 'keywords' => 'pływanie dzieci dorośli lekcje zajęcia'),
        array('name' => 'Cennik', 'url' => home_url('/nauka-plywania-cennik/'), 'keywords' => 'cena koszt płatność'),
        array('name' => 'Pływalnie', 'url' => home_url('/plywalnie-warszawa-wola/'), 'keywords' => 'basen adres lokalizacja Warszawa Wola Wilanów'),
        array('name' => 'Obozy i półkolonie', 'url' => home_url('/obozy-i-polkolonie/'), 'keywords' => 'obóz półkolonie lato zima dzieci'),
        array('name' => 'Treningi', 'url' => home_url('/treningi/'), 'keywords' => 'trening zajęcia sportowe personalne'),
        array('name' => 'Kontakt', 'url' => home_url('/kontakt/'), 'keywords' => 'telefon email wiadomość'),
        array('name' => 'Formularz rejestracyjny', 'url' => home_url('/formularz-rejestracyjny/'), 'keywords' => 'zapis zapisy rejestracja'),
        array('name' => 'FAQ', 'url' => home_url('/faq/'), 'keywords' => 'pytania odpowiedzi odwołanie zajęć'),
    );
}

function four_elements_mcp_tool_result($name, $arguments)
{
    $pages = four_elements_public_pages();
    if ($name === 'get_site_information') {
        $data = array('name' => get_bloginfo('name') ?: '4elements', 'description' => get_bloginfo('description'), 'website' => home_url('/'), 'faq' => home_url('/faq/'));
    } elseif ($name === 'list_services_and_pages') {
        $data = $pages;
    } elseif ($name === 'get_contact_details') {
        $data = array('email' => 'kontakt@4elements.pl', 'phones' => array('798 968 416', '798 784 748'), 'url' => home_url('/kontakt/'));
    } elseif ($name === 'get_faq') {
        $data = four_elements_faq_items();
    } elseif ($name === 'find_site_page') {
        $query = isset($arguments['query']) ? sanitize_text_field($arguments['query']) : '';
        $words = preg_split('/\s+/', mb_strtolower($query, 'UTF-8'));
        $data = array_values(array_filter($pages, function ($page) use ($words) {
            $haystack = mb_strtolower($page['name'] . ' ' . $page['keywords'], 'UTF-8');
            foreach ($words as $word) {
                if ($word !== '' && mb_strpos($haystack, $word, 0, 'UTF-8') !== false) {
                    return true;
                }
            }
            return false;
        }));
        if (!$data) {
            $data = array(array('name' => 'Wyniki wyszukiwania w serwisie', 'url' => home_url('/?s=' . rawurlencode($query))));
        }
    } else {
        return array('content' => array(array('type' => 'text', 'text' => 'Unknown tool.')), 'isError' => true);
    }

    return array('content' => array(array('type' => 'text', 'text' => wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))), 'structuredContent' => $data);
}

function four_elements_handle_a2a_request()
{
    four_elements_agent_cors_headers();
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(204);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST, OPTIONS');
        four_elements_send_agent_json(array('error' => 'Use POST for A2A JSON-RPC.'), 405);
    }

    $request = four_elements_agent_request_body();
    $id = is_array($request) && isset($request['id']) ? $request['id'] : null;
    if (!$request || empty($request['method'])) {
        four_elements_send_agent_json(four_elements_jsonrpc_error($id, -32600, 'Invalid JSON-RPC request.'), 400);
    }
    if ($request['method'] !== 'message/send') {
        four_elements_send_agent_json(four_elements_jsonrpc_error($id, -32601, 'Only message/send is supported.'), 404);
    }

    $parts = isset($request['params']['message']['parts']) && is_array($request['params']['message']['parts']) ? $request['params']['message']['parts'] : array();
    $message = '';
    foreach ($parts as $part) {
        if (isset($part['kind']) && $part['kind'] === 'text' && isset($part['text'])) {
            $message .= ' ' . sanitize_text_field($part['text']);
        }
    }
    $text = four_elements_a2a_reply($message);
    $task_id = wp_generate_uuid4();
    four_elements_send_agent_json(array('jsonrpc' => '2.0', 'id' => $id, 'result' => array(
        'id' => $task_id,
        'contextId' => '4elements-public-information',
        'status' => array('state' => 'completed'),
        'artifacts' => array(array('artifactId' => 'public-answer', 'parts' => array(array('kind' => 'text', 'text' => $text)))),
    )));
}

function four_elements_a2a_reply($message)
{
    $message = mb_strtolower($message, 'UTF-8');
    if (strpos($message, 'kontakt') !== false || strpos($message, 'telefon') !== false) {
        return 'Kontakt: 798 968 416 lub 798 784 748, e-mail kontakt@4elements.pl. Więcej: ' . home_url('/kontakt/');
    }
    if (strpos($message, 'cena') !== false || strpos($message, 'cennik') !== false || strpos($message, 'płat') !== false) {
        return 'Aktualne informacje o opłatach znajdują się na stronie: ' . home_url('/nauka-plywania-cennik/');
    }
    if (strpos($message, 'basen') !== false || strpos($message, 'pływal') !== false || strpos($message, 'lokal') !== false) {
        return 'Informacje o pływalniach i lokalizacjach: ' . home_url('/plywalnie-warszawa-wola/');
    }
    if (strpos($message, 'zapis') !== false || strpos($message, 'rejestr') !== false) {
        return 'Zapisy prowadzi formularz: ' . home_url('/formularz-rejestracyjny/');
    }
    return 'Publiczne informacje o 4elements znajdziesz na stronie głównej ' . home_url('/') . ', w FAQ ' . home_url('/faq/') . ' oraz na stronie kontaktowej ' . home_url('/kontakt/') . '.';
}


/**
 * NLWeb-compatible natural-language query endpoint.
 *
 * It is intentionally retrieval-only: answers are assembled from the public
 * FAQ and verified page descriptions, with source URLs in every response.
 * No visitor query is sent to a third-party AI provider.
 */
function four_elements_handle_nlweb_request()
{
    four_elements_agent_cors_headers();
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(204);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: GET, POST, OPTIONS');
        four_elements_send_agent_json(array('error' => array('code' => 'method_not_allowed', 'message' => 'Use GET or POST.')), 405);
    }

    $body = $_SERVER['REQUEST_METHOD'] === 'POST' ? four_elements_agent_request_body() : array();
    $query = isset($_GET['query']) ? wp_unslash($_GET['query']) : '';
    if ($query === '' && isset($_GET['q'])) {
        $query = wp_unslash($_GET['q']);
    }
    if ($query === '' && is_array($body) && isset($body['query'])) {
        $query = $body['query'];
    }
    if (is_array($query)) {
        $query = isset($query['text']) ? $query['text'] : '';
    }
    $query = trim(sanitize_text_field((string) $query));
    if ($query === '') {
        four_elements_send_agent_json(array(
            '_meta' => array(
                'response_type' => 'elicitation',
                'response_format' => 'conversational_search',
                'version' => '0.55',
            ),
            'elicitation' => array(
                'message' => 'Podaj pytanie o zajęcia, zapisy, płatności, pływalnie lub kontakt z 4elements.',
                'requestedSchema' => array(
                    'type' => 'object',
                    'properties' => array('query' => array('type' => 'string', 'minLength' => 2)),
                    'required' => array('query'),
                ),
            ),
            'examples' => array('Czy mogę odwołać zajęcia?', 'Gdzie są pływalnie?', 'Jak zapisać dziecko?'),
        ));
    }
    if (strlen($query) > 300) {
        four_elements_send_agent_json(array(
            '_meta' => array('response_type' => 'failure', 'version' => '0.55'),
            'error' => array('code' => 'INVALID_QUERY', 'message' => 'Pytanie może zawierać maksymalnie 300 znaków.'),
        ));
    }

    $limit = four_elements_nlweb_rate_limit();
    if (!$limit['allowed']) {
        header('Retry-After: ' . $limit['retry_after']);
        four_elements_send_agent_json(array(
            '_meta' => array('response_type' => 'failure', 'version' => '0.55'),
            'error' => array('code' => 'RATE_LIMITED', 'message' => 'Spróbuj ponownie za chwilę.'),
        ), 429);
    }

    $results = four_elements_nlweb_search($query);
    $answer = four_elements_nlweb_answer($results);
    $structured_results = array(
        array(
            '@type' => 'SearchSummary',
            'text' => $answer,
            'grounding' => array(
                'sources' => array_map(function ($result) {
                    return array('name' => $result['title'], 'url' => $result['url']);
                }, $results),
            ),
        ),
    );

    foreach ($results as $result) {
        if (isset($result['answer'])) {
            $structured_results[] = array(
                '@type' => 'Question',
                'name' => $result['title'],
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => $result['answer'],
                ),
                'url' => $result['url'],
                'grounding' => array('sources' => array(array('name' => 'FAQ 4elements', 'url' => $result['url']))),
            );
            continue;
        }

        $structured_results[] = array(
            '@type' => 'WebPage',
            'name' => $result['title'],
            'description' => $result['text'],
            'url' => $result['url'],
            'grounding' => array('sources' => array(array('name' => $result['title'], 'url' => $result['url']))),
        );
    }

    four_elements_send_agent_json(array(
        '_meta' => array(
            'response_type' => 'answer',
            'response_format' => 'conversational_search',
            'version' => '0.55',
        ),
        'results' => $structured_results,
    ));
}

function four_elements_nlweb_rate_limit()
{
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : 'unknown';
    $key = 'four_elements_ask_' . md5($ip);
    $current = get_transient($key);
    $now = time();
    if (!is_array($current) || empty($current['started']) || $now - (int) $current['started'] >= 60) {
        set_transient($key, array('started' => $now, 'count' => 1), MINUTE_IN_SECONDS);
        return array('allowed' => true, 'retry_after' => 0);
    }
    if ((int) $current['count'] >= 30) {
        return array('allowed' => false, 'retry_after' => max(1, 60 - ($now - (int) $current['started'])));
    }
    $current['count']++;
    set_transient($key, $current, max(1, 60 - ($now - (int) $current['started'])));
    return array('allowed' => true, 'retry_after' => 0);
}

function four_elements_nlweb_normalize($value)
{
    $value = strtolower(remove_accents(wp_strip_all_tags((string) $value)));
    return preg_replace('/[^a-z0-9]+/', ' ', $value);
}

function four_elements_nlweb_search($query)
{
    $pages = array(
        array('title' => 'Nauka pływania', 'text' => 'Nauka pływania dla dzieci i dorosłych, zajęcia indywidualne i grupowe.', 'url' => home_url('/nauka-plywania/')),
        array('title' => 'Cennik', 'text' => 'Aktualne ceny i informacje o płatnościach za zajęcia nauki pływania.', 'url' => home_url('/nauka-plywania-cennik/')),
        array('title' => 'Pływalnie', 'text' => 'Pływalnie i lokalizacje zajęć w Warszawie, Woli i Wilanowie.', 'url' => home_url('/plywalnie-warszawa-wola/')),
        array('title' => 'Formularz rejestracyjny', 'text' => 'Zapisy i rejestracja na zajęcia.', 'url' => home_url('/formularz-rejestracyjny/')),
        array('title' => 'Kontakt', 'text' => 'Kontakt telefoniczny i e-mail z 4elements.', 'url' => home_url('/kontakt/')),
    );
    foreach (array_merge(four_elements_answer_capsule_items(), four_elements_faq_items()) as $item) {
        $pages[] = array('title' => $item['question'], 'text' => $item['answer'], 'url' => home_url('/faq/'), 'answer' => $item['answer']);
    }

    $synonyms = array(
        'cena' => array('platnosc', 'oplata', 'wplata', 'cennik'),
        'koszt' => array('platnosc', 'oplata', 'wplata', 'cennik'),
        'odwolanie' => array('odwolac', 'odrobic', 'semestr'),
        'nieobecnosc' => array('odwolac', 'odrobic'),
        'basen' => array('plywalnia', 'lokalizacja', 'wola', 'wilanow'),
        'adres' => array('plywalnia', 'lokalizacja', 'warszawa'),
        'zapis' => array('zapisy', 'rejestracja', 'formularz'),
        'dziecko' => array('dzieci', 'wiek', 'grupa'),
        'rodzic' => array('szatni', 'dzieckiem'),
        'sprzet' => array('czepek', 'recznik', 'klapki', 'okulary'),
    );
    $terms = array_filter(explode(' ', four_elements_nlweb_normalize($query)), function ($term) {
        return strlen($term) > 1;
    });
    $matches = array();
    foreach ($pages as $page) {
        $haystack = four_elements_nlweb_normalize($page['title'] . ' ' . $page['text']);
        $score = 0;
        foreach ($terms as $term) {
            if (strpos($haystack, $term) !== false) {
                $score += 5;
                continue;
            }
            if (isset($synonyms[$term])) {
                foreach ($synonyms[$term] as $synonym) {
                    if (strpos($haystack, $synonym) !== false) {
                        $score += 3;
                        break;
                    }
                }
            }
        }
        if ($score > 0) {
            $page['score'] = $score;
            $matches[] = $page;
        }
    }
    usort($matches, function ($first, $second) {
        return $second['score'] - $first['score'];
    });
    if (!$matches) {
        $matches = array(
            array('title' => 'Najczęściej zadawane pytania', 'text' => 'Odpowiedzi o zajęciach, zapisach, płatnościach i pływalniach.', 'url' => home_url('/faq/'), 'score' => 0),
            array('title' => 'Kontakt', 'text' => 'Jeśli nie ma odpowiedzi na stronie, skontaktuj się z 4elements.', 'url' => home_url('/kontakt/'), 'score' => 0),
        );
    }
    return array_slice($matches, 0, 3);
}

function four_elements_nlweb_answer($results)
{
    $best = reset($results);
    if (isset($best['answer'])) {
        return $best['answer'];
    }
    return 'Najbardziej pomocna będzie strona „' . $best['title'] . '”. ' . $best['text'];
}
