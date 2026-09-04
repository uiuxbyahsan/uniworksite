<?php
/**
 * Contact banner + footer + inquiry drawer + mobile nav.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$logo = get_template_directory_uri() . '/assets/img/logo-footer.webp?v=' . (int) @filemtime( get_template_directory() . '/assets/img/logo-footer.webp' );
$cats = unitourk_categories();
$shop = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' );
$contact_page = get_page_by_path( 'contact' );
$contact_url  = $contact_page ? get_permalink( $contact_page ) : home_url( '/' );
$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/' );
?>

<!-- Footer -->
<footer class="footer-main" id="footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-brand">
				<div class="fbrand"><img src="<?php echo esc_url( $logo ); ?>" alt="Unitourk"></div>
				<p>Manufacturing &amp; Trading Group. Certified workwear and safety equipment supplied to industrial partners worldwide.</p>
				<!-- Facebook/Telegram: confirm real profile URLs with client before linking -->
				<div class="footer-social">
					<a href="https://www.instagram.com/unitourk/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor" stroke="none"/></svg></a>
					<a href="#" aria-label="Telegram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M22 3L2 10.4l6.1 2.1L20 5.5 10.6 14v5l3-3.6 4.4 3.2z"/></svg></a>
					<a href="https://wa.me/46764216566" target="_blank" rel="noopener" aria-label="WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.4 5 5.1-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20zm4.5-5.9c-.2-.1-1.4-.7-1.7-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.6 6.6 0 0 1-1.9-1.2 7.3 7.3 0 0 1-1.4-1.7c-.1-.2 0-.4.1-.5l.4-.5.3-.5v-.4l-.8-1.9c-.2-.5-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3A2.8 2.8 0 0 0 6.5 10a4.9 4.9 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c2 .8 2 .5 2.4.5a2.5 2.5 0 0 0 1.6-1.1 2 2 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3z"/></svg></a>
				</div>
			</div>
			<div class="footer-col">
				<h4>Categories</h4>
				<ul>
					<?php foreach ( $cats as $slug => $data ) :
						$term = get_term_by( 'slug', $slug, 'product_cat' );
						$link = $term ? get_term_link( $term ) : $shop; ?>
						<li><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $data[0] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="footer-col">
				<h4>Company</h4>
				<ul>
					<li><a href="<?php echo esc_url( ( $ut_f_about = get_page_by_path( 'about-us' ) ) ? get_permalink( $ut_f_about ) : home_url( '/#about' ) ); ?>">About Us</a></li>
					<li><a href="<?php echo esc_url( home_url( '/#testimonials' ) ); ?>">Partners</a></li>
					<li><a href="<?php echo esc_url( $shop ); ?>">Catalog</a></li>
					<li><a href="<?php echo esc_url( $contact_url ); ?>">Contact</a></li>
					<li><a href="<?php echo esc_url( $contact_url ); ?>">Request a Quote</a></li>
				</ul>
			</div>
			<div class="footer-col footer-contact">
				<h4>Get in Touch</h4>
				<?php
				$ut_offices = array(
					array( 'Head Office &middot; Sweden', '+46 764 216 566', '46764216566' ),
					array( 'Office &middot; Iran',          '+98 912 742 7268', '989127427268' ),
					array( 'Office &middot; Erbil, Iraq',   '+964 751 730 8376', '9647517308376' ),
				);
				$ut_phone_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>';
				$ut_wa_svg = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.4 5 5.1-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20zm4.5-5.9c-.2-.1-1.4-.7-1.7-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.6 6.6 0 0 1-1.9-1.2 7.3 7.3 0 0 1-1.4-1.7c-.1-.2 0-.4.1-.5l.4-.5.3-.5v-.4l-.8-1.9c-.2-.5-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3A2.8 2.8 0 0 0 6.5 10a4.9 4.9 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c2 .8 2 .5 2.4.5a2.5 2.5 0 0 0 1.6-1.1 2 2 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3z"/></svg>';
				?>
				<div class="ut-offices">
					<?php foreach ( $ut_offices as $o ) : $tel = preg_replace( '/[^0-9+]/', '', $o[1] ); ?>
						<div class="ut-office">
							<span class="ut-office-ico"><?php echo $ut_phone_svg; // phpcs:ignore ?></span>
							<div class="ut-office-body">
								<b><?php echo wp_kses_post( $o[0] ); ?></b>
								<a class="ut-office-tel" href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $o[1] ); ?></a>
								<a class="ut-office-wa" href="https://wa.me/<?php echo esc_attr( $o[2] ); ?>" target="_blank" rel="noopener"><?php echo $ut_wa_svg; // phpcs:ignore ?>Chat on WhatsApp</a>
							</div>
						</div>
					<?php endforeach; ?>
					<div class="ut-office ut-office--meta">
						<span class="ut-office-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg></span>
						<div class="ut-office-body"><a class="ut-office-tel" href="mailto:<?php echo esc_attr( UNITOURK_INQUIRY_EMAIL ); ?>"><?php echo esc_html( UNITOURK_INQUIRY_EMAIL ); ?></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bar">
		<div class="container">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Unitourk</p>
			<div class="legal"><a href="#">Privacy Policy</a><a href="#">Terms of Service</a></div>
		</div>
	</div>
</footer>

<!-- Cart drawer (mini-cart side panel) -->
<div class="ut-drawer-overlay" id="utDrawer" aria-hidden="true">
	<aside class="ut-drawer" role="dialog" aria-modal="true" aria-label="Your cart">
		<header class="ut-drawer-head">
			<h3>Your Cart</h3>
			<button class="ut-drawer-close" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
		</header>
		<?php unitourk_drawer_items(); ?>
		<div class="ut-drawer-foot">
			<p class="ut-form-intro">No pricing shown. Submit your request and our team will reply with a quote. This is not a paid transaction.</p>
			<a href="<?php echo esc_url( $checkout_url ); ?>" class="btn btn-primary ut-checkout-btn">Proceed to Checkout
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
			<button type="button" class="ut-continue ut-drawer-close">Continue browsing</button>
		</div>
	</aside>
</div>

<!-- Mobile nav -->
<div class="mobile-nav" id="mobileNav">
	<div class="mobile-panel">
		<div class="mp-head"><b>UNI<span>TOURK</span></b>
			<button class="icon-btn" id="closeMenu" aria-label="Close menu"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
		</div>
		<nav>
			<?php foreach ( $cats as $slug => $data ) :
				$term = get_term_by( 'slug', $slug, 'product_cat' );
				$link = $term ? get_term_link( $term ) : $shop; ?>
				<a href="<?php echo esc_url( $link ); ?>" data-close><?php echo esc_html( $data[0] ); ?></a>
			<?php endforeach; ?>
			<?php
			$ut_m_about   = get_page_by_path( 'about-us' );
			$ut_m_contact = get_page_by_path( 'contact' );
			$ut_m_track   = get_page_by_path( 'track-order' );
			if ( ! is_user_logged_in() && $ut_m_track ) : ?>
				<a href="<?php echo esc_url( get_permalink( $ut_m_track ) ); ?>" data-close>Track Order</a>
			<?php endif; ?>
			<a href="<?php echo esc_url( $ut_m_about ? get_permalink( $ut_m_about ) : home_url( '/#about' ) ); ?>" data-close>About</a>
			<a href="<?php echo esc_url( $ut_m_contact ? get_permalink( $ut_m_contact ) : home_url( '/#contact' ) ); ?>" data-close>Contact Us</a>
		</nav>
		<div class="mp-actions">
			<a href="#" class="btn btn-primary ut-drawer-open" data-close>Request a Quote</a>
		</div>
	</div>
</div>

<!-- Language switcher (Google Translate, no API key) -->
<script>
function googleTranslateElementInit(){
	new google.translate.TranslateElement({pageLanguage:'en',includedLanguages:'en,ru,fa,ar,es,sv,zh-CN,tr',autoDisplay:false},'google_translate_element');
}
(function(){
	var s=document.createElement('script');
	s.src='https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
	document.head.appendChild(s);

	function readLang(){var m=document.cookie.match(/googtrans=\/[^\/]+\/([^;]+)/);return m?decodeURIComponent(m[1]):'en';}
	function setLang(l){
		var host=location.hostname;
		var val='/en/'+l;
		document.cookie='googtrans='+val+';path=/';
		document.cookie='googtrans='+val+';path=/;domain='+host;
		document.cookie='googtrans='+val+';path=/;domain=.'+host;
		if(l==='en'){ // clear translation
			document.cookie='googtrans=;path=/;expires=Thu, 01 Jan 1970 00:00:00 GMT';
			document.cookie='googtrans=;path=/;domain='+host+';expires=Thu, 01 Jan 1970 00:00:00 GMT';
			document.cookie='googtrans=;path=/;domain=.'+host+';expires=Thu, 01 Jan 1970 00:00:00 GMT';
		}
		location.reload();
	}
	document.addEventListener('DOMContentLoaded',function(){
		var sel=document.getElementById('utLang');
		if(!sel)return;
		sel.value=readLang();
		sel.addEventListener('change',function(){setLang(this.value);});
	});
})();
</script>
<?php wp_footer(); ?>
</body>
</html>
