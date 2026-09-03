<?php
/**
 * Product detail — image(s), name, description/specs, inquiry button (no price),
 * breadcrumb, related products.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
unitourk_breadcrumb();

while ( have_posts() ) :
	the_post();
	global $product;
	if ( ! $product ) { $product = wc_get_product( get_the_ID() ); }
	$cats = wc_get_product_category_list( get_the_ID(), ', ' );
	?>
	<main class="section ut-single">
		<div class="container">
			<div class="ut-single-grid">
				<div class="ut-gallery"><?php woocommerce_show_product_images(); ?></div>

				<div class="ut-summary">
					<?php if ( $cats ) : ?><div class="ut-cats"><?php echo wp_kses_post( $cats ); ?></div><?php endif; ?>
					<h1 class="ut-title"><?php the_title(); ?></h1>

					<?php $short = apply_filters( 'woocommerce_short_description', get_the_excerpt() ); ?>
					<?php if ( $short ) : ?><div class="ut-short"><?php echo wp_kses_post( $short ); ?></div><?php endif; ?>

					<?php if ( $product->is_type( 'variable' ) ) : ?>
						<?php // Variable product: WooCommerce's native variation selectors + per-variation image swap. ?>
						<div class="ut-variations"><?php woocommerce_template_single_add_to_cart(); ?></div>
					<?php else : ?>
						<form class="ut-add-form" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
							<div class="ut-qty-input">
								<button type="button" class="ut-minus" aria-label="Decrease">&minus;</button>
								<input type="number" name="quantity" value="1" min="1" inputmode="numeric" aria-label="Quantity">
								<button type="button" class="ut-plus" aria-label="Increase">+</button>
							</div>
							<button type="submit" class="btn btn-primary ut-add-inquiry" data-id="<?php echo esc_attr( $product->get_id() ); ?>">
								<?php echo esc_html( UNITOURK_CART_BTN ); ?>
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
							</button>
						</form>
					<?php endif; ?>

					<div class="ut-assure">
						<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5z"/><path d="M9 12l2 2 4-4"/></svg>Certified to international standards</span>
						<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.7 2.1Z"/></svg>Volume &amp; contract supply available</span>
					</div>
				</div>
			</div>

			<?php
			$content = get_the_content();
			if ( trim( $content ) ) : ?>
				<div class="ut-desc">
					<h3>Description &amp; Specifications</h3>
					<div class="ut-desc-body"><?php the_content(); ?></div>
				</div>
			<?php endif; ?>

			<?php woocommerce_output_related_products(); ?>
		</div>
	</main>
	<?php
endwhile;

get_footer();
