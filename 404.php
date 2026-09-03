<?php
/**
 * 404 — page not found.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$shop = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' );
$contact = get_page_by_path( 'contact' );
?>
<main class="section ut-404">
	<div class="container">
		<div class="ut-404-inner">
			<div class="ut-404-badge">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/mark.webp' ); ?>" alt="Unitourk">
			</div>
			<span class="ut-404-code">404</span>
			<h1>This page has gone off-site</h1>
			<p>The page you're looking for isn't here &mdash; it may have moved, or the link might be out of date. Let's get you back to safety.</p>

			<form role="search" method="get" class="ut-404-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="product">
				<input type="search" name="s" placeholder="Search the catalog&hellip;" aria-label="Search products">
				<button type="submit" aria-label="Search"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg></button>
			</form>

			<div class="ut-404-links">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Back to Home
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
				</a>
				<a href="<?php echo esc_url( $shop ); ?>" class="btn btn-ghost">Browse Catalog</a>
				<?php if ( $contact ) : ?><a href="<?php echo esc_url( get_permalink( $contact ) ); ?>" class="btn btn-ghost">Contact Us</a><?php endif; ?>
			</div>

			<div class="ut-404-cats">
				<span>Popular categories</span>
				<div class="ut-404-cat-list">
					<?php
					$cats = unitourk_categories();
					$shown = 0;
					foreach ( $cats as $slug => $data ) {
						$term = get_term_by( 'slug', $slug, 'product_cat' );
						if ( $term && $term->count > 0 ) {
							echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $data[0] ) . '</a>';
							if ( ++$shown >= 4 ) { break; }
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
