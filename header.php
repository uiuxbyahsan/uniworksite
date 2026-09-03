<?php
/**
 * Header + primary navigation (mega-menu from the 6 categories).
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$logo = get_template_directory_uri() . '/assets/img/logo-nav.webp?v=' . (int) @filemtime( get_template_directory() . '/assets/img/logo-nav.webp' );
$cats = unitourk_categories();
$shop = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' );
$contact_page = get_page_by_path( 'contact' );
$contact_url  = $contact_page ? get_permalink( $contact_page ) : home_url( '/#footer' );
$track_page   = get_page_by_path( 'track-order' );
$track_url    = $track_page ? get_permalink( $track_page ) : home_url( '/' );
$about_page   = get_page_by_path( 'about-us' );
$about_url    = $about_page ? get_permalink( $about_page ) : home_url( '/#about' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="ltr">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'ltr' ); ?>>
<?php wp_body_open(); ?>

<!-- Top bar -->
<div class="topbar">
	<div class="container">
		<div class="topbar-info">
			<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.7 2.1Z"/></svg><a href="tel:+46764216566">+46 764 216 566</a></span>
			<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg><a href="mailto:<?php echo esc_attr( UNITOURK_INQUIRY_EMAIL ); ?>"><?php echo esc_html( UNITOURK_INQUIRY_EMAIL ); ?></a></span>
		</div>
		<div class="topbar-right"><span class="topbar-tag">Certified to EN&nbsp;ISO Global Standards &middot; Sweden &middot; Iran &middot; Iraq</span><div class="ut-lang"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg><select id="utLang" aria-label="Select language"><option value="en">English</option><option value="ru">Русский</option><option value="fa">فارسی</option><option value="ar">العربية</option><option value="es">Español</option><option value="sv">Svenska</option><option value="zh-CN">中文</option><option value="tr">Türkçe</option></select></div></div><div id="google_translate_element" aria-hidden="true"></div>
	</div>
</div>

<!-- Header -->
<header class="site-header" id="header">
	<div class="container">
		<div class="nav">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" aria-label="Unitourk home">
				<img class="brand-logo" src="<?php echo esc_url( $logo ); ?>" alt="Unitourk Manufacturing &amp; Trading Group">

			</a>

			<ul class="nav-links">
				<li class="has-mega">
					<button aria-expanded="false" aria-haspopup="true">Products
						<svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
					</button>
					<div class="mega" role="menu">
						<div class="mega-inner">
							<?php foreach ( $cats as $slug => $data ) :
								$term = get_term_by( 'slug', $slug, 'product_cat' );
								$link = $term ? get_term_link( $term ) : $shop; ?>
								<a class="mega-item" href="<?php echo esc_url( $link ); ?>" role="menuitem">
									<span class="m-ico"><img src="<?php echo esc_url( unitourk_category_image( $slug ) ); ?>" alt=""></span>
									<span class="m-txt"><b><?php echo esc_html( $data[0] ); ?></b><span><?php echo esc_html( $data[1] ); ?></span></span>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</li>
				<?php if ( ! is_user_logged_in() ) : ?>
					<li><a href="<?php echo esc_url( $track_url ); ?>">Track Order</a></li>
				<?php endif; ?>
				<li><a href="<?php echo esc_url( $about_url ); ?>">About</a></li>
				<li><a href="<?php echo esc_url( $contact_url ); ?>">Contact Us</a></li>
			</ul>

			<div class="nav-actions">
				<?php $acct = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/' ); ?>
				<span class="auth">
					<?php if ( is_user_logged_in() ) :
						$ut_user = wp_get_current_user();
						$ut_name = $ut_user->first_name ? $ut_user->first_name : $ut_user->display_name; ?>
						<a class="auth-user" href="<?php echo esc_url( $acct ); ?>" title="My account">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0z"/></svg>
							<span><?php echo esc_html( $ut_name ); ?></span>
						</a>
						<span>/</span>
						<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">Logout</a>
					<?php else : ?>
						<a href="<?php echo esc_url( $acct ); ?>">Login</a><span>/</span><a href="<?php echo esc_url( $acct ); ?>">Register</a>
					<?php endif; ?>
				</span>
				<button class="icon-btn" aria-label="Search" id="utSearchToggle">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
				</button>
				<button class="icon-btn ut-drawer-open" aria-label="Open inquiry list">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1.6"/><circle cx="18" cy="21" r="1.6"/><path d="M2 3h3l2.6 13.4a1.6 1.6 0 0 0 1.6 1.3h9.3a1.6 1.6 0 0 0 1.6-1.3L23 7H6"/></svg>
					<?php $count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0; ?>
					<span class="ut-cart-count<?php echo $count ? ' has' : ''; ?>"><?php echo esc_html( $count ); ?></span>
				</button>
				<button class="icon-btn hamburger" id="openMenu" aria-label="Open menu">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
				</button>
			</div>
		</div>
	</div>
	<form role="search" method="get" class="ut-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="container">
			<input type="hidden" name="post_type" value="product">
			<input type="search" name="s" placeholder="Search the catalog&hellip;" aria-label="Search products">
			<button type="submit">Search</button>
		</div>
	</form>
</header>
