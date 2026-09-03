<?php
/**
 * Product card in listing grids — image, name, Add to Cart (no price).
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $product;
if ( empty( $product ) || ! $product->is_visible() ) { return; }
$permalink = get_permalink( $product->get_id() );
$cart_icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1.4"/><circle cx="18" cy="21" r="1.4"/><path d="M2 3h3l2.6 13.4a1.6 1.6 0 0 0 1.6 1.3h9.3a1.6 1.6 0 0 0 1.6-1.3L23 7H6"/></svg>';
?>
<li <?php wc_product_class( 'ut-prod-card', $product ); ?>>
	<a href="<?php echo esc_url( $permalink ); ?>" class="ut-prod-media" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
		<?php echo $product->get_image( 'woocommerce_single' ); // uncropped, keeps full product visible ?>
	</a>
	<div class="ut-prod-foot">
		<h3 class="ut-prod-name"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
		<?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
			<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" rel="nofollow"
			   class="ut-prod-link add_to_cart_button ajax_add_to_cart"><?php echo $cart_icon; // phpcs:ignore ?>Add to Cart</a>
		<?php else : ?>
			<a href="<?php echo esc_url( $permalink ); ?>" class="ut-prod-link">View Details
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
		<?php endif; ?>
	</div>
</li>
