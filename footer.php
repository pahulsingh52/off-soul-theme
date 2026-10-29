<?php /** Footer. */ ?>
</main>
<footer class="os-foot">
	<?php
	os_footer_col( 'footer_1', 'Connect with us', array( 'Call', 'Text (WhatsApp)', 'Instagram', 'YouTube' ) );
	os_footer_col( 'footer_2', 'Order support', array( 'Make a return/exchange', 'Refund policy', 'Track your order', 'Shipping policy', 'FAQ’s', 'Terms' ) );
	os_footer_col( 'footer_3', 'We are Off Soul', array( 'Our story', 'Collaborations', 'Media', 'Blogs' ) );
	?>
	<img src="<?php echo esc_url( get_theme_file_uri( 'assets/logo.png' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>">
</footer>
<?php wp_footer(); ?>
</body>
</html>
