<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package qwerty
 */

if ( ! is_active_sidebar( 'footer-1' ) ) {
	return;
}
?>

<div class="widget-area-wrapper">
	<div id="secondary" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'footer-1' ); ?>
	</div><!-- #secondary -->
</div>
