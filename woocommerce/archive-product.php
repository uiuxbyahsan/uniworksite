<?php
/**
 * Category / catalog listing — heading, breadcrumb, 4×2 grid, pagination.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
unitourk_breadcrumb();

$title       = woocommerce_page_title( false );
$is_search   = is_search();
$found_total = (int) ( $GLOBALS['wp_query']->found_posts ?? 0 );
?>
<main class="section ut-archive">
	<div class="container">
		<div class="ut-archive-head reveal">
			<div class="section-head">
				<?php if ( $is_search ) :
					$sq = get_search_query(); ?>
					<h2>Search results</h2>
					<p>
						<?php
						/* translators: 1: number of results, 2: search term */
						printf(
							esc_html( _n( '%1$d result for &ldquo;%2$s&rdquo;', '%1$d results for &ldquo;%2$s&rdquo;', $found_total, 'unitourk' ) ),
							(int) $found_total,
							esc_html( $sq )
						);
						?>
					</p>
				<?php else : ?>
					<h2><?php echo esc_html( $title ); ?></h2>
					<?php
					if ( is_product_category() ) {
						$desc = term_description();
						if ( $desc ) { echo '<p>' . wp_kses_post( $desc ) . '</p>'; }
					}
					?>
				<?php endif; ?>
			</div>

			<?php // Local listing search — same behaviour as the global header search, pill styled. ?>
			<form role="search" method="get" class="ut-listing-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="product">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
				<input type="search" name="s" placeholder="Search products&hellip;" value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="Search products">
				<button type="submit">Search</button>
			</form>

			<?php
			// Sort dropdown (submits to page 1 so it can't land on an out-of-range /page/N/).
			if ( woocommerce_products_will_display() || is_product_category() ) {
				$sort_action = is_shop() ? get_permalink( wc_get_page_id( 'shop' ) ) : ( is_product_category() ? get_term_link( get_queried_object() ) : '' );
				if ( is_wp_error( $sort_action ) ) { $sort_action = ''; }
				$current_sort = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';
				$sort_opts = array(
					'menu_order' => 'Default',
					'title'      => 'Name: A to Z',
					'title-desc' => 'Name: Z to A',
					'date'       => 'Newest first',
				);
				?>
				<form class="ut-sort" method="get" action="<?php echo esc_url( $sort_action ); ?>">
					<label class="screen-reader-text" for="ut_orderby">Sort products</label>
					<select name="orderby" id="ut_orderby" onchange="this.form.submit()">
						<?php foreach ( $sort_opts as $k => $label ) : ?>
							<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $current_sort, $k ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</form>
				<?php
			}
			?>
		</div>

		<?php if ( woocommerce_product_loop() ) : ?>
			<?php
			woocommerce_product_loop_start();
			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();
					wc_get_template_part( 'content', 'product' );
				}
			}
			woocommerce_product_loop_end();

			// Pagination — WooCommerce only renders this when there is more than one page.
			woocommerce_pagination();
			?>
		<?php else : ?>
			<div class="ut-empty">
				<?php if ( $is_search ) : ?>
					<p>No results found for &ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;.</p>
					<a href="<?php echo esc_url( function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' ) ); ?>" class="btn btn-ghost">Browse all products</a>
				<?php else : ?>
					<p>No products in this category yet.</p>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-ghost">Back to homepage</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
