<?php

// Adjust Exhibition Prints quantity based on number of images
add_filter('woocommerce_add_to_cart_quantity', function ($quantity, $product_id) {
    $product = wc_get_product($product_id);

    if ($product->get_name() !== 'Exhibition Prints') {
        return $quantity;
    }

    $gme_quantity = isset($_POST['gme_exhibition_quantity'])
        ? (int) $_POST['gme_exhibition_quantity']
        : 0;

    return $gme_quantity;
}, 10, 2);

// Add hidden input field to bind to Vue data for capturing GME image URL's to send along with $_POST data
add_action('woocommerce_after_variations_form', function () {
    echo '<input type="hidden" name="gme_image_data" v-model="finData" />';
    echo '<input type="hidden" name="gme_exhibition_quantity" v-model="exhibitionQuantity" />';
});

add_action('woocommerce_composite_after_components', function () {
    echo '<input type="hidden" name="gme_image_data" v-model="finData" />';
    echo '<input type="hidden" name="gme_exhibition_quantity" v-model="exhibitionQuantity" />';
});

// Hide woo input field and use Vue binding to handle quantity based on number of images
add_action('woocommerce_before_quantity_input_field', function () {
    global $product;

    if (!is_product() || $product->get_name() !== 'Exhibition Prints') {
        return;
    }
    
    echo '<exhibition-prints-qty></exhibition-prints-qty>';
    echo '<div style="display: none;">';
});

add_action('woocommerce_after_quantity_input_field', function () {
    global $product;

    if (!is_product() || $product->get_name() !== 'Exhibition Prints') {
        return;
    }

    echo '</div>';
});

// Append GME image data to cart item during 'add to cart'
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id, $quantity) {
    if (empty($_POST['gme_image_data'])) {
        return;
    }

    $cart_item_data['gme_image_data'] = json_decode(stripslashes($_POST['gme_image_data']), true);

    return $cart_item_data;
}, 10, 4);
