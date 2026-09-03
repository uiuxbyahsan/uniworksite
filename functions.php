<?php
/**
 * Unitourk theme functions.
 *
 * Catalog + inquiry model: WooCommerce products carry no visible price, the
 * cart acts as an "inquiry list", and checkout is a slide-out drawer whose
 * form emails the full inquiry to the company inbox.
 *
 * @package Unitourk
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'UNITOURK_VERSION', '1.0.0' );
define( 'UNITOURK_INQUIRY_EMAIL', 'info@unitourk.com' );

/* -------------------------------------------------------------------------
 *  The final 6-category taxonomy (English working copy — confirm with client).
 *  slug => [ name, description, theme image file ]
 * ---------------------------------------------------------------------- */
function unitourk_categories() {
	return array(
		'workwear-uniforms'          => array( 'Workwear & Uniforms',          'Durable workwear, jackets and uniforms for every shift.',   'workwear.jpg' ),
		'safety-shoes'               => array( 'Safety Shoes',                 'Steel-toe and composite protective footwear.',              'footwear.jpg' ),
		'safety-eyewear'             => array( 'Safety Eyewear',               'Impact-rated safety glasses and goggles.',                  'safety-eyewear.jpg' ),
		'safety-gloves'              => array( 'Safety Gloves',                'Cut and impact-resistant protective gloves.',               'safety-gloves.jpg' ),
		'military-tactical-uniforms' => array( 'Military & Tactical Uniforms', 'Field, combat and duty apparel engineered for performance.','military.jpg' ),
		'respiratory-protection'     => array( 'Respiratory Protection',       'Half-mask and full-face respiratory protection.',           'respiratory.jpg' ),
		'safety-protection-accessories' => array( 'Safety Protection & Accessories', 'Helmets, hearing protection and general safety accessories.', 'safety-protection.jpg' ),
		'corporate-office-uniforms'  => array( 'Corporate & Office Uniforms',  'Corporate and formal wear for offices and client teams.',   'office.jpg' ),
	);
}

/** Line icon (SVG) for a category slug — shown in the navy circle badge. */
function unitourk_category_icon( $slug ) {
	$p = array(
		'workwear-uniforms'          => '<path d="M16 3l4 3-3 3-2-1v13H9V8L7 9 4 6l4-3 4 2z"/>',
		'safety-shoes'               => '<path d="M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5z"/>',
		'safety-eyewear'             => '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"/><circle cx="12" cy="12" r="3"/>',
		'safety-gloves'              => '<path d="M7 11V5.5a1.5 1.5 0 0 1 3 0V11M10 11V4.5a1.5 1.5 0 0 1 3 0V11M13 11V6a1.5 1.5 0 0 1 3 0v9a6 6 0 0 1-6 6H9a5 5 0 0 1-4.3-2.5L3 15.5a1.6 1.6 0 0 1 2.6-1.8L7 15"/>',
		'military-tactical-uniforms' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/><path d="M12 1v3M12 20v3M1 12h3M20 12h3"/>',
		'respiratory-protection'     => '<path d="M3 10a9 9 0 0 1 18 0v2a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5z"/><circle cx="8" cy="12" r="1.4"/><circle cx="16" cy="12" r="1.4"/>',
		'safety-protection-accessories' => '<path d="M12 2l8 3v6c0 5-3.5 9-8 11-4.5-2-8-6-8-11V5z"/><path d="M9 12l2 2 4-4"/>',
		'corporate-office-uniforms'  => '<circle cx="12" cy="8" r="4"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0z"/>',
	);
	// New/unknown categories get a neutral tag icon rather than borrowing another category's symbol.
	$default = '<path d="M3 11l8-8 10 10-8 8z"/><circle cx="8.5" cy="8.5" r="1.4"/>';
	$path    = isset( $p[ $slug ] ) ? $p[ $slug ] : $default;
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">' . $path . '</svg>';
}

/**
 * Image URL for a category slug.
 * Priority: the image set on the category in the dashboard
 * (Products → Categories → Image) first, then the bundled theme image.
 */
function unitourk_category_image( $slug ) {
	// 1) Dashboard-set category image, if any.
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term ) {
		$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
		if ( $thumb_id ) {
			$url = wp_get_attachment_image_url( $thumb_id, 'large' );
			if ( $url ) { return $url; }
		}
	}
	// 2) Bundled theme image fallback.
	$cats = unitourk_categories();
	if ( isset( $cats[ $slug ] ) ) {
		return get_template_directory_uri() . '/assets/img/cat/' . $cats[ $slug ][2];
	}
	return get_template_directory_uri() . '/assets/img/cat/office.jpg';
}

/**
 * Categories to show as cards on the homepage.
 * Pulls the live WooCommerce product categories, so ADDING a category in
 * the dashboard automatically adds a card (and deleting one removes it).
 * "Uncategorized" is always hidden. The eight bundled categories keep their
 * curated order; any new category is appended after them, alphabetically.
 *
 * @return WP_Term[]
 */
function unitourk_homepage_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) { return array(); }
	$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) || ! $terms ) { return array(); }

	$by_slug = array();
	foreach ( $terms as $t ) {
		if ( 'uncategorized' === $t->slug ) { continue; } // never feature the default bucket
		$by_slug[ $t->slug ] = $t;
	}

	$ordered = array();
	foreach ( array_keys( unitourk_categories() ) as $slug ) { // curated order first
		if ( isset( $by_slug[ $slug ] ) ) { $ordered[] = $by_slug[ $slug ]; unset( $by_slug[ $slug ] ); }
	}
	$rest = array_values( $by_slug ); // any newly added categories
	usort( $rest, function ( $a, $b ) { return strcasecmp( $a->name, $b->name ); } );

	return array_merge( $ordered, $rest );
}

/**
 * Display name for a category slug.
 * Uses the category name set in the dashboard, falling back to the
 * bundled default. Lets the client rename cards from Products → Categories.
 */
function unitourk_category_name( $slug ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term && $term->name ) { return $term->name; }
	$cats = unitourk_categories();
	return isset( $cats[ $slug ] ) ? $cats[ $slug ][0] : ucwords( str_replace( '-', ' ', $slug ) );
}

/* -------------------------------------------------------------------------
 *  Theme setup
 * ---------------------------------------------------------------------- */
function unitourk_setup() {
	load_theme_textdomain( 'unitourk', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 48, 'flex-width' => true ) );

	// WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'unitourk' ),
		'footer'  => __( 'Footer Menu', 'unitourk' ),
	) );
}
add_action( 'after_setup_theme', 'unitourk_setup' );

/* -------------------------------------------------------------------------
 *  Create the 6 product categories on theme activation (idempotent).
 *  Product-to-category assignment is left to the client (Workwear vs
 *  Office/Corporate Wear split must not be guessed).
 * ---------------------------------------------------------------------- */
function unitourk_seed_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) { return; } // WooCommerce inactive.
	foreach ( unitourk_categories() as $slug => $data ) {
		if ( ! term_exists( $slug, 'product_cat' ) ) {
			wp_insert_term( $data[0], 'product_cat', array(
				'slug'        => $slug,
				'description' => $data[1],
			) );
		}
	}
}
add_action( 'after_switch_theme', 'unitourk_seed_categories' );

/* -------------------------------------------------------------------------
 *  Assets
 * ---------------------------------------------------------------------- */
function unitourk_assets() {
	// Inter (Poppins fallback) from Google Fonts.
	wp_enqueue_style(
		'unitourk-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@500;600;700;800&display=swap',
		array(),
		null
	);
	$css_v = @filemtime( get_template_directory() . '/assets/css/theme.css' ) ?: UNITOURK_VERSION;
	$js_v  = @filemtime( get_template_directory() . '/assets/js/theme.js' ) ?: UNITOURK_VERSION;
	wp_enqueue_style( 'unitourk-main', get_template_directory_uri() . '/assets/css/theme.css', array( 'unitourk-fonts' ), $css_v );

	wp_enqueue_script( 'unitourk-main', get_template_directory_uri() . '/assets/js/theme.js', array( 'jquery' ), $js_v, true );
	wp_localize_script( 'unitourk-main', 'UNITOURK', array(
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'wcAjax'    => class_exists( 'WooCommerce' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
		'nonce'     => wp_create_nonce( 'unitourk_inquiry' ),
		'cartUrl'   => class_exists( 'WooCommerce' ) ? wc_get_cart_url() : '',
	) );
}
add_action( 'wp_enqueue_scripts', 'unitourk_assets' );

/* -------------------------------------------------------------------------
 *  WooCommerce — catalog / inquiry behaviour
 * ---------------------------------------------------------------------- */

// Products have no price but must still be addable to the inquiry list.
add_filter( 'woocommerce_is_purchasable', '__return_true' );
// Variations use their own purchasable/active filters — force them too so
// variable products work in the no-price inquiry flow.
add_filter( 'woocommerce_variation_is_purchasable', '__return_true' );
add_filter( 'woocommerce_variation_is_active', '__return_true' );
add_filter( 'woocommerce_variation_is_visible', '__return_true' ); // no price required

// Never surface pricing anywhere.
add_filter( 'woocommerce_get_price_html', '__return_empty_string', 100 );
add_filter( 'woocommerce_cart_item_price', '__return_empty_string', 100 );
add_filter( 'woocommerce_cart_item_subtotal', '__return_empty_string', 100 );
add_filter( 'woocommerce_cart_subtotal', '__return_empty_string', 100 );
add_filter( 'woocommerce_cart_total', '__return_empty_string', 100 );

// Grid: 3 wider columns, 9 per page (3 rows). Pagination then appears automatically.
add_filter( 'loop_shop_columns', function () { return 3; }, 20 );
add_filter( 'loop_shop_per_page', function () { return 9; }, 20 );

// Button copy — real WooCommerce cart, price hidden. "Add to Cart" on cards/product;
// only the final checkout submit is reworded (below) since that's the no-payment step.
define( 'UNITOURK_CART_BTN', 'Add to Cart' );
add_filter( 'woocommerce_product_add_to_cart_text',        function () { return UNITOURK_CART_BTN; }, 20 );
add_filter( 'woocommerce_product_single_add_to_cart_text', function () { return UNITOURK_CART_BTN; }, 20 );

// Checkout submit wording — this is a request, not a paid transaction.
add_filter( 'woocommerce_order_button_text', function () { return 'Submit Request'; }, 20 );

// Show the product thumbnail next to each item in the checkout order review.
add_filter( 'woocommerce_cart_item_name', function ( $name, $cart_item, $key ) {
	if ( is_checkout() && ! is_cart() && ! empty( $cart_item['data'] ) ) {
		$thumb = $cart_item['data']->get_image( 'woocommerce_thumbnail' );
		return '<span class="ut-co-item">' . $thumb . '<span class="ut-co-name">' . $name . '</span></span>';
	}
	return $name;
}, 10, 3 );

// Simplify checkout billing: one street line, no ZIP, no phone (inquiry model).
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
	unset( $fields['billing']['billing_address_2'] ); // one street field only
	unset( $fields['billing']['billing_postcode'] );  // no ZIP
	unset( $fields['billing']['billing_phone'] );      // no phone
	if ( isset( $fields['billing']['billing_address_1'] ) ) {
		$fields['billing']['billing_address_1']['placeholder'] = 'Street address';
	}
	return $fields;
}, 20 );
// Postcode isn't a required field for our billing once removed.
add_filter( 'woocommerce_default_address_fields', function ( $f ) {
	if ( isset( $f['postcode'] ) ) { $f['postcode']['required'] = false; }
	return $f;
} );

// Enable AJAX add-to-cart on archives so the drawer/mini-cart opens without a reload.
add_filter( 'option_woocommerce_enable_ajax_add_to_cart', function () { return 'yes'; } );
add_filter( 'default_option_woocommerce_enable_ajax_add_to_cart', function () { return 'yes'; } );

// Refresh the drawer's item list after every AJAX add-to-cart via Woo fragments.
add_filter( 'woocommerce_add_to_cart_fragments', 'unitourk_cart_fragments' );
function unitourk_cart_fragments( $fragments ) {
	ob_start();
	unitourk_drawer_items();
	$fragments['div.ut-drawer-body'] = ob_get_clean();

	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$fragments['span.ut-cart-count'] = '<span class="ut-cart-count' . ( $count ? ' has' : '' ) . '">' . esc_html( $count ) . '</span>';
	return $fragments;
}

/** Render the inquiry-list body (product name + qty controls, no price). */
function unitourk_drawer_items() {
	$cart = function_exists( 'WC' ) ? WC()->cart : null;
	echo '<div class="ut-drawer-body">';
	if ( ! $cart || $cart->is_empty() ) {
		echo '<div class="ut-drawer-empty">'
			. '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1.6"/><circle cx="18" cy="21" r="1.6"/><path d="M2 3h3l2.6 13.4a1.6 1.6 0 0 0 1.6 1.3h9.3a1.6 1.6 0 0 0 1.6-1.3L23 7H6"/></svg>'
			. '<p>Your inquiry list is empty.</p>'
			. '<span>Browse the catalog and add the items you need a quote for.</span>'
			. '</div>';
		echo '</div>';
		return;
	}
	foreach ( $cart->get_cart() as $key => $item ) {
		$p = $item['data'];
		if ( ! $p ) { continue; }
		$thumb = $p->get_image( 'woocommerce_thumbnail' );
		echo '<div class="ut-line" data-key="' . esc_attr( $key ) . '">';
		echo '<div class="ut-line-img">' . $thumb . '</div>';
		echo '<div class="ut-line-info">';
		echo '<b>' . esc_html( $p->get_name() ) . '</b>';
		echo '<div class="ut-qty">';
		echo '<button type="button" class="ut-qminus" aria-label="Decrease quantity">&minus;</button>';
		echo '<span class="ut-qnum">' . esc_html( $item['quantity'] ) . '</span>';
		echo '<button type="button" class="ut-qplus" aria-label="Increase quantity">+</button>';
		echo '</div>';
		echo '</div>';
		echo '<button type="button" class="ut-remove" aria-label="Remove item">'
			. '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>';
		echo '</div>';
	}
	echo '</div>';
}

/* -------------------------------------------------------------------------
 *  AJAX — quantity / remove / submit inquiry
 * ---------------------------------------------------------------------- */

// Update quantity or remove an item, return refreshed fragments.
add_action( 'wp_ajax_unitourk_update_cart', 'unitourk_update_cart' );
add_action( 'wp_ajax_nopriv_unitourk_update_cart', 'unitourk_update_cart' );
function unitourk_update_cart() {
	check_ajax_referer( 'unitourk_inquiry', 'nonce' );
	$key = isset( $_POST['key'] ) ? wc_clean( wp_unslash( $_POST['key'] ) ) : '';
	$qty = isset( $_POST['qty'] ) ? absint( $_POST['qty'] ) : 0;
	if ( $key && WC()->cart ) {
		if ( $qty > 0 ) {
			WC()->cart->set_quantity( $key, $qty, true );
		} else {
			WC()->cart->remove_cart_item( $key );
		}
	}
	WC_AJAX::get_refreshed_fragments();
}

// Submit the inquiry: email the full list + contact details, then empty the cart.
add_action( 'wp_ajax_unitourk_inquiry', 'unitourk_submit_inquiry' );
add_action( 'wp_ajax_nopriv_unitourk_inquiry', 'unitourk_submit_inquiry' );
function unitourk_submit_inquiry() {
	check_ajax_referer( 'unitourk_inquiry', 'nonce' );

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$company = sanitize_text_field( wp_unslash( $_POST['company'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$notes   = sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) );

	if ( ! $name || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please provide your name and a valid email address.' ) );
	}
	if ( ! WC()->cart || WC()->cart->is_empty() ) {
		wp_send_json_error( array( 'message' => 'Your inquiry list is empty.' ) );
	}

	$lines = array();
	foreach ( WC()->cart->get_cart() as $item ) {
		$p = $item['data'];
		if ( $p ) {
			$sku    = $p->get_sku();
			$lines[] = '  • ' . $p->get_name() . ( $sku ? " (SKU: {$sku})" : '' ) . '  ×  ' . $item['quantity'];
		}
	}

	$body  = "New product inquiry from unitourk.com\n";
	$body .= "----------------------------------------\n\n";
	$body .= "CONTACT\n";
	$body .= "Name:    {$name}\n";
	$body .= "Company: " . ( $company ?: '—' ) . "\n";
	$body .= "Email:   {$email}\n";
	$body .= "Phone:   " . ( $phone ?: '—' ) . "\n\n";
	$body .= "ITEMS REQUESTED\n";
	$body .= implode( "\n", $lines ) . "\n\n";
	if ( $notes ) {
		$body .= "NOTES\n{$notes}\n\n";
	}
	$body .= "----------------------------------------\n";
	$body .= "Submitted: " . current_time( 'mysql' ) . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);
	$subject = 'Product Inquiry — ' . $name . ( $company ? ' (' . $company . ')' : '' );

	$sent = wp_mail( UNITOURK_INQUIRY_EMAIL, $subject, $body, $headers );

	if ( $sent ) {
		WC()->cart->empty_cart();
		wp_send_json_success( array( 'message' => 'Thank you — your inquiry has been sent. Our team will be in touch shortly.' ) );
	}
	wp_send_json_error( array( 'message' => 'Something went wrong sending your inquiry. Please email ' . UNITOURK_INQUIRY_EMAIL . ' directly.' ) );
}

/* -------------------------------------------------------------------------
 *  Breadcrumb helper — Home / Category / Product
 * ---------------------------------------------------------------------- */
function unitourk_breadcrumb() {
	$items = array( array( 'Home', home_url( '/' ) ) );

	if ( function_exists( 'is_product' ) && is_product() ) {
		global $post;
		$terms = get_the_terms( $post->ID, 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$t = array_shift( $terms );
			$items[] = array( $t->name, get_term_link( $t ) );
		}
		$items[] = array( get_the_title(), '' );
	} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$items[] = array( single_term_title( '', false ), '' );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$items[] = array( 'Products', '' );
	} else {
		$items[] = array( get_the_title(), '' );
	}

	echo '<nav class="ut-breadcrumb" aria-label="Breadcrumb"><div class="container">';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $it ) {
		if ( $i === $last || empty( $it[1] ) ) {
			echo '<span aria-current="page">' . esc_html( $it[0] ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $it[1] ) . '">' . esc_html( $it[0] ) . '</a>';
			echo '<svg class="sep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>';
		}
	}
	echo '</div></nav>';
}

/** Fix the broken "Related Products" heading (clean English, no legacy string). */
add_filter( 'woocommerce_product_related_products_heading', function () { return 'Related Products'; } );

/* -------------------------------------------------------------------------
 *  Safety net: never show a 404 for an out-of-range paginated listing.
 *  Applying a filter (or a bookmarked deep page) can land on /page/N/ that
 *  no longer exists; instead of a hard 404, send the visitor to page 1 of
 *  that same listing, preserving any filter/sort query args.
 * ---------------------------------------------------------------------- */
add_action( 'template_redirect', 'unitourk_redirect_paged_404' );
function unitourk_redirect_paged_404() {
	if ( ! is_404() ) { return; }
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	if ( ! preg_match( '#/page/\d+/?$#', $path ) ) { return; } // only paginated URLs
	$base_path = preg_replace( '#/page/\d+/?$#', '/', $path );
	$qs   = wp_parse_url( $uri, PHP_URL_QUERY );
	$target = home_url( $base_path ) . ( $qs ? '?' . $qs : '' );
	if ( untrailingslashit( $target ) === untrailingslashit( home_url( $uri ) ) ) { return; } // no loop
	wp_safe_redirect( $target, 302 );
	exit;
}

/* -------------------------------------------------------------------------
 *  Catalog sorting (replaces the brand filter). No prices on this catalog,
 *  so we expose Default / Name A–Z / Name Z–A / Newest and drop price sorts.
 * ---------------------------------------------------------------------- */
add_filter( 'woocommerce_get_catalog_ordering_args', function ( $args ) {
	$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : '';
	if ( 'title' === $orderby ) { $args['orderby'] = 'title'; $args['order'] = 'ASC'; }
	if ( 'title-desc' === $orderby ) { $args['orderby'] = 'title'; $args['order'] = 'DESC'; }
	return $args;
} );

/* -------------------------------------------------------------------------
 *  Search: always show the results grid — never auto-jump to a single
 *  product's page, even when there is exactly one match.
 * ---------------------------------------------------------------------- */
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );

/* -------------------------------------------------------------------------
 *  Order received / thank-you page: Apple-style green checkmark success state
 *  shown above the order details.
 * ---------------------------------------------------------------------- */
add_action( 'woocommerce_before_thankyou', 'unitourk_thankyou_success', 5 );
function unitourk_thankyou_success( $order_id ) {
	$order = wc_get_order( $order_id );
	$num   = $order ? $order->get_order_number() : '';
	?>
	<div class="ut-success">
		<svg class="ut-success-check" viewBox="0 0 56 56" aria-hidden="true">
			<circle class="ut-success-ring" cx="28" cy="28" r="25"/>
			<path class="ut-success-tick" d="M16 29l8 8 16-17"/>
		</svg>
		<h2 class="ut-success-title">Request sent!</h2>
		<p class="ut-success-sub">Your inquiry has been submitted<?php echo $num ? ' &mdash; request <strong>#' . esc_html( $num ) . '</strong>' : ''; ?>. Our team will review it and be in touch shortly.</p>
	</div>
	<?php
}

/** Distinct brands present in a product category (for the filter dropdown). */
function unitourk_category_brands( $term_id ) {
	$ids = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'post_status'    => 'publish',
		'tax_query'      => array( array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $term_id ) ),
	) );
	$brands = array();
	foreach ( $ids as $id ) {
		$b = get_post_meta( $id, '_ut_brand', true );
		if ( $b ) { $brands[ $b ] = true; }
	}
	$out = array_keys( $brands );
	sort( $out );
	return $out;
}

/** Related products count. */
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
} );

/* -------------------------------------------------------------------------
 *  Standalone Contact form ([unitourk_contact]) — general inquiries,
 *  separate from the cart/checkout order flow. Emails info@unitourk.com.
 * ---------------------------------------------------------------------- */
add_shortcode( 'unitourk_contact', 'unitourk_contact_form' );
function unitourk_contact_form() {
	ob_start(); ?>
	<div class="ut-contact-wrap">
		<p class="ut-contact-lead">Have a question about our range, certifications or bulk supply? Send us a message and our team will get back to you.</p>
		<form class="ut-contact-form" novalidate>
			<label>Full name<input type="text" name="name" placeholder="Your name" required></label>
			<label>Email address<input type="email" name="email" placeholder="you@company.com" required></label>
			<label>Message<textarea name="message" rows="6" placeholder="How can we help?" required></textarea></label>
			<div class="ut-form-msg" role="status" aria-live="polite"></div>
			<button type="submit" class="btn btn-primary ut-contact-submit">Send Message
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}
add_action( 'wp_ajax_unitourk_contact', 'unitourk_contact_submit' );
add_action( 'wp_ajax_nopriv_unitourk_contact', 'unitourk_contact_submit' );
function unitourk_contact_submit() {
	check_ajax_referer( 'unitourk_inquiry', 'nonce' );
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$company = sanitize_text_field( wp_unslash( $_POST['company'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_send_json_error( array( 'message' => 'Please provide your name, a valid email and a message.' ) );
	}
	$body  = "New contact enquiry from unitourk.com\n";
	$body .= "----------------------------------------\n\n";
	$body .= "Name:    {$name}\n";
	$body .= "Company: " . ( $company ?: '—' ) . "\n";
	$body .= "Email:   {$email}\n";
	$body .= "Phone:   " . ( $phone ?: '—' ) . "\n\n";
	$body .= "MESSAGE\n{$message}\n\n";
	$body .= "----------------------------------------\nSubmitted: " . current_time( 'mysql' ) . "\n";
	$headers = array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' );

	if ( wp_mail( UNITOURK_INQUIRY_EMAIL, 'Contact enquiry — ' . $name . ( $company ? ' (' . $company . ')' : '' ), $body, $headers ) ) {
		wp_send_json_success( array( 'message' => 'Thank you — your message has been sent. We will be in touch shortly.' ) );
	}
	wp_send_json_error( array( 'message' => 'Something went wrong. Please email ' . UNITOURK_INQUIRY_EMAIL . ' directly.' ) );
}

/* -------------------------------------------------------------------------
 *  One-time DEMO product seed (local review only).
 *  Creates clearly-labelled "Sample —" products so the catalog, pagination,
 *  product template and inquiry drawer can be reviewed immediately. Runs only
 *  when there are zero products. Delete them anytime: Products → select all →
 *  Move to Trash. Real products / category assignment are the client's to add.
 * ---------------------------------------------------------------------- */
add_action( 'after_switch_theme', 'unitourk_seed_demo_products' );
function unitourk_seed_demo_products() {
	if ( ! function_exists( 'wc_get_products' ) ) { return; }
	$existing = wc_get_products( array( 'limit' => 1, 'return' => 'ids', 'status' => array( 'publish', 'draft' ) ) );
	if ( ! empty( $existing ) ) { return; } // don't touch a store that already has products.

	// safety-shoes gets 10 items so pagination (8 + 2) is testable.
	$map = array(
		'safety-shoes'               => array( 'Guardian S3 Safety Boot', 'Traxion Composite Boot', 'Ironclad Steel-Toe Boot', 'Summit Hiker Safety Boot', 'Volt ESD Safety Shoe', 'Forge Welder Boot', 'Terra Grip Work Boot', 'Apex Metatarsal Boot', 'Sentinel Chelsea Boot', 'Nova Lightweight Trainer' ),
		'workwear-uniforms'          => array( 'ProFlex Work Jacket', 'Hi-Vis Bomber Jacket', 'Rugged Cargo Trousers' ),
		'safety-eyewear'             => array( 'ClearVue Safety Goggles', 'Contour Wraparound Glasses', 'ArcShield Face Visor' ),
		'safety-gloves'              => array( 'CutGuard Level 5 Glove', 'ThermoGrip Winter Glove', 'Impact Defender Glove' ),
		'military-tactical-uniforms' => array( 'Field Combat Uniform', 'Tactical Softshell Jacket', 'Ripstop Cargo Pants' ),
		'respiratory-protection'     => array( 'AirPro Half-Mask Respirator', 'FullVue Face Respirator', 'DustShield FFP3 Mask' ),
		'safety-protection-accessories' => array( 'Site Safety Helmet', 'Ear Defender Set', 'Hi-Vis Accessory Kit' ),
		'corporate-office-uniforms'  => array( 'Executive Tailored Blazer', 'Corporate Oxford Shirt', 'Slim-Fit Office Trousers' ),
	);
	$catimg = array(
		'safety-shoes' => 'footwear.jpg', 'workwear-uniforms' => 'workwear.jpg',
		'safety-eyewear' => 'safety-eyewear.jpg', 'safety-gloves' => 'safety-gloves.jpg',
		'military-tactical-uniforms' => 'military.jpg', 'respiratory-protection' => 'respiratory.jpg',
		'safety-protection-accessories' => 'safety-protection.jpg', 'corporate-office-uniforms' => 'office.jpg',
	);

	foreach ( $map as $slug => $names ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		$att  = unitourk_sideload_theme_image( $catimg[ $slug ] );
		foreach ( $names as $n ) {
			$p = new WC_Product_Simple();
			$p->set_name( 'Sample — ' . $n );
			$p->set_status( 'publish' );
			$p->set_catalog_visibility( 'visible' );
			$p->set_short_description( 'Demo item for layout review — replace with real product content.' );
			$p->set_description( "This is a sample product created so the template can be reviewed.\n\nReplace with real specifications: materials, sizes, standards met, and features. Pricing is intentionally hidden — this catalog runs on an inquiry model." );
			if ( $term ) { $p->set_category_ids( array( $term->term_id ) ); }
			if ( $att )  { $p->set_image_id( $att ); }
			$p->save();
		}
	}
}

/** Copy a theme image into the media library and return its attachment id. */
function unitourk_sideload_theme_image( $file ) {
	$src = get_template_directory() . '/assets/img/cat/' . $file;
	if ( ! file_exists( $src ) ) { return 0; }
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$upload = wp_upload_bits( 'sample-' . $file, null, file_get_contents( $src ) );
	if ( ! empty( $upload['error'] ) ) { return 0; }
	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment( array(
		'post_mime_type' => $type['type'],
		'post_title'     => 'Sample ' . pathinfo( $file, PATHINFO_FILENAME ),
		'post_status'    => 'inherit',
	), $upload['file'] );
	if ( ! is_wp_error( $id ) ) {
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	}
	return $id;
}
