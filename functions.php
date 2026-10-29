<?php
/** Off Soul theme setup. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'OS_VER', '0.1.0' );
define( 'OS_BM', '<svg width="20" height="20" viewBox="0 0 24 24" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M7 3h10v18l-5-4-5 4z"/></svg>' );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 200, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	register_nav_menus( array(
		'footer_1' => 'Footer: Connect with us',
		'footer_2' => 'Footer: Order support',
		'footer_3' => 'Footer: About',
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'os-fonts', 'https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&family=Inter:wght@400;500;600&display=swap', array(), null );
	wp_enqueue_style( 'off-soul', get_stylesheet_uri(), array( 'os-fonts' ), OS_VER );
	wp_enqueue_script( 'off-soul', get_theme_file_uri( 'assets/theme.js' ), array(), OS_VER, true );
} );

function os_shop_url() {
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
}

function os_bag_badge() {
	$n = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
	return '<span class="os-bdg os-bag-n' . ( $n ? ' on' : '' ) . '">' . $n . '</span>';
}
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	$fragments['span.os-bag-n'] = os_bag_badge();
	return $fragments;
} );

function os_save_button( $product ) {
	$img = wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' );
	if ( ! $img && function_exists( 'wc_placeholder_img_src' ) ) { $img = wc_placeholder_img_src(); }
	printf(
		'<button type="button" class="os-save os-glass" data-id="%d" data-name="%s" data-url="%s" data-img="%s" aria-pressed="false" aria-label="Save to wishlist">%s</button>',
		(int) $product->get_id(),
		esc_attr( $product->get_name() ),
		esc_url( $product->get_permalink() ),
		esc_url( (string) $img ),
		OS_BM // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

// Save button on every product card.
add_action( 'woocommerce_before_shop_loop_item', function () {
	global $product;
	if ( $product ) { os_save_button( $product ); }
}, 5 );

// "Sold out" tag and second photo on hover.
add_action( 'woocommerce_before_shop_loop_item_title', function () {
	global $product;
	if ( ! $product ) { return; }
	if ( ! $product->is_in_stock() ) { echo '<span class="os-tag">Sold out</span>'; }
	$ids = $product->get_gallery_image_ids();
	if ( ! empty( $ids ) ) {
		echo wp_get_attachment_image( $ids[0], 'woocommerce_thumbnail', false, array( 'class' => 'os-alt', 'alt' => '' ) );
	}
}, 11 );

// Save button on the product page.
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product ) { return; }
	$img = wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' );
	printf(
		'<button type="button" class="os-sc os-save-inline" data-id="%d" data-name="%s" data-url="%s" data-img="%s" aria-pressed="false">%s <span>Save</span></button>',
		(int) $product->get_id(),
		esc_attr( $product->get_name() ),
		esc_url( $product->get_permalink() ),
		esc_url( (string) $img ),
		OS_BM // phpcs:ignore WordPress.Security.EscapeOutput
	);
}, 35 );

function os_footer_col( $location, $title, $defaults ) {
	echo '<div><h4>' . esc_html( $title ) . '</h4>';
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu( array( 'theme_location' => $location, 'container' => false, 'depth' => 1 ) );
	} else {
		echo '<ul>';
		foreach ( $defaults as $label ) { echo '<li><a href="#">' . esc_html( $label ) . '</a></li>'; }
		echo '</ul>';
	}
	echo '</div>';
}
