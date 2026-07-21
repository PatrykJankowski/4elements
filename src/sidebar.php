<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="sidebar" class="blog-sidebar-panel" role="complementary">
    <section class="blog-sidebar-panel__section" aria-labelledby="blog-sidebar-categories-heading">
        <p class="blog-sidebar-panel__eyebrow">Przeglądaj artykuły</p>
        <h2 id="blog-sidebar-categories-heading" class="blog-sidebar-panel__title">Kategorie</h2>
        <ul class="blog-sidebar-panel__list">
            <?php
            wp_list_categories(array(
                'title_li' => '',
                'show_count' => true,
                'orderby' => 'count',
                'order' => 'DESC',
            ));
            ?>
        </ul>
    </section>
</div>
