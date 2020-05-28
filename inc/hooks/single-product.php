<?php

// Control min/max when adding product to cart
add_filter('woocommerce_available_variation', function ($args, $product) {
    $args['min_qty'] = 6;

    return $args;
}, 10, 2);

add_filter('woocommerce_quantity_input_args', function ($args, $product) {
    $args['step'] = 6;
    $args['min_value'] = 6;

    return $args;
}, 10, 2);

// Add hidden input field to bind to Vue data for capturing Cloudinary image URL's to send along with $_POST data
add_action('woocommerce_after_variations_form', function () {
    echo '<input type="hidden" name="cloudinary_image_urls" v-model="finalUrls" />';
});

// Append Cloudinary image to cart item during 'add to cart'
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id, $quantity) {
	if (!isset($_POST['cloudinary_image_urls'])) {
		return;
	}

    $urls = json_decode(stripslashes($_POST['cloudinary_image_urls']), true);

    $cart_item_data['cloudinary_image_urls'] = $urls;

    return $cart_item_data;
}, 10, 4);

// Append the Informational Tabs
add_filter('woocommerce_product_tabs', function ( $array ) {

	$options = [ 'frame', 'paper', 'ink' ];
	$order   = 1;
	$tabs    = [];

	foreach ( $options as $option ) {
		$tabs[$option] = [
			'title'    => ucfirst( $option ) . ' Options',
			'priority' => $order++,
			'callback' => function ( $tab, $context ) {
				require BB_TEMPLATES_PATH . 'tab.php';
			},
		];
	}
	
	return array_merge( $array, $tabs );
});

// Remove the variable price range.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
