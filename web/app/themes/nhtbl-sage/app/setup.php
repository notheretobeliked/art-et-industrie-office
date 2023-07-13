<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css');
    wp_enqueue_script('mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js');
}, 200);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from the Soil plugin if activated.
     *
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'nav-walker',
        'nice-search',
        'relative-urls',
    ]);

    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Hamburger Navigation', 'sage'),
        'shortcut_navigation' => __('Top navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
        'editor-styles',
    ]);

    add_editor_style(asset('app.css'));
    add_editor_style(asset('editor.css'));

    if( function_exists('acf_add_options_page') ) {
        acf_add_options_page(array(
            'page_title'    => 'Paramètres generaux',
            'menu_title'    => 'Paramètres generaux',
            'menu_slug'     => 'general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }


    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Register Polylang strings (I really really really hate Polylang)
     * @link https://polylang.wordpress.com/documentation/documentation-for-developers/functions-reference/
     */

     pll_register_string( 'eventsbutton', 'Evènements et plus d\'informations' );
     pll_register_string( 'fromdate', 'du 10 juin 2023' );
     pll_register_string( 'todate', 'au 14 janvier 2024' );
     pll_register_string( 'consciences', 'Consciences énergétiques' );
     pll_register_string( 'energie', 'Energie' );
     pll_register_string( 'visible', 'Visible de nuit' );
     pll_register_string( 'accessible', 'Accessible aux horaires d’ouverture' );
     pll_register_string( 'promenades', 'Promenades' );
     pll_register_string( 'petitboucle', 'Petite boucle (4 km)' );
     pll_register_string( 'grandboucle', 'Grande boucle (17 km)' );
     pll_register_string( 'bouclegrandlarge', 'Boucle du Grand Large (2 km)' );

     pll_register_string( 'fermer', 'Fermer ↑' );
     pll_register_string( 'filtres', 'Filtrés par ↓' );
     pll_register_string( 'categories', 'Catégories');
     pll_register_string( 'tous', 'Tous');
     pll_register_string( 'quand', 'Quand');
     pll_register_string( 'avenir', 'A venir');
     pll_register_string( 'today', 'Aujourd’hui');
     pll_register_string( 'thismonth', 'Ce mois');
     pll_register_string( 'past', 'Passé');

}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});
