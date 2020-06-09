<?php

// Append the Informational Tabs
add_filter('woocommerce_product_tabs', function ($array) {
    $options = ['frame', 'paper', 'ink'];
    $order   = 1;
    $tabs    = [];

    foreach ($options as $option) {
        $tabs[$option] = [
            'title'    => ucfirst($option) . ' Options',
            'priority' => $order++,
            'callback' => function ($tab, $context) {
                require GME_TEMPLATES_PATH . 'tab.php';
            },
        ];
    }

    return array_merge($array, $tabs);
});
