<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package qwerty
 */
?>

	</div><!-- #content -->

	<?php get_sidebar(); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php printf( __( '<a href="http://sevenbold.com/wordpress/qwerty/">%1$s Theme</a> <i>by</i> %2$s', 'qwerty' ), 'Qwerty', '<a href="http://sevenbold.com/wordpress/" rel="designer">Seven Bold</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
