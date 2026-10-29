<?php
/** Homepage: hero (featured image), latest drop, then page content. */
get_header();
while ( have_posts() ) : the_post(); ?>
<section class="os-hero" id="os-hero">
	<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'full', array( 'class' => 'os-hbg' ) ); } ?>
	<div class="os-hv"></div>
	<div class="os-hc"><h1 class="os-gt"><?php the_title(); ?></h1><a class="os-btn os-glass" href="#drop">Shop now</a></div>
</section>
<section class="os-sec" id="drop">
	<div class="os-hd2"><span>Latest drop</span><a class="os-pill" href="<?php echo esc_url( os_shop_url() ); ?>">Discover more</a></div>
	<?php
	if ( class_exists( 'WooCommerce' ) ) {
		$q = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => 4, 'orderby' => 'date', 'order' => 'DESC' ) );
		if ( $q->have_posts() ) {
			wc_set_loop_prop( 'columns', 4 );
			woocommerce_product_loop_start();
			while ( $q->have_posts() ) { $q->the_post(); wc_get_template_part( 'content', 'product' ); }
			woocommerce_product_loop_end();
			wp_reset_postdata();
		} else {
			echo '<p class="os-mut">Add products in WooCommerce to fill this row.</p>';
		}
	} else {
		echo '<p class="os-mut">Activate WooCommerce to show products here.</p>';
	}
	?>
</section>
<div class="os-content"><?php the_content(); ?></div>
<?php endwhile;
get_footer();
