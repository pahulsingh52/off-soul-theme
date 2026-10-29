<?php
/** Standard pages, including Cart, Checkout and My account. */
get_header();
while ( have_posts() ) : the_post(); ?>
<div class="os-wrap"><article class="os-page"><h1><?php the_title(); ?></h1><?php the_content(); ?></article></div>
<?php endwhile;
get_footer();
