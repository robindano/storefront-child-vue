<?php

add_action('wp_enqueue_scripts', function () {
    $version = md5_file(get_stylesheet_directory() . '/mix-manifest.json');

    wp_enqueue_style('woo_photo', get_stylesheet_directory_uri() . '/dist/css/app.css', [], $version);

    if (is_product()) {
        wp_enqueue_script('cloudinary', 'https://widget.cloudinary.com/v2.0/global/all.js', ['jquery'], $version, true);
    }

    if (is_product() || is_cart() && !WC()->cart->is_empty()) {
        wp_enqueue_script('woo_photo', get_stylesheet_directory_uri() . '/dist/js/app.js', ['jquery', 'cloudinary'], $version, true);
    }
});

add_action('admin_enqueue_scripts', function () {
    $version = md5_file(get_stylesheet_directory() . '/mix-manifest.json');

    wp_enqueue_style('woo_photo', get_stylesheet_directory_uri() . '/dist/css/app.css', [], $version);
});
