<?php
function wp_adv_theme_support()
{
    add_theme_support('title-tag');
}

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
