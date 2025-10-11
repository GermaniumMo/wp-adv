<?php
function wp_adv_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'wp_adv_theme_support');

function wp_adv_menus()
{
    $locations = [
        'primary' => 'Desktop Primary Left Sidebar',
        'footer'  => 'Footer Menu Items',
    ];
    register_nav_menus($locations);
}

add_action('init', 'wp_adv_menus');

function wp_adv_register_styles()
{
    // 1. Load Bootstrap first
    wp_enqueue_style(
        'wp-adv-bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css',
        [],
        '5.0.2',
        'all'
    );
    wp_enqueue_style(
        'wp-adv-style',
        get_stylesheet_uri(),
        ['wp-adv-bootstrap'],
        '1.0',
        'all'
    );
    // 2. Load Font Awesome next
    wp_enqueue_style(
        'wp-adv-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css',
        [],
        '7.0.1',
        'all'
    );
}

add_action('wp_enqueue_scripts', 'wp_adv_register_styles');

function wp_adv_register_scripts()
{
    wp_enqueue_script(
        'wp-adv-bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
        ['jquery'],
        '5.0.2',
        true
    );
    wp_enqueue_script(
        'wp-adv-js',
        get_template_directory_uri() . '/assests/js/main.js',
        [],
        '1.0',
        true
    );
}

add_action('wp_enqueue_scripts', 'wp_adv_register_scripts');

/**
 * Add Bootstrap-compatible classes to menu <li> elements and map current item to 'active'.
 */
function wp_adv_nav_menu_li_class($classes, $item, $args, $depth)
{
    // Only modify menus that use the navbar-nav class (our primary menu)
    if (isset($args->menu_class) && strpos($args->menu_class, 'navbar-nav') !== false) {
        // add bootstrap nav-item class
        $classes[] = 'nav-item text-decoration-none';

        // map WP's current item classes to Bootstrap 'active'
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $classes[] = 'active';
        }
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'wp_adv_nav_menu_li_class', 10, 4);

/**
 * Add Bootstrap 'nav-link' class to menu links and optionally make contact link a button.
 */
function wp_adv_nav_menu_link_atts($atts, $item, $args, $depth)
{
    if (isset($args->menu_class) && strpos($args->menu_class, 'navbar-nav') !== false) {
        $existing      = isset($atts['class']) ? $atts['class'] . ' ' : '';
        $atts['class'] = trim($existing . 'nav-link');

        // If the menu item title contains 'contact' or the menu item has a 'contact' class,
        // add button classes so it looks like the original static markup.
        $title = strtolower(trim($item->title));
        if (strpos($title, 'contact') !== false || in_array('contact', $item->classes)) {
            $atts['class'] .= ' btn btn-primary';
        }
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'wp_adv_nav_menu_link_atts', 10, 4);

/**
 * Prepend FontAwesome icon HTML to menu item titles when the menu item has a fa- or fab- class.
 *
 * To use: in WP Admin > Appearance > Menus, open a menu item and add a CSS class like 'fa-home' or 'fab-twitter'.
 */
function wp_adv_nav_menu_item_title($title, $item, $args, $depth)
{
    if (isset($args->menu_class) && strpos($args->menu_class, 'navbar-nav') !== false) {
        // Look for any class starting with 'fa-' or 'fab-'
        $icon_class = '';
        foreach ($item->classes as $c) {
            if (strpos($c, 'fa-') === 0 || strpos($c, 'fab-') === 0 || strpos($c, 'fas-') === 0 || strpos($c, 'far-') === 0) {
                $icon_class = $c;
                break;
            }
        }

        if ($icon_class) {
            // Add fixed width and right margin like the original markup
            $title = '<i class="' . esc_attr($icon_class) . ' fa-fw mr-2"></i>' . $title;
        }
    }

    return $title;
}
add_filter('nav_menu_item_title', 'wp_adv_nav_menu_item_title', 10, 4);
