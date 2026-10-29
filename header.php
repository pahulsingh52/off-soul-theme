<?php
/** Header: glass bar, collections, menu, search and saved panels. */
$cats = array();
if ( taxonomy_exists( 'product_cat' ) ) {
	$found = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'exclude' => array( (int) get_option( 'default_product_cat' ) ) ) );
	if ( ! is_wp_error( $found ) ) { $cats = $found; }
}
$colours = array();
foreach ( array( 'pa_colour', 'pa_color' ) as $tax ) {
	if ( taxonomy_exists( $tax ) ) {
		$found = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => true ) );
		if ( ! is_wp_error( $found ) && $found ) { $colours = $found; break; }
	}
}
$shop = os_shop_url();
$icon = 'width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="os-hdr os-glass">
	<div class="os-l">
		<a href="<?php echo esc_url( add_query_arg( 'orderby', 'date', $shop ) ); ?>">New in</a>
		<button type="button" class="os-lnk" data-os-open="col" aria-expanded="false" aria-controls="os-col">Collections</button>
	</div>
	<a class="os-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/logo.png' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
		<?php } ?>
	</a>
	<div class="os-r">
		<button type="button" class="os-ib" data-os-open="search" aria-label="Search"><svg <?php echo $icon; // phpcs:ignore ?>><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/></svg></button>
		<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
		<a class="os-ib hm" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="Account"><svg <?php echo $icon; // phpcs:ignore ?>><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6"/></svg></a>
		<?php endif; ?>
		<button type="button" class="os-ib" data-os-open="drawer" aria-label="Saved items"><svg <?php echo $icon; // phpcs:ignore ?>><path d="M7 3h10v18l-5-4-5 4z"/></svg><span class="os-bdg" id="os-wn">0</span></button>
		<?php if ( function_exists( 'wc_get_cart_url' ) ) : ?>
		<a class="os-ib" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="Bag"><svg <?php echo $icon; // phpcs:ignore ?>><path d="M6 7h12l1 14H5z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg><?php echo os_bag_badge(); // phpcs:ignore ?></a>
		<?php endif; ?>
		<button type="button" class="os-burger" id="os-burger" data-os-open="menu" aria-label="Menu" aria-expanded="false" aria-controls="os-menu"><svg class="o" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 9h16M4 15h16"/></svg><svg class="c" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
	</div>
</header>
<div class="os-scrim" id="os-scrim"></div>

<section class="os-panel" id="os-col" inert aria-label="Collections"><div class="os-cols">
<?php foreach ( $cats as $t ) :
	$tid = (int) get_term_meta( $t->term_id, 'thumbnail_id', true ); ?>
	<a class="os-cc" href="<?php echo esc_url( get_term_link( $t ) ); ?>">
		<?php echo $tid ? wp_get_attachment_image( $tid, 'medium', false, array( 'alt' => '' ) ) : '<div class="os-ph"></div>'; // phpcs:ignore ?>
		<span><?php echo esc_html( $t->name ); ?></span>
	</a>
<?php endforeach; if ( ! $cats ) { echo '<p class="os-mut">Add product categories in WooCommerce to fill this panel.</p>'; } ?>
</div></section>

<section class="os-panel" id="os-menu" inert aria-label="Menu"><div class="os-mega">
	<div><a class="m" href="<?php echo esc_url( add_query_arg( 'orderby', 'date', $shop ) ); ?>">New arrivals</a><a class="m" href="<?php echo esc_url( $shop ); ?>">Shop all</a></div>
	<div><h4>Categories</h4><?php foreach ( $cats as $t ) { echo '<a class="os-chip" href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>'; } ?></div>
	<div><h4>Shop by colour</h4><?php foreach ( $colours as $t ) { echo '<a class="os-chip" href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>'; } ?></div>
</div></section>

<section class="os-panel" id="os-search" inert aria-label="Search">
	<div class="os-sbar">
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="hidden" name="post_type" value="product">
			<input type="search" name="s" id="os-q" placeholder="Search here…" aria-label="Search products" autocomplete="off" value="<?php echo esc_attr( get_search_query() ); ?>">
		</form>
		<button type="button" class="os-ib" data-os-close aria-label="Close search">✕</button>
	</div>
	<h3 style="margin:24px 0 8px">Collections</h3>
	<?php foreach ( $cats as $t ) :
		$tid = (int) get_term_meta( $t->term_id, 'thumbnail_id', true ); ?>
		<a class="os-srow" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo $tid ? wp_get_attachment_image( $tid, 'thumbnail', false, array( 'alt' => '' ) ) : '<div class="os-ph"></div>'; // phpcs:ignore ?><span><?php echo esc_html( $t->name ); ?></span></a>
	<?php endforeach; ?>
</section>

<aside class="os-drawer" id="os-drawer" inert aria-label="Saved items">
	<div style="display:flex;justify-content:space-between;align-items:center"><h2 style="margin:0">Saved</h2><button type="button" class="os-ib" data-os-close aria-label="Close">✕</button></div>
	<div id="os-saved"></div>
</aside>
<div class="os-toast os-glass" id="os-toast" role="status"></div>
<main class="os-main">
